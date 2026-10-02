#!/usr/bin/env python3
"""
Importe ou met à jour les formations depuis tools/formations.csv.

Le CSV porte la carte de chaque formation (slug, nom, niveau, durée, visuel,
accroche) ; le programme détaillé est dans tools/formations/<slug>.md, en
Markdown réduit — paragraphes, « ## Titre », listes « - », **gras** — converti
en blocs pour que la cliente puisse le retoucher dans l'éditeur.

    python3 tools/importer-formations.py            # applique
    python3 tools/importer-formations.py --dry-run  # montre sans écrire

Idempotent : la recherche se fait par slug, un contenu ou une méta inchangés ne
sont pas réécrits, et un visuel n'est réimporté que si son contenu a changé
(empreinte MD5 conservée en méta). L'ordre des lignes est l'ordre d'affichage.
Une formation publiée qui n'est plus dans le CSV repasse en brouillon : elle
n'est jamais supprimée. La commande WP-CLI se surcharge par la variable WP,
comme pour tools/importer-catalogue.py.

Les champs vides effacent la méta correspondante : un niveau ou une durée qui
ne sont pas confirmés ne s'affichent pas.
"""

import argparse
import csv
import hashlib
import html
import json
import os
import pathlib
import re
import shlex
import subprocess
import sys
import tempfile

RACINE = pathlib.Path(__file__).resolve().parent.parent
FORMATIONS = RACINE / "tools" / "formations.csv"
PROGRAMMES = RACINE / "tools" / "formations"
MEDIAS = RACINE / "tools" / "medias" / "formations"
WP = shlex.split(os.environ.get("WP", "wp"))

METAS = {
    "niveau": "_fati_niveau",
    "duree": "_fati_duree",
}


def wp(*args, check=True):
    # Compatible Python 3.6 (serveur mutualisé) : pas de capture_output ni de text=.
    resultat = subprocess.run([*WP, *args], stdout=subprocess.PIPE, stderr=subprocess.PIPE, universal_newlines=True, cwd=str(RACINE))
    if check and resultat.returncode != 0:
        sys.exit(f"wp {' '.join(args[:3])}… : {resultat.stderr.strip() or resultat.stdout.strip()}")
    return resultat.stdout.strip()


def en_ligne(texte):
    texte = html.escape(texte.strip(), quote=False)
    return re.sub(r"\*\*(.+?)\*\*", r"<strong>\1</strong>", texte)


def blocs(markdown):
    """Markdown réduit → blocs de l'éditeur (paragraphe, titre, liste)."""
    sortie, puces = [], []

    def vider_puces():
        if puces:
            items = "\n\n".join(f"<!-- wp:list-item -->\n<li>{p}</li>\n<!-- /wp:list-item -->" for p in puces)
            sortie.append(f'<!-- wp:list -->\n<ul class="wp-block-list">{items}</ul>\n<!-- /wp:list -->')
            puces.clear()

    for ligne in markdown.splitlines():
        ligne = ligne.strip()
        if ligne.startswith("- "):
            puces.append(en_ligne(ligne[2:]))
            continue
        vider_puces()
        if not ligne:
            continue
        if ligne.startswith("## "):
            sortie.append(f'<!-- wp:heading -->\n<h2 class="wp-block-heading">{en_ligne(ligne[3:])}</h2>\n<!-- /wp:heading -->')
        else:
            sortie.append(f"<!-- wp:paragraph -->\n<p>{en_ligne(ligne)}</p>\n<!-- /wp:paragraph -->")
    vider_puces()
    return "\n\n".join(sortie)


def poser_meta(post_id, cle, valeur, dry):
    valeur = valeur.strip()
    actuelle = wp("post", "meta", "get", post_id, cle, check=False)
    if valeur == actuelle:
        return
    if dry:
        print(f"      {cle} ← {valeur!r}" if valeur else f"      {cle} effacée")
        return
    if valeur:
        wp("post", "meta", "update", post_id, cle, valeur)
    else:
        wp("post", "meta", "delete", post_id, cle)


