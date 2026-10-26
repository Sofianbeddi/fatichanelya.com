# Manifeste des médias — Fatichanelya

Ce que chaque visuel est, d'où il vient, comment il a été produit et ce qu'on a le droit d'en
faire. Tenu à jour à chaque génération ou remplacement. Les originaux fournis par le gérant ou
remplacés sont conservés dans `tools/medias/sources/`, jamais effacés.

Établi le 2 octobre 2026.

## Outil et contraintes

- **Higgsfield via le connecteur claude.ai** (outils MCP). Le CLI `higgsfield` est installé mais sa
  session est expirée : `higgsfield auth login` est interactif, à relancer par Sofian si l'on veut
  utiliser `product-photoshoot` (prompts assemblés côté serveur).
- **Plan gratuit, 50 crédits au départ.** `gpt_image_2_5` et `seedream_v5_pro` exigent un plan
  payant (« Requires basic plan or higher »). Le modèle utilisable est **`nano_banana_2`**
  (Google) : **1,5 crédit par image en 1k**, 2 crédits en 2k. Il accepte une image de référence
  (`image_references`) et reproduit un emballage avec une fidélité très élevée — vérifiée sur
  26 rendus, aucun texte d'étiquette altéré.
- Envoi des références : `media_upload` (URL présignées) → `curl -X PUT` → `media_confirm`.
  Attention : la réponse de `media_upload` ne contient pas le nom du fichier, l'ordre des
  `uploads[]` est celui des `files[]` envoyés.
- Solde au 2 oct. 2026 en fin de journée : **2 crédits** sur 50 (packshots, Omega 3, trois visuels de formation, Reishilium, Spirulina 120 en deux essais).

## Visuels produits — 28 packshots, fond blanc

| Fichier (`tools/medias/produits/`) | Source de la référence | Statut |
|---|---|---|
| cordyceps-coffee, lingzhi-coffee, lingzhi-coffee-3-en-1, lions-mane-coffee, kallow-shower-gel, kallow-sunscreen-spf50, natural-shield-deo, virgin-coconut-oil, gano-massage-oil | emballage découpé dans les affiches fournies par le gérant le 2 oct. 2026 (`tools/medias/sources/affiches-gerant/` à archiver — fichiers 1–17) | **généré** nano_banana_2, 1:1, 1k |
| morinzhi, mycoveggie, poria-s, reishi-powder, rg-90, divine-night-oil, kallow-cosmetics, lions-mane, roselle, spirulina | ancien visuel DXN filigrané « DXN's Property » (`sources/produits-dxn-filigranes/`) | **généré** nano_banana_2 à partir de l'ancien visuel ; filigrane absent du rendu |
| omega-3 | visuel officiel DXN HF280 (`sources/omega-3-officiel.png`, PNG détouré relevé chez un revendeur agréé espagnol) ; la photo du kakémono fournie par le gérant est dans `sources/affiches-gerant/18.png` | **généré** nano_banana_2 à partir du visuel officiel : flacon blanc sans ombrage, illisible sur fond blanc une fois aplati |
| reishi-powder (Reishilium Powder) | pot découpé dans l'affiche du gérant (`sources/affiches-gerant/20.png`, elle-même générée : la tranche y porte une coquille, corrigée dans le prompt) ; l'ancien rendu à étiquette européenne est dans `sources/reishi-powder-etiquette-eu.webp` | **généré** nano_banana_2 ; l'étiquette affiche « 22 g », contenance à confirmer |
| spirulina-120 | petit flacon découpé dans une photo de revendeur agréé (`sources/spirulina-120-revendeur.jpg`) | **généré** nano_banana_2 ; le modèle a gardé le fond gris clair de la photo — ramené au blanc en posant le point blanc sur le fond (niveaux), le seuil du normaliseur découpait l'ombre en escalier |
| cordyceps, divine-dry-oil, divine-eye-cream, divine-face-cream, ganozhi-body-foam, ganozhi-shampoo, ganozhi-soap | visuels DXN d'origine, sans filigrane visible | **fournis**, inchangés |

