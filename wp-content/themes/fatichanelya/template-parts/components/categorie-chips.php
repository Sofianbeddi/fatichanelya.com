<?php
/**
 * Cercles de catégories (thème 1.6.0, remplace les pilules de 1.5.0).
 *
 * Quatre catégories, quatre cercles en une rangée : le visuel du terme
 * (`_fati_visuel`, le même que les ronds de l'accueil) dans un rond, le nom
 * dessous (à côté sur grand écran dans la boutique). Pas de bouton « Tout
 * voir » : un cercle se sélectionne, et se désélectionne pour tout afficher.
 *
 * Trois emplois, un seul composant :
 * - la boutique : boutons de filtre tenus par le JavaScript (bascule) ;
 * - une page de catégorie : liens, la courante marquée — son lien mène à la
 *   boutique, ce qui revient à la désélectionner ;
 * - l'accueil sur mobile et tablette : liens, posés au-dessus du hero.
 *
 *     set_query_var( 'fati_chips', array( 'mode' => 'filtres' ) );                   // boutique
 *     set_query_var( 'fati_chips', array( 'mode' => 'liens', 'actuelle' => $slug ) ); // catégorie
 *     get_template_part( 'template-parts/components/categorie-chips' );
 *
 * Le nombre de cercles est publié dans `--n` : la grille mobile suit si une
 * cinquième catégorie apparaît.
 *
 * @package Fatichanelya
 */

defined( 'ABSPATH' ) || exit;

$args = wp_parse_args(
	get_query_var( 'fati_chips', array() ),
	array(
		'mode'     => 'liens',   // « liens » ou « filtres »
		'actuelle' => '',        // slug de la catégorie courante (liens)
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
$boutique = get_post_type_archive_link( 'produit' );
$vignette = static function ( $cat ) {
	$visuel = (int) get_term_meta( $cat->term_id, '_fati_visuel', true );
	if ( $visuel ) {
		$img = wp_get_attachment_image(
			$visuel,
			'thumbnail',
			false,
			array(
				'alt'      => '',
				'loading'  => 'lazy',
				'decoding' => 'async',
				'sizes'    => '80px',
			)
		);
	} else {
		// Sans visuel : l'initiale du nom, en bleu nuit sur sable.
		$img = '<span class="cat-circle-initiale" aria-hidden="true">' . esc_html( mb_substr( $cat->name, 0, 1 ) ) . '</span>';
	}
	return '<span class="cat-circle-media">' . $img . '</span>';
};
?>
<<?php echo $balise; // phpcs:ignore WordPress.Security.EscapeOutput ?> class="filters <?php echo esc_attr( $args['classe'] ); ?>" style="--n:<?php echo (int) count( $categories ); ?>"<?php echo $filtres ? ' role="group"' : ''; ?> aria-label="<?php echo esc_attr( $label ); ?>">
	<?php foreach ( $categories as $cat ) : ?>
		<?php $active = $cat->slug === $args['actuelle']; ?>
		<?php if ( $filtres ) : ?>
			<button class="cat-circle" type="button" data-filter="<?php echo esc_attr( $cat->slug ); ?>" aria-pressed="false">
				<?php echo $vignette( $cat ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<span class="cat-circle-label"><?php echo esc_html( $cat->name ); ?></span>
			</button>
		<?php else : ?>
			<a class="cat-circle<?php echo $active ? ' is-active' : ''; ?>" href="<?php echo esc_url( $active ? $boutique : get_term_link( $cat ) ); ?>"<?php echo $active ? ' aria-current="page"' : ''; ?>>
				<?php echo $vignette( $cat ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<span class="cat-circle-label"><?php echo esc_html( $cat->name ); ?></span>
			</a>
		<?php endif; ?>
	<?php endforeach; ?>
</<?php echo $balise; // phpcs:ignore WordPress.Security.EscapeOutput ?>>
