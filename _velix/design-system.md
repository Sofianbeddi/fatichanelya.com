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

Une seule grammaire d'apparition pour tout le site (thème 1.4.0) : fondu et montée de 16 px,
700 ms sur la courbe `--ease-out`, **en animation et non en transition** pour que les transitions
propres des composants (contour de carte, couleur du nom, flèche qui glisse) gardent la main au
survol. Visible sans JavaScript, sans `IntersectionObserver` et en `prefers-reduced-motion`
(`.js-reveal` n'est posée qu'avec l'observateur). La cascade est plafonnée à 4 index, soit
280 ms — douze cartes produisaient une vague de 840 ms. Détail dans la section 1.4.0.

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
9. **Écrire une propriété physique** (`left`, `margin-left`, `text-align: left`, `float: right`,
   `to right`) : la feuille est logique de bout en bout, le RTL en dépend (section 1.4.0).
10. **Nommer une classe comme une classe de `<body>` WordPress** (`search`, `single-produit`,
    `archive-produit`, `page-panier`…) : le style s'applique au document entier.

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

## Panier et parcours d'achat (thème 1.2.0, 2 oct. 2026)

Demande du gérant : aucun parcours bizarre, chaque clic a une raison, et une vraie manipulation
e-commerce. Ce qui en découle, à ne pas défaire :

- **Une carte produit mène à la fiche.** Visuel et nom sont des liens. Pas de fenêtre par-dessus
  la page pour montrer un produit : la fiche existe, c'est elle qu'on ouvre.
- **Un seul sélecteur de quantité**, `.qty` (« − n + »), partout : carte (`.qty--plein`, bleu marine,
  il remplace le bouton « Ajouter » dès que le produit est au panier), fiche (`.qty--lg`, choisi
  avant l'ajout), tiroir (`.qty--sm`), page Panier.
- **Un ajout se voit trois fois** : le visuel file vers l'icône du panier (`.fly`), la pastille
  rebondit (`.is-bump`), une confirmation nomme le produit et donne « Voir le panier » (`.toast`,
  en bas sur mobile, sous l'en-tête sur grand écran). Tout est coupé en `prefers-reduced-motion`,
  sauf la confirmation.
- **Aucun lien vers une ancre absente, aucun `href="#"`.** Un bouton WhatsApp sans numéro mène à la
  page Contact. L'icône du panier est un lien vers la page Panier que le script intercepte.
- Le mot est **« panier »**, pas « sélection », dans tous les contrôles.

## Points clés d'une fiche produit

Liste cochée de trois faits, sous l'accroche (méta `_fati_points`, une ligne par fait). Coche en
`--gold-ink`, texte en `--ink`, corps courant. **Sur mobile la liste passe sous le bouton**
(`order:1`, comme l'accroche) : prix et bouton restent visibles sans défiler à 375 × 667.

> **Ce que la liste contient, et ce qu'elle ne contient jamais.** Des faits lisibles sur
> l'emballage : composition, format, façon d'utiliser. Jamais un effet sur la santé, même repris
> d'une affiche de la marque : c'est interdit pour une boutique dans l'UE, et le site promet par
> écrit de n'en faire aucun. `tools/rediger-catalogue.py` refuse les mots concernés.

## Piège : une variable de couleur ne prend pas de suffixe d'opacité

`var(--navy)80` ou `var(--surface)F0` ne sont pas des couleurs : la déclaration est invalide et
le navigateur l'ignore sans rien dire. C'est ce qui a laissé l'en-tête collant transparent
jusqu'au thème 1.2.1 (illisible sur les bandes bleu nuit) et le voile du tiroir sans teinte.
Pour une couleur du système avec de la transparence :
`color-mix(in srgb, var(--navy) 50%, transparent)`. Vérifier le fond **calculé** au navigateur,
et toujours regarder l'en-tête collant au-dessus d'une bande bleu nuit, pas seulement en haut de page.

## Thème 1.3.0 (25 oct. 2026) — commande WhatsApp, scène Formations, À propos, pied de page

### Première version sans paiement en ligne
Décision du gérant : ni carte ni PayPal pour l'instant, la commande se finalise sur WhatsApp. Le site
ne dit plus « le paiement en ligne arrive » ni « interface PayPal prête » : il décrit le parcours réel
(« Rien ne se paie sur le site : Fati confirme disponibilité, frais de port et total, puis indique le
moyen de règlement »). Textes concernés : bandeau d'engagements (icônes `shield`, `whatsapp`,
`support`, `leaf`), intro de la boutique, engagements détaillés, FAQ (nouvelle entrée « Comment se
règle la commande ? »), page Panier, tiroir, fiche produit, parcours de commande. **Ne jamais inventer
un moyen de règlement** (virement, Bizum…) tant que Fati ne l'a pas dit : la FAQ reste générique.
Les pages légales gardent leurs marqueurs `[[À ACTIVER AVEC PAYPAL]]` : elles ne sont pas publiables
en l'état de toute façon.

### Scène Formations (accueil)
Plus de liste à filets de 1 500 px à côté d'une nature morte. À partir de 1000 px : **rail** à
gauche (filets, titre, chip niveau · durée, flèche sur chaque ligne — or sur la ligne active),
**affiche du programme choisi** à droite dans un cadre de 28 px avec une **capsule de verre**
(`color-mix` navy 55 % + flou 14 px, contour blanc 18 %, rayon pilule) qui ne porte que le bouton
« Voir le programme ». Thème 1.3.1, retour du gérant sur la 1.3.0 : **ni numéros dans le rail, ni
texte sur l'affiche** — titre et chip sont déjà dans le rail, le résumé est sur la fiche, l'affiche
doit rester lisible. Chip, titre et résumé restent dans le balisage pour la carte mobile. Le rail
est une liste de **liens vers les fiches** ; le JavaScript lui donne les rôles `tablist`/`tab`/`tabpanel` et le clic change l'affiche
(flèches, Début, Fin au clavier ; cmd/ctrl-clic ouvre la fiche). L'état actif est posé par PHP sur la
première : sans script, la scène montre le premier programme et chaque clic mène quelque part.
Sous 1000 px : rail masqué, piste en **carrousel** (cartes 17,5 rem puis 21 rem, affiche 4:3 au-dessus
du texte sur fond bleu nuit), barre de progression et compteur.

> **Pièges vérifiés.** (1) Un enfant de grille ne descend pas sous sa largeur de contenu : la piste
> élargissait la page de 1 400 px sur mobile → `min-inline-size:0` sur les enfants de `.programmes`.
> (2) `.programmes-nav{display:none}` était battu par `.carousel-nav{display:flex}` déclaré plus bas
> dans le fichier : masquer avec `.programmes-scene .carousel-nav`. (3) Les affiches inactives sont en
> `display:none`, pas en `opacity:0` : six panneaux de verre superposés en opacité 0 restaient visibles
> dans certaines captures du compositeur. L'affiche entrante fait un fondu de 280 ms (`prog-fondu`).
> (4) `ul{list-style:none}` du reset ne couvre pas `ol` : le rail et les listes numérotées de l'À
> propos posent leur propre `list-style:none`.

Le réglage « Photo des formations » (`training_image`) est retiré du back-office : la section ne
l'affiche plus. Les affiches viennent de l'image mise en avant de chaque formation.

### Carrousel : barre de progression et compteur
Un carrousel se voit comme tel. `.carousel-nav` porte désormais, avant les flèches, une barre
(`.carousel-barre`, curseur de la longueur de l'écran visible, position = défilement) et un compteur
« 1 / 8 » (`.carousel-compte`). Le script les tient à jour dans `[data-carousel]` ; ils sont
`aria-hidden` (les flèches et la liste défilante portent déjà l'accessibilité). Appliqué à l'aperçu
boutique et à la scène Formations en mobile.

### Page À propos
Seule page intérieure **sans bannière de titre** : son hero éditorial reprend le cadre arrondi de
l'accueil (28 px, sable, 600 px minimum, 52/48) avec la **photo large de Fati** (`about_hero`, nouveau
réglage ; repli sur `about_image`) à droite, fondu sable côté texte, voile sombre sous la signature,
`object-position:100% 30%` pour garder le tiers droit où elle se trouve. À gauche : fil d'Ariane,
sur-titre, `h1` = titre « À propos » du back-office, intro, « Voir la sélection » + « Écrire à Fati »,
trois faits à point doré. La photo est l'élément LCP : `fetchpriority=high`, jamais différée, aucune
révélation animée. Puis : trois colonnes à filets et numéros (liens ancrés en bas par
`grid-template-rows:auto auto 1fr auto`), bande sable portrait + valeurs (phrase de clôture ancrée en
bas par `margin-block-start:auto` : les deux colonnes finissent à la même hauteur), bande bleu nuit
« Ce que je ne fais pas » en grand corps sur deux colonnes, appel à écrire. Tous les faits de la page
existent déjà ailleurs sur le site ; le parcours daté n'est toujours pas inventé.

### Pied de page
Deux colonnes (marque et contact · les deux menus) puis une ligne copyright + « Site conçu par Velix
Digital ». À la demande du gérant (thèmes 1.3.2 et 1.3.3) : **pas de colonne « En bref », pas de
mention santé dans le pied** (obligatoire pour des compléments, elle reste sur la fiche produit,
`.produit-legal`), et **plus de nom en très grand corps** en bas (« pas ergonomique »). Restent le
filet d'or en tête et le halo radial dans l'angle.

### Icônes : les logos de marque sont des aplats
Le pictogramme WhatsApp est le **glyphe officiel** (tracé Simple Icons), rendu `fill:currentColor`
sans trait ; `fati_icon()` tient la liste `$pleins`. Une version redessinée au trait « n'était plus
l'original » pour le gérant : ne jamais réinterpréter un logo de marque. Le reste du jeu reste au
trait. Dans le bandeau de réassurance, chaque icône est posée sur un **disque or clair** de 56 px
(`--gold-soft`, icône bleu nuit, trait 1,6) et les trois titres partent de la même ligne
(`align-items:start`) quel que soit le nombre de lignes de la description.

## Thème 1.4.0 (27 oct. 2026) — socle LTR / RTL, responsive, compatibilité, mouvement

Quatre chantiers demandés par le gérant avant la phase de traduction. Rien ne change au rendu
français au repos : la comparaison pixel à pixel avant / après sur 30 captures ne montre que les
corrections voulues (page de recherche, micro-textes à 12 px, barre d'annonce à 44 px au toucher).

### Bidirectionnel

La feuille était déjà écrite en propriétés logiques. Ce qui restait physique est passé par trois
variables : `--dir` (±1, rotations et translations : chevron de la FAQ, flèches qui glissent),
`--to-end` (angle des fondus des portraits : `to right` devient 270deg en RTL), `--origin-x`
(origine des soulignements d'or et des filets qui se tracent). Les icônes `arrow` et `external`
se lisent en miroir en RTL (`[dir="rtl"] :is(.i--arrow,.i--external)`), le bouton « précédent »
du carrousel garde sa rotation par-dessus. WordPress pose `dir="rtl"` sur `<html>` dès que la
langue du site est RTL (`language_attributes()`) : rien d'autre à brancher.

> **La photo de l'À propos garde son côté droit.** Fati est dans le tiers droit de l'image
> (`object-position:100%`) ; en miroir elle passerait sous le fondu sable. En RTL le cadre inverse
> ses colonnes (`order:-1` sur la photo), le fondu et la signature changent de bord. Le portrait du
> hero d'accueil, centré, se miroite sans correction.

> **Défaut corrigé au passage.** `transform-origin:inline-start` n'est pas une valeur valide : la
> déclaration était ignorée et les soulignements d'or partaient du centre. `var(--origin-x) 50%`.

**Typographie arabe : Cairo** (variable 200–1000, licence OFL, 30 Ko, `assets/fonts/`), une
géométrique dessinée pour s'accorder aux sans-serif latines — elle prolonge Poppins. Chargée par
`unicode-range` seulement si la page contient de l'arabe ; Poppins garde le latin, les chiffres et
la marque. En RTL : `letter-spacing:0!important` (les −.03em des titres rendraient l'écriture liée
illisible ; la marque « Fatichanelya » garde son −.045em), interlignes 1,8 (texte) et 1,35
(titres). JavaScript : le carrousel raisonne en distance depuis le bord de départ
(`Math.abs(scrollLeft)`, sens de `scrollBy` inversé), les flèches gauche / droite des onglets de
formation suivent le sens de lecture, le tiroir s'ouvre depuis la gauche (animation `tiroir-rtl`,
déjà présente), la confirmation d'ajout se pose près de l'icône du panier quel que soit le sens.

> **Point pour la traduction.** `.hero h1 em` est en `display:block` : une ponctuation placée après
> le `*mot en or*` tombe seule sur une troisième ligne (vu avec le texte arabe d'essai). Garder la
> ponctuation finale à l'intérieur du segment accentué, ou l'omettre.

### Responsive

Audit Playwright sur 26 largeurs de 320 à 3200 px et 15 pages (`_velix/outils/recette/`). Corrigé :
la page de résultats de recherche était détruite (classe `search` posée par WordPress sur `<body>`
et stylée comme le champ de recherche → `.search-form` + gabarit `search.php` en cartes produit) ;
« Pagination des publications » affiché en titre (`.screen-reader-text` non masquée) ; fiche
produit à 320 px, quantité et bouton d'ajout ne tenaient plus sur une ligne (294 px pour 280 :
retour à la ligne sous 360 px) ; panneau du menu mobile sans hauteur maximale (paysage) ; champ
quantité de la fiche à 44 px ; micro-textes sous 12 px (barre d'annonce, chips, en-têtes de
tableau) relevés ; lien de la barre d'annonce à 44 px au toucher. Les titres mesurent 586 à 660 px
à 2722 et 3200 px : la règle `--bord` tient.

### Compatibilité

Socle, inventaire et replis dans `_velix/compatibilite.md`. Replis hexadécimaux devant chaque
`color-mix()`, `:focus` pour les Safari sans `:focus-visible`, champs à 16 px minimum au toucher
(zoom iOS), `addListener` pour les vieilles `MediaQueryList`. Testé en Chromium 145, WebKit et
Firefox 157 : aucune anomalie sur les 15 pages, LTR et RTL.

### Mouvement

- **Cartes produit** : la carte monte (16 px, 700 ms), puis le visuel se pose d'un zoom de 6 %
  (1 100 ms, propriété `scale` pour se composer avec le zoom au survol qui reste en `transform`).
- **Vignettes de catégories** : s'épanouissent de 90 % à 100 % (900 ms).
- **Têtes de section** : sur-titre + titre, puis chapô ou lien, à 0 / 90 / 180 ms. Classe
  `reveal-head` posée par le JavaScript sur les têtes qui portent un `h2` — jamais un `h1`
  (élément LCP d'une page intérieure).
- **Cellules à filet** (`.rule-cell`, cartes de formation, engagements) : le filet se trace depuis
  le début de ligne (pseudo-élément, le vrai bord devient transparent ; le survol passe par lui).
- **Parallaxe** discrète (±4 %, compositeur) sur les affiches de formation seulement
  (`@supports (animation-timeline:view())`) : ni opacité, ni élément LCP — le hero et le visuel
  d'une fiche n'en reçoivent pas, et la réserve notée plus haut sur `view()` (élément bloqué en
  opacité 0 sans défilement) ne s'applique pas à une translation.
- Observateur à `threshold:0` (un bloc plus haut que l'écran apparaissait tard à 0,1).
- Les cartes hors champ d'un carrousel n'apparaissent qu'en entrant dans l'écran (ou après le
  repli de 3 s) : normal, et visible dans l'audit comme « notShown » sur l'accueil.

**Captures** : `captures/apres-1.4/` (accueil, fiche, À propos, panier en RTL ; accueil avec texte
arabe d'essai ; états interactifs ; recherche avant / après ; accueil en WebKit).

## Thème 1.5.0 (27 oct. 2026) — menu mobile sous l'en-tête, filtres collants, boutons de catégories à visuel

Trois retours du gérant sur captures de la préversion 1.4.0.

### Menu mobile
Le panneau était fixé à 122 px (barre d'annonce + en-tête plein) : une fois l'en-tête réduit à
66 px, il s'ouvrait 56 px plus bas et laissait voir la page entre les deux. Il vit désormais
**dans `<header>`**, en absolu à `inset-block-start:100%` : il suit la hauteur réelle de l'en-tête
(84 px, 66 px réduit, barre d'admin comprise) sans calcul. Le script borne sa hauteur à l'écran
(`--panel-max`), bloque le défilement de la page tant qu'il est ouvert, le ferme sur Échap et
quand on repasse en grand écran.

### Filtres et recherche collants (boutique et pages de catégorie)
`.shop-controls` est `position:sticky` sous l'en-tête : le script publie la hauteur de celui-ci dans
`--header-h` (ResizeObserver, 84 → 66 px en douceur), la barre d'admin dans `--admin-h`. Fond
sable opaque, filet sous le bloc, les cartes passent dessous. Sur mobile (< 700 px) : la recherche
d'abord, pleine largeur, puis les filtres **en une seule ligne qui défile au doigt de bord à bord**
(marges négatives = `--bord`), bloc d'environ 110 px.

> **Piège vérifié.** En colonne, un conteneur flex `wrap` prend pour largeur de ligne celle de son
> plus large enfant : la rangée de filtres (700 px, `nowrap`) élargissait la ligne et la recherche
> s'étirait sur 735 px, hors écran. `flex-wrap:nowrap` sur le conteneur en colonne, `min-inline-size:0`
> sur la rangée.

### Boutons de catégories à visuel — un composant, trois emplois
`template-parts/components/categorie-chips.php` : les mêmes pilules que les filtres, avec le visuel
du terme (`_fati_visuel`, vignette de 150 px dans un rond de 28 px, 34 px sur l'accueil mobile).
- **Accueil, mobile et tablette (≤ 980 px)** : rangée posée **au-dessus du cadre du hero**, juste
  sous l'en-tête (124 px du haut à 390 px) — les catégories se choisissent sans défiler. Liens vers
  les pages de catégorie, « Tout voir » vers la boutique. Pour raccourcir le hero : portrait à
  300 px au plus (360 avant) et **les trois faits du hero sont retirés sur mobile** (le bandeau de
  confiance, juste dessous, porte la même réassurance). Masquée sur grand écran : les ronds de
  « Nos catégories » la portent.
- **Boutique** : boutons de filtre (JavaScript), « Tout voir » actif par défaut.
- **Pages de catégorie** : liens vers les autres catégories, la courante marquée (`is-active`,
  `aria-current`) ; la recherche filtre la grille de la catégorie (`#product-grid` posé).
  Le script ne s'attache qu'aux `.chip-button[data-filter]` : un lien ne vide plus la grille au clic.

**Captures** : `captures/apres-1.5/` (accueil mobile premier écran, boutique et catégorie à 1440,
boutique mobile défilée, menu mobile en haut de page et en-tête réduit).

## Thème 1.6.0 (27 oct. 2026) — cercles de catégories

Retour du gérant sur la préversion 1.5.0 : « quatre catégories, donc je préfère les afficher une seule
fois, en quatre colonnes, quatre cercles ; je sélectionne un cercle, ou je désélectionne tout pour tout
afficher, avec l'image de la catégorie mise en valeur et bien visible en mobile ».

### Le dessin
Le composant `categorie-chips.php` garde son nom et ses trois emplois, mais rend des **cercles** :
le visuel du terme dans un rond (`.cat-circle-media`), le nom dessous. Le rond porte un liseré blanc de
2 px (bordure) et un filet sable à l'extérieur ; **sélectionné**, le filet devient un anneau bleu nuit de
2,5 px et le liseré blanc fait le jour — même dessin sur sable (boutique) et sur beige (hero). Survol :
filet plus sombre et rond à 104 %. Focus clavier : anneau or. Sans visuel, l'initiale du nom en bleu nuit.
Pas de bouton « Tout voir » : **un cercle se sélectionne, le même se désélectionne** et tout s'affiche.
Sur une page de catégorie, le cercle courant (marqué, `aria-current`) **mène à la boutique** — c'est la
désélection, en lien.

### Les tailles
- **Mobile (< 700 px)** : grille de `--n` colonnes égales (`--n` posé par le gabarit, 4 aujourd'hui),
  rond `clamp(56px, 18vw, 74px)` — 70 px à 390, 58 à 320 — nom en `clamp(.62rem, 2.75vw, .72rem)`
  (10 à 11,5 px : étiquette sous icône, exception assumée à la règle des 12 px). Le nom qui ne tient
  pas se coupe **au trait d'union** (`hyphens:auto`, « Alimenta-tion » à 320 px) ; `text-wrap:balance`
  a été écarté : combiné à `overflow-wrap:anywhere`, il coupait « Complément-s ».
- **Boutique, grand écran (≥ 700 px)** : le bloc est collant, donc il reste bas — rond de 48 px et nom
  à côté, sur deux lignes au plus (`max-inline-size:9em`) ; bloc de 77 px.
- **Accueil mobile et tablette (≤ 980 px)** : ronds plus grands (`clamp(60px, 19vw, 78px)`, 74 px à
  390), noms en bleu nuit, colonnes bornées à 150 px entre 600 et 980 px pour ne pas s'éparpiller.
  Toujours dans le premier écran (128 px du haut à 390 × 667).

### Le coût
Le bloc collant de la boutique mobile passe de 110 à **180 px** à 390 (recherche + quatre cercles avec
nom). C'est le prix des cercles « bien visibles » demandés ; si le gérant le trouve lourd au défilement,
la première piste est de ne garder collante que la recherche.

**Captures** : `captures/apres-1.6/` (boutique 390 au repos et avec « Compléments » sélectionné,
défilée, 768 et 1440 ; accueil 390 et 320 ; page de catégorie ; WebKit et Firefox).