Prompt commun (packshot) : « Professional e-commerce packshot of the exact product shown in the
reference image. Reproduce the packaging, label artwork, colors, typography and proportions with
complete fidelity […]. Remove the watermark overlay text […]. Upright, front-facing, centered,
about 60 % of the frame height, pure white seamless studio background, soft natural contact
shadow, diffused light from the upper left. No props, no added text. » Pour les emballages
découpés dans une affiche : « Isolate the single product from the reference image […] no
props, nothing else from the reference. »

Post-traitement : `tools/normaliser-visuels.py` → `produits-normalises/` (1000 × 1000, détourage
sur fond blanc, occupation d'encre cible 26 %, 11,5 % à 26 % obtenus — les flacons très élancés
sont bornés en hauteur). Le gris de fond du rendu `kallow-cosmetics` a été ramené au blanc par le
normaliseur.

**Point de droit à garder en tête.** Les rendus sont des images de synthèse dérivées des
emballages DXN. Montrer le produit que l'on revend est un usage nominatif normal, mais la question
posée à DXN dans `reglementaire-espagne.md` (« usage de la marque et des visuels ») reste ouverte.
La meilleure solution reste des photos du stock réel de Fati.

## Visuels de sections — 8 natures mortes, palette du site

| Fichier | Emploi | Statut |
|---|---|---|
| `formations/formation-ecommerce.webp` | **sans emploi** depuis le 2 oct. 2026 (« Lancer son e-commerce » supprimée) | généré, 4:3, 1200 × 896 |
| `formations/formation-vendre.webp` | carte « Vendre avec confiance » | généré |
| `formations/formation-ia.webp` | carte « L'IA au quotidien » | généré |
| `formations/formation-strategie.webp` | carte « Marketing digital » (auparavant « Stratégie digitale ») | généré |
| `formations/formation-contenu.webp` | carte « Création de contenu et réseaux sociaux » | généré le 2 oct. 2026, `formation-ia.webp` en référence de style |
| `formations/formation-media-buying.webp` | carte « Media buying et publicité en ligne » | idem |
| `formations/formation-automatisation.webp` | carte « Automatisation et assistants IA » | idem |
| `pages/training-session.webp` | section Formations de l'accueil (option `training_image`) | généré — **remplace une image générée d'une femme qui n'était pas Fati**, retirée pour la raison écrite dans le brief : jamais de visage inventé sur un site qui vend une personne réelle |

Direction commune : vue de dessus, nappe de lin beige (`#F7F1E8`), objets sable / ivoire / bleu
nuit, une touche d'or champagne, lumière de fenêtre diffuse en haut à gauche, 50 mm, aucun
texte, aucune personne, aucun écran allumé. Anciennes versions dans `sources/sections-v1/`.

## Image de partage

`pages/og-default.jpg` — 1200 × 630, composée **sans crédit** depuis `sources/og/og.html`
(Poppins, trois packshots normalisés) et rendue par Chrome headless (`sources/og/shot.cjs`).
Option `og_image` (Contenus du site › Général) ; repli sur la photo du hero.

## Photos réelles — ne jamais remplacer par une génération

`pages/hero-fati.webp`, `pages/about-avatar.webp`, `pages/tiktok-*.webp`,
`categories/cat-*.webp` : fournies par le gérant (sept. 2026 ; catégories refaites avec les vrais
produits le 26 oct. 2026, voir plus bas). Ce sont les seules preuves visuelles du site.

`pages/about-avatar.webp` — **photo de profil de la section « À propos »** (le rond de 96 px posé
sur le portrait, taille `fati-avatar` 240 × 240), remplacée le 5 oct. 2026 à la demande du gérant par
la photo de diplôme de Fati (320 × 320 fournie telle quelle, source
`sources/about-avatar-diplome-source.png`, WebP q86, 14 Ko). Aucun agrandissement : 320 px
suffisent pour un rond de 96 px, même en écran dense. L'ancienne, une image de profil TikTok avec
« fatichanelya » incrusté, est dans `reserve/about-avatar-v1-texte.webp`. Le portrait principal
`about-main.webp` n'a pas changé.

