<?php
/**
 * Fiche d'une formation.
 *
 * Le prix et le format ne sont pas publics : la conversion passe par un
 * message WhatsApp prérempli, pas par un panier. Aucun champ n'est inventé —
 * un bloc dont la donnée manque ne s'affiche pas.
 *
 * @package Fatichanelya
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();

	$niveau = get_post_meta( get_the_ID(), '_fati_niveau', true );
	$duree  = get_post_meta( get_the_ID(), '_fati_duree', true );
	$titre  = get_the_title();
	?>
<main id="main" class="section formation-single">
	<nav class="breadcrumb" aria-label="<?php esc_attr_e( 'Fil d\'Ariane', 'fatichanelya' ); ?>">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Accueil', 'fatichanelya' ); ?></a>
		<span aria-hidden="true">/</span>
		<a href="<?php echo esc_url( get_post_type_archive_link( 'formation' ) ); ?>"><?php esc_html_e( 'Formations', 'fatichanelya' ); ?></a>
	</nav>

	<article class="formation-body">
		<header class="formation-head">
			<p class="eyebrow"><?php esc_html_e( 'L\'accompagnement par Fati', 'fatichanelya' ); ?></p>
			<h1><?php echo esc_html( $titre ); ?></h1>

			<?php if ( $niveau || $duree ) : ?>
				<dl class="formation-facts">
					<?php if ( $niveau ) : ?>
						<div>
							<dt><?php esc_html_e( 'Niveau', 'fatichanelya' ); ?></dt>
							<dd><?php echo esc_html( $niveau ); ?></dd>
						</div>
					<?php endif; ?>
					<?php if ( $duree ) : ?>
						<div>
							<dt><?php esc_html_e( 'Durée', 'fatichanelya' ); ?></dt>
							<dd><?php echo esc_html( $duree ); ?></dd>
						</div>
					<?php endif; ?>
					<div>
						<dt><?php esc_html_e( 'Format', 'fatichanelya' ); ?></dt>
						<dd><?php esc_html_e( 'À distance, avec Fati', 'fatichanelya' ); ?></dd>
					</div>
				</dl>
			<?php endif; ?>
		</header>

		<div class="prose">
			<?php the_content(); ?>
		</div>

		<aside class="formation-cta">
			<h2><?php esc_html_e( 'Demander le programme', 'fatichanelya' ); ?></h2>
			<p><?php esc_html_e( 'Le programme détaillé, les dates et le tarif vous sont envoyés par message. Vous décidez ensuite, sans engagement.', 'fatichanelya' ); ?></p>
			<a class="button button-gold button-lg"
			   href="<?php echo esc_url( fati_wa( sprintf( __( 'Bonjour Fati, je souhaite le programme et le tarif de la formation « %s ».', 'fatichanelya' ), $titre ) ) ); ?>"
			   <?php echo fati_opt( 'whatsapp' ) ? 'target="_blank" rel="noopener"' : ''; ?>>
				<?php echo fati_icon( 'whatsapp', 18 ); ?><?php esc_html_e( 'Écrire à Fati', 'fatichanelya' ); ?>
			</a>
			<p class="formation-note"><?php esc_html_e( 'Réponse sous 24 h en semaine.', 'fatichanelya' ); ?></p>
		</aside>
	</article>

	<?php
	$autres = new WP_Query(
		array(
			'post_type'      => 'formation',
			'posts_per_page' => 3,
			'post__not_in'   => array( get_the_ID() ),
			'orderby'        => array( 'menu_order' => 'ASC' ),
			'no_found_rows'  => true,
		)
	);

	if ( $autres->have_posts() ) :
		?>
		<section class="formation-autres" aria-labelledby="autres-title">
			<h2 id="autres-title"><?php esc_html_e( 'Les autres formations', 'fatichanelya' ); ?></h2>
			<ul class="training-cards">
				<?php
				while ( $autres->have_posts() ) :
					$autres->the_post();
					$n    = get_post_meta( get_the_ID(), '_fati_niveau', true );
					$d    = get_post_meta( get_the_ID(), '_fati_duree', true );
					$chip = trim( implode( ' · ', array_filter( array( $n, $d ) ) ) );
					?>
					<li class="training-card">
						<?php if ( $chip ) : ?>
							<p class="chip"><?php echo esc_html( $chip ); ?></p>
						<?php endif; ?>
						<h3 class="training-card-title">
							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						</h3>
						<span class="arrow-link" aria-hidden="true">
							<?php esc_html_e( 'Voir le programme', 'fatichanelya' ); ?><?php echo fati_icon( 'arrow', 15 ); ?>
						</span>
					</li>
				<?php endwhile; ?>
			</ul>
		</section>
		<?php
		wp_reset_postdata();
	endif;
	?>
</main>
	<?php
endwhile;

get_footer();
