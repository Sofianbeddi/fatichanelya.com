<?php
/**
 * Pied de page.
 *
 * Trois temps : la marque et la façon de la joindre, les deux menus, puis le
 * copyright et la signature de l'agence. Le gérant a demandé un pied simple
 * (25 oct. 2026) : la colonne « En bref », la mention santé et le nom en très
 * grand corps en sont sortis ; la mention, obligatoire pour des compléments
 * alimentaires, reste sur la fiche produit.
 *
 * L'ancien bloc « newsletter » est retiré : aucun outil d'envoi n'est branché,
 * et un formulaire qui ne collecte rien est un clic mort. Le jour où un outil
 * existe, il reprend sa place dans la colonne de la marque.
 *
 * @package Fatichanelya
 */

defined( 'ABSPATH' ) || exit;

// Le bandeau de réassurance ferme chaque page, juste avant le pied.
get_template_part( 'template-parts/components/assurance' );

$cible = fati_whatsapp_actif() ? ' target="_blank" rel="noopener"' : '';
?>
<footer class="site-footer">
	<div class="footer-top">
		<div class="footer-brand">
			<p class="wordmark wordmark--light"><?php bloginfo( 'name' ); ?></p>
			<p class="footer-tagline"><?php bloginfo( 'description' ); ?></p>
			<p class="footer-mot"><?php esc_html_e( 'Une question avant de commander ? Écrivez-moi : je réponds moi-même, sous 24 h en semaine.', 'fatichanelya' ); ?></p>
			<div class="footer-actions">
				<a class="button button-gold" href="<?php echo esc_url( fati_wa( __( 'Bonjour Fati, j\'aimerais échanger avec vous.', 'fatichanelya' ) ) ); ?>"<?php echo $cible; ?>>
					<?php echo fati_icon( 'whatsapp', 22 ); ?><?php esc_html_e( 'Écrire sur WhatsApp', 'fatichanelya' ); ?>
				</a>
				<?php if ( fati_opt( 'tiktok' ) || fati_opt( 'instagram' ) ) : ?>
					<ul class="footer-social" aria-label="<?php esc_attr_e( 'Réseaux sociaux', 'fatichanelya' ); ?>">
						<?php if ( fati_opt( 'tiktok' ) ) : ?>
							<li>
								<a href="<?php echo esc_url( fati_opt( 'tiktok' ) ); ?>" target="_blank" rel="noopener">
									<?php echo fati_icon( 'tiktok', 20 ); ?>TikTok
								</a>
							</li>
						<?php endif; ?>
						<?php if ( fati_opt( 'instagram' ) ) : ?>
							<li>
								<a href="<?php echo esc_url( fati_opt( 'instagram' ) ); ?>" target="_blank" rel="noopener">
									<?php echo fati_icon( 'instagram', 20 ); ?>Instagram
								</a>
							</li>
						<?php endif; ?>
					</ul>
				<?php endif; ?>
			</div>
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
	</div>

	<div class="footer-bottom">
		<p>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?></p>
		<p class="footer-velix">
			<?php esc_html_e( 'Site conçu par', 'fatichanelya' ); ?>
			<a href="https://velixdigital.com/" target="_blank" rel="noopener">Velix Digital</a>
		</p>
		<?php
		set_query_var( 'fati_langue', array( 'mode' => 'liste' ) );
		get_template_part( 'template-parts/components/langue' );
		?>
	</div>

</footer>

<?php get_template_part( 'template-parts/components/bag-drawer' ); ?>

<?php wp_footer(); ?>
</body>
</html>
