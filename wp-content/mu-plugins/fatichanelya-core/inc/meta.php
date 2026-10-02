<?php
/**
 * Champs personnalisés en API native (pas d'ACF Pro : Repeater, Flexible Content
 * et Options Pages y sont payants, et le projet doit rester à coût zéro).
 *
 * @package Fatichanelya
 */

defined( 'ABSPATH' ) || exit;

/**
 * Définition des champs par type de contenu.
 * Ajouter un champ ici suffit : enregistrement, affichage et sauvegarde suivent.
 */
function fati_meta_schema() {
	return array(
		'produit'   => array(
			'_fati_prix'      => array(
				'label' => __( 'Prix (€)', 'fatichanelya' ),
				'type'  => 'number',
				'help'  => __( 'Prix indicatif hors livraison. Décimales avec un point : 40.50', 'fatichanelya' ),
				'attrs' => array( 'step' => '0.01', 'min' => '0' ),
			),
			'_fati_reference' => array(
				'label' => __( 'Référence fournisseur', 'fatichanelya' ),
				'type'  => 'text',
				'help'  => __( 'Optionnel. Affiché uniquement en interne.', 'fatichanelya' ),
			),
			// Les trois champs suivants sont lus sur l'emballage ou la fiche
			// officielle du fournisseur, jamais rédigés de notre propre chef :
			// la contenance rend le prix lisible, la composition et le mode
			// d'emploi répondent aux deux questions qui précèdent l'achat.
			'_fati_format'    => array(
				'label' => __( 'Contenance / format', 'fatichanelya' ),
				'type'  => 'text',
				'help'  => __( 'Tel qu\'imprimé sur l\'emballage : « 90 gélules », « 20 sachets × 21 g », « 250 ml ».', 'fatichanelya' ),
			),
			'_fati_composition' => array(
				'label' => __( 'Composition', 'fatichanelya' ),
				'type'  => 'textarea',
				'help'  => __( 'Ingrédients principaux, repris de l\'étiquette. Une ligne par ingrédient ou une phrase.', 'fatichanelya' ),
			),
			'_fati_usage'     => array(
				'label' => __( 'Conseils d\'utilisation', 'fatichanelya' ),
				'type'  => 'textarea',
				'help'  => __( 'Mode d\'emploi de l\'emballage, une étape par ligne. Jamais de posologie inventée.', 'fatichanelya' ),
			),
			'_fati_indispo'   => array(
				'label' => __( 'Visuel à venir', 'fatichanelya' ),
				'type'  => 'checkbox',
				'help'  => __( 'Coché : affiche le visuel de marque « Visuel à venir » à la place de l\'image.', 'fatichanelya' ),
			),
		),
		'formation' => array(
			'_fati_niveau' => array(
				'label' => __( 'Niveau', 'fatichanelya' ),
				'type'  => 'text',
				'help'  => __( 'Exemple : Débutant, Tous niveaux, Sur mesure', 'fatichanelya' ),
			),
			'_fati_duree'  => array(
				'label' => __( 'Durée', 'fatichanelya' ),
				'type'  => 'text',
				'help'  => __( 'Exemple : 6 semaines', 'fatichanelya' ),
			),
		),
	);
}

/**
 * Enregistrement côté REST/Gutenberg + sanitisation à l'écriture.
 */
add_action(
	'init',
	function () {
		foreach ( fati_meta_schema() as $post_type => $fields ) {
			foreach ( $fields as $key => $field ) {
				register_post_meta(
					$post_type,
					$key,
					array(
						'single'            => true,
						'type'              => 'number' === $field['type'] ? 'number' : 'string',
						'show_in_rest'      => true,
						'sanitize_callback' => fati_meta_sanitizer( $field['type'] ),
						'auth_callback'     => function () {
							return current_user_can( 'edit_posts' );
						},
					)
				);
			}
		}
	}
);

/**
 * Fonction de nettoyage selon le type de champ. Un textarea garde ses
 * retours à la ligne : sanitize_text_field les supprimerait.
 */
