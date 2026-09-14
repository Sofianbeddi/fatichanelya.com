<?php
/**
 * Page simple — mentions légales, confidentialité, livraison.
 *
 * @package Fatichanelya
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<main id="main" class="section page-simple">
	<?php while ( have_posts() ) : the_post(); ?>
		<article>
			<header class="section-head">
				<h1><?php the_title(); ?></h1>
			</header>
			<div class="prose"><?php the_content(); ?></div>
		</article>
	<?php endwhile; ?>
</main>
<?php
get_footer();
