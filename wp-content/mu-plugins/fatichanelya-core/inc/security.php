<?php
/**
 * Durcissement. Complète — ne remplace pas — la configuration serveur
 * et les en-têtes posés au niveau de l'hébergement en production.
 *
 * @package Fatichanelya
 */

defined( 'ABSPATH' ) || exit;

/** En-têtes de sécurité. La CSP reste à affiner en production, en Report-Only d'abord. */
add_action(
	'send_headers',
	function () {
		if ( is_admin() ) {
			return;
		}
		header( 'X-Content-Type-Options: nosniff' );
		header( 'Referrer-Policy: strict-origin-when-cross-origin' );
		header( 'X-Frame-Options: SAMEORIGIN' );
		header( 'Permissions-Policy: camera=(), microphone=(), geolocation=(), interest-cohort=()' );
	}
);

/** Masque la version de WordPress (générique, mais gratuit). */
remove_action( 'wp_head', 'wp_generator' );
add_filter( 'the_generator', '__return_empty_string' );

/** Coupe l'énumération des comptes via ?author=N et via l'API REST. */
add_action(
	'template_redirect',
	function () {
		if ( ! is_admin() && isset( $_GET['author'] ) ) {
			wp_safe_redirect( home_url( '/' ), 301 );
			exit;
		}
	}
);

add_filter(
	'rest_endpoints',
	function ( $endpoints ) {
		if ( ! is_user_logged_in() ) {
			unset( $endpoints['/wp/v2/users'], $endpoints['/wp/v2/users/(?P<id>[\d]+)'] );
		}
		return $endpoints;
	}
);

/** XML-RPC : inutile ici, et cible classique de bruteforce. */
add_filter( 'xmlrpc_enabled', '__return_false' );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );

/** Message d'erreur de connexion générique : ne dit pas si le compte existe. */
add_filter(
	'login_errors',
	function () {
		return __( 'Identifiants incorrects.', 'fatichanelya' );
	}
);

/** Rappels de configuration, visibles uniquement pour l'administrateur. */
add_action(
	'admin_notices',
	function () {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$todo = array();

		if ( ! fati_opt( 'whatsapp' ) ) {
			$todo[] = __( 'le numéro WhatsApp n\'est pas renseigné (Contenus du site > Général)', 'fatichanelya' );
		}
		if ( ! defined( 'DISALLOW_FILE_EDIT' ) || ! DISALLOW_FILE_EDIT ) {
			$todo[] = __( 'DISALLOW_FILE_EDIT n\'est pas activé dans wp-config.php', 'fatichanelya' );
		}
		if ( ! is_ssl() && ! in_array( wp_parse_url( home_url(), PHP_URL_HOST ), array( 'localhost', '127.0.0.1' ), true ) ) {
			$todo[] = __( 'le site n\'est pas servi en HTTPS', 'fatichanelya' );
		}

		if ( $todo ) {
			echo '<div class="notice notice-warning"><p><strong>Fatichanelya —</strong> à finaliser : ' . esc_html( implode( ' · ', $todo ) ) . '</p></div>';
		}
	}
);
