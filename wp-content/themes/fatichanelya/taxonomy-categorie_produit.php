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
<main id="main" class="section shop">
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

		<p class="shop-note"><?php esc_html_e( 'Prix indicatifs en euros, hors livraison. Disponibilité confirmée avec Fati avant commande.', 'fatichanelya' ); ?></p>
	<?php else : ?>
		<p class="section-intro"><?php esc_html_e( 'Aucun produit dans cette catégorie pour le moment.', 'fatichanelya' ); ?></p>
	<?php endif; ?>

	<div class="shop-apercu-suite">
		<a class="button button-navy button-lg" href="<?php echo esc_url( get_post_type_archive_link( 'produit' ) ); ?>">
			<?php esc_html_e( 'Voir tout le catalogue', 'fatichanelya' ); ?><?php echo fati_icon( 'arrow', 22 ); ?>
		</a>
	</div>
</main>
<?php
get_footer();
