# Version anglaise de fatichanelya.com — règles de traduction

Référence partagée par tous les fichiers de `tools/traductions/en/`. Le français du site est la
source ; l'anglais en est la traduction fidèle, jamais une réécriture. **On ne traduit jamais depuis
l'arabe ni depuis l'espagnol.**

Décisions validées par Sofian le 17 nov. 2026 : anglais britannique (`en_GB`), Polylang gratuit
(archives en `/en/produits/`, `/en/formations/`), formations données en arabe uniquement.

## Registre
- **British English** : colour, programme, catalogue, organise, personalise, centre, favourite,
  basket, delivery, postcode. Jamais d'américanismes (cart, shipping fee, zip code, program, color).
- Le lecteur est « you », ton chaleureux et direct, sans emphase. Phrases courtes. Contractions
  naturelles permises dans l'interface (« I'll », « you're »), pas dans les pages légales.
- **Pages légales** : registre contractuel britannique, sans contractions.
- Genre du lecteur : neutre (l'anglais le permet naturellement).
- **Fati parle à la première personne** là où le français le fait (« I reply personally »).
- Rien n'est ajouté, rien n'est retiré : mêmes phrases, mêmes réserves.
- **Langues de réponse** : le site dit que Fati répond en darija, français ou espagnol. On le traduit tel
  quel (« Darija, French or Spanish: I reply in your language ») — c'est l'information exacte pour une
  lectrice anglophone.

## Aucune allégation santé
Règlement (CE) 1924/2006 : on traduit ce que le français dit, et le français ne promet rien. Phrase
ambiguë : la traduire au plus près, sans l'améliorer.

- **Mots interdits** dans les produits, catégories, réglages et chaînes (contrôle automatique) : cure,
  heal, prevent, immune, immunity, defences, detox, cleanse, purify, slimming, weight loss, fat-burning,
  diabetes, cholesterol, blood pressure, blood sugar, anti-ageing, anti-aging, antioxidant,
  anti-inflammatory, boost, strengthen, stimulate, miracle, no side effects, health benefits, good for
  your health. « Treatment » seulement dans la phrase de la FAQ qui le **nie** (« Do supplements
  replace a treatment? No. »).
- Terme réglementaire : **food supplements** (jamais « dietary supplements » ni « nutraceuticals »).

## Noms propres et marques (inchangés)
Fatichanelya, Fati, DXN, Kallow, Velix Digital, WhatsApp, TikTok, Instagram, Facebook, Google, Meta,
Ganozhi, Zhicare, Morinzhi, Reishilium, Ganocelium, Sprudle, codes RG / GL / SPF 50+.
Noms latins des espèces en latin, précédés du nom courant : « reishi (Ganoderma lucidum) », « lion's
mane (Hericium erinaceus) », « cordyceps (Cordyceps sinensis) », « noni (Morinda citrifolia) »,
« roselle (Hibiscus sabdariffa) », « spirulina (Spirulina platensis) », « tea tree (Melaleuca
alternifolia) ».

## Noms de produits et adresses
- **Titre** = le nom officiel, comme en français : « DXN Lingzhi Black Coffee ». Seul le suffixe se
  traduit : « — 120 comprimés » → « — 120 tablets », « — 10 monodoses » → « — 10 single-use sachets »,
  « — 10 échantillons » → « — 10 samples ».
- **Slug** (clé `slug`, obligatoire) : le titre anglais en minuscules ASCII (`dxn-lingzhi-black-coffee`,
  `dxn-spirulina-120-tablets`). Unique dans tout le site (contrôle `verifier.py`) : Polylang gratuit
  refuse deux fois la même adresse et WordPress ajouterait « -2 ». Le slug français est souvent déjà de
  l'anglais (`lingzhi-coffee`) : le slug anglais ne doit jamais le reprendre.

## Vocabulaire du site
| Français | Anglais |
| --- | --- |
| Boutique | Shop |
| Catalogue | Catalogue |
| Sélection (la sélection Fatichanelya) | Selection |
| Produit / produits | Product / products |
| Panier | Basket |
| Ajouter au panier | Add to basket |
| Ajouter | Add |
| Commander | Order |
| Commande | Order |
| Prix indicatif | Guide price |
| Total indicatif | Estimated total |
| Sur demande (prix) | On request |
| Hors livraison | Excluding delivery |
| Livraison et retours | Delivery & returns |
| Frais de port | Delivery costs |
| Disponibilité | Availability |
| Formation / formations | Course / courses |
| Programme (de formation) | Programme |
| À distance | Online |
| Séance | Session |
| Niveau | Level |
| Durée | Duration |
| Langue | Language |
| Arabe (langue de la formation) | Arabic / taught in Arabic |
| Sans prérequis | No prerequisites |
| À votre rythme | At your own pace |
| Compléments alimentaires | Food supplements |
| Alimentation & boissons | Food & drinks |
| Beauté & soin | Beauty & care |
| Compléments | Supplements |
| Soin personnel | Personal care |
| Contenance / format | Size |
| Composition | Ingredients |
| Conseils d'utilisation | How to use |
| Points clés | Key points |
| Mention santé | Health notice |
| Professionnel de santé | Healthcare professional |
| Entrepreneure · Créatrice · Mentore | Entrepreneur · Creator · Mentor |
| Bien-être naturel | Natural wellbeing |
| Échange direct | Direct contact |
| Sans engagement | No obligation |
| Réponse le jour même, au plus tard sous 48 h | Same-day reply, within 48 hours at the latest |
| Aucun conseil médical | No medical advice |
| Accueil | Home |
| À propos | About Fati |
| Contact | Contact |
| Questions fréquentes | Frequently asked questions |
| Mentions légales | Legal notice |
| Politique de confidentialité | Privacy policy |
| Conditions générales de vente | Terms and conditions of sale |
| Écrire sur WhatsApp | Message me on WhatsApp |

## Chiffres, unités, ponctuation
- Décimale à **point** partout en anglais, prix compris : « €26.40 », « 4.5 g », « 1,000 ml ». C'est
  le seul écart de format avec le français ; `fati_format_prix` et le JavaScript du panier le font
  pour les prix, les textes (contenances, mode d'emploi) l'écrivent eux-mêmes.
- Unités : g, mg, ml, l, % ; tablet(s), capsule(s), softgel(s), sachet(s), tube, bottle, jar, box,
  single-use sachet(s), sample(s). Le « × » se garde : « 20 sachets × 4.5 g ».
- Guillemets “ ” (et ‘ ’ à l'intérieur). Pas de virgule d'Oxford.
- Durées : « 48 hours » (« 48 h » dans les libellés courts), « 14 days ».
- Casse de phrase dans les titres (« Frequently asked questions »), jamais de Title Case.
- Les astérisques `*…*` des titres (segment accentué, souvent seul sur sa ligne) se gardent autour du
  groupe de mots équivalent, ponctuation finale dehors comme en français.

## Formats de fichiers
- JSON : UTF-8, clés = slug français, retours à la ligne « \n » exactement comme la source.
- Lignes « Titre | texte » (réglages trust, pledge, faq, about_valeurs) : même nombre de lignes, une
  seule barre verticale par ligne.
- Markdown réduit des formations : même structure que la source, ligne pour ligne.
- Pages légales : même HTML que la source (mêmes balises, attributs, ordre), seuls les textes changent.
