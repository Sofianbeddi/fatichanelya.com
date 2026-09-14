<?php
/**
 * Boutique — filtres, recherche et grille produits.
 *
 * La grille est rendue en PHP (visible sans JavaScript et indexable), puis le
 * JavaScript se contente de filtrer et d'ouvrir les fiches.
 *
 * @package Fatichanelya
 */

defined( 'ABSPATH' ) || exit;

$produits = new WP_Query(
	array(
		'post_type'      => 'produit',
		'posts_per_page' => -1,
		'orderby'        => array( 'menu_order' => 'ASC', 'title' => 'ASC' ),
		'no_found_rows'  => true,
	)
);

if ( ! $produits->have_posts() ) {
	return;
}

$categories = get_terms(
	array(
		'taxonomy'   => 'categorie_produit',
		'hide_empty' => true,
	)
);
$total = $produits->post_count;
?>
<section class="section shop" id="shop" aria-labelledby="shop-title">
	<header class="section-head section-head--split">
		<div>
			<p class="eyebrow"><?php esc_html_e( 'La sélection Fatichanelya', 'fatichanelya' ); ?></p>
			<h2 id="shop-title"><?php echo wp_kses( fati_accent( fati_opt( 'shop_titre' ) ), array( 'em' => array() ) ); ?></h2>
		</div>
		<p class="section-intro"><?php echo esc_html( fati_opt( 'shop_intro' ) ); ?></p>
	</header>

	<div class="shop-controls">
		<div class="filters" role="group" aria-label="<?php esc_attr_e( 'Filtrer par catégorie', 'fatichanelya' ); ?>">
			<button class="chip-button is-active" type="button" data-filter="all" aria-pressed="true">
				<?php esc_html_e( 'Tout voir', 'fatichanelya' ); ?>
			</button>
			<?php if ( ! is_wp_error( $categories ) ) : ?>
				<?php foreach ( $categories as $cat ) : ?>
					<button class="chip-button" type="button" data-filter="<?php echo esc_attr( $cat->slug ); ?>" aria-pressed="false">
						<?php echo esc_html( $cat->name ); ?>
					</button>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>

		<div class="search">
			<?php echo fati_icon( 'search', 17 ); ?>
			<label class="sr-only" for="product-search"><?php esc_html_e( 'Rechercher dans la sélection', 'fatichanelya' ); ?></label>
			<input id="product-search" type="search" placeholder="<?php esc_attr_e( 'Rechercher un produit…', 'fatichanelya' ); ?>" autocomplete="off">
		</div>
	</div>

	<p class="result-count" id="result-count" role="status" aria-live="polite">
		<?php
		printf(
			esc_html( _n( '%d produit affiché', '%d produits affichés', $total, 'fatichanelya' ) ),
			(int) $total
		);
		?>
	</p>

	<ul class="product-grid" id="product-grid">
		<?php
		$i = 0;
		while ( $produits->have_posts() ) :
			$produits->the_post();
			set_query_var( 'fati_index', $i++ );
			get_template_part( 'template-parts/components/product-card' );
		endwhile;
		wp_reset_postdata();
		?>
	</ul>

	<p class="no-result" id="no-result" hidden>
		<?php esc_html_e( 'Aucun produit ne correspond à cette recherche.', 'fatichanelya' ); ?>
		<button class="text-button" id="reset-search" type="button"><?php esc_html_e( 'Réinitialiser', 'fatichanelya' ); ?></button>
	</p>

	<p class="shop-note"><?php esc_html_e( 'Prix indicatifs en euros, hors livraison. Disponibilité confirmée avec Fati avant commande.', 'fatichanelya' ); ?></p>
</section>
