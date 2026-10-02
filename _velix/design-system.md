# Système de design — Fatichanelya

Référence unique des couleurs, de la typographie et des composants du site.
Toute valeur ci-dessous est **mesurée**, jamais estimée : les ratios de contraste
ont été calculés, et les défauts trouvés en cours de route sont consignés pour
qu'ils ne soient pas réintroduits.

Le fichier qui fait foi est `wp-content/themes/fatichanelya/assets/css/main.css`,
section « 1. TOKENS ». Ce document explique **pourquoi** ces valeurs, pas seulement
lesquelles.

Établi le 15 septembre 2026.

---

## 1. La palette

Bleu marine et beige chaud, or champagne en accent. C'est l'identité validée en
maquette v3, avec des fonds réchauffés.

### Fonds

| Jeton | Valeur | Rôle |
|---|---|---|
| `--paper` | `#F7F1E8` | Beige chaud, fond dominant du site |
| `--ivory` | `#EFE6D8` | Sable, bandes alternées |
| `--shell` | `#F2EADE` | Bannières de titre de page |
| `--surface` | `#FFFFFF` | Cartes et surfaces posées |

### Bleu marine — surfaces fortes

| Jeton | Valeur | Rôle | Contraste |
|---|---|---|---|
| `--navy` | `#16233D` | En-tête, pied de page, aplats | blanc dessus **15,65:1** |
| `--navy-2` | `#1E3050` | Variante, survol des surfaces bleues | — |
| `--navy-deep` | `#0E1729` | État actif | blanc dessus **17,90:1** |
| `--navy-soft` | `#E6EAF0` | Aplat très clair de la même famille | — |

### Texte

| Jeton | Valeur | Contraste sur beige | Contraste sur sable |
|---|---|---|---|
| `--ink` | `#221E19` | **14,75:1** | **13,39:1** |
| `--muted` | `#5E574C` | **6,35:1** | **5,77:1** |
| `--muted-soft` | `#8B8377` | mentions seules, jamais un texte à lire | — |

### Or champagne — deux rôles, jamais interchangeables

C'est le point le plus facile à casser du système.

| Jeton | Valeur | Emploi | Contraste |
|---|---|---|---|
| `--gold` | `#C39A4E` | **Décor, icônes, aplats uniquement** | 2,32:1 sur beige — **ne porte jamais de texte** |
| `--gold-ink` | `#825F1F` | Texte or sur fond clair | **5,19:1** beige, **4,71:1** sable |
| `--gold-light` | `#D5A54E` | Texte or sur bleu marine | **6,95:1** |
| `--gold-press` | `#A8803B` | Survol : l'or fonce, il ne s'éclaircit jamais | — |
| `--gold-soft` | `#F0E3C9` | Aplat or très clair | — |

> **Interdit numéro un.** Fusionner `--gold` et `--gold-ink` parce qu'ils
> « se ressemblent ». Le premier est illisible en texte. C'est l'erreur la plus
> probable d'un développeur qui verrait deux variables en double.

### Filets et bordures

| Jeton | Valeur | Rôle |
|---|---|---|
| `--line` | `#E4DACA` | Filet discret entre blocs |
| `--line-mid` | `#DCCFBA` | Filet structurel, décoratif |
| `--line-card` | `#E8DECE` | Contour de carte, à peine visible |
| `--line-strong` | `#8F8065` | Bordure de **contrôle** sur fond clair |
| `--line-on-navy` | `#8A94A3` | Bordure de contrôle sur bleu marine |

Un filet purement décoratif n'est pas soumis au seuil de 3:1. Dès qu'il délimite
un **contrôle** — champ, bouton — c'est `--line-strong` qui s'applique.

### Texte sur bleu marine

| Jeton | Valeur | Contraste |
|---|---|---|
| `--text-on-navy` | `#F2EADE` | **13,50:1** |
| `--text-on-navy-2` | `#C9D0DB` | **10,08:1** |

---

## 2. Typographie

**Une seule famille sans empattement.** La serif d'origine, Iowan Old Style,
donnait au site un air de gabarit générique ; elle a été retirée partout.

Poppins est servie depuis le thème, deux graisses, sous-ensemble latin,
**7 Ko par graisse**. Aucun appel à un service tiers, donc aucune donnée de
visiteur envoyée ailleurs et rien à déclarer de plus dans la politique de
confidentialité. La graisse normale est préchargée : elle porte le texte
visible d'emblée.

