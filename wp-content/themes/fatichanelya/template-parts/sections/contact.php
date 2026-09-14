<?php defined( 'ABSPATH' ) || exit; ?>
<section class="section contact" id="contact" aria-labelledby="contact-title">
	<p class="eyebrow light"><?php esc_html_e( 'WhatsApp · direct', 'fatichanelya' ); ?></p>
	<h2 id="contact-title"><?php echo wp_kses( fati_accent( fati_opt( 'contact_titre' ) ), array( 'em' => array() ) ); ?></h2>
	<p class="section-intro"><?php echo esc_html( fati_opt( 'contact_intro' ) ); ?></p>

	<a class="button button-gold button-lg"
	   href="<?php echo esc_url( fati_wa( __( 'Bonjour Fati, j\'aimerais échanger avec vous.', 'fatichanelya' ) ) ); ?>"
	   <?php echo fati_opt( 'whatsapp' ) ? 'target="_blank" rel="noopener"' : ''; ?>>
		<?php echo fati_icon( 'whatsapp', 19 ); ?><?php esc_html_e( 'Écrire sur WhatsApp', 'fatichanelya' ); ?>
	</a>

	<p class="contact-note"><?php esc_html_e( 'Réponse sous 24 h en semaine. Aucun conseil médical n\'est donné par message.', 'fatichanelya' ); ?></p>
</section>
