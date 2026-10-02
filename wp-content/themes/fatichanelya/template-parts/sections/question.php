<?php
/**
 * « Une question avant de choisir ? » — bande sombre qui ferme la boutique.
 *
 * Rattrape celle qui n'a pas trouvé, ou qui hésite entre plusieurs produits :
 * un bouton WhatsApp prérempli, et le lien vers « Livraison et retours », la
 * page qu'une acheteuse prudente lit avant d'écrire.
 *
 * @package Fatichanelya
 */

defined( 'ABSPATH' ) || exit;

$orienter  = fati_wa( __( 'Bonjour Fati, j\'hésite entre plusieurs produits, pouvez-vous m\'orienter ?', 'fatichanelya' ) );
$cible     = fati_opt( 'whatsapp' ) ? ' target="_blank" rel="noopener"' : '';
$livraison = get_page_by_path( 'livraison-retours' );
?>
<section class="section section--navy question" aria-labelledby="question-title">
	<h2 id="question-title"><?php esc_html_e( 'Une question avant de choisir ?', 'fatichanelya' ); ?></h2>

	<div class="question-actions">
		<a class="button button-gold button-lg" href="<?php echo esc_url( $orienter ); ?>"<?php echo $cible; ?>>
			<?php echo fati_icon( 'whatsapp', 22 ); ?><?php esc_html_e( 'Poser une question', 'fatichanelya' ); ?>
		</a>
		<?php if ( $livraison ) : ?>
			<a class="arrow-link arrow-link--light" href="<?php echo esc_url( get_permalink( $livraison ) ); ?>">
				<?php esc_html_e( 'Livraison et retours', 'fatichanelya' ); ?><?php echo fati_icon( 'arrow', 20 ); ?>
			</a>
		<?php endif; ?>
	</div>
</section>
