# Versions traduites — fatichanelya.com

Thème 1.9.0, mu-plugin 1.2.0 (17 novembre 2026). Le site existe en quatre langues : **français**
(langue par défaut, sans préfixe), **arabe** (`/ar/`, de droite à gauche, depuis le 27 oct. 2026),
**espagnol** (`/es/`, espagnol d'Espagne) et **anglais** (`/en/`, anglais britannique). Ce document
dit comment c'est monté et comment on le fait vivre. Il remplace `traduction-arabe.md`.

## Décisions du gérant (17 nov. 2026)

- **Plugins gratuits seulement** : Polylang gratuit. Conséquence assumée : les archives gardent leur
  slug français sous le préfixe (`/es/produits/`, `/en/formations/`, `/es/categorie-produit/…`), et
  deux contenus ne peuvent pas partager une adresse — chaque traduction a son slug, fixé dans les
  sources (`slug`), contrôlé par `tools/traductions/verifier.py`.
- **Espagnol d'Espagne, lecteur tutoyé** ; pages légales en registre contractuel (usted implicite).
- **Anglais britannique** (basket, programme, catalogue) ; prix au format anglais « €26.40 ».
- **Les formations sont données en arabe uniquement** : chaque fiche affiche « Langue : Arabe » et les
  cartes « En arabe », dans les quatre langues (`fati_formation_langue()` dans `inc/helpers.php`, une
  seule source à changer le jour où une formation change de langue).
- **Langue du visiteur** : l'accueil suit la langue du navigateur ; les pages intérieures ne sont
  jamais redirigées, une carte propose la bonne version (voir plus bas).
- **Pages légales** : l'espagnol est une version de plein droit (pas de note « pour information ») ;
  l'arabe et l'anglais portent la note « traduction pour information, la version française fait
  foi ». Laquelle des versions française ou espagnole prévaut : question ouverte pour l'abogado.

## Ce qui est en place

| Élément | Où | Comment ça marche |
| --- | --- | --- |
| Multilingue | Polylang 3.9 (gratuit) | `fr` (défaut), `ar` (RTL), `es` (es_ES), `en` (en_GB), dans cet ordre dans le menu ; configuré par `tools/configurer-polylang.php` ; médias non traduits ; vignette et ordre synchronisés ; prix, référence et drapeau « visuel à venir » copiés par le mu-plugin |
| Chaînes du thème | `languages/ar.po`, `es_ES.po`, `en_GB.po` (+ `.mo`) | 230 chaînes par langue, générées par `tools/traductions/<langue>/chaines.py` (moteur commun `tools/traductions/po.py`) ; l'espagnol et l'anglais s'arrêtent si une chaîne publique de l'arabe leur manque |
| Réglages « Contenus du site » | Polylang → Traductions de chaînes | 23 chaînes par langue, `tools/traductions/<langue>/reglages.json` |
| Pages à gabarit | Accueil, À propos, Contact, Panier | le gabarit suit l'original français, quel que soit le slug (`sobre-fati`, `about-fati`, `contacto`, `contact-fati`, `cesta`, `basket`) |
| Pages légales | 4 par langue | même HTML que le français, seuls les textes changent ; slug traduit dans la source (`**Slug traduit**`) |
| Formations | 6 | titre, accroche, niveau, durée, programme (Markdown réduit → blocs) |
| Produits | 103 | nom (le nom officiel DXN / Kallow, seul le suffixe se traduit), accroche, description, contenance, composition, mode d'emploi, points clés ; même visuel, même prix |
| Catégories | 4 | noms traduits, même visuel |
| Menus | « — AR », « — ES », « — EN » | reconstruits par l'importateur, affectés par langue (sans toucher aux autres langues) |
| Sélecteur | en-tête et pied | `langue.php`, une langue apparaît dès qu'elle a du contenu |
| SEO | Polylang + mu-plugin | `hreflang` des quatre langues sur chaque page, `x-default` sur l'accueil, canonique et JSON-LD dans la langue, `og:locale` |
| Panier | partagé entre les langues | identifiants de toutes les traductions (`ids`) |

## Langue du visiteur (1.9.0)

1. **Accueil** (`fatichanelya.com/`) : détection native de Polylang (`browser`). Un navigateur en
   espagnol va sur `/es/`, en anglais sur `/en/`, en arabe sur `/ar/` ; en français il reste. Une
   langue que le site n'a pas (allemand, italien…) reçoit l'anglais (filtre `pll_preferred_language`
   du mu-plugin). Sans langue de navigateur (robots), l'accueil reste français.
2. **Le dernier choix gagne** : le cookie `pll_language` (un an, posé par Polylang) passe avant le
   navigateur ; un clic interne vers l'accueil ne redirige pas.
3. **Pages intérieures** : jamais redirigées (lien partagé, Google). Une carte bleu nuit propose la même
   page dans la langue du visiteur si elle existe — texte écrit dans la langue proposée, « plus tard »
   mémorisé (`localStorage`, clé `fati-langue`), le choix fait dans le menu de langues aussi
   (composant `langue-suggestion.php`, script en fin de `main.js`).
4. **Caches** : la racine répond `Vary: Accept-Language, Cookie`. Le CDN Hostinger sert aujourd'hui le
   HTML sans le garder (DYNAMIC) ; **si le cache HTML du CDN est un jour activé, exclure l'accueil**,
   sinon la redirection d'un visiteur serait servie aux suivants.

Le cookie de langue et le stockage local sont décrits dans la section Cookies de la politique de
confidentialité (quatre langues), comme strictement nécessaires.

## Faire vivre les traductions

**Règles de traduction** : `tools/traductions/<langue>/GLOSSAIRE.md` (registre, vocabulaire imposé,
noms propres, slugs, chiffres, mots interdits). On traduit **toujours depuis le français**.

**Un texte du thème change** (bouton, libellé) : ajouter la chaîne aux dictionnaires des trois langues
(`tools/traductions/ar|es|en/chaines.py`), puis :

```bash
for l in ar es en; do python3 tools/traductions/$l/chaines.py; done
for f in ar es_ES en_GB; do wp i18n make-mo wp-content/themes/fatichanelya/languages/$f.po; done
```

Les `.mo` partent avec le thème (`tools/deploy-production.sh`).

**Un réglage « Contenus du site » change** : une chaîne française modifiée devient une nouvelle
chaîne, à retraduire dans les trois langues (`reglages.json`, ou Polylang → Traductions de chaînes).
En production, `tools/importer-reglages-ar.php tools/traductions/<langue>/reglages.json` ne réécrit que
les chaînes (la langue est celle du dossier).

**Un produit, une formation, une page change ou s'ajoute** : traduire dans le JSON ou le Markdown de
chaque langue, puis contrôler et importer :

```bash
python3 tools/traductions/verifier.py es --complet                 # bloquant
wp eval-file tools/importer-traductions.php es simulation          # dit ce qui changerait
wp eval-file tools/importer-traductions.php es                     # importe (idempotent)
bash tools/publier-traductions-production.sh                       # production : simulation
bash tools/publier-traductions-production.sh --go                  # production, après feu vert
```

Une traduction existante garde son adresse (liens déjà partagés) : changer un slug se fait à la main,
avec une redirection.

**Une page légale change** : modifier `_velix/copy-legal.md`, puis les quatre traductions
(`tools/traductions/<langue>/legal/`). Français et arabe en ligne : `tools/publier-pages-legales-en-ligne.sh`
; espagnol et anglais : l'importateur.

## Contrôles

- `tools/traductions/verifier.py <es|en> [--complet] [--seul fichier|formations|legal]` : clés, chiffres
  (décimales comparées sans séparateur), lignes, barres « | », astérisques, slugs uniques et jamais
  français, mots interdits (allégations santé ; les mises en garde reprises du français sont admises),
  restes de français, balises des pages légales.
- Recette : `_velix/outils/recette/` — `pages-fati-es.json`, `pages-fati-en.json` pour `shoot.mjs`,
  `langue-suggestion.mjs` pour la redirection de l'accueil et la carte de suggestion.

## Typographie

- **Latin** (français, espagnol, anglais) : Poppins, sous-ensemble latin (couvre ñ, ¿, ¡, accents).
- **Arabe** : Alexandria (titres, prix, boutons) + IBM Plex Sans Arabic (texte), chargées seulement en
  présence d'arabe ; détail dans `design-system.md` (1.7.0).

## Limites connues

- Polylang gratuit : pas de traduction des slugs d'archive, pas d'adresse partagée entre langues, pas de
  duplication de contenu depuis l'admin ; l'administration reste en français.
- Une fiche ajoutée en français n'existe pas dans les autres langues tant qu'elle n'est pas traduite :
  la boutique ES / EN compte alors un produit de moins.
