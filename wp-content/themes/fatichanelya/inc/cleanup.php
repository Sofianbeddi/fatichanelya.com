<?php
/**
 * Retrait du superflu chargé par défaut sur chaque page.
 *
 * Chaque ligne ici vaut des kilo-octets et des requêtes en moins. Tout est
 * réversible : commenter une ligne suffit si un plugin en dépend.
 *
 * @package Fatichanelya
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'wp_enqueue_scripts',
	function () {
		// Gutenberg n'est pas utilisé en façade : ses feuilles ne servent à rien.
		wp_dequeue_style( 'wp-block-library' );
		wp_dequeue_style( 'wp-block-library-theme' );
		wp_dequeue_style( 'global-styles' );
		wp_dequeue_style( 'classic-theme-styles' );

		// jQuery : le thème est en JavaScript natif. Un plugin qui en a besoin
		// le réenfile lui-même, donc on ne le retire qu'en façade.
		if ( ! is_admin() && ! is_customize_preview() ) {
			wp_deregister_script( 'jquery' );
		}
	},
	100
);

/** Émojis : un script et une feuille sur chaque page, pour rien. */
add_action(
	'init',
	function () {
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
		remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
		remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
		remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
		remove_action( 'wp_head', 'wp_oembed_add_host_js' );
		remove_action( 'wp_head', 'wp_shortlink_wp_head' );
		remove_action( 'wp_head', 'feed_links_extra', 3 );
	}
);

/** Les commentaires ne servent pas ce site : on les coupe proprement. */
add_filter( 'comments_open', '__return_false', 20 );
add_filter( 'pings_open', '__return_false', 20 );
add_action(
	'admin_menu',
	function () {
		remove_menu_page( 'edit-comments.php' );
	}
);

/**
 * Liens internes écrits depuis la racine (`/mentions-legales/`) dans les
 * contenus : ils sont justes quand le site est à la racine de son domaine, et
 * tombent en 404 quand il est installé dans un sous-dossier, comme en local.
 * On les rattache à l'adresse du site ; à la racine, rien ne change.
 */
add_filter(
	'the_content',
	function ( $content ) {
		$chemin = rtrim( (string) wp_parse_url( home_url(), PHP_URL_PATH ), '/' );
		if ( '' === $chemin ) {
			return $content;
		}
		return preg_replace( '~href="/(?!/)(?!' . preg_quote( ltrim( $chemin, '/' ), '~' ) . '/)~', 'href="' . esc_url( home_url( '/' ) ), $content );
	},
	20
);
