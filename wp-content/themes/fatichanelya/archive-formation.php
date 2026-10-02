<?php
/**
 * Archive des formations.
 *
 * Sans ce gabarit, /formations/ tombait sur l'archive générique du blog :
 * les formations s'affichaient comme des articles, sans niveau, sans durée
 * et sans appel à l'action. C'est ce qui les rendait invendables.
 *
 * @package Fatichanelya
 */

defined( 'ABSPATH' ) || exit;

get_header();

set_query_var( 'fati_banner_title', __( 'Formations', 'fatichanelya' ) );
set_query_var( 'fati_banner_trail', array( array( 'label' => __( 'Formations', 'fatichanelya' ) ) ) );
get_template_part( 'template-parts/components/page-banner' );
?>
<main id="main" class="section training-archive">
	<header class="section-head section-head--split">
		<div>
			<p class="eyebrow"><?php esc_html_e( 'Les programmes', 'fatichanelya' ); ?></p>
			<h2><?php echo wp_kses( fati_accent( fati_opt( 'training_titre' ) ), array( 'em' => array() ) ); ?></h2>
		</div>
		<p class="section-intro"><?php echo esc_html( fati_opt( 'training_intro' ) ); ?></p>
	</header>

	<?php if ( have_posts() ) : ?>
		<ul class="training-cards">
			<?php
			$i = 0;
			while ( have_posts() ) :
				the_post();
				$niveau = get_post_meta( get_the_ID(), '_fati_niveau', true );
				$duree  = get_post_meta( get_the_ID(), '_fati_duree', true );
				$chip   = trim( implode( ' · ', array_filter( array( $niveau, $duree ) ) ) );
				?>
				<li class="training-card reveal" style="--i:<?php echo esc_attr( $i++ ); ?>">
					<?php if ( has_post_thumbnail() ) : ?>
						<span class="training-card-media">
							<?php
							the_post_thumbnail(
								'fati-paysage',
								array(
									'alt'      => '',
									'loading'  => 'lazy',
									'decoding' => 'async',
									'sizes'    => '(min-width:860px) 40vw, 92vw',
								)
							);
							?>
						</span>
					<?php endif; ?>

					<?php if ( $chip ) : ?>
						<p class="chip"><?php echo esc_html( $chip ); ?></p>
					<?php endif; ?>

					<h2 class="training-card-title">
						<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
					</h2>

					<?php if ( has_excerpt() ) : ?>
						<p class="training-card-desc"><?php echo esc_html( get_the_excerpt() ); ?></p>
					<?php endif; ?>

					<span class="arrow-link" aria-hidden="true">
						<?php esc_html_e( 'Voir le programme', 'fatichanelya' ); ?><?php echo fati_icon( 'arrow', 20 ); ?>
					</span>
				</li>
			<?php endwhile; ?>
		</ul>
	<?php else : ?>
		<p class="section-intro"><?php esc_html_e( 'Les formations arrivent très bientôt.', 'fatichanelya' ); ?></p>
	<?php endif; ?>

	<aside class="training-aside">
		<h2><?php esc_html_e( 'Vous ne savez pas laquelle choisir ?', 'fatichanelya' ); ?></h2>
		<p><?php esc_html_e( 'Dites à Fati où vous en êtes et ce que vous voulez atteindre. Elle vous indique le programme qui correspond, sans engagement.', 'fatichanelya' ); ?></p>
		<a class="button button-navy"
		   href="<?php echo esc_url( fati_wa( __( 'Bonjour Fati, j\'hésite entre plusieurs formations. Pouvez-vous m\'orienter ?', 'fatichanelya' ) ) ); ?>"
		   <?php echo fati_opt( 'whatsapp' ) ? 'target="_blank" rel="noopener"' : ''; ?>>
			<?php echo fati_icon( 'whatsapp', 22 ); ?><?php esc_html_e( 'En parler avec Fati', 'fatichanelya' ); ?>
		</a>
	</aside>
</main>
<?php
get_footer();
