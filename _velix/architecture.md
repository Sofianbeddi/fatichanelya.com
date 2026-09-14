# Architecture technique — Fatichanelya

*Écrit le 14 septembre 2026 · Auteur : velix-architecte · Décisions de socle prises par le gérant, consignées ici sans être rediscutées*

Ce document fige ce qui ne peut plus changer sans reconstruire : socle, back-office, URLs, langues,
hébergement. Tout ce qui est mesuré est marqué comme tel ; tout ce qui ne l'est pas est marqué
**non mesuré**. Les contraintes légales ne sont pas traitées ici — elles sont dans
`_velix/reglementaire-espagne.md`, écrit en parallèle. Quand une règle de droit a un effet technique,
ce document dit lequel et renvoie à ce fichier.

---

## 1. Socle retenu

**WordPress, thème sur mesure `fatichanelya`, métier isolé dans `wp-content/mu-plugins/fatichanelya-core/`.**
Déjà construit, installé et fonctionnel en local. Décision du gérant, non rediscutée.

Justification adossée aux cinq questions :

1. **Qui édite dans six mois ?** Fati seule, non technicienne. Elle a besoin d'un back-office natif
   qu'elle peut apprendre seule, en français, et d'une page « Contenus du site » où chaque texte de la
   page d'accueil est un champ. C'est exactement ce que `inc/options.php` fournit aujourd'hui.
2. **Combien de pages dans deux ans ?** Une quarantaine en français (4 pages, 19 produits, 4 formations,
   4 catégories, archives), multipliées par 4 langues : environ 160 URL. Volume confortable pour
   WordPress, trop lourd pour du statique écrit à la main, trop léger pour justifier un socle applicatif.
3. **Y a-t-il de l'interactif applicatif ?** Non aujourd'hui : un tiroir de sélection en JavaScript
   local qui compose un message WhatsApp, sans état serveur. Oui demain, et c'est tout l'enjeu de la
   section 3 : dès qu'on encaisse, il faut des commandes, des statuts, des factures.
4. **Est-ce que ça vend en ligne ?** Pas encore. Bientôt, par PayPal, vers toute l'Union européenne.
   C'est le point qui fait basculer l'architecture — traité en section 3.
5. **Qui maintient, sur quel hébergement ?** VELIX pour la technique, Fati pour le contenu.
   Mutualisé PHP 8.2+ en Europe, voir section 9.

**Contrainte structurante :** coût zéro en licences. Aucun plugin payant, aucun abonnement logiciel.
L'hébergement et le domaine sont payés par la cliente, ce qui est normal et n'entre pas dans cette
contrainte.

### Socles écartés, et pourquoi

Ces refus sont écrits pour ne pas rejouer le débat dans trois mois.

| Socle écarté | Pourquoi il ne gagne pas ici |
|---|---|
| **Astro + CMS headless** | Ferait de meilleurs scores, mais Fati administrerait un back-office qu'elle ne connaît pas, et l'hébergement d'un CMS headless est un coût mensuel supplémentaire. Le thème WordPress est déjà écrit et fonctionnel : migrer coûterait une reconstruction complète pour un gain de performance que le cache serveur peut largement rattraper. |
| **Next.js + TypeScript** | Aucun besoin applicatif aujourd'hui. Hébergement Node plus cher qu'un mutualisé espagnol, et Lighthouse plus difficile à tenir sur du vitrine. Retour d'expérience Esprit Fluide : le socle Next n'a rien coûté en soi, mais la dépendance à un CMS distant a fait échouer des builds. Ici, le contenu et le rendu vivent sur la même machine. |
| **Shopify** | Réglerait d'un coup panier, frais de port par pays, TVA OSS, facture et suivi de commande. Refusé par la contrainte de coût : abonnement mensuel, et l'agence ne peut pas l'imposer à une cliente qui démarre. À reconsidérer honnêtement si le volume de commandes décolle — c'est écrit en section 3.7. |
| **HTML/CSS/JS statique** | C'était la v3, elle mesurait 100/100/100/100. Écartée parce que 4 langues × 23 fiches se maintiennent mal à la main, et que Fati ne peut pas éditer un fichier HTML. |
| **Thème WordPress du commerce + page builder** | Poids JavaScript et CSS ingérable pour la cible Lighthouse ≥ 95, et dépendance à un éditeur tiers. Le thème sur mesure est déjà là. |

### Ce qui existe et qui est vérifié

État constaté en local le 14 septembre 2026, repris sans le re-tester :

- WordPress 7.1 fr_FR, base `fatichanelya`, permaliens `/%postname%/`.
- Thème `fatichanelya` activé ; mu-plugin `fatichanelya-core` avec `cpt.php`, `meta.php`, `seo.php`,
  `options.php`, `security.php`, `polylang.php`.
- 19 produits, 4 formations, 4 catégories de produit, 3 menus, 4 pages publiées.
- Toutes les URL en 200. Zéro erreur console, zéro débordement horizontal à 375 / 768 / 1440 px,
  aucune image cassée.
- Polylang **pas encore installé** — `inc/polylang.php` est écrit et attend le plugin ; sans lui,
  `fati_current_lang()` se replie proprement sur la locale.
- **Aucune mesure Lighthouse n'a été faite sur le thème WordPress.** La v3 statique mesurait
  100/100/100/100 ; ce chiffre ne se transfère pas. Score WordPress : **non mesuré**.

**Note de version :** PHP 8.4.5 en CLI local. La cible de production est PHP 8.2 ou 8.3. Avant la mise
en ligne, vérifier que la version PHP de l'hébergeur est bien celle du local, ou au moins de la même
branche majeure.

---

## 2. Back-office et modèle de contenu

**WordPress natif, sans ACF, sans page builder.** Les champs personnalisés passent par
`register_post_meta` + `add_meta_box` (`inc/meta.php`) et les textes de la page d'accueil par la
Settings API (`inc/options.php`, page « Contenus du site »).

Raison : ACF Pro est payant sur les champs dont on aurait eu besoin (Repeater, Options Pages). Le
contournement retenu — un champ `lines` où chaque ligne est `Titre | description` — est moins
confortable qu'un vrai répéteur, mais il coûte zéro euro et il est déjà en production dans le thème.
C'est un compromis assumé, pas un oubli.

### Types de contenu

