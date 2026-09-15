<?php
/**
 * Bandeau de réassurance, juste avant le pied de page.
 *
 * La référence annonce ici « livraison offerte dès 50 € ». Nous ne le faisons
 * pas : Fati n'a pas encore fixé ses tarifs de port, et annoncer un seuil qui
 * n'existe pas se retournerait contre elle à la première commande.
 *
 * Les trois promesses ci-dessous sont vraies aujourd'hui, telles quelles.
 *
 * @package Fatichanelya
 */

defined( 'ABSPATH' ) || exit;

$promesses = array(
	array(
		'icone' => 'box',
		'titre' => __( 'Expédié par Fati', 'fatichanelya' ),
		'texte' => __( 'Vos produits partent de son propre stock.', 'fatichanelya' ),
	),
	array(
		'icone' => 'wallet',
		'titre' => __( 'Total confirmé avant paiement', 'fatichanelya' ),
		'texte' => __( 'Disponibilité et frais de port réels, annoncés avant.', 'fatichanelya' ),
	),
	array(
		'icone' => 'support',
		'titre' => __( 'Une vraie réponse', 'fatichanelya' ),
		'texte' => __( 'Fati répond elle-même, sous 24 h en semaine.', 'fatichanelya' ),
	),
);
?>
<section class="assurance" aria-label="<?php esc_attr_e( 'Nos engagements', 'fatichanelya' ); ?>">
	<ul>
		<?php foreach ( $promesses as $p ) : ?>
			<li>
				<?php echo fati_icon( $p['icone'], 34 ); ?>
				<strong><?php echo esc_html( $p['titre'] ); ?></strong>
				<span><?php echo esc_html( $p['texte'] ); ?></span>
			</li>
		<?php endforeach; ?>
	</ul>
</section>
