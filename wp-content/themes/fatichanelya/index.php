<?php
/**
 * Repli générique. Rarement atteint : les archives et les pages ont leurs templates.
 *
 * @package Fatichanelya
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<main id="main" class="section">
	<header class="section-head">
		<h1><?php echo esc_html( wp_get_document_title() ); ?></h1>
	</header>

	<?php if ( have_posts() ) : ?>
		<ul class="journal-grid">
			<?php while ( have_posts() ) : the_post(); ?>
				<li class="reveal">
					<a class="journal-card" href="<?php the_permalink(); ?>">
						<?php the_post_thumbnail( 'fati-paysage', array( 'loading' => 'lazy', 'decoding' => 'async', 'alt' => get_the_title() ) ); ?>
						<span class="journal-meta"><em><?php echo esc_html( get_the_date() ); ?></em><?php the_title(); ?></span>
					</a>
				</li>
			<?php endwhile; ?>
		</ul>
		<?php the_posts_pagination( array( 'mid_size' => 1 ) ); ?>
	<?php else : ?>
		<p class="section-intro"><?php esc_html_e( 'Aucun contenu pour le moment.', 'fatichanelya' ); ?></p>
	<?php endif; ?>
</main>
<?php
get_footer();
