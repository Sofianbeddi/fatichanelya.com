#!/usr/bin/env python3
"""
Publie les quatre pages légales dans WordPress à partir de `_velix/copy-legal.md`.

Le texte de référence est le fichier Markdown : on ne le recopie jamais à la
main dans l'admin. Ce script en extrait chaque page, son titre, son slug et sa
meta description, puis crée ou met à jour la page correspondante.

    python3 tools/publier-pages-legales.py           # publie
    python3 tools/publier-pages-legales.py --dry-run # montre sans écrire

Les marqueurs « [[À FOURNIR : … ]] » sont conservés tels quels et comptés : ils
signalent les données que la cliente doit encore transmettre. Le script refuse
de publier sans `--force` si des marqueurs subsistent, pour qu'une page
incomplète ne parte jamais en production par inadvertance.
"""

from __future__ import annotations

import argparse
import pathlib
import re
import subprocess
import sys
import tempfile

SOURCE = pathlib.Path("_velix/copy-legal.md")

# Ordre d'affichage dans le menu du pied de page.
ORDRE_MENU = [
    "mentions-legales",
    "confidentialite",
    "conditions-generales-de-vente",
    "livraison-retours",
]


class Page:
    def __init__(self, titre: str, slug: str, description: str, html: str) -> None:
        self.titre = titre
        self.slug = slug
        self.description = description
        self.html = html

    @property
    def marqueurs(self) -> int:
        return self.html.count("[[À FOURNIR")


def wp(*args: str) -> str:
    """Appelle WP-CLI et renvoie sa sortie."""
    resultat = subprocess.run(["wp", *args], capture_output=True, text=True)
    if resultat.returncode != 0:
        message = resultat.stderr.strip() or resultat.stdout.strip()
        raise RuntimeError(f"wp {' '.join(args)} : {message}")
    return resultat.stdout.strip()


def extraire(markdown: str) -> list[Page]:
    """Découpe le Markdown en pages exploitables."""
    pages: list[Page] = []
    blocs = re.split(r"^# PAGE \d+ — ", markdown, flags=re.MULTILINE)[1:]

    for bloc in blocs:
        titre_match = re.search(r"\*\*Titre\*\*\s*:\s*(.+)", bloc)
        slug_match = re.search(r"\*\*Slug\*\*\s*:\s*`([^`]+)`", bloc)
        desc_match = re.search(
            r"\*\*Meta description\*\*[^\n]*\n`([^`]+)`", bloc
        )
        html_match = re.search(r"```html\n(.*?)\n```", bloc, flags=re.DOTALL)

        if not (titre_match and slug_match and html_match):
            continue

        pages.append(
            Page(
                titre=titre_match.group(1).strip(),
                slug=slug_match.group(1).strip(),
                description=desc_match.group(1).strip() if desc_match else "",
                html=html_match.group(1).strip(),
            )
        )

    return pages


def publier(page: Page, essai: bool) -> str:
    """Crée ou met à jour la page, renvoie son identifiant."""
    identifiant = wp(
        "post", "list", "--post_type=page", f"--name={page.slug}",
        "--field=ID", "--posts_per_page=1",
    )

    if essai:
        return identifiant or "(nouvelle)"

    # Le contenu passe par un fichier : « --post_content=- » ferait écrire la
    # chaîne « - » et non le HTML, et un long argument en ligne de commande
    # est de toute façon fragile.
    with tempfile.NamedTemporaryFile(
        "w", suffix=".html", encoding="utf-8", delete=False
    ) as fichier:
        fichier.write(page.html)
        chemin = fichier.name

    try:
        if identifiant:
            wp(
                "post", "update", identifiant,
                f"--post_title={page.titre}",
                "--post_status=publish",
                chemin,
            )
        else:
            identifiant = wp(
                "post", "create",
                "--post_type=page",
                "--post_status=publish",
                "--porcelain",
                f"--post_title={page.titre}",
                f"--post_name={page.slug}",
                chemin,
            )
    finally:
        pathlib.Path(chemin).unlink(missing_ok=True)

    if page.description:
        wp("post", "meta", "update", identifiant, "_fati_description", page.description)

    return identifiant


def main() -> int:
    analyseur = argparse.ArgumentParser(description=__doc__)
    analyseur.add_argument("--dry-run", action="store_true", help="n'écrit rien")
    analyseur.add_argument(
        "--force",
        action="store_true",
        help="publie même si des données manquent encore",
    )
    options = analyseur.parse_args()

    if not SOURCE.is_file():
        sys.exit(f"Source introuvable : {SOURCE}")

    pages = extraire(SOURCE.read_text(encoding="utf-8"))
    if not pages:
        sys.exit("Aucune page n'a pu être extraite du Markdown.")

    manquants = sum(page.marqueurs for page in pages)

    print(f"{'page':36s} {'slug':32s} {'données manquantes':>19s}")
    print("-" * 92)
    for page in pages:
        print(f"{page.titre:36s} {page.slug:32s} {page.marqueurs:>19d}")
    print(f"\n{len(pages)} pages · {manquants} données à fournir par la cliente")

    if manquants and not (options.force or options.dry_run):
        print(
            "\nPublication interrompue : ces pages contiennent encore des marqueurs.\n"
            "Elles sont valables comme brouillon de travail, pas en production.\n"
            "Relancer avec --force pour publier quand même en local."
        )
        return 1

    if options.dry_run:
        print("\nAucune écriture (--dry-run).")
        return 0

    print()
    for page in pages:
        identifiant = publier(page, essai=False)
        print(f"  {page.titre:36s} → page {identifiant}")

    print("\nPages publiées. Les marqueurs restent visibles dans le contenu.")
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
