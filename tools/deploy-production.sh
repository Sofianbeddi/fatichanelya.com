#!/bin/bash
# Déploie le thème et le mu-plugin sur la PRODUCTION (fatichanelya.com, Hostinger).
# Usage : ./tools/deploy-production.sh            (simulation : liste ce qui changerait)
#         ./tools/deploy-production.sh --go       (envoi réel, après archive du thème en ligne)
# Ne touche ni à la base, ni aux médias, ni à wp-config.php : depuis le lancement du
# 3 nov. 2026, le contenu de la production fait foi. La préversion reste le banc d'essai :
# y passer d'abord avec deploy-preversion.sh.
set -e
cd "$(dirname "$0")/.."
RACINE='~/domains/fatichanelya.com/public_html'
URL='https://fatichanelya.com'
OPTS=(-az --delete --exclude=".DS_Store" -e ssh)
# Les mu-plugins de l'hébergeur (hostinger-*.php) ne sont pas dans le dépôt : --delete les
# supprimerait.
MU=(--filter="protect hostinger-*")
if [ "${1:-}" != "--go" ]; then
  echo "Simulation (ajouter --go pour envoyer) :"
  rsync -n --itemize-changes "${OPTS[@]}" wp-content/themes/fatichanelya/ hostinger:"$RACINE/wp-content/themes/fatichanelya/"
  rsync -n --itemize-changes "${OPTS[@]}" "${MU[@]}" wp-content/mu-plugins/ hostinger:"$RACINE/wp-content/mu-plugins/"
  exit 0
fi
ARCHIVE="backups/fati-prod-theme-avant-$(date +%Y%m%d-%H%M).tgz"
ssh hostinger "mkdir -p ~/backups && tar czf ~/$ARCHIVE -C $RACINE wp-content/themes/fatichanelya wp-content/mu-plugins"
echo "   archive du thème en ligne : ~/$ARCHIVE"
rsync "${OPTS[@]}" wp-content/themes/fatichanelya/ hostinger:"$RACINE/wp-content/themes/fatichanelya/"
rsync "${OPTS[@]}" "${MU[@]}" wp-content/mu-plugins/ hostinger:"$RACINE/wp-content/mu-plugins/"
echo "✔ Thème déployé en production → $URL"
echo "  Si une page garde l'ancienne version : vider le cache du CDN (hPanel › Performance › CDN)."
