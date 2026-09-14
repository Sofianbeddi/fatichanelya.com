<?php
/**
 * SEO sans plugin : titre, description, canonical, Open Graph, hreflang, JSON-LD.
 *
 * Un plugin SEO complet n'apporte rien ici (site vitrine, une dizaine d'URL)
 * et chargerait des assets sur chaque page.
 *
 * @package Fatichanelya
 */

defined( 'ABSPATH' ) || exit;

add_theme_support( 'title-tag' );

/** Description de la page courante. */
function fati_meta_description() {
	if ( is_singular() ) {
		$post = get_queried_object();
		$desc = $post->post_excerpt ? $post->post_excerpt : wp_strip_all_tags( $post->post_content );
	} elseif ( is_tax() || is_category() ) {
		$desc = term_description();
	} else {
		$desc = fati_opt( 'hero_intro' );
	}

	$desc = wp_strip_all_tags( (string) $desc );
	$desc = preg_replace( '/\s+/', ' ', $desc );

	return trim( mb_substr( $desc, 0, 157 ) ) . ( mb_strlen( $desc ) > 157 ? '…' : '' );
}

add_action(
	'wp_head',
	function () {
		$desc  = fati_meta_description();
		$title = wp_get_document_title();
		$url   = home_url( add_query_arg( array() ) );
		$image = fati_opt( 'hero_image' );

		if ( is_singular() && has_post_thumbnail() ) {
			$image = get_the_post_thumbnail_url( null, 'large' );
		}

		echo '<meta name="description" content="' . esc_attr( $desc ) . '">' . "\n";
		echo '<link rel="canonical" href="' . esc_url( $url ) . '">' . "\n";
		echo '<meta property="og:type" content="' . ( is_singular() ? 'article' : 'website' ) . '">' . "\n";
		echo '<meta property="og:site_name" content="' . esc_attr( get_bloginfo( 'name' ) ) . '">' . "\n";
		echo '<meta property="og:locale" content="' . esc_attr( get_locale() ) . '">' . "\n";
		echo '<meta property="og:title" content="' . esc_attr( $title ) . '">' . "\n";
		echo '<meta property="og:description" content="' . esc_attr( $desc ) . '">' . "\n";
		echo '<meta property="og:url" content="' . esc_url( $url ) . '">' . "\n";
		if ( $image ) {
			echo '<meta property="og:image" content="' . esc_url( $image ) . '">' . "\n";
		}
		echo '<meta name="twitter:card" content="summary_large_image">' . "\n";

		// hreflang : Polylang pose les siens ; sinon rien à déclarer.
		if ( ! function_exists( 'pll_the_languages' ) ) {
			return;
		}
	},
	2
);

/** Données structurées : identité, site, et le produit sur sa fiche. */
add_action(
	'wp_head',
	function () {
		$graph = array(
			array(
				'@type'       => 'Person',
				'@id'         => home_url( '/#fati' ),
				'name'        => 'Fati',
				'alternateName' => get_bloginfo( 'name' ),
				'jobTitle'    => __( 'Entrepreneure, créatrice et mentore', 'fatichanelya' ),
				'sameAs'      => array_values( array_filter( array( fati_opt( 'tiktok' ), fati_opt( 'instagram' ) ) ) ),
			),
			array(
				'@type'      => 'WebSite',
				'@id'        => home_url( '/#site' ),
				'url'        => home_url( '/' ),
				'name'       => get_bloginfo( 'name' ),
				'inLanguage' => fati_current_lang(),
				'publisher'  => array( '@id' => home_url( '/#fati' ) ),
			),
		);

		if ( is_singular( 'produit' ) ) {
			$prix     = get_post_meta( get_the_ID(), '_fati_prix', true );
			$graph[] = array_filter(
				array(
					'@type'       => 'Product',
					'name'        => get_the_title(),
					'description' => fati_meta_description(),
					'image'       => has_post_thumbnail() ? get_the_post_thumbnail_url( null, 'large' ) : null,
					'offers'      => $prix ? array(
						'@type'         => 'Offer',
						'price'         => $prix,
						'priceCurrency' => 'EUR',
						'availability'  => 'https://schema.org/InStock',
						'url'           => get_permalink(),
					) : null,
				)
			);
		}

		if ( is_front_page() ) {
			$faq = array();
			foreach ( fati_opt_lines( 'faq' ) as $row ) {
				if ( count( $row ) < 2 ) {
					continue;
				}
				$faq[] = array(
					'@type'          => 'Question',
					'name'           => $row[0],
					'acceptedAnswer' => array( '@type' => 'Answer', 'text' => $row[1] ),
				);
			}
			if ( $faq ) {
				$graph[] = array( '@type' => 'FAQPage', 'mainEntity' => $faq );
			}
		}

		echo '<script type="application/ld+json">'
			. wp_json_encode( array( '@context' => 'https://schema.org', '@graph' => $graph ), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE )
			. '</script>' . "\n";
	},
	5
);
