#!/bin/bash
# Publie la VERSION ARABE sur la préversion (fatichanelya.velixdigital.com) :
# Polylang (installation et réglages), paquets de langue, puis les contenus
# traduits de tools/traductions/ar/ (catégories, pages, pages légales,
# formations, produits, menus, réglages).
#
#   bash tools/publier-traductions-preversion.sh
#
# Le thème (avec languages/ar.mo et les polices arabes) et le mu-plugin se
# déploient à part : tools/deploy-preversion.sh, à lancer AVANT ce script.
# Idempotent : relancer met à jour les traductions sans créer de doublon.
set -e
cd "$(dirname "$0")/.."
HOTE=hostinger
RACINE='domains/velixdigital.com/public_html/fatichanelya'

echo "→ Envoi des traductions et des scripts"
ssh "$HOTE" "mkdir -p $RACINE/tools/traductions"
rsync -az --delete --exclude=".DS_Store" -e ssh tools/traductions/ "$HOTE:$RACINE/tools/traductions/"
rsync -az -e ssh tools/configurer-polylang.php tools/importer-traductions.php "$HOTE:$RACINE/tools/"

ssh "$HOTE" bash -s "$RACINE" <<'DISTANT'
set -e
cd "$1"
wp() { command wp "$@" </dev/null; }
if ! grep -q 'RedirectMatch 404 "\^/tools' .htaccess; then
  printf '\n# Outils d%s\n<IfModule mod_alias.c>\nRedirectMatch 404 "^/tools(/|$)"\n</IfModule>\n' "'import, jamais servis" | cat - .htaccess > .htaccess.tmp && mv .htaccess.tmp .htaccess
fi

echo "→ Polylang"
if ! wp plugin is-installed polylang; then wp plugin install polylang; fi
wp plugin activate polylang >/dev/null
echo "   $(wp plugin get polylang --field=version)"

echo "→ Paquets de langue (arabe, français)"
wp language core install ar >/dev/null 2>&1 || true
wp language core install fr_FR >/dev/null 2>&1 || true
wp language core list --status=installed --field=language | tr '\n' ' '; echo

echo "→ Réglages Polylang"
wp eval-file tools/configurer-polylang.php

echo "→ Contenus arabes"
wp eval-file tools/importer-traductions.php | grep -v "^   [~+]"

wp transient delete pll_languages_list >/dev/null 2>&1 || true
wp cache flush >/dev/null
echo "✔ Version arabe publiée"
DISTANT
