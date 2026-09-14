<?php
/**
 * Point d'entrée du thème. Aucune logique ici : tout est dans /inc.
 *
 * @package Fatichanelya
 */

defined( 'ABSPATH' ) || exit;

define( 'FATI_VERSION', wp_get_theme()->get( 'Version' ) );
define( 'FATI_DIR', get_stylesheet_directory() );
define( 'FATI_URI', get_stylesheet_directory_uri() );

foreach ( array( 'setup', 'assets', 'cleanup', 'helpers' ) as $module ) {
	require_once FATI_DIR . '/inc/' . $module . '.php';
}
