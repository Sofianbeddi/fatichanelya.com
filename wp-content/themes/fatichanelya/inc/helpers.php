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

/** Le numéro WhatsApp est-il renseigné ? Sans lui, aucun bouton WhatsApp ne peut aboutir. */
function fati_whatsapp_actif() {
	return '' !== preg_replace( '/[^0-9]/', '', (string) fati_opt( 'whatsapp' ) );
}

/** Adresse d'une page par son slug, vide si elle n'existe pas. */
function fati_page_url( $slug ) {
	$page = get_page_by_path( $slug );
	return $page ? get_permalink( $page ) : '';
}

/** Page « Panier ». Elle s'appelait « Ma sélection » jusqu'à la version 1.2.0. */
function fati_panier_url() {
	$url = fati_page_url( 'panier' );
	return $url ? $url : fati_page_url( 'ma-selection' );
}

/**
 * Lien WhatsApp prérempli.
 *
 * Sans numéro, il renvoie la page Contact : l'ancienne ancre `#contact`
 * n'existe que sur l'accueil, et partout ailleurs le clic ne faisait rien.
 */
function fati_wa( $message = '' ) {
	$numero = preg_replace( '/[^0-9]/', '', (string) fati_opt( 'whatsapp' ) );
	if ( ! $numero ) {
		$contact = fati_page_url( 'contact' );
		return $contact ? $contact : home_url( '/' );
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
		'whatsapp' => '<path d="M20.5 11.6a8.5 8.5 0 0 1-12.6 7.4L3.5 20.5l1.6-4.3A8.5 8.5 0 1 1 20.5 11.6Z"/><path d="M9 8.4c.2-.5.4-.5.7-.5h.5c.2 0 .4 0 .6.5l.7 1.6c.1.3 0 .5-.1.7l-.4.5c-.1.2-.2.3-.1.5a5 5 0 0 0 2.4 2.1c.2.1.4 0 .5-.1l.5-.6c.2-.2.4-.2.6-.1l1.6.8c.3.1.4.3.4.5a1.7 1.7 0 0 1-1.2 1.4c-.5.1-1.1.2-3.2-.7a8 8 0 0 1-3.6-3.6c-.6-1.3-.4-2.2 0-3Z"/>',
		'search'   => '<circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/>',
		'bag'      => '<path d="M5.5 7.5h13l-1 12.5a1.5 1.5 0 0 1-1.5 1.4H8a1.5 1.5 0 0 1-1.5-1.4L5.5 7.5Z"/><path d="M8.8 7.5V6.2a3.2 3.2 0 0 1 6.4 0v1.3"/>',
		'globe'    => '<circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3a15 15 0 0 1 0 18M12 3a15 15 0 0 0 0 18"/>',
		'menu'     => '<path d="M4 7h16M4 12h16M4 17h16"/>',
		'close'    => '<path d="M6 6l12 12M18 6 6 18"/>',
		'check'    => '<path d="m5 12.5 4.5 4.5L19 7.5"/>',
		'minus'    => '<path d="M6 12h12"/>',
		'plus'     => '<path d="M6 12h12M12 6v12"/>',
		'shield'   => '<path d="M12 2.5 4 5.8v5.9c0 4.7 3.4 8.8 8 9.8 4.6-1 8-5.1 8-9.8V5.8l-8-3.3Z"/><path d="m8.6 12.2 2.4 2.4 4.4-4.8"/>',
		'card'     => '<rect x="2" y="6" width="20" height="13" rx="2"/><path d="M2 10h20"/>',
		'leaf'     => '<path d="M20 4C10 4 4 8.5 4 15a5 5 0 0 0 5 5c6.5 0 11-6 11-16Z"/><path d="M4 20c3-7 8-11 14-13"/>',
		// Bandeau de réassurance, repris de la référence.
		'box'      => '<path d="M3 8.5 12 4l9 4.5v7L12 20l-9-4.5v-7Z"/><path d="m3 8.5 9 4.5 9-4.5M12 13v7"/>',
		'wallet'   => '<rect x="3" y="6" width="18" height="13" rx="2.5"/><path d="M3 10h18"/><circle cx="17" cy="14.5" r="1.2"/>',
		'support'  => '<path d="M4 13a8 8 0 0 1 16 0"/><rect x="2.5" y="13" width="4" height="6" rx="1.6"/><rect x="17.5" y="13" width="4" height="6" rx="1.6"/><path d="M20 19a3 3 0 0 1-3 2.5h-2"/>',
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

/**
 * Visuel d'un produit, avec repli sur le placeholder de marque.
 *
 * $eager : réservé au visuel principal de la fiche produit, qui est l'élément
 * LCP de la page et ne doit donc jamais être lazy-loadé. $sizes permet au
 * gabarit de dire la largeur réellement rendue (le filtre de `seo.php` retire
 * alors le mot-clé « auto » que WordPress préfixe).
 */
function fati_produit_image( $post_id, $size = 'fati-produit', $class = '', $eager = false, $sizes = '' ) {
	$loading = $eager ? 'eager' : 'lazy';

	if ( get_post_meta( $post_id, '_fati_indispo', true ) || ! has_post_thumbnail( $post_id ) ) {
		return sprintf(
			'<img src="%s" alt="%s" width="620" height="620" loading="%s" decoding="async" class="%s"%s>',
			esc_url( FATI_URI . '/assets/img/placeholder-produit.svg' ),
			esc_attr( sprintf( __( 'Visuel à venir pour %s', 'fatichanelya' ), get_the_title( $post_id ) ) ),
			esc_attr( $loading ),
			esc_attr( $class ),
			$eager ? ' fetchpriority="high"' : ''
		);
	}

	$attrs = array(
		'alt'      => get_the_title( $post_id ),
		'loading'  => $loading,
		'decoding' => 'async',
		'class'    => $class,
	);
	if ( $eager ) {
		$attrs['fetchpriority'] = 'high';
	}
	if ( $sizes ) {
		$attrs['sizes'] = $sizes;
	}

	return get_the_post_thumbnail( $post_id, $size, $attrs );
}

/**
 * Les trois métas descriptives d'un produit, lues sur l'emballage et saisies
 * dans le back-office. Les textareas gardent leurs retours à la ligne : le
 * gabarit en fait des paragraphes ou des étapes.
 */
function fati_produit_details( $post_id ) {
	$lignes = static function ( $texte ) {
		$out = array();
		foreach ( preg_split( '/\R/', (string) $texte ) as $l ) {
			$l = trim( $l );
			if ( '' !== $l ) {
				$out[] = $l;
			}
		}
		return $out;
	};

	return array(
		'format'      => trim( (string) get_post_meta( $post_id, '_fati_format', true ) ),
		'composition' => trim( (string) get_post_meta( $post_id, '_fati_composition', true ) ),
		'usage'       => $lignes( get_post_meta( $post_id, '_fati_usage', true ) ),
		'points'      => $lignes( get_post_meta( $post_id, '_fati_points', true ) ),
	);
}

/**
 * Sélecteur de quantité relié au panier : « − 2 + ». Il n'apparaît que
 * lorsque le produit est dans le panier ; le JavaScript tient le nombre à jour.
 */
function fati_quantite( $post_id, $nom, $classe = '' ) {
	return sprintf(
		'<div class="qty %5$s" role="group" aria-label="%2$s" data-qty-box="%1$d" hidden>'
		. '<button class="qty-btn" type="button" data-step="-1" data-id="%1$d" aria-label="%3$s">%6$s</button>'
		. '<output class="qty-val" data-qty="%1$d">1</output>'
		. '<button class="qty-btn" type="button" data-step="1" data-id="%1$d" aria-label="%4$s">%7$s</button>'
		. '</div>',
		(int) $post_id,
		/* translators: %s : nom du produit. */
		esc_attr( sprintf( __( 'Quantité de %s dans le panier', 'fatichanelya' ), $nom ) ),
		esc_attr( sprintf( __( 'Retirer un %s', 'fatichanelya' ), $nom ) ),
		esc_attr( sprintf( __( 'Ajouter un %s', 'fatichanelya' ), $nom ) ),
		esc_attr( $classe ),
		fati_icon( 'minus', 18 ),
		fati_icon( 'plus', 18 )
	);
}

/** Les produits sérialisés pour le JavaScript du panier. */
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

	// Sans srcset : le panier affiche ces vignettes à 72 px au plus, la taille
	// demandée suffit. Avec les cinq tailles de chaque visuel, le JSON pesait
	// 57 Ko dans chaque page.
	add_filter( 'wp_calculate_image_srcset_meta', '__return_false' );

	foreach ( $query->posts as $post ) {
		$terms = get_the_terms( $post, 'categorie_produit' );
		$cat   = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0] : null;
		$prix  = get_post_meta( $post->ID, '_fati_prix', true );

		$out[] = array(
			'id'      => $post->ID,
			'slug'    => $post->post_name,
			'nom'     => html_entity_decode( get_the_title( $post ), ENT_QUOTES, 'UTF-8' ),
			// `null` quand le prix n'est pas encore en base : le JavaScript
			// affiche alors « prix à confirmer » et laisse l'article hors total.
			'prix'    => $prix ? (float) $prix : null,
			'prixFmt' => $prix ? fati_format_prix( $prix ) : '',
			'format'  => trim( (string) get_post_meta( $post->ID, '_fati_format', true ) ),
			'cat'     => $cat ? $cat->slug : '',
			// WordPress stocke « & » échappé en « &amp; ». Le JSON part vers du
			// JavaScript qui écrit en textContent : sans décodage, la visiteuse
			// lit « Alimentation &amp; boissons ».
			'catNom'  => $cat ? html_entity_decode( $cat->name, ENT_QUOTES, 'UTF-8' ) : '',
			'vignette'=> fati_produit_image( $post->ID, 'thumbnail' ),
			'url'     => get_permalink( $post ),
		);
	}

	remove_filter( 'wp_calculate_image_srcset_meta', '__return_false' );
	wp_reset_postdata();

	return $out;
}
