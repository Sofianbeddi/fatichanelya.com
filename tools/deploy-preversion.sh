#!/bin/bash
# Déploie le thème et le mu-plugin sur la PRÉVERSION client (Hostinger).
# Usage : ./tools/deploy-preversion.sh
# Ne touche ni à la base, ni aux médias, ni à wp-config.php : le contenu saisi
# en ligne n'est jamais écrasé. Un nouveau média ou un changement de contenu
# local se porte à part (WP-CLI via `ssh hostinger`).
set -e
cd "$(dirname "$0")/.."
RACINE='~/domains/velixdigital.com/public_html/fatichanelya'
URL='https://fatichanelya.velixdigital.com'
rsync -az --delete --exclude=".DS_Store" -e ssh wp-content/themes/fatichanelya/ hostinger:"$RACINE/wp-content/themes/fatichanelya/"
# Les mu-plugins de l'hébergeur (hostinger-*.php) ne sont pas dans le dépôt : --delete les
# supprimerait. Ils sont protégés par le filtre.
rsync -az --delete --exclude=".DS_Store" --filter="protect hostinger-*" -e ssh wp-content/mu-plugins/ hostinger:"$RACINE/wp-content/mu-plugins/"
echo "✔ Thème déployé en préversion → $URL"
