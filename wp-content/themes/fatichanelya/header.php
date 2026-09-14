<?php
/**
 * En-tête du document.
 *
 * @package Fatichanelya
 */

defined( 'ABSPATH' ) || exit;
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="theme-color" content="#FBF8F3">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link" href="#main"><?php esc_html_e( 'Aller au contenu principal', 'fatichanelya' ); ?></a>
<span id="scroll-sentinel" aria-hidden="true"></span>

<aside class="announcement" aria-label="<?php esc_attr_e( 'Informations générales', 'fatichanelya' ); ?>">
	<p><?php echo esc_html( fati_opt( 'annonce' ) ); ?></p>
	<a href="<?php echo esc_url( fati_wa() ); ?>"<?php echo fati_opt( 'whatsapp' ) ? ' target="_blank" rel="noopener"' : ''; ?>>
		<?php echo fati_icon( 'whatsapp', 14 ); ?><?php echo esc_html( fati_opt( 'annonce_cta' ) ); ?>
	</a>
</aside>

<header class="site-header" id="header">
	<a class="wordmark" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( sprintf( __( '%s, accueil', 'fatichanelya' ), get_bloginfo( 'name' ) ) ); ?>">
		<?php bloginfo( 'name' ); ?>
	</a>

	<?php
	wp_nav_menu(
		array(
			'theme_location' => 'principal',
			'container'      => 'nav',
			'container_class'=> 'primary-nav',
			'container_aria_label' => __( 'Navigation principale', 'fatichanelya' ),
			'menu_class'     => '',
			'items_wrap'     => '%3$s',
			'depth'          => 1,
			'fallback_cb'    => false,
		)
	);
	?>

	<div class="header-tools">
		<?php $langues = fati_languages(); ?>
		<?php if ( count( $langues ) > 1 ) : ?>
			<div class="lang">
				<?php echo fati_icon( 'globe', 17 ); ?>
				<label class="sr-only" for="lang-select"><?php esc_html_e( 'Langue du site', 'fatichanelya' ); ?></label>
				<select id="lang-select">
					<?php foreach ( $langues as $l ) : ?>
						<option value="<?php echo esc_url( $l['url'] ); ?>" <?php selected( ! empty( $l['current_lang'] ) ); ?>>
							<?php echo esc_html( strtoupper( $l['slug'] ) ); ?>
						</option>
					<?php endforeach; ?>
				</select>
			</div>
		<?php endif; ?>

		<button class="icon-button bag-button" id="open-bag" aria-label="<?php esc_attr_e( 'Ma sélection : 0 article', 'fatichanelya' ); ?>" aria-expanded="false" aria-controls="bag-drawer">
			<?php echo fati_icon( 'bag', 19 ); ?>
			<span class="bag-count" id="bag-count" aria-hidden="true">0</span>
		</button>

		<a class="button button-gold header-cta" href="<?php echo esc_url( fati_wa( fati_opt( 'contact_intro' ) ? '' : '' ) ); ?>"<?php echo fati_opt( 'whatsapp' ) ? ' target="_blank" rel="noopener"' : ''; ?>>
			<?php echo esc_html( fati_opt( 'annonce_cta' ) ); ?>
		</a>

		<button class="icon-button burger" id="burger" aria-label="<?php esc_attr_e( 'Ouvrir le menu', 'fatichanelya' ); ?>" aria-expanded="false" aria-controls="mobile-panel">
			<?php echo fati_icon( 'menu', 21 ); ?>
		</button>
	</div>
</header>

<div class="mobile-panel" id="mobile-panel" hidden>
	<?php
	wp_nav_menu(
		array(
			'theme_location' => 'principal',
			'container'      => 'nav',
			'container_aria_label' => __( 'Navigation mobile', 'fatichanelya' ),
			'items_wrap'     => '%3$s',
			'depth'          => 1,
			'fallback_cb'    => false,
		)
	);
	?>
</div>
