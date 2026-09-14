# Journal — Fatichanelya

Une ligne par intervention. Les agents `velix-*` lisent ce dossier avant de travailler.

| Date | Agent | Ce qui a été fait | Livrable |
|---|---|---|---|
| 2026-09-14 | chef de projet | Dépôt relié à GitHub (`origin` → Sofianbeddi/fatichanelya.com), premier push de `main`. | — |
| 2026-09-14 | chef de projet | Lancement du pipeline `velix-nouveau`. Création de `_velix/`. Inventaire de la matière : synthèse projet client, maquette v3 validée (HTML + captures + Lighthouse 100/100/100/100), thème WordPress déjà converti (commit `514fb6c`), catalogue 19 produits + 4 formations en CSV, 18 visuels produits WebP, 8 visuels de pages, 4 photos TikTok, favicon SVG. | `journal.md` |
| 2026-09-14 | velix-brief | Brief créatif écrit à partir de la matière client, de la maquette v3 validée et des textes du thème. Formalise l'offre (19 produits, 4 formations, marque personnelle), la cible (diaspora marocaine en Espagne, à confirmer), le positionnement, 7 objections, l'inventaire des preuves (aucun témoignage ni chiffre disponible ; portrait du hero généré, favicon hors palette), le secteur MLM DXN / compléments / infopreneuriat avec la réglementation UE et Maroc, 10 hypothèses, 5 questions et 13 demandes client. Question bloquante pour l'architecte : stock propre ou renvoi vers la boutique DXN officielle. | `brief.md` |
| 2026-09-14 | velix-architecte | Architecture écrite. Socle WordPress + thème sur mesure + mu-plugin consigné (socles écartés justifiés : Astro, Next.js, Shopify, statique, page builder). Réponses du gérant intégrées : Fati expédie de son stock, PayPal à venir, livraison UE, acquisition TikTok puis publicité payante. **Recommandation centrale : ne coder aucun PayPal sur mesure ; basculer vers WooCommerce (gratuit, 11.1.0) dès la première commande payée.** Vérifié que la passerelle WooCommerce PayPal Payments 4.1.3 est gratuite, mais que **le multilingue de WooCommerce ne l'est pas** (Polylang for WooCommerce payant ; TranslatePress gratuit limité à une langue) — d'où une boutique FR/ES d'abord. Inventaire : 11 objets métier sur 16 manquent (commande, adresse, port par zone, statut, facture séquentielle, TVA par pays, justificatifs, e-mail, suivi, stock, compte). Arborescence et URLs figées en 4 langues (Polylang sous-répertoires, FR à la racine ; slugs `produits` / `formations` vérifiés). 4 défauts SEO réels relevés dans `seo.php`, dont un canonical qui embarque les `utm` — critique vu l'acquisition TikTok. Préversion `flexdesign.chatgpt.site` vérifiée : **HTTP 401, non indexable, aucun capital SEO à préserver**. Hébergement mutualisé UE 5-12 €/mois avec SSH éliminatoire ; cache, sauvegarde et sécurité en extensions gratuites nommées. | `architecture.md` |
| 2026-09-14 | chef de projet | Installation locale : WordPress 7.1 fr_FR, base `fatichanelya`, thème activé, `tools/seed.sh` corrigé (bash 3.2, `--field`, métas idempotentes, durée vide) et exécuté — 19 produits, 4 formations, 4 catégories, 3 menus, 4 pages, 26 visuels. Droits `700/600` corrigés en `755/644` : Apache tourne en `daemon` et ne pouvait lire ni le thème ni les mu-plugins, d'où une page blanche de 0 octet. `.htaccess` écrit. Captures 375 / 768 / 1440 : aucune erreur console, aucun débordement, aucune image cassée. | site local |
| 2026-09-14 | recherche réglementaire | Cadre espagnol et européen pour une vendeuse résidant en Espagne livrant dans l'UE : statut d'autónoma, TVA et guichet unique OSS, recargo de equivalencia, notification AESAN, allégations santé, cosmétiques, LSSI/RGPD/cookies, rétractation, MLM, publicité TikTok, Verifactu. Trois informations très répandues sont périmées : Verifactu reporté au 1ᵉʳ juillet 2027, plateforme ODR européenne fermée le 20 juillet 2025, et DXN Internacional Spain SLU (NIF B30877195) existe, ce qui place vraisemblablement Fati en revendeuse et non en importatrice. | `reglementaire-espagne.md` |
| 2026-09-14 | chef de projet | Document client rédigé et mis en PDF (15 pages) à partir du rapport réglementaire : langue simple, pas de jargon, 12 questions prêtes à envoyer en espagnol au gestor, tri bloquant / important / plus tard, et la liste de ce que Fati doit fournir. Déposé dans `04_DOCUMENTS_CLIENT/reglementaire/`. | PDF client |

## À fournir par le client

- Numéro WhatsApp définitif
- Compte marchand PayPal, devises, pays / tarifs / délais de livraison
- Catalogue DXN officiel (prix, stocks, variantes) — le CSV actuel est indicatif
- Visuel du DXN Lingzhi Black Coffee
- Textes juridiques : mentions légales, confidentialité, livraison & retours
- Traductions validées EN / ES / AR
- Avis clients réels (aucun sur le site tant qu'ils n'existent pas)
- Nom de domaine et accès hébergement
- Identifiants d'analytique, si souhaité
- **Tarifs et délais de livraison pays par pays** (UE) — nécessaires pour paramétrer les zones de livraison et rédiger `/livraison-retours/` *(ajouté par velix-architecte)*
- **Statut juridique, NIF et adresse** de Fati — mentions légales et facturation *(ajouté par velix-architecte)*
- **Où l'URL `fatichanelya.flexdesign.chatgpt.site` a-t-elle été diffusée ?** (lien en bio TikTok, messages WhatsApp) — détermine s'il faut une redirection au moment de la bascule *(ajouté par velix-architecte)*
