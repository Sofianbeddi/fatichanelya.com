<?php
/**
 * Page de réglages « Contenus du site », pilotée par un schéma.
 *
 * Remplace les Options Pages d'ACF Pro (payantes) par la Settings API native.
 * Ajouter un champ = ajouter une ligne dans fati_options_schema().
 *
 * @package Fatichanelya
 */

defined( 'ABSPATH' ) || exit;

const FATI_OPTION_KEY = 'fati_settings';

/**
 * Schéma : onglet => champs. Types : text, textarea, url, tel, lines, image.
 * « lines » = une entrée par ligne, colonnes séparées par « | ».
 */
function fati_options_schema() {
	return array(
		'general'  => array(
			'label'  => __( 'Général', 'fatichanelya' ),
			'fields' => array(
				'whatsapp'        => array( 'label' => __( 'Numéro WhatsApp', 'fatichanelya' ), 'type' => 'tel', 'help' => __( 'Format international sans le +, exemple : 212612345678', 'fatichanelya' ) ),
				'annonce'         => array( 'label' => __( 'Barre d\'annonce', 'fatichanelya' ), 'type' => 'text' ),
				'annonce_cta'     => array( 'label' => __( 'Libellé du lien d\'annonce', 'fatichanelya' ), 'type' => 'text' ),
				'tiktok'          => array( 'label' => __( 'Profil TikTok', 'fatichanelya' ), 'type' => 'url' ),
				'instagram'       => array( 'label' => __( 'Profil Instagram', 'fatichanelya' ), 'type' => 'url' ),
				'og_image'        => array( 'label' => __( 'Image de partage (1200 × 630)', 'fatichanelya' ), 'type' => 'image', 'help' => __( 'Affichée quand un lien du site est partagé sur WhatsApp ou les réseaux. À défaut, la photo du hero est utilisée.', 'fatichanelya' ) ),
				'mention_sante'   => array( 'label' => __( 'Mention santé (pied de page)', 'fatichanelya' ), 'type' => 'textarea', 'help' => __( 'Obligatoire pour les compléments alimentaires. Ne jamais promettre de guérison.', 'fatichanelya' ) ),
			),
		),
		'hero'     => array(
			'label'  => __( 'Accueil — Hero', 'fatichanelya' ),
			'fields' => array(
				'hero_eyebrow'  => array( 'label' => __( 'Sur-titre', 'fatichanelya' ), 'type' => 'text' ),
				'hero_titre'    => array( 'label' => __( 'Titre', 'fatichanelya' ), 'type' => 'text', 'help' => __( 'Encadrer un mot avec des astérisques pour le mettre en or : *prendre soin*', 'fatichanelya' ) ),
				'hero_intro'    => array( 'label' => __( 'Introduction', 'fatichanelya' ), 'type' => 'textarea' ),
				'hero_cta1'     => array( 'label' => __( 'Bouton principal', 'fatichanelya' ), 'type' => 'text' ),
				'hero_cta2'     => array( 'label' => __( 'Lien secondaire', 'fatichanelya' ), 'type' => 'text' ),
				'hero_faits'    => array( 'label' => __( 'Points clés', 'fatichanelya' ), 'type' => 'lines', 'help' => __( 'Une par ligne.', 'fatichanelya' ) ),
				'hero_image'    => array( 'label' => __( 'Photo du hero', 'fatichanelya' ), 'type' => 'image' ),
				'hero_badge'    => array( 'label' => __( 'Badge flottant', 'fatichanelya' ), 'type' => 'lines', 'help' => __( 'Titre | sous-titre', 'fatichanelya' ) ),
			),
		),
		'sections' => array(
			'label'  => __( 'Accueil — Sections', 'fatichanelya' ),
			'fields' => array(
				'trust'          => array( 'label' => __( 'Bandeau engagements', 'fatichanelya' ), 'type' => 'lines', 'help' => __( 'Une par ligne : Titre | description', 'fatichanelya' ) ),
				'shop_titre'     => array( 'label' => __( 'Titre de la boutique', 'fatichanelya' ), 'type' => 'text' ),
				'shop_intro'     => array( 'label' => __( 'Intro de la boutique', 'fatichanelya' ), 'type' => 'textarea' ),
				'training_titre' => array( 'label' => __( 'Titre des formations', 'fatichanelya' ), 'type' => 'text' ),
				'training_intro' => array( 'label' => __( 'Intro des formations', 'fatichanelya' ), 'type' => 'textarea' ),
				'training_image' => array( 'label' => __( 'Photo des formations', 'fatichanelya' ), 'type' => 'image' ),
				'about_titre'    => array( 'label' => __( 'Titre « À propos »', 'fatichanelya' ), 'type' => 'text' ),
				'about_intro'    => array( 'label' => __( 'Texte « À propos »', 'fatichanelya' ), 'type' => 'textarea' ),
				'about_valeurs'  => array( 'label' => __( 'Valeurs', 'fatichanelya' ), 'type' => 'lines', 'help' => __( 'Titre | description', 'fatichanelya' ) ),
				'about_image'    => array( 'label' => __( 'Photo « À propos »', 'fatichanelya' ), 'type' => 'image' ),
				'about_avatar'   => array( 'label' => __( 'Photo de profil', 'fatichanelya' ), 'type' => 'image' ),
				'pledge'         => array( 'label' => __( 'Engagements détaillés', 'fatichanelya' ), 'type' => 'lines', 'help' => __( 'Titre | description', 'fatichanelya' ) ),
				'faq'            => array( 'label' => __( 'Questions fréquentes', 'fatichanelya' ), 'type' => 'lines', 'help' => __( 'Question | réponse', 'fatichanelya' ) ),
				'contact_titre'  => array( 'label' => __( 'Titre du bloc contact', 'fatichanelya' ), 'type' => 'text' ),
				'contact_intro'  => array( 'label' => __( 'Texte du bloc contact', 'fatichanelya' ), 'type' => 'textarea' ),
			),
		),
	);
}

