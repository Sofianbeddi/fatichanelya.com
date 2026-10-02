<?php
/**
 * Page « Ma sélection » — l'équivalent du panier, sans paiement en ligne.
 *
 * Le paiement n'est pas branché : cette page ne prétend pas encaisser. Elle
 * récapitule la sélection, laisse ajuster les quantités, et prépare un message
 * WhatsApp complet pour que Fati confirme disponibilité, frais de port réels
 * et total avant tout paiement.
 *
 * Le jour où PayPal sera actif, c'est le bouton de fin de page qui change,
 * pas la structure. Le contenu est rendu par le JavaScript à partir de la
 * sélection enregistrée dans le navigateur : rien n'est stocké côté serveur.
 *
 * @package Fatichanelya
 */

defined( 'ABSPATH' ) || exit;

get_header();

set_query_var( 'fati_banner_title', __( 'Ma sélection', 'fatichanelya' ) );
set_query_var( 'fati_banner_trail', array( array( 'label' => __( 'Ma sélection', 'fatichanelya' ) ) ) );
get_template_part( 'template-parts/components/page-banner' );
?>
<main id="main" class="section page-selection">
	<div class="selection-layout">
		<section class="selection-liste" aria-labelledby="selection-title">
			<h2 id="selection-title" class="sr-only"><?php esc_html_e( 'Produits sélectionnés', 'fatichanelya' ); ?></h2>

			<div class="selection-head" aria-hidden="true">
				<span><?php esc_html_e( 'Produit', 'fatichanelya' ); ?></span>
				<span><?php esc_html_e( 'Prix', 'fatichanelya' ); ?></span>
				<span><?php esc_html_e( 'Quantité', 'fatichanelya' ); ?></span>
				<span><?php esc_html_e( 'Sous-total', 'fatichanelya' ); ?></span>
			</div>

			<ul class="selection-items" id="selection-items"></ul>

			<p class="selection-vide" id="selection-vide" hidden>
				<?php esc_html_e( 'Votre sélection est vide pour le moment.', 'fatichanelya' ); ?>
				<a class="arrow-link" href="<?php echo esc_url( get_post_type_archive_link( 'produit' ) ); ?>">
					<?php esc_html_e( 'Voir le catalogue', 'fatichanelya' ); ?><?php echo fati_icon( 'arrow', 20 ); ?>
				</a>
			</p>

			<div class="selection-actions" id="selection-actions" hidden>
				<a class="text-button" href="<?php echo esc_url( get_post_type_archive_link( 'produit' ) ); ?>">
					<?php esc_html_e( 'Continuer mes achats', 'fatichanelya' ); ?>
				</a>
				<button class="text-button" type="button" id="selection-vider">
					<?php esc_html_e( 'Vider ma sélection', 'fatichanelya' ); ?>
				</button>
			</div>
		</section>

		<aside class="selection-resume" aria-labelledby="resume-title">
			<h2 id="resume-title"><?php esc_html_e( 'Récapitulatif', 'fatichanelya' ); ?></h2>

			<dl class="selection-totaux">
				<div>
					<dt><?php esc_html_e( 'Articles', 'fatichanelya' ); ?></dt>
					<dd id="resume-articles">0</dd>
				</div>
				<div>
					<?php // Le libellé change quand un article est sans prix : « hors articles à confirmer ». ?>
					<dt id="resume-soustotal-label"><?php esc_html_e( 'Sous-total', 'fatichanelya' ); ?></dt>
					<dd id="resume-soustotal">—</dd>
				</div>
				<div>
					<dt><?php esc_html_e( 'Livraison', 'fatichanelya' ); ?></dt>
					<dd><?php esc_html_e( 'confirmée par message', 'fatichanelya' ); ?></dd>
				</div>
			</dl>

			<p class="selection-note">
				<?php esc_html_e( 'Les frais de port dépendent de votre pays. Fati vous donne le total exact avant tout paiement.', 'fatichanelya' ); ?>
			</p>

			<a class="button button-gold button-lg button-block" id="selection-wa"
			   href="<?php echo esc_url( fati_wa( __( 'Bonjour Fati, je souhaite commander.', 'fatichanelya' ) ) ); ?>"
			   <?php echo fati_opt( 'whatsapp' ) ? 'target="_blank" rel="noopener"' : ''; ?>>
				<?php echo fati_icon( 'whatsapp', 24 ); ?><?php esc_html_e( 'Envoyer ma sélection à Fati', 'fatichanelya' ); ?>
			</a>

			<p class="selection-paiement">
				<?php esc_html_e( 'Le paiement en ligne n\'est pas encore activé. Je préfère vous le dire plutôt que de faire semblant.', 'fatichanelya' ); ?>
			</p>
		</aside>
	</div>
</main>
<?php
get_footer();
