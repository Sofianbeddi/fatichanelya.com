<?php
defined( 'ABSPATH' ) || exit;

$formations = new WP_Query(
	array(
		'post_type'      => 'formation',
		'posts_per_page' => -1,
		'orderby'        => array( 'menu_order' => 'ASC', 'title' => 'ASC' ),
		'no_found_rows'  => true,
	)
);

if ( ! $formations->have_posts() ) {
	return;
}
?>
<section class="section training" id="training" aria-labelledby="training-title">
	<?php if ( fati_opt( 'training_image' ) ) : ?>
		<figure class="training-media reveal">
			<?php
			// Le visuel est une nature morte (bureau, carnet, tasse) : l'ancien
			// texte « Fati pendant une session » décrivait une image générée
			// d'une personne qui n'était pas Fati, retirée le 2 oct. 2026.
			echo fati_image( fati_opt( 'training_image' ), 'fati-paysage', __( 'Un bureau calme en bois clair, carnet bleu nuit et tasse de thé près de la fenêtre', 'fatichanelya' ) );
			?>
			<figcaption><?php esc_html_e( 'Des formations à distance, à suivre depuis chez vous.', 'fatichanelya' ); ?></figcaption>
		</figure>
	<?php endif; ?>

	<div class="training-copy">
		<p class="eyebrow"><?php esc_html_e( 'Formations à distance', 'fatichanelya' ); ?></p>
		<h2 id="training-title"><?php echo wp_kses( fati_accent( fati_opt( 'training_titre' ) ), array( 'em' => array() ) ); ?></h2>
		<p class="section-intro"><?php echo esc_html( fati_opt( 'training_intro' ) ); ?></p>

		<ul class="training-list">
			<?php
			$i = 0;
			while ( $formations->have_posts() ) :
				$formations->the_post();
				$niveau = get_post_meta( get_the_ID(), '_fati_niveau', true );
				$duree  = get_post_meta( get_the_ID(), '_fati_duree', true );
				$chip   = trim( implode( ' · ', array_filter( array( $niveau, $duree ) ) ) );
				?>
				<li class="reveal" style="--i:<?php echo esc_attr( $i++ ); ?>">
					<?php if ( $chip ) : ?>
						<span class="chip"><?php echo esc_html( $chip ); ?></span>
					<?php endif; ?>
					<h3><?php the_title(); ?></h3>
					<?php if ( has_excerpt() ) : ?>
						<p><?php echo esc_html( get_the_excerpt() ); ?></p>
					<?php endif; ?>
					<a class="arrow-link" href="<?php the_permalink(); ?>">
						<?php esc_html_e( 'Voir le programme', 'fatichanelya' ); ?><?php echo fati_icon( 'arrow', 20 ); ?>
					</a>
				</li>
			<?php endwhile; ?>
			<?php wp_reset_postdata(); ?>
		</ul>
	</div>
</section>
