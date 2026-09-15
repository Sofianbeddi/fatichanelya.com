<?php
/**
 * Boutique — aperçu sur la page d'accueil.
 *
 * L'accueil chargeait les dix-neuf produits d'un bloc, filtres et recherche
 * compris. Sur mobile, tout ce qui construit la confiance — formations, qui
 * est Fati, engagements, questions fréquentes — arrivait derrière ce mur :
 * on demandait d'accepter 61 € pour un pot avant d'avoir donné une raison de
 * faire confiance.
 *
 * L'accueil montre donc un aperçu et oriente vers la boutique, où les filtres
 * et la recherche ont un sens parce qu'on y vient pour chercher.
 *
 * @package Fatichanelya
 */

defined( 'ABSPATH' ) || exit;

/** Nombre de produits montrés sur l'accueil. */
const FATI_APERCU_PRODUITS = 8;

$produits = new WP_Query(
	array(
		'post_type'      => 'produit',
		'posts_per_page' => FATI_APERCU_PRODUITS,
		'orderby'        => array( 'menu_order' => 'ASC', 'title' => 'ASC' ),
		'no_found_rows'  => true,
	)
);

if ( ! $produits->have_posts() ) {
	return;
}

$total = (int) wp_count_posts( 'produit' )->publish;
?>
<section class="section shop shop--apercu" id="shop" aria-labelledby="shop-title">
	<header class="section-head section-head--split">
		<div>
			<p class="eyebrow"><?php esc_html_e( 'La sélection Fatichanelya', 'fatichanelya' ); ?></p>
			<h2 id="shop-title"><?php echo wp_kses( fati_accent( fati_opt( 'shop_titre' ) ), array( 'em' => array() ) ); ?></h2>
		</div>
		<p class="section-intro"><?php echo esc_html( fati_opt( 'shop_intro' ) ); ?></p>
	</header>

	<ul class="product-grid">
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

	<div class="shop-apercu-suite">
		<a class="button button-navy button-lg" href="<?php echo esc_url( get_post_type_archive_link( 'produit' ) ); ?>">
			<?php
			printf(
				/* translators: %d : nombre total de produits. */
				esc_html__( 'Voir les %d produits', 'fatichanelya' ),
				$total
			);
			?>
			<?php echo fati_icon( 'arrow', 17 ); ?>
		</a>
		<p class="shop-note"><?php esc_html_e( 'Prix indicatifs en euros, hors livraison. Disponibilité confirmée avec Fati avant commande.', 'fatichanelya' ); ?></p>
	</div>
</section>
