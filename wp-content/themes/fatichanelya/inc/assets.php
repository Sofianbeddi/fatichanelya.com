<?php
/**
 * Chargement des assets. Un seul CSS, un seul JS, versionnés par filemtime
 * pour que le cache navigateur se casse tout seul à chaque déploiement.
 *
 * @package Fatichanelya
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'wp_enqueue_scripts',
	function () {
		$css = '/assets/css/main.css';
		$js  = '/assets/js/main.js';

		wp_enqueue_style( 'fati', FATI_URI . $css, array(), fati_asset_version( $css ) );

		wp_enqueue_script(
			'fati',
			FATI_URI . $js,
			array(),
			fati_asset_version( $js ),
			array(
				'strategy'  => 'defer',
				'in_footer' => true,
			)
		);

		wp_localize_script(
			'fati',
			'FATI',
			array(
				// Chiffres seuls : c'est ce qu'attend wa.me. Sans numéro, les liens
				// WhatsApp du script retombent sur la page Contact.
				'whatsapp' => preg_replace( '/[^0-9]/', '', (string) fati_opt( 'whatsapp' ) ),
				'contact'  => fati_page_url( 'contact' ) ? fati_page_url( 'contact' ) : home_url( '/' ),
				// Sur la page Panier, l'icône de l'en-tête n'ouvre pas le tiroir :
				// il doublerait la page.
				'surPanier' => is_page( array( 'panier', 'ma-selection' ) ),
				'i18n'     => array(
					/* translators: nom du produit, puis quantité ajoutée. */
					'ajoute'    => __( '%s × %d', 'fatichanelya' ),
					'bag'       => __( 'Panier : %d article', 'fatichanelya' ),
					'bags'      => __( 'Panier : %d articles', 'fatichanelya' ),
					'dansPanier'  => __( '%d dans votre panier', 'fatichanelya' ),
					'shown_one' => __( '%d produit affiché', 'fatichanelya' ),
					'shown'     => __( '%d produits affichés', 'fatichanelya' ),
					'none'      => __( 'Aucun produit affiché', 'fatichanelya' ),
					'waIntro'   => __( 'Bonjour Fati, voici ma commande :', 'fatichanelya' ),
					'waTotal'   => __( 'Total indicatif :', 'fatichanelya' ),
					'waConfirm' => __( 'Pouvez-vous me confirmer la disponibilité et la livraison ?', 'fatichanelya' ),
					'waHello'   => __( 'Bonjour Fati, je souhaite commander.', 'fatichanelya' ),
					'remove'    => __( 'Retirer %s du panier', 'fatichanelya' ),
					'moins'     => __( 'Retirer un %s', 'fatichanelya' ),
					'plus'      => __( 'Ajouter un %s', 'fatichanelya' ),
					'quantite'  => __( 'Quantité de %s dans le panier', 'fatichanelya' ),
					'unite'     => __( '%s l\'unité', 'fatichanelya' ),
					// Produit sans prix en base : il reste commandable, mais il
					// n'entre pas dans le total et le dit.
					'aConfirmer'   => __( 'prix à confirmer', 'fatichanelya' ),
					'totalPartiel' => __( 'Total indicatif, hors articles à confirmer', 'fatichanelya' ),
				),
			)
		);

		// Le visuel du hero est le LCP : il se précharge, il ne se lazy-load jamais.
		$hero = fati_opt( 'hero_image' );
		if ( is_front_page() && $hero ) {
			add_action(
				'wp_head',
				function () use ( $hero ) {
					echo '<link rel="preload" as="image" href="' . esc_url( $hero ) . '" fetchpriority="high">' . "\n";
				},
				1
			);
		}
	},
	20
);

function fati_asset_version( $relative ) {
	$path = FATI_DIR . $relative;
	return file_exists( $path ) ? (string) filemtime( $path ) : FATI_VERSION;
}

/** Styles de l'éditeur : le client voit dans l'admin ce qu'il aura en façade. */
add_action(
	'admin_enqueue_scripts',
	function ( $hook ) {
		if ( in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
			wp_enqueue_style( 'fati-editor', FATI_URI . '/assets/css/main.css', array(), fati_asset_version( '/assets/css/main.css' ) );
		}
	}
);

/**
 * Préchargement de la police.
 *
 * La graisse normale porte tout le texte visible d'emblée : sans préchargement,
 * le premier rendu se fait dans la police de repli puis saute. La graisse grasse
 * peut attendre, elle ne sert qu'aux titres.
 *
 * Accroché directement sur `wp_head` : posé dans `wp_enqueue_scripts`, le lien
 * arriverait après que wp_head a dépassé cette priorité.
 */
add_action(
	'wp_head',
	function () {
		if ( is_admin() ) {
			return;
		}
		printf(
			'<link rel="preload" as="font" type="font/woff2" href="%s" crossorigin>' . "\n",
			esc_url( FATI_URI . '/assets/fonts/poppins-400.woff2' )
		);
	},
	1
);

/**
 * Icône d'onglet. Le fichier existait dans le thème sans être annoncé : le
 * navigateur demandait alors /favicon.ico, qui répondait 404 sur chaque page.
 * Une icône de site choisie dans l'admin (Apparence > Personnaliser) prime.
 */
add_action(
	'wp_head',
	function () {
		if ( has_site_icon() ) {
			return;
		}
		printf(
			'<link rel="icon" href="%s" type="image/svg+xml">' . "\n",
			esc_url( FATI_URI . '/assets/favicon.svg' )
		);
	},
	2
);
