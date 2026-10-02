<?php
/**
 * Carte produit. Utilisée sur l'accueil, la boutique, les catégories et la
 * fiche produit (produits associés).
 *
 * Deux gestes, et chacun mène où on l'attend : le visuel et le nom ouvrent la
 * fiche du produit, le bouton ajoute au panier. La carte n'ouvre plus de
 * fenêtre par-dessus la page : une fiche existe, c'est elle qu'on va lire.
 *
 * @package Fatichanelya
 */

defined( 'ABSPATH' ) || exit;

$id     = get_the_ID();
$nom    = get_the_title();
$url    = get_permalink();
$terms  = get_the_terms( $id, 'categorie_produit' );
$cat    = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0] : null;
$prix   = get_post_meta( $id, '_fati_prix', true );
$format = trim( (string) get_post_meta( $id, '_fati_format', true ) );
$index  = (int) get_query_var( 'fati_index', 0 );
$indispo = (bool) get_post_meta( $id, '_fati_indispo', true );
?>
<li class="product-card reveal"
    style="--i:<?php echo esc_attr( $index % 6 ); ?>"
    data-cat="<?php echo esc_attr( $cat ? $cat->slug : '' ); ?>"
    data-name="<?php echo esc_attr( mb_strtolower( $nom ) ); ?>"
    data-id="<?php echo esc_attr( $id ); ?>">

	<?php
	// Le nom porte déjà le lien pour le clavier et les lecteurs d'écran : le
	// visuel mène au même endroit, il sort donc de l'ordre de tabulation
	// plutôt que d'annoncer deux fois la même destination.
	?>
	<a class="product-media" href="<?php echo esc_url( $url ); ?>" tabindex="-1" aria-hidden="true">
		<?php echo fati_produit_image( $id ); ?>
		<?php if ( $indispo ) : ?>
			<span class="product-flag"><?php esc_html_e( 'Visuel à venir', 'fatichanelya' ); ?></span>
		<?php endif; ?>
	</a>

	<div class="product-body">
		<?php if ( $cat ) : ?>
			<p class="product-cat"><?php echo esc_html( $cat->name ); ?></p>
		<?php endif; ?>

		<h3 class="product-name"><a href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $nom ); ?></a></h3>

		<?php if ( $format ) : ?>
			<p class="product-format"><?php echo esc_html( $format ); ?></p>
		<?php endif; ?>

		<?php
		// Sans prix en base, la ligne reste à sa place et le dit : une carte
		// muette sur le prix se lit comme une erreur, pas comme une réserve.
		?>
		<p class="product-price<?php echo $prix ? '' : ' product-price--demande'; ?>">
			<?php echo $prix ? esc_html( fati_format_prix( $prix ) ) : esc_html__( 'Sur demande', 'fatichanelya' ); ?>
		</p>

		<?php
		/*
		 * Le bouton et le sélecteur de quantité occupent la même place : au
		 * premier ajout le bouton cède sa place à « − 1 + », qui dit à la fois
		 * que le produit est dans le panier et combien il y en a.
		 *
		 * Libellé court : « Ajouter au panier » ne tient pas dans une boîte de
		 * 162 à 183 px dès que la grille passe à trois ou quatre colonnes. Le
		 * nom du produit part dans le nom accessible.
		 */
		?>
		<div class="product-buy">
			<button class="product-add" type="button" data-add="<?php echo esc_attr( $id ); ?>"
			        aria-label="<?php echo esc_attr( sprintf( __( 'Ajouter %s au panier', 'fatichanelya' ), $nom ) ); ?>">
				<?php echo fati_icon( 'bag', 18 ); ?><?php esc_html_e( 'Ajouter', 'fatichanelya' ); ?>
			</button>
			<?php echo fati_quantite( $id, $nom, 'qty--plein' ); ?>
		</div>
	</div>
</li>
