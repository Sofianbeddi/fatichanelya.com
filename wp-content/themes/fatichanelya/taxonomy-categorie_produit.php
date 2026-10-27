<?php
/**
 * Page d'une catégorie de produit.
 *
 * Sans ce gabarit, WordPress retombait sur `archive.php`, le repli générique
 * du blog : les produits s'affichaient en vignettes de journal, sans carte,
 * sans prix et sans bouton d'ajout. `archive-produit.php` ne couvre que
 * l'archive du type de contenu, pas ses catégories.
 *
 * @package Fatichanelya
 */

defined( 'ABSPATH' ) || exit;

get_header();

$terme = get_queried_object();

set_query_var( 'fati_banner_title', $terme ? $terme->name : __( 'Boutique', 'fatichanelya' ) );
set_query_var(
	'fati_banner_trail',
	array(
		array(
			'label' => __( 'Boutique', 'fatichanelya' ),
			'url'   => get_post_type_archive_link( 'produit' ),
		),
		array( 'label' => $terme ? $terme->name : '' ),
	)
);
get_template_part( 'template-parts/components/page-banner' );
?>
<?php
// Même suite de bandes que l'archive : grille, parcours de commande,
// question finale. Voir `archive-produit.php`.
?>
<main id="main" class="shop-page">
<div class="section shop">
	<header class="section-head section-head--split">
		<div>
			<p class="eyebrow"><?php esc_html_e( 'La sélection Fatichanelya', 'fatichanelya' ); ?></p>
			<h2><?php echo esc_html( $terme ? $terme->name : '' ); ?></h2>
		</div>
		<p class="section-intro">
			<?php
			echo $terme && term_description( $terme )
				? wp_kses_post( term_description( $terme ) )
				: esc_html( fati_opt( 'shop_intro' ) );
			?>
		</p>
	</header>

	<?php
	// Boutons de catégories à visuel (liens, la courante marquée) et recherche dans la
	// catégorie, collants sous l'en-tête (1.5.0). Même bloc que la boutique.
	set_query_var( 'fati_chips', array( 'mode' => 'liens', 'actuelle' => $terme ? $terme->slug : '' ) );
	?>
	<div class="shop-controls">
		<?php get_template_part( 'template-parts/components/categorie-chips' ); ?>

		<div class="search-form">
			<?php echo fati_icon( 'search', 22 ); ?>
			<label class="sr-only" for="product-search"><?php esc_html_e( 'Rechercher dans cette catégorie', 'fatichanelya' ); ?></label>
			<input id="product-search" type="search" placeholder="<?php esc_attr_e( 'Rechercher un produit…', 'fatichanelya' ); ?>" autocomplete="off">
		</div>
	</div>

	<p class="result-count" id="result-count" role="status" aria-live="polite">
		<?php
		$total = $terme ? (int) $terme->count : 0;
		printf(
			esc_html( _n( '%d produit affiché', '%d produits affichés', $total, 'fatichanelya' ) ),
			$total
		);
		?>
	</p>

	<?php if ( have_posts() ) : ?>
		<ul class="product-grid" id="product-grid">
			<?php
			$i = 0;
			while ( have_posts() ) :
				the_post();
				set_query_var( 'fati_index', $i++ );
				get_template_part( 'template-parts/components/product-card' );
			endwhile;
			?>
		</ul>
		<p class="no-result" id="no-result" hidden>
			<?php esc_html_e( 'Aucun produit ne correspond à cette recherche.', 'fatichanelya' ); ?>
			<button class="text-button" id="reset-search" type="button"><?php esc_html_e( 'Réinitialiser', 'fatichanelya' ); ?></button>
		</p>

		<p class="shop-note"><?php esc_html_e( 'Prix indicatifs en euros, hors livraison. Disponibilité confirmée avec Fati avant commande.', 'fatichanelya' ); ?></p>
	<?php else : ?>
		<p class="section-intro"><?php esc_html_e( 'Aucun produit dans cette catégorie pour le moment.', 'fatichanelya' ); ?></p>
	<?php endif; ?>

	<div class="shop-apercu-suite">
		<a class="button button-navy button-lg" href="<?php echo esc_url( get_post_type_archive_link( 'produit' ) ); ?>">
			<?php esc_html_e( 'Voir tout le catalogue', 'fatichanelya' ); ?><?php echo fati_icon( 'arrow', 22 ); ?>
		</a>
	</div>
</div>

<?php
get_template_part( 'template-parts/sections/parcours-commande' );
get_template_part( 'template-parts/sections/question' );
?>
</main>
<?php
get_footer();
