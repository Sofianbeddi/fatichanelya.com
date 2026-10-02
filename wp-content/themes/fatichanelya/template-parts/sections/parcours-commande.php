<?php
/**
 * « Comment ça se passe » — le parcours de commande en trois étapes.
 *
 * Se place sous la grille de la boutique et des pages de catégorie : la
 * visiteuse apprend qu'on ne paie pas en ligne avant de le découvrir dans le
 * tiroir, et l'apprend comme une façon de faire, pas comme une excuse.
 *
 * Trois cellules à filets, numéros en grand corps sans zéro devant. Les
 * textes sont ceux du blueprint (section S4 de la boutique).
 *
 * @package Fatichanelya
 */

defined( 'ABSPATH' ) || exit;

$etapes = array(
	array(
		__( 'Vous remplissez votre panier', 'fatichanelya' ),
		__( 'Ajoutez les produits qui vous intéressent, sans engagement.', 'fatichanelya' ),
	),
	array(
		__( 'On confirme ensemble par message', 'fatichanelya' ),
		__( 'Disponibilité, frais de port réels et total, avant tout paiement.', 'fatichanelya' ),
	),
	array(
		__( 'Vous recevez chez vous', 'fatichanelya' ),
		__( 'Fati expédie depuis son propre stock, en Espagne.', 'fatichanelya' ),
	),
);
?>
<section class="section parcours" aria-labelledby="parcours-title">
	<header class="section-head">
		<h2 id="parcours-title"><?php esc_html_e( 'Comment ça se passe', 'fatichanelya' ); ?></h2>
	</header>

	<?php // La liste ordonnée porte déjà le rang : le numéro visible est décoratif. ?>
	<ol class="parcours-etapes">
		<?php foreach ( $etapes as $i => $etape ) : ?>
			<li class="rule-cell reveal" style="--i:<?php echo esc_attr( $i ); ?>">
				<span class="rule-num" aria-hidden="true"><?php echo (int) $i + 1; ?></span>
				<h3><?php echo esc_html( $etape[0] ); ?></h3>
				<p><?php echo esc_html( $etape[1] ); ?></p>
			</li>
		<?php endforeach; ?>
	</ol>

	<p class="parcours-note">
		<?php esc_html_e( 'Le paiement en ligne arrive. En attendant, tout se règle après confirmation.', 'fatichanelya' ); ?>
	</p>
</section>
