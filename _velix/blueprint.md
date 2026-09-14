# Blueprint UX — Fatichanelya

*Écrit le 14 septembre 2026 · Auteur : velix-ux · Entrée : `brief.md`, `architecture.md`, `reglementaire-espagne.md`, `journal.md` et le site en local*

Ce document décide **la stratégie de conversion d'abord, la mise en page ensuite**. Il ne réinvente
aucune URL : l'arborescence est figée en `architecture.md` section 5, et ce blueprint s'y range.
Il ne fabrique aucune preuve : le brief est formel, il n'existe **aucun témoignage, aucun chiffre,
aucune preuve sociale**. Chaque emplacement de preuve est donc créé vide, avec la règle d'affichage
qui le fait disparaître tant qu'il n'est pas rempli.

Il est écrit pour être suivi par `velix-da` (direction artistique) et `velix-front` (intégration).

---

## Sommaire

- [A. Stratégie de conversion](#a--stratégie-de-conversion)
- [B. Arborescence cible](#b--arborescence-cible)
- [C. Les pages légales](#c--les-pages-légales)
- [D. Wireframes page par page](#d--wireframes-page-par-page)
- [E. Ce qui manque pour convertir](#e--ce-qui-manque-pour-convertir)
- [F. Règles transverses](#f--règles-transverses-da--front)
- [G. Deux directions pour l'accueil](#g--deux-directions-pour-laccueil)
- [H. Ordre de travail](#h--ordre-de-travail)

---

# A · Stratégie de conversion

## A.1 Qui arrive, d'où, dans quel état d'esprit

Une seule source de trafic compte aujourd'hui, et elle commande tout le reste.

| | |
|---|---|
| **D'où** | TikTok, lien en bio. Secondairement : un lien envoyé en message privé par Fati elle-même. La recherche Google est un second temps (`architecture.md` §6), pas le cas à optimiser en premier. |
| **Sur quoi** | Mobile, en très grande majorité. 375 px est la largeur de référence, pas une dégradation. |
| **Dans quel état** | **En scroll, sans intention d'achat.** Elle ne cherchait rien. Elle regardait des vidéos, un visage lui a plu, elle a tapé sur le lien en bio par curiosité. Son attention est empruntée, pas acquise. |
| **Ce qu'elle sait déjà** | Le visage de Fati, sa voix, sa langue, son énergie. C'est un capital énorme et c'est **le seul** qu'on possède. |
| **Ce qu'elle ne sait pas** | Qu'il existe une boutique. Ce qui s'y vend. À quel prix. Si c'est sérieux. Si ça se livre chez elle. |
| **Ce qu'elle redoute** | Trois soupçons, dans cet ordre d'apparition : « encore du MLM », « encore une qui vend du rêve », « je vais me faire avoir sur un truc à 60 € ». |

**La conséquence la plus importante de ce profil :** la visiteuse n'arrive pas avec un problème à
résoudre. Elle arrive avec **une personne en tête**. Un site construit sur la logique classique
« problème → solution → preuve → offre » rate la cible, parce qu'elle n'a pas encore reconnu de
problème. La bonne logique ici est :

> **personne reconnue → ce qu'elle propose → pourquoi c'est honnête → une question posée sans engagement.**

C'est l'inverse d'un tunnel. Et c'est cohérent avec le concept validé en v3 : « du conseil avant la
pression d'acheter ».

## A.2 Où se perd la conversion aujourd'hui

Constat fait sur le site local, dans l'ordre du parcours réel d'une visiteuse mobile.

| # | Fuite | Ce qui se passe | Gravité |
|---|---|---|---|
| 1 | **Le hero ne dit pas qui parle** | Le titre est « Des choix simples pour prendre soin de vous ». Une phrase que n'importe quelle marque de compléments pourrait écrire. La visiteuse vient **pour Fati** et le premier écran ne lui rend pas Fati. Le seul capital dont on dispose n'est pas encaissé. | **Critique** |
| 2 | **Le portrait du hero est une image générée** | C'est déjà signalé comme risque n° 1 dans le brief. Sur une marque personnelle, alimentée par une audience qui connaît le vrai visage par cœur, l'écart se voit. L'argument central — « une vraie personne » — s'effondre à l'endroit exact où on le revendique. | **Critique** |
| 3 | **Deux CTA de même poids dans le hero** | « Découvrir les produits » et « Trouver ma formation » se partagent l'attention. Deux primaires = zéro primaire. Et la visiteuse TikTok ne sait pas encore lequel la concerne. | Forte |
| 4 | **19 produits d'un bloc sur l'accueil, en mobile** | `shop.php` charge tout le catalogue dans la page d'accueil (`posts_per_page => -1`). Sur 375 px, cela fait un mur de défilement qui enterre Formations, À propos, Promesse, FAQ et Contact **sous la grille**. Tout ce qui devait construire la confiance arrive après l'épreuve d'endurance. | **Critique** |
| 5 | **Le prix arrive avant la raison d'y croire** | La grille produits s'affiche avant la section « À propos » et avant les engagements détaillés. On demande d'accepter 61 € pour un pot avant d'avoir donné une seule raison de faire confiance. | Forte |
| 6 | **La livraison n'a pas de réponse** | La FAQ répond « les pays, délais et tarifs seront affichés avec le catalogue final ». Pour quelqu'un qui hésite à 60 €, c'est un non. `/livraison-retours/` existe en tant qu'URL mais la page est vide. | **Critique** |
| 7 | **Les pages légales disent « Contenu à rédiger »** | Trois pages publiées et vides. Sur un site qui vend des compléments alimentaires, c'est le signal « boutique improvisée » le plus lisible qui soit. Pire que l'absence de page. | **Critique** |
| 8 | **Le tiroir « Ma sélection » se termine sur un aveu** | « Le paiement en ligne n'est pas encore activé » est **honnête et il faut le garder** — mais aujourd'hui il tombe à la fin, comme une mauvaise nouvelle. Il n'est pas préparé, il est subi. | Moyenne |
| 9 | **Le numéro WhatsApp est vide** | `fati_opt('whatsapp')` est vide : l'unique appel à l'action du site ne fonctionne pas. Tout le reste est secondaire tant que ce point est ouvert. | **Bloquante** |
| 10 | **Les formations sont invendables en l'état** | Aucun prix, aucun format, aucun parcours, aucune preuve de légitimité. Une section entière qui demande de faire confiance sans donner de quoi. | Forte |
| 11 | **Aucun pont vers TikTok** | La seule preuve sociale réellement disponible (4 captures publiques, un compte actif) n'est pas exploitée. On coupe le lien avec l'endroit d'où vient la visiteuse. | Forte |
| 12 | **La newsletter est un formulaire mort** | Un champ sans service derrière, avec une note d'admin visible en front (« Inscription à brancher sur l'outil d'emailing »). Collecte des adresses qui se perdent, et c'est un problème RGPD autant qu'un problème de crédibilité. | Moyenne |

**La fuite structurante est la n° 4 combinée à la n° 1.** Le site fait aujourd'hui une seule page qui
essaie de tout faire, et l'oblige à mettre le catalogue en plein milieu. C'est exactement l'argument
du passage en multi-pages : **un accueil qui oriente convertit mieux qu'une page longue qui fait
tout** — à condition de ne pas éclater un petit site au point de le vider (voir B.2).

## A.3 La promesse centrale

Une phrase qu'une acheteuse répéterait à une amie :

> **« C'est la boutique de Fati — celle de TikTok. Tu lui écris avant d'acheter, elle te répond
> elle-même, et elle ne te promet rien de magique. »**

Trois choses y sont vraies et vérifiables aujourd'hui : c'est bien elle, elle répond bien elle-même,
et le site ne promet effectivement rien. Aucune de ces trois affirmations ne demande une preuve qu'on
ne possède pas. C'est ce qui la rend utilisable immédiatement.

**Ce que la promesse ne dit pas, volontairement :** aucun bénéfice santé, aucun résultat, aucun
chiffre. Interdit par la réglementation (§5 de `reglementaire-espagne.md`) et par la règle d'agence.

## A.4 Le message du hero

| Élément | Contenu |
|---|---|
| **Sur-titre** | `Fati · Espagne` — situe la personne et le lieu en trois mots. Remplace « Bien-être naturel · apprentissage concret », qui est une catégorie, pas une personne. |
| **Titre** | **« Je vous présente ce que j'utilise, et je réponds avant que vous achetiez. »** Première personne, revendiquée. Deux promesses tenables : une sélection assumée, une personne joignable. |
| **Sous-titre** | « La sélection Naturixa et mes formations, avec un échange direct sur WhatsApp. Prix affichés, aucune promesse santé, aucune pression. » |
| **CTA principal** | **« Voir la sélection »** → `/produits/`. Un seul primaire. Libellé d'issue, pas de mécanique. |
| **CTA secondaire** | **« Écrire à Fati »** → WhatsApp prérempli. En lien texte souligné, pas en second bouton plein : la hiérarchie doit se voir au premier coup d'œil. |
| **Élément de preuve** | **Le lien TikTok avec le handle `@fatichanelya`**, posé sous les CTA. C'est la seule preuve qui existe, elle est vérifiable en un tap, et elle boucle avec l'endroit d'où vient la visiteuse. Pas de nombre d'abonnés tant qu'il n'est pas fourni et daté (voir E). |
| **Média** | Une **vraie photo de Fati**, cadrage portrait/buste, regard caméra. C'est l'élément LCP. |

**Trois titres candidats évalués, pour que `velix-copy` tranche avec le contexte :**

1. « Je vous présente ce que j'utilise, et je réponds avant que vous achetiez. » — *retenu.* Dit la
   personne, le catalogue et le canal. Longueur tenable sur 4 lignes à 375 px.
2. « Vous m'avez vue sur TikTok. Voici ma sélection. » — plus court, très bon message-match, mais
   il vieillit mal si l'acquisition se diversifie, et il ne dit pas le canal WhatsApp.
3. « Des produits choisis, des prix affichés, une vraie personne au bout du message. » — bon sur la
   transparence, mais il parle de la boutique et non de Fati : il perd le capital TikTok.

**Ce qui disparaît du hero actuel :** le second bouton plein, le badge flottant « Catalogue vérifié »
(une auto-déclaration sans valeur probante), et la triple mention « Entrepreneure · Créatrice ·
Mentore » qui affirme trois statuts sans en prouver un seul. Elle revient sur `/a-propos/`, datée,
quand le parcours de Fati sera fourni.

## A.5 Hiérarchie des CTA

Une action principale par page. Une secondaire au maximum. La règle tient sur tout le site.

| Page | CTA principal | CTA secondaire | Interdit sur cette page |
|---|---|---|---|
| Accueil `/` | Voir la sélection → `/produits/` | Écrire à Fati (WhatsApp) | Ajouter au panier |
| Boutique `/produits/` | Ajouter à ma sélection (sur chaque fiche) | Poser une question sur ce produit | — |
| Catégorie | idem boutique | — | — |
| Fiche produit | Ajouter à ma sélection | Poser une question sur **ce produit** (WhatsApp prérempli avec le nom) | — |
| Formations `/formations/` | Demander le programme (WhatsApp) | Voir les 4 programmes | Ajouter au panier |
| Fiche formation | Demander le programme de **cette formation** | — | — |
| À propos `/a-propos/` | Voir la sélection | Écrire à Fati | — |
| Contact `/contact/` | Écrire à Fati | — | — |
| Livraison & retours | Écrire à Fati | Voir la sélection | — |
| Mentions, Confidentialité, CGV | **aucun** | — | Tout CTA commercial |

**Le bouton flottant « Ma sélection »** reste dans l'en-tête sur toutes les pages, avec un compteur.
Ce n'est pas un CTA de page, c'est un état persistant : il ne compte pas dans la hiérarchie
ci-dessus, mais il ne doit jamais masquer le CTA principal en bas d'écran mobile.

**Aucune barre collante en bas sur mobile.** Elle mange 15 % de la hauteur utile sur 375 px et entre
en collision avec le bandeau cookies. Le CTA se répète dans le flux.

## A.6 Le parcours de conversion, littéralement

### Parcours 1 — produit, le cas principal

```
TikTok (vidéo)  →  lien en bio  →  /  (accueil, mobile)
     ↓
lit le hero : reconnaît Fati, comprend qu'il y a une boutique   [3 s]
     ↓
descend : 4 engagements, l'univers en 3 entrées                 [8 s]
     ↓
tape « Voir la sélection »  →  /produits/
     ↓
filtre par catégorie, regarde 2-3 prix
     ↓
tape une fiche  →  /produits/<slug>/
     ↓
lit : prix, contenance, usage, mention santé, livraison
     ↓
     ├── « Ajouter à ma sélection »  → tiroir, compteur +1
     │        ↓
     │   continue, ajoute 1-2 produits
     │        ↓
     │   ouvre le tiroir : liste, total indicatif, ET LA PHRASE DE CADRAGE
     │        ↓
     │   « Envoyer ma sélection sur WhatsApp »
     │        ↓
     │   WhatsApp s'ouvre, message prérempli : produits + quantités + total
     │        ↓
     │   ELLE ENVOIE  ←── c'est ici que la conversion est comptée
     │        ↓
     │   Fati répond : disponibilité, frais de port réels, total, moyen de paiement
     │        ↓
     │   paiement hors site (virement / Bizum / PayPal privé) → expédition
     │
     └── « Poser une question sur ce produit » → WhatsApp prérempli avec le nom du produit
              ↓
          conversation → retour éventuel sur le site → boucle sur la branche du haut
```

**Le point de vérité de ce parcours :** la conversion **n'est pas une commande, c'est un message
envoyé**. Il faut l'écrire ainsi partout, y compris dans la mesure. Une « commande » se conclut dans
WhatsApp, hors du site, et le site ne le sait pas.

**La conséquence à assumer dans le design :** la phrase « le paiement en ligne n'est pas encore
activé » ne doit **pas** apparaître pour la première fois au moment de valider. Elle doit être posée
**en amont, comme une caractéristique et non comme une excuse** — dans les engagements du hero, puis
répétée sur la fiche produit, puis rappelée dans le tiroir. Une information annoncée trois fois
n'est plus une mauvaise surprise ; une information découverte au dernier écran fait abandonner.

**Formulation à retenir** (à affiner par `velix-copy`), qui retourne la contrainte en argument :

> « Ici, on confirme la commande ensemble par message : disponibilité, frais de port réels et total,
> avant tout paiement. Le paiement en ligne arrive. »

Elle est vraie, elle explique *pourquoi* c'est ainsi, et elle donne un bénéfice réel : pas de
surprise sur les frais de port — ce qui est justement l'objection n° 1 de la vente à distance.

### Parcours 2 — formation

```
TikTok  →  /  →  bloc Formations (3 lignes, pas de grille)  →  /formations/
     ↓
compare 4 programmes : niveau, durée, à qui c'est destiné
     ↓
/formations/<slug>/  → lit le détail, le format, ce qui est inclus
     ↓
« Demander le programme »  →  WhatsApp prérempli avec le nom de la formation
     ↓
Fati répond : format, dates, prix                 ←── le prix est ici, pas sur le site (voir A.8)
```

### Parcours 3 — la sceptique

C'est le parcours le plus important à ne pas casser, parce qu'il précède les deux autres chez la
moitié des visiteuses. Elle ne va pas à la boutique : elle va vérifier.

```
/  →  descend jusqu'à « Qui est Fati »  →  /a-propos/
     ↓
cherche : est-ce qu'elle est réelle ? est-ce du MLM ? est-ce que je peux la joindre ?
     ↓
pied de page  →  /mentions-legales/  →  cherche un nom, une adresse, un NIF
     ↓
pied de page  →  /livraison-retours/  →  cherche « est-ce que ça vient jusqu'à chez moi »
     ↓
     ├── satisfaite → revient sur /produits/
     └── rien trouvé, ou pages vides → part et ne revient pas
```

**Ce parcours explique pourquoi les pages légales sont un sujet de conversion et pas une corvée.**
C'est développé en section C.

### Après l'envoi du message — ce qui n'existe pas et qu'il faut décider

Le site s'arrête au moment où WhatsApp s'ouvre. Trois trous à combler, à faire trancher par la
cliente :

1. **Aucun accusé de réception.** La visiteuse envoie et ne sait pas quand on lui répond. → Annoncer
   le délai **avant** le clic : « Réponse sous 24 h en semaine » existe déjà sur la section contact,
   il faut la remonter dans le tiroir, juste au-dessus du bouton.
2. **Aucune trace côté site.** Rien n'est enregistré. Assumé en v1 (`architecture.md` §4 : 11 objets
   métier sur 16 manquent). À compter à la main jusqu'à la bascule WooCommerce.
3. **Aucune relance.** Si la conversation s'arrête, il ne se passe rien. C'est hors périmètre v1 et
   c'est normal ; à noter comme limite connue, pas comme oubli.

### Quand PayPal sera branché — ce qui change, et ce qui ne change pas

`architecture.md` §3.5 tranche : pas de PayPal sur mesure, bascule WooCommerce. Côté UX, le blueprint
est construit pour que **la bascule ne redessine rien**.

| Élément | Aujourd'hui | Après la bascule | Redessin ? |
|---|---|---|---|
| Bouton sur fiche produit | « Ajouter à ma sélection » | « Ajouter au panier » | Libellé seulement |
| Tiroir latéral | « Ma sélection » + total indicatif | Panier + sous-total | Même composant |
| Bouton de sortie du tiroir | « Envoyer ma sélection sur WhatsApp » | « Passer commande » puis, sur la dernière étape, un bouton portant une mention non équivoque d'obligation de payer (voir §8.3 du réglementaire) | Libellé + destination |
| Phrase de cadrage | « on confirme ensemble par message » | remplacée par frais de port et TVA calculés | Texte |
| `/livraison-retours/` | tarifs annoncés en texte | même page, plus le calcul au panier | Aucun |
| Étapes ajoutées | — | panier → adresse → livraison → paiement → confirmation | **4 écrans nouveaux** |
| CGV | page lue | **case à cocher non pré-cochée** avant paiement | Ajout |

**Ce qu'il faut préparer dès maintenant pour que ce soit vrai :**
- Le tiroir est un composant autonome, jamais une section de la page d'accueil.
- Le CTA produit passe par un composant unique (règle playbook : un seul `Button` à variantes).
  Changer un libellé doit être une ligne, pas dix-neuf.
- `/livraison-retours/` est écrite dès la v1 avec une structure qui accueillera des tarifs chiffrés
  sans réécriture.
- **Ne rien écrire nulle part qui devienne faux après la bascule.** Éviter « le paiement en ligne
  n'existe pas » ; préférer « pas encore activé ».

## A.7 Carte des objections

Reprise du brief §3, avec pour chacune **la page, la section, et la preuve réellement disponible**.
Quand la preuve manque, c'est écrit, et la section prévoit son affichage conditionnel.

| Objection | Où elle est traitée | Avec quelle preuve | Disponible ? |
|---|---|---|---|
| **« C'est qui, en fait ? »** | Hero (`/`) + S7 Qui est Fati + `/a-propos/` | Vraie photo + première personne + lien TikTok vérifiable | Photo **non**, TikTok **oui** |
| **« C'est du MLM / pyramidal »** | `/a-propos/` S4 « Ce que je ne fais pas » | Le site ne recrute personne, ne parle ni de revenus ni d'équipe. Argument par l'absence, vérifiable en parcourant le site | **Oui**, gratuitement |
| **« Est-ce que ça marche ? »** | Fiche produit + FAQ + mention santé en pied | On ne répond pas. On décrit l'usage et on renvoie au professionnel de santé. **La sobriété est la preuve** dans un secteur saturé de promesses | **Oui** |
| **« C'est cher »** | Fiche produit, à côté du prix | **Contenance + durée d'usage** (« 90 gélules ≈ 45 jours »). C'est ce qui rend un prix acceptable | **Non — à demander.** Voir E |
| **« Livrez-vous chez moi ? »** | Fiche produit (ligne courte) + `/livraison-retours/` (détail) | Pays, délais, tarifs | **Non — bloquant.** Voir C.3 |
| **« WhatsApp, c'est louche »** | Tiroir + fiche produit + FAQ | Le cadrage de A.6 : on confirme ensemble, frais de port réels, avant paiement | **Oui**, par la formulation |
| **« Le produit est-il authentique ? »** | Fiche produit, bloc discret | Mention de l'entité qui fournit (DXN Internacional Spain SLU, NIF B30877195, vérifiée dans le réglementaire §4.3) | **Partiel — à confirmer** par écrit auprès de DXN |
| **« Pourquoi elle serait légitime pour me former ? »** | `/formations/` + `/a-propos/` | **Rien aujourd'hui.** Parcours daté en 6 lignes à obtenir | **Non.** Voir E |
| **« Et si ça ne me va pas ? »** | `/livraison-retours/` + fiche produit | Rétractation 14 jours, avec l'exception « produit descellé » correctement rédigée (réglementaire §8.2) | **Oui**, dès que la page est écrite |

**Trois objections sur neuf n'ont aujourd'hui aucune preuve disponible.** Aucune section ne sera
construite autour de ces trois-là ; on crée l'emplacement, il reste masqué.

## A.8 Ce qu'on révèle du prix, et pourquoi

Deux offres, deux traitements. C'est assumé et il faut l'expliquer, sinon ça passe pour une
incohérence.

### Produits — prix affichés, complets, partout

**Décision : le prix est visible sur la vignette, sur la fiche, dans le tiroir, sans exception, et
jamais barré.**

Pourquoi :
- La grille avec prix visible est **la convention du secteur qu'on suit volontairement** (brief §Secteur).
  L'acheteuse sait lire une boutique ; ne pas la surprendre là libère l'attention pour le reste.
- Cacher un prix dans une catégorie à ticket bas (15-200 €) lit comme « c'est cher » ou comme un
  tunnel. Les deux nous coûtent plus que le prix lui-même.
- Un prix stable et non barré est, dans un secteur saturé de « -30 % », **un signal de sérieux**.
- **Obligation légale** : l'information précontractuelle exige le prix total, taxes et frais de
  livraison compris (réglementaire §8.3). Le prix doit donc être accompagné d'une mention du statut
  des frais de port, partout où il apparaît.

**Mention obligatoire à côté de tout prix**, courte et constante :
« Prix en euros, TTC, hors livraison. Frais de port confirmés par message. »

### Formations — prix non affiché, et on dit pourquoi

**Décision : aucun prix affiché, mais le silence est expliqué.**

Pourquoi : le prix, le format et la durée réelle **ne sont pas connus** (brief §12, hypothèse 5, et
question ouverte 5). On ne peut pas afficher ce qui n'existe pas.

Mais un prix absent sans explication lit comme un tunnel — exactement ce que le brief refuse. La
page doit donc porter une ligne du type : « Le tarif dépend du format (groupe ou individuel) et de
la durée. Demandez le programme, je vous réponds avec le détail. »

> **Recommandation forte à la cliente :** afficher au minimum un **prix plancher** (« à partir de
> X € ») dès qu'il est connu. Sur ce type d'offre, un plancher qualifie mieux qu'une opacité
> complète, et il aligne les formations sur la transparence revendiquée des produits. **Tant que le
> chiffre n'est pas fourni, on n'invente rien.**

---

# B · Arborescence cible

## B.1 Le principe de découpe

Une page par intention (`architecture.md` §6). **Une page longue qui fait tout convertit moins bien
qu'un accueil qui oriente — mais un petit site éclaté trop tôt se vide.** Deux tests appliqués à
chaque candidat :

1. **Le test de l'intention** — quelqu'un peut-il arriver directement sur cette page depuis Google,
   un lien en bio, ou un partage WhatsApp, avec cette question en tête ? Si oui, c'est une page.
2. **Le test du volume** — y a-t-il assez de matière pour que la page ne paraisse pas vide ? Si non,
   ça reste une section.

## B.2 L'arborescence

URLs figées par `architecture.md` §5. **Ce blueprint n'en invente aucune** et signale explicitement
les trois seules créations.

| Page | URL | Intention | Statut |
|---|---|---|---|
| **Accueil** | `/` | « Qui est Fatichanelya ? » — **premier trafic attendu** | Existe, à recomposer |
| **Boutique** | `/produits/` | « Qu'est-ce qu'elle vend, à quel prix ? » | Existe, à enrichir |
| **Catégorie** | `/categorie-produit/<slug>/` ×4 | « compléments / soins / boissons » | Existe |
| **Fiche produit** | `/produits/<slug>/` ×19 | nom exact — **gisement SEO réel** | Existe, à enrichir |
| **Formations** | `/formations/` | « formation e-commerce / vente / IA en français » | Existe (archive CPT) |
| **Fiche formation** | `/formations/<slug>/` ×4 | nom exact | URL existe, **pas de template** |
| **À propos** | `/a-propos/` | « c'est qui, est-ce du MLM, est-ce sérieux » | **Page nouvelle** |
| **Contact** | `/contact/` | « comment je la joins » | **Page nouvelle** |
| **Livraison & retours** | `/livraison-retours/` | « ça vient chez moi, en combien de temps, et si je renvoie ? » | Existe, **vide** |
| **Mentions légales** | `/mentions-legales/` | obligation LSSI art. 10 | Existe, **vide** |
| **Confidentialité** | `/confidentialite/` | obligation RGPD | Existe, **vide** |
| **CGV** | `/conditions-generales-de-vente/` | obligation vente à distance | **Page nouvelle** — texte écrit par `velix-copy` |
| Recherche | `/?s=` | — | `noindex` |
| 404 | — | — | Existe |

**Trois pages nouvelles seulement : `/a-propos/`, `/contact/`, `/conditions-generales-de-vente/`.** Aucune redirection à
prévoir : À propos et Contact sont aujourd'hui des ancres, pas des URL (`architecture.md` §8), et
`/conditions-generales-de-vente/` n'a jamais existé.

**Deux templates à créer :** `archive-formation.php` et `single-formation.php`. Ils n'existent pas
dans le thème (vérifié le 14 septembre 2026) : `/formations/` et `/formations/<slug>/` tombent
aujourd'hui sur `archive.php` et `single.php` génériques. **C'est un trou à signaler à `velix-front`,
et c'est ce qui rend la section Formations invendable aujourd'hui.**

## B.3 Ce qui reste sur l'accueil, ce qui en sort

La question posée explicitement. Réponse, ligne par ligne, avec le motif.

| Section actuelle | Décision | Pourquoi |
|---|---|---|
| **Hero** | **Reste**, réécrit | Le seul écran qui encaisse le capital TikTok |
| **Engagements** (`trust`) | **Reste**, resserré à 3 | Traite les trois soupçons d'entrée. Mais 4 items en bandeau = 4 points focaux : on descend à 3 |
| **Univers** (`universe`) | **Reste**, devient l'aiguillage | C'est la section qui fait passer l'accueil de « page qui vend » à « page qui oriente ». Sa fonction est d'envoyer vers `/produits/`, `/formations/`, `/a-propos/` |
| **Boutique** — 19 produits | **SORT.** Remplacée par **6 produits en aperçu** + « Voir les 19 » | **La fuite n° 4.** L'accueil doit donner envie d'aller à la boutique, pas être la boutique. Gain direct : tout ce qui construit la confiance remonte au-dessus de la ligne de fatigue |
| **Formations** | **Reste**, réduit à un bloc narratif | 3 lignes + un lien vers `/formations/`. Pas de grille de 4 cartes sur l'accueil |
| **À propos** | **Reste en teaser**, détail sur `/a-propos/` | Un teaser à l'accueil (parcours 3 : la sceptique doit trouver Fati sans quitter la page), le développement sur sa page |
| **Promesse** (`pledge`) | **Fusionne** dans « Ce que je ne fais pas » | Aujourd'hui `trust` et `pledge` disent la même chose à deux endroits. Deux sections structurellement identiques = un défaut de rythme, et une dilution du message |
| **FAQ** | **Reste**, 5 questions | Bon format, JSON-LD `FAQPage` déjà en place. Les questions changent (voir D.1) |
| **Contact** | **Reste** comme bande finale | C'est le CTA final. Il reçoit le plus d'espace vertical de la page |

**Bilan : l'accueil passe de 9 sections à 9 sections** — mais ce ne sont plus les mêmes, et surtout
le catalogue complet n'y est plus. La page raccourcit d'environ 60 % en hauteur mobile alors qu'elle
argumente davantage.

## B.4 Navigation

Menu principal, 4 entrées + la sélection. Aujourd'hui deux entrées sont des ancres ; elles deviennent
des pages.

| Emplacement | Aujourd'hui | Cible |
|---|---|---|
| **Principal** | Boutique · Formations · #about · #contact | **Boutique · Formations · À propos · Contact** (toutes des URL) |
| **Pied — Navigation** | Boutique · Formations · Contact | Boutique · Formations · À propos · Contact |
| **Pied — Informations** | Mentions · Confidentialité · Livraison | **Mentions · Confidentialité · CGV · Livraison & retours** + Gérer les cookies |

**Le lien « Gérer les cookies » du pied est une obligation, pas un confort** : le consentement doit
pouvoir être retiré aussi facilement qu'il a été donné (réglementaire §7.2). Il ouvre le panneau de
préférences, il ne mène pas à une page.

**Mobile, en-tête à 375 px :** wordmark à gauche, bouton « Ma sélection » avec compteur à droite,
menu hamburger. Le sélecteur de langue passe **dans** le menu déroulant, pas dans la barre : à
375 px, quatre langues en barre écrasent tout. Le menu ouvert affiche les 4 entrées en grand corps,
le sélecteur de langue, puis « Écrire à Fati » en bas.

---

# C · Les pages légales

Demande explicite du gérant. Trois pages existent et sont vides ; le réglementaire en exige **quatre**.

> **Convergence avec `velix-copy`, le 14 septembre 2026.** En parallèle de ce blueprint, `velix-copy`
> a rédigé les quatre pages dans `copy-legal.md`, avec 28 marqueurs `[[À FOURNIR]]` et le slug
> `conditions-generales-de-vente` pour les CGV — slug repris ici. Les deux documents concordent sur
> les deux pièges vérifiés (clause abusive sur les produits scellés, lien ODR mort). **Ce que la
> section C apporte en plus, et qui reste à faire :** la place de chaque page dans le parcours de la
> visiteuse, l'endroit exact où poser chaque lien (C.5), le gabarit de mise en page (D.8), les règles
> de conception du bandeau cookies (C.2), et la décision de publier `/livraison-retours/` à 70 %
> plutôt que de la laisser vide (C.3).

## C.0 Pourquoi c'est un facteur de conversion, pas une corvée

L'argument mérite d'être posé avant les fiches, parce qu'il change la façon de les écrire.

**Le produit vendu est un complément alimentaire acheté à distance par une femme qui ne connaît la
vendeuse que par des vidéos.** Dans cette configuration précise, le doute n'est pas « est-ce que le
produit est bon » : c'est **« est-ce que cette personne existe vraiment et est-ce que je peux la
retrouver si ça se passe mal »**.

C'est exactement la question à laquelle répondent les mentions légales. Un nom réel, une adresse
réelle, un NIF, un e-mail : ce sont les seuls éléments **vérifiables et opposables** de tout le site.
Dans un secteur — le MLM de compléments — où l'immense majorité des boutiques de distributeurs sont
anonymes ou répliquées sous un sous-domaine, **avoir de vraies mentions légales est un
différenciateur commercial**, pas une case à cocher.

Le raisonnement vaut aussi en négatif : une page qui affiche « Contenu à rédiger » est **pire que
l'absence de la page**. Une page absente passe inaperçue ; une page vide est une preuve active
d'amateurisme, découverte exactement au moment où la visiteuse cherchait à se rassurer (parcours 3).

Trois conséquences rédactionnelles qui découlent de là :

1. **Écrites en français lisible, pas en jargon recopié.** Une page de CGV manifestement
   copiée-collée d'un générateur se reconnaît et ne rassure personne.
2. **Au ton du site.** Le reste du site parle à la première personne et refuse les promesses. Les
   pages légales doivent sonner comme la même personne, pas comme un avocat greffé.
3. **Pas de `noindex` sur Livraison & retours et CGV.** `architecture.md` §6 note « `noindex`
   acceptable » pour mentions et confidentialité — d'accord. Mais `/livraison-retours/` répond à une
   vraie intention de recherche et traite une objection majeure : **elle doit être indexable**. Les
   CGV aussi, parce qu'elles sont consultées avant achat.

> **Limite à poser clairement au client.** L'agence structure ces pages, écrit ce qui relève du
> fonctionnement du site, et dit exactement quelles informations manquent. **Elle ne rédige pas le
> contenu juridique et ne le valide pas.** `architecture.md` §10 le dit déjà : « Contenu des pages
> légales et CGV → cliente + son conseil ». Les quatre fiches ci-dessous sont des **plans de page à
> faire remplir et valider**, pas des textes prêts à publier.

## C.1 `/mentions-legales/` — « aviso legal »

| | |
|---|---|
| **À quoi ça sert** | Obligation de la **LSSI-CE, Ley 34/2002, article 10** : identifier le vendeur de manière « permanente, facile, directe et gratuite ». Commercialement : c'est la page qui prouve que Fati existe légalement |
| **Indexation** | `noindex` acceptable. Mais **jamais** derrière un formulaire ou un compte |
| **CTA** | Aucun |

**Ce qu'elle doit contenir** (réglementaire §7.1) :

1. Nom et prénom réels de Fati (personne physique autónoma) — pas « Fatichanelya »
2. Adresse réelle (domicile ou domiciliation professionnelle)
3. Adresse e-mail + un second moyen de contact effectif (le WhatsApp professionnel convient)
4. **NIF**
5. Données d'inscription à un registre, le cas échéant
6. Autorisation administrative, le cas échéant
7. Hébergeur : raison sociale et pays d'hébergement
8. Propriété intellectuelle : à qui appartiennent les textes, photos et visuels produits
9. Loi applicable et juridiction

**Ce qui manque côté cliente — bloquant :** nom complet, adresse, NIF. Et une décision sensible :

> **Point à traiter avec elle avant la mise en ligne.** Une autónoma doit publier son **adresse
> réelle**. Si elle travaille de chez elle, cela signifie **publier son adresse personnelle sur un
> site relié à un compte TikTok public**. Ce n'est pas neutre pour une femme qui s'expose en vidéo.
> Le réglementaire §7.1 recommande d'interroger le gestor sur la validité d'une **domiciliation
> professionnelle**. **Cette question doit être posée avant, pas après.**

**Liens entrants :** pied de page (Informations), et `/conditions-generales-de-vente/` qui renvoie à l'identité du vendeur.

## C.2 `/confidentialite/` — politique de confidentialité + cookies

| | |
|---|---|
| **À quoi ça sert** | RGPD (2016/679) + LOPDGDD. Informer sur les données collectées, la base légale, les destinataires, les durées, les droits |
| **Indexation** | `noindex` acceptable |
| **CTA** | Aucun. **Sauf** un bouton « Gérer mes cookies » qui rouvre le panneau |

**Ce qu'elle doit contenir**, traitement par traitement (réglementaire §7.3) :

| Traitement | Base légale | À préciser |
|---|---|---|
| Message WhatsApp | Exécution du contrat / mesures précontractuelles | **WhatsApp est un traitement à documenter** : les données transitent par Meta. C'est le canal principal du site, il ne peut pas être passé sous silence |
| Commande et livraison | Exécution du contrat | Destinataires : transporteur, PayPal le moment venu |
| Formulaire de contact | Consentement ou intérêt légitime | Case **non pré-cochée** |
| Newsletter | **Consentement explicite** | Case non pré-cochée, désinscription en un clic. **Voir E.4 : à retirer tant qu'aucun outil n'est branché** |
| Cookies / pixel TikTok | **Consentement** | Ne se déclenche **jamais** avant acceptation |
| Facturation | Obligation légale | Durées de conservation fiscales |

Plus : identité du responsable de traitement (= Fati, mêmes données que C.1), droits (accès,
rectification, suppression, opposition, limitation, portabilité), moyen de les exercer, et **droit de
réclamation auprès de l'AEPD**.

**Ce qui manque côté cliente :** l'hébergeur retenu, l'outil de mesure retenu (s'il y en a un),
l'outil d'emailing (s'il y en a un), et le NIF/adresse de C.1.

### Le bandeau cookies — exigences de conception, non négociables

Le réglementaire §7.2 est précis et les manquements sont constatables en quelques secondes.

```
S0 · Bandeau de consentement
objectif  obtenir un consentement valide avant tout traceur non essentiel
contenu   une phrase + trois actions de même poids : Tout accepter · Tout refuser · Configurer
preuve    aucune
cta       trois actions au même niveau d'accès — « Tout refuser » EXACTEMENT aussi visible
          que « Tout accepter » : même taille, même couleur, même emplacement, même poids
média     aucun
mobile    bandeau bas, deux boutons pleine largeur côte à côte, « Configurer » en lien dessous.
          NE COUVRE JAMAIS LE HERO. Hauteur réservée dans le flux pour ne pas décaler la page
motion    aucune — une entrée animée est un décalage de mise en page déguisé
```

**Quatre règles à tenir absolument :**

1. Le pixel TikTok et tout script de mesure **ne se chargent pas avant acceptation**. C'est l'erreur
   la plus fréquente : le pixel se déclenche au chargement et le bandeau devient décoratif.
2. Choix **par catégorie** (mesure, publicité, personnalisation), pas seulement tout ou rien.
3. Le consentement doit être **retirable** aussi facilement — d'où le lien permanent dans le pied.
4. **Le bandeau ne doit rien décaler.** Il pèse directement sur le CLS, et la cible est ≤ 0,1.

> **Tant qu'aucun traceur n'est installé, il n'y a pas de bandeau à afficher.** Le site n'en pose pas
> aujourd'hui (brief §7). Mais le composant doit être prêt **avant** la première campagne, pas après :
> le jour où le pixel est posé, le bandeau doit déjà exister et fonctionner.

## C.3 `/livraison-retours/` — la page qui fait le plus pour la conversion

| | |
|---|---|
| **À quoi ça sert** | Répondre à l'objection n° 1 de la vente à distance, et satisfaire l'information précontractuelle (directive 2011/83/UE, RDL 1/2007) |
| **Indexation** | **Indexable.** Intention de recherche réelle (`architecture.md` §6) |
| **CTA** | Principal : « Écrire à Fati » · Secondaire : « Voir la sélection » |

**Ce qu'elle doit contenir :**

1. **Pays livrés**, sous forme de liste ou de tableau de zones
2. **Délais** par zone, en jours ouvrés
3. **Tarifs** par zone, et seuil de franco de port s'il existe
4. **Comment la commande est confirmée aujourd'hui** — le cadrage WhatsApp de A.6, écrit ici en clair
5. **Moyens de paiement** actuels, et mention que le paiement en ligne arrive
6. **Droit de rétractation : 14 jours naturels** à compter de la réception du bien
7. **Le formulaire type de rétractation**, fourni (obligation, réglementaire §8.1)
8. **L'exception « produits scellés »**, rédigée avec précision
9. **Remboursement** : sous 14 jours naturels, frais de livraison standard aller inclus
10. **Garantie légale de conformité : 3 ans** (Espagne, depuis le 1ᵉʳ janvier 2022)
11. **Voies de recours** — OMIC, Juntas Arbitrales de Consumo
12. Produit abîmé ou non conforme à la réception : quoi faire, dans quel délai

> ⚠️ **Deux pièges à ne pas reproduire, tous deux vérifiés dans le réglementaire.**
>
> **1. Ne jamais écrire « pas de retour sur les compléments alimentaires ».** C'est une **clause
> abusive**. L'exception de l'article 103 e) du RDL 1/2007 s'interprète **restrictivement** et exige
> trois conditions cumulatives : le bien est scellé, le descellement le rend inapte au retour pour
> raisons d'hygiène, **et** il a été effectivement descellé après livraison. Un pot **non ouvert**
> reste retournable. Formulation correcte : « Les produits scellés ne peuvent être repris que si le
> scellé a été retiré après la livraison. Un produit non descellé reste retournable dans les
> 14 jours. » Et corollaire opérationnel : **le scellé doit être réel, visible et documenté** — c'est
> une décision de conditionnement, pas de rédaction.
>
> **2. Ne jamais mettre le lien vers la plateforme européenne ODR** (`ec.europa.eu/consumers/odr`).
> **Elle est fermée depuis le 20 juillet 2025** (règlement UE 2024/3228). Quasi tous les modèles de
> CGV et tous les générateurs en circulation contiennent encore ce lien. Un lien mort vers un recours
> inexistant est une information fausse donnée à la consommatrice, et le signal d'une boutique dont
> les textes n'ont pas été tenus à jour.

**Ce qui manque côté cliente — le plus bloquant de tout le projet :** pays livrés, délais, tarifs.
C'est déjà au journal, et ça reste ouvert.

**Comportement tant que ces informations manquent — et c'est une décision, pas un contournement :**

> La page est publiée avec ce qui est **certain** (rétractation, garantie, recours, procédure de
> confirmation par message) et une ligne honnête sur ce qui ne l'est pas encore : « Les tarifs et
> délais par pays sont confirmés par message avant tout paiement. » **On ne publie pas une page
> vide, et on n'invente pas un tarif.** Une page utile à 70 % vaut infiniment mieux que
> « Contenu à rédiger ».

**Liens entrants — elle doit être atteignable de partout où le doute naît :** pied de page, **chaque
fiche produit** (ligne courte sous le prix), **le tiroir de sélection** (juste au-dessus du bouton
WhatsApp), la FAQ de l'accueil, et les CGV.

## C.4 `/conditions-generales-de-vente/` — conditions générales de vente · **page manquante**

| | |
|---|---|
| **À quoi ça sert** | Le contrat de vente. Obligation de la vente à distance. **N'existe pas** : c'est le trou signalé par le rapport réglementaire |
| **Indexation** | Indexable |
| **CTA** | Aucun |

**Ce qu'elle doit contenir :**

1. Identité du vendeur (renvoi à `/mentions-legales/`)
2. Objet et champ d'application
3. Produits : description, disponibilité, prix TTC, devise
4. **Comment une commande se forme aujourd'hui** — point délicat et important : dans le parcours
   actuel, le contrat ne se conclut **pas sur le site**, il se conclut dans la conversation WhatsApp.
   Les CGV doivent le dire exactement, parce que c'est ce qui détermine à quel moment la cliente est
   engagée et quand court le délai de rétractation
5. Moyens de paiement acceptés
6. Livraison : renvoi à `/livraison-retours/`, et **délai maximal de 30 jours naturels**
7. Rétractation : renvoi à `/livraison-retours/`, avec le formulaire type
8. Garantie légale de conformité : 3 ans
9. Responsabilité, et **la mention santé** : les compléments ne sont pas des médicaments
10. Données personnelles : renvoi à `/confidentialite/`
11. Loi applicable, juridiction, voies de recours (**sans le lien ODR**)

**Ce qui manque côté cliente :** tout le contenu juridique, plus l'arbitrage de la question 4 avec un
abogado. **La question 4 est la plus délicate du site** : vendre par WhatsApp sans tunnel de paiement
est parfaitement légal, mais le moment de formation du contrat doit être écrit noir sur blanc.

**Après la bascule WooCommerce**, la page change sur deux points : le parcours de commande devient
celui du site, et une **case à cocher non pré-cochée** d'acceptation des CGV s'ajoute avant paiement.
Le bouton final devra porter une mention non équivoque d'obligation de payer — un bouton « Confirmer »
n'est pas conforme, et la sanction est que **la consommatrice n'est pas liée** (réglementaire §8.3).

## C.5 Où placer les liens, et pourquoi là

| Emplacement | Ce qu'on y met | Pourquoi |
|---|---|---|
| **Pied de page, colonne « Informations »** | Les 4 pages + « Gérer les cookies » | Obligation LSSI : accès permanent, facile, direct, gratuit depuis n'importe quelle page |
| **Fiche produit, sous le prix** | Une ligne : « Livraison et retours » | L'objection naît **ici**. Un lien dans le pied arrive trois écrans trop tard |
| **Tiroir de sélection, au-dessus du bouton** | « Livraison et retours » + le délai de réponse | Dernier point de doute avant l'action |
| **FAQ de l'accueil** | Réponses courtes qui **renvoient** aux pages | La FAQ oriente, elle ne duplique pas |
| **Au moment du paiement** (après bascule) | Case CGV non pré-cochée | Obligation |
| **Mention santé** | Pied de page, **toutes pages** — déjà en place | Obligation compléments. **À ne pas déplacer ni raccourcir** |

**Ce qu'on ne fait pas :** pas de bandeau légal permanent en haut de page, pas de pop-in d'acceptation
des CGV à l'arrivée, pas de lien légal dans le menu principal. Les obligations se remplissent dans le
pied ; les surexposer transforme un signal de sérieux en signal d'inquiétude.

---

# D · Wireframes page par page

Format : `objectif · contenu · preuve · cta · média · mobile · motion`.
**Mobile 375 px est écrit en premier** ; le desktop est la variante.

## D.1 Accueil `/`

**Objectif** — transformer une spectatrice TikTok en visiteuse qui ouvre la boutique ou écrit un
message. **Ce n'est pas de vendre sur cette page.**

**CTA principal** — Voir la sélection → `/produits/`. **Secondaire** — Écrire à Fati.

**L'argument, dans l'ordre :** c'est bien moi → voici ce que je fais et comment → voici ce que je ne
fais pas → voici ce que je vends → voici qui je suis → vos questions → écrivez-moi.

```
S1 · Hero — split éditorial
objectif  encaisser le capital TikTok : la visiteuse doit reconnaître Fati en moins de 2 s
contenu   sur-titre « Fati · Espagne » · titre en 1re personne (A.4) · sous-titre 2 lignes ·
          1 CTA plein + 1 lien texte · handle @fatichanelya cliquable
preuve    le lien TikTok — seule preuve disponible et vérifiable en un tap. AUCUN chiffre
cta       principal « Voir la sélection » · secondaire « Écrire à Fati » en lien souligné
média     photo réelle de Fati, portrait. ÉLÉMENT LCP — jamais lazy-load, jamais animé à l'entrée
mobile    photo EN PREMIER (c'est elle qu'on vient voir), puis titre, sous-titre, CTA pleine
          largeur, lien secondaire, handle. Titre ≤ 4 lignes à 375 px. Hauteur ~85 vh, jamais 100
motion    aucune sur le texte du hero (règle playbook : ne jamais animer le LCP)
```

```
S2 · Bandeau engagements — cellules à filets
objectif  désamorcer les 3 soupçons (MLM · promesse · arnaque) avant qu'ils se formulent
contenu   3 cellules, PAS 4 : « Prix affichés, jamais barrés » · « Aucune promesse santé » ·
          « Vous me parlez à moi, pas à un robot »
preuve    aucune — cette section gagne par la justesse, pas par la preuve
cta       aucun — ne pas interrompre
média     aucun
mobile    3 cellules empilées, séparées par un filet horizontal fin. Pas de cartes, pas de
          bordure, pas d'arrondi, pas d'ombre. Pas d'icônes
motion    reveal commun, décalage 60 ms entre cellules
```

```
S3 · L'univers en trois entrées — aiguillage asymétrique
objectif  LA section qui fait de l'accueil une page qui oriente : 3 destinations, 3 intentions
contenu   3 entrées : Produits (19 réf., 4 catégories) · Formations (4 programmes) ·
          Qui est Fati. Une phrase chacune + un lien
preuve    aucune
cta       3 liens de même poids — c'est l'unique section où c'est VOULU : on aiguille,
          on ne pousse pas
média     1 visuel d'ambiance par entrée (existants), format portrait, arrondi doux
mobile    3 blocs empilés pleine largeur, photo puis titre puis phrase puis lien
motion    reveal commun
```

```
S4 · Ce que je ne fais pas — bande sombre  ← 1re rupture (bleu nuit)
objectif  répondre frontalement à « c'est du MLM ? ». Le différenciateur le plus fort qu'on
          possède, parce qu'il ne coûte aucune preuve à produire
contenu   4 lignes courtes, en négatif : « Je ne recrute personne. » · « Je ne promets aucun
          résultat de santé. » · « Je n'affiche aucun avis que je n'ai pas reçu. » ·
          « Je ne fais pas de compte à rebours. » Puis UNE ligne en positif
preuve    l'absence est la preuve : rien sur le site ne contredit ces phrases, et ça se vérifie
          en le parcourant
cta       aucun
média     aucun — la typographie porte seule
mobile    liste verticale, grand corps, filet vertical à gauche. Section respirée
motion    reveal commun, ligne par ligne
```

```
S5 · Aperçu de la sélection — grille, 6 produits
objectif  donner envie d'ouvrir la boutique. PAS être la boutique
contenu   6 produits (2 par catégorie parmi les 4) + « Voir les 19 produits »
preuve    prix affichés, visuels réels du catalogue
cta       le lien « Voir les 19 produits » — les vignettes mènent aux fiches, pas au panier.
          AUCUN bouton d'ajout depuis l'accueil : on ne fait pas ajouter avant d'avoir montré
          qui on est
média     6 visuels produits WebP fond blanc (existants)
mobile    2 colonnes de 3. Vignette = photo carrée, nom sur 2 lignes max, prix. Sous la grille :
          « Prix en euros, TTC, hors livraison » puis le lien, en pleine largeur
motion    reveal commun sur la grille entière, pas vignette par vignette
```

```
S6 · Les formations — bloc narratif asymétrique
objectif  signaler la deuxième offre sans ouvrir un second tunnel
contenu   titre + 3 lignes + les 4 noms de programmes en liste simple + « Voir les formations »
preuve    AUCUNE, et c'est le problème de fond (E.2). Tant que le parcours n'est pas fourni,
          cette section reste courte et modeste : une section longue sans preuve fait douter
          de tout le reste de la page
cta       un lien vers /formations/
média     1 photo de travail (à produire — voir E.5)
mobile    photo pleine largeur puis texte. Les 4 noms en liste à filets, sans description
motion    reveal commun
```

```
S7 · Qui est Fati — teaser, asymétrique inversé
objectif  la sceptique (parcours 3) doit trouver la personne sans quitter la page
contenu   portrait + 3-4 phrases à la 1re personne + « En savoir plus sur moi » → /a-propos/
preuve    la photo réelle. Le parcours daté ira sur /a-propos/ quand il sera fourni
cta       un lien vers /a-propos/
média     photo verticale de Fati, différente de celle du hero
mobile    photo, puis texte, puis lien. Inverser le côté par rapport à S6 (rythme)
motion    reveal commun
```

```
S8 · Questions fréquentes — accordéon
objectif  traiter les objections résiduelles et ORIENTER vers les pages légales
contenu   5 questions, la 1re ouverte. Les questions changent : « Comment se passe une
          commande ? » · « Livrez-vous chez moi ? » · « Puis-je renvoyer un produit ? » ·
          « Les compléments remplacent-ils un traitement ? » (réponse : non) ·
          « Comment choisir un produit ? ». Chaque réponse ≤ 3 lignes + un lien vers la page
preuve    aucune
cta       aucun bouton — les liens dans les réponses suffisent
média     aucun
mobile    accordéon natif <details>/<summary>, zone tactile ≥ 44 px
motion    ouverture/fermeture uniquement, respect de prefers-reduced-motion
```

```
S9 · Écrivez-moi — bande finale sombre  ← 2e rupture, PLUS D'ESPACE VERTICAL DE LA PAGE
objectif  le CTA final
contenu   titre court, une phrase, le bouton, et EN DESSOUS les attentes : « Réponse sous 24 h
          en semaine · aucun conseil médical par message · pas de liste de diffusion »
preuve    aucune
cta       « Écrire à Fati » sur WhatsApp — un seul bouton, seul dans son écran
média     aucun
mobile    bouton pleine largeur. La section occupe seule un écran complet à 375 px
motion    reveal commun
```

**Rythme vérifié :** clair → clair → clair → **sombre** → clair → clair → clair → clair → **sombre**.
Alternance de structure : split → filets → aiguillage → typographique → grille → asymétrique →
asymétrique inversé → accordéon → bande centrée. **Jamais trois sections structurellement
identiques.** S6 et S7 sont toutes deux asymétriques : elles sont inversées en côté et différentes en
densité (S6 liste + photo, S7 portrait + prose), ce qui suffit — mais c'est le point à surveiller à
l'intégration.

**Ce qui disparaît de l'accueil et qui est une décision assumée :**
- La grille des 19 produits (→ `/produits/`)
- La section `pledge` autonome (fusionnée dans S4)
- Le badge flottant « Catalogue vérifié » (auto-déclaration sans valeur)
- Le champ newsletter (voir E.4)
- La recherche produits (elle appartient à la boutique)

## D.2 Boutique `/produits/`

**Objectif** — faire ouvrir au moins une fiche produit. **CTA principal** — ouvrir une fiche.

```
S1 · En-tête de boutique — compact
objectif  poser le cadre en 2 lignes et ne pas retarder la grille
contenu   titre, une phrase, et LA MENTION DE PRIX : « 19 références · prix en euros, TTC,
          hors livraison »
preuve    aucune
cta       aucun
média     aucun — pas de bannière : elle repousse la grille sous la ligne de flottaison
mobile    compact, ~120 px de haut maximum
motion    aucune
```

```
S2 · Filtres et recherche — barre collante
objectif  permettre de réduire 19 à 4 en un tap
contenu   « Tout voir » + 4 catégories + champ de recherche + compteur de résultats
preuve    aucune
cta       aucun
média     aucun
mobile    chips en défilement horizontal (assumé : c'est la convention, et chacune est
          atteignable). Recherche sous les chips, repliée derrière une icône. Barre collante
          en haut au défilement, hauteur ≤ 56 px, ne masquant jamais la première rangée
motion    aucune — un filtre doit être instantané
```

```
S3 · Grille produits — 19 références
objectif  faire ouvrir une fiche
contenu   vignettes : photo, nom, catégorie, prix. Les indisponibles (_fati_indispo) grisées
          et repoussées en fin de grille, jamais masquées
preuve    prix + visuels réels
cta       la vignette entière est cliquable → fiche. Bouton d'ajout rapide en second plan
          seulement, jamais concurrent du lien
média     18 visuels WebP. Lingzhi Black Coffee : placeholder — voir E.5
mobile    2 colonnes. Lazy-load à partir de la 5e vignette. Ratio fixé pour éviter le CLS
motion    AUCUNE animation d'entrée sur la grille — 19 éléments qui se révèlent, c'est un
          site qui rame
```

```
S4 · Comment ça se passe — bande claire sous la grille
objectif  expliquer le parcours AVANT que la visiteuse découvre qu'on ne paie pas en ligne
contenu   3 étapes en grand corps, sans zéro devant : « 1 Vous composez votre sélection »
          « 2 On confirme ensemble par message : stock, frais de port réels, total »
          « 3 Vous recevez » + une ligne : « Le paiement en ligne arrive »
preuve    aucune
cta       aucun
média     aucun
mobile    3 blocs empilés, numéro en grand corps au-dessus du titre. Cellules à filets
motion    reveal commun
```

```
S5 · Une question avant de choisir — bande sombre finale
objectif  rattraper celle qui n'a pas trouvé, ou qui hésite
contenu   une phrase + le bouton WhatsApp + lien « Livraison et retours »
preuve    aucune
cta       « Poser une question » · secondaire : Livraison et retours
média     aucun
mobile    bouton pleine largeur
motion    reveal commun
```

**Pages catégorie `/categorie-produit/<slug>/`** — même gabarit, S1 porte le nom et la description de
la catégorie, S2 a la catégorie courante pré-sélectionnée, et un lien « Voir les 19 produits » est
ajouté sous la grille.

## D.3 Fiche produit `/produits/<slug>/`

**Objectif** — faire ajouter à la sélection, ou faire poser une question. C'est la page
transactionnelle et le gisement SEO réel (`architecture.md` §6).

```
S1 · Bloc produit — image + informations
objectif  décider
contenu   fil d'Ariane · photo · nom · catégorie · PRIX · mention « TTC, hors livraison » ·
          extrait · CONTENANCE ET DURÉE D'USAGE (manquant, voir E.1) · CTA · lien question ·
          ligne « Livraison et retours »
preuve    prix, visuel réel, contenance quand elle sera fournie
cta       principal « Ajouter à ma sélection » · secondaire « Poser une question sur ce
          produit » (WhatsApp prérempli AVEC LE NOM DU PRODUIT — détail qui change tout :
          Fati sait de quoi on parle dès le premier message)
média     visuel WebP fond blanc. LCP de la page
mobile    photo pleine largeur en premier (ratio réservé), puis nom, prix, mention, CTA
          pleine largeur, lien secondaire, puis extrait. LE PRIX ET LE CTA SONT VISIBLES
          SANS DÉFILER à 375 px — c'est la contrainte de mise en page n° 1 de cette page
motion    aucune
```

```
S2 · Description et usage — prose
objectif  informer sans jamais rien promettre
contenu   description (champ éditeur), conseils d'utilisation repris de l'emballage
preuve    la description d'usage elle-même — factuelle, vérifiable sur le produit
cta       aucun
média     aucun
mobile    mesure lisible, ~65 caractères
motion    aucune
```

```
S3 · Mention santé — encadré discret
objectif  obligation légale, et paradoxalement un signal de sérieux
contenu   la mention obligatoire, déjà en place dans les options
preuve    aucune
cta       aucun
média     aucun
mobile    fond beige léger, texte petit mais ≥ 14 px, contraste AA. C'est l'une des rares
          BOÎTES autorisées : elle doit se distinguer du corps de texte
motion    aucune
```

```
S4 · Origine du produit — 2 lignes  [CONDITIONNEL]
objectif  répondre à « est-ce authentique ? » (contrefaçons DXN sur les marketplaces)
contenu   l'entité qui fournit : DXN Internacional Spain SLU, NIF B30877195
preuve    l'entité est vérifiée dans le réglementaire §4.3, MAIS la relation commerciale de
          Fati avec elle NE L'EST PAS
cta       aucun
média     aucun
mobile    2 lignes sous la description
motion    aucune
--- SECTION ENTIÈREMENT MASQUÉE tant que DXN n'a pas confirmé PAR ÉCRIT (réglementaire §9.3,
    question 8). Ne rien afficher vaut mieux qu'une affirmation non vérifiée ---
```

```
S5 · Avis  [N'EXISTE PAS]
--- AUCUNE SECTION D'AVIS, AUCUN EMPLACEMENT VIDE, AUCUN « soyez la première à donner votre
    avis », AUCUN balisage Review en JSON-LD. Voir E.3 ---
```

```
S6 · Dans la même catégorie — rangée de 4
objectif  relancer le parcours plutôt que de finir en cul-de-sac
contenu   4 produits de la même catégorie
preuve    prix
cta       les vignettes
média     4 visuels
mobile    défilement horizontal de 4, ou grille 2×2. Préférer la grille 2×2 : un défilement
          horizontal cache la moitié des produits
motion    aucune
```

```
S7 · Bande finale — question
objectif  rattraper l'hésitante
contenu   « Une question sur ce produit ? » + bouton WhatsApp + lien Livraison
cta       WhatsApp prérempli avec le nom du produit
mobile    bouton pleine largeur
motion    reveal commun
```

## D.4 Formations `/formations/` — **template à créer**

**Objectif** — faire demander un programme. **CTA principal** — Demander le programme.

> **Avertissement structurant.** Cette page demande une confiance qu'aucune preuve ne soutient
> aujourd'hui : ni parcours, ni prix, ni format, ni nombre de personnes accompagnées, ni témoignage.
> **Le blueprint la conçoit courte et sobre exprès.** Une page longue et enthousiaste sans preuve
> ressemble exactement au tunnel d'infopreneuse que le brief refuse, et elle contaminerait la
> crédibilité de la partie produits. **Elle s'allongera quand les preuves arriveront, pas avant.**

```
S1 · En-tête — typographique
objectif  poser ce que c'est et ce que ce n'est pas
contenu   titre, 2 phrases, et une ligne de cadrage honnête : « Pas de masterclass gratuite,
          pas de compte à rebours. Un échange, un programme, des dates. »
preuve    aucune
cta       aucun
média     aucun
mobile    compact
motion    aucune
```

```
S2 · Les 4 programmes — cellules à filets, PAS des cartes
objectif  faire choisir
contenu   4 entrées : nom, niveau (_fati_niveau), durée (_fati_duree), une phrase « à qui ça
          s'adresse », un lien vers la fiche
preuve    aucune
cta       un lien par programme vers /formations/<slug>/
média     aucun — pas d'icônes, pas de pictogrammes
mobile    4 cellules empilées, séparées par un filet horizontal. Numéro en GRAND CORPS sans
          zéro devant. Pas de bordure, pas d'arrondi, pas d'ombre au survol
motion    reveal commun
```

```
S3 · Comment ça se passe — 3 étapes
objectif  réduire l'anxiété : ce qui se passe après le message
contenu   « 1 Vous m'écrivez ce que vous voulez faire » · « 2 Je vous envoie le programme,
          le format et le tarif » · « 3 On fixe les dates ». Plus une ligne sur l'engagement
          de temps demandé
preuve    aucune
cta       aucun
média     aucun
mobile    vertical, numéros en grand corps
motion    reveal commun
```

```
S4 · Pourquoi moi  [CONDITIONNEL]
objectif  répondre à « pourquoi elle serait légitime »
contenu   le parcours de Fati en 4-6 lignes DATÉES
preuve    le parcours lui-même
cta       aucun
média     1 photo de travail
mobile    photo puis texte
motion    reveal commun
--- SECTION MASQUÉE tant que le parcours daté n'est pas fourni. Voir E.2. Ne PAS remplacer
    par « Entrepreneure · Créatrice · Mentore » : trois mots sans date, lieu ni fait, qui
    affirment sans prouver ---
```

```
S5 · Témoignages  [N'EXISTE PAS EN V1]
--- Emplacement prévu dans le gabarit, ENTIÈREMENT MASQUÉ. Voir E.3 ---
```

```
S6 · Bande finale sombre — demander un programme
objectif  le CTA
contenu   titre, une phrase, bouton, et « Le tarif dépend du format et de la durée. Je vous
          réponds avec le détail. »
preuve    aucune
cta       « Demander le programme » (WhatsApp)
média     aucun
mobile    bouton pleine largeur, le plus d'espace vertical de la page
motion    reveal commun
```

## D.5 Fiche formation `/formations/<slug>/` — **template à créer**

```
S1 · En-tête — nom, niveau, durée, à qui c'est destiné · CTA « Demander le programme »
     mobile : CTA visible sans défiler
S2 · Le contenu du programme — prose ou liste. Ce qu'on apprend, dans l'ordre
S3 · Ce qui est inclus / ce qui n'est pas inclus — deux colonnes.
     La colonne « pas inclus » est ce qui rend l'autre crédible
     [MANQUE : format, nombre de séances, support. À demander]
S4 · Tarif — une ligne honnête, sans chiffre tant qu'il n'est pas fourni (A.8)
S5 · Les 3 autres formations — cellules à filets
S6 · Bande finale — « Demander le programme de <nom> » (WhatsApp prérempli avec le nom)
```

## D.6 À propos `/a-propos/` — **page nouvelle**

**Objectif** — lever le soupçon MLM et rendre Fati réelle. C'est la page du parcours 3, et la plus
importante du site après l'accueil pour une audience qui vient d'un réseau social.

**CTA principal** — Voir la sélection. **Secondaire** — Écrire à Fati.

```
S1 · Portrait éditorial — pleine largeur
objectif  montrer la personne, pas la marque
contenu   une phrase manifeste en très grand corps, à la 1re personne
preuve    la photo réelle
cta       aucun
média     grande photo de Fati, horizontale, lumière naturelle. LCP
mobile    photo pleine largeur ratio réservé, phrase en dessous
motion    aucune sur la photo (LCP)
```

```
S2 · Mon parcours  [CONDITIONNEL — PARTIELLEMENT MASQUÉ]
objectif  rendre crédible tout le reste du site
contenu   6 lignes datées : d'où elle vient, depuis quand en Espagne, depuis quand
          distributrice, ce qu'elle a monté
preuve    les dates elles-mêmes — c'est ce qui distingue un parcours d'une déclaration
cta       aucun
média     aucun
mobile    liste verticale à filets, année en grand corps à gauche
motion    reveal commun
--- MASQUÉE tant que les 6 lignes datées ne sont pas fournies. Voir E.2 ---
```

```
S3 · Ce que je fais / ce que je ne fais pas — deux colonnes  ← LA SECTION CENTRALE
objectif  répondre frontalement au soupçon MLM. C'est ici que se joue la page
contenu   « Ce que je fais » : je choisis, je présente, je réponds, j'expédie.
          « Ce que je ne fais pas » : je ne recrute personne, je ne promets aucun résultat de
          santé, je ne vends pas d'opportunité, je n'affiche aucun avis que je n'ai pas reçu
preuve    l'absence, vérifiable en parcourant le site
cta       aucun
média     aucun
mobile    deux blocs empilés, « ce que je ne fais pas » EN SECOND (on finit sur la réponse
          au soupçon). Filet vertical distinguant les deux colonnes, pas de cartes
motion    reveal commun
```

```
S4 · Mes engagements — bande sombre
objectif  transformer les contraintes du projet en engagements tenus
contenu   3 engagements repris de `pledge` : informations vérifiables · conseil direct ·
          aucune fausse promesse
preuve    aucune
cta       aucun
média     aucun
mobile    3 blocs, numéros en grand corps
motion    reveal commun
```

```
S5 · Vu sur TikTok — grille de captures
objectif  boucler avec l'origine du trafic et montrer la seule preuve réellement disponible
contenu   4 captures TikTok publiques (405×720), ASSUMÉES COMME TELLES (ratio vertical,
          apparence de capture) + lien vers le profil
preuve    LA SEULE PREUVE SOCIALE DISPONIBLE AUJOURD'HUI. Aucun nombre d'abonnés tant qu'il
          n'est pas fourni et daté avec capture
cta       lien vers @fatichanelya
média     4 captures existantes. Ne PAS les recadrer en paysage : leur nature de capture est
          ce qui les rend crédibles
mobile    2 colonnes de 2, ratio 9:16
motion    reveal commun
```

```
S6 · Bande finale — Voir la sélection · Écrire à Fati
```

## D.7 Contact `/contact/` — **page nouvelle**

**Objectif** — faire envoyer un message. **CTA principal** — Écrire à Fati.

```
S1 · En-tête — titre, une phrase, bouton WhatsApp
     Sous le bouton : « Réponse sous 24 h en semaine · aucun conseil médical par message »
S2 · Trois raisons d'écrire — cellules à filets
     « Une question sur un produit » · « Demander un programme de formation » ·
     « Proposer une collaboration ». Chacune → WhatsApp prérempli avec un objet différent.
     C'est le détail qui fait que Fati sait de quoi on parle dès le premier message
S3 · Ce que je ne peux pas faire par message — encadré honnête
     « Je ne donne aucun conseil médical. Pour toute question de santé, parlez à un
     professionnel. » Rare cas où une contrainte légale améliore la confiance
S4 · Informations pratiques — langues parlées (français, espagnol, darija), délai,
     et le lien vers /mentions-legales/
S5 · Formulaire e-mail  [CONDITIONNEL — MASQUÉ EN V1]
     Alternative pour qui ne veut pas donner son numéro. Nécessite une adresse e-mail
     professionnelle + SMTP authentifié (architecture.md §9) + information RGPD.
     Tant que ces trois éléments n'existent pas, la section reste masquée :
     un formulaire dont les messages se perdent est pire que pas de formulaire
```

## D.8 Pages légales — gabarit commun

Un seul gabarit pour `/mentions-legales/`, `/confidentialite/`, `/conditions-generales-de-vente/`, `/livraison-retours/`.

```
S1 · En-tête
objectif  situer et dater
contenu   titre, une phrase de résumé en français simple, DATE DE DERNIÈRE MISE À JOUR
preuve    la date — sur une page légale, c'est un signal de sérieux mesurable
cta       aucun
média     aucun
mobile    compact
motion    aucune
```

```
S2 · Sommaire ancré  [si plus de 5 sections]
objectif  rendre une page longue consultable sur mobile
contenu   liens d'ancre vers les sections
mobile    liste verticale. Pas de sommaire collant : il mange la hauteur utile
motion    aucune
```

```
S3 · Le corps — prose structurée
objectif  être lisible, pas impressionnant
contenu   h2 par thème, paragraphes courts, tableaux pour zones/délais/tarifs
preuve    l'exactitude
cta       AUCUN sur mentions, confidentialité et CGV. Sur Livraison & retours UNIQUEMENT :
          un lien « Écrire à Fati » en fin de page
média     aucun
mobile    mesure lisible, corps ≥ 16 px, tableaux qui passent en listes définition à 375 px
          plutôt qu'en défilement horizontal
motion    aucune
```

```
S4 · Les autres pages légales — liens croisés
objectif  qui lit une page légale en lit souvent deux
contenu   liens vers les 3 autres
mobile    liste simple
motion    aucune
```

**Sur `/confidentialite/` uniquement :** un bouton « Gérer mes cookies » qui rouvre le panneau.

## D.9 404

```
S1 · Message court, à la 1re personne, sans humour forcé
S2 · Trois liens : Accueil · Boutique · Écrire à Fati
     Pas de recherche, pas de grille produits : une 404 est un aiguillage, pas une vitrine
```

---

# E · Ce qui manque pour convertir

Le brief est formel : **aucun témoignage, aucun chiffre, aucune preuve sociale**. Rien n'est fabriqué
ici. Pour chaque manque : l'emplacement créé, ce qu'il faut demander, et le comportement tant qu'il
est vide.

## E.1 Contenance et durée d'usage — le plus rentable

| | |
|---|---|
| **Où** | Fiche produit, D.3/S1, immédiatement sous le prix |
| **Pourquoi c'est le plus rentable** | C'est ce qui rend un prix acceptable. « RG · 61 € » est cher. « RG · 90 gélules · environ 45 jours · 61 € » est un prix à la journée que la visiteuse calcule elle-même. **Aucune autre information ne change autant la perception du prix, et celle-ci n'est pas une preuve sociale : elle est sur l'emballage.** |
| **À demander** | Pour les 19 références : contenance (nombre d'unités ou poids) et durée d'usage indicative selon les conseils de l'emballage |
| **Attention** | La durée se déduit de la posologie **imprimée sur l'emballage**. Ne jamais recommander une posologie de notre propre chef : ce serait un conseil, et le site n'en donne pas |
| **Tant que c'est vide** | Le prix s'affiche seul. Aucun texte de remplacement, aucun tiret, aucun « — ». La ligne n'existe simplement pas |

## E.2 Le parcours de Fati en 6 lignes datées

| | |
|---|---|
| **Où** | `/a-propos/` D.6/S2 (section entière) et `/formations/` D.4/S4 |
| **Pourquoi** | C'est **la** preuve qui manque aux formations. Aujourd'hui la page demande de payer pour apprendre de quelqu'un dont on ne sait rien. Trois mots — « Entrepreneure · Créatrice · Mentore » — ne sont pas un parcours |
| **À demander** | Six lignes, chacune avec une date ou une période : d'où elle vient · depuis quand en Espagne · ce qu'elle faisait avant · depuis quand distributrice · ce qu'elle a monté · depuis quand elle forme |
| **Ce qu'on ne demande pas** | Aucun chiffre de résultat, aucun revenu, aucun « j'ai accompagné X personnes » non vérifiable. Un chiffre de résultat en formation tombe sous les pratiques commerciales trompeuses (brief §Secteur) |
| **Tant que c'est vide** | Les deux sections sont **entièrement masquées**. `/a-propos/` reste cohérente sans S2 : S1, S3, S4, S5 tiennent seules. `/formations/` reste courte — et c'est honnête |

## E.3 Témoignages — emplacement créé, jamais affiché vide

| | |
|---|---|
| **Où** | `/formations/` D.4/S5. **Et nulle part ailleurs** |
| **Pourquoi pas sur les produits** | Deux raisons. **Réglementaire :** relayer une cliente qui écrit « depuis que je prends ça je me sens mieux » revient à **diffuser soi-même l'allégation interdite** (réglementaire §5.2). Le risque est réel et il pèse sur Fati, pas sur l'agence. **Stratégique :** un avis produit appelle une note en étoiles, donc un balisage `Review`, qui est un risque de sanction manuelle s'il ne repose sur rien |
| **À demander, et seulement pour les formations** | 3 à 5 témoignages écrits de personnes réellement accompagnées : prénom, ville ou pays, une à trois phrases, **accord écrit de publication**. Aucun propos portant sur la santé, même indirectement |
| **Règle de seuil** | La section n'apparaît **qu'à partir de 3 témoignages**. Un témoignage seul ressemble à une faveur d'amie ; trois ressemblent à une pratique |
| **Tant que c'est vide** | **Section absente du rendu.** Pas de bloc gris, pas de « Bientôt les avis de mes élèves », pas de « soyez la première ». **Une section de témoignages vide est pire que pas de section du tout** — elle annonce précisément ce qu'on n'a pas |
| **Aucun cas** | Aucun balisage `Review` ni `AggregateRating` en JSON-LD, nulle part, tant qu'aucun avis réel n'existe |

## E.4 Audience TikTok, et le sort de la newsletter

**Le nombre d'abonnés.** La seule preuve sociale que Fati possède déjà.

| | |
|---|---|
| **Où** | Hero D.1/S1, sous les CTA, et `/a-propos/` D.6/S5 |
| **À demander** | Le nombre d'abonnés **à une date donnée, avec capture datée**, plus 3 à 5 commentaires publics réutilisables avec autorisation |
| **Condition** | Le chiffre ne s'affiche **que** s'il est fourni et daté. Un chiffre d'audience vieillit : prévoir un champ éditable et une convention de formulation (« plus de X abonnées sur TikTok »), pas un chiffre codé en dur |
| **Tant que c'est vide** | Seul le handle `@fatichanelya` s'affiche, en lien. Le handle est déjà une preuve : il est vérifiable en un tap, et c'est exactement le geste que la visiteuse sait faire |
| **Les commentaires** | **Modération obligatoire.** Ne jamais republier un commentaire à contenu médical, même élogieux — ce serait diffuser l'allégation (réglementaire §5.2) |

**La newsletter.** Un champ sans service derrière, avec une note d'administration visible en front.

> **Décision : la retirer du pied de page en v1.** Trois raisons cumulatives. **Fonctionnelle :** les
> adresses se perdent. **Juridique :** collecter une adresse sans base légale ni information est une
> infraction RGPD, et l'envoi sans consentement explicite relève des infractions graves LSSI
> (réglementaire §7.3). **Crédibilité :** une note d'admin en clair sur le site public est le
> contraire d'un site premium.
>
> **L'emplacement reste prévu dans le gabarit du pied.** Le jour où un outil gratuit est choisi, la
> section revient avec : une phrase d'attente honnête (« un e-mail par mois, désinscription en un
> clic »), un champ, une case **non pré-cochée**, et un lien vers `/confidentialite/`.

## E.5 Les photos — combien, et pour quoi faire

Le brief signale que **le portrait du hero et celui du mentoring sont générés**. C'est le risque n° 1
de la marque : sur une marque personnelle alimentée par une audience qui connaît le vrai visage, une
image générée démasquée fait tomber l'argument central.

**Ce blueprint est écrit en supposant que les vraies photos arrivent.** Les emplacements sont fixés
en conséquence.

| # | Photo | Où | Format | Priorité |
|---|---|---|---|---|
| 1 | **Portrait horizontal, regard caméra** | Hero `/` — **LCP du site** | Paysage 3:2, cadrage buste, arrière-plan calme | **Bloquante** |
| 2 | **Portrait vertical** | `/a-propos/` S1 | Portrait 4:5 | **Bloquante** |
| 3 | **Portrait vertical, second cadrage** | Accueil S7 (teaser) | Portrait 4:5, **différent du n° 1** | Haute |
| 4 | **Photo de travail** (ordinateur, carnet, en situation) | Accueil S6 + `/formations/` S4 | Paysage 3:2 | Haute |
| 5 | **Photo produits en situation** (mains, plan de travail) | Aperçu boutique S5 ou en-tête `/produits/` | Paysage | Moyenne |
| 6 | **Image Open Graph 1200×630** | Partages WhatsApp et TikTok | 1200×630 | **Haute** |
| 7 | **Visuel Lingzhi Black Coffee** | Fiche produit — seule des 19 sans visuel | Carré fond blanc | Moyenne |
| 8 | **Monogramme / favicon** | En-tête, favicon | SVG | Moyenne |

**Minimum viable : 4 photos réelles** (n° 1, 2, 3, 4) issues d'une seule séance. Les n° 5 et 6 se
dérivent de la même séance.

**Le n° 6 mérite une insistance :** le canal de partage principal est WhatsApp. Une carte de partage
sans visuel — ou avec un visuel générique — divise le taux de clic sur le canal qui compte le plus
ici (`architecture.md` §7, défaut 4).

**Tant que les vraies photos n'arrivent pas — comportement à décider avec la cliente :**

> Deux options, et l'agence recommande la seconde. **(a)** Conserver le portrait généré, marqué comme
> provisoire dans le média-manifest — c'est l'hypothèse 6 du brief, et c'est un risque assumé.
> **(b)** Basculer le hero sur une composition **produit + typographie**, sans visage, et attendre.
> Le hero perd en chaleur mais ne ment pas, et le risque de démasquage disparaît.
>
> **La recommandation est (b)**, parce que le coût de (a) n'est pas graduel : le jour où une abonnée
> le remarque, ce n'est pas la photo qui est perdue, c'est la promesse « une vraie personne » —
> c'est-à-dire tout le positionnement. **Cette décision appartient à la cliente et doit être posée
> explicitement, pas laissée par défaut.**

## E.6 Récapitulatif — à demander à la cliente

Par ordre d'impact sur la conversion. Complète la liste du journal.

| # | Ce qu'il faut | Débloque | Gravité |
|---|---|---|---|
| 1 | **Numéro WhatsApp définitif** | L'unique CTA du site | **Bloquante** |
| 2 | **Pays livrés, délais, tarifs** | `/livraison-retours/`, objection n° 1 | **Bloquante** |
| 3 | **Nom, adresse, NIF** + arbitrage domiciliation | `/mentions-legales/`, `/conditions-generales-de-vente/` | **Bloquante** |
| 4 | **4 photos réelles de Fati**, ou décision E.5 | Hero, `/a-propos/`, accueil S7, formations | **Bloquante** |
| 5 | **Contenance + durée d'usage** des 19 références | Acceptabilité du prix | Haute |
| 6 | **Parcours en 6 lignes datées** | `/a-propos/` S2, `/formations/` S4 | Haute |
| 7 | **Textes juridiques validés** par son conseil | Les 4 pages légales | Haute |
| 8 | **Format, durée, tarif des formations** | `/formations/` et les 4 fiches | Haute |
| 9 | **Confirmation écrite DXN** (question 8 du réglementaire) | Fiche produit S4 | Moyenne |
| 10 | **Abonnés TikTok datés** + commentaires autorisés | Hero, `/a-propos/` S5 | Moyenne |
| 11 | **3 à 5 témoignages de formation** avec accord écrit | `/formations/` S5 | Moyenne |
| 12 | **Visuel Lingzhi Black Coffee** | La 19ᵉ fiche | Moyenne |
| 13 | **Outil d'emailing**, ou confirmation du retrait | Newsletter | Basse |

---

# F · Règles transverses (DA / front)

## F.1 Préférence du gérant — cartes et numéros

**Corrigée deux fois sur APE Maroc. Elle n'est pas négociable et elle s'applique à tout ce blueprint.**

| | |
|---|---|
| **Les grilles de contenu** (engagements, étapes, valeurs, programmes, rubriques) | **Cellules à filets.** Filet supérieur ou vertical fin. **Sans contour gris, sans arrondi, sans ombre, sans changement d'ombre au survol** |
| **Les numéros** | **Grand corps** (≈ 34 px), graisse normale, **sans zéro devant**. Couleur de titre sur fond clair, accent sur fond sombre. **Jamais** en 11 px capitales colorées au-dessus du titre |
| **Les boîtes restent autorisées pour** | les formulaires · les bandeaux d'appel à l'action · les **cartes avec photo** (vignettes produits, captures TikTok) · l'encadré de mention santé |

**Application concrète dans ce blueprint :** D.1/S2, D.1/S4, D.2/S4, D.4/S2, D.4/S3, D.6/S3, D.6/S4,
D.7/S2 sont **tous en cellules à filets**. Seules les vignettes produits (D.1/S5, D.2/S3) et les
captures TikTok (D.6/S5) sont des cartes — parce qu'elles portent une photo.

## F.2 Mobile d'abord — contraintes à 375 px

Écrit avant le desktop, et ce sont des règles de recette, pas des intentions.

- **Le hero tient en un écran** à 375 px : photo + titre + sous-titre + CTA. ~85 vh, jamais 100.
- **Titre du hero ≤ 4 lignes** à 375 px.
- **Sur la fiche produit, le prix et le CTA sont visibles sans défiler.** Contrainte n° 1 de la page.
- **Zones tactiles ≥ 44 px**, y compris les en-têtes d'accordéon et les chips de filtre.
- **CTA principaux en pleine largeur** sur mobile.
- **Aucune barre collante en bas.** Elle mange 15 % de la hauteur et entre en collision avec le
  bandeau cookies.
- **Le bandeau cookies ne couvre jamais le hero** et ne décale rien (CLS ≤ 0,1).
- **Les tableaux des pages légales** passent en listes définition, jamais en défilement horizontal.
- **Tester 768 px systématiquement** — c'est là que les grilles déclenchées à `md` cassent (playbook).
- **RTL à 320 px** : l'arabe est au périmètre, la feuille RTL n'existe pas, rien n'est mesuré
  (`architecture.md` §5).

**Ce qui n'existe que sur desktop — décisions assumées, écrites :**

| Élément | Comportement mobile | Pourquoi |
|---|---|---|
| Recherche produits | Repliée derrière une icône dans la barre de filtres | Les filtres par catégorie suffisent pour 19 produits ; le champ ouvert mange une rangée |
| Sélecteur de langue | Dans le menu déroulant, pas dans la barre | 4 langues en barre écrasent l'en-tête à 375 px |
| Sommaire ancré des pages légales | Présent mais non collant | Un sommaire collant mange la hauteur utile |
| Grille produits associés | 2×2 plutôt que défilement horizontal | Un défilement horizontal cache la moitié des produits |

**Ce qui ne disparaît sur aucun écran :** le prix, la mention « hors livraison », la mention santé, le
lien vers `/livraison-retours/` sur la fiche produit, les liens légaux du pied.

## F.3 Motion

Une seule motion de révélation pour tout le site (règle playbook) : opacité + 18 px, 0,55 s, easing
de marque, respect de `prefers-reduced-motion`. Appliquée aux en-têtes de section.

**Où il n'y a aucune motion, et c'est une décision :**
- Le texte et la photo du hero — **élément LCP**, jamais animé.
- La grille produits de `/produits/` — 19 éléments qui se révèlent lisent comme un site qui rame.
- Les filtres — un filtre doit être instantané.
- Les pages légales — entièrement.
- Le bandeau cookies — une entrée animée est un décalage de mise en page déguisé.

Aucun parallaxe, aucun compteur animé, aucun carrousel automatique.

## F.4 Performance — ce que le blueprint engage

La performance est ici un **levier d'acquisition** avant d'être un levier SEO : le public arrive d'un
lien en bio et repart si ça rame (`architecture.md` §6).

- Sortir les 19 produits de l'accueil **allège directement le LCP de la page la plus visitée**.
- Un seul élément LCP par page, jamais lazy-load, jamais animé.
- Ratios d'image réservés partout (CLS).
- Lazy-load à partir de la 5ᵉ vignette de la grille.
- Le bandeau cookies ne décale rien.
- **Aucun chiffre Lighthouse n'est annoncé ici.** Le thème WordPress est **non mesuré**
  (`architecture.md` §1). La mesure appartient à `velix-perf-seo`, sur préproduction.

---

# G · Deux directions pour l'accueil

L'accueil est la page qui décide. Deux structures **différentes en nature**, pas deux variantes du
même empilement. Ce qui suit est écrit pour que le gérant tranche vite.

## Direction 1 — « L'aiguillage » *(celle décrite en D.1 — recommandée)*

**La structure.** Hero personne → engagements → **aiguillage en 3 entrées** → ce que je ne fais pas →
aperçu 6 produits → formations → qui est Fati → FAQ → contact.

**Ce qu'elle optimise.** Le premier écran vend Fati, pas des produits — donc elle encaisse le capital
TikTok. Elle oriente vite vers `/produits/`, ce qui fait travailler les fiches, qui sont le gisement
SEO réel. Elle tient sur mobile : environ 60 % plus courte que l'accueil actuel. Elle survit à
l'absence de preuves, parce qu'aucune section n'est bâtie sur une preuve manquante. Et elle bascule
sans redessin le jour de WooCommerce.

**Ce qu'elle sacrifie.** Une visiteuse qui voulait juste voir les prix doit faire un tap de plus.
L'aperçu à 6 produits montre moins de catalogue : si la valeur perçue tient à la largeur de l'offre,
on en perd un peu. Et elle demande **impérativement une vraie photo de Fati** — sans visage crédible,
son premier écran s'effondre.

**Quand la choisir.** Quand la marque personnelle est l'actif principal. **C'est le cas ici**, et
c'est pourquoi elle est recommandée.

## Direction 2 — « La vitrine »

**La structure.** Hero **produit-first** (une composition produits, typographie forte, pas de visage)
→ grille de 12 produits immédiatement → filtres par catégorie → comment ça se passe → qui vend ces
produits (Fati, en second) → formations reléguées en bas → FAQ → contact.

**Ce qu'elle optimise.** Le temps jusqu'au premier prix : trois secondes. Elle convient à une
visiteuse qui arrive **déjà décidée** — typiquement celle à qui Fati a envoyé le lien en message
privé en disant « regarde, c'est là ». Elle est **indifférente au problème du portrait** : pas de
visage, pas de risque de démasquage. Et elle serait la bonne structure sous publicité payante avec
une créative produit.

**Ce qu'elle sacrifie.** Elle jette le capital TikTok : la visiteuse curieuse arrive sur une boutique
anonyme, exactement ce à quoi ressemblent les boutiques DXN répliquées dont le brief veut se
distinguer. Le soupçon MLM n'est traité qu'après la grille, donc trop tard. Les formations deviennent
un appendice. Et sur mobile, une grille de 12 en tête de page est un mur.

**Quand la choisir.** Quand le trafic devient majoritairement transactionnel — après la bascule
WooCommerce, ou sous campagne payante à créative produit. **Pas aujourd'hui.**

## Direction 3 — « La lettre » *(mentionnée, non recommandée)*

Un accueil éditorial : une longue lettre de Fati à la première personne, entrecoupée de trois
produits et d'un lien vers la boutique.

**Ce qu'elle optimise.** La connexion émotionnelle, qui est ce que TikTok produit le mieux. Elle
serait la plus différenciante des trois.

**Ce qu'elle sacrifie.** Elle repose **entièrement** sur du texte à la première personne qui n'existe
pas encore, et sur un parcours qui n'a pas été fourni. Elle est illisible pour qui veut un prix. Et
elle ressemble dangereusement, en structure, à la page de vente d'infopreneuse que le brief refuse
explicitement — même avec un ton opposé.

**Quand la reconsidérer.** Si le parcours de Fati et un vrai ton éditorial sont fournis, et si le
gérant veut assumer un site qui ne ressemble à aucun autre. **Pas avec la matière disponible.**

## Recommandation

**Direction 1.** Elle est la seule des trois qui encaisse le capital TikTok **et** qui survit à
l'absence totale de preuves sociales. Les deux autres supposent une matière qui n'existe pas :
la Direction 2 suppose que la boutique se suffit à elle-même, la Direction 3 suppose un texte
éditorial qui n'est pas écrit.

---

# H · Ordre de travail

## H.1 Ce que le blueprint demande, dans l'ordre

| Ordre | Tâche | Pour qui | Dépend de |
|---|---|---|---|
| 1 | **Renseigner le numéro WhatsApp** | cliente | — |
| 2 | Créer `/a-propos/`, `/contact/`, `/conditions-generales-de-vente/` | `velix-cms` | — |
| 3 | Créer `archive-formation.php` et `single-formation.php` | `velix-front` | — |
| 4 | Recomposer l'accueil selon D.1 | `velix-front` | direction artistique |
| 5 | Sortir la grille des 19 produits de l'accueil | `velix-front` | 4 |
| 6 | Enrichir la fiche produit selon D.3 | `velix-front` | E.1 |
| 7 | Écrire les 4 pages légales selon C | cliente + conseil | E.6 n° 2, 3, 7 |
| 8 | Retirer la newsletter du pied | `velix-front` | — |
| 9 | Composant bandeau cookies, prêt mais inactif | `velix-front` | avant toute campagne |
| 10 | Menu principal : ancres → URL | `velix-cms` | 2 |
| 11 | Pied : ajouter CGV + « Gérer les cookies » | `velix-cms` | 2, 9 |
| 12 | Feuille RTL et test à 320 px | `velix-front` / `velix-qa` | Polylang |

## H.2 Ce que ce blueprint ne tranche pas

- **Le choix entre photo réelle et hero sans visage** (E.5) — décision de la cliente, à poser
  explicitement.
- **Le prix plancher des formations** (A.8) — recommandé, mais le chiffre n'existe pas.
- **Le moment de formation du contrat dans les CGV** (C.4) — question pour un abogado.
- **La domiciliation de l'adresse des mentions légales** (C.1) — question pour le gestor.
- **Tout contenu juridique** — l'agence structure, la cliente et son conseil rédigent et valident.

## H.3 Passage de relais

**`velix-da`** et **`velix-copy`** peuvent avancer **en parallèle** à partir de ce document.

**Pour `velix-da`** — la palette et les typographies sont validées en v3 et ne se rediscutent pas
(brief §5). Ce que le blueprint attend : le système de **cellules à filets** (F.1), la déclinaison
claire/sombre des 9 sections de l'accueil avec les deux ruptures en S4 et S9, le composant CTA unique
à variantes avec **contraste AA calculé** avant d'appliquer l'or en fond de bouton (playbook), le
gabarit de vignette produit, le gabarit de page légale, et le bandeau cookies avec **accepter et
refuser strictement équivalents** (C.2).

**Pour `velix-copy`** — le hero (A.4, trois titres candidats à départager), la formulation du cadrage
WhatsApp (A.6, qui doit sonner comme un choix et non comme une excuse), les 4 lignes de « Ce que je ne
fais pas » (D.1/S4), les 5 questions de la FAQ (D.1/S8), les micro-textes du tiroir, et les
en-têtes en français lisible des 4 pages légales. **Rappel de cadre :** aucune allégation santé nulle
part, **y compris dans les libellés de boutons et les titres de section** ; pas de superlatif, pas
d'urgence ; vouvoiement au féminin ; première personne quand Fati parle.

**Pour `velix-front`**, plus tard : les deux templates manquants (H.1 n° 3) sont le trou technique le
plus visible, et ce sont eux qui rendent la section Formations invendable aujourd'hui.