/** Valeurs de départ, reprises de la maquette v3 validée. */
function fati_options_defaults() {
	return array(
		'whatsapp'       => '',
		'annonce'        => 'Bien-être naturel & formations concrètes — conseil direct, sans pression d\'achat.',
		'annonce_cta'    => 'Parler à Fati',
		'tiktok'         => 'https://www.tiktok.com/@fatichanelya',
		'mention_sante'  => 'Les compléments alimentaires ne remplacent pas une alimentation variée, un mode de vie sain ni l\'avis d\'un professionnel de santé. Respectez les conseils d\'utilisation figurant sur l\'emballage.',
		'hero_eyebrow'   => 'Bien-être naturel · apprentissage concret',
		'hero_titre'     => 'Des choix simples pour *prendre soin* de vous.',
		'hero_intro'     => 'Découvrez la sélection Naturixa et les formations pratiques de Fati, avec un échange direct sur WhatsApp quand vous avez besoin d\'être guidée.',
		'hero_cta1'      => 'Découvrir les produits',
		'hero_cta2'      => 'Trouver ma formation',
		'hero_faits'     => "Catalogue Naturixa\nÉchange direct WhatsApp\nEntrepreneure · Créatrice · Mentore",
		'hero_badge'     => "Catalogue vérifié | Prix indicatifs en euros",
		'trust'          => "Sélection transparente | Noms, prix et visuels issus du catalogue\nPaiement PayPal | Interface prête, activation à venir\nÉchange direct | Une réponse de Fati, pas d'un robot\nAucune promesse santé | Information, jamais de prescription",
		'shop_titre'     => 'Le catalogue Naturixa, *clairement présenté*.',
		'shop_intro'     => 'Les références sont regroupées par catégorie, avec leurs noms, prix et visuels disponibles. Le paiement marchand sera activé avant l\'ouverture des ventes.',
		'training_titre' => 'Des compétences qui *servent vraiment*.',
		'training_intro' => 'Un échange sur WhatsApp permet de choisir le format, le niveau et les objectifs avant toute réservation.',
		'about_titre'    => 'Partager ce qui m\'a *aidée à avancer*.',
		'about_intro'    => 'Fatichanelya réunit bien-être, entrepreneuriat et transmission dans un univers clair. Fati partage ses apprentissages, présente ses sélections avec transparence et accompagne celles et ceux qui veulent structurer leur projet digital.',
		'about_valeurs'  => "Clarté avant tout | Ce qui est disponible, ce qui reste à confirmer.\nProgrès réaliste | Des étapes tenables plutôt que des promesses.\nCommunauté bienveillante | On avance mieux en étant accompagnée.",
		'pledge'         => "Informations vérifiables | Les noms, prix et visuels proviennent du catalogue disponible.\nConseil direct | Une question peut être posée à Fati avant de choisir, sans engagement.\nAucune fausse promesse | Paiement marchand et conditions de livraison sont signalés avant activation.",
		'faq'            => "Comment se passe une commande ? | Vous composez votre sélection sur le site, puis vous l'envoyez à Fati par WhatsApp. Elle confirme la disponibilité, les frais de port réels et le total avant tout paiement.\nLivrez-vous chez moi ? | Fati expédie depuis l'Espagne vers l'Union européenne. Les délais et les frais dépendent du pays : ils sont confirmés avec vous avant la commande.\nPuis-je renvoyer un produit ? | Oui. Vous disposez de 14 jours après réception pour changer d'avis, sauf pour les produits descellés pour des raisons d'hygiène. Le détail figure sur la page Livraison et retours.\nLes compléments remplacent-ils un traitement ? | Non. Les compléments alimentaires ne remplacent ni une alimentation variée, ni un mode de vie sain, ni l'avis d'un professionnel de santé. Fati ne donne aucun conseil médical.\nComment choisir un produit ? | Chaque fiche indique la composition, la contenance et le mode d'emploi. Pour une orientation générale, écrivez à Fati ; pour toute question de santé, parlez-en à un professionnel.",
		'contact_titre'  => 'Votre prochaine étape peut *commencer simplement*.',
		'contact_intro'  => 'Une question sur un produit, une formation ou une collaboration ? Écrivez directement à Fati.',
	);
}

