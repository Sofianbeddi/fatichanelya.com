<?php
/**
 * Hero de la page d'accueil.
 *
 * Composition reprise de la maquette validée par le gérant : texte à gauche
 * sur fond beige, portrait de Fati à droite en pleine hauteur, fondu entre les
 * deux. La signature se pose en bas du portrait.
 *
 * Le titre tient sur deux lignes, la seconde en or : c'est l'élément LCP, il
 * ne porte donc aucune animation et l'image est chargée en priorité.
 *
 * @package Fatichanelya
 */

defined( 'ABSPATH' ) || exit;

$portrait = fati_opt( 'hero_image' );
?>
<section class="hero<?php echo $portrait ? '' : ' hero--sans-portrait'; ?>" aria-labelledby="hero-title">
	<div class="hero-copy">
		<?php if ( fati_opt( 'hero_eyebrow' ) ) : ?>
			<p class="eyebrow"><?php echo esc_html( fati_opt( 'hero_eyebrow' ) ); ?></p>
		<?php endif; ?>

		<h1 id="hero-title"><?php echo wp_kses( fati_accent( fati_opt( 'hero_titre' ) ), array( 'em' => array() ) ); ?></h1>

		<?php if ( fati_opt( 'hero_intro' ) ) : ?>
			<p class="lead"><?php echo esc_html( fati_opt( 'hero_intro' ) ); ?></p>
		<?php endif; ?>

		<div class="hero-actions">
			<a class="button button-navy button-lg" href="<?php echo esc_url( get_post_type_archive_link( 'produit' ) ); ?>">
				<?php echo esc_html( fati_opt( 'hero_cta1' ) ); ?><?php echo fati_icon( 'arrow', 22 ); ?>
			</a>
			<a class="text-button" href="<?php echo esc_url( get_post_type_archive_link( 'formation' ) ); ?>">
				<?php echo esc_html( fati_opt( 'hero_cta2' ) ); ?><?php echo fati_icon( 'arrow', 20 ); ?>
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

	<?php if ( $portrait ) : ?>
		<figure class="hero-portrait">
			<?php
			// Élément LCP : chargement prioritaire, jamais différé.
			echo fati_image(
				$portrait,
				'fati-portrait',
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
</section>
