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
  wp term get categorie_produit "$cat" --by=name >/dev/null 2>&1 \
    || { wp term create categorie_produit "$cat" >/dev/null; echo "   + $cat"; }
done

# ---------------------------------------------------------------- médias
import_media() {
  local file="$1" title="$2"
  local existing
  existing=$(wp post list --post_type=attachment --name="$(basename "${file%.*}")" --field=ID --posts_per_page=1 2>/dev/null || true)
  if [ -n "$existing" ]; then echo "$existing"; return; fi
  wp media import "$file" --title="$title" --porcelain
}

echo "→ Visuels des pages"
declare -A PAGE_MEDIA
for f in "$MEDIAS"/pages/*.webp; do
  [ -e "$f" ] || continue
  key=$(basename "$f" .webp)
  PAGE_MEDIA[$key]=$(import_media "$f" "$key")
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

  wp post meta update "$id" _fati_prix "$prix" >/dev/null
  wp post term set "$id" categorie_produit "$cat" --by=name >/dev/null

  if [ -n "$img" ] && [ -f "$MEDIAS/produits/$img" ]; then
    att=$(import_media "$MEDIAS/produits/$img" "$nom")
    wp post meta update "$id" _thumbnail_id "$att" >/dev/null
    wp post meta delete "$id" _fati_indispo >/dev/null 2>&1 || true
  else
    wp post meta update "$id" _fati_indispo 1 >/dev/null
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
  wp post meta update "$id" _fati_niveau "$niveau" >/dev/null
  [ -n "$duree" ] && wp post meta update "$id" _fati_duree "$duree" >/dev/null
done

# ---------------------------------------------------------------- pages
echo "→ Pages"
create_page() {
  local title="$1" slug="$2"
  local id
  id=$(wp post list --post_type=page --name="$slug" --field=ID --posts_per_page=1)
  if [ -z "$id" ]; then
    id=$(wp post create --post_type=page --post_status=publish --porcelain \
         --post_title="$title" --post_name="$slug" \
         --post_content="<p>Contenu à rédiger.</p>")
  fi
  echo "$id"
}

HOME_ID=$(create_page "Accueil" "accueil")
wp option update show_on_front page >/dev/null
wp option update page_on_front "$HOME_ID" >/dev/null

LEGAL_ID=$(create_page "Mentions légales" "mentions-legales")
PRIV_ID=$(create_page "Confidentialité" "confidentialite")
SHIP_ID=$(create_page "Livraison & retours" "livraison-retours")

# ---------------------------------------------------------------- menus
echo "→ Menus"
build_menu() {
  local name="$1" location="$2"; shift 2
  wp menu list --field=name | grep -qx "$name" || wp menu create "$name" >/dev/null
  wp menu location assign "$name" "$location" >/dev/null
  for item in "$@"; do
    IFS='|' read -r label target <<< "$item"
    wp menu item list "$name" --field=title 2>/dev/null | grep -qx "$label" && continue
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
  "FAQ|/#faq" "Mentions légales|$LEGAL_ID" "Confidentialité|$PRIV_ID" "Livraison & retours|$SHIP_ID"

# ---------------------------------------------------------------- réglages
echo "→ Réglages du site"
wp option update blogname "Fatichanelya" >/dev/null
wp option update blogdescription "Bien-être, compétences et inspiration au quotidien." >/dev/null

if [ -n "${PAGE_MEDIA[hero-fati]:-}" ]; then
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
