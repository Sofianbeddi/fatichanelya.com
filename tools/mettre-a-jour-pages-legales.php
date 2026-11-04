<?php
/**
 * Met à jour les huit pages légales (FR + AR) depuis leurs sources Markdown.
 *
 *     wp eval-file tools/mettre-a-jour-pages-legales.php _velix/copy-legal.md tools/traductions/ar/legal [avant.json]
 *
 * Ne crée rien : une page absente est signalée et sautée. Seuls le titre, le contenu et la
 * meta description changent, et seulement s'ils diffèrent. Avec un troisième argument, le
 * contenu en place est d'abord sauvegardé dans ce fichier JSON.
 *
 * Fait pour la production, où le contenu fait foi depuis le 3 nov. 2026 : l'importateur complet
 * (importer-traductions.php) réécrirait toutes les pages, et publier-pages-legales.py ne traite
 * que le français. En ligne, passer par tools/publier-pages-legales-en-ligne.sh.
 *
 * @package Fatichanelya
 */

if ( ! defined( 'WP_CLI' ) ) {
	return;
}

list( $fati_lg_fr, $fati_lg_ar, $fati_lg_sauvegarde ) = array_pad( (array) $args, 3, '' );
if ( ! is_file( $fati_lg_fr ) || ! is_dir( $fati_lg_ar ) ) {
	WP_CLI::error( 'Usage : wp eval-file mettre-a-jour-pages-legales.php <copy-legal.md> <dossier legal arabe> [avant.json]' );
}

/** Titre, slug, description et HTML d'un bloc Markdown, ou null. */
function fati_lg_lire( $md ) {
	if ( ! preg_match( '/\*\*Titre\*\*\s*:\s*(.+)/u', $md, $t ) || ! preg_match( '/\*\*Slug\*\*\s*:\s*`([^`]+)`/u', $md, $s ) || ! preg_match( '/```html\n(.*?)\n```/su', $md, $h ) ) {
		return null;
	}
	preg_match( '/\*\*Meta description\*\*[^\n]*\n`([^`]+)`/u', $md, $d );
	return array(
		'titre' => trim( $t[1] ),
		'slug'  => trim( $s[1] ),
		'desc'  => $d ? trim( $d[1] ) : '',
		'html'  => trim( $h[1] ),
	);
}

/** Page française d'un slug (les traductions arabes ont leur propre slug). */
function fati_lg_page_fr( $slug ) {
	$defaut = function_exists( 'pll_default_language' ) ? pll_default_language() : '';
	$pages  = get_posts( array( 'post_type' => 'page', 'name' => $slug, 'post_status' => 'any', 'posts_per_page' => -1, 'lang' => '' ) );
	foreach ( $pages as $p ) {
		if ( ! $defaut || pll_get_post_language( $p->ID ) === $defaut ) {
			return $p;
		}
	}
	return null;
}

$fati_lg_cibles = array(); // [ [WP_Post, page source], … ]

$blocs = preg_split( '/^# PAGE \d+ — /mu', file_get_contents( $fati_lg_fr ) );
foreach ( array_slice( $blocs, 1 ) as $bloc ) {
	$src = fati_lg_lire( $bloc );
	if ( ! $src ) {
		continue;
	}
	$p = fati_lg_page_fr( $src['slug'] );
	if ( ! $p ) {
		WP_CLI::warning( "page française absente : {$src['slug']} (sautée)" );
		continue;
	}
	$fati_lg_cibles[] = array( $p, $src );
}

foreach ( glob( rtrim( $fati_lg_ar, '/' ) . '/*.md' ) as $fichier ) {
	$src = fati_lg_lire( file_get_contents( $fichier ) );
	$fr  = $src ? fati_lg_page_fr( $src['slug'] ) : null;
	$id  = ( $fr && function_exists( 'pll_get_post' ) ) ? pll_get_post( $fr->ID, 'ar' ) : 0;
	if ( ! $id ) {
		WP_CLI::warning( 'traduction arabe absente : ' . basename( $fichier ) . ' (sautée)' );
		continue;
	}
	$fati_lg_cibles[] = array( get_post( $id ), $src );
}

if ( $fati_lg_sauvegarde ) {
	$avant = array();
	foreach ( $fati_lg_cibles as list( $p ) ) {
		$avant[ $p->ID ] = array(
			'titre'   => $p->post_title,
			'contenu' => $p->post_content,
			'desc'    => get_post_meta( $p->ID, '_fati_description', true ),
		);
	}
	file_put_contents( $fati_lg_sauvegarde, wp_json_encode( $avant, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) );
	WP_CLI::log( "   sauvegarde : $fati_lg_sauvegarde" );
}

$fati_lg_n = 0;
foreach ( $fati_lg_cibles as list( $p, $src ) ) {
	$maj = array();
	if ( $p->post_title !== $src['titre'] ) {
		$maj['post_title'] = $src['titre'];
	}
	if ( trim( $p->post_content ) !== $src['html'] ) {
		$maj['post_content'] = $src['html'];
	}
	if ( $maj ) {
		$maj['ID'] = $p->ID;
		$r         = wp_update_post( wp_slash( $maj ), true );
		if ( is_wp_error( $r ) ) {
			WP_CLI::error( "page {$p->ID} : " . $r->get_error_message() );
		}
		$fati_lg_n++;
	}
	if ( '' !== $src['desc'] && get_post_meta( $p->ID, '_fati_description', true ) !== $src['desc'] ) {
		update_post_meta( $p->ID, '_fati_description', $src['desc'] );
	}
	WP_CLI::log(
		sprintf(
			'   %-4d %-40s %-10s marqueurs %2d → %2d',
			$p->ID,
			mb_substr( $src['titre'], 0, 40 ),
			$maj ? 'mise à jour' : 'inchangée',
			substr_count( $p->post_content, '[[À' ),
			substr_count( $src['html'], '[[À' )
		)
	);
}

wp_cache_flush();
WP_CLI::success( sprintf( '%d page(s) légale(s) mise(s) à jour sur %d.', $fati_lg_n, count( $fati_lg_cibles ) ) );
