<?php defined( 'ABSPATH' ) || exit; ?>
<dialog class="product-dialog" id="product-dialog" aria-labelledby="pd-title">
	<button class="icon-button pd-close" id="pd-close" aria-label="<?php esc_attr_e( 'Fermer la fiche produit', 'fatichanelya' ); ?>">
		<?php echo fati_icon( 'close', 24 ); ?>
	</button>

	<div class="pd-body">
		<div class="pd-media" id="pd-picture"></div>
		<div class="pd-copy">
			<span class="chip" id="pd-cat"></span>
			<h2 id="pd-title"></h2>
			<p class="pd-price" id="pd-price"></p>
			<p class="pd-format" id="pd-format" hidden></p>
			<p class="pd-desc" id="pd-desc"></p>
			<div class="pd-actions">
				<button class="button button-navy" id="pd-add" type="button"><?php esc_html_e( 'Ajouter à ma sélection', 'fatichanelya' ); ?></button>
				<a class="button button-outline" id="pd-wa" href="#" target="_blank" rel="noopener"><?php esc_html_e( 'Poser une question', 'fatichanelya' ); ?></a>
			</div>
			<p class="pd-legal"><?php esc_html_e( 'Complément d\'information disponible sur l\'emballage. Ce produit ne remplace pas l\'avis d\'un professionnel de santé.', 'fatichanelya' ); ?></p>
		</div>
	</div>
</dialog>

<script type="application/json" id="fati-produits"><?php
	echo wp_json_encode( fati_produits_json(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP );
?></script>
