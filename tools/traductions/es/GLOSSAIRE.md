# Version espagnole de fatichanelya.com — règles de traduction

Référence partagée par tous les fichiers de `tools/traductions/es/`. Le français du site est la
source ; l'espagnol en est la traduction fidèle, jamais une réécriture. **On ne traduit jamais depuis
l'arabe ni depuis l'anglais.**

Décisions validées par Sofian le 17 nov. 2026 : espagnol d'Espagne, lecteur tutoyé, Polylang gratuit
(archives en `/es/produits/`, `/es/formations/`), formations données en arabe uniquement.

## Registre
- **Espagnol d'Espagne** (castellano peninsular) : móvil, ordenador, gastos de envío, cesta, pedido,
  vale. Aucune tournure d'Amérique latine (celular, computadora, ordenar au sens de commander, carrito
  de compras, tomar un curso…).
- Le lecteur est **tutoyé** (« descubre », « añade », « escríbeme », « tu cesta »). Le site parle à une
  personne : jamais « vosotros », jamais « usted » hors pages légales.
- **Pages légales** : formulation impersonnelle ou en *usted* implicite (« dispone de 14 días », « el
  cliente »), comme un texte contractuel espagnol.
- **Genre du lecteur neutre.** Le français écrit parfois au féminin (« être guidée », « en étant
  accompagnée ») : on reformule sans adjectif genré (« cuando necesites orientación », « se avanza mejor
  con alguien al lado »). Jamais « guiado/a », « guiad@ » ni « guiadx ».
- **Fati parle à la première personne au féminin** là où le français le fait : « soy emprendedora »,
  « te respondo yo misma ».
- Ton : cercano, claro, sin exageración. Phrases courtes. Pas de superlatifs ajoutés.
- Rien n'est ajouté, rien n'est retiré : mêmes phrases, mêmes réserves.

## Aucune allégation santé
Règlement (CE) 1924/2006 et, en Espagne, Real Decreto 1907/1996 (publicité des produits à prétendue
finalité sanitaire). La version espagnole est la plus exposée : clientes et autorités espagnoles.

- On traduit ce que le français dit, et le français ne promet rien. Phrase ambiguë : la traduire au plus
  près, sans l'améliorer.
- **Mots interdits** dans les produits, catégories, réglages et chaînes (contrôle automatique) : curar,
  cura, sanar, previene/prevenir, inmunidad, inmunitario, defensas, depurativo, depurar, detox,
  desintoxicar, adelgazar, quemagrasas, quemar grasa, pérdida de peso, diabetes, colesterol, tensión
  arterial, presión arterial, antiedad, antienvejecimiento, antioxidante, antiinflamatorio, refuerza,
  fortalece, estimula, potencia, milagro, sin efectos secundarios, beneficios para la salud, bueno para
  la salud. « Tratamiento » seulement dans la phrase de la FAQ qui le **nie** (« ¿Sustituyen los
  complementos a un tratamiento? No. »).
- Le **catalogue DXN Espagne** (`tools/catalogue-dxn-2026-02.csv`, colonne `description_es`) sert pour
  les noms et les formats (« 20 sobres × 4,5 g »), **jamais pour ses phrases**, qui ne sont pas
  contrôlées.
- Terme réglementaire : **complementos alimenticios** (jamais « suplementos dietéticos »).

## Noms propres et marques (inchangés)
Fatichanelya, Fati, DXN, Kallow, Velix Digital, WhatsApp, TikTok, Instagram, Facebook, Google, Meta,
Ganozhi, Zhicare, Morinzhi, Reishilium, Ganocelium, Spirudle, codes RG / GL / SPF 50+.
Noms latins des espèces en latin, précédés du nom courant quand il existe : « reishi (Ganoderma
lucidum) », « melena de león (Hericium erinaceus) », « cordyceps (Cordyceps sinensis) », « noni
(Morinda citrifolia) », « hibisco (Hibiscus sabdariffa) », « espirulina (Spirulina platensis) »,
« árbol del té (Melaleuca alternifolia) ».

