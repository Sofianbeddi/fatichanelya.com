<?php
/**
 * Page d'accueil — assemblage des sections validées en maquette v3.
 *
 * @package Fatichanelya
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<main id="main">
	<?php
	foreach ( array( 'hero', 'trust', 'categories', 'shop-apercu', 'training', 'about', 'pledge', 'faq', 'contact' ) as $section ) {
		get_template_part( 'template-parts/sections/' . $section );
	}
	?>
</main>
<?php
get_footer();