def poser_visuel(post_id, nom, fichier, dry):
    if not fichier.is_file():
        print(f"      visuel absent : {fichier.name}")
        return
    empreinte = hashlib.md5(fichier.read_bytes()).hexdigest()
    ancien = wp("post", "meta", "get", post_id, "_thumbnail_id", check=False)
    if ancien and wp("post", "meta", "get", post_id, "_fati_visuel_md5", check=False) == empreinte:
        return
    if dry:
        print(f"      visuel ← {fichier.name}")
        return
    wp("media", "import", str(fichier), f"--post_id={post_id}", "--featured_image", f"--title={nom}", "--porcelain")
    wp("post", "meta", "update", post_id, "_fati_visuel_md5", empreinte)
    if ancien.isdigit():
        wp("post", "delete", ancien, "--force", check=False)


def main():
    parseur = argparse.ArgumentParser(description=__doc__, formatter_class=argparse.RawDescriptionHelpFormatter)
    parseur.add_argument("--dry-run", action="store_true", help="affiche les changements sans rien écrire")
    dry = parseur.parse_args().dry_run

    if not FORMATIONS.is_file():
        sys.exit(f"Fichier introuvable : {FORMATIONS}")

    lignes = list(csv.DictReader(open(str(FORMATIONS), encoding="utf-8", newline="")))
    attendues = {"slug", "nom", "niveau", "duree", "image", "accroche"}
    manquantes = attendues - set(lignes[0].keys())
    if manquantes:
        sys.exit(f"Colonnes manquantes dans le CSV : {', '.join(sorted(manquantes))}")

    ordre, slugs = 0, []
    for ligne in lignes:
        ligne = {k: (v or "").strip() for k, v in ligne.items()}
        slug, nom = ligne["slug"], ligne["nom"]
        if not slug:
            continue
        ordre += 10
        slugs.append(slug)

        programme = PROGRAMMES / f"{slug}.md"
        if not programme.is_file():
            sys.exit(f"Programme introuvable : {programme}")
        contenu = blocs(programme.read_text(encoding="utf-8"))

        post_id = wp("post", "list", "--post_type=formation", f"--name={slug}", "--field=ID", "--posts_per_page=1", "--post_status=any")
        champs = [f"--post_title={nom}", f"--menu_order={ordre}", f"--post_excerpt={ligne['accroche']}", "--post_status=publish"]

        if post_id:
            actuel = wp("post", "get", post_id, "--fields=post_title,menu_order,post_excerpt,post_status,post_content", "--format=json")
            actuel = json.loads(actuel)
            inchange = (
                actuel["post_title"] == nom
                and int(actuel["menu_order"]) == ordre
                and actuel["post_excerpt"] == ligne["accroche"]
                and actuel["post_status"] == "publish"
                and actuel["post_content"].strip() == contenu
            )
            print(f"   {'=' if inchange else '~'} {nom}")
        else:
            inchange = False
            print(f"   + {nom}")

        if not inchange and not dry:
            # Le contenu passe par un fichier : un long argument en ligne de
            # commande est fragile, et les blocs contiennent des retours ligne.
            with tempfile.NamedTemporaryFile("w", suffix=".html", encoding="utf-8", delete=False) as fichier:
                fichier.write(contenu)
            try:
                if post_id:
                    wp("post", "update", post_id, fichier.name, *champs)
                else:
                    post_id = wp("post", "create", fichier.name, "--post_type=formation", f"--post_name={slug}", "--porcelain", *champs)
            finally:
                os.unlink(fichier.name)

        if not post_id:
            continue

        for colonne, cle in METAS.items():
            poser_meta(post_id, cle, ligne[colonne], dry)
        if ligne["image"]:
            poser_visuel(post_id, nom, MEDIAS / ligne["image"], dry)

    # Ce qui n'est plus au programme ne reste pas en ligne, mais n'est pas perdu.
    publiees = wp("post", "list", "--post_type=formation", "--post_status=publish", "--fields=ID,post_name", "--format=csv", "--posts_per_page=-1")
    for rangee in csv.DictReader(publiees.splitlines()):
        if rangee["post_name"] not in slugs:
            print(f"   − {rangee['post_name']} (repasse en brouillon)")
            if not dry:
                wp("post", "update", rangee["ID"], "--post_status=draft")

    print(f"{ordre // 10} formations traitées" + (" (simulation)" if dry else ""))
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
