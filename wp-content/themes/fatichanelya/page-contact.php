<?php
/**
 * Page « Contact ».
 *
 * Un seul canal, WhatsApp, et on le dit franchement. Pas de formulaire :
 * il n'existe aucun service d'envoi connecté, et un formulaire qui perd
 * les messages fait plus de mal que pas de formulaire du tout.
 *
 * @package Fatichanelya
 */

defined( 'ABSPATH' ) || exit;

get_header();

set_query_var( 'fati_banner_title', __( 'Contact', 'fatichanelya' ) );
set_query_var( 'fati_banner_trail', array( array( 'label' => __( 'Contact', 'fatichanelya' ) ) ) );
get_template_part( 'template-parts/components/page-banner' );

$cible = fati_opt( 'whatsapp' ) ? ' target="_blank" rel="noopener"' : '';
?>
<main id="main" class="section page-contact">
	<header class="contact-head">
		<p class="eyebrow"><?php esc_html_e( 'WhatsApp · direct', 'fatichanelya' ); ?></p>
		<h2><?php echo wp_kses( fati_accent( fati_opt( 'contact_titre' ) ), array( 'em' => array() ) ); ?></h2>
		<p class="contact-intro"><?php echo esc_html( fati_opt( 'contact_intro' ) ); ?></p>
	</header>

	<section class="contact-choix" aria-labelledby="choix-title">
		<h2 id="choix-title" class="sr-only"><?php esc_html_e( 'Choisir le bon message', 'fatichanelya' ); ?></h2>
		<ul>
			<li>
				<strong><?php esc_html_e( 'Une question sur un produit', 'fatichanelya' ); ?></strong>
				<p><?php esc_html_e( 'Disponibilité, usage, composition : dites-moi lequel vous intéresse.', 'fatichanelya' ); ?></p>
				<a class="arrow-link"
				   href="<?php echo esc_url( fati_wa( __( 'Bonjour Fati, j\'ai une question sur un produit.', 'fatichanelya' ) ) ); ?>"<?php echo $cible; ?>>
					<?php esc_html_e( 'Poser ma question', 'fatichanelya' ); ?><?php echo fati_icon( 'arrow', 20 ); ?>
				</a>
			</li>
			<li>
				<strong><?php esc_html_e( 'Commander', 'fatichanelya' ); ?></strong>
				<p><?php esc_html_e( 'On confirme ensemble la disponibilité, les frais de port réels et le total avant tout paiement.', 'fatichanelya' ); ?></p>
				<a class="arrow-link"
				   href="<?php echo esc_url( fati_wa( __( 'Bonjour Fati, je souhaite passer une commande.', 'fatichanelya' ) ) ); ?>"<?php echo $cible; ?>>
					<?php esc_html_e( 'Préparer ma commande', 'fatichanelya' ); ?><?php echo fati_icon( 'arrow', 20 ); ?>
				</a>
			</li>
			<li>
				<strong><?php esc_html_e( 'Une formation', 'fatichanelya' ); ?></strong>
				<p><?php esc_html_e( 'Programme, dates et tarif vous sont envoyés par message, sans engagement.', 'fatichanelya' ); ?></p>
				<a class="arrow-link"
				   href="<?php echo esc_url( fati_wa( __( 'Bonjour Fati, je souhaite des informations sur vos formations.', 'fatichanelya' ) ) ); ?>"<?php echo $cible; ?>>
					<?php esc_html_e( 'Demander le programme', 'fatichanelya' ); ?><?php echo fati_icon( 'arrow', 20 ); ?>
				</a>
			</li>
		</ul>
	</section>

	<aside class="contact-cta">
		<a class="button button-gold button-lg"
		   href="<?php echo esc_url( fati_wa( __( 'Bonjour Fati, j\'aimerais échanger avec vous.', 'fatichanelya' ) ) ); ?>"<?php echo $cible; ?>>
			<?php echo fati_icon( 'whatsapp', 24 ); ?><?php esc_html_e( 'Écrire sur WhatsApp', 'fatichanelya' ); ?>
		</a>
		<p class="contact-note"><?php esc_html_e( 'Réponse sous 24 h en semaine. Aucun conseil médical n\'est donné par message.', 'fatichanelya' ); ?></p>
	</aside>

	<?php if ( fati_opt( 'tiktok' ) ) : ?>
		<section class="contact-ailleurs" aria-labelledby="ailleurs-title">
			<h2 id="ailleurs-title"><?php esc_html_e( 'Me suivre ailleurs', 'fatichanelya' ); ?></h2>
			<p><?php esc_html_e( 'Le quotidien, les nouveautés et les coulisses passent surtout par TikTok.', 'fatichanelya' ); ?></p>
			<a class="button button-outline" href="<?php echo esc_url( fati_opt( 'tiktok' ) ); ?>" target="_blank" rel="noopener">
				<?php esc_html_e( 'Voir mon TikTok', 'fatichanelya' ); ?><?php echo fati_icon( 'external', 20 ); ?>
			</a>
		</section>
	<?php endif; ?>
</main>
<?php
get_footer();
