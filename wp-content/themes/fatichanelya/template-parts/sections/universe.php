<?php defined( 'ABSPATH' ) || exit; ?>
<section class="section universe" aria-labelledby="universe-title">
	<header class="section-head">
		<p class="eyebrow"><?php bloginfo( 'name' ); ?></p>
		<h2 id="universe-title"><?php echo wp_kses( fati_accent( __( 'Deux façons d\'avancer, *un même univers*.', 'fatichanelya' ) ), array( 'em' => array() ) ); ?></h2>
	</header>

	<div class="universe-grid">
		<a class="universe-card reveal" style="--i:0" href="#shop">
			<div class="universe-copy">
				<span class="chip"><?php esc_html_e( 'La sélection Fatichanelya', 'fatichanelya' ); ?></span>
				<h3><?php esc_html_e( 'Boutique bien-être', 'fatichanelya' ); ?></h3>
				<p><?php esc_html_e( 'Des essentiels choisis pour une routine simple et lisible.', 'fatichanelya' ); ?></p>
				<strong class="arrow-link"><?php echo esc_html( fati_opt( 'hero_cta1' ) ); ?><?php echo fati_icon( 'arrow', 16 ); ?></strong>
			</div>

			<?php
			$vedettes = get_posts(
				array(
					'post_type'      => 'produit',
					'posts_per_page' => 2,
					'meta_key'       => '_thumbnail_id',
					'orderby'        => 'rand',
					'no_found_rows'  => true,
				)
			);
			?>
			<?php if ( $vedettes ) : ?>
				<div class="universe-thumbs" aria-hidden="true">
					<?php foreach ( $vedettes as $v ) : ?>
						<?php echo get_the_post_thumbnail( $v, 'fati-produit', array( 'alt' => '', 'loading' => 'lazy', 'decoding' => 'async' ) ); ?>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</a>

		<a class="universe-card universe-card--dark reveal" style="--i:1" href="#training">
			<?php if ( fati_opt( 'training_image' ) ) : ?>
				<?php echo fati_image( fati_opt( 'training_image' ), 'fati-paysage', '', false, 'universe-bg' ); ?>
			<?php endif; ?>
			<div class="universe-copy">
				<span class="chip chip--light"><?php esc_html_e( 'L\'accompagnement par Fati', 'fatichanelya' ); ?></span>
				<h3><?php esc_html_e( 'Formations concrètes', 'fatichanelya' ); ?></h3>
				<p><?php esc_html_e( 'E-commerce, vente, IA et digital, avec un plan d\'action.', 'fatichanelya' ); ?></p>
				<strong class="arrow-link"><?php echo esc_html( fati_opt( 'hero_cta2' ) ); ?><?php echo fati_icon( 'arrow', 16 ); ?></strong>
			</div>
		</a>
	</div>
</section>
