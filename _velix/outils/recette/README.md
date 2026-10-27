# Outils de recette (Playwright)

Scripts utilisés pour la recette des thèmes 1.4.0 à 1.7.0 (27 oct. 2026). Ils se copient dans un dossier de travail hors
dépôt (`~/recette-fati` par exemple), avec `npm i playwright pngjs pixelmatch` puis
`npx playwright install chromium webkit firefox`. Socle de navigateurs, inventaire et replis :
`_velix/compatibilite.md`.

| Script | Rôle |
| --- | --- |
| `shoot.mjs` | captures pleine page + audit (débordement horizontal, éléments hors écran, zones de toucher, texte < 12 px, largeur du `h1`, erreurs JS, éléments restés invisibles) ; options `--pagesfile pages-fati.json`, `--widths`, `--pages`, `--rtl`, `--browser`, `--motion`, `--audit`, `--no-shot`, `--notshown`, `--ghost` ; l'URL vient de `BASE=` |
| `compare.mjs` | comparaison pixel à pixel de deux dossiers de captures, images de différence |
| `bbox.mjs` | localise les zones rouges d'une image de différence |
| `crop.mjs` | découpe une zone d'une capture |
| `interact-fati.mjs` | menu mobile en paysage, carrousel, ajout au panier (confirmation + tiroir), en LTR et RTL (`ENGINE=webkit` ou `firefox`) |
| `cercles-fati.mjs` | cercles de catégories (1.6.0) : rangée de quatre à 320–430 px, étiquettes sans débordement, bascule sélection / désélection, page de catégorie, accueil mobile, grand écran, RTL ; `OUT=` dossier des captures, `ENGINE=webkit` ou `firefox` |
| `sticky-fati.mjs` | menu mobile et bloc filtres + recherche collants (1.5.0) : écart à l'en-tête en haut de page, en-tête réduit, paysage |
| `arabe-fati.mjs` | texte arabe de substitution sur l'accueil en RTL : police Cairo chargée, interlignes |
| `pages-fati-ar.json` | les treize adresses de la version arabe (accueil `/ar/`, boutique, catégorie, fiche, formations, fiche formation, À propos, contact, panier, deux pages légales, recherche, 404) |
| `panier-ar.mjs` | version arabe : polices Alexandria / Plex chargées, panier partagé FR ↔ AR, prix de gauche à droite dans le tiroir |
| `pages-fati.json` | les quinze adresses recettées (accueil, boutique, catégorie, fiche, formations, fiche formation, À propos, contact, panier, quatre pages légales, recherche, 404) |

```bash
BASE=http://localhost/websites/fatichanelya.com/ node shoot.mjs --pagesfile pages-fati.json --out avant --widths 390,1440
# … modifications …
BASE=http://localhost/websites/fatichanelya.com/ node shoot.mjs --pagesfile pages-fati.json --out apres --widths 390,1440
node compare.mjs avant apres diff
BASE=… node shoot.mjs --pagesfile pages-fati.json --out grille --no-shot --widths 320,375,768,1024,1440,2722,3200 --audit grille.json
BASE=… node shoot.mjs --pagesfile pages-fati.json --rtl --out rtl --widths 390,1440
BASE=… node shoot.mjs --pagesfile pages-fati.json --motion --out mouvement --widths 390,1440 --audit mouvement.json \
  --notshown ".reveal:not(.is-in), .reveal-head:not(.is-in)" --ghost ".reveal, .reveal-head > *, .product-media img, .categorie-media"
```

Règles du design system à respecter en recette : **toujours 2722 et 3200 px** dans la grille (le gérant travaille sur
un très grand écran) et la **largeur du `h1`** mesurée, pas seulement les bords ; défilement en `instant`
(le site pose `scroll-behavior:smooth`) ; une capture d'élément « hors fenêtre » peut rendre des `opacity:0`,
vérifier avec une capture de fenêtre réelle.

Pour tester le RTL avec le vrai câblage WordPress (et non le `dir` forcé par `--rtl`) :

```bash
wp language core install ar && wp site switch-language ar    # <html dir="rtl" lang="ar">, body.rtl
wp site switch-language fr_FR && wp language core uninstall ar
```