| Type | Slug d'URL | Champs | Qui édite |
|---|---|---|---|
| **Produit** (CPT `produit`) | `produits` | Titre, description (éditeur), extrait, visuel à la une, `_fati_prix` (nombre, €), `_fati_reference` (interne), `_fati_indispo` (case à cocher), catégorie, ordre | Fati |
| **Formation** (CPT `formation`) | `formations` | Titre, description, extrait, visuel, `_fati_niveau`, `_fati_duree`, ordre | Fati |
| **Catégorie de produit** (taxonomie `categorie_produit`) | `categorie-produit` | Nom, slug, description | Fati |
| **Page** | racine | Titre, contenu | Fati (pages légales : texte fourni par la cliente ou son conseil) |
| **Contenus du site** (options) | — | ~30 champs : hero, engagements, valeurs, FAQ, mention santé, WhatsApp, réseaux | Fati |
| **Article** (natif) | `blog` | En réserve, aucun contenu prévu | — |

Slugs `produits` et `formations` vérifiés dans `inc/cpt.php` le 14 septembre 2026.

### Garde-fou éditorial à ajouter

Le risque n° 1 du brief est qu'une allégation santé interdite soit écrite par Fati dans un champ
libre. Techniquement, cela se traite par une aide contextuelle sous chaque champ de description, et
éventuellement un avertissement non bloquant à l'enregistrement quand un mot d'une liste apparaît.
C'est une tâche pour `velix-cms` ; le périmètre légal de la liste vient de
`_velix/reglementaire-espagne.md`.

---

## 3. Vendre vraiment : le chemin de PayPal sans WooCommerce

C'est la décision la plus lourde de ce document. Les réponses du gérant la rendent inévitable :
Fati expédie depuis son propre stock, encaisse par PayPal, et livre dans toute l'Union européenne.
Ce n'est plus un site vitrine avec un bouton WhatsApp. C'est une boutique.

### 3.1 Ce que « vendre dans toute l'UE » impose réellement

Trois obligations techniques, indépendantes du socle choisi :

1. **Des frais de port variables par pays.** Livrer à Bilbao et livrer en Finlande n'a pas le même
   prix. Il faut au minimum une table zone → tarif, et une règle de seuil (franco de port).
2. **La TVA.** Tant que les ventes transfrontalières vers les autres pays de l'UE restent sous
   10 000 € par an, la TVA espagnole s'applique partout. Au-delà de ce seuil, c'est le taux du pays de
   la cliente qui s'applique, déclaré via le guichet unique OSS. Concrètement : le prix affiché et le
   montant encaissé doivent pouvoir dépendre du pays de livraison, et la facture doit porter le bon
   taux. **Le détail juridique de ce seuil, du régime OSS et du statut de Fati est dans
   `_velix/reglementaire-espagne.md` — ici on ne retient que la conséquence technique : le calcul de
   TVA doit être paramétrable par pays, pas codé en dur.**
3. **Une trace de la commande.** Numéro, date, contenu, adresse, montant, taux de TVA appliqué,
   facture séquentielle, et conservation de ces justificatifs. Une transaction PayPal seule ne fait
   pas une comptabilité.

C'est cette troisième obligation qui disqualifie le « petit bouton PayPal ». Encaisser est facile ;
ce qui est difficile, c'est tout ce qui entoure l'encaissement.

### 3.2 Option (a) — Boutons PayPal Smart Buttons posés à la main dans le thème

**Ce que c'est.** Le SDK JavaScript de PayPal, intégré dans le thème. Le tiroir « Ma sélection »
existant calcule un total, le passe à PayPal, PayPal encaisse.

**Vérifié le 14 septembre 2026** dans la documentation PayPal (Standard Checkout) : le SDK affiche des
boutons et capture un paiement. Il ne calcule **ni la TVA, ni les frais de port**. Le marchand est
explicitement responsable du calcul du montant, qu'il envoie à PayPal déjà calculé.

| | |
|---|---|
| **Coût** | 0 € de licence. Mais du développement sur mesure : calcul de port par zone, calcul de TVA par pays, création d'une commande côté serveur, vérification du paiement côté serveur (webhook), e-mail de confirmation, numérotation de facture, écran de suivi. C'est le coût réel, et il est élevé. |
| **Ce que ça permet** | Panier multi-produits (le tiroir existe déjà). Encaissement PayPal et carte. |
| **Ce que ça ne permet pas sans écrire le code** | Frais de port par pays, TVA par pays, facture, e-mail de confirmation, statut de commande, suivi, remboursement, stock, code promo, relance de panier. Rien de tout cela n'existe. |
| **Le piège** | Le montant est calculé **dans le navigateur**. Sans vérification serveur, n'importe qui peut modifier le total avant de payer. Une intégration sérieuse impose donc un point d'entrée serveur qui recalcule le panier et un webhook PayPal qui confirme l'encaissement. On est en train de réécrire, mal, le noyau d'un moteur e-commerce. |

**Verdict.** Séduisant sur le papier parce que « gratuit », réellement coûteux, et surtout **fragile
là où il ne faut pas l'être** : l'argent et la comptabilité.

### 3.3 Option (b) — Un plugin de paiement gratuit léger, hors WooCommerce

**Ce que c'est.** Un plugin de type « bouton de paiement » ou « formulaire de don/vente simple » qui
pose un PayPal sur une page.

| | |
|---|---|
| **Coût** | 0 € en façade. Une dépendance de plus à maintenir et à auditer, pour une fonction que PayPal fournit déjà en direct. |
| **Ce que ça permet** | Vendre un article à la fois, à prix fixe. |
| **Ce que ça ne permet pas** | Presque tout ce qui compte : panier multi-produits robuste, port par pays, TVA par pays, facture séquentielle, statut de commande, suivi. Ces plugins réservent systématiquement le paramétrage fiscal et logistique à leur version payante — ce qui ramène à la contrainte de coût zéro. |

**Verdict. Écarté.** On accepterait une dépendance supplémentaire pour obtenir moins que l'option (a),
avec en plus un risque de mur commercial (la fonction manquante est derrière le paiement). C'est le
pire des deux mondes.

### 3.4 Option (c) — WooCommerce, maintenant ou plus tard

**Ce que c'est.** Le moteur e-commerce de WordPress. **Vérifié le 14 septembre 2026 : WooCommerce
11.1.0, gratuit, requiert WordPress 7.0+ et PHP 7.4+ (8.0+ recommandé).** La passerelle officielle
**WooCommerce PayPal Payments 4.1.3 est gratuite** (PayPal, cartes, Pay Later ; requiert WooCommerce 9.6+).

Le playbook interdit les **plugins payants**. WooCommerce n'en est pas un. Il n'a jamais été interdit.

