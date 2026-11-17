<?php
/**
 * Importe une langue du site depuis tools/traductions/<langue>/ (arabe en 1.7.0,
 * espagnol et anglais en 1.9.0).
 *
 *     wp eval-file tools/importer-traductions.php es              # importe
 *     wp eval-file tools/importer-traductions.php es simulation   # n'écrit rien, dit ce qui changerait
 *
 * Prérequis : Polylang actif et configuré (tools/configurer-polylang.php), le
 * contenu français en place (importer-catalogue.py, importer-formations.py,
 * publier-pages-legales.py). Idempotent : chaque contenu traduit est retrouvé
 * par la traduction Polylang de son original français, mis à jour s'il
 * existe, créé sinon ; les menus de la langue sont reconstruits (ceux des
 * autres langues ne bougent pas) ; les chaînes des réglages sont (ré)écrites
 * dans les traductions de chaînes de Polylang.
 *
 * Adresses : la clé facultative `slug` des JSON fixe l'adresse d'un contenu à
 * sa création (obligatoire en espagnol et en anglais, voir GLOSSAIRE.md) ; une
 * traduction existante garde la sienne. Polylang gratuit refuse deux fois la
 * même adresse : la simulation signale tout slug déjà pris.
 *
 * Fichiers lus :
 *   termes.json       catégories de produit (clé = slug français)
 *   pages.json        pages à gabarit (accueil, à propos, contact, panier)
 *   legal/<slug>.md   pages légales (même forme que _velix/copy-legal.md)
 *   formations.json + formations/<slug>.md
 *   produits-*.json   catalogue (plusieurs lots, fusionnés)
 *   menus.json        libellés des liens de menu
 *   reglages.json     réglages « Contenus du site » (clé = clé du réglage)
 *
 * Ce qui est partagé, jamais traduit : visuels (vignette copiée), prix,
 * référence, drapeau « visuel à venir », visuel de catégorie.
 *
 * @package Fatichanelya
 */

if ( ! defined( 'WP_CLI' ) ) {
	return;
}
if ( ! function_exists( 'pll_set_post_language' ) || ! function_exists( 'PLL' ) ) {
	WP_CLI::error( "Polylang n'est pas actif." );
}

$fati_tr_args = array_values( (array) ( $args ?? array() ) );
if ( empty( $fati_tr_args[0] ) || ! preg_match( '/^[a-z]{2}$/', $fati_tr_args[0] ) ) {
	WP_CLI::error( 'Langue manquante. Usage : wp eval-file tools/importer-traductions.php <ar|es|en> [simulation]' );
}
define( 'FATI_TR_LANG', $fati_tr_args[0] );
define( 'FATI_TR_SIMULATION', 'simulation' === ( $fati_tr_args[1] ?? '' ) );
define( 'FATI_TR_DIR', dirname( __DIR__ ) . '/tools/traductions/' . FATI_TR_LANG );
if ( ! is_dir( FATI_TR_DIR ) ) {
	WP_CLI::error( 'Dossier absent : ' . FATI_TR_DIR );
}
WP_CLI::log( '== Langue « ' . FATI_TR_LANG . ' »' . ( FATI_TR_SIMULATION ? ' — SIMULATION, rien n\'est écrit' : '' ) );

// `wp eval-file` exécute ce fichier dans une fonction : les variables de
// premier niveau ne sont pas globales, on les pose explicitement.
$GLOBALS['fati_tr_defaut'] = pll_default_language();
$GLOBALS['fati_tr_compte'] = array( '+' => 0, '~' => 0 );
$fati_tr_defaut = $GLOBALS['fati_tr_defaut'];
$fati_tr_langue = PLL()->model->get_language( FATI_TR_LANG );
if ( ! $fati_tr_langue ) {
	if ( ! FATI_TR_SIMULATION ) {
		WP_CLI::error( 'Langue « ' . FATI_TR_LANG . ' » absente : lancer tools/configurer-polylang.php.' );
	}
	// Simulation avant la création de la langue (première mise en ligne) : on contrôle quand même
	// les sources françaises et les slugs ; tout serait créé.
	WP_CLI::warning( 'Langue « ' . FATI_TR_LANG . ' » pas encore créée : configurer-polylang.php la créera avant l\'import.' );
}

function fati_tr_json( $fichier ) {
	$chemin = FATI_TR_DIR . '/' . $fichier;
	if ( ! file_exists( $chemin ) ) {
		return null;
	}
	$data = json_decode( file_get_contents( $chemin ), true );
	if ( ! is_array( $data ) ) {
		WP_CLI::error( "JSON invalide : $fichier" );
	}
	return $data;
}