## Noms de produits et adresses
- **Titre** = le nom officiel, comme en français (c'est le nom anglais du catalogue DXN Espagne et de
  l'emballage) : « DXN Lingzhi Black Coffee ». Seul le suffixe se traduit : « — 120 comprimés » →
  « — 120 comprimidos », « — 10 monodoses » → « — 10 monodosis », « — 10 échantillons » →
  « — 10 muestras ».
- **Slug** (clé `slug`, obligatoire) : descripteur espagnol court + nom propre + marque, minuscules
  ASCII, sans article ni accent : `cafe-negro-lingzhi-dxn`, `espirulina-dxn-120-comprimidos`,
  `gel-de-ducha-kallow`. Unique dans tout le site (le contrôle `verifier.py` le vérifie) : Polylang
  gratuit refuse deux fois la même adresse et WordPress ajouterait « -2 ».
- Le descripteur espagnol (« café negro con reishi ») vit dans l'accroche et la description : c'est ce que
  cherche une cliente espagnole.

## Vocabulaire du site
| Français | Espagnol |
| --- | --- |
| Boutique | Tienda |
| Catalogue | Catálogo |
| Sélection (la sélection Fatichanelya) | Selección |
| Produit / produits | Producto / productos |
| Panier | Cesta |
| Ajouter au panier | Añadir a la cesta |
| Ajouter | Añadir |
| Commander | Pedir (bouton) / hacer un pedido |
| Commande | Pedido |
| Prix indicatif | Precio orientativo |
| Total indicatif | Total orientativo |
| Sur demande (prix) | A consultar |
| Hors livraison | Envío no incluido |
| Livraison et retours | Envíos y devoluciones |
| Frais de port | Gastos de envío |
| Disponibilité | Disponibilidad |
| Formation / formations | Curso / cursos |
| Programme (de formation) | Programa |
| À distance | Online |
| Séance | Sesión |
| Niveau | Nivel |
| Durée | Duración |
| Langue | Idioma |
| Arabe (langue de la formation) | Árabe / en árabe |
| Sans prérequis | Sin requisitos previos |
| À votre rythme | A tu ritmo |
| Compléments alimentaires | Complementos alimenticios |
| Alimentation & boissons | Alimentación y bebidas |
| Beauté & soin | Belleza y cuidado |
| Compléments | Complementos |
| Soin personnel | Cuidado personal |
| Contenance / format | Formato |
| Composition | Ingredientes |
| Conseils d'utilisation | Modo de empleo |
| Points clés | Puntos clave |
| Mention santé | Aviso de salud |
| Professionnel de santé | Profesional sanitario |
| Entrepreneure · Créatrice · Mentore | Emprendedora · Creadora · Mentora |
| Bien-être naturel | Bienestar natural |
| Échange direct | Trato directo |
| Sans engagement | Sin compromiso |
| Réponse le jour même, au plus tard sous 48 h | Respuesta en el mismo día y, como máximo, en 48 h |
| Aucun conseil médical | Ningún consejo médico |
| Accueil | Inicio |
| À propos | Sobre Fati |
| Contact | Contacto |
| Questions fréquentes | Preguntas frecuentes |
| Mentions légales | Aviso legal |
| Politique de confidentialité | Política de privacidad |
| Conditions générales de vente | Condiciones generales de venta |
| Écrire sur WhatsApp | Escríbeme por WhatsApp |

## Chiffres, unités, ponctuation
- Décimale à **virgule**, point des milliers : « 26,40 € », « 4,5 g », « 1.000 ml ». Espace insécable
  avant « € » et les unités.
- Unités : g, mg, ml, l, % ; comprimido(s), cápsula(s), cápsula(s) blanda(s), sobre(s), tubo, frasco,
  tarro, caja, monodosis, muestra(s). Le « × » se garde : « 20 sobres × 4,5 g ».
- **¿…?** et **¡…!** toujours ouverts. Guillemets « » pour citer (usage RAE).
- Durées : « 48 h », « 14 días », « 12 meses, 2 sesiones por semana ».
- Casse de phrase dans les titres (« Preguntas frecuentes »), jamais de majuscule à chaque mot. Mois et
  jours en minuscules.
- Les astérisques `*…*` des titres (segment accentué, souvent seul sur sa ligne) se gardent autour du
  groupe de mots équivalent, ponctuation finale dehors comme en français.

## Formats de fichiers
- JSON : UTF-8, clés = slug français, retours à la ligne « \n » exactement comme la source (une étape de
  mode d'emploi par ligne, un point clé par ligne, une question de FAQ par ligne).
- Lignes « Titre | texte » (réglages trust, pledge, faq, about_valeurs) : même nombre de lignes, une
  seule barre verticale par ligne.
- Markdown réduit des formations : même structure que la source, ligne pour ligne.
- Pages légales : même HTML que la source (mêmes balises, attributs, ordre), seuls les textes changent.
