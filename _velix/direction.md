# Direction artistique — Fatichanelya

*Écrit le 14 septembre 2026 · Auteur : velix-da · Mode : **montée en gamme**, pas refonte d'identité*

La maquette v3 a été validée par le gérant et par la cliente, puis convertie en thème WordPress qui
tourne aujourd'hui. **La palette, les polices et l'esprit sont acquis et ne se rediscutent pas.** Ce
document ne propose donc pas de directions alternatives : il n'y a pas de choix à faire sur l'identité,
il y a un écart à combler entre « correct » et « premium ».

Ce document répond à une seule question : **qu'est-ce qui empêche concrètement ce site de paraître
premium aujourd'hui, et quelles règles visuelles le feraient basculer ?**

Tous les contrastes cités ont été **calculés** (formule WCAG 2.x sur les valeurs hexadécimales
réelles), pas estimés. Les valeurs figurent dans la section 4.

`velix-front` applique. **Aucun fichier de thème n'a été modifié par cette intervention.**

---

## 0. Ce qui est acquis et ne se rediscute pas

| Décision v3 | Valeur |
|---|---|
| Fond de page | Ivoire chaud `#FBF8F3` |
| Bandes alternées | Beige `#F3EDE3` |
| Surfaces | Blanc `#FFFFFF` |
| Surfaces fortes, en-tête, pied de page | Bleu nuit `#16233D` |
| Texte | Noir doux `#23201C` · secondaire `#605950` |
| Or — fonds et décor | `#C39A4E` |
| Or — texte sur fond clair | `#8A6521` |
| Titres | Iowan Old Style (repli Palatino / Georgia) |
| Texte | Avenir Next (repli Segoe UI / system-ui) |

**Les deux ors ne fusionnent jamais.** `#C39A4E` sur ivoire mesure **2,46:1** — il échoue au contraste
pour du texte. `#8A6521` sur ivoire mesure **5,00:1** — il passe. C'est la raison d'être de la
séparation, et c'est aussi le piège le plus probable de la reprise : un développeur qui trouve deux
variables d'or « en double » et les unifie casse l'accessibilité de tout le site clair d'un seul coup.

**Aucune police web.** Le site charge 0 octet de fonte et n'a aucun clignotement au chargement. C'est
un acquis de performance, pas une contrainte de budget. Ne pas proposer d'alternative.

---

## 1. Le diagnostic

Le site n'est pas raté. Il est **générique** : il ressemble à un bon thème acheté, pas à un objet
conçu pour Fati. Voici précisément où ça se joue.

### 1.1 Tout est une boîte, donc rien n'est important

C'est le défaut structurant, et il traverse le site entier. En relisant `main.css`, on compte
**huit familles de composants** qui répètent exactement la même recette : fond blanc ou translucide,
bordure 1 px, rayon, ombre, puis translation vers le haut au survol.

| Composant | Ligne CSS | Recette |
|---|---|---|
| `.universe-card` | 262 | `border:1px solid var(--line)` + `r-lg` + `translateY(-4px)` + `shadow-lift` |
| `.product-card` | 305 | `border:1px solid var(--line)` + `r-md` + `translateY(-4px)` + `shadow-lift` |
| `.training-list li` | 344 | `border:1px solid var(--line)` + `r-md` + `translateX(4px)` |
| `.pledge-grid li` | 394 | `background:#FFFFFF0F` + `border:1px solid var(--line-light)` + `r-md` |
| `.faq-list details` | 403 | `border:1px solid var(--line)` + `r-md` |
| `.journal-card` | 376 | `r-lg` + `translateY(-4px)` + `shadow-lift` |
| `.about-stat` | 366 | `r-md` + `shadow-card` |
| `.training-media figcaption` | 341 | `r-md` + `shadow-lift` |

Quand huit composants de nature différente — une catégorie, un produit, un programme, un engagement,
une question — portent le même contour et la même ombre, l'œil ne hiérarchise plus rien. **Le site
devient une nappe de rectangles.** C'est exactement ce que le gérant a fait corriger deux fois sur
APE Maroc, et la préférence est maintenant au playbook.

Le luxe n'ajoute pas de contour, il en retire. Une marque premium sépare par **le filet, le blanc et
le changement de fond** — jamais par la boîte.

### 1.2 L'échelle typographique s'écrase au milieu

Le hero est correctement dimensionné (`--fs-hero` monte à 4,7 rem), mais **tout le reste vit entre
0,66 rem et 1,4 rem**. Il n'existe aucun palier intermédiaire entre le titre de section et le texte
courant.

Conséquence concrète, mesurable dans le CSS actuel :

- `.product-name` est en **0,92 rem, police de texte** — le nom du produit, l'information la plus
  importante de la grille, est plus petit que le texte courant et composé dans la police secondaire ;
- `.product-price` est en 1,15 rem — un prix, dans une boutique, à peine plus gros qu'une légende ;
- `.product-cat` est en **0,66 rem** et `.product-flag` en **0,62 rem**, soit environ 10 px : sous le
  seuil de lisibilité confortable, et dans la liste anti-générique du playbook ;
- `.pledge-grid strong` est en 1,15 rem, `.training-list` sans taille propre.

Un site premium assume un **écart de 6 à 10×** entre le plus grand et le plus petit. Ici l'écart utile
(hors hero) est d'environ 2×. C'est le symptôme visuel numéro un du « fine but not premium ».

### 1.3 La fiche produit est vide là où l'achat se décide

C'est le constat le plus coûteux commercialement. `single-produit.php` produit, sous la ligne de
flottaison : un fil d'Ariane, une puce de catégorie, un titre, un prix, le contenu de l'éditeur (souvent
deux lignes issues du CSV), deux boutons, la mention santé. **Puis le pied de page.**

Il manque tout ce qui fait acheter un complément à 61 € ou un coffret à 200 € :

- la **contenance et la durée d'usage** (« 90 gélules ≈ 45 jours ») — le brief la désigne comme la
  preuve qui rend un prix acceptable, et elle est absente ;