/** L'original français d'un contenu, par son slug. */
function fati_tr_source( $type, $slug ) {
	$post = get_page_by_path( $slug, OBJECT, $type );
	return $post instanceof WP_Post ? $post : null;
}

/**
 * Crée ou met à jour la traduction d'un contenu, la relie à son
 * original, copie la vignette et les métas partagées, pose les métas traduites.
 */
function fati_tr_poser( WP_Post $src, array $args, array $metas = array() ) {
	global $fati_tr_defaut, $fati_tr_compte;

	$existant = pll_get_post( $src->ID, FATI_TR_LANG );
	$args    += array(
		'post_type'   => $src->post_type,
		'post_status' => $src->post_status,
		'menu_order'  => $src->menu_order,
		'post_parent' => 0,
	);
	if ( FATI_TR_SIMULATION ) {
		$verbe = $existant ? '~' : '+';
		$fati_tr_compte[ $verbe ]++;
		if ( ! $existant ) {
			$voulu = sanitize_title( $args['post_name'] ?? '' );
			if ( '' === $voulu ) {
				if ( 'ar' !== FATI_TR_LANG ) {
					WP_CLI::warning( "   {$src->post_name} : pas de slug, WordPress le tirerait du titre" );
				}
			} elseif ( wp_unique_post_slug( $voulu, 0, 'publish', $src->post_type, 0 ) !== $voulu ) {
				WP_CLI::warning( "   {$src->post_name} : le slug « $voulu » est déjà pris" );
			}
		}
		return $existant ? $existant : 0;
	}
	if ( $existant ) {
		unset( $args['post_name'] ); // le slug d'une traduction existante ne bouge plus (liens partagés)
		// `wp_update_post` relit `page_template` et refuse un gabarit qui n'a pas d'en-tête
		// « Template Name » (page-a-propos.php…) : on le neutralise, la méta est reposée après.
		$args['page_template'] = 'default';
		$args['ID'] = $existant;
		$id         = wp_update_post( wp_slash( $args ), true );
		$verbe      = '~';
	} else {
		$id    = wp_insert_post( wp_slash( $args ), true );
		$verbe = '+';
	}
	if ( is_wp_error( $id ) ) {
		WP_CLI::error( "{$src->post_name} : " . $id->get_error_message() );
	}
	$fati_tr_compte[ $verbe ]++;

	pll_set_post_language( $id, FATI_TR_LANG );
	$trads                    = pll_get_post_translations( $src->ID );
	$trads[ $fati_tr_defaut ] = $src->ID;
	$trads[ FATI_TR_LANG ]    = $id;
	pll_save_post_translations( $trads );

	$vignette = get_post_thumbnail_id( $src->ID );
	if ( $vignette ) {
		set_post_thumbnail( $id, $vignette );
	} else {
		delete_post_thumbnail( $id );
	}
	foreach ( array( '_fati_prix', '_fati_reference', '_fati_indispo' ) as $cle ) {
		$valeur = get_post_meta( $src->ID, $cle, true );
		if ( '' === $valeur || null === $valeur ) {
			delete_post_meta( $id, $cle );
		} else {
			update_post_meta( $id, $cle, $valeur );
		}
	}
	foreach ( $metas as $cle => $valeur ) {
		$valeur = is_string( $valeur ) ? trim( $valeur ) : $valeur;
		if ( '' === $valeur || null === $valeur ) {
			delete_post_meta( $id, $cle );
		} else {
			update_post_meta( $id, $cle, $valeur );
		}
	}
	return $id;
}

/** Markdown réduit → blocs de l'éditeur (même règle que importer-formations.py). */
function fati_tr_blocs( $markdown ) {
	$sortie     = array();
	$puces      = array();
	$ligne_html = static function ( $t ) {
		$t = htmlspecialchars( trim( $t ), ENT_NOQUOTES, 'UTF-8', false );
		return preg_replace( '/\*\*(.+?)\*\*/u', '<strong>$1</strong>', $t );
	};
	$vider = static function () use ( &$sortie, &$puces ) {
		if ( $puces ) {
			$items    = implode( "\n\n", array_map( fn( $p ) => "<!-- wp:list-item -->\n<li>$p</li>\n<!-- /wp:list-item -->", $puces ) );
			$sortie[] = "<!-- wp:list -->\n<ul class=\"wp-block-list\">$items</ul>\n<!-- /wp:list -->";
			$puces    = array();
		}
	};
	foreach ( preg_split( '/\R/u', $markdown ) as $ligne ) {
		$ligne = trim( $ligne );
		if ( str_starts_with( $ligne, '- ' ) ) {
			$puces[] = $ligne_html( substr( $ligne, 2 ) );
			continue;
		}
		$vider();
		if ( '' === $ligne ) {
			continue;
		}
		if ( str_starts_with( $ligne, '## ' ) ) {
			$sortie[] = "<!-- wp:heading -->\n<h2 class=\"wp-block-heading\">" . $ligne_html( substr( $ligne, 3 ) ) . "</h2>\n<!-- /wp:heading -->";
		} else {
			$sortie[] = "<!-- wp:paragraph -->\n<p>" . $ligne_html( $ligne ) . "</p>\n<!-- /wp:paragraph -->";
		}
	}
	$vider();
	return implode( "\n\n", $sortie );
}