| | |
|---|---|
| **Coût** | 0 € de licence. Coût réel : poids (WooCommerce charge ses propres scripts et styles — à contenir en les désactivant hors des pages boutique), surface de maintenance et de sécurité plus large, et surtout **le problème multilingue ci-dessous**. |
| **Ce que ça permet, immédiatement et sans code** | Panier multi-produits · **frais de port par zone et par pays** · **taux de TVA par pays, y compris le régime distance-selling UE** · facture et numérotation de commande séquentielle · e-mail de confirmation automatique · statuts de commande (en attente, en préparation, expédiée, terminée, remboursée) · page « Mon compte » et suivi de commande · gestion du stock · remboursement depuis l'admin · export des commandes pour la comptabilité. |
| **Ce que ça ne permet pas** | Rien de bloquant pour ce projet. |

**Le vrai problème, et il est sérieux.** **Vérifié le 14 septembre 2026 auprès de l'éditeur :
« Polylang ou Polylang Pro ne suffisent pas à créer une boutique WooCommerce multilingue. Il faut
l'add-on Polylang for WooCommerce », qui est payant.** Et l'alternative gratuite TranslatePress est
compatible WooCommerce mais **sa version gratuite ne gère qu'une seule langue de traduction** ;
au-delà, l'add-on « Extra Languages » est payant.

Autrement dit : **WooCommerce gratuit + 4 langues gratuit = impossible aujourd'hui.** Le brief l'avait
déjà identifié, et cette vérification le confirme. Ce n'est pas un détail : c'est le seul argument
solide contre WooCommerce sur ce projet.

### 3.5 Recommandation

**Aujourd'hui, à la mise en ligne : on ne code pas de paiement sur mesure. On garde le tiroir
« Ma sélection » vers WhatsApp, et on prépare la bascule.**

**Dès que le compte marchand PayPal existe et que la vente démarre vraiment : on passe à WooCommerce,
et on assume une réduction temporaire du périmètre multilingue de la boutique.**

Le raisonnement, sans dogme :

- L'option (a) demande d'écrire à la main commande, port par pays, TVA par pays, facture et suivi.
  C'est trois à cinq jours de développement, et surtout **du code maison sur de l'argent et de la
  comptabilité**, qu'il faudra maintenir seul, sans communauté, sans mise à jour de sécurité, et
  corriger nous-mêmes le jour où un taux de TVA change. C'est le genre de dette qui ne se voit pas au
  lancement et qui coûte très cher au premier contrôle ou au premier litige client.
- L'option (c) livre tout cela gratuitement, testé par des centaines de milliers de boutiques, avec
  les taux de TVA UE maintenus par l'écosystème.
- Ce qu'on perd avec (c), c'est la traduction native de la boutique en 4 langues. **Ce que l'on perd
  avec (a), c'est la fiabilité comptable.** Entre les deux, le choix est net : on ne bricole pas
  l'encaissement.

**Comment on récupère le multilingue sans payer** — trois pistes, à trancher par `velix-cms` au moment
de la bascule, par ordre de préférence :

1. **Boutique en français et en espagnol seulement** (les deux marchés réels : Fati vit en Espagne,
   sa cible est francophone et hispanophone), le reste du site en 4 langues via Polylang. Le catalogue
   est composé de noms de produits DXN largement intraduisibles et de prix en euros ; la perte réelle
   est faible. Techniquement : Polylang gère le site, WooCommerce reste sur la langue par défaut, et
   l'anglais et l'arabe pointent vers la boutique en français.
2. **Traduire les chaînes d'interface de WooCommerce** via les fichiers de langue officiels (gratuits
   et complets pour EN, ES, AR) et n'avoir qu'un seul jeu de fiches produit. Le tunnel d'achat est
   alors dans la langue du visiteur même si la fiche produit ne l'est pas.
3. **Payer l'add-on Polylang for WooCommerce** le jour où le chiffre d'affaires le justifie. Ce n'est
   pas interdit par la nature, c'est interdit par le budget d'aujourd'hui. Une licence se paie toute
   seule dès quelques centaines d'euros de ventes mensuelles, et cette décision appartient à la
   cliente, pas à l'agence.

### 3.6 Le point de bascule, écrit noir sur blanc

Un vrai moteur e-commerce devient **inévitable** au premier des événements suivants :

- **Le compte marchand PayPal est actif et une commande payée est passée sur le site.** À partir de
  là, il faut une facture, un statut et une trace. C'est le seuil principal.
- **On livre hors d'Espagne.** Frais de port par pays.
- **On approche 10 000 € de ventes annuelles vers les autres pays de l'UE.** TVA au taux du pays de la
  cliente, régime OSS : calculer cela à la main dans un thème est déraisonnable. Voir
  `_velix/reglementaire-espagne.md`.
- **Plus de cinq commandes par semaine.** Au-delà, la gestion manuelle par WhatsApp devient le
  goulot d'étranglement, pas le paiement.

**Pourquoi partir sur du sur-mesure aujourd'hui coûterait cher demain.** Chaque jour passé à écrire
notre propre notion de commande, de panier serveur et de facture est un jour qu'il faudra **jeter**
au moment de passer à WooCommerce — et pire, il faudra migrer les commandes déjà enregistrées dans un
format maison vers le format Woo, ce qui est un travail ingrat et risqué. Le sur-mesure ici n'est pas
un investissement, c'est une avance de trésorerie sur une dette.

### 3.7 Si le e-commerce prend vraiment

Si le volume dépasse ce que WooCommerce tenu à la main permet de gérer sereinement, **Shopify
redevient une option honnête** malgré son abonnement : il règle nativement le multilingue, la TVA UE,
les frais de port et la facturation. Ce document ne le recommande pas aujourd'hui, mais il refuse de
faire comme si cette porte n'existait pas.

### 3.8 Ce qu'on fait maintenant, concrètement

