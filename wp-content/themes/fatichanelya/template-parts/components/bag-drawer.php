<?php defined( 'ABSPATH' ) || exit; ?>
<div class="drawer-backdrop" id="drawer-backdrop" hidden></div>

<div class="drawer" id="bag-drawer" role="dialog" aria-modal="true" aria-labelledby="bag-title" hidden>
	<div class="drawer-head">
		<h2 id="bag-title"><?php esc_html_e( 'Ma sélection', 'fatichanelya' ); ?></h2>
		<button class="icon-button" id="close-bag" aria-label="<?php esc_attr_e( 'Fermer ma sélection', 'fatichanelya' ); ?>">
			<?php echo fati_icon( 'close', 24 ); ?>
		</button>
	</div>

	<ul class="drawer-list" id="drawer-list"></ul>

	<p class="drawer-empty" id="drawer-empty">
		<?php esc_html_e( 'Votre sélection est vide. Ajoutez des produits pour préparer votre message à Fati.', 'fatichanelya' ); ?>
	</p>

	<div class="drawer-foot">
		<p class="drawer-total">
			<?php esc_html_e( 'Total indicatif', 'fatichanelya' ); ?>
			<strong id="drawer-total">0 €</strong>
		</p>
		<a class="button button-gold button-block" id="drawer-wa" href="#" target="_blank" rel="noopener">
			<?php esc_html_e( 'Envoyer ma sélection sur WhatsApp', 'fatichanelya' ); ?>
		</a>
		<?php
		// Le tiroir suffit pour deux ou trois références ; au-delà, la page
		// dédiée laisse ajuster les quantités confortablement.
		$page_selection = get_page_by_path( 'ma-selection' );
		if ( $page_selection ) :
			?>
			<a class="text-button drawer-voir" href="<?php echo esc_url( get_permalink( $page_selection ) ); ?>">
				<?php esc_html_e( 'Voir ma sélection en détail', 'fatichanelya' ); ?>
			</a>
		<?php endif; ?>
		<p class="drawer-note">
			<?php esc_html_e( 'Le paiement en ligne n\'est pas encore activé : la commande est confirmée avec Fati par message.', 'fatichanelya' ); ?>
		</p>
	</div>
</div>
