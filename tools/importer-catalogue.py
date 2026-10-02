#!/usr/bin/env python3
"""
Importe ou met à jour le catalogue produits depuis tools/catalogue.csv.

Succède à la partie « Produits » de tools/seed.sh, qui ne connaissait que le
nom, le prix et une phrase. Le catalogue porte désormais une accroche, une
description, la contenance, la composition et le mode d'emploi — des champs à
plusieurs lignes qu'un script shell ligne à ligne ne sait pas transporter.

    python3 tools/importer-catalogue.py            # applique
    python3 tools/importer-catalogue.py --dry-run  # montre sans écrire

Idempotent : la recherche se fait par slug, les métas inchangées ne sont pas
réécrites, et un visuel n'est réimporté que si son contenu a changé (empreinte
MD5 conservée en méta). La commande WP-CLI se surcharge par la variable WP,
nécessaire en local XAMPP où le socket MySQL n'est pas celui par défaut :

    WP="php -d mysqli.default_socket=/Applications/XAMPP/xamppfiles/var/mysql/mysql.sock /usr/local/bin/wp" \
      python3 tools/importer-catalogue.py

Colonnes du CSV : slug, nom, categorie, prix, image, format, accroche,
description, composition, usage. Les champs vides effacent la méta
correspondante : ce qui n'est pas vérifié ne s'affiche pas.
"""

import argparse
import csv
import hashlib
import os
import pathlib
import re
import shlex
import subprocess
import sys
import unicodedata

RACINE = pathlib.Path(__file__).resolve().parent.parent
CATALOGUE = RACINE / "tools" / "catalogue.csv"
MEDIAS = RACINE / "tools" / "medias"
WP = shlex.split(os.environ.get("WP", "wp"))

METAS = {
    "prix": "_fati_prix",
    "format": "_fati_format",
    "composition": "_fati_composition",
    "usage": "_fati_usage",
}


def wp(*args, check=True):
    # Compatible Python 3.6 (serveur mutualisé) : pas de capture_output ni de text=.
    resultat = subprocess.run([*WP, *args], stdout=subprocess.PIPE, stderr=subprocess.PIPE, universal_newlines=True, cwd=str(RACINE))
    if check and resultat.returncode != 0:
        sys.exit(f"wp {' '.join(args[:3])}… : {resultat.stderr.strip() or resultat.stdout.strip()}")
    return resultat.stdout.strip()


def slugifier(texte):
    """Même résultat que sanitize_title() côté WordPress, pour les catégories."""
    s = unicodedata.normalize("NFKD", texte).encode("ascii", "ignore").decode()
    return re.sub(r"[^a-zA-Z0-9]+", "-", s).strip("-").lower()


def meta_actuelle(post_id, cle):
    return wp("post", "meta", "get", post_id, cle, check=False)


def poser_meta(post_id, cle, valeur, dry):
    valeur = valeur.strip()
    actuelle = meta_actuelle(post_id, cle)
    if valeur == actuelle:
        return
    # « 40.50 » est relu « 40.5 » : comparer aussi en valeur numérique.
    try:
        if valeur and actuelle and float(valeur) == float(actuelle):
            return
    except ValueError:
        pass
    if dry:
        print(f"      {cle} ← {valeur[:60]!r}" if valeur else f"      {cle} effacée")
        return
    if valeur:
        wp("post", "meta", "update", post_id, cle, valeur)
    elif actuelle:
        wp("post", "meta", "delete", post_id, cle)


def garantir_categorie(nom, dry):
    slug = slugifier(nom)
    existants = wp("term", "list", "categorie_produit", "--field=slug").split()
    if slug not in existants and not dry:
        wp("term", "create", "categorie_produit", nom, f"--slug={slug}")
    return slug


def chemin_visuel(nom_fichier):
    if not nom_fichier:
        return None
    for dossier in ("produits-normalises", "produits"):
        p = MEDIAS / dossier / nom_fichier
        if p.is_file():
            return p
    return None


def poser_visuel(post_id, nom, fichier, dry):
    if fichier is None:
        poser_meta(post_id, "_fati_indispo", "1", dry)
        return
    empreinte = hashlib.md5(fichier.read_bytes()).hexdigest()
    if meta_actuelle(post_id, "_fati_visuel_md5") == empreinte and wp("post", "meta", "get", post_id, "_thumbnail_id", check=False):
        return
    if dry:
        print(f"      visuel ← {fichier.name}")
        return
    ancien = wp("post", "meta", "get", post_id, "_thumbnail_id", check=False)
    wp("media", "import", str(fichier), f"--post_id={post_id}", "--featured_image", f"--title={nom}", "--porcelain")
    wp("post", "meta", "update", post_id, "_fati_visuel_md5", empreinte)
    wp("post", "meta", "delete", post_id, "_fati_indispo", check=False)
    # L'ancien visuel ne sert plus à rien : il partirait sinon grossir la
    # médiathèque à chaque remplacement.
    if ancien and ancien.isdigit():
        wp("post", "delete", ancien, "--force", check=False)


def main():
    parseur = argparse.ArgumentParser(description=__doc__, formatter_class=argparse.RawDescriptionHelpFormatter)
    parseur.add_argument("--dry-run", action="store_true", help="affiche les changements sans rien écrire")
    options = parseur.parse_args()
    dry = options.dry_run

    if not CATALOGUE.is_file():
        sys.exit(f"Catalogue introuvable : {CATALOGUE}")

    lignes = list(csv.DictReader(open(str(CATALOGUE), encoding="utf-8", newline="")))
    attendues = {"slug", "nom", "categorie", "prix", "image", "format", "accroche", "description", "composition", "usage"}
    manquantes = attendues - set(lignes[0].keys())
    if manquantes:
        sys.exit(f"Colonnes manquantes dans le CSV : {', '.join(sorted(manquantes))}")

    ordre = 0
    for ligne in lignes:
        ligne = {k: (v or "").strip() for k, v in ligne.items()}
        if not ligne["slug"]:
            continue
        ordre += 10
        slug, nom = ligne["slug"], ligne["nom"]

        post_id = wp("post", "list", "--post_type=produit", f"--name={slug}", "--field=ID", "--posts_per_page=1", "--post_status=any")
        if not post_id:
            print(f"   + {nom}")
            if dry:
                post_id = "0"
            else:
                post_id = wp(
                    "post", "create", "--post_type=produit", "--post_status=publish", "--porcelain",
                    f"--post_title={nom}", f"--post_name={slug}", f"--menu_order={ordre}",
                    f"--post_excerpt={ligne['accroche']}", f"--post_content={ligne['description']}",
                )
        else:
            print(f"   ~ {nom}")
            if not dry:
                wp(
                    "post", "update", post_id, f"--post_title={nom}", f"--menu_order={ordre}",
                    f"--post_excerpt={ligne['accroche']}", f"--post_content={ligne['description']}",
                )

        if dry and post_id == "0":
            continue

        for colonne, cle in METAS.items():
            poser_meta(post_id, cle, ligne[colonne], dry)

        if ligne["categorie"]:
            slug_cat = garantir_categorie(ligne["categorie"], dry)
            if not dry:
                wp("post", "term", "set", post_id, "categorie_produit", slug_cat, "--by=slug")

        poser_visuel(post_id, nom, chemin_visuel(ligne["image"]), dry)

    print(f"{ordre // 10} produits traités" + (" (simulation)" if dry else ""))
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
