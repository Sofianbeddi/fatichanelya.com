<?php
/**
 * Déclarations du thème.
 *
 * @package Fatichanelya
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'after_setup_theme',
	function () {
		load_theme_textdomain( 'fatichanelya', FATI_DIR . '/languages' );

		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ) );
		add_theme_support( 'responsive-embeds' );
		add_theme_support( 'editor-styles' );
		add_editor_style( 'assets/css/main.css' );

		// Tailles calées sur les largeurs réellement rendues : pas d'image surdimensionnée.
		add_image_size( 'fati-produit', 620, 620, true );
		add_image_size( 'fati-portrait', 620, 1102, true );
		add_image_size( 'fati-hero', 1400, 876, true );
		add_image_size( 'fati-paysage', 1000, 750, true );
		add_image_size( 'fati-vertical', 520, 924, true );
		add_image_size( 'fati-avatar', 240, 240, true );
		// Vignette ronde des catégories : servie en 320 px pour un affichage
		// de 160, donc nette sur les écrans à haute densité.
		add_image_size( 'fati-categorie', 320, 320, true );

		register_nav_menus(
			array(
				'principal'   => __( 'Menu principal', 'fatichanelya' ),
				'pied_nav'    => __( 'Pied de page — Navigation', 'fatichanelya' ),
				'pied_infos'  => __( 'Pied de page — Informations', 'fatichanelya' ),
			)
		);
	}
);

/** Une seule taille servie par image : pas de déclinaison inutile sur le disque. */
add_filter(
	'intermediate_image_sizes_advanced',
	function ( $sizes ) {
		unset( $sizes['medium_large'], $sizes['1536x1536'], $sizes['2048x2048'] );
		return $sizes;
	}
);

/**
 * Gabarit d'une page traduite (1.7.0). Les pages À propos, Contact et Panier
 * ont leur gabarit par slug (`page-a-propos.php`…) ; leur traduction arabe a
 * un slug arabe, donc elle prendrait `page.php`. Elle reçoit ici le gabarit
 * de son original, quel que soit son slug et sans dépendre d'une méta.
 */
add_filter(
	'page_template',
	function ( $template ) {
		if ( ! function_exists( 'pll_get_post' ) || ! function_exists( 'pll_default_language' ) ) {
			return $template;
		}
		$id     = get_queried_object_id();
		$source = $id ? pll_get_post( $id, pll_default_language() ) : 0;
		if ( ! $source || $source === $id ) {
			return $template;
		}
		$original = get_post( $source );
		$fichier  = $original ? locate_template( 'page-' . $original->post_name . '.php' ) : '';
		return $fichier ? $fichier : $template;
	}
);
