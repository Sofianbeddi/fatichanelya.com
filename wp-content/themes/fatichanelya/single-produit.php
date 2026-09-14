<?php
/**
 * Fiche produit complète (l'accueil ouvre une version condensée en <dialog>).
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
	?>
	<main id="main" class="section single-produit">
		<nav class="breadcrumb" aria-label="<?php esc_attr_e( 'Fil d\'Ariane', 'fatichanelya' ); ?>">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Accueil', 'fatichanelya' ); ?></a>
			<span aria-hidden="true">/</span>
			<a href="<?php echo esc_url( get_post_type_archive_link( 'produit' ) ); ?>"><?php esc_html_e( 'Produits', 'fatichanelya' ); ?></a>
			<?php if ( $cat ) : ?>
				<span aria-hidden="true">/</span>
				<a href="<?php echo esc_url( get_term_link( $cat ) ); ?>"><?php echo esc_html( $cat->name ); ?></a>
			<?php endif; ?>
		</nav>

		<article class="produit-layout">
			<figure class="produit-media">
				<?php echo fati_produit_image( $id, 'fati-produit' ); ?>
			</figure>

			<div class="produit-copy">
				<?php if ( $cat ) : ?>
					<span class="chip"><?php echo esc_html( $cat->name ); ?></span>
				<?php endif; ?>

				<h1><?php the_title(); ?></h1>

				<?php if ( $prix ) : ?>
					<p class="pd-price"><?php echo esc_html( fati_format_prix( $prix ) ); ?></p>
				<?php endif; ?>

				<div class="prose"><?php the_content(); ?></div>

				<div class="pd-actions">
					<a class="button button-gold"
					   href="<?php echo esc_url( fati_wa( sprintf( __( 'Bonjour Fati, je souhaite commander « %s ».', 'fatichanelya' ), get_the_title() ) ) ); ?>"
					   <?php echo fati_opt( 'whatsapp' ) ? 'target="_blank" rel="noopener"' : ''; ?>>
						<?php echo fati_icon( 'whatsapp', 18 ); ?><?php esc_html_e( 'Commander sur WhatsApp', 'fatichanelya' ); ?>
					</a>
					<a class="button button-outline" href="<?php echo esc_url( get_post_type_archive_link( 'produit' ) ); ?>">
						<?php esc_html_e( 'Voir tout le catalogue', 'fatichanelya' ); ?>
					</a>
				</div>

				<p class="pd-legal">
					<?php echo esc_html( fati_opt( 'mention_sante' ) ); ?>
				</p>
			</div>
		</article>
	</main>
	<?php
endwhile;

get_footer();
