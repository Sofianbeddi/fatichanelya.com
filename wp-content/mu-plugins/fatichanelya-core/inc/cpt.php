<?php
/**
 * Types de contenu et taxonomies.
 *
 * Pas de WooCommerce : un CPT « Produit » suffit tant que le paiement en ligne
 * n'est pas activé, et Polylang traduit nativement les CPT en version gratuite.
 *
 * @package Fatichanelya
 */

defined( 'ABSPATH' ) || exit;

add_action( 'init', 'fati_register_content_types' );

function fati_register_content_types() {

	register_post_type(
		'produit',
		array(
			'label'         => __( 'Produits', 'fatichanelya' ),
			'labels'        => array(
				'name'               => __( 'Produits', 'fatichanelya' ),
				'singular_name'      => __( 'Produit', 'fatichanelya' ),
				'add_new_item'       => __( 'Ajouter un produit', 'fatichanelya' ),
				'edit_item'          => __( 'Modifier le produit', 'fatichanelya' ),
				'search_items'       => __( 'Rechercher un produit', 'fatichanelya' ),
				'not_found'          => __( 'Aucun produit', 'fatichanelya' ),
				'featured_image'     => __( 'Visuel du produit', 'fatichanelya' ),
				'set_featured_image' => __( 'Définir le visuel', 'fatichanelya' ),
			),
			'public'        => true,
			'has_archive'   => true,
			'menu_icon'     => 'dashicons-carrot',
			'menu_position' => 20,
			'supports'      => array( 'title', 'editor', 'excerpt', 'thumbnail', 'page-attributes' ),
			'rewrite'       => array( 'slug' => 'produits', 'with_front' => false ),
			'show_in_rest'  => true,
		)
	);

	register_post_type(
		'formation',
		array(
			'label'         => __( 'Formations', 'fatichanelya' ),
			'labels'        => array(
				'name'          => __( 'Formations', 'fatichanelya' ),
				'singular_name' => __( 'Formation', 'fatichanelya' ),
				'add_new_item'  => __( 'Ajouter une formation', 'fatichanelya' ),
				'edit_item'     => __( 'Modifier la formation', 'fatichanelya' ),
			),
			'public'        => true,
			'has_archive'   => true,
			'menu_icon'     => 'dashicons-welcome-learn-more',
			'menu_position' => 21,
			'supports'      => array( 'title', 'editor', 'excerpt', 'thumbnail', 'page-attributes' ),
			'rewrite'       => array( 'slug' => 'formations', 'with_front' => false ),
			'show_in_rest'  => true,
		)
	);

	register_taxonomy(
		'categorie_produit',
		array( 'produit' ),
		array(
			'label'             => __( 'Catégories de produit', 'fatichanelya' ),
			'labels'            => array(
				'name'          => __( 'Catégories de produit', 'fatichanelya' ),
				'singular_name' => __( 'Catégorie', 'fatichanelya' ),
				'add_new_item'  => __( 'Ajouter une catégorie', 'fatichanelya' ),
			),
			'hierarchical'      => true,
			'public'            => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array( 'slug' => 'categorie-produit', 'with_front' => false ),
		)
	);
}

/**
 * Les archives affichent tout le catalogue d'un coup : 19 références, pas de pagination utile.
 */
add_action(
	'pre_get_posts',
	function ( $query ) {
		if ( is_admin() || ! $query->is_main_query() ) {
			return;
		}
		if ( $query->is_post_type_archive( 'produit' ) || $query->is_tax( 'categorie_produit' ) ) {
			$query->set( 'posts_per_page', -1 );
			$query->set( 'orderby', array( 'menu_order' => 'ASC', 'title' => 'ASC' ) );
		}

		// Les formations suivent l'ordre voulu par la cliente, du plus
		// accessible au plus engageant, pas la date de publication.
		if ( $query->is_post_type_archive( 'formation' ) ) {
			$query->set( 'posts_per_page', -1 );
			$query->set( 'orderby', array( 'menu_order' => 'ASC', 'title' => 'ASC' ) );
		}
	}
);

/**
 * Les permaliens changent avec les CPT : on les régénère une seule fois après activation.
 */
add_action(
	'init',
	function () {
		if ( get_option( 'fati_permalinks_flushed' ) !== FATI_CORE_VERSION ) {
			flush_rewrite_rules( false );
			update_option( 'fati_permalinks_flushed', FATI_CORE_VERSION );
		}
	},
	99
);
