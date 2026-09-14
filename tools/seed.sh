#!/usr/bin/env bash
#
# Import initial du catalogue et des contenus de la maquette v3.
# Exécuter une fois, depuis la racine du projet, WordPress déjà installé.
#
#   bash tools/seed.sh
#
# Idempotent : relancer ne crée pas de doublon (recherche par slug).

set -euo pipefail

cd "$(dirname "$0")/.."
ROOT="$(pwd)"
MEDIAS="$ROOT/tools/medias"

command -v wp >/dev/null || { echo "WP-CLI introuvable. https://wp-cli.org/#installing"; exit 1; }
wp core is-installed >/dev/null 2>&1 || { echo "WordPress n'est pas encore installé dans ce dossier."; exit 1; }

echo "→ Activation du thème"
wp theme activate fatichanelya

echo "→ Permaliens"
wp rewrite structure '/%postname%/' --hard >/dev/null
wp rewrite flush --hard >/dev/null

# ---------------------------------------------------------------- catégories
# Slug d'un nom de catégorie, identique à sanitize_title() côté WordPress.
fati_slug() {
  python3 -c "
import sys, re, unicodedata
s = unicodedata.normalize('NFKD', sys.argv[1]).encode('ascii','ignore').decode()
s = re.sub(r'[^a-zA-Z0-9]+', '-', s).strip('-').lower()
print(s)
" "$1"
}

echo "→ Catégories de produit"
# Lecture par python : le CSV contient des virgules à l'intérieur des guillemets.
python3 -c "
import csv, sys
vus = []
for r in csv.DictReader(open('tools/produits.csv', encoding='utf-8')):
    c = r['categorie'].strip()
    if c and c not in vus:
        vus.append(c)
print('\n'.join(vus))
" | while IFS= read -r cat; do
  [ -z "$cat" ] && continue
  # Comparaison par slug : WordPress échappe « & » en « &amp; » dans le nom,
  # et `wp term get --by=name` n'existe pas.
  slug_cat=$(fati_slug "$cat")
  if wp term list categorie_produit --field=slug | grep -qxF "$slug_cat"; then
    echo "   = $cat"
  else
    wp term create categorie_produit "$cat" --slug="$slug_cat" >/dev/null && echo "   + $cat"
  fi
done

# Écrit une méta sans échouer quand la valeur est déjà la bonne.
# `wp post meta update` renvoie une erreur dans ce cas, ce qui tuerait `set -e`.
fati_meta() {
  local id="$1" key="$2" value="$3" current
  current=$(wp post meta get "$id" "$key" 2>/dev/null)
  [ "$current" = "$value" ] && return 0
  # « 40.50 » est relu « 40.5 » : comparer aussi en valeur numérique.
  if [ -n "$current" ] && awk -v a="$current" -v b="$value" \
      'BEGIN{exit !(a+0==b+0 && a ~ /^[0-9.]+$/ && b ~ /^[0-9.]+$/)}'; then
    return 0
  fi
  wp post meta update "$id" "$key" "$value" >/dev/null || true
}

# ---------------------------------------------------------------- médias
import_media() {
  local file="$1" title="$2"
  local existing
  existing=$(wp post list --post_type=attachment --name="$(basename "${file%.*}")" --field=ID --posts_per_page=1 2>/dev/null || true)
  if [ -n "$existing" ]; then echo "$existing"; return; fi
  wp media import "$file" --title="$title" --porcelain
}

