#!/bin/bash
# Met à jour les huit pages légales en ligne (FR + AR) depuis _velix/copy-legal.md et
# tools/traductions/ar/legal/. Ne crée aucune page et ne touche à rien d'autre.
#
#   bash tools/publier-pages-legales-en-ligne.sh preversion
#   bash tools/publier-pages-legales-en-ligne.sh production
#
# Les sources et le script partent dans ~/backups/fati-legal-<cible>-<date>/, hors de la racine
# web, et le contenu en place y est sauvegardé (avant.json) avant toute écriture.
# En production, vider ensuite le cache du CDN (hPanel › Performance › CDN, ou l'API Hostinger).
set -e
cd "$(dirname "$0")/.."
case "${1:-}" in
  preversion) RACINE='domains/velixdigital.com/public_html/fatichanelya' ;;
  production) RACINE='domains/fatichanelya.com/public_html' ;;
  *) echo "Usage : $0 preversion|production"; exit 1 ;;
esac
DOSSIER="backups/fati-legal-$1-$(date +%Y%m%d-%H%M%S)"
ssh hostinger "mkdir -p ~/$DOSSIER/ar"
rsync -az -e ssh _velix/copy-legal.md tools/mettre-a-jour-pages-legales.php hostinger:"$DOSSIER/"
rsync -az --exclude=".DS_Store" -e ssh tools/traductions/ar/legal/ hostinger:"$DOSSIER/ar/"
ssh hostinger bash -s "$RACINE" "$DOSSIER" <<'DISTANT'
set -e
cd "$HOME/$1"
D="$HOME/$2"
wp eval-file "$D/mettre-a-jour-pages-legales.php" "$D/copy-legal.md" "$D/ar" "$D/avant.json" </dev/null
DISTANT
echo "✔ Pages légales publiées ($1)"