/* ------------------------------------------------------------------ 1. Catégories */
WP_CLI::log( '→ Catégories de produit' );
foreach ( (array) fati_tr_json( 'termes.json' ) as $slug_fr => $t ) {
	$fr = get_term_by( 'slug', $slug_fr, 'categorie_produit' );
	if ( ! $fr ) {
		WP_CLI::warning( "   catégorie française absente : $slug_fr" );
		continue;
	}
	$tr_id  = pll_get_term( $fr->term_id, FATI_TR_LANG );
	$champs = array( 'name' => $t['nom'], 'description' => $t['description'] ?? '' );
	if ( FATI_TR_SIMULATION ) {
		$voulu = $t['slug'] ?? '';
		$pris  = ! $tr_id && '' !== $voulu && get_term_by( 'slug', $voulu, 'categorie_produit' );
		WP_CLI::log( '   ' . ( $tr_id ? '~' : '+' ) . " {$t['nom']}" . ( $pris ? " — slug « $voulu » déjà pris" : '' ) );
		continue;
	}
	if ( $tr_id ) {
		wp_update_term( $tr_id, 'categorie_produit', $champs );
		WP_CLI::log( "   ~ {$t['nom']}" );
	} else {
		$champs['slug'] = $t['slug'] ?? '';
		$r = wp_insert_term( $t['nom'], 'categorie_produit', $champs );
		if ( is_wp_error( $r ) ) {
			WP_CLI::error( "   catégorie {$t['nom']} : " . $r->get_error_message() );
		}
		$tr_id = (int) $r['term_id'];
		WP_CLI::log( "   + {$t['nom']}" );
	}
	pll_set_term_language( $tr_id, FATI_TR_LANG );
	$trads                    = pll_get_term_translations( $fr->term_id );
	$trads[ $fati_tr_defaut ] = $fr->term_id;
	$trads[ FATI_TR_LANG ]    = $tr_id;
	pll_save_term_translations( $trads );
	$visuel = get_term_meta( $fr->term_id, '_fati_visuel', true );
	if ( $visuel ) {
		update_term_meta( $tr_id, '_fati_visuel', $visuel );
	}
}

/* ------------------------------------------------------------------ 2. Pages à gabarit */
WP_CLI::log( '→ Pages' );
foreach ( (array) fati_tr_json( 'pages.json' ) as $slug_fr => $p ) {
	$src = fati_tr_source( 'page', $slug_fr );
	if ( ! $src ) {
		WP_CLI::warning( "   page française absente : $slug_fr" );
		continue;
	}
	$gabarit = $p['template'] ?: get_post_meta( $src->ID, '_wp_page_template', true );
	$id      = fati_tr_poser(
		$src,
		array(
			'post_title'   => $p['titre'],
			'post_name'    => $p['slug'] ?? '',
			'post_content' => $src->post_content,
		),
		array( '_wp_page_template' => $gabarit ? $gabarit : '' )
	);
	WP_CLI::log( "   {$p['titre']} → " . ( $id ? get_permalink( $id ) : '(simulation)' ) );
}

/* ------------------------------------------------------------------ 3. Pages légales */
WP_CLI::log( '→ Pages légales' );
foreach ( glob( FATI_TR_DIR . '/legal/*.md' ) as $fichier ) {
	$md = file_get_contents( $fichier );
	if ( ! preg_match( '/\*\*Titre\*\*\s*:\s*(.+)/u', $md, $titre ) || ! preg_match( '/\*\*Slug\*\*\s*:\s*`([^`]+)`/u', $md, $slug ) || ! preg_match( '/```html\n(.*?)\n```/su', $md, $html ) ) {
		WP_CLI::warning( '   fichier illisible : ' . basename( $fichier ) );
		continue;
	}
	preg_match( '/\*\*Meta description\*\*[^\n]*\n`([^`]+)`/u', $md, $desc );
	$src = fati_tr_source( 'page', trim( $slug[1] ) );
	if ( ! $src ) {
		WP_CLI::warning( "   page française absente : {$slug[1]}" );
		continue;
	}
	preg_match( '/\*\*Slug traduit\*\*\s*:\s*`([^`]+)`/u', $md, $slug_tr );
	$id = fati_tr_poser(
		$src,
		array(
			'post_title'   => trim( $titre[1] ),
			'post_name'    => $slug_tr ? trim( $slug_tr[1] ) : '',
			'post_content' => trim( $html[1] ),
		),
		array( '_fati_description' => $desc ? trim( $desc[1] ) : '' )
	);
	WP_CLI::log( '   ' . trim( $titre[1] ) . ' → ' . ( $id ? get_permalink( $id ) : '(simulation)' ) );
}

