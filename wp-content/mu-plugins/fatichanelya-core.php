<?php
/**
 * Plugin Name: Fatichanelya — Cœur métier
 * Description: Types de contenu, champs, options, SEO et durcissement. Indépendant du thème : le contenu survit à un changement de thème.
 * Version:     1.0.0
 * Author:      Velix Digital
 *
 * @package Fatichanelya
 */

defined( 'ABSPATH' ) || exit;

define( 'FATI_CORE_VERSION', '1.0.0' );
define( 'FATI_CORE_DIR', __DIR__ . '/fatichanelya-core' );

foreach ( array( 'cpt', 'meta', 'options', 'polylang', 'seo', 'security' ) as $module ) {
	require_once FATI_CORE_DIR . '/inc/' . $module . '.php';
}
