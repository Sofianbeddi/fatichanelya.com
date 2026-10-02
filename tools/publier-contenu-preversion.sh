#!/bin/bash
# Publie le CONTENU sur la préversion (fatichanelya.velixdigital.com) :
# catalogue produits (textes, métas, visuels), visuels des formations, image de
# la section Formations et image de partage.
#
#   bash tools/publier-contenu-preversion.sh
#
# Le thème se déploie à part (tools/deploy-preversion.sh). Ce script envoie le
# dossier tools/ sur le serveur — nécessaire à l'importateur, qui lit
# tools/catalogue.csv et tools/medias/ — puis le rend inaccessible depuis le web.
# Idempotent : relancer ne crée ni doublon ni ré-import inutile (empreinte MD5).
set -e
cd "$(dirname "$0")/.."
HOTE=hostinger
RACINE='domains/velixdigital.com/public_html/fatichanelya'

echo "→ Envoi de tools/ (catalogue, médias, scripts)"
rsync -az --delete --exclude=".DS_Store" --exclude="sources/" -e ssh tools/ "$HOTE:$RACINE/tools/"

ssh "$HOTE" bash -s "$RACINE" <<'DISTANT'
set -e
cd "$1"
# Ce script arrive par l'entrée standard (`bash -s`). Toute commande qui lit
# stdin avalerait la suite du script : WP-CLI le fait, et tout s'arrêtait en
# silence après le premier réglage, code de sortie 0. On lui ferme stdin.
wp() { command wp "$@" </dev/null; }
# Le dossier d'outils ne doit pas être servi.
if ! grep -q 'RedirectMatch 404 "\^/tools' .htaccess; then
  printf '\n# Outils d%s\n<IfModule mod_alias.c>\nRedirectMatch 404 "^/tools(/|$)"\n</IfModule>\n' "'import, jamais servis" | cat - .htaccess > .htaccess.tmp && mv .htaccess.tmp .htaccess
fi

echo "→ Catalogue"
# Le python3 par défaut de l'hébergeur est un 3.6 ; un 3.11 est fourni à côté.
PY=$(command -v /opt/alt/python311/bin/python3 || command -v python3)
"$PY" tools/importer-catalogue.py </dev/null

echo "→ Visuels des formations"
for pair in "lancer-ecommerce:formation-ecommerce" "vendre-avec-confiance:formation-vendre" "ia-au-quotidien:formation-ia" "strategie-digitale:formation-strategie"; do
  slug=${pair%%:*}; img=${pair##*:}
  id=$(wp post list --post_type=formation --name="$slug" --field=ID --posts_per_page=1)
  [ -z "$id" ] && { echo "   ? $slug absente"; continue; }
  md5=$(md5sum "tools/medias/formations/$img.webp" | cut -d' ' -f1)
  if [ "$(wp post meta get "$id" _fati_visuel_md5 2>/dev/null)" = "$md5" ]; then echo "   = $slug"; continue; fi
  old=$(wp post meta get "$id" _thumbnail_id 2>/dev/null || true)
  wp media import "tools/medias/formations/$img.webp" --post_id="$id" --featured_image --title="$img" --porcelain >/dev/null
  wp post meta update "$id" _fati_visuel_md5 "$md5" >/dev/null
  [ -n "$old" ] && wp post delete "$old" --force >/dev/null 2>&1 || true
  echo "   + $slug"
done

echo "→ Images de réglage (section Formations, partage)"
poser_option_image() { # clé, fichier, titre
  local cle="$1" fichier="$2" titre="$3" att url
  att=$(wp post list --post_type=attachment --title="$titre" --field=ID --posts_per_page=1)
  if [ -z "$att" ]; then att=$(wp media import "$fichier" --title="$titre" --porcelain); fi
  url=$(wp post get "$att" --field=guid)
  wp option patch insert fati_settings "$cle" "$url" >/dev/null 2>&1 || wp option patch update fati_settings "$cle" "$url" >/dev/null
  echo "   $cle → $url"
}
poser_option_image training_image tools/medias/pages/training-session.webp "training-session-2"
poser_option_image og_image       tools/medias/pages/og-default.jpg       "og-default"
poser_option_image about_image    tools/medias/pages/about-main.webp      "about-main-2"

wp cache flush >/dev/null
echo "✔ Contenu publié"
DISTANT
