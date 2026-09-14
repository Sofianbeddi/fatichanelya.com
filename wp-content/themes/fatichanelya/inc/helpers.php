<?php
/**
 * Fonctions d'affichage partagées par les templates.
 *
 * @package Fatichanelya
 */

defined( 'ABSPATH' ) || exit;

/**
 * Transforme *mot* en <em> doré. Permet au client de mettre un mot en valeur
 * depuis un champ texte, sans éditeur riche ni HTML à taper.
 */
function fati_accent( $text ) {
	$escaped = esc_html( $text );
	return preg_replace( '/\*(.+?)\*/u', '<em>$1</em>', $escaped );
}

/** Lien WhatsApp prérempli. Renvoie le lien de contact du site si le numéro manque. */
function fati_wa( $message = '' ) {
	$numero = preg_replace( '/[^0-9]/', '', (string) fati_opt( 'whatsapp' ) );
	if ( ! $numero ) {
		return '#contact';
	}
	return 'https://wa.me/' . $numero . ( $message ? '?text=' . rawurlencode( $message ) : '' );
}

/**
 * Icône SVG en ligne. Aucune police d'icônes, aucune requête supplémentaire.
 */
function fati_icon( $name, $size = 18 ) {
	$paths = array(
		'arrow'    => '<path d="M5 12h14m-7-7 7 7-7 7"/>',
		'external' => '<path d="M7 17 17 7m0 0h-7m7 0v7"/>',
		'whatsapp' => '<path d="M21 11.5a8.4 8.4 0 0 1-9 8.4 8.5 8.5 0 0 1-3.8-.9L3 21l1.9-5.1A8.4 8.4 0 0 1 12 3a8.4 8.4 0 0 1 9 8.5Z"/>',
		'search'   => '<circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/>',
		'bag'      => '<path d="M6 7h12l-1 13H7L6 7Z"/><path d="M9 7a3 3 0 0 1 6 0"/>',
		'globe'    => '<circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3a15 15 0 0 1 0 18M12 3a15 15 0 0 0 0 18"/>',
		'menu'     => '<path d="M4 7h16M4 12h16M4 17h16"/>',
		'close'    => '<path d="M6 6l12 12M18 6 6 18"/>',
		'shield'   => '<path d="M12 3 4 6v6c0 4.4 3.4 8.3 8 9 4.6-.7 8-4.6 8-9V6l-8-3Z"/><path d="m9 12 2 2 4-4"/>',
		'card'     => '<rect x="2" y="6" width="20" height="13" rx="2"/><path d="M2 10h20"/>',
		'leaf'     => '<path d="M12 3v18M5 8h14M7 16h10"/>',
	);

	if ( ! isset( $paths[ $name ] ) ) {
		return '';
	}

	return sprintf(
		'<svg class="i" width="%1$d" height="%1$d" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">%2$s</svg>',
		(int) $size,
		$paths[ $name ]
	);
}

/**
 * Balise <img> responsive à partir d'une URL de réglage ou d'un ID de média.
 * $eager : réservé au visuel du hero, qui ne doit jamais être lazy-loadé.
 */
function fati_image( $source, $size, $alt = '', $eager = false, $class = '' ) {
	$loading = $eager ? 'eager' : 'lazy';
	$priority = $eager ? ' fetchpriority="high"' : '';

	$id = is_numeric( $source ) ? (int) $source : attachment_url_to_postid( (string) $source );

	if ( $id ) {
		return wp_get_attachment_image(
			$id,
			$size,
			false,
			array(
				'alt'           => $alt,
				'loading'       => $loading,
				'decoding'      => 'async',
				'class'         => $class,
				'fetchpriority' => $eager ? 'high' : 'auto',
			)
		);
	}

	if ( ! $source ) {
		return '';
	}

	return sprintf(
		'<img src="%s" alt="%s" loading="%s" decoding="async" class="%s"%s>',
		esc_url( (string) $source ),
		esc_attr( $alt ),
		esc_attr( $loading ),
		esc_attr( $class ),
		$priority
	);
}

/** Visuel d'un produit, avec repli sur le placeholder de marque. */
function fati_produit_image( $post_id, $size = 'fati-produit', $class = '' ) {
	if ( get_post_meta( $post_id, '_fati_indispo', true ) || ! has_post_thumbnail( $post_id ) ) {
		return sprintf(
			'<img src="%s" alt="%s" width="620" height="620" loading="lazy" decoding="async" class="%s">',
			esc_url( FATI_URI . '/assets/img/placeholder-produit.svg' ),
			esc_attr( sprintf( __( 'Visuel à venir pour %s', 'fatichanelya' ), get_the_title( $post_id ) ) ),
			esc_attr( $class )
		);
	}

	return get_the_post_thumbnail(
		$post_id,
		$size,
		array(
			'alt'      => get_the_title( $post_id ),
			'loading'  => 'lazy',
			'decoding' => 'async',
			'class'    => $class,
		)
	);
}

/** Les produits sérialisés pour le JavaScript de la boutique. */
function fati_produits_json() {
	$query = new WP_Query(
		array(
			'post_type'      => 'produit',
			'posts_per_page' => -1,
			'orderby'        => array( 'menu_order' => 'ASC', 'title' => 'ASC' ),
			'no_found_rows'  => true,
		)
	);

	$out = array();

	foreach ( $query->posts as $post ) {
		$terms = get_the_terms( $post, 'categorie_produit' );
		$cat   = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0] : null;
		$prix  = get_post_meta( $post->ID, '_fati_prix', true );

		$out[] = array(
			'id'      => $post->ID,
			'slug'    => $post->post_name,
			'nom'     => get_the_title( $post ),
			'prix'    => $prix ? (float) $prix : null,
			'prixFmt' => $prix ? fati_format_prix( $prix ) : '',
			'cat'     => $cat ? $cat->slug : '',
			'catNom'  => $cat ? $cat->name : '',
			'desc'    => wp_strip_all_tags( $post->post_excerpt ? $post->post_excerpt : $post->post_content ),
			'img'     => fati_produit_image( $post->ID, 'fati-produit' ),
			'vignette'=> fati_produit_image( $post->ID, 'thumbnail' ),
			'url'     => get_permalink( $post ),
		);
	}

	wp_reset_postdata();

	return $out;
}
