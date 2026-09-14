# Journal — Fatichanelya

Une ligne par intervention. Les agents `velix-*` lisent ce dossier avant de travailler.

| Date | Agent | Ce qui a été fait | Livrable |
|---|---|---|---|
| 2026-09-14 | chef de projet | Dépôt relié à GitHub (`origin` → Sofianbeddi/fatichanelya.com), premier push de `main`. | — |
| 2026-09-14 | chef de projet | Lancement du pipeline `velix-nouveau`. Création de `_velix/`. Inventaire de la matière : synthèse projet client, maquette v3 validée (HTML + captures + Lighthouse 100/100/100/100), thème WordPress déjà converti (commit `514fb6c`), catalogue 19 produits + 4 formations en CSV, 18 visuels produits WebP, 8 visuels de pages, 4 photos TikTok, favicon SVG. | `journal.md` |
| 2026-09-14 | velix-brief | Brief créatif écrit à partir de la matière client, de la maquette v3 validée et des textes du thème. Formalise l'offre (19 produits, 4 formations, marque personnelle), la cible (diaspora marocaine en Espagne, à confirmer), le positionnement, 7 objections, l'inventaire des preuves (aucun témoignage ni chiffre disponible ; portrait du hero généré, favicon hors palette), le secteur MLM DXN / compléments / infopreneuriat avec la réglementation UE et Maroc, 10 hypothèses, 5 questions et 13 demandes client. Question bloquante pour l'architecte : stock propre ou renvoi vers la boutique DXN officielle. | `brief.md` |
| 2026-09-14 | chef de projet | Installation locale : WordPress 7.1 fr_FR, base `fatichanelya`, thème activé, `tools/seed.sh` corrigé (bash 3.2, `--field`, métas idempotentes, durée vide) et exécuté — 19 produits, 4 formations, 4 catégories, 3 menus, 4 pages, 26 visuels. Droits `700/600` corrigés en `755/644` : Apache tourne en `daemon` et ne pouvait lire ni le thème ni les mu-plugins, d'où une page blanche de 0 octet. `.htaccess` écrit. Captures 375 / 768 / 1440 : aucune erreur console, aucun débordement, aucune image cassée. | site local |

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
