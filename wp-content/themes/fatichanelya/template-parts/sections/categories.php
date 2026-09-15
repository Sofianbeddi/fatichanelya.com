<?php
/**
 * Catégories de produit, en vignettes rondes sur la page d'accueil.
 *
 * Elle répond à une question précise : « qu'est-ce qu'on vend, au juste ? ».
 * Une visiteuse venue de TikTok ne connaît pas le catalogue ; quatre entrées
 * lisibles valent mieux qu'une grille de dix-neuf produits.
 *
 * Le visuel de chaque catégorie est enregistré dans un champ de terme. Sans
 * visuel, la vignette affiche un aplat de la palette plutôt qu'une image
 * cassée : la section reste présentable avant que tout soit rempli.
 *
 * @package Fatichanelya
 */

defined( 'ABSPATH' ) || exit;

$categories = get_terms(
	array(
		'taxonomy'   => 'categorie_produit',
		'hide_empty' => true,
		'orderby'    => 'count',
		'order'      => 'DESC',
	)
);

if ( is_wp_error( $categories ) || ! $categories ) {
	return;
}
?>
<section class="section categories" aria-labelledby="categories-title">
	<header class="section-head section-head--center">
		<p class="eyebrow"><?php esc_html_e( 'Nos catégories', 'fatichanelya' ); ?></p>
		<h2 id="categories-title">
			<?php
			echo wp_kses(
				fati_accent( __( 'Trouvez ce qu\'il vous *faut*.', 'fatichanelya' ) ),
				array( 'em' => array() )
			);
			?>
		</h2>
	</header>

	<ul class="categories-grid">
		<?php foreach ( $categories as $i => $cat ) : ?>
			<?php $visuel = (int) get_term_meta( $cat->term_id, '_fati_visuel', true ); ?>
			<li class="categorie reveal" style="--i:<?php echo esc_attr( $i ); ?>">
				<a href="<?php echo esc_url( get_term_link( $cat ) ); ?>">
					<span class="categorie-media<?php echo $visuel ? '' : ' categorie-media--vide'; ?>">
						<?php
						if ( $visuel ) {
							echo wp_get_attachment_image(
								$visuel,
								'fati-categorie',
								false,
								array(
									'alt'      => '',
									'loading'  => 'lazy',
									'decoding' => 'async',
								)
							);
						}
						?>
					</span>
					<span class="categorie-nom"><?php echo esc_html( $cat->name ); ?></span>
					<span class="categorie-compte">
						<?php
						printf(
							/* translators: %d : nombre de produits dans la catégorie. */
							esc_html( _n( '%d produit', '%d produits', (int) $cat->count, 'fatichanelya' ) ),
							(int) $cat->count
						);
						?>
					</span>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
</section>
