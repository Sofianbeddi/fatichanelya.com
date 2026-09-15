# Analyse UI/UX — Fatichanelya

Vague du 15 septembre 2026, à la demande du gérant. Périmètre : bugs d'affichage
après l'unification de la largeur, et intégration des images fournies (hero et
quatre catégories).

Cas appliqué : **le gérant a décrit précisément les améliorations**. Pas d'analyse
préalable séparée, le diagnostic est fait pièce par pièce en mesurant.

---

## Constats et corrections

| # | Constat mesuré | Correction | État |
|---|---|---|---|
| 1 | Le hero affichait un **portrait généré**. Sur un site dont la promesse est « une vraie personne qui répond », c'était le risque numéro un du brief. | Vraie photo de Fati installée, recadrée en 3:4 sur sa silhouette (détectée par la variance des colonnes). L'ancien portrait est retiré de la médiathèque. | ✅ |
| 2 | Les quatre catégories portaient des **natures mortes générées**. | Remplacées par les quatre images du gérant. Le cinquième fichier était nommé « cat-beaute » mais montrait la théière : reclassé en Alimentation & boissons. | ✅ |
| 3 | **Hero de 918 px de haut** à 1440 : l'image imposait sa hauteur intrinsèque à la rangée (620 × 941 à 605 px de large). | Image sortie du flux (`position:absolute`), la rangée est fixée par le texte et le minimum. Hero à 727 px. | ✅ |
| 4 | **Titre sur quatre lignes** à 1440 : le bord unifié à 80 px avait repris 16 px à une colonne déjà plafonnée à 736 px. | Colonne de texte portée à 52 rem, pente du `clamp` de la taille adoucie. Trois lignes à 1440 et 1280. | ✅ |
| 5 | **Titre sur quatre lignes à 1024** : 58 % de largeur ne laissaient que 517 px. | Palier tablette : 63 / 37 entre 981 et 1180 px. Trois lignes à 1024. | ✅ |
| 6 | Bandeau d'engagements : titres **décalés de 4 px** selon que le texte descriptif tenait sur une ou deux lignes. | `align-items:start` avec l'icône seule centrée. | ✅ |
| 7 | Les visuels de catégories n'étaient pas dans le script de réinstallation : **perdus à la prochaine réinitialisation**. | Étape ajoutée à `tools/seed.sh`, nommage `cat-<slug>.webp`. | ✅ |

## Fichiers modifiés

- `tools/medias/pages/hero-fati.webp` — nouveau portrait, 46 Ko
- `tools/medias/categories/cat-*.webp` — quatre images du gérant
- `tools/medias/sources/` — originaux du gérant, conservés hors médiathèque
- `tools/medias/reserve/hero-fati-large.webp` — version pleine largeur, si le gérant préfère
- `wp-content/themes/fatichanelya/assets/css/main.css` — hero, bandeau
- `tools/seed.sh` — import des visuels de catégories

## Contrastes recontrôlés

Aucun échec sur les sept pages mesurées. La signature du portrait, en blanc sur
voile dégradé, a été mesurée sur les pixels : 18,24:1.

## Vérifié au navigateur

- 10 pages × 4 largeurs (375, 768, 1024, 1440) : zéro erreur console, zéro débordement, zéro image cassée
- 4 pages × 4 largeurs supplémentaires (320, 1280, 1600, 1920) : bords alignés, aucun débordement
- Hero : 727 px à 1440, titre sur 3 lignes à 1024, 1280 et 1440, portrait visible en mobile

## Écarté volontairement

- **Hero pleine largeur avec texte à droite.** L'image du gérant s'y prêterait (mur vide à gauche, Fati à droite), mais la maquette validée met le texte à gauche, et un aplat sauge sur toute la largeur réintroduirait une grande surface verte alors que le gérant l'a proscrite. La version large est en réserve.
- **Recadrage plus serré sur le visage.** Le buste avec les bras croisés dit « entrepreneure » mieux qu'un gros plan.

## Reste ouvert

- Les visuels **produits** portent toujours le filigrane « DXN's Property ». Il faut les fichiers officiels du back-office de Fati, ou des photos de son stock.
- Le numéro WhatsApp n'est pas renseigné : les boutons mènent à la page Contact.