echo "→ Visuels des pages"
# Pas de tableau associatif : bash 3.2 (macOS) ne les connaît pas.
for f in "$MEDIAS"/pages/*.webp; do
  [ -e "$f" ] || continue
  key=$(basename "$f" .webp)
  import_media "$f" "$key" >/dev/null
done

# ---------------------------------------------------------------- produits
echo "→ Produits"
order=0
tail -n +2 tools/produits.csv | while IFS= read -r line; do
  [ -z "$line" ] && continue
  slug=$(echo "$line" | python3 -c "import sys,csv;print(next(csv.reader(sys.stdin))[0])")
  nom=$(echo "$line"  | python3 -c "import sys,csv;print(next(csv.reader(sys.stdin))[1])")
  cat=$(echo "$line"  | python3 -c "import sys,csv;print(next(csv.reader(sys.stdin))[2])")
  prix=$(echo "$line" | python3 -c "import sys,csv;print(next(csv.reader(sys.stdin))[3])")
  img=$(echo "$line"  | python3 -c "import sys,csv;print(next(csv.reader(sys.stdin))[4])")
  desc=$(echo "$line" | python3 -c "import sys,csv;print(next(csv.reader(sys.stdin))[5])")

  order=$((order + 10))

  id=$(wp post list --post_type=produit --name="$slug" --field=ID --posts_per_page=1)
  if [ -z "$id" ]; then
    id=$(wp post create --post_type=produit --post_status=publish --porcelain \
         --post_title="$nom" --post_name="$slug" --post_excerpt="$desc" \
         --post_content="$desc" --menu_order="$order")
    echo "   + $nom"
  else
    wp post update "$id" --post_title="$nom" --post_excerpt="$desc" --menu_order="$order" >/dev/null
    echo "   ~ $nom"
  fi

  fati_meta "$id" _fati_prix "$prix"
  wp post term set "$id" categorie_produit "$(fati_slug "$cat")" --by=slug >/dev/null

  # Les visuels normalisés priment : même échelle et même fond pour toute la
  # grille (voir tools/normaliser-visuels.py). On retombe sur les originaux
  # si la normalisation n'a pas encore été lancée.
  src="$MEDIAS/produits-normalises/$img"
  [ -f "$src" ] || src="$MEDIAS/produits/$img"

  if [ -n "$img" ] && [ -f "$src" ]; then
    att=$(import_media "$src" "$nom")
    fati_meta "$id" _thumbnail_id "$att"
    wp post meta delete "$id" _fati_indispo >/dev/null 2>&1 || true
  else
    fati_meta "$id" _fati_indispo 1
  fi
done

# ---------------------------------------------------------------- formations
echo "→ Formations"
order=0
tail -n +2 tools/formations.csv | while IFS= read -r line; do
  [ -z "$line" ] && continue
  slug=$(echo "$line"   | python3 -c "import sys,csv;print(next(csv.reader(sys.stdin))[0])")
  nom=$(echo "$line"    | python3 -c "import sys,csv;print(next(csv.reader(sys.stdin))[1])")
  niveau=$(echo "$line" | python3 -c "import sys,csv;print(next(csv.reader(sys.stdin))[2])")
  duree=$(echo "$line"  | python3 -c "import sys,csv;print(next(csv.reader(sys.stdin))[3])")
  desc=$(echo "$line"   | python3 -c "import sys,csv;print(next(csv.reader(sys.stdin))[4])")

  order=$((order + 10))

  id=$(wp post list --post_type=formation --name="$slug" --field=ID --posts_per_page=1)
  if [ -z "$id" ]; then
    id=$(wp post create --post_type=formation --post_status=publish --porcelain \
         --post_title="$nom" --post_name="$slug" --post_excerpt="$desc" \
         --post_content="$desc" --menu_order="$order")
    echo "   + $nom"
  fi
  fati_meta "$id" _fati_niveau "$niveau"
  if [ -n "$duree" ]; then fati_meta "$id" _fati_duree "$duree"; fi
done

# ---------------------------------------------------------------- pages
echo "→ Pages"
create_page() {
  local title="$1" slug="$2"
  local id
  id=$(wp post list --post_type=page --name="$slug" --field=ID --posts_per_page=1)
  if [ -z "$id" ]; then
    # Contenu volontairement vide : le texte réel est publié par
    # tools/publier-pages-legales.py depuis _velix/copy-legal.md.
    id=$(wp post create --post_type=page --post_status=publish --porcelain \
         --post_title="$title" --post_name="$slug" \
         --post_content="")
  fi
  echo "$id"
}

HOME_ID=$(create_page "Accueil" "accueil")
wp option update show_on_front page >/dev/null
wp option update page_on_front "$HOME_ID" >/dev/null

LEGAL_ID=$(create_page "Mentions légales" "mentions-legales")
PRIV_ID=$(create_page "Politique de confidentialité" "confidentialite")
CGV_ID=$(create_page "Conditions générales de vente" "conditions-generales-de-vente")
SHIP_ID=$(create_page "Livraison et retours" "livraison-retours")

# ---------------------------------------------------------------- menus
echo "→ Menus"
build_menu() {
  local name="$1" location="$2"; shift 2
  # `wp menu list` ne connaît que --fields ; on extrait la colonne nom.
  wp menu list --fields=name --format=csv 2>/dev/null | tail -n +2 | tr -d '"' \
    | grep -qxF "$name" || wp menu create "$name" >/dev/null
  wp menu location assign "$name" "$location" >/dev/null
  for item in "$@"; do
    IFS='|' read -r label target <<< "$item"
    # `wp menu item list` ne connaît que --fields ; on extrait la colonne titre.
    # WordPress échappe « & » en « &#038; » : on décode avant de comparer,
    # sinon « Livraison & retours » est réajouté à chaque exécution.
    if wp menu item list "$name" --fields=title --format=csv 2>/dev/null \
       | tail -n +2 | tr -d '"' | sed 's/&#038;/\&/g' | grep -qxF "$label"; then
      continue
    fi
    if [[ "$target" =~ ^[0-9]+$ ]]; then
      wp menu item add-post "$name" "$target" --title="$label" >/dev/null
    else
      wp menu item add-custom "$name" "$label" "$target" >/dev/null
    fi
  done
}

build_menu "Principal" principal \
  "Boutique|/#shop" "Formations|/#training" "À propos|/#about" "FAQ|/#faq"

build_menu "Pied — Navigation" pied_nav \
  "Boutique|/#shop" "Formations|/#training" "À propos|/#about"

build_menu "Pied — Informations" pied_infos \
  "FAQ|/#faq" "Livraison et retours|$SHIP_ID" "Conditions générales de vente|$CGV_ID" \
  "Mentions légales|$LEGAL_ID" "Politique de confidentialité|$PRIV_ID"

# ---------------------------------------------------------------- réglages
echo "→ Réglages du site"
wp option update blogname "Fatichanelya" >/dev/null
wp option update blogdescription "Bien-être, compétences et inspiration au quotidien." >/dev/null

if [ -f "$MEDIAS/pages/hero-fati.webp" ]; then
  python3 - "$ROOT" <<'PY'
import subprocess, sys, json
def url(slug):
    out = subprocess.run(['wp','post','list','--post_type=attachment',f'--name={slug}','--field=ID','--posts_per_page=1'],
                         capture_output=True, text=True).stdout.strip()
    if not out: return ''
    return subprocess.run(['wp','post','get',out,'--field=guid'], capture_output=True, text=True).stdout.strip()

current = subprocess.run(['wp','option','get','fati_settings','--format=json'], capture_output=True, text=True).stdout.strip()
settings = json.loads(current) if current.startswith('{') else {}
settings.update({
    'hero_image':     url('hero-fati'),
    'training_image': url('training-session'),
    'about_image':    url('about-main'),
    'about_avatar':   url('about-avatar'),
})
subprocess.run(['wp','option','update','fati_settings','--format=json'], input=json.dumps(settings), text=True, check=True)
print('   visuels des sections reliés')
PY
fi

echo
echo "✓ Import terminé."
echo "  Accueil : $(wp option get home)"
echo "  Reste à faire : renseigner le numéro WhatsApp dans Contenus du site > Général."
