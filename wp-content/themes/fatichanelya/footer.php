<?php
/**
 * Pied de page.
 *
 * @package Fatichanelya
 */

defined( 'ABSPATH' ) || exit;
?>
<footer class="site-footer">
	<div class="footer-top">
		<div class="footer-brand">
			<p class="wordmark wordmark--light"><?php bloginfo( 'name' ); ?></p>
			<p><?php bloginfo( 'description' ); ?></p>
			<?php if ( fati_opt( 'tiktok' ) ) : ?>
				<a class="arrow-link arrow-link--light" href="<?php echo esc_url( fati_opt( 'tiktok' ) ); ?>" target="_blank" rel="noopener">
					TikTok<?php echo fati_icon( 'external', 15 ); ?>
				</a>
			<?php endif; ?>
		</div>

		<nav class="footer-nav" aria-label="<?php esc_attr_e( 'Navigation du pied de page', 'fatichanelya' ); ?>">
			<div>
				<h2><?php esc_html_e( 'Navigation', 'fatichanelya' ); ?></h2>
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'pied_nav',
						'container'      => false,
						'depth'          => 1,
						'fallback_cb'    => false,
					)
				);
				?>
			</div>
			<div>
				<h2><?php esc_html_e( 'Informations', 'fatichanelya' ); ?></h2>
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'pied_infos',
						'container'      => false,
						'depth'          => 1,
						'fallback_cb'    => false,
					)
				);
				?>
			</div>
		</nav>

		<div class="newsletter">
			<h2><?php esc_html_e( 'Recevez les nouveautés, sans bruit inutile.', 'fatichanelya' ); ?></h2>
			<p class="newsletter-help">
				<?php esc_html_e( 'Inscription à brancher sur l\'outil d\'emailing avant la mise en ligne.', 'fatichanelya' ); ?>
			</p>
		</div>
	</div>

	<?php if ( fati_opt( 'mention_sante' ) ) : ?>
		<p class="disclaimer"><?php echo esc_html( fati_opt( 'mention_sante' ) ); ?></p>
	<?php endif; ?>

	<div class="footer-bottom">
		<p>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?></p>
		<?php $langues = fati_languages(); ?>
		<?php if ( count( $langues ) > 1 ) : ?>
			<p><?php echo esc_html( implode( ' · ', array_map( fn( $l ) => strtoupper( $l['slug'] ), $langues ) ) ); ?></p>
		<?php endif; ?>
	</div>
</footer>

<?php get_template_part( 'template-parts/components/bag-drawer' ); ?>
<?php get_template_part( 'template-parts/components/product-dialog' ); ?>

<p class="toast" id="toast" role="status" aria-live="polite" hidden></p>

<?php wp_footer(); ?>
</body>
</html>
