<?php
defined( 'ABSPATH' ) || exit;
$items = fati_opt_lines( 'trust' );
if ( ! $items ) {
	return;
}
$icones = array( 'shield', 'card', 'whatsapp', 'leaf' );
?>
<section class="trust" aria-label="<?php esc_attr_e( 'Nos engagements', 'fatichanelya' ); ?>">
	<ul>
		<?php foreach ( $items as $i => $item ) : ?>
			<li>
				<?php echo fati_icon( $icones[ $i % count( $icones ) ], 20 ); ?>
				<strong><?php echo esc_html( $item[0] ); ?></strong>
				<span><?php echo esc_html( isset( $item[1] ) ? $item[1] : '' ); ?></span>
			</li>
		<?php endforeach; ?>
	</ul>
</section>
