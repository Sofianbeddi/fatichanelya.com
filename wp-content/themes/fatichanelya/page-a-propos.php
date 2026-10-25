<?php
/**
 * Page « À propos ».
 *
 * Elle répond à trois questions qu'une visiteuse venue de TikTok se pose
 * avant d'acheter : c'est qui, est-ce du MLM, est-ce sérieux. Quatre temps :
 *
 * 1. Un hero éditorial dans le cadre arrondi du site : la photo large de Fati
 *    à droite, le titre, l'intro et les faits à gauche. C'est l'élément LCP :
 *    la photo se charge en priorité et rien n'est animé. Seule page intérieure
 *    sans bannière de titre — le cadre porte le titre et le fil d'Ariane.
 * 2. Ce qu'on trouve ici, en trois colonnes à filets et numéros en grand corps,
 *    chacune menant à sa page.
 * 3. « Comment je travaille » : portrait et valeurs du back-office, sur sable.
 * 4. « Ce que je ne fais pas » en bande bleu nuit, grand corps, puis l'appel à
 *    écrire. La réponse à l'objection MLM se fait par l'absence, vérifiable en
 *    parcourant le site : on ne recrute personne, on ne parle ni de revenus ni
 *    d'équipe.
 *
 * Le parcours daté de Fati n'existe toujours pas : il n'est pas inventé. Rien
 * ici n'avance un fait qui ne soit déjà énoncé ailleurs sur le site.
 *
 * @package Fatichanelya
 */

defined( 'ABSPATH' ) || exit;

get_header();

$hero       = fati_opt( 'about_hero' ) ? fati_opt( 'about_hero' ) : fati_opt( 'about_image' );
$cible      = fati_whatsapp_actif() ? ' target="_blank" rel="noopener"' : '';
$boutique   = get_post_type_archive_link( 'produit' );
$formations = get_post_type_archive_link( 'formation' );
$tiktok     = fati_opt( 'tiktok' );

$faits = array(
	__( 'J\'expédie depuis l\'Espagne, vers toute l\'Union européenne', 'fatichanelya' ),
	__( 'Darija, français ou espagnol : je réponds dans votre langue', 'fatichanelya' ),
	__( 'Sur WhatsApp, c\'est moi qui réponds', 'fatichanelya' ),
);

$piliers = array(
	array(
		'titre' => __( 'Une sélection DXN, expédiée par moi', 'fatichanelya' ),
		'texte' => __( 'Des produits choisis dans le catalogue DXN, avec leurs noms, prix et formats. Ils partent de mon propre stock, en Espagne.', 'fatichanelya' ),
		'lien'  => $boutique,
		'label' => __( 'Voir la sélection', 'fatichanelya' ),
	),
	array(
		'titre' => __( 'Des formations à distance', 'fatichanelya' ),
		'texte' => __( 'Des programmes pour apprendre un métier du digital : marketing, contenu, vente, publicité, IA, automatisation. Le programme est publié, le format et le niveau se choisissent par message.', 'fatichanelya' ),
		'lien'  => $formations,
		'label' => __( 'Voir les formations', 'fatichanelya' ),
	),
	array(
		'titre' => __( 'TikTok, là où on se parle', 'fatichanelya' ),
		'texte' => __( 'Je parle produits, projets et quotidien d\'entrepreneure, en darija surtout, et je réponds aux questions comme je le ferais à une amie.', 'fatichanelya' ),
		'lien'  => $tiktok,
		'label' => __( 'Suivre @fatichanelya', 'fatichanelya' ),
		'externe' => true,
	),
);