`pages/about-main.webp` — **portrait de la section « À propos »**, recadré le 2 oct. 2026 depuis
`sources/hero-fati-source.png` (zone 1015, 45 → 1405, 738, agrandie 1,59× en Lanczos, léger
renfort de netteté). **Aucun traitement génératif sur le visage** : pas d'agrandissement par IA,
qui retoucherait les traits d'une personne réelle. Il remplace une capture TikTok (« San
Sebastian », texte incrusté) que le gérant a jugée hors contexte et pas professionnelle ;
l'ancienne est dans `sources/about-main-san-sebastian.webp`. Le hero et ce portrait viennent de la
même photo : c'est un intérim, la cible reste une vraie séance (blueprint E.5).

## Références sans visuel (16 octobre 2026 — 76 ; 64 depuis le 26 octobre, voir plus bas)

Les 75 produits ajoutés d'après le catalogue DXN de février 2026 n'ont pas de visuel (`image=""`
dans `rediger-catalogue.py`, méta `_fati_indispo`) ; le thème affiche `placeholder-produit.svg` et
la mention « Visuel à venir ». L'huile de coco (`virgin-coconut-oil`) est dans le même cas depuis
que sa fiche décrit l'huile bio 500 ml du catalogue et non le pot au Ganoderma de l'affiche ; son
ancien rendu reste dans `tools/medias/produits/virgin-coconut-oil.webp`, inutilisé. Les images
embarquées dans le PDF (`sources/catalogue-dxn-2026-02.pdf`) font 110 × 135 px : inutilisables
en packshot, utilisables au mieux comme référence pour `nano_banana_2` (1,5 crédit l'image, solde
2 crédits au 16 oct. 2026). Priorité : photos du stock réel de Fati, ou visuels officiels de
l'espace distributrice.

## Banque DXN collectée (16 octobre 2026)

`tools/collecter-visuels-dxn.py` télécharge, pour chaque produit sans visuel, la galerie de sa
fiche sur la banque produits DXN Malaisie (dxn2u.com) : `tools/medias/sources/dxn2u/<slug>/01.jpg`
(face), `02.jpg` (dos), `03.jpg` (sachet ou variante), 735 × 960 sur fond blanc, index de
provenance dans `index.csv`, planche de choix dans `planche.html`. Dossier hors dépôt (56 Mo,
retéléchargeable avec `--hors-ligne` pour ne pas rejouer les pages). 52 produits couverts, 24 sans
image : les 21 Kallow (fabriqués en Grèce, absents de la banque malaisienne), Maca Vita, Maca
Cafe, Brook Coffee.

**Non publiables en l'état** : filigrane « DXN's Property » répété sur toute l'image, emballage
malaisien. Usage prévu : référence pour `nano_banana_2` (packshot fidèle sans filigrane, comme en
octobre) ou pour contrôler les visuels officiels que Fati fournira. La correspondance d'une fiche
« famille » (RG, GL, Black Cumin, Andro-G, Agaricus, Mengkudu, dentifrice, Spirudle, mégapacks)
vaut pour tous ses formats : vérifier sur la planche quelle vignette montre le bon flacon.

## Affiches de formation du gérant (16 octobre 2026)

Six affiches fournies (1448 × 1086, texte arabe incrusté, visuels de synthèse), originaux dans
`tools/medias/sources/affiches-formations/`, converties en WebP 1200 × 900 dans
`tools/medias/formations/formation-*-affiche.webp`. Neuf affiches au total. Posées sur les six programmes ; `cosmetiques`, `nutrition`, `complements` attendent
une décision sur l'offre. Les natures mortes générées (`formation-strategie`, `formation-contenu`,
`formation-vendre`) restent dans le dossier, sans emploi.

## Packshots officiels Kallow (26 octobre 2026)

Le gérant a transmis le catalogue Kallow (DXN Cosmetics, édition arabe, 28 pages), archivé dans
`tools/medias/sources/kallow-catalogue/catalogue-kallow-ar.pdf`. Le PDF range chaque packshot en
couche séparée avec un masque de transparence : `tools/extraire-visuels-kallow.py` (pdfimages +
Pillow) les recompose sur blanc, les détoure et les dépose dans `tools/medias/produits/`, PNG de
contrôle à côté du PDF. Puis `normaliser-visuels.py` et l'import, comme pour le reste.

