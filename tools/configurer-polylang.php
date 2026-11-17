<?php
/**
 * Configure Polylang : français par défaut, arabe (thème 1.7.0), espagnol et anglais (1.9.0).
 *
 *     wp eval-file tools/configurer-polylang.php
 *
 * Idempotent. Crée les langues « fr » (défaut), « ar » (RTL), « es » (es_ES)
 * et « en » (en_GB) si elles manquent — dans cet ordre, qui est celui du menu de
 * langues ; une langue existante n'est jamais modifiée —, pose les réglages (adresses en /ar/, français sans préfixe, pas de
 * redirection selon le navigateur sur l'accueil seulement — 1.9.0 —, médias non traduits, vignette / gabarit /
 * ordre synchronisés entre traductions), rattache au français tout contenu
 * sans langue, puis régénère les permaliens. Les contenus arabes se créent
 * ensuite avec tools/importer-traductions.php <langue>.
 *
 * @package Fatichanelya
 */

if ( ! defined( 'WP_CLI' ) || ! function_exists( 'PLL' ) ) {
	echo "Polylang n'est pas actif.\n";
	return;
}

$modele  = PLL()->model;
$langues = $modele->languages;

foreach ( array( 'fr_FR' => 0, 'ar' => 1, 'es_ES' => 2, 'en_GB' => 3 ) as $locale => $ordre ) {
	$existante = $langues->get( $locale );
	if ( $existante ) {
		WP_CLI::log( "   = langue déjà là : {$existante->slug} ({$locale})" );
		continue;
	}
	$resultat = $langues->add( array( 'locale' => $locale, 'term_group' => $ordre ) );
	if ( is_wp_error( $resultat ) ) {
		WP_CLI::error( "Langue {$locale} : " . $resultat->get_error_message() );
	}
	WP_CLI::log( "   + langue créée : {$resultat->slug} ({$locale}" . ( $resultat->is_rtl ? ', RTL' : '' ) . ')' );
}

$options = PLL()->options;
$reglages = array(
	'default_lang'  => 'fr',
	'force_lang'    => 1,      // /ar/ dans l'adresse
	'hide_default'  => true,   // le français sans préfixe
	'rewrite'       => true,   // /ar/ et non /language/ar/
	'browser'       => true,   // 1.9.0 : l'accueil suit la langue du navigateur (pages intérieures jamais)
	// true : l'accueil arabe est /ar/ (et non /ar/الرئيسية/, le permalien de la page traduite).
	'redirect_lang' => true,
	'media_support' => false,
	// Jamais `post_meta` : les textes des produits sont traduits, pas copiés
	// (les prix passent par le filtre pll_copy_post_metas du mu-plugin).
	// Pas `_wp_page_template` non plus : les pages françaises n'en ont pas (gabarit par
	// slug), la synchronisation effacerait celui des traductions.
	'sync'          => array( '_thumbnail_id', 'menu_order' ),
);
foreach ( $reglages as $cle => $valeur ) {
	$options[ $cle ] = $valeur;
}
$options->save_all();
WP_CLI::log( '   réglages posés : ' . implode( ', ', array_keys( $reglages ) ) );

// Tout ce qui n'a pas de langue est en français.
$modele->set_language_in_mass( $langues->get( 'fr' ) );
$modele->clean_languages_cache();
flush_rewrite_rules();
WP_CLI::log( '   contenu sans langue rattaché au français, permaliens régénérés' );

$liste = array_map( fn( $l ) => $l->slug . ( $l->is_rtl ? ' (rtl)' : '' ), $langues->get_list() );
WP_CLI::success( 'Polylang : ' . implode( ', ', $liste ) . ' — défaut ' . $options['default_lang'] );
