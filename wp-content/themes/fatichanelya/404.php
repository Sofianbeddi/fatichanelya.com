<?php
defined( 'ABSPATH' ) || exit;
get_header();
?>
<main id="main" class="section page-simple">
	<header class="section-head">
		<p class="eyebrow"><?php esc_html_e( 'Erreur 404', 'fatichanelya' ); ?></p>
		<h1><?php echo wp_kses( fati_accent( __( 'Cette page n\'existe *plus*.', 'fatichanelya' ) ), array( 'em' => array() ) ); ?></h1>
		<p class="section-intro"><?php esc_html_e( 'Le lien est peut-être ancien. Reprenez depuis l\'accueil ou le catalogue.', 'fatichanelya' ); ?></p>
	</header>
	<div class="hero-actions">
		<a class="button button-navy" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Retour à l\'accueil', 'fatichanelya' ); ?><?php echo fati_icon( 'arrow', 17 ); ?></a>
		<a class="text-button" href="<?php echo esc_url( get_post_type_archive_link( 'produit' ) ); ?>"><?php esc_html_e( 'Voir le catalogue', 'fatichanelya' ); ?><?php echo fati_icon( 'arrow', 16 ); ?></a>
	</div>
</main>
<?php
get_footer();
