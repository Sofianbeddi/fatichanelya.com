#!/bin/bash
# Publie le CONTENU sur la préversion (fatichanelya.velixdigital.com) :
# catalogue produits (textes, métas, visuels), formations (programmes, métas,
# visuels), image de la section Formations et image de partage.
#
#   bash tools/publier-contenu-preversion.sh
#
# Le thème se déploie à part (tools/deploy-preversion.sh). Ce script envoie le
# dossier tools/ sur le serveur — nécessaire aux importateurs, qui lisent
# tools/catalogue.csv, tools/formations.csv, tools/formations/ et tools/medias/ —
# puis le rend inaccessible depuis le web.
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

echo "→ Formations"
"$PY" tools/importer-formations.py </dev/null

echo "→ Images de réglage (section Formations, partage, À propos)"
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
poser_option_image about_avatar   tools/medias/pages/about-avatar.webp    "about-avatar-2"

echo "→ Page Panier"
# Le thème 1.2.0 cherche la page « panier » ; elle s'appelait « ma-selection ».
ancienne=$(wp post list --post_type=page --name=ma-selection --field=ID --posts_per_page=1)
if [ -n "$ancienne" ]; then
  wp post update "$ancienne" --post_name=panier --post_title="Panier" >/dev/null
  echo "   ma-selection → panier"
fi

wp cache flush >/dev/null
echo "✔ Contenu publié"
DISTANT
