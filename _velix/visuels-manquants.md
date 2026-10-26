# Visuels manquants — aucun au 26 octobre 2026 (soir)

Les 103 produits ont un visuel. Historique du jour : 54 références DXN posées depuis les copies
retouchées des images dxn2u.eu livrées par le gérant ; six monodoses et échantillons Kallow sur le
visuel de leur produit parent ; faces avant des quatre Megapack et visuel Brook Coffee en second
dépôt. Détail et réserves (textes des Megapack reconstruits, faces avant du format courant) dans
`medias.md`.

## Pour remplacer ou ajouter un visuel

Nommer le fichier par le code DXN (`FB124 - Cocozhi.png`) ou par le slug (`cocozhi.png`), sur fond
blanc ou transparent, puis :

```
python3 tools/poser-visuels-recus.py <dossier>
python3 tools/normaliser-visuels.py
# nouveau produit : renseigner image="<slug>.webp" dans tools/rediger-catalogue.py, puis :
python3 tools/rediger-catalogue.py
WP="php -d mysqli.default_socket=/Applications/XAMPP/xamppfiles/var/mysql/mysql.sock /usr/local/bin/wp" python3 tools/importer-catalogue.py
bash tools/publier-contenu-preversion.sh
```

Un fichier à fond transparent dont le nom ne porte ni code ni slug se copie sous le slug après
aplatissement sur blanc (voir `medias.md`, second dépôt du 26 octobre).
