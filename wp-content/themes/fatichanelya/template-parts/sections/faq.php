<?php
defined( 'ABSPATH' ) || exit;
$items = fati_opt_lines( 'faq' );
if ( ! $items ) {
	return;
}
?>
<section class="section faq" id="faq" aria-labelledby="faq-title">
	<header class="section-head">
		<p class="eyebrow"><?php esc_html_e( 'Questions fréquentes', 'fatichanelya' ); ?></p>
		<h2 id="faq-title"><?php echo wp_kses( fati_accent( __( 'Tout ce qu\'il *faut savoir*.', 'fatichanelya' ) ), array( 'em' => array() ) ); ?></h2>
	</header>
	<div class="faq-list">
		<?php foreach ( $items as $item ) : ?>
			<details name="faq">
				<summary><?php echo esc_html( $item[0] ); ?></summary>
				<div><p><?php echo esc_html( isset( $item[1] ) ? $item[1] : '' ); ?></p></div>
			</details>
		<?php endforeach; ?>
	</div>
</section>