function fati_meta_sanitizer( $type ) {
	if ( 'number' === $type ) {
		return 'fati_sanitize_decimal';
	}
	return 'textarea' === $type ? 'sanitize_textarea_field' : 'sanitize_text_field';
}
function fati_sanitize_decimal( $value ) {
	$value = str_replace( ',', '.', (string) $value );
	return '' === $value ? '' : (string) round( (float) $value, 2 );
}

add_action(
	'add_meta_boxes',
	function () {
		foreach ( array_keys( fati_meta_schema() ) as $post_type ) {
			add_meta_box(
				'fati_details',
				__( 'Détails', 'fatichanelya' ),
				'fati_render_meta_box',
				$post_type,
				'side',
				'high'
			);
		}
	}
);

function fati_render_meta_box( $post ) {
	$schema = fati_meta_schema();
	$fields = isset( $schema[ $post->post_type ] ) ? $schema[ $post->post_type ] : array();

	wp_nonce_field( 'fati_save_meta', 'fati_meta_nonce' );

	foreach ( $fields as $key => $field ) {
		$value = get_post_meta( $post->ID, $key, true );
		$id    = esc_attr( $key );

		echo '<p style="margin:0 0 14px">';
		echo '<label for="' . $id . '" style="display:block;font-weight:600;margin-bottom:4px">' . esc_html( $field['label'] ) . '</label>';

		if ( 'checkbox' === $field['type'] ) {
			echo '<input type="checkbox" id="' . $id . '" name="' . $id . '" value="1" ' . checked( $value, '1', false ) . '>';
		} elseif ( 'textarea' === $field['type'] ) {
			echo '<textarea id="' . $id . '" name="' . $id . '" class="widefat" rows="4">' . esc_textarea( $value ) . '</textarea>';
		} else {
			$attrs = '';
			foreach ( ( isset( $field['attrs'] ) ? $field['attrs'] : array() ) as $a => $v ) {
				$attrs .= ' ' . esc_attr( $a ) . '="' . esc_attr( $v ) . '"';
			}
			echo '<input type="' . esc_attr( $field['type'] ) . '" id="' . $id . '" name="' . $id . '" value="' . esc_attr( $value ) . '" class="widefat"' . $attrs . '>';
		}

		if ( ! empty( $field['help'] ) ) {
			echo '<span class="description" style="display:block;margin-top:4px">' . esc_html( $field['help'] ) . '</span>';
		}
		echo '</p>';
	}
}

add_action(
	'save_post',
	function ( $post_id ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		if ( ! isset( $_POST['fati_meta_nonce'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['fati_meta_nonce'] ) ), 'fati_save_meta' ) ) {
			return;
		}
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		$schema = fati_meta_schema();
		$type   = get_post_type( $post_id );
		if ( ! isset( $schema[ $type ] ) ) {
			return;
		}

		foreach ( $schema[ $type ] as $key => $field ) {
			if ( 'checkbox' === $field['type'] ) {
				$raw = isset( $_POST[ $key ] ) ? '1' : '';
			} else {
				$raw = isset( $_POST[ $key ] ) ? wp_unslash( $_POST[ $key ] ) : '';
				$raw = call_user_func( fati_meta_sanitizer( $field['type'] ), $raw );
			}

			if ( '' === $raw ) {
				delete_post_meta( $post_id, $key );
			} else {
				update_post_meta( $post_id, $key, $raw );
			}
		}
	}
);

/**
 * Colonne « Prix » dans la liste des produits, triable.
 */
add_filter(
	'manage_produit_posts_columns',
	function ( $cols ) {
		$new = array();
		foreach ( $cols as $k => $v ) {
			$new[ $k ] = $v;
			if ( 'title' === $k ) {
				$new['fati_prix'] = __( 'Prix', 'fatichanelya' );
			}
		}
		return $new;
	}
);

add_action(
	'manage_produit_posts_custom_column',
	function ( $col, $post_id ) {
		if ( 'fati_prix' === $col ) {
			$prix = get_post_meta( $post_id, '_fati_prix', true );
			echo $prix ? esc_html( fati_format_prix( $prix ) ) : '—';
		}
	},
	10,
	2
);

add_filter(
	'manage_edit-produit_sortable_columns',
	function ( $cols ) {
		$cols['fati_prix'] = 'fati_prix';
		return $cols;
	}
);

