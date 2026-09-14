# Fatichanelya — site WordPress

Thème sur-mesure. Sans WooCommerce, sans page builder, sans plugin payant.

## Démarrer en local (XAMPP)

1. Installer WordPress dans ce dossier (`wp-admin/`, `wp-includes/`, `wp-config.php` ne sont pas versionnés).
2. Créer la base via phpMyAdmin, puis lancer l'assistant sur `http://localhost/websites/fatichanelya.com/`.
3. Importer le contenu : `bash tools/seed.sh`
4. Renseigner le numéro WhatsApp dans **Contenus du site → Général**.

## Ce qui est versionné

| Chemin | Rôle |
|---|---|
| `wp-content/themes/fatichanelya/` | Le thème : templates, CSS, JS, visuels |
| `wp-content/mu-plugins/` | Types de contenu, champs, réglages, SEO, sécurité |
| `tools/` | CSV du catalogue, visuels sources, script d'import |

Les mu-plugins portent le métier : le contenu survit à un changement de thème.

## Plugins à installer depuis l'admin

- **Polylang** (gratuit) — FR / EN / AR / ES. Les types de contenu sont déjà déclarés traduisibles.
- Une extension de cache et une de sauvegarde au moment de la mise en production.

## À finaliser avant la mise en ligne

Numéro WhatsApp · traductions EN/ES/AR · pages légales · visuel du DXN Lingzhi Black Coffee · compte marchand PayPal · domaine réel.
