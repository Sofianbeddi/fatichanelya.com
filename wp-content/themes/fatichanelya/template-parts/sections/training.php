<?php
/**
 * Formations à distance — accueil.
 *
 * Jusqu'au thème 1.2.1, la section alignait six cellules à filets à côté
 * d'une nature morte : 1 500 px de liste pour six programmes. Elle devient une
 * scène : à gauche, les programmes en rail numéroté (de vrais liens vers
 * chaque fiche, que le JavaScript transforme en onglets sur grand écran) ; à
 * droite, l'affiche du programme choisi, avec une capsule de verre qui ne
 * porte que l'appel à l'action — titre et chip sont déjà dans le rail, le
 * résumé est sur la fiche (retour du gérant du 25 oct. 2026 : ni numéros, ni
 * texte sur l'affiche, une flèche sur chaque ligne). Sur mobile, le rail
 * disparaît, la carte reprend chip, titre et résumé sous l'affiche, et
 * les six affiches défilent en carrousel, carte suivante visible, barre de
 * progression et compteur.
 *
 * Fati revend ces formations : la section dit « à distance », jamais qui les
 * anime. Aucun prix, aucune durée inventée — le chip ne montre que ce qui est
 * saisi dans la fiche.
 *
 * @package Fatichanelya
 */

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

$programmes = array();
while ( $formations->have_posts() ) {
	$formations->the_post();
	$niveau = get_post_meta( get_the_ID(), '_fati_niveau', true );
	$duree  = get_post_meta( get_the_ID(), '_fati_duree', true );

	$programmes[] = array(
		'id'      => get_the_ID(),
		'titre'   => get_the_title(),
		'url'     => get_permalink(),
		'extrait' => has_excerpt() ? get_the_excerpt() : '',
		'chip'    => trim( implode( ' · ', array_filter( array( $niveau, $duree ) ) ) ),
		'image'   => has_post_thumbnail() ? get_the_post_thumbnail(
			get_the_ID(),
			'fati-paysage',
			array(
				'alt'      => '',
				'loading'  => 'lazy',
				'decoding' => 'async',
				'sizes'    => '(min-width:1000px) 50vw, 80vw',
			)
		) : '',
	);
}
wp_reset_postdata();

$total = count( $programmes );
?>
<section class="section training" id="training" aria-labelledby="training-title">
	<header class="section-head section-head--split">
		<div>
			<p class="eyebrow"><?php esc_html_e( 'Formations à distance', 'fatichanelya' ); ?></p>
			<h2 id="training-title"><?php echo wp_kses( fati_accent( fati_opt( 'training_titre' ) ), array( 'em' => array() ) ); ?></h2>
		</div>
		<div class="training-head-aside">
			<p class="section-intro"><?php echo esc_html( fati_opt( 'training_intro' ) ); ?></p>
			<a class="arrow-link" href="<?php echo esc_url( get_post_type_archive_link( 'formation' ) ); ?>">
				<?php esc_html_e( 'Toutes les formations', 'fatichanelya' ); ?><?php echo fati_icon( 'arrow', 20 ); ?>
			</a>
		</div>
	</header>

	<div class="programmes" data-programmes>
		<?php
		// Rail des programmes. Ce sont des liens vers les fiches : sans
		// JavaScript, chaque clic mène quelque part. Sur grand écran, le script
		// leur donne le rôle d'onglets et le clic change l'affiche à droite.
		?>
		<ol class="programmes-rail" data-prog-liste>
			<?php foreach ( $programmes as $i => $p ) : ?>
				<li>
					<a class="programmes-tab<?php echo 0 === $i ? ' is-active' : ''; ?>" id="prog-tab-<?php echo (int) $p['id']; ?>"
					   href="<?php echo esc_url( $p['url'] ); ?>" data-prog-tab="<?php echo (int) $p['id']; ?>">
						<span class="programmes-tab-copy">
							<span class="programmes-tab-titre"><?php echo esc_html( $p['titre'] ); ?></span>
							<?php if ( $p['chip'] ) : ?>
								<span class="programmes-tab-chip"><?php echo esc_html( $p['chip'] ); ?></span>
							<?php endif; ?>
						</span>
						<?php echo fati_icon( 'arrow', 20 ); ?>
					</a>
				</li>
			<?php endforeach; ?>
		</ol>

		<div class="programmes-scene carousel" data-carousel>
			<?php // Commandes du carrousel mobile : masquées sur grand écran, où le rail les remplace. ?>
			<div class="carousel-nav programmes-nav">
				<div class="carousel-progres" aria-hidden="true">
					<span class="carousel-barre" data-carousel-barre></span>
					<span class="carousel-compte" data-carousel-compte>1 / <?php echo (int) $total; ?></span>
				</div>
				<button class="carousel-bouton carousel-prec" type="button" data-carousel-prec
				        aria-label="<?php esc_attr_e( 'Programme précédent', 'fatichanelya' ); ?>">
					<?php echo fati_icon( 'arrow', 22 ); ?>
				</button>
				<button class="carousel-bouton carousel-suiv" type="button" data-carousel-suiv
				        aria-label="<?php esc_attr_e( 'Programme suivant', 'fatichanelya' ); ?>">
					<?php echo fati_icon( 'arrow', 22 ); ?>
				</button>
			</div>

			<ul class="programmes-piste" data-carousel-piste tabindex="0" role="region"
			    aria-label="<?php esc_attr_e( 'Programmes de formation', 'fatichanelya' ); ?>">
				<?php foreach ( $programmes as $i => $p ) : ?>
					<li class="programme<?php echo 0 === $i ? ' is-active' : ''; ?>" id="prog-<?php echo (int) $p['id']; ?>"
					    data-prog="<?php echo (int) $p['id']; ?>" style="--i:<?php echo (int) $i; ?>">
						<figure class="programme-media<?php echo $p['image'] ? '' : ' programme-media--vide'; ?>" aria-hidden="true">
							<?php echo $p['image']; // phpcs:ignore WordPress.Security.EscapeOutput ?>
						</figure>
						<div class="programme-panneau">
							<?php if ( $p['chip'] ) : ?>
								<span class="chip chip--light"><?php echo esc_html( $p['chip'] ); ?></span>
							<?php endif; ?>
							<h3 class="programme-titre"><?php echo esc_html( $p['titre'] ); ?></h3>
							<?php if ( $p['extrait'] ) : ?>
								<p class="programme-extrait"><?php echo esc_html( $p['extrait'] ); ?></p>
							<?php endif; ?>
							<a class="button button-gold programme-cta" href="<?php echo esc_url( $p['url'] ); ?>">
								<?php esc_html_e( 'Voir le programme', 'fatichanelya' ); ?><?php echo fati_icon( 'arrow', 22 ); ?>
								<span class="sr-only"><?php echo esc_html( ' : ' . $p['titre'] ); ?></span>
							</a>
						</div>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
</section>