1. **Mise en ligne v1 sans paiement.** Tiroir « Ma sélection » → WhatsApp, avec la phrase honnête déjà
   en place (« le paiement en ligne n'est pas encore activé »). C'est le brief validé, et c'est mieux
   qu'un paiement à moitié fait.
2. **Ne rien coder de PayPal dans le thème.** Aucune ligne. Tout ce qui serait écrit serait jeté.
3. **Préparer la bascule dès maintenant**, sans coût : les 19 produits ont déjà un titre, un extrait,
   une description, un visuel, un prix `_fati_prix` et une catégorie. C'est exactement ce que Woo
   importe. L'import se fera par CSV via l'importateur natif de WooCommerce, ou par un script WP-CLI
   qui lit les CPT `produit` et crée les `product` — le mapping est direct.
4. **Conserver le CPT `produit` après la bascule**, en `noindex`, ou le rediriger en 301 vers les URL
   Woo. Voir section 8 : c'est le seul endroit où la bascule touche au référencement.

---

## 4. Objets métier : ce qui existe, ce qui manque

Inventaire honnête, à jour du code lu le 14 septembre 2026.

| Objet métier | État | Où |
|---|---|---|
| Produit (nom, description, visuel, prix, catégorie) | **Existe** | CPT `produit`, `_fati_prix` |
| Catégorie de produit | **Existe** | Taxonomie `categorie_produit` |
| Formation | **Existe** | CPT `formation` |
| Panier / sélection | **Existe, partiellement** | Tiroir JavaScript, `Map` en mémoire côté navigateur, total indicatif. Aucune persistance serveur, aucun contrôle du montant. Suffisant pour composer un message, insuffisant pour encaisser. |
| Contenu éditorial du site | **Existe** | `fati_options_schema()` |
| **Commande** | **Manque** | Aucun objet. Ni numéro, ni date, ni contenu, ni montant, ni historique. |
| **Adresse de livraison** | **Manque** | Aucun champ nulle part. |
| **Frais de port par zone** | **Manque** | Aucune table zone → tarif. Le total du tiroir est un total produits, hors livraison, et le dit. |
| **Statut de commande** | **Manque** | Aucun cycle de vie (payée, préparée, expédiée, livrée, remboursée). |
| **Numéro de facture séquentiel** | **Manque** | Aucune numérotation. C'est l'objet le plus sensible : une numérotation doit être continue et sans trou, ce qui ne s'improvise pas. |
| **Taux de TVA par pays** | **Manque** | Aucun calcul de TVA. Les prix sont affichés « indicatifs, hors livraison ». |
| **Conservation des justificatifs** | **Manque** | Aucun archivage de facture. La durée légale de conservation relève de `_velix/reglementaire-espagne.md` ; techniquement, il faut un stockage durable et sauvegardé des PDF ou de leurs données sources. |
| **E-mail de confirmation** | **Manque** | Aucun envoi transactionnel. Voir aussi section 9 : WordPress envoie mal les e-mails sans SMTP authentifié. |
| **Suivi de commande côté cliente** | **Manque** | Aucun espace client. |
| **Stock** | **Manque** | Aucun décompte. Fati expédie de son stock propre : sans décompte, elle peut vendre ce qu'elle n'a plus. |
| Client / compte | **Manque, et volontairement** | Hors périmètre v1. |

**Onze objets manquants sur seize.** C'est la mesure exacte de l'écart entre le site actuel et une
boutique. Et c'est l'argument décisif de la section 3 : WooCommerce fournit ces onze objets sans écrire
une ligne ; le sur-mesure impose de les écrire tous les onze, y compris la numérotation de facture.

---

## 5. Arborescence et URLs

Permaliens `/%postname%/`, vérifiés en local. Domaine cible : `fatichanelya.com` (à confirmer par la
cliente — voir section 8). Toutes les URL ci-dessous portent une barre oblique finale, conformément à
la configuration WordPress en place ; cette politique ne doit pas changer après mise en ligne.

### Français — langue par défaut, à la racine

| Page | URL | Type |
|---|---|---|
| Accueil | `/` | Page « Accueil » en page d'accueil statique (`front-page.php`) |
| Boutique | `/produits/` | Archive CPT `produit` |
| Catégorie | `/categorie-produit/alimentation-boissons/` | Taxonomie |
| | `/categorie-produit/beaute-soin/` | |
| | `/categorie-produit/complements/` | |
| | `/categorie-produit/soin-personnel/` | |
| Fiche produit | `/produits/<slug-produit>/` | 19 URL |
| Formations | `/formations/` | Archive CPT `formation` |
| Fiche formation | `/formations/<slug-formation>/` | 4 URL |
| Mentions légales | `/mentions-legales/` | Page |
| Confidentialité | `/confidentialite/` | Page |
| Livraison & retours | `/livraison-retours/` | Page |
| Recherche | `/?s=` | `noindex` |
| 404 | — | `404.php` |

À propos, FAQ et Contact sont des **sections de la page d'accueil** (`about.php`, `faq.php`,
`contact.php`), pas des pages autonomes. C'est cohérent avec la maquette v3 validée. Conséquence SEO
assumée : on ne vise pas de mot-clé propre sur « à propos » ou « contact ». Si `velix-ux` décide plus
tard de les autonomiser, ce sont des pages nouvelles — aucune redirection à prévoir, rien n'existe
encore à ces adresses.

### Ménage à faire avant mise en ligne

Deux contenus WordPress par défaut traînent en base :

- Page 2 `page-d-exemple` — **à supprimer** (publiée, donc indexable).
- Page 3 `politique-de-confidentialite` — brouillon WordPress, doublon de `/confidentialite/`.
  **À supprimer** pour éviter deux pages de confidentialité concurrentes.

Un article « Bonjour tout le monde ! » et un commentaire par défaut sont probablement présents aussi :
à vérifier et à supprimer.

### Multilingue — Polylang, sous-répertoires

**Structure retenue :** le français à la racine, les autres langues en sous-répertoire.
Réglage Polylang : *« Le nom du répertoire dans l'URL »*, avec l'option **« Masquer l'information de
langue par défaut dans l'URL » activée**. C'est ce qui garde `/produits/` en français plutôt que
`/fr/produits/`.

| Langue | Code | Préfixe | Exemple |
|---|---|---|---|
| Français | `fr` | aucun | `/produits/` |
| Espagnol | `es` | `/es/` | `/es/productos/` |
| Anglais | `en` | `/en/` | `/en/products/` |
| Arabe | `ar` | `/ar/` | `/ar/<slug>/` — **RTL** |

**Traduction des slugs.** La traduction des slugs de CPT et de taxonomie (`produits` → `productos` →
`products`) est une fonction de **Polylang Pro**. En version gratuite, le slug d'archive reste
`produits` dans toutes les langues : `/es/produits/`, `/en/produits/`. Ce n'est pas bloquant — l'URL
reste valide, unique et indexable — mais il faut le savoir et ne pas le promettre à la cliente.
En revanche, **le slug de chaque fiche produit et de chaque page est bien traduisible gratuitement**,
puisque c'est le `post_name` d'une traduction. C'est là que se joue l'essentiel du bénéfice SEO.

