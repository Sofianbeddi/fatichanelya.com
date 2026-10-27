<?php
/**
 * Archive des produits et des catégories de produit.
 *
 * @package Fatichanelya
 */

defined( 'ABSPATH' ) || exit;

get_header();

$titre = is_tax() ? single_term_title( '', false ) : __( 'Boutique', 'fatichanelya' );
$trail = is_tax()
	? array(
		array( 'label' => __( 'Boutique', 'fatichanelya' ), 'url' => get_post_type_archive_link( 'produit' ) ),
		array( 'label' => $titre ),
	)
	: array( array( 'label' => $titre ) );

set_query_var( 'fati_banner_title', $titre );
set_query_var( 'fati_banner_trail', $trail );
get_template_part( 'template-parts/components/page-banner' );
?>
<?php
// La page est une suite de bandes : la grille sur sable, le parcours de
// commande sur beige, la question finale sur bleu nuit. Chaque bande porte
// sa propre marge `--bord`, d'où le <main> sans classe de section.
?>
<main id="main" class="shop-page">
<div class="section shop">
	<header class="section-head section-head--split">
		<div>
			<p class="eyebrow"><?php esc_html_e( 'La sélection Fatichanelya', 'fatichanelya' ); ?></p>
			<h2><?php echo is_tax() ? esc_html( single_term_title( '', false ) ) : esc_html__( 'Tous les produits', 'fatichanelya' ); ?></h2>
		</div>
		<p class="section-intro">
			<?php echo is_tax() && term_description() ? wp_kses_post( term_description() ) : esc_html( fati_opt( 'shop_intro' ) ); ?>
		</p>
	</header>

	<?php
	// Filtres et recherche, collants sous l'en-tête (1.5.0). Les cercles de catégories
	// (1.6.0, demande du gérant) filtrent la grille en JavaScript : un cercle se sélectionne,
	// le même se désélectionne et tout s'affiche. Les pages de catégorie ont leur gabarit
	// (`taxonomy-categorie_produit.php`), avec le même bloc en liens.
	set_query_var( 'fati_chips', array( 'mode' => 'filtres' ) );
	?>
		<div class="shop-controls">
			<?php get_template_part( 'template-parts/components/categorie-chips' ); ?>

			<div class="search-form">
				<?php echo fati_icon( 'search', 22 ); ?>
				<label class="sr-only" for="product-search"><?php esc_html_e( 'Rechercher dans la sélection', 'fatichanelya' ); ?></label>
				<input id="product-search" type="search" placeholder="<?php esc_attr_e( 'Rechercher un produit…', 'fatichanelya' ); ?>" autocomplete="off">
			</div>
		</div>

		<p class="result-count" id="result-count" role="status" aria-live="polite">
			<?php
			$total = fati_nb_produits();
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
		<p class="section-intro"><?php esc_html_e( 'Aucun produit dans cette catégorie.', 'fatichanelya' ); ?></p>
	<?php endif; ?>
</div>

<?php
get_template_part( 'template-parts/sections/parcours-commande' );
get_template_part( 'template-parts/sections/question' );
?>
</main>
<?php
get_footer();
