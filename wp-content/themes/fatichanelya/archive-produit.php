<?php
/**
 * Archive des produits et des catégories de produit.
 *
 * @package Fatichanelya
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<main id="main" class="section shop">
	<header class="section-head section-head--split">
		<div>
			<p class="eyebrow"><?php esc_html_e( 'La sélection Fatichanelya', 'fatichanelya' ); ?></p>
			<h1><?php echo is_tax() ? esc_html( single_term_title( '', false ) ) : esc_html__( 'Tous les produits', 'fatichanelya' ); ?></h1>
		</div>
		<p class="section-intro">
			<?php echo is_tax() && term_description() ? wp_kses_post( term_description() ) : esc_html( fati_opt( 'shop_intro' ) ); ?>
		</p>
	</header>

	<?php if ( have_posts() ) : ?>
		<ul class="product-grid">
			<?php
			$i = 0;
			while ( have_posts() ) :
				the_post();
				set_query_var( 'fati_index', $i++ );
				get_template_part( 'template-parts/components/product-card' );
			endwhile;
			?>
		</ul>
	<?php else : ?>
		<p class="section-intro"><?php esc_html_e( 'Aucun produit dans cette catégorie.', 'fatichanelya' ); ?></p>
	<?php endif; ?>
</main>
<?php
get_footer();