**Ce que Polylang gratuit couvre bien, et qui est déjà câblé** dans `inc/polylang.php` :
CPT `produit` et `formation` déclarés traduisibles, taxonomie `categorie_produit` déclarée traduisible,
et tous les champs de « Contenus du site » exposés à l'écran *Traductions des chaînes* via
`pll_register_string()`. Les champs de type `url`, `image` et `tel` sont volontairement exclus. Les
menus se traduisent nativement (un menu par langue et par emplacement : 3 emplacements × 4 langues =
12 menus à créer).

**Version vérifiée le 14 septembre 2026 : Polylang 3.8.9, requiert WordPress 6.5+ et PHP 7.4+.**
Compatible avec l'installation en place.

**Arabe et RTL.** Polylang pose `dir="rtl"` sur `<html>` et WordPress charge `style-rtl.css` quand
il existe. `inc/polylang.php` ajoute déjà une classe `is-rtl` au corps. Il reste à produire la feuille
RTL du thème et à la tester **à 320 px**, largeur où les débordements RTL apparaissent en premier.
Tâche pour `velix-ux` / `velix-qa`. **Non mesuré à ce jour.**

**Ordre de bataille recommandé :** ne publier une langue que lorsqu'elle est relue par la cliente. Une
langue à moitié traduite est pire que pas de langue du tout — Polylang permet de créer une langue sans
l'afficher dans le sélecteur tant qu'elle n'est pas prête.

### Navigation

**Menu principal** (`principal`) — 4 entrées aujourd'hui :
Boutique (`/produits/`) · Formations (`/formations/`) · À propos (ancre `#about`) · Contact (ancre `#contact`).
Plus le sélecteur de langue et le bouton « Ma sélection », qui ne sont pas des entrées de menu.

**Pied — Navigation** (`pied_nav`) : Boutique · Formations · Contact.

**Pied — Informations** (`pied_infos`) : Mentions légales · Confidentialité · Livraison & retours,
plus le lien TikTok. La mention santé obligatoire est affichée par le pied de page depuis l'option
`mention_sante`, pas par un menu.

À la bascule WooCommerce, deux entrées s'ajoutent au pied : Panier et Mon compte, plus les CGV
(nouvelle page `/cgv/`), dont le contenu relève de `_velix/reglementaire-espagne.md`.

---

## 6. Carte des intentions de recherche

Une intention, une page. Aucune page n'est créée pour une intention qui n'existe pas.

| Intention du visiteur | Page unique qui y répond | Nature |
|---|---|---|
| « Qui est Fatichanelya ? » (recherche de marque, depuis le lien TikTok en bio) | `/` | Navigationnelle — **c'est de loin le premier trafic attendu** |
| « Quels produits vend-elle, à quel prix ? » | `/produits/` | Commerciale |
| « <nom exact du produit DXN> » (ex. « Morinzhi », « Lingzhi Black Coffee ») | `/produits/<slug>/` | Transactionnelle, longue traîne — **le gisement SEO réel du catalogue** |
| « compléments / soins / boissons bien-être » par famille | `/categorie-produit/<slug>/` | Commerciale |
| « formation e-commerce / vente / IA en français » | `/formations/` | Commerciale |
| « <nom exact de la formation> » | `/formations/<slug>/` | Transactionnelle |
| « livraison, délais, retours, rétractation » | `/livraison-retours/` | Informationnelle — **et c'est une objection majeure du brief, pas seulement du SEO** |
| Mentions légales / vie privée | `/mentions-legales/`, `/confidentialite/` | Obligation, `noindex` acceptable |

**Trois remarques de méthode.**

1. **Le trafic viendra d'abord de TikTok, pas de Google.** Le gérant l'a confirmé : les abonnés
   d'abord, la publicité payante ensuite. Le SEO est un second temps. Conséquence concrète : la page
   d'accueil doit répondre en moins d'une seconde sur un mobile en 4G, parce que c'est un public qui
   arrive par un lien en bio et qui repart si ça rame. La performance est ici un levier d'acquisition
   avant d'être un levier SEO.
2. **La publicité payante impose une mesure**, donc un traceur, donc un bandeau de consentement dans
   l'UE, et probablement un pixel publicitaire. Aucun n'est installé aujourd'hui. Le choix de l'outil
   et la conformité du consentement sont à traiter avant la première campagne, en coordination avec
   `_velix/reglementaire-espagne.md`. Contrainte technique à poser dès maintenant : **aucun script de
   mesure ne se déclenche avant consentement**, et le bandeau ne doit pas décaler la mise en page
   (risque direct sur le CLS).
3. **Pas de page « avis » ni de balisage `Review`** tant qu'il n'existe aucun avis réel. Règle
   d'agence, et risque de sanction manuelle.

---

## 7. Métadonnées et données structurées

Le socle existe déjà dans `inc/seo.php`, sans plugin. C'est un bon choix pour une quarantaine d'URL :
un plugin SEO complet chargerait des assets sur chaque page sans rien apporter ici.

### Ce qui est en place et qui fonctionne

- `<title>` par `add_theme_support( 'title-tag' )`.
- `<meta name="description">` : extrait, sinon début du contenu, sinon `hero_intro`, tronqué à
  157 caractères.
- `<link rel="canonical">` auto-référent.
- Open Graph (`type`, `site_name`, `locale`, `title`, `description`, `url`, `image`) et
  `twitter:card`.
- JSON-LD : `Person` (Fati), `WebSite`, `Product` avec `Offer` en EUR sur la fiche produit, `FAQPage`
  sur l'accueil. Aucun `Review`, aucune note — conforme à la règle d'agence.

### Quatre corrections à apporter avant mise en ligne

Ce sont des défauts réels, lus dans le code, à confier à `velix-perf-seo` :

1. **Le canonical inclut les paramètres d'URL.**
   `home_url( add_query_arg( array() ) )` reconstruit l'URL courante **avec** sa chaîne de requête.
   Une arrivée depuis une campagne (`?utm_source=tiktok`) produit donc un canonical avec l'`utm`, ce
   qui est exactement ce que le canonical doit éliminer. Vu que l'acquisition passera par TikTok puis
   par de la publicité payante, ce défaut est **quasi certain de se déclencher en production**.
   Correction : construire le canonical depuis la requête WordPress (`get_permalink()`,
   `get_post_type_archive_link()`, `get_term_link()`, `home_url('/')`), sans paramètres.
2. **`home_url()` appliqué à un chemin déjà absolu** dans la même expression : à revoir en même temps
   que le point 1.
