<?php
/**
 * Page simple — mentions légales, confidentialité, livraison.
 *
 * @package Fatichanelya
 */

defined( 'ABSPATH' ) || exit;

get_header();

set_query_var( 'fati_banner_title', get_the_title() );
set_query_var( 'fati_banner_trail', array( array( 'label' => get_the_title() ) ) );
get_template_part( 'template-parts/components/page-banner' );
?>
<main id="main" class="section page-simple">
	<?php while ( have_posts() ) : the_post(); ?>
		<article>
			<div class="prose"><?php the_content(); ?></div>
		</article>
	<?php endwhile; ?>
</main>
<?php
get_footer();
