#!/bin/bash
# Publie les versions ESPAGNOLE et ANGLAISE en PRODUCTION (fatichanelya.com), avec la
# configuration Polylang 1.9.0 (langues es / en, accueil selon la langue du navigateur).
#
#   bash tools/publier-traductions-production.sh          # simulation : n'écrit rien en ligne
#   bash tools/publier-traductions-production.sh --go     # sauvegarde de la base, puis import
#
# --go seulement après le feu vert explicite du gérant (règle du projet depuis le 13 nov. 2026).
#
# Ordre de mise en ligne :
#   1. tools/deploy-production.sh --go        thème 1.9.0 (.mo es_ES / en_GB, suggestion de langue)
#                                              et mu-plugin 1.2.0 (repli anglais, x-default, Vary)
#   2. ce script --go                          langues, contenus, menus, réglages
#   3. tools/publier-pages-legales-en-ligne.sh production   (paragraphe Cookies FR + AR)
#
# Les sources partent hors de la racine web (~/backups/fati-traductions-production-<date>/tools/),
# jamais dans public_html. Le contenu de la production fait foi : seuls les contenus ES / EN, leurs
# menus et leurs chaînes sont créés ou mis à jour ; le français et l'arabe ne sont pas touchés.
set -e
cd "$(dirname "$0")/.."
RACINE='domains/fatichanelya.com/public_html'
MODE="${1:-simulation}"
export WP="${WP:-php -d mysqli.default_socket=/Applications/XAMPP/xamppfiles/var/mysql/mysql.sock /usr/local/bin/wp}"

echo "→ Contrôle des traductions (bloquant)"
for l in es en; do python3 tools/traductions/verifier.py "$l" --complet; done

DOSSIER="backups/fati-traductions-production-$(date +%Y%m%d-%H%M%S)"
ssh hostinger "mkdir -p ~/$DOSSIER/tools"
rsync -az --exclude=".DS_Store" --exclude="__pycache__" -e ssh \
  tools/traductions tools/configurer-polylang.php tools/importer-traductions.php hostinger:"$DOSSIER/tools/"

ssh hostinger bash -s "$RACINE" "$DOSSIER" "$MODE" <<'DISTANT'
set -e
cd "$HOME/$1"
D="$HOME/$2"
wp() { command wp "$@" </dev/null; }

echo "→ État en ligne : Polylang $(wp plugin get polylang --field=version), langues $(wp eval 'echo implode(" ", pll_languages_list());')"

if [ "$3" != "--go" ]; then
  echo "→ SIMULATION — rien n'est écrit"
  for l in es en; do
    wp eval-file "$D/tools/importer-traductions.php" "$l" simulation 2>&1 | grep -v '^   [~+] \|(simulation)$'
  done
  echo "Pour publier : bash tools/publier-traductions-production.sh --go (après feu vert)"
  exit 0
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

echo "→ Paquets de langue du cœur (espagnol, anglais britannique)"
wp language core install es_ES en_GB >/dev/null 2>&1 || true
wp language core list --status=installed --field=language | tr '\n' ' '; echo

echo "→ Réglages Polylang (langues es / en, détection du navigateur sur l'accueil)"
wp eval-file "$D/tools/configurer-polylang.php"

for l in es en; do
  echo "→ Contenus « $l »"
  wp eval-file "$D/tools/importer-traductions.php" "$l" | grep -v '^   [~+] '
done

wp transient delete pll_languages_list >/dev/null 2>&1 || true
wp cache flush >/dev/null
echo "✔ Versions espagnole et anglaise publiées. Restauration si besoin :"
echo "   gunzip -c $SAUVEGARDE | wp db import -"
DISTANT
