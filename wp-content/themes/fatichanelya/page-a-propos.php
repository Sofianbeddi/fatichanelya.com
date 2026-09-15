<?php
/**
 * Page « À propos ».
 *
 * Elle répond à trois questions qu'une visiteuse venue de TikTok se pose
 * avant d'acheter : c'est qui, est-ce du MLM, est-ce sérieux. La troisième
 * section répond à la deuxième par l'absence, ce qui est vérifiable en
 * parcourant le site : on ne recrute personne, on ne parle ni de revenus
 * ni d'équipe.
 *
 * Le parcours daté de Fati n'existe pas encore. Il n'est pas inventé : la
 * section qui l'accueillera ne s'affiche pas tant que la donnée manque.
 *
 * @package Fatichanelya
 */

defined( 'ABSPATH' ) || exit;

get_header();

set_query_var( 'fati_banner_title', __( 'À propos', 'fatichanelya' ) );
set_query_var( 'fati_banner_trail', array( array( 'label' => __( 'À propos', 'fatichanelya' ) ) ) );
get_template_part( 'template-parts/components/page-banner' );
?>
<main id="main" class="section page-apropos">
	<header class="apropos-head">
		<p class="eyebrow"><?php esc_html_e( 'À propos de Fati', 'fatichanelya' ); ?></p>
		<h2><?php echo wp_kses( fati_accent( fati_opt( 'about_titre' ) ), array( 'em' => array() ) ); ?></h2>
		<p class="apropos-intro"><?php echo esc_html( fati_opt( 'about_intro' ) ); ?></p>
	</header>

	<?php if ( fati_opt( 'about_image' ) ) : ?>
		<figure class="apropos-portrait">
			<?php
			echo fati_image(
				fati_opt( 'about_image' ),
				'fati-portrait',
				sprintf( __( 'Fati, créatrice de %s', 'fatichanelya' ), get_bloginfo( 'name' ) )
			);
			?>
			<figcaption>
				<strong>Fati</strong>
				<span><?php esc_html_e( 'Entrepreneure · Créatrice · Mentore', 'fatichanelya' ); ?></span>
			</figcaption>
		</figure>
	<?php endif; ?>

	<?php $valeurs = fati_opt_lines( 'about_valeurs' ); ?>
	<?php if ( $valeurs ) : ?>
		<section class="apropos-valeurs" aria-labelledby="valeurs-title">
			<h2 id="valeurs-title"><?php esc_html_e( 'Comment je travaille', 'fatichanelya' ); ?></h2>
			<ul>
				<?php foreach ( $valeurs as $i => $v ) : ?>
					<li>
						<span class="rule-num"><?php echo esc_html( $i + 1 ); ?></span>
						<strong><?php echo esc_html( $v[0] ); ?></strong>
						<p><?php echo esc_html( isset( $v[1] ) ? $v[1] : '' ); ?></p>
					</li>
				<?php endforeach; ?>
			</ul>
		</section>
	<?php endif; ?>

	<section class="apropos-limites" aria-labelledby="limites-title">
		<h2 id="limites-title"><?php esc_html_e( 'Ce que je ne fais pas', 'fatichanelya' ); ?></h2>
		<ul>
			<li><?php esc_html_e( 'Je ne recrute personne. Ce site vend des produits et des formations, il ne propose aucune activité de distribution.', 'fatichanelya' ); ?></li>
			<li><?php esc_html_e( 'Je ne promets aucun revenu, et je ne parle ni de gains ni d\'équipe.', 'fatichanelya' ); ?></li>
			<li><?php esc_html_e( 'Je ne donne aucun conseil médical. Les compléments alimentaires ne soignent pas et ne remplacent pas l\'avis d\'un professionnel de santé.', 'fatichanelya' ); ?></li>
			<li><?php esc_html_e( 'Je ne publie aucun avis client tant que je n\'en ai pas de véritables.', 'fatichanelya' ); ?></li>
		</ul>
	</section>

	<aside class="apropos-cta">
		<h2><?php esc_html_e( 'Une question avant de commander ?', 'fatichanelya' ); ?></h2>
		<p><?php esc_html_e( 'Écrivez-moi directement. Je réponds moi-même, sous 24 h en semaine.', 'fatichanelya' ); ?></p>
		<div class="apropos-actions">
			<a class="button button-gold button-lg"
			   href="<?php echo esc_url( fati_wa( __( 'Bonjour Fati, j\'aimerais échanger avec vous.', 'fatichanelya' ) ) ); ?>"
			   <?php echo fati_opt( 'whatsapp' ) ? 'target="_blank" rel="noopener"' : ''; ?>>
				<?php echo fati_icon( 'whatsapp', 18 ); ?><?php esc_html_e( 'Écrire sur WhatsApp', 'fatichanelya' ); ?>
			</a>
			<?php if ( fati_opt( 'tiktok' ) ) : ?>
				<a class="button button-outline" href="<?php echo esc_url( fati_opt( 'tiktok' ) ); ?>" target="_blank" rel="noopener">
					<?php esc_html_e( 'Voir mon TikTok', 'fatichanelya' ); ?><?php echo fati_icon( 'external', 16 ); ?>
				</a>
			<?php endif; ?>
		</div>
	</aside>
</main>
<?php
get_footer();