$limites = array(
	__( 'Je ne recrute personne. Ce site vend des produits et des formations, il ne propose aucune activité de distribution.', 'fatichanelya' ),
	__( 'Je ne promets aucun revenu, et je ne parle ni de gains ni d\'équipe.', 'fatichanelya' ),
	__( 'Je ne donne aucun conseil médical. Les compléments alimentaires ne soignent pas et ne remplacent pas l\'avis d\'un professionnel de santé.', 'fatichanelya' ),
	__( 'Je ne publie aucun avis client tant que je n\'en ai pas de véritables.', 'fatichanelya' ),
);
?>
<main id="main" class="page-apropos">

	<section class="apropos-hero" aria-labelledby="apropos-title">
		<div class="apropos-frame<?php echo $hero ? '' : ' apropos-frame--sans-photo'; ?>">
			<div class="apropos-copy">
				<nav class="breadcrumb" aria-label="<?php esc_attr_e( 'Fil d\'Ariane', 'fatichanelya' ); ?>">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Accueil', 'fatichanelya' ); ?></a>
					<span aria-hidden="true">/</span>
					<span aria-current="page"><?php esc_html_e( 'À propos', 'fatichanelya' ); ?></span>
				</nav>
				<p class="eyebrow"><?php esc_html_e( 'À propos de Fati', 'fatichanelya' ); ?></p>
				<h1 id="apropos-title"><?php echo wp_kses( fati_accent( fati_opt( 'about_titre' ) ), array( 'em' => array() ) ); ?></h1>
				<p class="lead"><?php echo esc_html( fati_opt( 'about_intro' ) ); ?></p>
				<div class="apropos-actions">
					<a class="button button-navy button-lg" href="<?php echo esc_url( $boutique ); ?>">
						<?php esc_html_e( 'Voir la sélection', 'fatichanelya' ); ?><?php echo fati_icon( 'arrow', 22 ); ?>
					</a>
					<a class="arrow-link" href="<?php echo esc_url( fati_wa( __( 'Bonjour Fati, j\'aimerais échanger avec vous.', 'fatichanelya' ) ) ); ?>"<?php echo $cible; ?>>
						<?php echo fati_icon( 'whatsapp', 20 ); ?><?php esc_html_e( 'Écrire à Fati', 'fatichanelya' ); ?>
					</a>
				</div>
				<ul class="apropos-faits">
					<?php foreach ( $faits as $f ) : ?>
						<li><?php echo esc_html( $f ); ?></li>
					<?php endforeach; ?>
				</ul>
			</div>

			<?php if ( $hero ) : ?>
				<figure class="apropos-photo">
					<?php
					// Élément LCP de la page : chargé en priorité, jamais différé.
					echo fati_image( $hero, 'fati-hero', sprintf( __( 'Fati, créatrice de %s', 'fatichanelya' ), get_bloginfo( 'name' ) ), true );
					?>
					<figcaption>
						<strong>Fati</strong>
						<span><?php esc_html_e( 'Entrepreneure · Créatrice · Mentore', 'fatichanelya' ); ?></span>
					</figcaption>
				</figure>
			<?php endif; ?>
		</div>
	</section>

	<section class="section apropos-piliers" aria-labelledby="piliers-title">
		<header class="section-head">
			<p class="eyebrow"><?php esc_html_e( 'Ce que vous trouverez ici', 'fatichanelya' ); ?></p>
			<h2 id="piliers-title"><?php echo wp_kses( fati_accent( __( 'Des produits, des formations, et *quelqu\'un à qui parler*.', 'fatichanelya' ) ), array( 'em' => array() ) ); ?></h2>
		</header>
		<ol class="piliers">
			<?php foreach ( $piliers as $i => $p ) : ?>
				<li class="rule-cell reveal" style="--i:<?php echo (int) $i; ?>">
					<span class="rule-num" aria-hidden="true"><?php echo (int) $i + 1; ?></span>
					<h3><?php echo esc_html( $p['titre'] ); ?></h3>
					<p><?php echo esc_html( $p['texte'] ); ?></p>
					<?php if ( $p['lien'] ) : ?>
						<a class="arrow-link" href="<?php echo esc_url( $p['lien'] ); ?>"<?php echo empty( $p['externe'] ) ? '' : ' target="_blank" rel="noopener"'; ?>>
							<?php echo esc_html( $p['label'] ); ?><?php echo fati_icon( empty( $p['externe'] ) ? 'arrow' : 'external', 20 ); ?>
						</a>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ol>
	</section>

	<?php $valeurs = fati_opt_lines( 'about_valeurs' ); ?>
	<?php if ( $valeurs ) : ?>
		<section class="section section--shell apropos-methode" aria-labelledby="methode-title">
			<div class="methode-grid">
				<?php if ( fati_opt( 'about_image' ) ) : ?>
					<figure class="methode-portrait reveal">
						<?php echo fati_image( fati_opt( 'about_image' ), 'fati-portrait', __( 'Fati, souriante, en veste claire', 'fatichanelya' ) ); ?>
					</figure>
				<?php endif; ?>
				<div class="methode-copy">
					<p class="eyebrow"><?php esc_html_e( 'Ma façon de faire', 'fatichanelya' ); ?></p>
					<h2 id="methode-title"><?php esc_html_e( 'Comment je travaille', 'fatichanelya' ); ?></h2>
					<ol class="methode-liste">
						<?php foreach ( $valeurs as $i => $v ) : ?>
							<li class="reveal" style="--i:<?php echo (int) $i; ?>">
								<span class="rule-num" aria-hidden="true"><?php echo (int) $i + 1; ?></span>
								<div>
									<strong><?php echo esc_html( $v[0] ); ?></strong>
									<p><?php echo esc_html( isset( $v[1] ) ? $v[1] : '' ); ?></p>
								</div>
							</li>
						<?php endforeach; ?>
					</ol>
					<p class="methode-fin"><?php esc_html_e( 'Ce qui n\'est pas confirmé n\'est pas affiché : disponibilité, frais de port et total sont validés avec vous avant l\'envoi.', 'fatichanelya' ); ?></p>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<section class="section apropos-limites" aria-labelledby="limites-title">
		<header class="section-head">
			<p class="eyebrow light"><?php esc_html_e( 'Une relation claire', 'fatichanelya' ); ?></p>
			<h2 id="limites-title"><?php esc_html_e( 'Ce que je ne fais pas', 'fatichanelya' ); ?></h2>
		</header>
		<ul class="limites-liste">
			<?php foreach ( $limites as $i => $l ) : ?>
				<li class="rule-cell rule-cell--side reveal" style="--i:<?php echo (int) $i; ?>"><?php echo esc_html( $l ); ?></li>
			<?php endforeach; ?>
		</ul>

		<div class="limites-cta">
			<p><?php esc_html_e( 'Vous hésitez entre deux produits, ou vous voulez savoir si une formation est faite pour vous ? Dites-le-moi, je vous réponds sans engagement.', 'fatichanelya' ); ?></p>
			<div class="apropos-actions">
				<a class="button button-gold button-lg" href="<?php echo esc_url( fati_wa( __( 'Bonjour Fati, j\'aimerais échanger avec vous.', 'fatichanelya' ) ) ); ?>"<?php echo $cible; ?>>
					<?php echo fati_icon( 'whatsapp', 24 ); ?><?php esc_html_e( 'Écrire sur WhatsApp', 'fatichanelya' ); ?>
				</a>
				<?php if ( $tiktok ) : ?>
					<a class="button button-outline button-outline--light" href="<?php echo esc_url( $tiktok ); ?>" target="_blank" rel="noopener">
						<?php echo fati_icon( 'tiktok', 20 ); ?><?php esc_html_e( 'Voir mon TikTok', 'fatichanelya' ); ?>
					</a>
				<?php endif; ?>
			</div>
		</div>
	</section>
</main>
<?php
get_footer();
