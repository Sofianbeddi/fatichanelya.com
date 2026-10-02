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
- Solde au 2 oct. 2026 après l'ajout de l'Omega 3 : **11 crédits** (47 images dont 2 tests, 39 crédits dépensés).

## Visuels produits — 27 packshots, fond blanc

| Fichier (`tools/medias/produits/`) | Source de la référence | Statut |
|---|---|---|
| cordyceps-coffee, lingzhi-coffee, lingzhi-coffee-3-en-1, lions-mane-coffee, kallow-shower-gel, kallow-sunscreen-spf50, natural-shield-deo, virgin-coconut-oil, gano-massage-oil | emballage découpé dans les affiches fournies par le gérant le 2 oct. 2026 (`tools/medias/sources/affiches-gerant/` à archiver — fichiers 1–17) | **généré** nano_banana_2, 1:1, 1k |
| morinzhi, mycoveggie, poria-s, reishi-powder, rg-90, divine-night-oil, kallow-cosmetics, lions-mane, roselle, spirulina | ancien visuel DXN filigrané « DXN's Property » (`sources/produits-dxn-filigranes/`) | **généré** nano_banana_2 à partir de l'ancien visuel ; filigrane absent du rendu |
| omega-3 | visuel officiel DXN HF280 (`sources/omega-3-officiel.png`, PNG détouré relevé chez un revendeur agréé espagnol) ; la photo du kakémono fournie par le gérant est dans `sources/affiches-gerant/18.png` | **généré** nano_banana_2 à partir du visuel officiel : flacon blanc sans ombrage, illisible sur fond blanc une fois aplati |
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

## Visuels de sections — 5 natures mortes, palette du site

| Fichier | Emploi | Statut |
|---|---|---|
| `formations/formation-ecommerce.webp` | carte « Lancer son e-commerce » | généré, 4:3, 1200 × 896 |
| `formations/formation-vendre.webp` | carte « Vendre avec confiance » | généré |
| `formations/formation-ia.webp` | carte « L'IA au quotidien » | généré |
| `formations/formation-strategie.webp` | carte « Stratégie digitale » | généré |
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
`categories/cat-*.webp` : fournies par le gérant (sept. 2026). Ce sont les seules preuves
visuelles du site.

`pages/about-main.webp` — **portrait de la section « À propos »**, recadré le 2 oct. 2026 depuis
`sources/hero-fati-source.png` (zone 1015, 45 → 1405, 738, agrandie 1,59× en Lanczos, léger
renfort de netteté). **Aucun traitement génératif sur le visage** : pas d'agrandissement par IA,
qui retoucherait les traits d'une personne réelle. Il remplace une capture TikTok (« San
Sebastian », texte incrusté) que le gérant a jugée hors contexte et pas professionnelle ;
l'ancienne est dans `sources/about-main-san-sebastian.webp`. Le hero et ce portrait viennent de la
même photo : c'est un intérim, la cible reste une vraie séance (blueprint E.5).

## Reste à produire

- Photos du stock réel (remplaceraient les packshots générés) et photo de travail de Fati pour la
  page Formations (blueprint E.5).
- Visuel du Lion's Mane Coffee et du Cordyceps Coffee **dans la version réellement vendue**
  (1 in 1 / 3 in 1, voir `journal.md`).