| | Produits | Taille utile du produit |
|---|---|---|
| Visuel posé pour la première fois | Foaming Cleanser, Tightening Serum, Argan Face Cream, Micellar Tonic, Face Mask, Divine Face Serum, Body Lotion, Lip Balm, Hand Cream, Sunscreen SPF 30, Tanning Oil, After Sun | 287 à 840 px |
| Packshot de synthèse remplacé par l'officiel | Shower Gel, Face Sunscreen SPF 50+, Divine Eye Cream, Divine Face Cream, Night Oil, Dry Oil | 508 à 795 px |

Trois visuels gardent le décor officiel collé au produit (perles du Tightening Serum, olive de
l'Argan Face Cream, aloe et menthe de l'After Sun) : il est dans la même image, il n'est pas
séparable. Le baume à lèvres est pris seul, sans le beurre de karité de la page.

> **Piège corrigé dans `normaliser-visuels.py` : les produits blancs.** Le chemin par seuil prenait
> le corps du pot du masque et du flacon SPF 50+ (pixels 245 à 252) pour du fond, le ramenait à
> 255, et il ne restait que l'étiquette. Un seuil plus haut ne suffisait pas : six sources touchent
> le bord de leur image, et les anciens packshots de synthèse auraient changé aussi. Le masque du
> PDF est donc **conservé comme canal alpha** du WebP déposé dans `produits/` ; le normaliseur
> reconnaît une source à son alpha et y lit exactement la boîte et la surface du produit. Les
> sources sans alpha suivent le code d'origine, inchangé : les 22 anciens visuels normalisés sont
> restés identiques à l'octet près.

**Toujours sans visuel après cette vague : 64 références.** Côté Kallow : Divine Men After Shave,
Summer Bling et Pink Glow Mist, et les six monodoses et échantillons, absents du catalogue. Le
second document du gérant (« منتوجات DXN وفوائدها », 24 pages) n'a pas servi : captures à 85 ppp,
produits de 150 à 300 px avec le filigrane « DXN's Property », et des textes faits d'allégations
thérapeutiques inutilisables.

**Textes.** Les douze fiches Kallow nouvelles ont été réécrites dans `rediger-catalogue.py` à partir
du catalogue : ingrédients clés, pourcentage d'ingrédients d'origine naturelle tel qu'imprimé sur
l'étiquette (cité comme tel), référence SC0xx, format réel et mode d'emploi. Aucune promesse
d'effet reprise (anti-rides, anti-âge, cicatrisant, antibactérien, collagène…). Trois corrections
d'emballage au passage, pour coller aux packshots : le masque est un pot et un masque de nuit sans
rinçage, le contour des yeux un pot de 15 ml, le SPF 50+ un flacon-pompe.

## Visuels de catégories avec les vrais produits (26 octobre 2026)

Les quatre ronds de la section « Nos catégories » de l'accueil montraient des natures mortes
génériques (pot de crème, théière, flacon ambré, savon) : aucun produit DXN n'y figurait. Le gérant
a fourni six mises en scène des produits réels dans la même ambiance (mur vert, travertin, olivier),
carrées, 1254 px — cinq le matin, celle du soin personnel le jour même. Sources archivées dans `tools/medias/sources/categories-gerant/` ; anciennes
vignettes gardées dans `reserve/cat-*-v1-generique.webp`.

| Catégorie | Fichier retenu | Source | Traitement |
|---|---|---|---|
| Beauté & soin | `categories/cat-beaute-soin.webp` | `beaute-soin-serre.png` (gamme Kallow) | 1200 × 1200, WebP q88 |
| Alimentation & boissons | `categories/cat-alimentation-boissons.webp` | `alimentation-boissons-serre.png` (cafés DXN, Morinzhi) | 1200 × 1200, WebP q88 |
| Compléments | `categories/cat-complements.webp` | `complements.png` (flacons DXN) | recadrage 1000 × 1000 (zone 135, 145 → 1135, 1145), jamais agrandi, WebP q88 |
| Soin personnel | `categories/cat-soin-personnel.webp` | `soin-personnel.png` (Ganozhi, Gano Massage Oil) | recadrage 1100 × 1100 (zone 45, 100 → 1145, 1200), jamais agrandi, WebP q88 |

