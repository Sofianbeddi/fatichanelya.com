# Visuels manquants — 7 références au 26 octobre 2026 (soir)

Les 54 références DXN listées ici le 26 octobre au matin ont reçu leur visuel le soir même : copies
retouchées des images dxn2u.eu livrées par le gérant (voir `medias.md`, « Visuels DXN retouchés »).
Restent sept produits pour lesquels **aucune image n'existe nulle part** : ni sur dxn2u.eu, ni sur
kallowshop.gr, ni dans les catalogues PDF. Le site affiche pour eux le gabarit « Visuel à venir ».

| # | Produit | Code DXN | Catégorie | Piste |
|---|---|---|---|---|
| 1 | DXN Brook Coffee | FB616 | Alimentation & boissons | photo du stock de Fati (référence récente, absente des banques DXN) |
| 2 | Kallow Divine Touch Lifting Impact Eye Cream — 10 monodoses | SC101 | Beauté & soin | photo du stock de Fati, ou retirer la fiche si elle ne vend pas les monodoses |
| 3 | Kallow Divine Touch Lifting Impact Face Serum — 10 monodoses | SC102 | Beauté & soin | photo du stock de Fati, ou retirer la fiche si elle ne vend pas les monodoses |
| 4 | Kallow Divine Touch Lifting Impact Face Cream — 10 monodoses | SC103 | Beauté & soin | photo du stock de Fati, ou retirer la fiche si elle ne vend pas les monodoses |
| 5 | Kallow Spirulina & Avocado Hydrating Face Mask — 10 échantillons | SC106 | Beauté & soin | photo du stock de Fati, ou retirer la fiche si elle ne vend pas les monodoses |
| 6 | Kallow High Protection Face Sunscreen SPF 50+ — 10 échantillons | SC104 | Beauté & soin | photo du stock de Fati, ou retirer la fiche si elle ne vend pas les monodoses |
| 7 | Kallow Luxury Aromatic Body Lotion — 10 monodoses | SC105 | Beauté & soin | photo du stock de Fati, ou retirer la fiche si elle ne vend pas les monodoses |

Une fois les photos reçues, nommer les fichiers par le code DXN (ou le slug) et lancer :

```
python3 tools/poser-visuels-recus.py <dossier>
python3 tools/normaliser-visuels.py
# renseigner image="<slug>.webp" dans tools/rediger-catalogue.py, puis :
python3 tools/rediger-catalogue.py
WP="php -d mysqli.default_socket=/Applications/XAMPP/xamppfiles/var/mysql/mysql.sock /usr/local/bin/wp" python3 tools/importer-catalogue.py
bash tools/publier-contenu-preversion.sh
```
