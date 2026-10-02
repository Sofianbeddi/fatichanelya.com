#!/usr/bin/env python3
"""
Normalise les visuels produits pour qu'ils tiennent ensemble dans une grille.

Le problème résolu : sur le catalogue d'origine, le produit occupe entre 11 %
et 56 % de son visuel selon la référence. Dans une grille, l'œil lit cet écart
comme un défaut de fabrication, pas comme une variation de produit.

Ce script détoure le produit sur son fond blanc, le recadre, puis le repose
centré sur un carré au même taux d'occupation pour toutes les références.

    python3 tools/normaliser-visuels.py            # écrit dans tools/medias/produits-normalises/
    python3 tools/normaliser-visuels.py --inspect  # mesure sans rien écrire

Le dossier source n'est jamais modifié.
"""

from __future__ import annotations

import argparse
import pathlib
import sys

try:
    import numpy as np
    from PIL import Image
except ImportError:  # pragma: no cover
    sys.exit("Pillow et numpy sont nécessaires : python3 -m pip install Pillow numpy")


SOURCE = pathlib.Path("tools/medias/produits")
DESTINATION = pathlib.Path("tools/medias/produits-normalises")

TAILLE = 1000          # côté du carré produit, en pixels
SURFACE_CIBLE = 0.34   # part de la surface du carré couverte par le produit (26 % jusqu'au 2 oct. 2026 : produit trop petit sur mobile)
HAUTEUR_MAX = 0.86     # un objet élancé ne dépasse jamais cette part de la hauteur
LARGEUR_MAX = 0.86     # ni celle-ci en largeur
SEUIL_FOND = 244       # au-delà, le pixel est considéré comme du fond blanc
FOND = (255, 255, 255)


def seuil_du_fond(pixels: np.ndarray) -> int:
    """Seuil de détection du fond, adapté à l'image.

    Certains visuels ont des coins blancs mais un fond qui s'assombrit vers le
    centre en dégradé. Lire seulement les coins y laisse passer un rectangle
    gris autour du produit. On échantillonne donc tout le pourtour et on retient
    sa valeur la plus sombre, en écartant les extrêmes au cas où le produit
    toucherait un bord.
    """
    bandeau = np.concatenate([
        pixels[:10, :].reshape(-1, 3),
        pixels[-10:, :].reshape(-1, 3),
        pixels[:, :10].reshape(-1, 3),
        pixels[:, -10:].reshape(-1, 3),
    ])
    luminance = bandeau.min(axis=1)
    fond = int(np.percentile(luminance, 12))
    return max(min(fond - 6, SEUIL_FOND), 196)


def boite_du_produit(image: Image.Image) -> tuple[int, int, int, int] | None:
    """Rectangle englobant du produit, fond exclu."""
    pixels = np.asarray(image.convert("RGB"), dtype=np.int16)
    encre = pixels.min(axis=2) < seuil_du_fond(pixels)

    if not encre.any():
        return None

    lignes = np.flatnonzero(encre.any(axis=1))
    colonnes = np.flatnonzero(encre.any(axis=0))
    return int(colonnes[0]), int(lignes[0]), int(colonnes[-1]) + 1, int(lignes[-1]) + 1


