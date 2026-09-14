<?php
defined( 'ABSPATH' ) || exit;
$items = fati_opt_lines( 'pledge' );
if ( ! $items ) {
	return;
}
?>
<section class="section pledge" aria-labelledby="pledge-title">
	<header class="section-head">
		<p class="eyebrow light"><?php esc_html_e( 'Une relation claire', 'fatichanelya' ); ?></p>
		<h2 id="pledge-title"><?php echo wp_kses( fati_accent( __( 'Du conseil *avant la pression d\'acheter*.', 'fatichanelya' ) ), array( 'em' => array() ) ); ?></h2>
	</header>
	<ul class="pledge-grid">
		<?php foreach ( $items as $i => $item ) : ?>
			<li class="reveal" style="--i:<?php echo esc_attr( $i ); ?>">
				<strong><?php echo esc_html( $item[0] ); ?></strong>
				<p><?php echo esc_html( isset( $item[1] ) ? $item[1] : '' ); ?></p>
			</li>
		<?php endforeach; ?>
	</ul>
</section>
