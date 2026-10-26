#!/usr/bin/env python3
"""
Pose des visuels produits reçus (photos de Fati, images officielles téléchargées)
comme sources du site, détourés, prêts pour la normalisation.

    python3 tools/poser-visuels-recus.py <dossier> [--inspect]

Chaque fichier du dossier (jpg, jpeg, png, webp) est rattaché à un produit par
son nom : soit le slug du site (`cocozhi.jpg`), soit la référence DXN quelque
part dans le nom (`FB124 Cocozhi.jpg`, `HF002_RG30.jpg`). Les fichiers sans
correspondance sont listés et ignorés.

Le fond doit être uni et clair (packshot ou photo sur fond blanc) : le script
le détoure par remplissage depuis les bords, garde les reflets clairs à
l'intérieur du produit, et écrit un WebP avec canal alpha dans
tools/medias/produits/ — c'est ce canal que lit normaliser-visuels.py, et il
évite que le corps d'un produit blanc soit pris pour du fond.

Étapes suivantes : python3 tools/normaliser-visuels.py, puis l'import
(tools/importer-catalogue.py en local, tools/publier-contenu-preversion.sh en
ligne). Ne pas oublier de renseigner la colonne image dans rediger-catalogue.py.
"""

from __future__ import annotations

import argparse
import pathlib
import re
import sys

try:
    from PIL import Image, ImageDraw, ImageFilter
except ImportError:  # pragma: no cover
    sys.exit("Pillow est nécessaire : python3 -m pip install Pillow")

RACINE = pathlib.Path(__file__).resolve().parent.parent
DESTINATION = RACINE / "tools" / "medias" / "produits"
REDIGER = RACINE / "tools" / "rediger-catalogue.py"

SEUIL_FOND = 246   # au-delà (sur le canal le plus sombre), le pixel peut être du fond
MARGE = 12
EXTENSIONS = {".jpg", ".jpeg", ".png", ".webp"}


def codes_du_site() -> dict[str, str]:
    """référence DXN → slug, lue dans le dictionnaire CODES de rediger-catalogue.py."""
    texte = REDIGER.read_text(encoding="utf-8")
    bloc = re.search(r"CODES = \{(.*?)\n\}", texte, re.S)
    if not bloc:
        sys.exit("Dictionnaire CODES introuvable dans rediger-catalogue.py")
    return {code: slug for slug, code in re.findall(r'"([a-z0-9\-]+)":"([A-Z]{2}\d{3})"', bloc.group(1))}


def slugs_du_site() -> set[str]:
    return set(re.findall(r'slug="([a-z0-9\-]+)"', REDIGER.read_text(encoding="utf-8")))


def rattacher(nom: str, slugs: set[str], codes: dict[str, str]) -> str | None:
    base = pathlib.Path(nom).stem.lower()
    if base in slugs:
        return base
    for code, slug in codes.items():
        if re.search(r"(?<![a-z0-9])" + code.lower() + r"(?![0-9])", base):
            return slug
    return None


def detourer(image: Image.Image) -> Image.Image:
    """Alpha = tout ce qui n'est pas le fond clair relié aux bords."""
    rgb = image.convert("RGB")
    # Carte du « clair » : 255 là où le pixel peut être du fond.
    r, g, b = rgb.split()
    px_r, px_g, px_b = r.load(), g.load(), b.load()
    carte = Image.new("L", rgb.size, 0)
    px_c = carte.load()
    w, h = rgb.size
    for y in range(h):
        for x in range(w):
            if min(px_r[x, y], px_g[x, y], px_b[x, y]) >= SEUIL_FOND:
                px_c[x, y] = 255
    # Remplissage depuis les quatre coins : seul le clair relié au bord est du fond.
    for coin in ((0, 0), (w - 1, 0), (0, h - 1), (w - 1, h - 1)):
        if px_c[coin] == 255:
            ImageDraw.floodfill(carte, coin, 128)
    alpha = carte.point(lambda v: 0 if v == 128 else 255)
    # Un léger adoucissement du bord, pour qu'il ne soit pas crénelé après réduction.
    alpha = alpha.filter(ImageFilter.GaussianBlur(0.8))
    boite = alpha.getbbox()
    if not boite:
        raise ValueError("image vide")
    rgba = rgb.copy()
    rgba.putalpha(alpha)
    produit = rgba.crop(boite)
    toile = Image.new("RGBA", (produit.width + 2 * MARGE, produit.height + 2 * MARGE), (0, 0, 0, 0))
    toile.paste(produit, (MARGE, MARGE))
    return toile


def main() -> int:
    analyseur = argparse.ArgumentParser(description=__doc__, formatter_class=argparse.RawDescriptionHelpFormatter)
    analyseur.add_argument("dossier", type=pathlib.Path)
    analyseur.add_argument("--inspect", action="store_true", help="rattache et mesure sans écrire")
    options = analyseur.parse_args()

    if not options.dossier.is_dir():
        sys.exit(f"Dossier introuvable : {options.dossier}")
    slugs, codes = slugs_du_site(), codes_du_site()
    fichiers = sorted(f for f in options.dossier.iterdir() if f.suffix.lower() in EXTENSIONS)
    if not fichiers:
        sys.exit("Aucune image dans le dossier")

    poses, ignores = [], []
    for f in fichiers:
        slug = rattacher(f.name, slugs, codes)
        if not slug:
            ignores.append(f.name)
            continue
        image = Image.open(f)
        produit = detourer(image)
        print(f"{f.name:45s} → {slug:28s} {image.size[0]}x{image.size[1]} → produit {produit.width - 2 * MARGE}x{produit.height - 2 * MARGE}")
        if not options.inspect:
            DESTINATION.mkdir(parents=True, exist_ok=True)
            produit.save(DESTINATION / f"{slug}.webp", "WEBP", quality=95, method=6, exact=True)
        poses.append(slug)

    if ignores:
        print("\nSans correspondance (nommer par slug ou par référence DXN) : " + ", ".join(ignores))
    if poses and not options.inspect:
        print(f"\n{len(poses)} visuel(s) écrit(s) dans {DESTINATION.relative_to(RACINE)}/")
        print("Étapes suivantes : python3 tools/normaliser-visuels.py, puis l'import.")
        print("Colonne image à renseigner dans rediger-catalogue.py pour : " + ", ".join(poses))
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
