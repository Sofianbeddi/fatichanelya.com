#!/bin/bash
# Thème 1.10.0 (17 nov. 2026) : nouveau portrait du hero et nouveau titre en PRODUCTION.
#
#   bash tools/publier-hero-production.sh          # simulation : lit l'état en ligne, n'écrit rien
#   bash tools/publier-hero-production.sh --go     # sauvegarde de la base, puis écriture
#
# --go seulement après le feu vert explicite du gérant (règle du projet depuis le 13 nov. 2026).
#
# Ordre de mise en ligne :
#   1. tools/deploy-production.sh --go    thème 1.10.0 (logo, hero sans points clés, fond de studio,
#                                          en-tête qui se réduit au défilement) et mu-plugin 1.3.0
#                                          (nouveau titre par défaut, champ « Points clés » retiré)
#   2. ce script --go                     photo détourée posée sur `hero_image`, titre traduit en
#                                          arabe, espagnol et anglais (chaînes Polylang)
#
# Le mu-plugin doit être en ligne avant l'étape 2 : Polylang range chaque traduction sous le texte
# français exact, lu dans les réglages en place. Si le titre a été enregistré un jour depuis le
# back-office, l'ancien texte est stocké en base et masquerait le nouveau titre par défaut : le
# script le remplace (et le signale). Rien d'autre n'est touché.
set -e
cd "$(dirname "$0")/.."
RACINE='domains/fatichanelya.com/public_html'
MODE="${1:-simulation}"
NOUVEAU_TITRE='Entrepreneure en *vente directe* et marketing digital'

DOSSIER="backups/fati-hero-1.10.0-$(date +%Y%m%d-%H%M%S)"
ssh hostinger "mkdir -p ~/$DOSSIER/tools/traductions/ar ~/$DOSSIER/tools/traductions/es ~/$DOSSIER/tools/traductions/en"
rsync -az -e ssh tools/medias/pages/hero-fati.webp hostinger:"$DOSSIER/hero-fati.webp"
rsync -az -e ssh tools/importer-reglages-ar.php hostinger:"$DOSSIER/tools/"
for l in ar es en; do
  rsync -az -e ssh "tools/traductions/$l/reglages.json" hostinger:"$DOSSIER/tools/traductions/$l/"
done

ssh hostinger bash -s "$RACINE" "$DOSSIER" "$MODE" "$NOUVEAU_TITRE" <<'DISTANT'
set -e
cd "$HOME/$1"
D="$HOME/$2"
TITRE="$4"
wp() { command wp "$@" </dev/null; }

VERSION=$(wp eval 'echo wp_get_theme( "fatichanelya" )->get( "Version" );')
echo "→ Thème en ligne : $VERSION"
STOCKE=$(wp option pluck fati_settings hero_titre 2>/dev/null || true)
echo "→ Titre stocké en base : ${STOCKE:-(aucun, la valeur par défaut du mu-plugin s’applique)}"
echo "→ Photo du hero en ligne : $(wp option pluck fati_settings hero_image 2>/dev/null || echo '(aucune)')"

if [ "$3" != "--go" ]; then
  echo "→ SIMULATION — rien n'est écrit"
  [ "$VERSION" = "1.10.0" ] || echo "   ! Le thème en ligne n'est pas en 1.10.0 : lancer d'abord tools/deploy-production.sh --go"
  echo "Pour publier : bash tools/publier-hero-production.sh --go (après feu vert)"
  exit 0
fi
if [ "$VERSION" != "1.10.0" ]; then
  echo "✗ Thème en ligne en $VERSION : déployer la 1.10.0 d'abord (tools/deploy-production.sh --go). Rien n'a été écrit."
  exit 1
fi

echo "→ Sauvegarde de la base"
SAUVEGARDE="$D/base-avant.sql.gz"
MYSQL_PWD="$(wp config get DB_PASSWORD)" mysqldump --single-transaction --no-tablespaces \
  -h "$(wp config get DB_HOST)" -u "$(wp config get DB_USER)" "$(wp config get DB_NAME)" | gzip > "$SAUVEGARDE"
TAILLE=$(stat -c %s "$SAUVEGARDE")
if [ "$TAILLE" -lt 100000 ]; then
  echo "✗ Sauvegarde trop petite ($TAILLE octets) : arrêt, rien n'a été écrit."; exit 1
fi
echo "   $SAUVEGARDE ($TAILLE octets)"

echo "→ Portrait détouré (média « hero-fati-3 »)"
ATT=$(wp post list --post_type=attachment --title="hero-fati-3" --field=ID --posts_per_page=1)
if [ -z "$ATT" ]; then ATT=$(wp media import "$D/hero-fati.webp" --title="hero-fati-3" --porcelain); fi
URL=$(wp post get "$ATT" --field=guid)
wp option patch insert fati_settings hero_image "$URL" >/dev/null 2>&1 || wp option patch update fati_settings hero_image "$URL" >/dev/null
echo "   hero_image → $URL"

if [ -n "$STOCKE" ] && [ "$STOCKE" != "$TITRE" ]; then
  echo "→ Titre stocké en base remplacé : « $STOCKE » → « $TITRE »"
  wp option patch update fati_settings hero_titre "$TITRE" >/dev/null
fi

for l in ar es en; do
  echo "→ Titre traduit « $l »"
  wp eval-file "$D/tools/importer-reglages-ar.php" "$D/tools/traductions/$l/reglages.json"
done

wp cache flush >/dev/null
echo "✔ Hero 1.10.0 publié. Restauration si besoin :"
echo "   gunzip -c $SAUVEGARDE | wp db import -"
DISTANT
