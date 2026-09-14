<?php defined( 'ABSPATH' ) || exit; ?>
<form role="search" method="get" class="search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<?php echo fati_icon( 'search', 17 ); ?>
	<label class="sr-only" for="s"><?php esc_html_e( 'Rechercher sur le site', 'fatichanelya' ); ?></label>
	<input id="s" type="search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php esc_attr_e( 'Rechercher…', 'fatichanelya' ); ?>">
</form>
