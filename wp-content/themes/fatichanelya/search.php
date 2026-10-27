<?php
/**
 * Résultats de recherche.
 *
 * Jusqu'au thème 1.3.3, la recherche tombait sur le repli générique (`index.php`) et la page
 * était détruite : WordPress pose la classe `search` sur <body> sur cette page, et la feuille
 * donnait à cette classe le style du champ de recherche (pilule flexible). Le champ s'appelle
 * désormais `search-form`, et les résultats ont leur gabarit : les produits trouvés en cartes,
 * comme dans la boutique, le reste (formations, pages) en liste à filets.
 *
 * @package Fatichanelya
 */

defined( 'ABSPATH' ) || exit;

get_header();

$requete  = get_search_query();
$produits = array();
$autres   = array();

if ( have_posts() ) {
	while ( have_posts() ) {
		the_post();
		if ( 'produit' === get_post_type() ) {
			$produits[] = get_post();
		} else {
			$autres[] = get_post();
		}
	}
}
$total = count( $produits ) + count( $autres );

set_query_var( 'fati_banner_title', __( 'Recherche', 'fatichanelya' ) );
set_query_var( 'fati_banner_trail', array( array( 'label' => __( 'Recherche', 'fatichanelya' ) ) ) );
get_template_part( 'template-parts/components/page-banner' );
?>
<main id="main" class="shop-page">
<div class="section shop">
	<header class="section-head section-head--split">
		<div>
			<p class="eyebrow"><?php esc_html_e( 'Résultats', 'fatichanelya' ); ?></p>
			<h2>
				<?php
				if ( $total ) {
					printf(
						/* translators: 1 : nombre de résultats, 2 : texte cherché. */
						esc_html( _n( '%1$d résultat pour « %2$s »', '%1$d résultats pour « %2$s »', $total, 'fatichanelya' ) ),
						(int) $total,
						esc_html( $requete )
					);
				} else {
					/* translators: %s : texte cherché. */
					printf( esc_html__( 'Rien ne correspond à « %s »', 'fatichanelya' ), esc_html( $requete ) );
				}
				?>
			</h2>
		</div>
		<?php get_search_form(); ?>
	</header>

	<?php if ( $produits ) : ?>
		<ul class="product-grid">
			<?php
			global $post;
			foreach ( $produits as $i => $post ) : // phpcs:ignore WordPress.WP.GlobalVariablesOverride
				setup_postdata( $post );
				set_query_var( 'fati_index', $i );
				get_template_part( 'template-parts/components/product-card' );
			endforeach;
			wp_reset_postdata();
			?>
		</ul>
	<?php endif; ?>

	<?php if ( $autres ) : ?>
		<ul class="resultats">
			<?php foreach ( $autres as $i => $autre ) : ?>
				<li class="rule-cell reveal" style="--i:<?php echo (int) $i; ?>">
					<p class="eyebrow"><?php echo esc_html( 'formation' === $autre->post_type ? __( 'Formation', 'fatichanelya' ) : __( 'Page', 'fatichanelya' ) ); ?></p>
					<h3><a href="<?php echo esc_url( get_permalink( $autre ) ); ?>"><?php echo esc_html( get_the_title( $autre ) ); ?></a></h3>
					<?php if ( has_excerpt( $autre ) ) : ?>
						<p class="section-intro"><?php echo esc_html( get_the_excerpt( $autre ) ); ?></p>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>

	<?php if ( ! $total ) : ?>
		<p class="section-intro"><?php esc_html_e( 'Essayez un autre mot, ou parcourez la boutique par catégorie.', 'fatichanelya' ); ?></p>
		<div class="hero-actions">
			<a class="button button-navy" href="<?php echo esc_url( get_post_type_archive_link( 'produit' ) ); ?>"><?php esc_html_e( 'Voir la boutique', 'fatichanelya' ); ?><?php echo fati_icon( 'arrow', 22 ); ?></a>
		</div>
	<?php endif; ?>

	<?php the_posts_pagination( array( 'mid_size' => 1, 'screen_reader_text' => __( 'Pages de résultats', 'fatichanelya' ) ) ); ?>
</div>
</main>
<?php
get_footer();