add_action(
	'pre_get_posts',
	function ( $q ) {
		if ( is_admin() && $q->is_main_query() && 'fati_prix' === $q->get( 'orderby' ) ) {
			$q->set( 'meta_key', '_fati_prix' );
			$q->set( 'orderby', 'meta_value_num' );
		}
	}
);

/**
 * Formatage monétaire unique, utilisé par le thème et l'admin.
 */
function fati_format_prix( $montant ) {
	$montant = (float) $montant;
	$decimales = fmod( $montant, 1 ) === 0.0 ? 0 : 2;
	return number_format_i18n( $montant, $decimales ) . ' €';
}

/**
 * Visuel de catégorie.
 *
 * Chaque catégorie de produit porte une image, utilisée par la section
 * « Nos catégories » de l'accueil. Le champ vit dans le mu-plugin et non dans
 * le thème : le visuel doit survivre à un changement de thème, comme le reste
 * du contenu.
 *
 * L'interface reste volontairement sommaire — la médiathèque native, un champ
 * et un aperçu — parce que la cliente y passera quatre fois, pas tous les jours.
 */
const FATI_TERM_VISUEL = '_fati_visuel';

add_action(
	'categorie_produit_add_form_fields',
	function () {
		?>
		<div class="form-field">
			<label for="fati_visuel"><?php esc_html_e( 'Visuel de la catégorie', 'fatichanelya' ); ?></label>
			<input type="number" name="fati_visuel" id="fati_visuel" value="" min="0" step="1">
			<p><?php esc_html_e( 'Identifiant du média. Ouvrez la médiathèque, cliquez sur l\'image : le numéro figure à la fin de l\'adresse.', 'fatichanelya' ); ?></p>
		</div>
		<?php
	}
);

add_action(
	'categorie_produit_edit_form_fields',
	function ( $term ) {
		$visuel = (int) get_term_meta( $term->term_id, FATI_TERM_VISUEL, true );
		?>
		<tr class="form-field">
			<th scope="row">
				<label for="fati_visuel"><?php esc_html_e( 'Visuel de la catégorie', 'fatichanelya' ); ?></label>
			</th>
			<td>
				<input type="number" name="fati_visuel" id="fati_visuel"
				       value="<?php echo $visuel ? esc_attr( $visuel ) : ''; ?>" min="0" step="1">
				<p class="description">
					<?php esc_html_e( 'Identifiant du média. Ouvrez la médiathèque, cliquez sur l\'image : le numéro figure à la fin de l\'adresse.', 'fatichanelya' ); ?>
				</p>
				<?php if ( $visuel && wp_get_attachment_image( $visuel, 'thumbnail' ) ) : ?>
					<p style="margin-top:8px">
						<?php echo wp_get_attachment_image( $visuel, 'thumbnail', false, array( 'style' => 'border-radius:50%' ) ); ?>
					</p>
				<?php endif; ?>
			</td>
		</tr>
		<?php
	}
);

/**
 * Enregistrement du visuel. Vérifie que l'identifiant désigne bien un média
 * existant : un numéro saisi au hasard produirait une image cassée en ligne.
 */
add_action(
	'edited_categorie_produit',
	'fati_save_term_visuel'
);
add_action(
	'created_categorie_produit',
	'fati_save_term_visuel'
);

function fati_save_term_visuel( $term_id ) {
	if ( ! current_user_can( 'manage_categories' ) ) {
		return;
	}

	$nonce = isset( $_POST['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) ) : '';
	if ( ! $nonce || ! wp_verify_nonce( $nonce, 'update-tag_' . $term_id ) ) {
		// Création de terme : WordPress utilise un autre nonce, déjà vérifié.
		if ( ! wp_verify_nonce( $nonce, 'add-tag' ) ) {
			return;
		}
	}

	if ( ! isset( $_POST['fati_visuel'] ) ) {
		return;
	}

	$visuel = (int) $_POST['fati_visuel'];

	if ( $visuel > 0 && 'attachment' === get_post_type( $visuel ) ) {
		update_term_meta( $term_id, FATI_TERM_VISUEL, $visuel );
	} else {
		delete_term_meta( $term_id, FATI_TERM_VISUEL );
	}
}
