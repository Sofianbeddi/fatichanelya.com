<?php
/**
 * Bannière de titre de page, avec fil d'Ariane.
 *
 * Chaque page intérieure entre en matière de la même façon. Le titre et le
 * chemin sont passés par `set_query_var` avant l'appel :
 *
 *     set_query_var( 'fati_banner_title', 'Boutique' );
 *     set_query_var( 'fati_banner_trail', array( array( 'label' => 'Boutique' ) ) );
 *     get_template_part( 'template-parts/components/page-banner' );
 *
 * Chaque étape du chemin est un tableau `label` et, sauf pour la dernière,
 * `url`. L'accueil est ajouté ici, il n'a pas à être répété à chaque appel.
 *
 * @package Fatichanelya
 */

defined( 'ABSPATH' ) || exit;

$titre = get_query_var( 'fati_banner_title' );
$trail = get_query_var( 'fati_banner_trail' );

if ( ! $titre ) {
	return;
}

$etapes = array_merge(
	array(
		array(
			'label' => __( 'Accueil', 'fatichanelya' ),
			'url'   => home_url( '/' ),
		),
	),
	is_array( $trail ) ? $trail : array()
);

$derniere = count( $etapes ) - 1;
?>
<section class="page-banner">
	<h1><?php echo esc_html( $titre ); ?></h1>

	<nav class="breadcrumb" aria-label="<?php esc_attr_e( 'Fil d\'Ariane', 'fatichanelya' ); ?>">
		<?php foreach ( $etapes as $i => $etape ) : ?>
			<?php if ( $i > 0 ) : ?>
				<span aria-hidden="true">/</span>
			<?php endif; ?>

			<?php if ( $i === $derniere || empty( $etape['url'] ) ) : ?>
				<span aria-current="page"><?php echo esc_html( $etape['label'] ); ?></span>
			<?php else : ?>
				<a href="<?php echo esc_url( $etape['url'] ); ?>"><?php echo esc_html( $etape['label'] ); ?></a>
			<?php endif; ?>
		<?php endforeach; ?>
	</nav>
</section>
