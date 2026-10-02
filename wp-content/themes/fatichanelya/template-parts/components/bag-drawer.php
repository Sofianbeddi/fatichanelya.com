<?php
/**
 * Tiroir du panier, ouvert depuis l'icône de l'en-tête.
 *
 * Il sert à vérifier et ajuster sans quitter la page : chaque ligne porte son
 * sélecteur de quantité, son montant et son bouton de retrait. Les lignes
 * sont dessinées par le JavaScript à partir du panier enregistré dans le
 * navigateur ; rien n'est stocké côté serveur.
 *
 * Le paiement en ligne n'est pas branché : la commande part en message à
 * Fati, qui confirme disponibilité, frais de port et total.
 *
 * @package Fatichanelya
 */

defined( 'ABSPATH' ) || exit;

$panier = fati_panier_url();
?>
<div class="drawer-backdrop" id="drawer-backdrop" hidden></div>

<div class="drawer" id="bag-drawer" role="dialog" aria-modal="true" aria-labelledby="bag-title" hidden>
	<div class="drawer-head">
		<h2 id="bag-title"><?php esc_html_e( 'Mon panier', 'fatichanelya' ); ?></h2>
		<button class="icon-button" id="close-bag" type="button" aria-label="<?php esc_attr_e( 'Fermer le panier', 'fatichanelya' ); ?>">
			<?php echo fati_icon( 'close', 24 ); ?>
		</button>
	</div>

	<ul class="drawer-list" id="drawer-list"></ul>

	<div class="drawer-empty" id="drawer-empty">
		<p><?php esc_html_e( 'Votre panier est vide.', 'fatichanelya' ); ?></p>
		<a class="arrow-link" href="<?php echo esc_url( get_post_type_archive_link( 'produit' ) ); ?>">
			<?php esc_html_e( 'Voir la boutique', 'fatichanelya' ); ?><?php echo fati_icon( 'arrow', 20 ); ?>
		</a>
	</div>

	<div class="drawer-foot" id="drawer-foot" hidden>
		<p class="drawer-total">
			<?php // Le libellé change quand un article est sans prix : « hors articles à confirmer ». ?>
			<span id="drawer-total-label"><?php esc_html_e( 'Total indicatif', 'fatichanelya' ); ?></span>
			<strong id="drawer-total">0 €</strong>
		</p>
		<a class="button button-gold button-block" id="drawer-wa" href="<?php echo esc_url( fati_wa() ); ?>"
		   <?php echo fati_whatsapp_actif() ? 'target="_blank" rel="noopener"' : ''; ?>>
			<?php echo fati_icon( 'whatsapp', 22 ); ?><?php esc_html_e( 'Commander sur WhatsApp', 'fatichanelya' ); ?>
		</a>
		<?php if ( $panier ) : ?>
			<a class="button button-outline button-block" href="<?php echo esc_url( $panier ); ?>">
				<?php esc_html_e( 'Voir le panier', 'fatichanelya' ); ?>
			</a>
		<?php endif; ?>
		<p class="drawer-note">
			<?php esc_html_e( 'Pas de paiement en ligne pour l\'instant : Fati confirme la disponibilité, les frais de port et le total par message.', 'fatichanelya' ); ?>
		</p>
	</div>
</div>

<?php
// Confirmation d'ajout : visuel, nom, quantité, et l'accès au panier.
?>
<div class="toast" id="toast" role="status" aria-live="polite" hidden>
	<span class="toast-media" id="toast-media" aria-hidden="true"></span>
	<p class="toast-copy">
		<strong><?php echo fati_icon( 'check', 18 ); ?><?php esc_html_e( 'Ajouté au panier', 'fatichanelya' ); ?></strong>
		<span id="toast-text"></span>
	</p>
	<button class="toast-action" id="toast-action" type="button"><?php esc_html_e( 'Voir le panier', 'fatichanelya' ); ?></button>
</div>

<script type="application/json" id="fati-produits"><?php
	echo wp_json_encode( fati_produits_json(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP );
?></script>
