<?php
/**
 * Intégration Polylang (version gratuite).
 *
 * La version gratuite traduit nativement les CPT et taxonomies : il suffit de
 * les déclarer traduisibles. Les chaînes des réglages passent par pll_register_string
 * pour être traduites dans Réglages > Langues > Traductions des chaînes.
 *
 * @package Fatichanelya
 */

defined( 'ABSPATH' ) || exit;

/** Rend les CPT traduisibles sans passer par l'écran de réglages. */
add_filter(
	'pll_get_post_types',
	function ( $types, $is_settings ) {
		$types['produit']   = 'produit';
		$types['formation'] = 'formation';
		return $types;
	},
	10,
	2
);

add_filter(
	'pll_get_taxonomies',
	function ( $taxonomies, $is_settings ) {
		$taxonomies['categorie_produit'] = 'categorie_produit';
		return $taxonomies;
	},
	10,
	2
);

/** Expose les réglages à l'écran de traduction des chaînes. */
add_action(
	'init',
	function () {
		if ( ! function_exists( 'pll_register_string' ) ) {
			return;
		}
		foreach ( fati_options_schema() as $tab ) {
			foreach ( $tab['fields'] as $key => $field ) {
				if ( in_array( $field['type'], array( 'url', 'image', 'tel' ), true ) ) {
					continue; // Pas de traduction pour une URL ou un numéro.
				}
				$value = fati_opt_raw( $key );
				if ( '' !== $value ) {
					pll_register_string(
						$field['label'],
						$value,
						'Fatichanelya',
						in_array( $field['type'], array( 'textarea', 'lines' ), true )
					);
				}
			}
		}
	},
	20
);

/** Valeur brute, sans passer par pll__ : évite la récursion à l'enregistrement. */
function fati_opt_raw( $key ) {
	$all = get_option( FATI_OPTION_KEY, array() );
	if ( ! empty( $all[ $key ] ) ) {
		return $all[ $key ];
	}
	$defaults = fati_options_defaults();
	return isset( $defaults[ $key ] ) ? $defaults[ $key ] : '';
}

/** Langue courante, avec repli propre quand Polylang n'est pas installé. */
function fati_current_lang() {
	if ( function_exists( 'pll_current_language' ) ) {
		$lang = pll_current_language( 'slug' );
		if ( $lang ) {
			return $lang;
		}
	}
	return substr( get_locale(), 0, 2 );
}

/** Langues disponibles pour le sélecteur du header. */
function fati_languages() {
	if ( ! function_exists( 'pll_the_languages' ) ) {
		return array();
	}
	return pll_the_languages(
		array(
			'raw'                    => 1,
			'hide_if_no_translation' => 0,
			'display_names_as'       => 'slug',
		)
	);
}

/**
 * L'arabe impose le RTL : Polylang gère dir sur <html>, on s'assure que la
 * classe de corps suit pour les rares règles qui en dépendent.
 */
add_filter(
	'body_class',
	function ( $classes ) {
		if ( is_rtl() ) {
			$classes[] = 'is-rtl';
		}
		$classes[] = 'lang-' . sanitize_html_class( fati_current_lang() );
		return $classes;
	}
);
