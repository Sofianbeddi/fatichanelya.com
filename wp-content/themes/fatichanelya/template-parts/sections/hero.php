<?php defined( 'ABSPATH' ) || exit; ?>
<section class="hero" aria-labelledby="hero-title">
	<div class="hero-copy">
		<?php if ( fati_opt( 'hero_eyebrow' ) ) : ?>
			<p class="eyebrow"><?php echo esc_html( fati_opt( 'hero_eyebrow' ) ); ?></p>
		<?php endif; ?>

		<h1 id="hero-title"><?php echo wp_kses( fati_accent( fati_opt( 'hero_titre' ) ), array( 'em' => array() ) ); ?></h1>

		<?php if ( fati_opt( 'hero_intro' ) ) : ?>
			<p class="lead"><?php echo esc_html( fati_opt( 'hero_intro' ) ); ?></p>
		<?php endif; ?>

		<div class="hero-actions">
			<a class="button button-navy" href="#shop">
				<?php echo esc_html( fati_opt( 'hero_cta1' ) ); ?><?php echo fati_icon( 'arrow', 17 ); ?>
			</a>
			<a class="text-button" href="#training">
				<?php echo esc_html( fati_opt( 'hero_cta2' ) ); ?><?php echo fati_icon( 'arrow', 16 ); ?>
			</a>
		</div>

		<?php $faits = fati_opt_lines( 'hero_faits' ); ?>
		<?php if ( $faits ) : ?>
			<ul class="hero-facts">
				<?php foreach ( $faits as $fait ) : ?>
					<li><?php echo esc_html( $fait[0] ); ?></li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</div>

	<div class="hero-visual">
		<span class="hero-shape" aria-hidden="true"></span>

		<?php if ( fati_opt( 'hero_image' ) ) : ?>
			<figure class="hero-media">
				<?php
				echo fati_image(
					fati_opt( 'hero_image' ),
					'fati-hero',
					sprintf( __( 'Portrait de Fati, créatrice de %s', 'fatichanelya' ), get_bloginfo( 'name' ) ),
					true
				);
				?>
				<figcaption>
					<strong>Fati</strong>
					<span><?php esc_html_e( 'Entrepreneure · Créatrice · Mentore', 'fatichanelya' ); ?></span>
				</figcaption>
			</figure>
		<?php endif; ?>

		<?php $badge = fati_opt_lines( 'hero_badge' ); ?>
		<?php if ( $badge ) : ?>
			<p class="hero-badge reveal" style="--i:1">
				<?php echo esc_html( $badge[0][0] ); ?>
				<?php if ( isset( $badge[0][1] ) ) : ?>
					<span><?php echo esc_html( $badge[0][1] ); ?></span>
				<?php endif; ?>
			</p>
		<?php endif; ?>
	</div>
</section>
