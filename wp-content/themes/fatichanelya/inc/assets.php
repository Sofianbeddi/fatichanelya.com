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
				'whatsapp' => fati_opt( 'whatsapp' ),
				'i18n'     => array(
					'added'     => __( '%s ajouté à votre sélection', 'fatichanelya' ),
					'bag'       => __( 'Ma sélection : %d article', 'fatichanelya' ),
					'bags'      => __( 'Ma sélection : %d articles', 'fatichanelya' ),
					'shown_one' => __( '%d produit affiché', 'fatichanelya' ),
					'shown'     => __( '%d produits affichés', 'fatichanelya' ),
					'none'      => __( 'Aucun produit affiché', 'fatichanelya' ),
					'waIntro'   => __( 'Bonjour Fati, voici ma sélection :', 'fatichanelya' ),
					'waTotal'   => __( 'Total indicatif :', 'fatichanelya' ),
					'waConfirm' => __( 'Pouvez-vous me confirmer la disponibilité et la livraison ?', 'fatichanelya' ),
					'waHello'   => __( 'Bonjour Fati, j\'aimerais échanger avec vous.', 'fatichanelya' ),
					'waQuestion'=> __( 'Bonjour Fati, j\'ai une question sur « %s ».', 'fatichanelya' ),
					'remove'    => __( 'Retirer %s de ma sélection', 'fatichanelya' ),
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