def normaliser(chemin: pathlib.Path) -> Image.Image | None:
    image = Image.open(chemin).convert("RGB")
    boite = boite_du_produit(image)
    if boite is None:
        return None

    produit = image.crop(boite)

    # Le fond du visuel source n'est pas toujours blanc pur (gris clair,
    # léger dégradé). On le ramène au blanc, sinon la grille garde des
    # rectangles gris visibles autour de certains produits.
    tableau = np.asarray(produit.convert("RGB"), dtype=np.int16)
    fond_local = tableau.min(axis=2) >= seuil_du_fond(np.asarray(image.convert("RGB"), dtype=np.int16))
    if fond_local.any():
        tableau = tableau.copy()
        tableau[fond_local] = FOND
        produit = Image.fromarray(tableau.astype("uint8"), "RGB")

    # Mise à l'échelle. Caler le plus grand côté sur une cible unique ne suffit
    # pas : un flacon fin et haut couvre alors bien moins de surface qu'un
    # coffret large, et la grille reste irrégulière. On vise donc directement
    # la surface d'encre, en bornant les deux côtés pour qu'un objet très
    # élancé ne touche jamais les bords.
    encre = np.asarray(produit.convert("RGB"), dtype=np.int16).min(axis=2) < SEUIL_FOND
    pixels_encre = max(int(encre.sum()), 1)

    # Le facteur agit sur les deux dimensions : la surface varie en son carré.
    facteur = ((SURFACE_CIBLE * TAILLE * TAILLE) / pixels_encre) ** 0.5

    # Bornes : ni plus haut que la zone utile, ni plus large qu'elle.
    facteur = min(facteur, TAILLE * HAUTEUR_MAX / produit.height)
    facteur = min(facteur, TAILLE * LARGEUR_MAX / produit.width)

    largeur = max(1, round(produit.width * facteur))
    hauteur = max(1, round(produit.height * facteur))
    produit = produit.resize((largeur, hauteur), Image.LANCZOS)

    # Le produit est centré horizontalement, et optiquement sur la verticale :
    # un centrage géométrique strict fait toujours paraître l'objet trop bas.
    toile = Image.new("RGB", (TAILLE, TAILLE), FOND)
    x = (TAILLE - largeur) // 2
    y = round((TAILLE - hauteur) * 0.50)
    toile.paste(produit, (x, y))
    return toile


def occupation(chemin: pathlib.Path) -> float:
    image = Image.open(chemin).convert("RGB")
    pixels = np.asarray(image, dtype=np.int16)
    return float((pixels.min(axis=2) < SEUIL_FOND).mean() * 100)


def main() -> int:
    analyseur = argparse.ArgumentParser(description=__doc__)
    analyseur.add_argument(
        "--inspect",
        action="store_true",
        help="mesure l'occupation avant et après, sans écrire de fichier",
    )
    options = analyseur.parse_args()

    if not SOURCE.is_dir():
        sys.exit(f"Dossier source introuvable : {SOURCE}")

    fichiers = sorted(SOURCE.glob("*.webp"))
    if not fichiers:
        sys.exit(f"Aucun visuel dans {SOURCE}")

    if not options.inspect:
        DESTINATION.mkdir(parents=True, exist_ok=True)

    print(f"{'fichier':34s} {'avant':>8s} {'après':>8s}")
    print("-" * 54)

    ignores: list[str] = []
    apres: list[float] = []

    for fichier in fichiers:
        avant = occupation(fichier)
        resultat = normaliser(fichier)

        if resultat is None:
            ignores.append(fichier.name)
            print(f"{fichier.name:34s} {avant:7.1f}% {'—':>8s}  visuel vide, ignoré")
            continue

        if options.inspect:
            tampon = resultat
        else:
            sortie = DESTINATION / fichier.name
            resultat.save(sortie, "WEBP", quality=88, method=6)
            tampon = Image.open(sortie).convert("RGB")

        pixels = np.asarray(tampon, dtype=np.int16)
        valeur = float((pixels.min(axis=2) < SEUIL_FOND).mean() * 100)
        apres.append(valeur)
        print(f"{fichier.name:34s} {avant:7.1f}% {valeur:7.1f}%")

    if apres:
        ecart = max(apres) - min(apres)
        print(
            f"\n{len(apres)} visuels normalisés · "
            f"occupation de {min(apres):.1f} % à {max(apres):.1f} % "
            f"(écart {ecart:.1f} points)"
        )

    if ignores:
        print("Ignorés : " + ", ".join(ignores))

    if not options.inspect:
        print(f"Écrits dans {DESTINATION}/")

    return 0


if __name__ == "__main__":
    raise SystemExit(main())
