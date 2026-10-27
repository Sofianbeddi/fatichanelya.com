# Version arabe — fatichanelya.com

Thème 1.7.0, mu-plugin 1.1.0 (27 octobre 2026). Le site existe en français (langue par défaut, sans
préfixe) et en arabe (`/ar/`, écriture de droite à gauche). Ce document dit comment c'est monté et
comment on le fait vivre.

## Ce qui est en place

| Élément | Où | Comment ça marche |
| --- | --- | --- |
| Multilingue | Polylang 3.9 (gratuit) | langues `fr` (défaut) et `ar` (RTL) ; adresses `/ar/…` ; aucune redirection selon le navigateur ; médias non traduits ; vignette et ordre synchronisés entre traductions ; prix, référence et drapeau « visuel à venir » copiés par le mu-plugin (`pll_copy_post_metas`) |
| Chaînes du thème | `wp-content/themes/fatichanelya/languages/ar.po` → `ar.mo` | 225 chaînes (boutons, libellés, messages WhatsApp, pied, panier…) ; les libellés d'administration restent en français |
| Réglages « Contenus du site » | Polylang → Traductions de chaînes, groupe Fatichanelya | 23 chaînes (hero, bandeau, engagements, FAQ, contact, mention santé, slogan), posées par `tools/traductions/ar/reglages.json` |
| Pages à gabarit | Accueil, À propos, Contact, Panier | traductions liées à l'original ; **le gabarit suit l'original** (filtre `page_template` du thème), quel que soit le slug |
| Pages légales | 4 pages | HTML traduit à structure identique, note « للاطلاع فقط » en tête : la version française fait foi ; marqueurs `[[À FOURNIR]]` conservés en français |
| Formations | 6 | titre, accroche, niveau, durée, programme (Markdown réduit → blocs) |
| Produits | 103 | nom, accroche, description, contenance, composition, mode d'emploi, points clés ; catégorie arabe ; même visuel, même prix |
| Catégories | 4 | noms arabes, même visuel (`_fati_visuel` copié) |
| Menus | 3 menus « — AR » | reconstruits par l'importateur, affectés par langue dans Polylang |
| Sélecteur | en-tête (FR / AR) et pied | `fati_languages()` |
| SEO | `hreflang` fr / ar (Polylang), canonique et JSON-LD dans la langue (`pll_home_url_white_list`), `og:locale` |
| Panier | partagé entre les langues | chaque produit expose les identifiants de ses traductions (`ids`) ; un panier rempli en français se relit en arabe |

Adresses : les slugs arabes sont en arabe (encodés en pourcentage dans l'adresse). Les archives gardent
leur slug français sous le préfixe : `/ar/produits/`, `/ar/formations/`, `/ar/categorie-produit/…`.

## Typographie arabe

- **Alexandria** (variable 300–800, 31 Ko) : titres, prix, boutons, sur-titres, navigation.
- **IBM Plex Sans Arabic** (400 et 600, 34 + 36 Ko) : texte courant.
- Chargées seulement si la page contient des glyphes arabes (`unicode-range`) ; Poppins garde le latin,
  les chiffres et la marque. Aucun interlettrage en arabe ; interlignes 1,8 / 1,4.
- Prix de gauche à droite, isolés (`26,40 €`), chiffres occidentaux, virgule décimale dans toutes les
  langues (`fati_format_prix`, `ar-MA` côté JavaScript).
- Comparaison faite avec Almarai, Readex Pro et Cairo : Plex est la plus régulière et la plus lisible en
  petit corps, Alexandria la plus proche de Poppins pour les titres.

## Faire vivre la version arabe

**Un texte du thème change** (bouton, libellé) : modifier `languages/ar.po` (ou le dictionnaire de
`_velix/outils/recette/../chaines.py` si on régénère), puis `wp i18n make-mo wp-content/themes/fatichanelya/languages/ar.po`.
Le `.mo` part avec le thème (`tools/deploy-preversion.sh`).

**Un réglage « Contenus du site » change** : Polylang → Langues → Traductions de chaînes (groupe
Fatichanelya) depuis l'admin, ou `tools/traductions/ar/reglages.json` puis l'importateur. Attention :
une chaîne française modifiée dans « Contenus du site » devient une nouvelle chaîne, à retraduire.

**Un produit, une formation, une page change ou s'ajoute** : traduire dans le JSON ou le Markdown
correspondant de `tools/traductions/ar/` (règles dans `GLOSSAIRE.md`), puis :

```bash
wp eval-file tools/importer-traductions.php           # local (idempotent)
bash tools/publier-traductions-preversion.sh          # préversion (installe Polylang si besoin)
```

Un prix modifié en français se propage à l'arabe à l'enregistrement (synchronisation Polylang) ; une
traduction peut aussi se créer à la main depuis l'admin (icône « + » de Polylang), le gabarit et le
visuel suivent.

**Une page légale change** : refaire la traduction dans `tools/traductions/ar/legal/<slug>.md` (même HTML,
textes traduits), puis l'importateur.

## Ordre de mise en ligne

1. `tools/deploy-preversion.sh` — thème (polices, `ar.mo`, CSS) et mu-plugin.
2. `tools/publier-traductions-preversion.sh` — Polylang, paquets de langue, réglages, contenus arabes.
3. Recette : `_velix/outils/recette/` avec `pages-fati-ar.json` (`BASE=… node shoot.mjs --pagesfile pages-fati-ar.json …`)
   et `panier-ar.mjs` (polices chargées, panier partagé, prix dans le tiroir).

## Choix de traduction (à faire valider par Fati)

- Arabe standard moderne, lecteur au singulier (forme neutre des interfaces : « أضف », « اكتشف ») ; Fati
  parle d'elle au féminin. Si Fati préfère s'adresser aux femmes (« أضيفي »), c'est un changement global
  des chaînes et des réglages, pas une retouche.
- Noms de produits : descripteur arabe + nom translittéré + « DXN » (« قهوة لينجزي السوداء DXN »), nom
  latin du catalogue cité une fois dans la fiche ; la recherche de la boutique trouve aussi le nom latin.
- « Formation » → « دورة تدريبية », « À propos » → « من هي فاتي », « Contact » → « اتصل بفاتي ».
- Pages légales : traduction pour information, le français fait foi (note en tête de chaque page).

## Limites connues

- Polylang gratuit : pas de traduction automatique des slugs d'archive (`/ar/produits/`), pas de
  duplication de contenu depuis l'admin ; l'administration reste en français.
- Les marqueurs `[[À FOURNIR]]` des pages légales apparaissent en français dans la version arabe tant que
  la cliente n'a pas fourni les données.
- L'espagnol suivra le même chemin : une langue `es` dans Polylang, un dossier `tools/traductions/es/`.