Replis : Futura et Avenir Next sur macOS, Segoe UI sur Windows. Tous sont des
géométriques réellement présentes — vérifié, Century Gothic ne l'est pas.

### Échelle

| Jeton | Valeur | Emploi |
|---|---|---|
| `--fs-hero` | `clamp(2.35rem, …, 3.65rem)` | Titre du hero |
| `--fs-h2` | `clamp(1.85rem, …, 2.85rem)` | Titres de section |
| `--fs-h3` | `clamp(1.15rem, …, 1.4rem)` | Sous-titres |
| `--fs-body` | `clamp(.95rem, …, 1.02rem)` | Corps de texte |
| `--fs-lead` | `clamp(1rem, …, 1.12rem)` | Chapô |
| `--fs-num` | `2.125rem` | Numéros en grand corps |
| `--fs-price` | `1.22rem` | Prix en grille |
| `--fs-price-lg` | `clamp(1.55rem, …, 1.85rem)` | Prix en fiche produit |
| `--fs-label` | `.875rem` | **Plancher des étiquettes lues** |
| `--fs-eyebrow` | `.75rem` | Sur-titres en capitales espacées |

Graisses : `--fw-title` 700, `--fw-medium` 500, `--fw-body` 400. Le modèle
oppose un 700 franc à un 400 régulier, sans niveau intermédiaire dans les titres.

> **Défaut corrigé.** Le nom de produit était en 0,92 rem dans la police de
> texte, soit **plus petit que le corps**. C'est l'information principale de la
> carte : il se lit au-dessus du corps, jamais en dessous.

### Taille des pictogrammes

Une seule échelle, appliquée partout. **La taille se pose en CSS, jamais par
l'attribut `width` de l'appel PHP** : une règle CSS prime quel que soit l'appel,
un attribut se laisse oublier lors d'une reprise.

| Emploi | Taille | Épaisseur de trait |
|---|---|---|
| Bandeau d'engagements, en-tête de section | **44 à 48 px** | `1.4` |
| Bouton | **22 px** | `1.7` |
| Lien flèche, lien texte | **20 px** | `1.9` |
| Navigation, panier, menu | **24 à 26 px** | `1.7` |

L'épaisseur du trait **baisse quand la taille monte** : un grand pictogramme
au trait épais paraît lourd à côté du texte.

> **Défaut rencontré.** Le bandeau d'engagements du haut et celui du pied de
> page sont **deux composants distincts**. Une correction appliquée à l'un ne
> touche pas l'autre : le premier est resté à 20 px quand le second passait à
> 48. Vérifier les deux à chaque reprise sur les pictogrammes.

---

## 3. Formes, ombres, mouvement

### Rayons — registre éditorial, pas applicatif

`--r-sm` 8px · `--r-md` 14px · `--r-lg` 20px · `--r-pill` 999px

28 px sur une carte de 340 px lit « application mobile ». Les valeurs ont été
resserrées pour cette raison.

### Ombres — réservées à ce qui flotte réellement

| Jeton | Valeur | Emploi |
|---|---|---|
| `--shadow-card` | `none` | Les composants de contenu sont à plat |
| `--shadow-lift` | `none` | Neutralisé : rien ne se soulève au survol |
| `--shadow-float` | `0 4px 10px …, 0 22px 55px …` | **Tiroir, modale, toast, panneau mobile uniquement** |

### Survol — on change une couleur, pas une position

| Composant | Règle |
|---|---|
| Carte produit | Le contour se renforce, le nom passe en `--gold-ink`. Rien ne bouge |
| Cellule à filet | Le filet supérieur passe à `--navy`. Rien ne bouge |
| Bouton principal | Assombrissement du fond seul |
| Lien flèche | La flèche glisse de 4 px — seul mouvement conservé, il dit la direction |