/* ------------------------------------------------------------------ 4. Formations */
WP_CLI::log( '→ Formations' );
foreach ( (array) fati_tr_json( 'formations.json' ) as $slug_fr => $f ) {
	$src = fati_tr_source( 'formation', $slug_fr );
	if ( ! $src ) {
		WP_CLI::warning( "   formation française absente : $slug_fr" );
		continue;
	}
	$programme = FATI_TR_DIR . "/formations/$slug_fr.md";
	$contenu   = file_exists( $programme ) ? fati_tr_blocs( file_get_contents( $programme ) ) : $src->post_content;
	fati_tr_poser(
		$src,
		array( 'post_title' => $f['nom'], 'post_name' => $f['slug'] ?? '', 'post_excerpt' => $f['accroche'], 'post_content' => $contenu ),
		array( '_fati_niveau' => $f['niveau'] ?? '', '_fati_duree' => $f['duree'] ?? '' )
	);
	WP_CLI::log( "   {$f['nom']}" );
}

/* ------------------------------------------------------------------ 5. Produits */
WP_CLI::log( '→ Produits' );
$produits = array();
foreach ( glob( FATI_TR_DIR . '/produits-*.json' ) as $lot ) {
	$produits = array_merge( $produits, (array) fati_tr_json( basename( $lot ) ) );
}
$n = 0;
foreach ( $produits as $slug_fr => $p ) {
	$src = fati_tr_source( 'produit', $slug_fr );
	if ( ! $src ) {
		WP_CLI::warning( "   produit français absent : $slug_fr" );
		continue;
	}
	$id = fati_tr_poser(
		$src,
		array( 'post_title' => $p['nom'], 'post_name' => $p['slug'] ?? '', 'post_excerpt' => $p['accroche'], 'post_content' => $p['description'] ),
		array(
			'_fati_format'      => $p['format'] ?? '',
			'_fati_composition' => $p['composition'] ?? '',
			'_fati_usage'       => $p['usage'] ?? '',
			'_fati_points'      => $p['points'] ?? '',
		)
	);
	$n++;
	if ( FATI_TR_SIMULATION ) {
		continue;
	}
	$termes_fr = wp_get_object_terms( $src->ID, 'categorie_produit', array( 'fields' => 'ids' ) );
	$termes_tr = array();
	foreach ( (array) $termes_fr as $tid ) {
		$t = pll_get_term( $tid, FATI_TR_LANG );
		if ( $t ) {
			$termes_tr[] = (int) $t;
		}
	}
	wp_set_object_terms( $id, $termes_tr, 'categorie_produit' );
}
WP_CLI::log( "   $n produits" );

