#!/bin/bash
# Change le numéro WhatsApp d'un site en ligne (réglage fati_settings.whatsapp, celui du
# back-office : Contenus du site › Général). Tous les boutons WhatsApp du thème le lisent.
#
#   bash tools/changer-numero-whatsapp.sh preversion 34604284263
#   bash tools/changer-numero-whatsapp.sh production 34604284263
#
# Numéro au format international, chiffres seuls, sans le « + ». L'ancien numéro est consigné
# dans ~/backups/fati-whatsapp.log sur le serveur avant l'écriture.
#
# Les pages légales citent aussi le numéro, en clair : le remplacer dans _velix/copy-legal.md
# et tools/traductions/ar/legal/, puis lancer tools/publier-pages-legales-en-ligne.sh.
# En production, vider ensuite le cache du CDN.
set -e
cd "$(dirname "$0")/.."
case "${1:-}" in
  preversion) RACINE='domains/velixdigital.com/public_html/fatichanelya' ;;
  production) RACINE='domains/fatichanelya.com/public_html' ;;
  *) echo "Usage : $0 preversion|production <numéro, chiffres seuls>"; exit 1 ;;
esac
NUMERO="${2:-}"
if ! [[ "$NUMERO" =~ ^[0-9]{8,15}$ ]]; then
  echo "Numéro attendu en chiffres seuls, indicatif compris, sans « + » (exemple : 34604284263)."
  exit 1
fi
ssh hostinger bash -s "$RACINE" "$NUMERO" "$1" <<'DISTANT'
set -e
cd "$HOME/$1"
mkdir -p "$HOME/backups"
AVANT=$(wp option pluck fati_settings whatsapp </dev/null)
echo "$(date '+%F %T') $3 : $AVANT -> $2" >> "$HOME/backups/fati-whatsapp.log"
wp option patch update fati_settings whatsapp "$2" </dev/null
echo "   numéro WhatsApp : $AVANT → $(wp option pluck fati_settings whatsapp </dev/null)"
DISTANT
echo "✔ Numéro WhatsApp changé ($1)"