- le **conseil d'utilisation** structuré, pas noyé dans un paragraphe ;
- la **réassurance** (expédition depuis l'Espagne, réponse de Fati, paiement à venir) ;
- les **produits associés** de la même catégorie ;
- un **rappel du prix et du bouton** en bas de page, quand l'utilisateur a fini de lire.

La page qui doit convertir est la plus pauvre du site. Aucune règle typographique ne compense ça :
c'est un problème de contenu et de structure avant d'être un problème de style.

### 1.4 Les visuels produits ne sont pas exploitables en l'état

Vérification faite sur les 18 fichiers de `tools/medias/produits/`. Le format est homogène
(620×620, WebP, 3 à 16 Ko), **mais le contenu ne l'est pas du tout** :

- **Filigrane « DXN's Property » incrusté** sur au moins `morinzhi`, `rg-90`, `divine-night-oil`,
  en diagonale répétée sur toute l'image. C'est un problème **juridique et de crédibilité**, pas
  esthétique : afficher en boutique une photo qui porte la mention « propriété de DXN » signale que
  le visuel n'appartient pas à la vendeuse. Sur une marque personnelle dont l'argument est
  l'authenticité, c'est un contresens complet.
- **Fonds hétérogènes** : blanc pur sur `morinzhi` et `rg-90`, **dégradé gris** sur
  `kallow-cosmetics`. Dans une grille, un fond dégradé au milieu de fonds blancs saute aux yeux.
- **Cadrages et échelles incohérents** : `ganozhi-soap` et `kallow-cosmetics` remplissent le cadre ;
  `morinzhi` et `rg-90` laissent une marge importante. Les produits n'ont pas la même taille apparente,
  alors qu'ils sont alignés côte à côte.
- **Texte incrusté en arabe** sur `natural-shield-deo` (trois vignettes d'ingrédients légendées
  المريمية / الخيار / الألوفيرا). Sur un site dont la langue de référence est le français, c'est une
  image qui parle une autre langue que sa fiche, et qui ne se traduira jamais.
- **Composition multi-produits** sur `kallow-cosmetics` (six flacons) là où les autres montrent un seul
  article : la carte ne dit plus ce qu'on achète.
- **Un placeholder** « visuel à venir » pour le Lingzhi Black Coffee (19ᵉ référence).

Aucune règle CSS ne rattrape ça. `object-fit:cover` sur `aspect-ratio:1` **recadre** des images déjà
mal cadrées et coupe les bords des produits les plus remplis. Le traitement doit se faire sur les
fichiers — voir section 5.

### 1.5 L'or est utilisé comme décor, pas comme signal

`#C39A4E` apparaît aujourd'hui sur : le soulignement de navigation au survol, le compteur du panier,
le bouton d'achat, le chevron de la FAQ, le filet des valeurs, les mots en emphase des titres, les
puces d'accroche, le bord des citations. **Huit emplois différents.**

Quand l'accent est partout, il n'accentue plus rien — c'est littéralement la règle « Colour as signal »
de la référence. Et c'est ce qui distingue un or « luxe » d'un or « doré bon marché » : la rareté.
Sur un site premium, l'or doit valoir quelque chose parce qu'on le voit trois fois par page, pas trente.

### 1.6 Le mouvement est appliqué partout, y compris là où rien ne change d'état

Six composants portent `translateY(-4px)` + ombre au survol, plus deux zooms d'image à `scale(1.05)`,
plus la pastille « Voir la fiche » qui apparaît en fondu, plus le soulignement de nav, plus le
`translateX(4px)` des flèches. Tout cela **flotte** : c'est le vocabulaire d'un site de 2019, et c'est
précisément ce que la référence range dans « motion on everything, including things that don't change
state ».

Une carte produit qui se soulève ne communique aucun état. Elle dit juste « je suis cliquable », ce que
le curseur dit déjà.

### 1.7 Les pages légales n'ont aucune structure typographique

`page.php` produit un `h1` puis `.prose` brut. La classe `.prose` existe et fait le minimum (68ch,
marges, listes), mais il manque tout ce qui rend un document légal **lisible et rassurant** : pas de
date de mise à jour, pas de sommaire, pas de hiérarchie visible, pas de mesure de lecture confortable,
pas de filets de séparation entre articles. Or les pages légales sont lues par l'acheteuse méfiante —
c'est précisément la cible décrite dans le brief. Une page de mentions bâclée annule le travail de
réassurance fait ailleurs.

### 1.8 L'en-tête flottant recouvre la grille au défilement

`.site-header` est `position:sticky` avec `backdrop-filter:blur(14px)` et un fond `#FBF8F3F0`
(94 % d'opacité). Sur l'archive produits, les cartes passent dessous et restent **partiellement
visibles à travers le flou** : on lit un produit à moitié effacé sous l'en-tête. Le flou de verre
remplace ici la hiérarchie au lieu de la servir, et c'est un autre point de la liste anti-générique.

Par ailleurs `:where(section[id]){scroll-margin-block-start:112px}` est calé sur l'en-tête à 84 px +
barre d'annonce, mais l'en-tête réduit à 66 px une fois collé : l'ancrage est faux dans les deux états.

### 1.9 Verdict sur la checklist anti-générique

| Point | État |
|---|---|
| Dégradé violet-bleu | Non |
| **Rangées de cartes arrondies identiques** | **Oui** — huit familles (§1.1) |
| Hero centré générique | Non — le hero est asymétrique, c'est un point fort |
| Formes 3D / illustrations isométriques | Non |
| **Verre et flou à la place de la hiérarchie** | **Oui** — en-tête (§1.8) |
| **Toutes les sections de même hauteur, même marge** | **Oui** — `--section-y` uniforme partout |
| Emoji en guise d'icônes | Non — jeu d'icônes SVG maison |
| **Texte courant sous 16 px** | **Partiel** — `--fs-body` descend à 0,95 rem ; labels à 0,62-0,66 rem |
| **Motion sur tout** | **Oui** — §1.6 |
| Photo de stock de bureau | Non — mais portrait généré, voir §5 |

**Cinq cases cochées.** Le seuil du playbook est de deux. Ce n'est pas une question de goût : le site
est objectivement dans la zone générique, et c'est réparable sans toucher à l'identité.

---

## 2. Les règles de montée en gamme

Le concept v3 reste le cadre : **« Du conseil avant la pression d'acheter »**. Ce que ces règles y
ajoutent est une exclusion supplémentaire, qui devient la ligne directrice de la reprise :

> **La séparation se fait par le filet, le blanc et le fond — jamais par la boîte.**
> Ce qui est encadré est ce qu'on peut manipuler : un formulaire, un bandeau d'action, une carte
> portant une photo. Tout le reste se compose à plat. Rien ne flotte, rien ne se soulève, rien ne
> brille. L'or ne sert qu'à désigner ce sur quoi on agit ou ce qu'on doit retenir.

### 2.1 Échelle typographique et hiérarchie

**Élargir l'amplitude.** Le palier manquant est entre le titre de section et le texte : il faut un
niveau « chiffre / prix / nom » qui se voie, et des petites choses franchement petites mais lisibles.

| Règle | Application |
|---|---|
| Le prix est une information de premier plan | `.product-price` en Iowan, **1,5 rem** en grille, **2,25 rem** en fiche. Aujourd'hui 1,15 / 1,6 |
| Le nom du produit passe en serif | `.product-name` en `--font-display`, **1,12 rem**, `--ink`. Aujourd'hui 0,92 rem en police de texte |
| Plancher absolu du texte lu | **14 px (0,875 rem)**. Aucune information lue en dessous. Les `0,62` / `0,66 rem` actuels disparaissent |
| Texte courant | `--fs-body` remonte à **1 rem minimum** en bas de plage (aujourd'hui 0,95 rem) |
| Les étiquettes sont en `--muted` ou `--gold-ink` | Jamais en gris clair. Règle de playbook : `disabled` n'est pas une couleur d'étiquette |
| Les numéros en grand corps | **≈ 34 px (2,125 rem)**, graisse normale, Iowan, **sans zéro devant**. `--navy` sur clair, `--gold` sur navy |
| Mesure de lecture | 60-68 ch pour le texte long, 52 ch pour les chapôs |

**Interdit** : petit sur-titre en 11 px capitales colorées au-dessus d'un titre comme substitut de
hiérarchie. L'`.eyebrow` existant (0,75 rem, `--gold-ink`, 5,00:1) est conservé parce qu'il est lisible
et qu'il sert de fil éditorial — mais il ne doit pas se multiplier, et jamais devant un numéro.

### 2.2 Densité et respiration

Le site applique `--section-y` à **toutes** les sections sans exception. C'est le « every section the
same height with the same padding » de la checklist.

Introduire **trois rythmes** et les alterner selon l'argument :

| Rythme | Valeur | Pour |
|---|---|---|
| `--section-y-lg` | `clamp(5rem, 10vw, 9rem)` | Moments narratifs : hero, À propos, Engagement, Contact |
| `--section-y` | `clamp(3.75rem, 7.5vw, 7rem)` | Rythme courant (inchangé) |
| `--section-y-sm` | `clamp(2.25rem, 4.5vw, 3.75rem)` | Bandes d'information : réassurance, filtres, notes |

**Un seul point focal par écran.** En descendant la page, chaque hauteur d'écran doit avoir un
gagnant. Aujourd'hui la grille produits en présente douze à égalité.

**L'espace comme emphase.** L'élément le plus important d'une page reçoit le double d'air. Sur la fiche
produit, c'est le bloc prix + bouton : il doit être isolé par du blanc, pas par une bordure.

### 2.3 Filets et séparateurs

**Un seul système de séparation par page**, choisi parmi trois : changement de fond, filet, ou blanc
seul. Les mélanger est la première source de bruit visuel.

Le système retenu pour Fatichanelya :

- **Entre sections** : changement de fond (`--paper` ↔ `--ivory` ↔ `--navy`). Pas de filet en plus.
- **Entre éléments d'une même liste** : **filet supérieur** `1px solid var(--line)`, sans contour, sans
  rayon, sans ombre.
- **Filet structurel** (sous un titre de section, séparation d'article légal) : `--line-mid`
  `#D8CDBA`, plus présent que `--line` sans devenir une bordure.
- **Jamais** de filet et de fond différent et d'ombre sur le même élément.

**La cellule à filet est le composant de grille par défaut du site.** Univers, engagements, valeurs,
étapes, listes de formations, réassurance : tout passe en cellule à filet. Ce qui garde une boîte est
listé en 2.4.

### 2.4 Ce qui a encore droit à une boîte

Restrictif et fermé. Une boîte est justifiée quand l'élément est **manipulable** ou **porte une photo
à contenir** :

1. la **carte produit avec photo** — la photo a besoin d'un cadre de fond pour être détourée ;
2. le **formulaire** (newsletter, recherche) — un champ de saisie doit se voir comme tel ;
3. le **bandeau d'appel à l'action** (bande navy de contact) ;
4. le **tiroir « Ma sélection »** et la **modale** — surfaces flottantes par nature ;
5. les **logos et vignettes TikTok** — une image a un bord, c'est celui de l'image.

Tout le reste — engagements, valeurs, FAQ, programmes, univers, réassurance — se compose **à plat**.

### 2.5 Traitement des images produits

Voir la section 5 pour la production. Côté règles d'affichage :

- **`object-fit: contain`, jamais `cover`, pour les visuels produits.** Un produit détouré qu'on
  recadre est un produit coupé. `cover` est réservé aux photos d'ambiance et aux portraits.
- **Une zone de respiration fixe** : le produit occupe **78 %** de la hauteur du cadre, centré. C'est ce
  qui donne l'impression « catalogue » plutôt que « collage ».
- **Un seul fond de vignette** pour toute la grille : `--surface` blanc. Pas de fond ivoire sur
  certaines cartes et blanc sur d'autres.
- **Proportion unique** : `1:1` en grille, `4:5` en fiche produit.
- **Aucune ombre portée dans le fichier image.** Les ombres qui existent dans les visuels source
  doivent être supprimées au détourage, pas compensées en CSS.

### 2.6 Usage de l'or — la règle qui fait ou défait le luxe

**L'or se mérite.** Réduction de huit emplois à **trois rôles**, et rien d'autre :

| Rôle | Token | Où |
|---|---|---|
| **Action principale** | `--gold` en fond | Le bouton WhatsApp, et lui seul. Un par écran |
| **Emphase éditoriale** | `--gold-ink` en texte | Les mots en `<em>` des titres, l'`.eyebrow`, le prix en fiche |
| **Marqueur d'état actif** | `--gold` en filet 2 px | Onglet de catégorie actif, question de FAQ ouverte |

**Supprimé** : l'or sur le chevron de la FAQ fermée (état par défaut = pas d'accent), l'or sur le filet
des valeurs (passe en `--line-mid`), l'or sur le soulignement de nav au survol (passe en `--navy`),
l'or sur le bord des citations (passe en navy 2 px).

**Règle de contraste absolue** : `--gold` (`#C39A4E`) **ne porte jamais de texte sur fond clair**
(2,46:1). Sur fond clair, le texte or est `--gold-ink` (`#8A6521`, 5,00:1). Sur navy, `--gold` passe
(6,00:1) et peut porter du texte.

### 2.7 Profondeur et ombres

**Le site passe à plat.** C'est le geste le plus efficace de toute la montée en gamme, et le moins
coûteux.

- `--shadow-lift` (`0 22px 55px`) **est supprimé de tous les survols**. Il ne subsiste que sur les
  surfaces réellement flottantes : tiroir, modale, toast, panneau mobile.
- `--shadow-card` est remplacé par **rien** sur les composants à plat, et par un filet là où une
  séparation est nécessaire.
- Les rayons diminuent : `--r-lg` (28 px) est **trop mou** pour un registre éditorial. Voir 4.3.
- **Aucun `backdrop-filter` sur l'en-tête.** Fond opaque, filet inférieur. Le verre disparaît.

Une marque premium ne flotte pas au-dessus de son propre papier. Elle est imprimée dessus.

### 2.8 États de survol

Un survol doit **changer un état**, pas simuler du relief.

| Composant | Aujourd'hui | Règle |
|---|---|---|
| Carte produit | `translateY(-4px)` + ombre + zoom image + pastille | **Le fond de la vignette passe à `--ivory`**, le nom passe en `--navy`. Rien ne bouge |
| Cellule à filet | translation latérale | **Le filet supérieur passe à `--navy`** (1 px → 2 px). Rien ne bouge |
| Bouton principal | `translateY(-2px)` + ombre | **Assombrissement du fond seul** |
| Lien texte / flèche | flèche qui glisse de 4 px | Conservé — c'est le seul mouvement qui dit quelque chose (la direction) |
| Nav | soulignement or qui se déploie | Conservé, mais **en `--navy`** |

Toute la logique tient en une phrase : **au survol, on change une couleur, pas une position.**

### 2.9 Mouvement

- **Un seul reveal** pour tout le site, celui qui existe déjà (`.reveal`, opacité + 22 px,
  `--t-enter` 600 ms). Il est bien conçu : visible sans JavaScript, respecte
  `prefers-reduced-motion`. **Le conserver tel quel.**
- **Réduire le décalage en cascade** : `calc(var(--i,0)*70ms)` sur douze cartes produit une vague de
  840 ms. Plafonner l'index à **4** (soit 280 ms maximum).
- **Jamais sur l'élément LCP** : le hero ne porte pas `.reveal`. À vérifier à l'implémentation.
- **Supprimer** : les zooms d'image au survol (`scale(1.05)`, trois occurrences), la pastille
  « Voir la fiche » en fondu, toutes les translations de survol.
- Micro-interactions : **150 ms**. Entrées : **280-600 ms**. Rien entre les deux sans raison.
- `transform` et `opacity` uniquement. Jamais de transition sur `all` — `.chip-button` porte
  aujourd'hui `transition:all var(--t-micro)`, à remplacer par la liste explicite des propriétés.

---

## 3. Les composants à normaliser

### 3.1 Bouton — un seul composant, quatre variantes

Aujourd'hui : `.button` + 5 modificateurs + `.product-add` + `.chip-button` + `.text-button` +
`.arrow-link`, chacun avec ses propres paddings, hauteurs et survols. La dérive vient de la copie
manuelle — c'est une règle de playbook établie sur Esprit Fluide.

**Un composant, quatre variantes, trois tailles, quatre états.**

| Variante | Fond | Texte | Bord | Usage |
|---|---|---|---|---|
| `primary` | `--gold` `#C39A4E` | `--navy` (6,00:1) | — | WhatsApp. **Un seul par écran** |
| `secondary` | `--navy` `#16233D` | `#FFFFFF` (15,65:1) | — | Action neutre |
| `outline` | transparent | `--navy` | 1,5 px `--navy` | Action tertiaire |
| `ghost` | transparent | `--navy` | — | Dans une liste |

Sur navy, `outline` et `ghost` prennent `#FFFFFF` et un bord `--line-on-navy` (`#8A94A3`, 5,10:1).

| État | Comportement |
|---|---|
| Repos | Fond plein, sans ombre |
| Survol | `primary` → `#B98D3F` (navy dessus : **5,18:1**) · `secondary` → `--navy-2` `#22345A` (blanc : **12,30:1**) · `outline` → fond `--navy`, texte blanc. **Aucune translation, aucune ombre** |
| Focus | `outline: 3px solid var(--gold-ink)`, `offset: 3px` — conservé de l'existant, contraste 5,30:1 sur blanc |
| Actif | `scale(.985)` — conservé, c'est un retour tactile légitime |

Tailles : `sm` 44 px · `md` 52 px · `lg` 58 px. Cible tactile minimale 44 px partout.

`.product-add` et `.chip-button` deviennent des instances (`outline sm` et `ghost sm`), pas des
composants séparés.

### 3.2 Cellule à filet — le composant de grille par défaut

```
.rule-cell
  border-block-start: 1px solid var(--line-mid)
  padding-block-start: 1.5rem
  padding-inline: 0            /* pas de padding horizontal : la cellule n'est pas une boîte */
  background: transparent
  border-radius: 0
  box-shadow: none
```

- **Variante numérotée** : le numéro en Iowan **2,125 rem**, graisse 500, `--navy` sur clair /
  `--gold` sur navy, **sans zéro devant**, posé au-dessus du titre avec 0,5 rem d'écart.
- **Variante à filet vertical** : `border-inline-start` + `padding-inline-start: 1.25rem`, pour les
  listes de valeurs (remplace le filet or actuel).
- **Sur navy** : filet `--line-on-navy` (`#8A94A3`, 5,10:1).
- **Survol** : filet → `--navy` et passage à 2 px. Rien d'autre.

Remplace : `.universe-card` (version claire), `.pledge-grid li`, `.training-list li`, `.values li`,
`.trust li`.

### 3.3 Carte produit

Garde une boîte — elle porte une photo — mais allégée.

```
Structure    vignette carrée + corps
Vignette     aspect-ratio 1 · background --surface · object-fit CONTAIN · produit à 78%
Bord         1px solid var(--line) · radius --r-md (12px après révision)
Ombre        AUCUNE, ni au repos ni au survol
Corps        catégorie (--fs-label, --gold-ink) · nom (Iowan 1,12rem, --ink)
             · prix (Iowan 1,5rem, --navy) · bouton outline sm
Survol       fond de vignette → --ivory · nom → --navy. Aucun mouvement, aucun zoom
Focus        outline 3px --gold-ink sur la carte entière
```

**Supprimé** : la pastille « Voir la fiche » en fondu (redondante avec le curseur et le bouton), le
zoom d'image, la translation, l'ombre.

### 3.4 Fiche produit — la refonte prioritaire

C'est la page où se décide l'achat. Structure complète attendue :

1. **Fil d'Ariane** (existant, conservé).
2. **Bloc d'achat** en deux colonnes : visuel `4:5` à gauche ; à droite catégorie, `h1`, prix en Iowan
   **2,25 rem** `--navy`, contenance et durée d'usage, bouton `primary lg`, bouton `outline`.
   *Isolé par du blanc, pas par une bordure.*
3. **Bande de réassurance** (3.6) juste sous le bloc d'achat — c'est là que le doute apparaît.
4. **Description** en `.prose`, mesure 62 ch.
5. **Conseils d'utilisation** en cellules à filet numérotées (grand corps, sans zéro).
6. **Composition / contenance** en liste de définition à filets, pas en tableau bordé.
7. **Mention santé** — conservée, en `--muted` sur `--ivory`, jamais en gris clair.
8. **Produits associés** : 3 à 4 de la même catégorie, en cartes produit.
9. **Rappel d'achat** : bande `--ivory`, prix + bouton `primary`, à la fin de la lecture.

Les points 5, 6 et 8 demandent des champs qui n'existent pas encore côté CMS (contenance, durée,
conseils). **C'est une demande à porter à `velix-cms` et à la cliente**, pas une invention : si la
donnée manque, le bloc ne s'affiche pas. Jamais de contenu inventé.

### 3.5 Accordéon de FAQ

```
Conteneur    aucun. La liste est une suite de lignes séparées par un filet supérieur
Question     min-height 60px · Iowan 1,12rem · --navy · pas de fond
Chevron      --muted au repos (pas d'or) · --gold-ink à l'ouverture · rotation 280ms
Réponse      --muted · 0,95rem · mesure 62ch · padding-block-end 1,35rem
Survol       la question passe en --navy foncé. Pas de fond ivoire, pas de boîte
Ouvert       filet supérieur en --gold 2px — l'or marque l'état actif, c'est son troisième rôle
```

Remplace le `border + radius + background` actuel de `.faq-list details`.

### 3.6 Bandeau de réassurance

Aujourd'hui `.trust` : quatre colonnes sur fond ivoire entre deux filets. La structure est bonne, le
traitement est faible (icônes or, textes en 0,76 rem).

```
Fond         --ivory · filets supérieur et inférieur en --line-mid
Rythme       --section-y-sm
Colonnes     4 (desktop) → 2 (900px) → 1 (520px)
Icône        --navy, 20px, trait 1,5px — PAS or (l'or ne décore pas)
Titre        0,95rem · 600 · --navy
Texte        0,875rem · --muted (6,52:1 sur paper, 5,93:1 sur ivory)
Séparation   filet vertical --line-mid entre colonnes en desktop, supprimé sous 900px
```

**Contenu à n'écrire que s'il est vrai** : expédition depuis l'Espagne, réponse de Fati sous X heures,
paiement à la commande. Aucune promesse non confirmée par la cliente.

### 3.7 Citation / témoignage — à préparer, pas à remplir

Le brief est formel : **aucun témoignage n'existe**, et aucun ne sera affiché tant qu'il n'existe pas.
Pas de balisage `Review` en JSON-LD. Le composant se définit maintenant pour être prêt le jour où la
cliente fournira des avis réels avec accord.

```
Structure    citation en Iowan 1,5rem, --ink, mesure 44ch
Guillemets   optiquement alignés hors de la colonne de texte (pas d'indentation mathématique)
Filet        border-inline-start 2px --navy (PAS or) · padding-inline-start 1,5rem
Attribution  prénom en 0,875rem 600 --navy · contexte en 0,875rem --muted
Fond         aucun. Pas de carte, pas d'ombre, pas de guillemet décoratif géant
```

**Tant qu'il n'y a pas de témoignage, le composant n'est pas instancié.** Pas de placeholder visible en
production.

### 3.8 En-tête

```
Position     sticky, conservé
Fond         --paper OPAQUE (#FBF8F3, sans alpha). backdrop-filter SUPPRIMÉ
Séparation   filet inférieur 1px --line-mid, présent en permanence
Collé        hauteur 84px → 72px (et non 66px : moins brutal), sans ombre
Ancres       scroll-margin recalculé sur la hauteur collée réelle + barre d'annonce
```

Corrige le point 1.8 : la grille ne transparaît plus sous l'en-tête.

### 3.9 Pages légales

```
Largeur      68ch, colonne unique
h1           --fs-h2 · date de mise à jour juste dessous en 0,875rem --muted
Sommaire     liens d'ancrage en cellules à filet, si plus de 4 sections
h2           1,35rem Iowan --navy · filet supérieur --line-mid · margin-block-start 3rem
h3           1,12rem · --ink · pas de filet
Texte        1rem · line-height 1,75 · --ink (et non --muted : c'est du texte lu, pas secondaire)
Listes       puces --gold-ink, retrait 1,3rem
Liens        --gold-ink souligné, offset 3px (existant, conservé)
```

Le changement le plus important : **le corps des pages légales passe en `--ink`, pas en `--muted`**.
`.prose` met aujourd'hui tout le texte en `--muted` — acceptable pour un chapô, pas pour trois pages
de conditions générales qu'on demande à l'acheteuse de lire.

---

## 4. Tokens à ajouter ou corriger dans `main.css`

Section « 1. TOKENS ». **Aucun token existant n'est supprimé** — le thème tourne, et une variable
retirée casse silencieusement une règle. On ajoute, et on corrige trois valeurs.

### 4.1 Couleurs à ajouter

| Token | Valeur | Contraste vérifié | Raison |
|---|---|---|---|
| `--line-mid` | `#D8CDBA` | 1,48:1 sur paper — **décoratif uniquement** | Filet structurel entre `--line` (trop discret) et `--line-strong` (trop dur). Ne porte jamais d'information |
| `--navy-deep` | `#0F1A2E` | blanc dessus **17,39:1** | État actif des surfaces navy, pied de page profond |
| `--gold-press` | `#B98D3F` | navy dessus **5,18:1** | Survol du bouton or. Le `#D3AC64` actuel *éclaircit* au survol, ce qui allège le bouton au lieu de l'affirmer |
| `--sage-ink` | `#4F6857` | **5,75:1** sur paper, **5,23:1** sur ivory | `--sage` (`#7B9482`) échoue à **3,10:1** : inutilisable en texte. Version conforme pour les puces et mentions |
| `--text-on-navy` | `#EAE3D8` | **12,28:1** | Nomme la couleur de texte sur navy, aujourd'hui recopiée en dur dans 6 règles |
| `--text-on-navy-2` | `#C9D0DB` | **10,08:1** | Texte secondaire sur navy |
| `--line-decor-navy` | `#9BA6B6` | **6,35:1** | Filet de cellule sur navy |

**Important sur `--line-mid`** : un filet purement décoratif n'est pas soumis au 3:1 de WCAG 1.4.11,
qui ne vise que les éléments porteurs d'information ou les limites de contrôles. Quand un filet
délimite un **contrôle** (champ, bouton), c'est `--line-strong` (`#8F8065`, **3,64:1** sur paper) qui
s'applique — il existe déjà et il est conforme.

### 4.2 Typographie à ajouter et corriger

| Token | Valeur | Raison |
|---|---|---|
| `--fs-body` | `clamp(1rem, .96rem + .2vw, 1.09rem)` | **Correction.** Aujourd'hui `.95rem` en bas de plage, soit 15,2 px — sous le plancher de la checklist |
| `--fs-num` | `2.125rem` | **Ajout.** Les numéros en grand corps (≈ 34 px) exigés par le playbook |
| `--fs-price` | `1.5rem` | **Ajout.** Le prix en grille, palier manquant |
| `--fs-price-lg` | `clamp(1.9rem, 1.6rem + 1vw, 2.25rem)` | **Ajout.** Le prix en fiche produit |
| `--fs-lead` | `clamp(1.06rem, 1rem + .35vw, 1.22rem)` | **Ajout.** Chapô, aujourd'hui écrit en dur dans `.hero .lead` |
| `--fs-label` | `.875rem` | **Ajout.** Plancher des étiquettes lues. Remplace les `.62rem` / `.66rem` / `.72rem` |
| `--fs-eyebrow` | `.75rem` | Conservé — 12 px en capitales espacées, en `--gold-ink` à 5,00:1 |
| `--lh-tight` / `--lh-body` / `--lh-prose` | `1.08` / `1.65` / `1.75` | **Ajout.** Interlignes nommés, aujourd'hui dispersés |
| `--measure` / `--measure-prose` | `56ch` / `68ch` | **Ajout.** Mesures de lecture nommées |

### 4.3 Espace, formes, ombres

| Token | Valeur | Raison |
|---|---|---|
| `--section-y-lg` | `clamp(5rem, 10vw, 9rem)` | **Ajout.** Rythme narratif (§2.2) |
| `--section-y-sm` | `clamp(2.25rem, 4.5vw, 3.75rem)` | **Ajout.** Rythme des bandes d'information |
| `--r-sm` | `6px` | **Correction** de 10 px. Registre éditorial, pas applicatif |
| `--r-md` | `12px` | **Correction** de 18 px |
| `--r-lg` | `18px` | **Correction** de 28 px. 28 px sur une carte de 340 px lit « application mobile » |
| `--r-pill` | `999px` | Conservé — les boutons en gélule sont un acquis v3 |
| `--shadow-card` | `none` | **Correction.** Les composants à plat n'ont plus d'ombre |
| `--shadow-float` | `0 4px 10px #23201c0f, 0 22px 55px #23201c1f` | **Ajout.** Ancienne valeur de `--shadow-lift`, renommée pour dire son usage : tiroir, modale, toast, panneau mobile |
| `--shadow-lift` | `none` | **Correction.** Le token reste défini pour ne rien casser, mais ne produit plus d'ombre au survol |

Renommer `--shadow-lift` plutôt que le supprimer est délibéré : il est référencé dans neuf règles ;
le neutraliser applique la décision partout d'un seul geste, et `velix-front` remet `--shadow-float`
seulement sur les quatre surfaces réellement flottantes.

### 4.4 Récapitulatif des contrastes

Toutes les valeurs ci-dessous sont **calculées**, pas estimées.

| Paire | Ratio | Seuil | Verdict |
|---|---|---|---|
| `--ink` / `--paper` | 15,31:1 | 4,5 | OK |
| `--ink` / `--ivory` | 13,93:1 | 4,5 | OK |
| `--muted` / `--paper` | 6,52:1 | 4,5 | OK |
| `--muted` / `--ivory` | 5,93:1 | 4,5 | OK |
| `--navy` / `--paper` | 14,77:1 | 4,5 | OK |
| `--gold-ink` / `--paper` | 5,00:1 | 4,5 | OK |
| `--gold-ink` / `--ivory` | 4,55:1 | 4,5 | OK (marge faible — ne pas assombrir l'ivoire) |
| `--gold-ink` / `--surface` | 5,30:1 | 4,5 | OK |
| **`--gold` / `--paper`** | **2,46:1** | 4,5 | **ÉCHEC — texte interdit** |
| `--navy` / `--gold` (bouton) | 6,00:1 | 4,5 | OK |
| `--navy` / `--gold-press` (survol) | 5,18:1 | 4,5 | OK |
| `#FFFFFF` / `--navy` | 15,65:1 | 4,5 | OK |
| `--text-on-navy` / `--navy` | 12,28:1 | 4,5 | OK |
| `--text-on-navy-2` / `--navy` | 10,08:1 | 4,5 | OK |
| `--gold` / `--navy` | 6,00:1 | 4,5 | OK |
| `--gold-soft` / `--navy` | 12,02:1 | 4,5 | OK |
| `--line-strong` / `--paper` (contrôle) | 3,64:1 | 3,0 | OK |
| `--line-on-navy` / `--navy` (contrôle) | 5,10:1 | 3,0 | OK |
| `--line-decor-navy` / `--navy` | 6,35:1 | 3,0 | OK |
| **`--sage` / `--paper`** | **3,10:1** | 4,5 | **ÉCHEC — d'où `--sage-ink`** |
| `--sage-ink` / `--paper` | 5,75:1 | 4,5 | OK |
| `--sage-ink` / `--ivory` | 5,23:1 | 4,5 | OK |

**Deux pièges vérifiés dans les sections sombres**, conformément à la consigne de recontrôle :

- `--gold-soft` (`#EFE0C4`) passe sur navy à 12,02:1 mais **échoue sur fond clair** — il ne doit jamais
  sortir des bandes navy.
- `--gold-ink` (`#8A6521`) sur `--gold-soft` (`#EFE0C4`) mesure **4,07:1** : **échec**. Si un badge or
  clair porte du texte, ce texte est `--ink` (12,46:1) ou `--navy` (12,02:1), jamais `--gold-ink`.

**Deux valeurs héritées à corriger à l'implémentation**, relevées dans le CSS actuel :
`.newsletter input::placeholder` en `#A79F93` sur navy mesure 5,98:1 (conforme), mais les
`.footer-bottom` et `.disclaimer` en `#B6AFA3` à 7,19:1 sont conformes également — **aucune correction
de contraste n'est nécessaire dans le pied de page**. Le seul échec réel du site actuel est
`--sage` employé en texte.

---

## 5. Direction photo

### 5.1 Les photos de Fati

Une vraie photo va remplacer le portrait généré du hero. C'est le changement le plus important du
projet : **tout l'argument de la marque est « une vraie personne »**, et le brief signale déjà le
risque d'un portrait généré démasqué.

**Cadrage.** Trois plans à demander, pas un seul :

| Emplacement | Cadrage | Proportion | Orientation du regard |
|---|---|---|---|
| Hero | Plan poitrine, buste légèrement de trois quarts | `4:5` | Vers l'objectif — c'est une prise de contact |
| À propos | Plan taille ou américain, en situation | `3:4` | Hors champ, vers la lumière |
| Formations | En activité (ordinateur, carnet, atelier) | `16:9` ou `3:2` | Sur son travail, pas sur l'objectif |

**Fond.** Un mur uni clair, ivoire ou beige, **dans la famille `#FBF8F3` / `#F3EDE3`** — le fond de la
photo doit prolonger le fond du site, pas s'y opposer. Sinon : un intérieur réel très défocalisé
(f/2.0 à f/2.8), sans élément identifiable. **Jamais de fond blanc pur** (le site n'a pas de blanc en
fond de page) ni de fond navy (il entrerait en concurrence avec les bandes fortes).

**Lumière.** Une seule source, naturelle, latérale à 45°, **fenêtre à gauche ou à droite du sujet**,
réflecteur blanc de l'autre côté. Ombres douces, présentes, non supprimées. **Pas de flash direct, pas
de ring light** — le cercle dans l'œil est la signature « contenu TikTok », pas « marque premium ».
Lumière du milieu de journée, légèrement chaude (4800-5200 K) pour rester dans le registre ivoire.

**Étalonnage.** Un seul traitement pour toutes les photos du site : hautes lumières retenues, noirs
**levés** (jamais bouchés, l'ombre la plus dense reste au-dessus de `#23201C`), saturation globale
réduite d'environ 10 %, **légère dominante chaude dans les basses lumières** pour accrocher l'ivoire.
Le contraste reste moyen — un contraste dur lit « dynamique », pas « posé ».

**Grain.** Très léger, monochrome, équivalent ISO 400 argentique. Il unifie des photos prises à des
moments différents et empêche l'aplat numérique qui fait « image de banque ». Il doit être invisible à
l'œil et perceptible seulement à la comparaison.

**Retouche.** Peau conservée — texture, grain de peau, cernes atténués et non effacés. Une peau
plastifiée détruit exactement ce que la photo doit prouver.

**Interdits formels** : photo de stock, bras croisés devant un mur blanc, sourire commercial, salade,
mains en cœur, citation incrustée sur l'image, filtre beauté, arrière-plan flouté artificiellement par
l'IA du téléphone (le halo autour du hijab se voit immédiatement et trahit le procédé).

**En attendant les vraies photos** : le portrait généré reste en place, mais il est **marqué comme
provisoire dans le média-manifest** et ne doit pas être enrichi (pas de recadrage, pas de déclinaison,
pas de version Open Graph). Toute production supplémentaire autour de lui augmente le coût du
remplacement.

### 5.2 Les visuels produits — homogénéisation

C'est le chantier média prioritaire, et il est **bloquant pour l'impression de qualité de la boutique**.

**Le problème du filigrane, d'abord.** Les visuels `morinzhi`, `rg-90` et `divine-night-oil` portent
une mention « DXN's Property » incrustée en diagonale. C'est à traiter avant l'esthétique :

- **Ne pas retirer le filigrane par retouche.** Effacer une mention de propriété sur un visuel qui ne
  nous appartient pas aggrave le problème au lieu de le résoudre.
- **Demander à la cliente les visuels officiels sans filigrane**, auxquels elle a normalement accès
  par son back-office de distributrice DXN. C'est la voie propre, et elle est probablement gratuite.
- **À défaut, photographier les produits réels** : Fati les a en stock, et c'est ce qui donnerait le
  plus de valeur au site. Voir le protocole ci-dessous.
- Cette demande est ajoutée à la liste « À fournir par le client ».

**Note sur `tools/medias/produits-normalises/`.** Un dossier de visuels retraités (1000×1000) est
apparu dans le dépôt pendant cette intervention. Il **règle une partie du problème et pas la plus
grave** : le fond dégradé de `kallow-cosmetics` est bien neutralisé en blanc et le format est unifié,
mais **le filigrane « DXN's Property » est toujours là** (vérifié sur `morinzhi`), le produit n'est
pas centré optiquement (`kallow-cosmetics` reste décalé vers la droite avec une marge gauche vide) et
l'échelle n'est pas homogène d'un fichier à l'autre. **Ce dossier ne doit pas être considéré comme le
chantier visuels terminé** : il ne remplace pas la demande de sources propres à la cliente, qui reste
le seul moyen correct de traiter le filigrane.

**Le système cible**, une fois les sources propres obtenues :

```
Format          WebP, 1200×1200 (2× de l'affichage 600px), qualité 82
Proportion      1:1 strict, sans exception
Fond            #FFFFFF pur et uniforme — un seul fond pour les 19 références
Détourage       produit détouré, fond remplacé, AUCUNE ombre portée dans le fichier
Échelle         le produit occupe 78% de la hauteur du cadre, sans exception
Centrage        centré optiquement (et non mathématiquement) : un flacon à bouchon
                lourd se centre sur sa masse, pas sur sa boîte englobante
Angle           face, à hauteur d'étiquette. Le même angle pour les 19
Lumière         diffuse, deux sources latérales, reflet spéculaire discret sur les
                surfaces brillantes, aucun point chaud brûlé
Texte incrusté  aucun. Le natural-shield-deo actuel (légendes en arabe) est à refaire
Composition     UN produit par visuel. Le kallow-cosmetics actuel (six flacons) devient
                soit un visuel de coffret assumé, soit six visuels distincts
```

**Protocole si Fati photographie elle-même** — réaliste, elle a les produits :

1. Une feuille de papier blanc mat A2 courbée en fond infini, posée sur une table près d'une fenêtre.
2. Lumière du jour indirecte, **pas de soleil direct**, réflecteur (une seconde feuille blanche) du
   côté opposé.
3. Téléphone sur trépied, **même distance et même hauteur pour les 19 produits** — c'est ce qui crée
   l'homogénéité, plus que la qualité de l'appareil.
4. Format carré, mise au point sur l'étiquette, HDR désactivé.
5. Toutes les photos dans la même session, à la même heure : la lumière change plus vite qu'on ne
   le croit.
6. Post-traitement identique en lot : balance des blancs sur le fond, fond poussé au blanc pur,
   détourage, mise à l'échelle à 78 %.

**Le 19ᵉ visuel** (Lingzhi Black Coffee) est manquant. En attendant, le placeholder ne doit **pas**
afficher « visuel à venir » : une vignette `--ivory` unie avec le nom du produit composé en Iowan
centré est plus digne et ne signale pas un site inachevé.

### 5.3 Les captures TikTok

Elles sont **réelles**, c'est leur valeur — et le brief confirme qu'elles sont la seule preuve sociale
existante. Elles ne doivent **pas** être étalonnées comme les photos studio : leur authenticité est
l'argument.

```
Traitement    aucun. Pas d'étalonnage, pas de grain ajouté, pas de recadrage
Proportion    9:16 assumée
Présentation  dans leur section dédiée uniquement, jamais mélangées aux photos studio
Cadre         vignette avec radius --r-md, sans ombre
Signal        une icône TikTok discrète en surimpression indique la nature de l'image
```

**Elles ne servent jamais de portrait de Fati** ailleurs sur le site. Une capture d'écran 405×720 en
hero serait immédiatement lisible comme un pis-aller.

### 5.4 Ce qui tient l'ensemble

Trois registres d'image coexistent : **portrait éditorial** (chaud, doux, étalonné), **produit
studio** (blanc, net, neutre), **capture TikTok** (brute, verticale, non traitée). Ils ne se mélangent
jamais dans une même grille. Ce qui les relie n'est pas un filtre commun, c'est **le fond du site** :
chaque image est posée sur ivoire, avec la même respiration et le même rayon. C'est le papier qui fait
la cohérence, pas la retouche.

---

## 6. Ce qu'il ne faut surtout pas faire sur ce projet

À relire avant chaque vague d'implémentation.

**Identité — acquis à ne pas défaire**

1. **Ne pas fusionner `--gold` et `--gold-ink`.** Ils ne font pas doublon. `#C39A4E` sur ivoire mesure
   2,46:1 et échoue ; `#8A6521` mesure 5,00:1 et passe. L'unification casse l'accessibilité de tout le
   site clair.
2. **Ne pas introduire de police web.** Le site charge 0 octet de fonte. Pas de Google Fonts, pas de
   `@font-face`, pas de préchargement. C'est un acquis de performance, pas un manque.
3. **Ne pas changer la palette.** Elle est validée par la cliente.
4. **Ne pas utiliser `--sage` (`#7B9482`) en texte** — 3,10:1, échec. C'est `--sage-ink` (`#4F6857`).
5. **Ne pas mettre `--gold-ink` sur `--gold-soft`** — 4,07:1, échec. Texte `--ink` ou `--navy`.

**Structure — les réflexes à ne pas réintroduire**

6. **Pas de cartes grises bordées.** Contrainte du gérant, corrigée deux fois sur un autre projet. Les
   grilles de contenu sont des cellules à filet : filet supérieur ou vertical, sans contour, sans
   arrondi, sans ombre au survol.
7. **Pas de petits « 01 » en capitales colorées.** Les numéros sont en grand corps (≈ 34 px), graisse
   normale, **sans zéro devant**.
8. **Ne pas remettre une boîte autour de la FAQ, des engagements, des valeurs ou des univers.** La
   liste des composants qui gardent une boîte est fermée (§2.4).
9. **Ne pas réintroduire `translateY(-4px)` + ombre au survol.** Le survol change une couleur, pas une
   position.
10. **Ne pas remettre de `backdrop-filter` sur l'en-tête.** Le verre est ce qui fait actuellement passer
    la grille produits en transparence sous la barre.

**Contenu — les règles de l'agence**

11. **Ne jamais inventer un témoignage, un chiffre, un avis ou une certification.** Aucun témoignage
    n'existe aujourd'hui. Le composant de citation est défini mais **non instancié**. Pas de balisage
    `Review` en JSON-LD.
12. **Ne pas afficher de nombre d'abonnés TikTok** tant qu'il n'est pas fourni avec une capture datée.
13. **Ne pas écrire d'allégation santé**, même autorisée par l'EFSA. Règle plus stricte que la loi,
    et c'est voulu (brief §12.10).
14. **Ne pas retoucher le filigrane « DXN's Property »** pour l'effacer. On demande les visuels
    officiels ou on photographie les produits réels.
15. **Ne pas enrichir le portrait généré** (recadrages, déclinaisons, Open Graph) tant que la vraie
    photo n'est pas arrivée.

**Conversion — les codes du secteur à refuser**

16. **Pas de prix barré, pas de « -30 % », pas de compte à rebours, pas d'étoiles d'avis, pas
    d'avant/après.** Le brief documente précisément pourquoi : l'acheteuse est méfiante du secteur MLM
    et des tunnels d'infopreneuriat, et la sobriété est ici une preuve.
17. **Pas de bandeau promotionnel en surimpression**, pas de fenêtre modale d'entrée, pas de « Fati
    vient de vendre… ».
18. **Pas plus d'un bouton or par écran.** Si deux actions or se voient en même temps, la seconde passe
    en `outline`.

**Mise en œuvre**

19. **Ne pas coder en dur une couleur dans un composant.** Les composants ne lisent que la couche
    sémantique. Un `#16233D` écrit en dur condamne tout rethème futur — et le CSS actuel en contient
    déjà plusieurs (`#16233DE0`, `#16233D8C`, `#FFFFFF0F`), à remplacer par des tokens.
20. **Ne pas transitionner `all`.** `.chip-button` le fait aujourd'hui. Lister les propriétés.
21. **Ne pas animer l'élément LCP.** Le hero ne porte pas `.reveal`.
22. **Ne pas tester seulement 375 et 1440.** C'est à **768 px** que les grilles cassent — règle de
    playbook établie sur Esprit Fluide. La grille produits en `auto-fill minmax(220px, 1fr)` est à
    vérifier précisément à cette largeur.

---

## 7. Ordre d'application recommandé

Pour `velix-front`, par rapport impact / effort :

| Priorité | Chantier | Effet attendu |
|---|---|---|
| 1 | Tokens (§4) — ajouts, corrections de rayons, neutralisation de `--shadow-lift` | Le site s'aplatit d'un seul geste. Effet immédiat sur toutes les pages |
| 2 | En-tête opaque + `scroll-margin` (§3.8) | Corrige un défaut visible signalé par le gérant |
| 3 | Fiche produit (§3.4) | La page qui convertit. Le plus fort impact commercial |
| 4 | Cellule à filet (§3.2) appliquée aux 5 composants concernés | Supprime la nappe de rectangles |
| 5 | Bouton unifié (§3.1) et carte produit (§3.3) | Cohérence et discipline de l'or |
| 6 | Échelle typographique (§2.1) | Fait basculer la perception de gamme |
| 7 | Pages légales (§3.9) | Faible effort, gain de crédibilité net |
| 8 | Visuels produits (§5.2) | Bloqué par la cliente — à lancer en parallèle dès maintenant |

Les chantiers 1, 2, 4, 5, 6 et 7 sont purement CSS et ne touchent aucun contenu. Le 3 demande des
champs CMS supplémentaires (contenance, durée d'usage, conseils) : **à cadrer avec `velix-cms`**, et
à afficher uniquement quand la donnée existe.

---

## 8. À fournir par la cliente — ajouts de cette intervention

1. **Les visuels produits officiels sans filigrane « DXN's Property »**, depuis son back-office de
   distributrice — ou l'accord pour photographier les produits réels selon le protocole §5.2.
2. **La contenance et la durée d'usage de chaque référence** (ex. « 90 gélules ≈ 45 jours »). Le brief
   la désigne déjà comme la preuve qui rend un prix acceptable ; la fiche produit ne peut pas être
   complétée sans elle.
3. **Les conseils d'utilisation** par produit, en 2 à 4 étapes courtes (issus de l'emballage, sans
   ajout ni interprétation).
4. **Les vraies photos de Fati** : trois cadrages (§5.1), un seul fond, une seule lumière.
5. **Le visuel du Lingzhi Black Coffee** (19ᵉ référence).
6. **Le contenu réel du bandeau de réassurance** : lieu d'expédition, délai de réponse, modalité de
   paiement. Rien ne s'affiche tant que ce n'est pas confirmé.

---

## 9. Passage à la suite

**`velix-media`** — produire et homogénéiser les visuels de la direction retenue : traitement des 18
visuels produits selon §5.2 (après obtention des sources propres), vignette de remplacement du 19ᵉ,
cadrages et étalonnage des photos de Fati selon §5.1, monogramme et favicon dans la palette (le
favicon actuel est hors palette, signalé par le brief), image Open Graph à refaire avec la photo réelle.
Mettre à jour `media-manifest.md` en marquant explicitement ce qui est provisoire.

**`velix-front`** — appliquer dans l'ordre de la section 7. Aucun fichier de thème n'a été modifié par
cette intervention.
