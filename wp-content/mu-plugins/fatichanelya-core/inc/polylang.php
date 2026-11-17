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
			'display_names_as'       => 'name', // nom natif : Français, العربية, Español…
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

/**
 * Métas partagées entre les traductions (1.7.0). Le prix, la référence et le
 * drapeau « visuel à venir » sont les mêmes dans toutes les langues : ils se
 * copient à la création d'une traduction et se synchronisent à chaque
 * enregistrement. Les textes (contenance, composition, mode d'emploi, points
 * clés, niveau, durée, meta description) sont traduits, jamais copiés.
 */
add_filter(
	'pll_copy_post_metas',
	function ( $metas ) {
		return array_merge( $metas, array( '_fati_prix', '_fati_reference', '_fati_indispo' ) );
	}
);

/** Le visuel d'une catégorie de produit vaut pour toutes ses traductions. */
add_filter(
	'pll_copy_term_metas',
	function ( $metas ) {
		return array_merge( $metas, array( '_fati_visuel' ) );
	}
);

/**
 * Polylang ne traduit `home_url()` (→ /ar/) que pour les appels venant du
 * thème ou des extensions classiques ; le mu-plugin (canonique, JSON-LD du
 * SEO) recevait l'accueil français. On l'ajoute à la liste blanche.
 */
add_filter(
	'pll_home_url_white_list',
	function ( $liste ) {
		$liste[] = array( 'file' => WPMU_PLUGIN_DIR );
		return $liste;
	}
);

/**
 * Langue du visiteur (1.2.0).
 *
 * L'accueil (fatichanelya.com/) envoie vers /es/, /en/ ou /ar/ selon la langue du navigateur :
 * c'est la détection native de Polylang (réglage `browser`, posé par tools/configurer-polylang.php),
 * qui ne joue que sur l'accueil et laisse passer d'abord le cookie `pll_language` (dernière langue
 * consultée). Les pages intérieures ne sont jamais redirigées ; le thème y propose la bonne
 * version (suggestion de langue, 1.9.0).
 *
 * Repli : un navigateur dans une langue que le site n'a pas (allemand, italien, néerlandais…)
 * reçoit l'anglais plutôt que le français. Sans en-tête Accept-Language (robots d'indexation),
 * rien ne change : l'accueil reste français.
 */
add_filter(
	'pll_preferred_language',
	function ( $langue, $cookie ) {
		if ( $langue || $cookie || ! function_exists( 'PLL' ) ) {
			return $langue;
		}
		$entete = isset( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ) ) : '';
		if ( ! preg_match( '/\b[a-z]{2}\b/i', $entete ) ) {
			return $langue;
		}
		return PLL()->model->get_language( 'en' ) ? 'en' : $langue;
	},
	10,
	2
);

/**
 * `x-default` sur l'accueil : l'adresse racine, celle qui choisit la langue du visiteur.
 * Polylang ne le pose pas quand la langue par défaut est sans préfixe.
 */
add_filter(
	'pll_rel_hreflang_attributes',
	function ( $hreflangs ) {
		if ( is_front_page() && function_exists( 'pll_default_language' ) ) {
			$defaut = pll_default_language();
			if ( isset( $hreflangs[ $defaut ] ) ) {
				$hreflangs['x-default'] = $hreflangs[ $defaut ];
			}
		}
		return $hreflangs;
	}
);

/**
 * L'adresse racine répond selon la langue du navigateur et le cookie de langue : un cache
 * intermédiaire ne doit pas figer la réponse d'un visiteur pour le suivant. (Le CDN Hostinger
 * sert aujourd'hui le HTML sans le garder, en DYNAMIC ; s'il se met un jour à le garder, c'est
 * cet en-tête qui protège la redirection.)
 */
function fati_vary_langue() {
	$racine = trailingslashit( (string) wp_parse_url( get_option( 'home' ), PHP_URL_PATH ) );
	$chemin = trailingslashit( (string) wp_parse_url( $_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH ) );
	if ( $racine === $chemin && ! headers_sent() ) {
		header( 'Vary: Accept-Language, Cookie', false );
	}
}
add_action( 'send_headers', 'fati_vary_langue' );
// La redirection de l'accueil : Polylang pose « Vary: Accept-Language » juste avant de rediriger
// (en remplaçant tout Vary antérieur) ; on y ajoute le cookie au tout dernier moment.
add_filter(
	'pll_redirect_home',
	function ( $adresse ) {
		add_filter(
			'wp_redirect',
			function ( $cible ) {
				if ( ! headers_sent() ) {
					header( 'Vary: Cookie', false );
				}
				return $cible;
			}
		);
		return $adresse;
	}
);

/**
 * Liens internes dans la langue de la page (1.2.0).
 *
 * Les pages traduites reprennent le HTML français à l'identique, liens compris : la politique de
 * confidentialité arabe, espagnole ou anglaise renvoyait vers « /conditions-generales-de-vente/ »,
 * la page française (défaut présent en arabe depuis 1.7.0). À l'affichage, tout lien vers une page
 * française, relatif ou absolu, part vers sa traduction dans la langue courante si elle existe ;
 * l'ancre éventuelle est gardée. Le contenu en base n'est pas modifié.
 */
add_filter(
	'the_content',
	function ( $html ) {
		if ( ! function_exists( 'pll_current_language' ) || ! function_exists( 'pll_get_post' ) || false === strpos( $html, 'href=' ) ) {
			return $html;
		}
		$langue = pll_current_language();
		if ( ! $langue || pll_default_language() === $langue ) {
			return $html;
		}
		$maison = preg_quote( untrailingslashit( (string) get_option( 'home' ) ), '#' );
		return preg_replace_callback(
			'#href="(?:' . $maison . ')?/([a-z0-9-]+)/(\#[^"]*)?"#',
			function ( $m ) use ( $langue ) {
				$page = get_page_by_path( $m[1] );
				if ( ! $page || pll_get_post_language( $page->ID ) !== pll_default_language() ) {
					return $m[0];
				}
				$traduction = pll_get_post( $page->ID, $langue );
				return $traduction ? 'href="' . esc_url( get_permalink( $traduction ) . ( $m[2] ?? '' ) ) . '"' : $m[0];
			},
			$html
		);
	},
	20
);