3. **`hreflang` : le bloc est vide.** La fonction se contente de vérifier que Polylang existe puis
   sort sans rien écrire. Polylang pose ses propres `hreflang` quand il est actif, donc ce ne sera pas
   un trou une fois le plugin installé — mais il faut **vérifier en production** que les balises sont
   bien là, réciproques, et complétées par un `x-default` pointant vers le français. Polylang ne pose
   pas toujours `x-default` : si absent, l'ajouter à la main dans `inc/seo.php`.
4. **`og:image` absent des fiches sans visuel.** Le repli sur `hero_image` existe ; s'assurer qu'une
   image Open Graph 1200×630 par défaut est bien définie, sinon les partages WhatsApp et TikTok
   sortiront sans visuel. Or WhatsApp est le canal principal de ce projet.

### Sitemap

**WordPress génère nativement `/wp-sitemap.xml` depuis la version 5.5.** Aucun plugin nécessaire.
À faire :

- Exclure du sitemap ce qui n'a pas à être indexé : auteurs (déjà neutralisés par `security.php`),
  et les pages légales si on les met en `noindex`.
- Vérifier après installation de Polylang que chaque langue apparaît. Polylang complète le sitemap
  natif ; c'est **à vérifier en production**, pas à supposer.
- `robots.txt` : autoriser le site, interdire `/wp-admin/` sauf `admin-ajax.php`, déclarer la ligne
  `Sitemap: https://fatichanelya.com/wp-sitemap.xml`.
- **Retirer tout `noindex` de préproduction** au moment de la bascule. C'est l'erreur de mise en ligne
  la plus fréquente et la plus coûteuse.

### Réglage WordPress à vérifier en production

« Réglages → Lecture → Demander aux moteurs de recherche de ne pas indexer ce site » : **coché en
préproduction, décoché en production.** À inscrire dans la checklist de `velix-deploy`.

---

## 8. Redirections et bascule de domaine

### L'URL actuelle : `https://fatichanelya.flexdesign.chatgpt.site/`

**Vérifié le 14 septembre 2026 : cette URL répond `HTTP/2 401`.** Elle est protégée par une
authentification (Cloudflare en frontal, `cache-control: no-store`). Conséquence directe et
rassurante :

- **Elle n'est pas accessible publiquement, donc pas indexable, donc elle ne reçoit aucun trafic de
  recherche organique.**
- **Il n'y a donc aucun capital SEO à préserver, et aucun plan de redirection 301 à construire depuis
  cet ancien site.** La règle d'agence « aucune URL qui reçoit du trafic ne change sans 301 » ne
  s'applique pas ici, faute de trafic. C'est une bonne nouvelle : la migration est propre.

**Ce qu'il faut néanmoins faire au moment de la bascule** — le vrai risque n'est pas le SEO, c'est le
lien mort chez les visiteurs :

1. **Identifier où cette URL a été diffusée.** Si elle a servi de lien en bio TikTok ou a été envoyée
   par WhatsApp à des abonnées, des personnes réelles la possèdent. Question à poser à la cliente.
2. **Mettre à jour le lien en bio TikTok vers le domaine définitif**, avant même la bascule technique.
   C'est le geste le plus important du lancement : c'est la source de trafic n° 1.
3. **Si la plateforme le permet, poser une redirection 301 de `fatichanelya.flexdesign.chatgpt.site`
   vers `https://fatichanelya.com/`.** Une redirection vers l'accueil est acceptable **ici seulement**,
   parce que l'ancien site était un fichier HTML d'une seule page : il n'y a pas d'URL profonde à faire
   correspondre. Ce n'est pas une entorse à la règle « jamais de redirection globale vers l'accueil »,
   c'est une correspondance un-vers-un qui tombe sur l'accueil.
4. **Si la plateforme ne permet pas de redirection, fermer la préversion** plutôt que de laisser deux
   sites vivre en parallèle. Un site de démonstration oublié finit toujours par être indexé et par
   dupliquer le contenu.

### Règles de canonicalisation à poser dès la mise en ligne

Elles ne coûtent rien le jour 1 et sont douloureuses à corriger plus tard :

- **Un seul protocole : HTTPS.** Tout le HTTP en 301 vers HTTPS.
- **Un seul hôte.** Choisir apex (`fatichanelya.com`) ou `www`, et rediriger l'autre en 301.
  Recommandation : l'apex, plus court, plus lisible dans une bio TikTok.
- **Une seule politique de barre oblique finale** : avec, conformément à WordPress. `/produits` →
  301 → `/produits/`, ce que WordPress fait nativement.
