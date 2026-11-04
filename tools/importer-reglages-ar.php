<?php
/**
 * Réécrit les traductions arabes des réglages « Contenus du site » (chaînes Polylang),
 * et rien d'autre. C'est la section 7 d'importer-traductions.php, seule, pour la production.
 *
 *     wp eval-file tools/importer-reglages-ar.php tools/traductions/ar/reglages.json [motif]
 *
 * Polylang range chaque traduction sous le texte français exact. Quand un texte français
 * change, l'ancienne entrée devient orpheline : elle n'est plus affichée, mais reste en base.
 * Le motif facultatif purge les entrées dont l'original le contient (insensible à la casse),
 * par exemple « Naturixa » le 4 nov. 2026. Le mu-plugin doit être déployé avant : les textes
 * français sont lus dans les réglages en place.
 *
 * @package Fatichanelya
 */

if ( ! defined( 'WP_CLI' ) ) {
	return;
}
if ( ! class_exists( 'PLL_MO' ) || ! function_exists( 'PLL' ) ) {
	WP_CLI::error( "Polylang n'est pas actif." );
}
list( $fati_rg_json, $fati_rg_motif ) = array_pad( (array) $args, 2, '' );
$fati_rg = json_decode( (string) file_get_contents( $fati_rg_json ), true );
if ( ! is_array( $fati_rg ) ) {
	WP_CLI::error( "JSON illisible : $fati_rg_json" );
}
$fati_rg_langue = PLL()->model->get_language( 'ar' );
if ( ! $fati_rg_langue ) {
	WP_CLI::error( 'Langue « ar » absente.' );
}

$mo = new PLL_MO();
$mo->import_from_db( $fati_rg_langue );

$purgees = 0;
if ( '' !== $fati_rg_motif ) {
	foreach ( $mo->entries as $cle => $entree ) {
		if ( false !== mb_stripos( $entree->singular, $fati_rg_motif ) ) {
			unset( $mo->entries[ $cle ] );
			$purgees++;
		}
	}
}

$posees = 0;
foreach ( $fati_rg as $cle => $ar ) {
	$fr = 'blogdescription' === $cle ? get_option( 'blogdescription' ) : fati_opt_raw( $cle );
	if ( '' === $fr || null === $fr ) {
		WP_CLI::warning( "réglage vide ou inconnu : $cle" );
		continue;
	}
	if ( '' !== $fati_rg_motif && false !== mb_stripos( $fr, $fati_rg_motif ) ) {
		WP_CLI::error( "« $fati_rg_motif » est encore dans le texte français de $cle : le mu-plugin est-il déployé ?" );
	}
	$mo->add_entry( $mo->make_entry( $fr, $ar ) );
	$posees++;
}
$mo->export_to_db( $fati_rg_langue );
wp_cache_flush();
WP_CLI::success( "$posees chaînes posées, $purgees entrée(s) purgée(s)." );