/**
 * Accesseur unique. Passe par Polylang quand il est actif, pour que chaque
 * langue ait sa propre valeur (chaînes enregistrées via polylang.php).
 */
function fati_opt( $key, $fallback = '' ) {
	$all   = get_option( FATI_OPTION_KEY, array() );
	$value = isset( $all[ $key ] ) ? $all[ $key ] : '';

	if ( '' === $value ) {
		$defaults = fati_options_defaults();
		$value    = isset( $defaults[ $key ] ) ? $defaults[ $key ] : $fallback;
	}

	if ( function_exists( 'pll__' ) && is_string( $value ) && '' !== $value ) {
		$value = pll__( $value );
	}

	return $value;
}

/**
 * Découpe un champ « lines » en tableau de colonnes.
 */
function fati_opt_lines( $key ) {
	$raw  = fati_opt( $key );
	$out  = array();
	foreach ( preg_split( '/\R/', (string) $raw ) as $line ) {
		$line = trim( $line );
		if ( '' === $line ) {
			continue;
		}
		$out[] = array_map( 'trim', explode( '|', $line ) );
	}
	return $out;
}

add_action(
	'admin_menu',
	function () {
		add_menu_page(
			__( 'Contenus du site', 'fatichanelya' ),
			__( 'Contenus du site', 'fatichanelya' ),
			'manage_options',
			'fati-settings',
			'fati_render_settings_page',
			'dashicons-admin-customizer',
			22
		);
	}
);

add_action(
	'admin_init',
	function () {
		register_setting(
			'fati_settings_group',
			FATI_OPTION_KEY,
			array( 'sanitize_callback' => 'fati_sanitize_settings' )
		);
	}
);

function fati_sanitize_settings( $input ) {
	$clean = array();
	foreach ( fati_options_schema() as $tab ) {
		foreach ( $tab['fields'] as $key => $field ) {
			$raw = isset( $input[ $key ] ) ? wp_unslash( $input[ $key ] ) : '';
			switch ( $field['type'] ) {
				case 'url':
				case 'image':
					$clean[ $key ] = esc_url_raw( $raw );
					break;
				case 'tel':
					$clean[ $key ] = preg_replace( '/[^0-9]/', '', $raw );
					break;
				case 'textarea':
				case 'lines':
					$clean[ $key ] = sanitize_textarea_field( $raw );
					break;
				default:
					$clean[ $key ] = sanitize_text_field( $raw );
			}
		}
	}
	return $clean;
}

function fati_render_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$schema  = fati_options_schema();
	$saved   = get_option( FATI_OPTION_KEY, array() );
	$default = fati_options_defaults();
	$active  = isset( $_GET['tab'] ) ? sanitize_key( $_GET['tab'] ) : 'general';
	if ( ! isset( $schema[ $active ] ) ) {
		$active = 'general';
	}
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Contenus du site', 'fatichanelya' ); ?></h1>

		<h2 class="nav-tab-wrapper">
			<?php foreach ( $schema as $slug => $tab ) : ?>
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=fati-settings&tab=' . $slug ) ); ?>"
				   class="nav-tab <?php echo $slug === $active ? 'nav-tab-active' : ''; ?>">
					<?php echo esc_html( $tab['label'] ); ?>
				</a>
			<?php endforeach; ?>
		</h2>

		<form method="post" action="options.php">
			<?php settings_fields( 'fati_settings_group' ); ?>
			<table class="form-table" role="presentation">
				<?php
				foreach ( $schema as $slug => $tab ) {
					foreach ( $tab['fields'] as $key => $field ) {
						$value  = isset( $saved[ $key ] ) ? $saved[ $key ] : ( isset( $default[ $key ] ) ? $default[ $key ] : '' );
						$name   = FATI_OPTION_KEY . '[' . $key . ']';
						$hidden = $slug !== $active;
						?>
						<tr<?php echo $hidden ? ' style="display:none"' : ''; ?>>
							<th scope="row"><label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $field['label'] ); ?></label></th>
							<td>
								<?php if ( in_array( $field['type'], array( 'textarea', 'lines' ), true ) ) : ?>
									<textarea id="<?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( $name ); ?>"
									          rows="<?php echo 'lines' === $field['type'] ? 6 : 3; ?>" class="large-text code"><?php echo esc_textarea( $value ); ?></textarea>
								<?php else : ?>
									<input type="<?php echo esc_attr( 'image' === $field['type'] ? 'url' : $field['type'] ); ?>"
									       id="<?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( $name ); ?>"
									       value="<?php echo esc_attr( $value ); ?>" class="regular-text">
								<?php endif; ?>
								<?php if ( ! empty( $field['help'] ) ) : ?>
									<p class="description"><?php echo esc_html( $field['help'] ); ?></p>
								<?php endif; ?>
							</td>
						</tr>
						<?php
					}
				}
				?>
			</table>
			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}
