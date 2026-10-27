<?php
/**
 * Boutons de catégories avec visuel (thème 1.5.0).
 *
 * Trois emplois, un seul composant : la rangée du hero sur mobile (liens, pour
 * atteindre une catégorie sans défiler), les filtres de la boutique (boutons
 * tenus par le JavaScript) et la navigation entre catégories sur une page de
 * catégorie (liens, la catégorie courante marquée). Le visuel est celui du
 * terme (`_fati_visuel`, le même que les ronds de l'accueil), servi en
 * vignette de 150 px pour un rond de 28 à 34 px.
 *
 *     set_query_var( 'fati_chips', array( 'mode' => 'filtres' ) );              // boutique
 *     set_query_var( 'fati_chips', array( 'mode' => 'liens', 'actuelle' => $slug ) ); // catégorie
 *     get_template_part( 'template-parts/components/categorie-chips' );
 *
 * @package Fatichanelya
 */

defined( 'ABSPATH' ) || exit;

$args = wp_parse_args(
	get_query_var( 'fati_chips', array() ),
	array(
		'mode'     => 'liens',   // « liens » ou « filtres »
		'actuelle' => '',        // slug de la catégorie courante (liens)
		'tout'     => true,      // bouton « Tout voir » en tête
		'classe'   => '',
		'label'    => '',
	)
);

$categories = get_terms(
	array(
		'taxonomy'   => 'categorie_produit',
		'hide_empty' => true,
		'orderby'    => 'count',
		'order'      => 'DESC',
	)
);

if ( is_wp_error( $categories ) || ! $categories ) {
	return;
}

$filtres  = 'filtres' === $args['mode'];
$balise   = $filtres ? 'div' : 'nav';
$label    = $args['label'] ? $args['label'] : ( $filtres ? __( 'Filtrer par catégorie', 'fatichanelya' ) : __( 'Catégories de produits', 'fatichanelya' ) );
$vignette = static function ( $cat ) {
	$visuel = (int) get_term_meta( $cat->term_id, '_fati_visuel', true );
	if ( ! $visuel ) {
		return '';
	}
	return '<span class="chip-media">' . wp_get_attachment_image(
		$visuel,
		'thumbnail',
		false,
		array(
			'alt'      => '',
			'loading'  => 'lazy',
			'decoding' => 'async',
			'sizes'    => '34px',
		)
	) . '</span>';
};
$tout_actif = '' === $args['actuelle'] && is_post_type_archive( 'produit' );
?>
<<?php echo $balise; // phpcs:ignore WordPress.Security.EscapeOutput ?> class="filters <?php echo esc_attr( $args['classe'] ); ?>"<?php echo $filtres ? ' role="group"' : ''; ?> aria-label="<?php echo esc_attr( $label ); ?>">
	<?php if ( $args['tout'] ) : ?>
		<?php if ( $filtres ) : ?>
			<button class="chip-button is-active" type="button" data-filter="all" aria-pressed="true"><?php esc_html_e( 'Tout voir', 'fatichanelya' ); ?></button>
		<?php else : ?>
			<a class="chip-button<?php echo $tout_actif ? ' is-active' : ''; ?>" href="<?php echo esc_url( get_post_type_archive_link( 'produit' ) ); ?>"<?php echo $tout_actif ? ' aria-current="page"' : ''; ?>><?php esc_html_e( 'Tout voir', 'fatichanelya' ); ?></a>
		<?php endif; ?>
	<?php endif; ?>
	<?php foreach ( $categories as $cat ) : ?>
		<?php $active = $cat->slug === $args['actuelle']; ?>
		<?php if ( $filtres ) : ?>
			<button class="chip-button" type="button" data-filter="<?php echo esc_attr( $cat->slug ); ?>" aria-pressed="false"><?php echo $vignette( $cat ); // phpcs:ignore WordPress.Security.EscapeOutput ?><?php echo esc_html( $cat->name ); ?></button>
		<?php else : ?>
			<a class="chip-button<?php echo $active ? ' is-active' : ''; ?>" href="<?php echo esc_url( get_term_link( $cat ) ); ?>"<?php echo $active ? ' aria-current="page"' : ''; ?>><?php echo $vignette( $cat ); // phpcs:ignore WordPress.Security.EscapeOutput ?><?php echo esc_html( $cat->name ); ?></a>
		<?php endif; ?>
	<?php endforeach; ?>
</<?php echo $balise; // phpcs:ignore WordPress.Security.EscapeOutput ?>>
