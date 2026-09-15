<?php
/**
 * Carte produit. Utilisée sur l'accueil et sur les archives.
 *
 * @package Fatichanelya
 */

defined( 'ABSPATH' ) || exit;

$id     = get_the_ID();
$terms  = get_the_terms( $id, 'categorie_produit' );
$cat    = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0] : null;
$prix   = get_post_meta( $id, '_fati_prix', true );
$index  = (int) get_query_var( 'fati_index', 0 );
$indispo = (bool) get_post_meta( $id, '_fati_indispo', true );
?>
<li class="product-card reveal"
    style="--i:<?php echo esc_attr( $index % 6 ); ?>"
    data-cat="<?php echo esc_attr( $cat ? $cat->slug : '' ); ?>"
    data-name="<?php echo esc_attr( mb_strtolower( get_the_title() ) ); ?>"
    data-id="<?php echo esc_attr( $id ); ?>">

	<button class="product-media" type="button" data-open="<?php echo esc_attr( $id ); ?>"
	        aria-label="<?php echo esc_attr( sprintf( __( 'Voir la fiche : %s', 'fatichanelya' ), get_the_title() ) ); ?>">
		<?php echo fati_produit_image( $id ); ?>
		<?php if ( $indispo ) : ?>
			<span class="product-flag"><?php esc_html_e( 'Visuel à venir', 'fatichanelya' ); ?></span>
		<?php endif; ?>
	</button>

	<div class="product-body">
		<?php if ( $cat ) : ?>
			<p class="product-cat"><?php echo esc_html( $cat->name ); ?></p>
		<?php endif; ?>

		<h3 class="product-name"><?php the_title(); ?></h3>

		<?php if ( $prix ) : ?>
			<p class="product-price"><?php echo esc_html( fati_format_prix( $prix ) ); ?></p>
		<?php endif; ?>

		<?php
		/*
		 * Libellé court : « Ajouter à ma sélection » demande 194 px de texte
		 * pour une boîte de 162 à 183 px dès que la grille passe à trois ou
		 * quatre colonnes. Le nom du produit part dans le nom accessible, donc
		 * une lectrice d'écran entend l'intention complète.
		 */
		?>
		<button class="product-add" type="button" data-add="<?php echo esc_attr( $id ); ?>"
		        aria-label="<?php echo esc_attr( sprintf( __( 'Ajouter %s à ma sélection', 'fatichanelya' ), get_the_title() ) ); ?>">
			<?php esc_html_e( 'Ajouter', 'fatichanelya' ); ?>
		</button>
	</div>
</li>
