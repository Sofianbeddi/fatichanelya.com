#!/usr/bin/env python3
"""
Extrait les packshots officiels du catalogue Kallow (DXN Cosmetics, édition
arabe fournie par le gérant le 26 octobre 2026) et les dépose comme visuels
source des produits du site.

    python3 tools/extraire-visuels-kallow.py          # écrit dans tools/medias/produits/
    python3 tools/extraire-visuels-kallow.py --inspect # mesure sans rien écrire

Le PDF range chaque packshot en couche séparée avec un masque de transparence.
Le masque est conservé comme canal alpha du WebP déposé : le normaliseur y lit
exactement où est le produit, et le corps d'un pot blanc reste entier (au seuil,
il passait pour du fond). Sans masque dans le PDF (baume à lèvres), le visuel
est déposé en RGB sur blanc et suit le chemin habituel. Trois
visuels gardent le décor officiel collé au produit (perles du sérum, olive de
la crème argan, aloe de l'après-soleil) : il fait partie de la même image.

Dépend de `pdfimages` (poppler) et de Pillow. Les numéros d'image sont ceux
que `pdfimages -list` attribue à ce PDF précis : un autre fichier, même
visuellement identique, demande une nouvelle lecture de la liste.

Après ce script : python3 tools/normaliser-visuels.py, puis l'import.
"""

from __future__ import annotations

import argparse
import pathlib
import shutil
import subprocess
import sys
import tempfile

try:
    from PIL import Image
except ImportError:  # pragma: no cover
    sys.exit("Pillow est nécessaire : python3 -m pip install Pillow")

RACINE = pathlib.Path(__file__).resolve().parent.parent
PDF = RACINE / "tools" / "medias" / "sources" / "kallow-catalogue" / "catalogue-kallow-ar.pdf"
ARCHIVE = PDF.parent          # copies PNG du produit détouré, pour contrôle
DESTINATION = RACINE / "tools" / "medias" / "produits"

# slug du site → (page du PDF, numéro d'image pdfimages). Référence DXN en commentaire.
VISUELS = {
    "kallow-foaming-cleanser": (6, 93),      # SC081
    "kallow-tightening-serum": (7, 99),      # SC082 — perles dans l'image
    "kallow-argan-face-cream": (8, 213),     # SC083 — olive dans l'image
    "kallow-micellar-tonic":   (9, 221),     # SC084
    "kallow-face-mask":        (10, 288),    # SC085
    "kallow-sunscreen-spf50":  (11, 296),    # SC086 — remplace le packshot de synthèse
    "divine-eye-cream":        (15, 455),    # SC087 — remplace le packshot de synthèse
    "divine-face-serum":       (16, 459),    # SC088
    "divine-face-cream":       (17, 567),    # SC089 — remplace le packshot de synthèse
    "divine-night-oil":        (18, 571),    # SC090 — remplace le packshot de synthèse
    "divine-dry-oil":          (19, 627),    # SC091 — remplace le packshot de synthèse
    "kallow-shower-gel":       (20, 685),    # SC092 — remplace le packshot de synthèse
    "kallow-body-lotion":      (21, 743),    # SC093
    "kallow-lip-balm":         (22, 799),    # SC094 — le pot seul, sans le beurre de karité
    "kallow-hand-cream":       (23, 863),    # SC095
    "kallow-sunscreen-spf30":  (24, 919),    # SC096
    "kallow-tanning-oil":      (25, 1164),   # SC097
    "kallow-after-sun":        (26, 1174),   # SC098 — aloe et menthe dans l'image
}
MARGE = 12      # pixels de blanc autour du produit détouré
BLANC = (255, 255, 255)


def recomposer(dossier: pathlib.Path, page: int, num: int) -> Image.Image:
    """RGBA quand le PDF fournit un masque, RGB sur blanc sinon."""
    image = Image.open(dossier / f"k-{page:03d}-{num:03d}.png").convert("RGB")
    masque = dossier / f"k-{page:03d}-{num + 1:03d}.png"
    if masque.exists():
        m = Image.open(masque)
        if m.size == image.size and m.mode in ("L", "1"):
            image.putalpha(m.convert("L"))
    return image


def detourer(image: Image.Image) -> Image.Image:
    """Recadre sur le produit et l'entoure d'une marge vide."""
    if "A" in image.getbands():
        boite = image.getchannel("A").getbbox()
    else:
        boite = image.convert("L").point(lambda v: 255 if v < 240 else 0).getbbox()
    if not boite:
        raise ValueError("image vide")
    produit = image.crop(boite)
    fond = (0, 0, 0, 0) if "A" in produit.getbands() else BLANC
    toile = Image.new(produit.mode, (produit.width + 2 * MARGE, produit.height + 2 * MARGE), fond)
    toile.paste(produit, (MARGE, MARGE))
    return toile


def main() -> int:
    analyseur = argparse.ArgumentParser(description=__doc__, formatter_class=argparse.RawDescriptionHelpFormatter)
    analyseur.add_argument("--inspect", action="store_true", help="mesure les produits sans écrire")
    options = analyseur.parse_args()

    if not PDF.is_file():
        sys.exit(f"PDF introuvable : {PDF}")
    if not shutil.which("pdfimages"):
        sys.exit("pdfimages (poppler) est nécessaire : brew install poppler")

    with tempfile.TemporaryDirectory() as tmp:
        dossier = pathlib.Path(tmp)
        subprocess.run(["pdfimages", "-png", "-p", str(PDF), str(dossier / "k")], check=True)
        print(f"{'produit':28s} {'page':>4s} {'produit utile':>14s}")
        print("-" * 50)
        for slug, (page, num) in VISUELS.items():
            produit = detourer(recomposer(dossier, page, num))
            print(f"{slug:28s} {page:4d} {produit.width:6d}x{produit.height:<6d}")
            if options.inspect:
                continue
            produit.save(ARCHIVE / f"{slug}.png", "PNG", optimize=True)
            DESTINATION.mkdir(parents=True, exist_ok=True)
            # Alpha sans perte : c'est lui que lit le normaliseur.
            produit.save(DESTINATION / f"{slug}.webp", "WEBP", quality=95, method=6, exact=True)
    if not options.inspect:
        print(f"\n{len(VISUELS)} visuels écrits dans {DESTINATION.relative_to(RACINE)}/ (PNG de contrôle dans {ARCHIVE.relative_to(RACINE)}/)")
        print("Étape suivante : python3 tools/normaliser-visuels.py")
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
