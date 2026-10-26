# Visuels manquants — 1 référence au 26 octobre 2026 (soir)

Les 54 références DXN listées ici le 26 octobre au matin ont reçu leur visuel le soir même (copies
retouchées des images dxn2u.eu livrées par le gérant), et les six monodoses et échantillons Kallow
reprennent le visuel de leur produit parent (décision du gérant). Voir `medias.md`.

| # | Produit | Code DXN | Catégorie | Piste |
|---|---|---|---|---|
| 1 | DXN Brook Coffee | FB616 | Alimentation & boissons | photo du stock de Fati (référence récente, absente des banques DXN et des catalogues PDF) |

Une fois la photo reçue, la nommer par le code DXN (ou le slug `brook-coffee`) et lancer :

```
python3 tools/poser-visuels-recus.py <dossier>
python3 tools/normaliser-visuels.py
# renseigner image="brook-coffee.webp" dans tools/rediger-catalogue.py, puis :
python3 tools/rediger-catalogue.py
WP="php -d mysqli.default_socket=/Applications/XAMPP/xamppfiles/var/mysql/mysql.sock /usr/local/bin/wp" python3 tools/importer-catalogue.py
bash tools/publier-contenu-preversion.sh
```
