<?php
/**
 * Bouton WhatsApp flottant (1.8.0), demandé par le gérant le 13 nov. 2026 : une
 * visiteuse qui a une question doit pouvoir la poser sans chercher le bouton du
 * pied de page.
 *
 * Trois règles :
 * - sans numéro, pas de bouton : il mènerait à la page Contact sous un logo
 *   WhatsApp ;
 * - pas sur le panier : « Commander sur WhatsApp » y envoie la commande rédigée,
 *   un second bouton WhatsApp la ferait partir sans son contenu ;
 * - le message est celui de la page : sur une fiche, Fati sait de quel produit ou
 *   de quelle formation on lui parle.
 *
 * Le script le retire quand le pied de page est à l'écran (son bouton WhatsApp
 * prend le relais, et les liens du bas restent accessibles).
 *
 * @package Fatichanelya
 */

defined( 'ABSPATH' ) || exit;

if ( ! fati_whatsapp_actif() || fati_is_page( array( 'panier', 'ma-selection' ) ) ) {
	return;
}

if ( is_singular( 'produit' ) ) {
	/* translators: %s : nom du produit. */
	$message = sprintf( __( 'Bonjour Fati, j\'ai une question sur « %s ».', 'fatichanelya' ), html_entity_decode( get_the_title( get_queried_object_id() ), ENT_QUOTES, 'UTF-8' ) );
} elseif ( is_singular( 'formation' ) ) {
	/* translators: %s : nom de la formation. */
	$message = sprintf( __( 'Bonjour Fati, je souhaite le programme et le tarif de la formation « %s ».', 'fatichanelya' ), html_entity_decode( get_the_title( get_queried_object_id() ), ENT_QUOTES, 'UTF-8' ) );
} else {
	$message = __( 'Bonjour Fati, j\'aimerais échanger avec vous.', 'fatichanelya' );
}
?>
<a class="wa-float" id="wa-float" href="<?php echo esc_url( fati_wa( $message ) ); ?>" target="_blank" rel="noopener"
   aria-label="<?php esc_attr_e( 'Une question ? Écrivez à Fati sur WhatsApp', 'fatichanelya' ); ?>">
	<span class="wa-float-label" aria-hidden="true"><?php esc_html_e( 'Une question ?', 'fatichanelya' ); ?></span>
	<?php echo fati_icon( 'whatsapp', 30 ); ?>
</a>