- `home` et `siteurl` à mettre à jour (aujourd'hui `http://localhost/websites/fatichanelya.com`), ainsi
  que les URL absolues en base après import. Utiliser `wp search-replace` avec `--dry-run` d'abord —
  jamais un `SELECT`/`UPDATE` SQL brut, qui casse les données sérialisées.

### Redirections internes à prévoir plus tard

| Quand | Ce qu'il faut rediriger |
|---|---|
| Bascule vers WooCommerce | `/produits/<slug>/` → `/boutique/<slug>/` (ou le slug Woo retenu), une 301 par produit, 19 lignes. **Alternative préférable : forcer WooCommerce à utiliser le slug `produits`**, ce qui supprime toute redirection. À trancher par `velix-cms`, et c'est l'option à privilégier. |
| Autonomisation d'À propos / FAQ / Contact | Aucune redirection : ce sont des ancres aujourd'hui, pas des URL. |
| Traduction des slugs | Si Polylang Pro est acquis un jour, `/es/produits/` → `/es/productos/` en 301. |

Les redirections se posent dans `.htaccess` ou dans le mu-plugin, **pas dans un plugin de
redirection** : quelques lignes ne justifient pas une dépendance.

---

## 9. Hébergement, environnements, déploiement

### Offre recommandée

**Un hébergement mutualisé WordPress chez un hébergeur européen, avec datacenter en Espagne ou en
France, à environ 5 à 12 € HT par mois.** Le domaine s'ajoute, autour de 10 à 15 € par an.

Le cahier des charges est plus important que la marque. Exiger :

| Exigence | Pourquoi |
|---|---|
| **PHP 8.2 ou 8.3**, sélectionnable | Le local tourne en 8.4 ; WooCommerce recommande 8.0+. Une version imposée en 7.x est rédhibitoire. |
| **HTTPS gratuit et auto-renouvelé** (Let's Encrypt) | Non négociable. `security.php` alerte déjà si le site n'est pas en HTTPS. |
| **Datacenter en Europe, idéalement Espagne ou France** | La cible est en Espagne : la latence compte pour le LCP, et l'hébergement dans l'UE simplifie le volet données personnelles (voir `_velix/reglementaire-espagne.md`). |
| **MySQL 8 ou MariaDB 10.6+** | WordPress 7.1 et WooCommerce. |
| **Accès SSH et WP-CLI** | **Critère éliminatoire.** Sans SSH, pas de `wp search-replace`, pas de déploiement `git pull`, pas de sauvegarde scriptée. Un hébergeur sans SSH double la charge de maintenance. |
| **Git accessible depuis le serveur** | Pour le déploiement décrit plus bas. |
| **Sauvegardes automatiques quotidiennes côté hébergeur, avec restauration en autonomie** | La sauvegarde de l'hébergeur ne remplace pas la nôtre, elle la double. |
| **Envoi d'e-mail authentifié (SMTP), ou une adresse du domaine** | `mail()` en PHP part en indésirable. Dès qu'il y aura des confirmations de commande, c'est critique. |
| **Un espace de préproduction, ou au minimum un second sous-domaine + une seconde base** | Voir plus bas. |
| **HTTP/2 ou HTTP/3, compression Brotli ou gzip** | Performance, gratuit chez tout hébergeur sérieux. |

**Pourquoi mutualisé et pas un VPS.** Personne ne va administrer un serveur pour ce projet. Un
mutualisé infogéré met les mises à jour système et les sauvegardes chez l'hébergeur, ce qui est
exactement ce qu'il faut quand la maintenance est légère. Un VPS deviendrait pertinent si le trafic
explosait après les campagnes payantes — ce serait un bon problème, à retraiter à ce moment-là.

**Point d'attention publicité payante.** Une campagne qui fonctionne envoie des pics de trafic sur
quelques heures. Vérifier avant la première campagne que l'offre ne facture pas à l'excès de trafic et
que le cache est bien actif — un site en cache tient un pic sans difficulté, un site sans cache tombe.

### Cache

1. **Cache de pages : WP Super Cache.** **Vérifié le 14 septembre 2026 : version 3.1.3, requiert
   WordPress 6.8+ et PHP 7.4+, plus d'un million d'installations, maintenu par Automattic.** Gratuit,
   sans version payante, donc sans mur commercial. Il sert des fichiers HTML statiques aux visiteurs
   non connectés, ce qui représente la quasi-totalité du trafic ici.
   *Si l'hébergeur fournit son propre cache serveur (LiteSpeed, Varnish, NGINX FastCGI), le préférer
   et ne pas empiler deux caches — c'est une cause classique de pages figées et de bugs invisibles.*
2. **Cache navigateur et compression** : en-têtes `Expires` et `Cache-Control` longs sur CSS, JS,
   images et polices, via `.htaccess`. Gratuit, immédiat.
3. **Pas de plugin d'optimisation d'assets.** Le thème charge un seul CSS et un seul JS écrits à la
   main. Un plugin de minification n'apporterait presque rien et introduirait un risque de casse.
   Règle du brief, confirmée ici.
4. **Images** : les visuels produits sont déjà en WebP. Pour la suite, redimensionner **avant**
   téléversement plutôt que d'installer un plugin d'optimisation. À écrire dans le guide de la cliente.
5. **Attention au cache avec Polylang et avec WooCommerce** : panier, compte et sélection ne doivent
   jamais être servis depuis le cache. WP Super Cache sait exclure ces pages ; c'est un réglage
   obligatoire au moment de la bascule, pas une option.

### Sauvegarde

1. **UpdraftPlus.** **Vérifié le 14 septembre 2026 : version 1.26.7, plus de 3 millions
   d'installations.** La version gratuite permet l'envoi vers **Google Drive, Dropbox, Amazon S3 ou
   FTP** — c'est le point qui compte, car une sauvegarde stockée sur le serveur qu'elle sauvegarde ne
   sauvegarde rien.
   Rythme : **base de données quotidienne, fichiers hebdomadaires, 30 jours de rétention**, destination
   Google Drive.
   Dès que des commandes existeront, **passer la base en sauvegarde quotidienne avec une rétention plus
   longue** : perdre un jour de contenu est ennuyeux, perdre un jour de commandes est grave.
2. **Sauvegarde de l'hébergeur** en second filet, indépendante de WordPress. Si UpdraftPlus casse, elle
   reste.
3. **Une restauration doit être testée avant la mise en ligne**, pas le jour de l'incident. Une
   sauvegarde jamais restaurée n'est pas une sauvegarde. Tâche pour `velix-deploy`.
4. Le code est déjà sauvegardé par Git. Ce qui n'est **pas** dans Git — et donc ce que les sauvegardes
   doivent absolument couvrir — c'est la **base de données** et `wp-content/uploads/`.

### Sécurité

Le mu-plugin `security.php` couvre déjà, sans plugin : en-têtes `X-Content-Type-Options`,
`Referrer-Policy`, `X-Frame-Options`, `Permissions-Policy` ; masquage de la version ; blocage de
l'énumération des comptes par `?author=` et par l'API REST ; XML-RPC désactivé ; message d'erreur de
connexion générique.

À ajouter :

1. **Wordfence, version gratuite.** **Vérifié le 14 septembre 2026 : version 9.0.1.** Point d'honnêteté
   à dire à la cliente : **en version gratuite, les règles de pare-feu et les signatures de malware
   arrivent avec 30 jours de retard** sur la version payante. C'est une protection réelle mais décalée.
   Elle apporte surtout ici la limitation des tentatives de connexion et la détection de fichiers
   modifiés, qui sont les deux besoins concrets d'un petit site WordPress.
   *Alternative plus légère si Wordfence pèse trop sur les performances mesurées : un simple plugin de
   limitation des tentatives de connexion, plus une protection `.htaccess` sur `wp-login.php`.
   À trancher après la mesure Lighthouse, pas avant.*
2. **`wp-config.php` en production** : `DISALLOW_FILE_EDIT` à `true` (`security.php` le réclame déjà
   par une alerte admin), `WP_DEBUG` à `false`, `WP_DEBUG_DISPLAY` à `false`, clés de sécurité
   régénérées — **différentes de celles du local**.
3. **Comptes** : un seul administrateur, identifiant qui n'est ni `admin` ni `fati`, mot de passe long
   et unique, **authentification à deux facteurs activée** (Wordfence la fournit gratuitement). C'est
   la mesure au meilleur rapport effort/bénéfice de toute cette liste.
4. **Mises à jour** : mineures de WordPress en automatique, majeures et plugins en manuel après
   sauvegarde. À inscrire dans le contrat de maintenance.
5. **CSP** : à poser en `Report-Only` d'abord, comme le note déjà le code. Après l'ajout d'un traceur
   publicitaire, pas avant — sinon il faudra la refaire.

Conformément à la règle d'agence, **ce site n'est pas déclaré « sécurisé »**. Ce qui précède est ce qui
est en place et ce qui est prévu ; aucun test d'intrusion n'a été réalisé.

### Environnements

| Environnement | Où | Base | Indexation | Usage |
|---|---|---|---|---|
| **Local** | XAMPP, `http://localhost/websites/fatichanelya.com` | `fatichanelya` | Sans objet | Développement. **Fait et fonctionnel.** |
| **Préproduction** | `preprod.fatichanelya.com` ou sous-domaine de l'hébergeur | Base distincte | **`noindex` + protection par mot de passe HTTP** | Recette client, validation des traductions par Fati, mesure Lighthouse sur une machine comparable à la production |
| **Production** | `https://fatichanelya.com` | Base de production | Indexable | Le site |

La double protection de la préproduction (`noindex` **et** mot de passe HTTP) n'est pas une ceinture
et bretelles inutile : le `noindex` seul se perd au moment d'une copie de base entre environnements,
et c'est comme cela qu'une préproduction se retrouve indexée.

### Déploiement

Dépôt : `https://github.com/Sofianbeddi/fatichanelya.com.git`, branche `main`.
`.gitignore` vérifié : le cœur WordPress, `wp-config.php`, `wp-content/uploads/`, les plugins tiers et
les thèmes autres que `fatichanelya` ne sont pas versionnés. **C'est la bonne configuration.**

**Ce qui est versionné, et qui se déploie par Git :** le thème `fatichanelya`, le mu-plugin
`fatichanelya-core`, les outils de `tools/`, la documentation `_velix/`.

**Ce qui ne l'est pas, et qui se déploie autrement :**

- **Le cœur WordPress** : installé une fois par l'hébergeur, mis à jour par l'admin.
- **Les plugins** (Polylang, WP Super Cache, UpdraftPlus, Wordfence) : installés depuis l'admin de
  chaque environnement. Leur liste et leurs versions doivent être notées dans
  `_velix/deploiement.md` — sinon la préproduction et la production divergent sans qu'on le voie.
- **Les médias** (`uploads/`) : transférés une fois par SFTP au lancement, puis téléversés par Fati.
- **La base de données** : migrée une fois du local vers la préproduction, puis de la préproduction
  vers la production, par `wp db export` / `wp db import` suivi de
  `wp search-replace 'http://localhost/websites/fatichanelya.com' 'https://fatichanelya.com'`
  **avec `--dry-run` d'abord**. Après la mise en ligne, la base de production ne redescend plus jamais
  vers le local en écrasement : c'est elle qui contient les vraies commandes et les vrais contenus.

**Procédure de déploiement, à formaliser par `velix-deploy`** :

1. Développement en local, commit sur une branche, fusion dans `main`.
2. `git pull` sur la préproduction (via SSH), recette, capture aux cinq largeurs.
3. `git pull` sur la production, après sauvegarde UpdraftPlus déclenchée à la main.
4. Vider le cache WP Super Cache. Vérifier l'accueil, une fiche produit, une page légale, dans les
   langues publiées.

**Point de vigilance des permaliens.** `cpt.php` régénère les règles de réécriture une seule fois par
version de `FATI_CORE_VERSION`. Après chaque déploiement qui touche aux CPT, **incrémenter cette
constante**, faute de quoi les nouvelles URL renverront des 404 en production alors que tout
fonctionnait en local. C'est exactement le genre de bug qui ne se voit pas avant la mise en ligne.

**Autre point de vigilance, déjà rencontré sur ce projet :** les droits de fichiers. En local, un
`700/600` a produit une page blanche parce qu'Apache tourne sous un autre utilisateur. En production,
viser `755` pour les dossiers et `644` pour les fichiers, et `wp-config.php` en `600` si l'hébergeur
le permet.

---

## 10. Ce qui reste à faire, et par qui

| Sujet | Qui | Pourquoi c'est ici |
|---|---|---|
| **Mesurer Lighthouse sur le thème WordPress** | `velix-perf-seo` | Jamais fait. La cible est ≥ 95 en mobile. Mesurer sur la préproduction, pas en local XAMPP. |
| Corriger le canonical avec paramètres d'URL | `velix-perf-seo` | Défaut réel, déclenché par les `utm` des campagnes TikTok |
| Vérifier `hreflang` et `x-default` après installation de Polylang | `velix-perf-seo` | Le bloc est vide dans `seo.php` ; Polylang doit prendre le relais — à constater, pas à supposer |
| Installer Polylang, créer les 4 langues, 12 menus, traduire les chaînes | `velix-cms` | Aucune langue n'existe à ce jour |
| Feuille RTL du thème et test à 320 px | `velix-ux` / `velix-qa` | L'arabe est au périmètre, le RTL n'est pas testé |
| Supprimer les contenus WordPress par défaut | `velix-cms` | Page d'exemple publiée, brouillon de confidentialité en doublon |
| Aide contextuelle anti-allégation santé dans l'admin | `velix-cms` | Risque n° 1 du brief ; périmètre légal dans `reglementaire-espagne.md` |
| Bascule WooCommerce quand PayPal est actif | `velix-cms` + `velix-architecte` | Section 3. Décider alors du périmètre multilingue de la boutique |
| Choix de l'outil de mesure et bandeau de consentement | `velix-perf-seo` + `reglementaire-espagne.md` | Préalable à la publicité payante |
| Contenu des pages légales et CGV | cliente + son conseil | Pas du ressort de l'agence |

### Ce qui bloque encore, côté cliente

Repris du journal, avec ce que l'architecture y ajoute :

- **Nom de domaine et accès hébergement** — bloque tout ce document à partir de la section 8.
- **Numéro WhatsApp définitif** — sans lui, le seul appel à l'action du site ne fonctionne pas.
- **Compte marchand PayPal** — déclenche la bascule WooCommerce.
- **Pays livrés, délais et tarifs par pays** — nécessaires pour paramétrer les zones de livraison, et
  pour écrire `/livraison-retours/`.
- **Statut juridique, NIF, adresse** — pour les mentions légales et la facturation.
- **Traductions ES / EN / AR relues** — aucune langue ne se publie sans relecture de la cliente.
- **Photos réelles de Fati** — le portrait du hero est généré ; c'est un risque de marque, pas
  d'architecture, mais il reste ouvert.