/* ------------------------------------------------------------------ 6. Menus */
WP_CLI::log( '→ Menus' . ( FATI_TR_SIMULATION ? ' (simulation : non reconstruits)' : '' ) );
$libelles  = FATI_TR_SIMULATION ? array() : (array) fati_tr_json( 'menus.json' );
$theme     = get_option( 'stylesheet' );
$locations = get_theme_mod( 'nav_menu_locations' );
$options   = PLL()->options;
$nav       = $options['nav_menus'];
$maison    = home_url( '/' );
$maison_tr = pll_home_url( FATI_TR_LANG );
foreach ( FATI_TR_SIMULATION ? array() : (array) $locations as $loc => $menu_fr_id ) {
	if ( ! $menu_fr_id || str_contains( $loc, '___' ) ) {
		continue;
	}
	$menu_fr = wp_get_nav_menu_object( $menu_fr_id );
	if ( ! $menu_fr ) {
		continue;
	}
	$nom_tr     = $menu_fr->name . ' — ' . strtoupper( FATI_TR_LANG );
	$menu_tr    = wp_get_nav_menu_object( $nom_tr );
	$menu_tr_id = $menu_tr ? $menu_tr->term_id : wp_create_nav_menu( $nom_tr );
	if ( is_wp_error( $menu_tr_id ) ) {
		WP_CLI::error( "   menu $nom_tr : " . $menu_tr_id->get_error_message() );
	}
	foreach ( (array) wp_get_nav_menu_items( $menu_tr_id ) as $ancien ) {
		wp_delete_post( $ancien->ID, true );
	}
	$position = 0;
	foreach ( (array) wp_get_nav_menu_items( $menu_fr_id ) as $item ) {
		$position++;
		$args = array( 'menu-item-status' => 'publish', 'menu-item-position' => $position );
		if ( 'post_type' === $item->type ) {
			$cible = pll_get_post( (int) $item->object_id, FATI_TR_LANG );
			if ( ! $cible ) {
				WP_CLI::warning( "   pas de traduction pour le lien « {$item->title} »" );
				continue;
			}
			$args += array(
				'menu-item-type'      => 'post_type',
				'menu-item-object'    => $item->object,
				'menu-item-object-id' => $cible,
				'menu-item-title'     => $libelles[ $item->title ] ?? get_the_title( $cible ),
			);
		} else {
			$url    = $item->url;
			$chemin = str_starts_with( $url, $maison ) ? substr( $url, strlen( $maison ) ) : null;
			$cible  = null;
			if ( null !== $chemin ) {
				$sans_ancre = preg_replace( '/[#?].*$/', '', $chemin );
				$page       = '' !== $sans_ancre ? get_page_by_path( rtrim( $sans_ancre, '/' ), OBJECT, 'page' ) : null;
				if ( $page ) {
					$trad  = pll_get_post( $page->ID, FATI_TR_LANG );
					$cible = $trad ? get_permalink( $trad ) : null;
				} else {
					$cible = $maison_tr . $chemin; // accueil, archives (/produits/, /formations/), ancres
				}
			}
			$args += array(
				'menu-item-type'  => 'custom',
				'menu-item-url'   => $cible ?: $url,
				'menu-item-title' => $libelles[ $item->title ] ?? $item->title,
			);
		}
		$r = wp_update_nav_menu_item( $menu_tr_id, 0, $args );
		if ( is_wp_error( $r ) ) {
			WP_CLI::warning( "   lien « {$item->title} » : " . $r->get_error_message() );
		}
	}
	// Fusion : réécrire le tableau entier effaçait l'affectation des autres langues
	// (lancé pour « es », l'importateur 1.7.0 aurait vidé les menus arabes).
	$nav[ $theme ][ $loc ]                    = (array) ( $nav[ $theme ][ $loc ] ?? array() );
	$nav[ $theme ][ $loc ][ $fati_tr_defaut ] = (int) $menu_fr_id;
	$nav[ $theme ][ $loc ][ FATI_TR_LANG ]    = (int) $menu_tr_id;
	WP_CLI::log( "   $loc : {$menu_fr->name} (fr) / $nom_tr (" . FATI_TR_LANG . ')' );
}
if ( ! FATI_TR_SIMULATION ) {
	$options['nav_menus'] = $nav;
	$options->save_all();
}

/* ------------------------------------------------------------------ 7. Réglages (chaînes Polylang) */
WP_CLI::log( '→ Réglages « Contenus du site » et slogan' );
$mo = new PLL_MO();
if ( $fati_tr_langue ) {
	$mo->import_from_db( $fati_tr_langue );
}
$n = 0;
foreach ( (array) fati_tr_json( 'reglages.json' ) as $cle => $ar ) {
	$fr = 'blogdescription' === $cle ? get_option( 'blogdescription' ) : fati_opt_raw( $cle );
	if ( '' === $fr || null === $fr ) {
		WP_CLI::warning( "   réglage vide ou inconnu : $cle" );
		continue;
	}
	$mo->add_entry( $mo->make_entry( $fr, $ar ) );
	$n++;
}
if ( ! FATI_TR_SIMULATION ) {
	$mo->export_to_db( $fati_tr_langue );
}
WP_CLI::log( "   $n chaînes" );

if ( FATI_TR_SIMULATION ) {
	WP_CLI::success( sprintf( 'Simulation « %s » : %d contenus seraient créés, %d mis à jour. Rien n\'a été écrit.', FATI_TR_LANG, $GLOBALS['fati_tr_compte']['+'], $GLOBALS['fati_tr_compte']['~'] ) );
	return;
}
flush_rewrite_rules();
wp_cache_flush();
WP_CLI::success( sprintf( 'Version « %s » importée : %d créés, %d mis à jour.', FATI_TR_LANG, $GLOBALS['fati_tr_compte']['+'], $GLOBALS['fati_tr_compte']['~'] ) );
