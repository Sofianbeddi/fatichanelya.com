<?php defined( 'ABSPATH' ) || exit; ?>
<section class="section about" id="about" aria-labelledby="about-title">
	<div class="about-gallery">
		<?php if ( fati_opt( 'about_image' ) ) : ?>
			<figure class="about-main reveal">
				<?php echo fati_image( fati_opt( 'about_image' ), 'fati-portrait', __( 'Fati, créatrice de Fatichanelya', 'fatichanelya' ) ); ?>
			</figure>
		<?php endif; ?>

		<?php if ( fati_opt( 'about_avatar' ) ) : ?>
			<figure class="about-avatar">
				<?php echo fati_image( fati_opt( 'about_avatar' ), 'fati-avatar', __( 'Photo de profil de Fati', 'fatichanelya' ) ); ?>
			</figure>
		<?php endif; ?>

		<p class="about-stat">
			<strong>Fati</strong>
			<span><?php esc_html_e( 'Entrepreneure · Créatrice · Mentore', 'fatichanelya' ); ?></span>
		</p>
	</div>

	<div class="about-copy">
		<p class="eyebrow"><?php esc_html_e( 'À propos de Fati', 'fatichanelya' ); ?></p>
		<h2 id="about-title"><?php echo wp_kses( fati_accent( fati_opt( 'about_titre' ) ), array( 'em' => array() ) ); ?></h2>
		<p class="section-intro"><?php echo esc_html( fati_opt( 'about_intro' ) ); ?></p>

		<?php $valeurs = fati_opt_lines( 'about_valeurs' ); ?>
		<?php if ( $valeurs ) : ?>
			<ul class="values">
				<?php foreach ( $valeurs as $v ) : ?>
					<li>
						<strong><?php echo esc_html( $v[0] ); ?></strong>
						<span><?php echo esc_html( isset( $v[1] ) ? $v[1] : '' ); ?></span>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>

		<?php if ( fati_opt( 'tiktok' ) ) : ?>
			<a class="button button-outline" href="<?php echo esc_url( fati_opt( 'tiktok' ) ); ?>" target="_blank" rel="noopener">
				<?php esc_html_e( 'Suivre Fati sur TikTok', 'fatichanelya' ); ?><?php echo fati_icon( 'external', 16 ); ?>
			</a>
		<?php endif; ?>
	</div>
</section>
