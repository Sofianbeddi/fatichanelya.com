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

	<?php
	// Les filtres n'ont de sens que sur cette page : on y vient pour chercher.
	// Ils vivaient jusqu'ici dans la section d'accueil, qui ne montre plus
	// qu'un aperçu.
	$categories = get_terms(
		array(
			'taxonomy'   => 'categorie_produit',
			'hide_empty' => true,
		)
	);

	if ( ! is_tax() && ! is_wp_error( $categories ) && $categories ) :
		?>
		<div class="shop-controls">
			<div class="filters" role="group" aria-label="<?php esc_attr_e( 'Filtrer par catégorie', 'fatichanelya' ); ?>">
				<button class="chip-button is-active" type="button" data-filter="all" aria-pressed="true">
					<?php esc_html_e( 'Tout voir', 'fatichanelya' ); ?>
				</button>
				<?php foreach ( $categories as $cat ) : ?>
					<button class="chip-button" type="button" data-filter="<?php echo esc_attr( $cat->slug ); ?>" aria-pressed="false">
						<?php echo esc_html( $cat->name ); ?>
					</button>
				<?php endforeach; ?>
			</div>

			<div class="search">
				<?php echo fati_icon( 'search', 17 ); ?>
				<label class="sr-only" for="product-search"><?php esc_html_e( 'Rechercher dans la sélection', 'fatichanelya' ); ?></label>
				<input id="product-search" type="search" placeholder="<?php esc_attr_e( 'Rechercher un produit…', 'fatichanelya' ); ?>" autocomplete="off">
			</div>
		</div>

		<p class="result-count" id="result-count" role="status" aria-live="polite">
			<?php
			$total = (int) wp_count_posts( 'produit' )->publish;
			printf(
				esc_html( _n( '%d produit affiché', '%d produits affichés', $total, 'fatichanelya' ) ),
				$total
			);
			?>
		</p>
	<?php endif; ?>

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
</main>
<?php
get_footer();
