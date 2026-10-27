# Compatibilité navigateurs — fatichanelya.com

État au 27 octobre 2026 (thème 1.4.0). Socle de navigateurs retenu, inventaire des fonctionnalités CSS / JS
employées avec leur comportement de repli, ce qui a été testé et ce qui ne l'a pas été.

## Socle retenu

| Navigateur | Versions couvertes | Remarque |
| --- | --- | --- |
| Chrome, Edge (Chromium) | deux dernières années (≥ 110) | moteur de référence des captures |
| Firefox | deux dernières années (≥ 110) | |
| Safari macOS | ≥ 15 (sept. 2021) | |
| Safari iOS / iPadOS | ≥ 15 | tous les navigateurs iOS utilisent WebKit |
| Samsung Internet | ≥ 20 | Chromium, non testé séparément |

Le plancher est fixé par les propriétés logiques (`inset-inline`, `padding-inline`, `inline-size`), déjà
employées partout dans `main.css`, et par `aspect-ratio` : Safari 15 / Chrome 89 / Firefox 66.

## Inventaire et replis

| Fonctionnalité | Où | Si absente |
| --- | --- | --- |
| Propriétés logiques (`*-inline-*`, `inset-inline`, `inline-size`, `block-size`) | toute la feuille | hors socle en dessous de Safari 15 |
| `color-mix()` | en-tête collant, voile du tiroir, capsule de verre des formations, dégradés du journal et du halo du pied | **repli hexadécimal avec alpha déclaré juste avant** (`#16233D80`…) : même rendu, sans mélange |
| `:has()` | filet au survol d'une cellule, fil d'Ariane mobile de la fiche | progressif : pas de filet au survol, séparateur visible (Firefox < 121) |
| `text-wrap: balance` | titres | progressif : coupure normale (Safari < 17.5, Firefox < 121) |
| `@view-transition { navigation: auto }` | changement de page | ignoré (Chrome < 126, Firefox) |
| `backdrop-filter` + `-webkit-` | en-tête, tiroir, capsule de verre | fond translucide sans flou (Firefox < 103) |
| `aspect-ratio`, `gap` sur flex, `clamp()`, `min()`, `max()` | partout | hors socle en dessous |
| `scroll-snap-type`, `scrollbar-width` + `::-webkit-scrollbar` | carrousels | défilement libre, barre visible dans de vieux Chrome |
| `-webkit-line-clamp` | nom de produit, résumé, confirmation | texte non coupé |
| `:focus-visible` | focus clavier | `@supports not selector(:focus-visible)` → `:focus` (Safari 14.1–15.3) |
| `scale`, `translate` (propriétés individuelles) | apparitions et parallaxe | Safari 14.1+, Chrome 104+ |
| `animation-timeline: view()` (parallaxe des affiches) | visuels des formations | `@supports` : aucune parallaxe (Firefox, Safari < 26) |
| `100dvh` | panneau du menu mobile | `100vh` déclaré avant (Safari < 15.4) |
| `IntersectionObserver` | apparitions, en-tête collant | tout visible d'emblée (`.js-reveal` n'est posée qu'avec l'observateur) |
| `Element.animate()` | visuel qui file vers le panier | gardé par `img.animate` : simple rebond de la pastille |
| `MediaQueryList.addEventListener` | onglets des formations | repli `addListener` (Safari < 14) |
| `localStorage` | panier | `try/catch` : panier en mémoire pour la page |
| `Intl.NumberFormat` | prix (devise selon `lang`) | Safari 10+, universel |
| `unicode-range` | Poppins (latin), Cairo (arabe) | police système |

Corrigé en 1.4.0 :

- `transform-origin: inline-start` n'existe pas (`inline-start` n'est pas une valeur de `transform-origin`) : les
  soulignements d'or de la navigation et du pied partaient du centre au lieu du début de ligne → `var(--origin-x)`
  (0 % en LTR, 100 % en RTL) ;
- caractère invalide pour `color-mix()` : les quatre emplois reçoivent un repli hexadécimal ;
- `.search` : WordPress pose cette classe sur `<body>` de la page de résultats, qui prenait le style du champ
  de recherche (pilule flexible) → `.search-form`, et un gabarit `search.php` ;
- `.screen-reader-text` (classe WordPress) n'était pas masquée : « Pagination des publications » s'affichait en titre ;
- zoom au focus d'iOS : `font-size: max(16px, 1em)` sur les champs des appareils tactiles ;
- menu mobile borné à la hauteur de l'écran avec défilement interne (téléphone en paysage).

## Ce qui a été testé

Avec Playwright 1.64 (outils dans `_velix/outils/recette/`), en local, le 27 octobre 2026 :

| Moteur | Version | Pages | Largeurs | Contrôles |
| --- | --- | --- | --- | --- |
| Chromium | 145 | 15 | 320, 360, 375, 390, 414, 480, 560, 600, 699, 700, 768, 820, 900, 980, 1000, 1024, 1080, 1180, 1240, 1280, 1366, 1440, 1600, 1920, **2722, 3200** | débordement, éléments hors écran, zones de toucher, texte < 12 px, largeur du `h1`, erreurs JS, éléments restés invisibles |
| WebKit | build Playwright (≈ Safari 26) | 15 | 390, 1440 | débordement, erreurs JS, captures |
| Firefox | 157 | 15 | 390, 1440 | débordement, erreurs JS, captures |

États interactifs vérifiés en LTR et en RTL (Chromium) : menu mobile en paysage (844 × 390), carrousel après
« suivant » (position, boutons, barre, compteur, sens des flèches), ajout au panier (confirmation, pastille,
sélecteur de quantité, tiroir). Texte arabe de substitution sur l'accueil : Cairo chargée, aucun interlettrage,
interlignes 1,35 / 1,8.

Résultat : aucun débordement, aucune erreur JavaScript, aucun élément resté invisible, titres de 586 à 660 px à
2722 et 3200 px (la marge `--bord` tient).

## Ce qui n'a pas été testé

- De vrais appareils iOS et Android (barre d'adresse mobile, zoom au focus réel) ;
- les versions réelles de Safari 15 à 17 (seul le WebKit courant de Playwright a tourné) ;
- Samsung Internet, Opera ;
- Windows avec barres de défilement classiques (`--bord` compte `100vw`, écart de 8 px par côté au pire, déjà
  documenté dans le design system) ;
- l'impression (une règle `@media print` rend tout visible, sans contrôle visuel).

## Rejouer la recette

Voir `_velix/outils/recette/README.md`.