**Pourquoi le cadrage serré.** La vignette est un rond de 184 px (taille `fati-categorie`, 320 px,
découpée par `border-radius:50%`) : les coins de l'image disparaissent et les produits doivent
remplir le centre. Les deux variantes larges (`*-large.png`, plus de mur et de feuillage) ont été
écartées après simulation du rond à 320 puis 184 px ; les images des compléments et du soin
personnel, seules variantes fournies, sont recadrées pour que leurs produits aient la même échelle
que les deux autres groupes (à 1000 px, le coin du savon Ganozhi touchait le bord du rond : 1100).

**Import.** `tools/importer-categories.sh` pose le visuel de chaque terme depuis
`categories/cat-<slug>.webp`, avec empreinte MD5 en méta de terme (`_fati_visuel_md5`) : un fichier
remplacé est ré-importé et l'ancien média supprimé, un fichier inchangé est laissé tel quel. Appelé
par `seed.sh` et par `publier-contenu-preversion.sh`. En local, passer la variable `WP` (socket
XAMPP) comme pour les importateurs Python.

## Recherche de sources en ligne et visuels Kallow de la boutique grecque (26 octobre 2026)

Demande du gérant : trouver des images fiables sur Internet pour les 64 références restantes.
Ce qui a été vérifié, pour ne pas le refaire :

| Source | Résultat |
|---|---|
| **dxn2u.eu** (site officiel DXN Europe, `product/img/gb/<CODE>_….jpg`) | 90 images, 735 × 960, **54 de nos 64 codes** — mais c'est la même banque filigranée « DXN's Property » que dxn2u.com. **Inutilisable telle quelle, et on ne retire pas un filigrane.** |
| dxnusa.com | Même structure et même banque. |
| dxn.com.mx, dxn-deutschland.de, sites IT/FR/PT/HU/SK/PL/RO | Vides, morts ou sans catalogue public. |
| **kallowshop.gr** (boutique officielle Kallow, Grèce, WooCommerce) | 25 fiches, JPEG 1200 × 1500 sur blanc, **sans filigrane**. Les 18 références SC081–SC098 (déjà posées depuis le PDF) plus **Divine Men Calming After Shave, Summer Bling et Pink Glow Mist, Natural Shield** (`Calming-After-Shave-.jpg`, `Summer-Bling-.jpg`, `Pink-Glow-.jpg`, `Natural-Shield-closed-.jpg`). Pas de monodoses ni d'échantillons. |
| Revendeurs (eBay, Jumia, Marjane Mall, vidadxn.es…) | Écartés : photos de tiers. |
| Higgsfield | Solde 2 crédits, plan gratuit : 1,5 crédit par packshot, soit ≈ 85 crédits pour 55 produits. Un pack de 500 crédits minimum. |

Les quatre images de kallowshop.gr sont archivées dans `tools/medias/sources/kallowshop/`
(`PROVENANCE.md`), renommées par slug, et posées par le nouveau `tools/poser-visuels-recus.py` :
il rattache chaque fichier par slug ou par référence DXN dans le nom, détoure le fond clair par
remplissage depuis les bords (les reflets intérieurs restent), et écrit le WebP à canal alpha que
lit le normaliseur. **Trois produits de plus ont leur visuel, le déodorant passe du packshot de
synthèse à l'officiel : 61 références sans visuel.** À confirmer : la bouteille grecque des brumes
porte « 150 ml » alors que le catalogue DXN Espagne (et le site) disent 100 ml.

**Pour les 55 références DXN et les 6 monodoses, aucune source en ligne propre n'existe.** Les
trois chemins, par ordre de préférence : images officielles de l'espace membre DXN de Fati ou photos
de son stock (déposées dans un dossier et posées par `poser-visuels-recus.py`, une heure de travail
une fois reçues) ; packshots provisoires Higgsfield à partir des références filigranées (crédits à
acheter, relecture des étiquettes) ; ou garder le gabarit « Visuel à venir », qui est honnête.

## Reste à produire

- Photos du stock réel (remplaceraient les packshots générés) et photo de travail de Fati pour la
  page Formations (blueprint E.5).
- Visuel du Lion's Mane Coffee et du Cordyceps Coffee **dans la version réellement vendue**
  (1 in 1 / 3 in 1, voir `journal.md`).
