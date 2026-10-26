#!/bin/bash
# Pose le visuel de chaque catégorie de produit, depuis
# tools/medias/categories/cat-<slug>.webp (un fichier par slug de terme).
#
#   bash tools/importer-categories.sh
#   WP="php -d mysqli.default_socket=/Applications/XAMPP/xamppfiles/var/mysql/mysql.sock /usr/local/bin/wp" \
#     bash tools/importer-categories.sh      # XAMPP local : WP-CLI par le socket
#
# Idempotent : l'empreinte MD5 du fichier est gardée en méta de terme
# (_fati_visuel_md5). Un fichier inchangé n'est pas ré-importé ; un fichier
# remplacé l'est, et l'ancien média est supprimé pour ne pas grossir la
# médiathèque — même logique que les visuels produits (importer-catalogue.py).
# Appelé par seed.sh (première installation) et par publier-contenu-preversion.sh.
set -u
cd "$(dirname "$0")/.."
WP="${WP:-wp}"
# stdin fermé : sous `ssh … bash -s`, WP-CLI avalerait sinon la suite du script.
wp() { $WP "$@" </dev/null; }
empreinte() { if command -v md5sum >/dev/null 2>&1; then md5sum "$1" | cut -d' ' -f1; else md5 -q "$1"; fi; }

for f in tools/medias/categories/cat-*.webp; do
  [ -e "$f" ] || continue
  slug=$(basename "$f" .webp); slug="${slug#cat-}"
  tid=$(wp term list categorie_produit --slug="$slug" --field=term_id 2>/dev/null)
  [ -z "$tid" ] && { echo "   ? $slug : catégorie absente"; continue; }
  md5=$(empreinte "$f")
  actuel=$(wp term meta get "$tid" _fati_visuel 2>/dev/null || true)
  connu=$(wp term meta get "$tid" _fati_visuel_md5 2>/dev/null || true)
  if [ -n "$actuel" ] && [ "$connu" = "$md5" ] && wp post get "$actuel" --field=ID >/dev/null 2>&1; then
    echo "   = $slug (visuel $actuel)"; continue
  fi
  att=$(wp media import "$f" --title="cat-$slug" --porcelain) || { echo "   ! $slug : import échoué"; continue; }
  wp term meta update "$tid" _fati_visuel "$att" >/dev/null 2>&1 || true
  wp term meta update "$tid" _fati_visuel_md5 "$md5" >/dev/null 2>&1 || true
  if [ -n "$actuel" ] && [ "$actuel" != "$att" ] && [ "$actuel" -eq "$actuel" ] 2>/dev/null; then
    wp post delete "$actuel" --force >/dev/null 2>&1 || true
  fi
  echo "   + $slug → visuel $att${actuel:+ (remplace $actuel)}"
done
