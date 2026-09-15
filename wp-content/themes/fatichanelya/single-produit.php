<?php
/**
 * Fiche produit complète (l'accueil ouvre une version condensée en <dialog>).
 *
 * C'est la page où se décide l'achat. Elle répond donc, dans l'ordre, aux
 * questions qui arrêtent une acheteuse : ce que c'est, combien ça coûte,
 * comment on commande, qui expédie, et ce qui se passe si ça ne convient pas.
 *
 * Aucun champ n'est inventé : contenance, durée d'usage et conseils
 * d'utilisation n'existent pas encore côté CMS. Les blocs correspondants
 * n'apparaîtront que le jour où la donnée existera.
 *
 * @package Fatichanelya
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	$id    = get_the_ID();
	$prix  = get_post_meta( $id, '_fati_prix', true );
	$terms = get_the_terms( $id, 'categorie_produit' );
	$cat   = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0] : null;
	$titre = get_the_title();

	$commander = fati_wa( sprintf( __( 'Bonjour Fati, je souhaite commander « %s ».', 'fatichanelya' ), $titre ) );
	$cible     = fati_opt( 'whatsapp' ) ? ' target="_blank" rel="noopener"' : '';
	$trail = array(
		array( 'label' => __( 'Boutique', 'fatichanelya' ), 'url' => get_post_type_archive_link( 'produit' ) ),
	);
	if ( $cat ) {
		$trail[] = array( 'label' => $cat->name, 'url' => get_term_link( $cat ) );
	}
	$trail[] = array( 'label' => $titre );

	set_query_var( 'fati_banner_title', $titre );
	set_query_var( 'fati_banner_trail', $trail );
	get_template_part( 'template-parts/components/page-banner' );
	?>
	<main id="main" class="section single-produit">

		<article class="produit-layout">
			<figure class="produit-media">
				<?php echo fati_produit_image( $id, 'fati-produit' ); ?>
			</figure>

			<div class="produit-copy">
				<?php if ( $cat ) : ?>
					<p class="eyebrow"><?php echo esc_html( $cat->name ); ?></p>
				<?php endif; ?>

				<h2><?php echo esc_html( $titre ); ?></h2>

				<?php if ( $prix ) : ?>
					<p class="produit-prix"><?php echo esc_html( fati_format_prix( $prix ) ); ?></p>
				<?php endif; ?>

				<?php if ( has_excerpt() ) : ?>
					<p class="produit-accroche"><?php echo esc_html( get_the_excerpt() ); ?></p>
				<?php endif; ?>

				<div class="pd-actions">
					<a class="button button-gold button-lg" href="<?php echo esc_url( $commander ); ?>"<?php echo $cible; ?>>
						<?php echo fati_icon( 'whatsapp', 24 ); ?><?php esc_html_e( 'Commander sur WhatsApp', 'fatichanelya' ); ?>
					</a>
					<a class="button button-outline" href="<?php echo esc_url( get_post_type_archive_link( 'produit' ) ); ?>">
						<?php esc_html_e( 'Voir tout le catalogue', 'fatichanelya' ); ?>
					</a>
				</div>

				<p class="produit-cadrage">
					<?php esc_html_e( 'On confirme ensemble la disponibilité, les frais de port réels et le total avant tout paiement. Le paiement en ligne n\'est pas encore activé.', 'fatichanelya' ); ?>
				</p>
			</div>
		</article>

		<?php
		// Bande de réassurance : elle se place juste sous le bloc d'achat,
		// là où le doute apparaît, et pas en pied de page.
		$engagements = fati_opt_lines( 'trust' );
		if ( $engagements ) :
			?>
			<section class="produit-reassurance" aria-label="<?php esc_attr_e( 'Nos engagements', 'fatichanelya' ); ?>">
				<ul>
					<?php foreach ( array_slice( $engagements, 0, 4 ) as $i => $item ) : ?>
						<li>
							<strong><?php echo esc_html( $item[0] ); ?></strong>
							<?php if ( isset( $item[1] ) ) : ?>
								<span><?php echo esc_html( $item[1] ); ?></span>
							<?php endif; ?>
						</li>
					<?php endforeach; ?>
				</ul>
			</section>
		<?php endif; ?>

		<?php
		// L'import initial a rempli l'extrait et le corps avec le même texte.
		// Tant que la cliente n'a pas écrit une vraie description, afficher les
		// deux reviendrait à répéter la même phrase à deux endroits.
		$corps    = trim( wp_strip_all_tags( get_the_content() ) );
		$accroche = trim( wp_strip_all_tags( get_the_excerpt() ) );
		if ( '' !== $corps && $corps !== $accroche ) :
			?>
			<section class="produit-description" aria-labelledby="desc-title">
				<h2 id="desc-title"><?php esc_html_e( 'Ce que c\'est', 'fatichanelya' ); ?></h2>
				<div class="prose"><?php the_content(); ?></div>
			</section>
		<?php endif; ?>

		<p class="produit-legal"><?php echo esc_html( fati_opt( 'mention_sante' ) ); ?></p>

		<?php
		// Produits associés : la même catégorie d'abord, ce qui aide vraiment
		// à comparer. Sans catégorie, on ne propose rien plutôt qu'au hasard.
		if ( $cat ) :
			$associes = new WP_Query(
				array(
					'post_type'      => 'produit',
					'posts_per_page' => 4,
					'post__not_in'   => array( $id ),
					'no_found_rows'  => true,
					'orderby'        => array( 'menu_order' => 'ASC' ),
					'tax_query'      => array(
						array(
							'taxonomy' => 'categorie_produit',
							'field'    => 'term_id',
							'terms'    => $cat->term_id,
						),
					),
				)
			);

			if ( $associes->have_posts() ) :
				?>
				<section class="produit-associes" aria-labelledby="associes-title">
					<h2 id="associes-title">
						<?php
						printf(
							/* translators: %s : nom de la catégorie. */
							esc_html__( 'Autres produits — %s', 'fatichanelya' ),
							esc_html( $cat->name )
						);
						?>
					</h2>
					<ul class="product-grid">
						<?php
						$i = 0;
						while ( $associes->have_posts() ) :
							$associes->the_post();
							set_query_var( 'fati_index', $i++ );
							get_template_part( 'template-parts/components/product-card' );
						endwhile;
						wp_reset_postdata();
						?>
					</ul>
				</section>
				<?php
			endif;
		endif;
		?>

		<?php if ( $prix ) : ?>
			<aside class="produit-rappel">
				<div>
					<p class="produit-rappel-nom"><?php echo esc_html( $titre ); ?></p>
					<p class="produit-rappel-prix"><?php echo esc_html( fati_format_prix( $prix ) ); ?></p>
				</div>
				<a class="button button-navy button-lg" href="<?php echo esc_url( $commander ); ?>"<?php echo $cible; ?>>
					<?php echo fati_icon( 'whatsapp', 24 ); ?><?php esc_html_e( 'Commander sur WhatsApp', 'fatichanelya' ); ?>
				</a>
			</aside>
		<?php endif; ?>
	</main>
	<?php
endwhile;

get_footer();