Supprimés : zooms d'image au survol, pastille « Voir la fiche » en fondu
(elle n'existait pas au toucher), transitions sur `all`.

### Mouvement

Un seul effet de révélation pour tout le site : opacité et 22 px, 600 ms,
visible sans JavaScript, respectant `prefers-reduced-motion`. La cascade est
plafonnée à 4 index, soit 280 ms — douze cartes produisaient une vague de 840 ms.

---

## 4. Composants

### Hero en cadre

Le hero est un **cadre arrondi** (28 px) posé dans la colonne de contenu, fond
sable `--ivory`, 58 / 42, **600 px de haut** sur bureau. Le portrait remplit sa
colonne, hors flux, avec un fondu sable côté texte et un voile sombre sous la
signature. En mobile le cadre empile le portrait (360 px) au-dessus du texte.

> **La hauteur est un `min-block-size`, pas un `block-size`.** Le cadre porte
> `overflow:hidden` pour arrondir le portrait ; une hauteur stricte coupait le
> texte dès qu'il dépassait (676 px de contenu à 1280). En minimum, le cadre fait
> 600 px dans le cas normal et grandit plutôt que d'amputer une ligne.

Le texte du cadre est **inséré** par la marge intérieure du cadre : c'est le bord
du cadre qui s'aligne sur la colonne, pas le texte. C'est le comportement attendu
d'une carte.

### Cellule à filet — la grille par défaut

```
border-block-start: 1px solid var(--line-mid)
padding-block-start: 1.5rem
padding-inline: 0        /* ce n'est pas une boîte */
background: transparent
border-radius: 0
box-shadow: none
```

**Règle d'agence, corrigée deux fois sur un autre projet :** pas de cartes
grises bordées, pas de petits « 01 » en capitales rouges au-dessus des titres.
Les numéros se posent en **grand corps, sans zéro devant**.

Quand une catégorie, un produit, un engagement et une question portent tous le
même contour, l'œil ne hiérarchise plus rien.

### Ce qui a encore droit à une boîte

Logos, formulaires, bandeaux d'appel à l'action, et **cartes portant une photo**.
Rien d'autre.

### Carrousel

Défilement horizontal natif avec points d'ancrage. Le geste tactile fonctionne
sans JavaScript et toutes les cartes restent dans le HTML, donc indexables.

> **Piège vérifié.** `grid-auto-columns` en **pourcentage** se résout sur le
> conteneur puis se répartit entre les colonnes implicites : les cartes tombaient
> à 139 px en 1440 px de large et la piste ne défilait pas du tout. Une piste de
> carrousel se dimensionne en **unités absolues**, jamais en pourcentage.

### Libellés de bouton

« Ajouter à ma sélection » demandait 194 px de texte pour une boîte de 162 à
183 px dès trois colonnes : les dix-neuf boutons étaient coupés. Le libellé
visible est **« Ajouter »**, le nom du produit passe dans le nom accessible.

**Jamais de troncature par points de suspension sur un bouton** : un bouton dont
le texte est coupé ne dit plus ce qu'il fait.

---

## 5. Gabarits — le piège des types de contenu

Un type de contenu avec taxonomie a besoin de **deux** gabarits, jamais d'un seul.

| Gabarit | Couvre |
|---|---|
| `archive-produit.php` | `/produits/` — l'archive du type de contenu |
| `taxonomy-categorie_produit.php` | `/categorie-produit/<slug>/` — **ses catégories** |

Sans le second, WordPress retombe sur `archive.php`, le repli générique du blog :
produits en vignettes de journal, sans carte, sans prix, sans bouton.

> **Piège vérifié (2 octobre 2026).** WordPress pose sur `<body>` des classes
> nommées d'après le gabarit : `single-produit`, `archive-produit`,
> `page-ma-selection`… Une classe de mise en page du même nom s'applique alors
> au document entier. `.single-produit{display:grid;gap:…}` posait 40 px d'écart
> entre l'annonce, l'en-tête, la bannière et le pied de la fiche produit, et un
> blanc au-dessus de l'annonce. Les conteneurs de page s'appellent désormais
> `produit-page`, `shop-page` : **jamais le nom d'un gabarit**. Les sélecteurs
> `body.single-produit …` restent légitimes pour cibler une page.

## 5 bis. La marge unique `--bord` — et la règle qui va avec

Tout le site s'aligne sur une seule marge :

```
--bord: max(var(--gutter), calc((100vw - var(--container)) / 2))
```

En dessous de 1280 px plus deux gouttières, c'est la gouttière qui décide ; au-delà,
c'est le centrage du conteneur. L'en-tête, les sections, les bandeaux et le pied
de page la portent tous. Vérifié aligné de 1024 à 3200 px.

> **Règle absolue.** Un bloc qui porte `padding-inline: var(--bord)` **ne porte
> jamais** de `max-width` ni de `max-inline-size`. En `border-box`, la marge est
> comptée dans le maximum : sur un écran de 2722 px, `--bord` vaut 721 px, et un
> bloc plafonné à 832 px ne laissait que 71 px à son texte. La section Formations,
> plafonnée à 1408 px, tombait à **zéro**. Ce sont les **enfants** qui portent
> leur mesure (`20ch`, `40ch`, `34rem`), jamais le bloc qui porte la marge.

> **Piège vérifié.** Ce défaut est invisible en dessous de 1500 px : mes tests
> allaient jusqu'à 1920 sans mesurer la largeur du texte. Le gérant l'a vu sur un
> écran de 2722 px. Toute vérification de mise en page inclut désormais 2722 et
> 3200 px, et mesure la **largeur du titre**, pas seulement les bords.

Note : `100vw` compte la barre de défilement quand elle est visible (Windows,
Linux). L'écart est de 8 px par côté au pire, invisible ; `100%` n'est pas
utilisable car la marge est aussi posée sur des blocs imbriqués dont le bloc
englobant n'est pas la fenêtre.

---

## 6. Images

### Attribut `sizes`

Depuis WordPress 6.7, le mot-clé `auto` est préfixé à `sizes` dès qu'une image
est en chargement différé. Le navigateur résout alors la largeur sur la boîte
mise en page et **ignore les conditions écrites à la main**. Un filtre dans
`inc/seo.php` le retire lorsque le gabarit a fourni ses propres conditions.

### Visuels produits

Les visuels doivent être **homogènes en échelle**. À l'origine, le produit
occupait de 11 % à 56 % de son cadre selon la référence, soit un rapport de 1 à 5
dans la même grille : l'œil lit cet écart comme un défaut de fabrication.
`tools/normaliser-visuels.py` détoure, recadre et repose chaque produit à
occupation constante, autour de **34 %** (bornes 86 % en hauteur et en largeur).

> **26 % jusqu'au 2 octobre 2026.** À cette échelle, dans le cadre mobile de la
> fiche produit (227 px de haut à 375 × 667, imposé pour garder prix et bouton
> au-dessus du pli), le produit ne mesurait plus que 59 px. Passé à 34 % : le
> produit gagne un tiers partout, la grille reste aérée. Si le cadre mobile
> paraît encore trop petit, c'est l'occupation qu'on relève, jamais un
> `object-fit: cover` qui couperait les flacons hauts.

> **Depuis le 2 octobre 2026**, 19 des 26 visuels sont des packshots de synthèse
> (Higgsfield, `nano_banana_2`) produits à partir des emballages réels — ancien
> visuel filigrané ou affiche du gérant — sur fond blanc pur, puis normalisés.
> Le détail, les prompts et la question de droit sont dans `_velix/medias.md`.
> La photo du stock réel de Fati reste la cible : un packshot généré est un
> intérim, pas une preuve.

> **Les packshots sont sur fond blanc** : le média de la carte produit et celui
> de la fiche sont donc en `--surface`, jamais en `--ivory`, sinon un rectangle
> blanc apparaît autour du produit.

---

## 7. Ce qu'il ne faut jamais faire

1. **Fusionner `--gold` et `--gold-ink`.** Le premier est illisible en texte.
2. **Réintroduire la carte grise bordée** comme composant de grille par défaut.
3. **Poser un petit « 01 » rouge en capitales** au-dessus d'un titre.
4. **Dimensionner une piste de carrousel en pourcentage.**
5. **Tronquer le libellé d'un bouton** par des points de suspension.
6. **Animer l'élément LCP** : le hero ne porte jamais l'effet de révélation.
7. **Remettre une ombre sur un composant de contenu.** Elle est réservée à ce
   qui flotte : tiroir, modale, toast, panneau mobile.
8. **Annoncer une promesse non vérifiée** — « livraison offerte dès 50 € » et
   autres seuils tant que la cliente n'a pas fixé ses tarifs.

---

## 8. Cibles de qualité

| | |
|---|---|
| Contraste | WCAG 2.2 niveau AA — 4,5:1 en texte, 3:1 en gros texte |
| Largeurs testées | 375, 768, 1024, 1440 px |
| À chaque vague | zéro erreur de console, zéro débordement horizontal, zéro image cassée |
| Titres | un seul `h1` par page |

La vérification se fait au navigateur, jamais en lisant le code : une
compilation réussie n'est pas une preuve.
