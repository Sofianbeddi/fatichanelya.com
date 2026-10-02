<?php
/**
 * Fiche produit. Toutes les cartes du site y mènent.
 *
 * C'est la page où se décide l'achat. Elle répond donc, dans l'ordre, aux
 * questions qui arrêtent une acheteuse : ce que c'est, combien ça coûte et
 * pour quelle contenance, comment on commande, ce qu'il y a dedans et comment
 * on s'en sert, qui expédie, et ce qui se passe si ça ne convient pas.
 *
 * Aucun champ n'est inventé : contenance, composition et conseils
 * d'utilisation sont lus sur l'emballage et saisis dans le back-office
 * (`_fati_format`, `_fati_composition`, `_fati_usage`). Un bloc dont la
 * donnée manque n'est pas rendu.
 *
 * L'action principale est « Ajouter au panier », précédée du choix de la
 * quantité : la fiche alimente le même panier que les cartes, et c'est le
 * panier complet qui part en message à Fati. « Poser une question » reste la
 * sortie directe.
 *
 * @package Fatichanelya
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	$id      = get_the_ID();
	$prix    = get_post_meta( $id, '_fati_prix', true );
	$details = fati_produit_details( $id );
	$terms   = get_the_terms( $id, 'categorie_produit' );
	$cat     = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0] : null;
	$titre   = get_the_title();

	$question = fati_wa( sprintf( __( 'Bonjour Fati, j\'ai une question sur « %s ».', 'fatichanelya' ), $titre ) );
	$cible    = fati_opt( 'whatsapp' ) ? ' target="_blank" rel="noopener"' : '';
	$trail    = array(
		array( 'label' => __( 'Boutique', 'fatichanelya' ), 'url' => get_post_type_archive_link( 'produit' ) ),
	);
	if ( $cat ) {
		$trail[] = array( 'label' => $cat->name, 'url' => get_term_link( $cat ) );
	}
	$trail[] = array( 'label' => $titre );

	set_query_var( 'fati_banner_title', $titre );
	set_query_var( 'fati_banner_trail', $trail );
	get_template_part( 'template-parts/components/page-banner' );
	?>
	<?php
	/*
	 * `produit-page` et non `single-produit` : WordPress pose déjà la classe
	 * `single-produit` sur <body>, et une règle `.single-produit{display:grid}`
	 * mettait tout le document en grille, avec 40 px d'écart entre
	 * l'annonce, l'en-tête, la bannière et le pied de page.
	 */
	?>
	<main id="main" class="section produit-page">

		<article class="produit-achat">
			<?php
			// Le visuel est l'élément LCP de la page : chargé d'emblée, jamais
			// différé. Packshot sur fond blanc dans un carré blanc : rien n'est
			// recadré, l'image est contenue.
			?>
			<figure class="produit-media">
				<?php echo fati_produit_image( $id, 'fati-produit', '', true, '(min-width: 900px) min(41vw, 540px), calc(100vw - 2.5rem)' ); ?>
			</figure>

			<div class="produit-copy">
				<?php if ( $cat ) : ?>
					<p class="eyebrow"><?php echo esc_html( $cat->name ); ?></p>
				<?php endif; ?>

				<h2><?php echo esc_html( $titre ); ?></h2>

				<?php
				// Prix puis contenance sur la même ligne : « 61 € · 90 gélules ».
				// Sans prix en base, la ligne le dit plutôt que d'afficher 0 €.
				?>
				<p class="produit-prix<?php echo $prix ? '' : ' produit-prix--demande'; ?>">
					<?php echo $prix ? esc_html( fati_format_prix( $prix ) ) : esc_html__( 'Prix communiqué sur demande', 'fatichanelya' ); ?>
					<?php if ( $details['format'] ) : ?>
						<span class="produit-format">· <?php echo esc_html( $details['format'] ); ?></span>
					<?php endif; ?>
				</p>

				<?php if ( has_excerpt() ) : ?>
					<p class="produit-accroche"><?php echo esc_html( get_the_excerpt() ); ?></p>
				<?php endif; ?>

				<?php
				// Points clés : ce que le produit est, pas ce qu'il ferait à la
				// santé. Sur mobile la liste passe sous le bouton (voir CSS) pour
				// que prix et bouton restent visibles sans défiler.
				if ( $details['points'] ) :
					?>
					<ul class="produit-points">
						<?php foreach ( $details['points'] as $point ) : ?>
							<li><?php echo fati_icon( 'check', 20 ); ?><span><?php echo esc_html( $point ); ?></span></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>

				<?php
				// On choisit la quantité, puis on ajoute : le bouton confirme sur
				// place (« Ajouté »), le panier de l'en-tête se met à jour, et la
				// ligne du dessous rappelle ce qui s'y trouve déjà.
				?>
				<div class="produit-actions">
					<div class="qty qty--lg" role="group" aria-label="<?php esc_attr_e( 'Quantité', 'fatichanelya' ); ?>">
						<button class="qty-btn" type="button" data-pick-step="-1" aria-label="<?php esc_attr_e( 'Diminuer la quantité', 'fatichanelya' ); ?>"><?php echo fati_icon( 'minus', 20 ); ?></button>
						<label class="sr-only" for="produit-qte"><?php esc_html_e( 'Quantité', 'fatichanelya' ); ?></label>
						<input class="qty-val" id="produit-qte" type="number" inputmode="numeric" min="1" max="99" step="1" value="1" data-pick>
						<button class="qty-btn" type="button" data-pick-step="1" aria-label="<?php esc_attr_e( 'Augmenter la quantité', 'fatichanelya' ); ?>"><?php echo fati_icon( 'plus', 20 ); ?></button>
					</div>
					<button class="button button-navy button-lg add-button" type="button" data-add="<?php echo esc_attr( $id ); ?>" data-add-pick="produit-qte">
						<span class="add-label"><?php echo fati_icon( 'bag', 22 ); ?><?php esc_html_e( 'Ajouter au panier', 'fatichanelya' ); ?></span>
						<span class="add-done" aria-hidden="true"><?php echo fati_icon( 'check', 22 ); ?><?php esc_html_e( 'Ajouté', 'fatichanelya' ); ?></span>
					</button>
				</div>

				<p class="produit-panier" data-inbag="<?php echo esc_attr( $id ); ?>" hidden>
					<span data-inbag-texte></span>
					<a class="arrow-link" href="<?php echo esc_url( fati_panier_url() ); ?>" data-open-bag>
						<?php esc_html_e( 'Voir le panier', 'fatichanelya' ); ?><?php echo fati_icon( 'arrow', 20 ); ?>
					</a>
				</p>

				<a class="text-button produit-question" href="<?php echo esc_url( $question ); ?>"<?php echo $cible; ?>>
					<?php echo fati_icon( 'whatsapp', 20 ); ?><?php esc_html_e( 'Poser une question sur ce produit', 'fatichanelya' ); ?>
				</a>

				<p class="produit-cadrage">
					<?php esc_html_e( 'On confirme ensemble la disponibilité, les frais de port réels et le total avant tout paiement. Le paiement en ligne n\'est pas encore activé.', 'fatichanelya' ); ?>
				</p>
			</div>
		</article>

		<?php
		// Bande de réassurance : elle se place juste sous le bloc d'achat,
		// là où le doute apparaît, et pas en pied de page.
		$engagements = fati_opt_lines( 'trust' );
		if ( $engagements ) :
			?>
			<section class="produit-reassurance" aria-label="<?php esc_attr_e( 'Nos engagements', 'fatichanelya' ); ?>">
				<ul>
					<?php foreach ( array_slice( $engagements, 0, 4 ) as $i => $item ) : ?>
						<li>
							<strong><?php echo esc_html( $item[0] ); ?></strong>
							<?php if ( isset( $item[1] ) ) : ?>
								<span><?php echo esc_html( $item[1] ); ?></span>
							<?php endif; ?>
						</li>
					<?php endforeach; ?>
				</ul>
			</section>
		<?php endif; ?>

		<?php
		// Composition et conseils d'utilisation : deux cellules à filets côte à
		// côte. Chaque colonne n'existe que si sa donnée a été saisie ; sans
		// aucune des deux, la section n'est pas rendue.
		if ( $details['composition'] || $details['usage'] ) :
			?>
			<section class="produit-details" aria-label="<?php esc_attr_e( 'Composition et conseils d\'utilisation', 'fatichanelya' ); ?>">
				<?php if ( $details['composition'] ) : ?>
					<div class="rule-cell produit-composition">
						<h2><?php esc_html_e( 'Composition', 'fatichanelya' ); ?></h2>
						<p><?php echo nl2br( esc_html( $details['composition'] ) ); ?></p>
					</div>
				<?php endif; ?>

				<?php if ( $details['usage'] ) : ?>
					<div class="rule-cell produit-usage">
						<h2><?php esc_html_e( 'Conseils d\'utilisation', 'fatichanelya' ); ?></h2>
						<?php // Une étape par ligne saisie ; la liste ordonnée porte déjà le rang, le numéro visible est décoratif. ?>
						<ol>
							<?php foreach ( $details['usage'] as $n => $etape ) : ?>
								<li>
									<span class="rule-num" aria-hidden="true"><?php echo (int) $n + 1; ?></span>
									<p><?php echo esc_html( $etape ); ?></p>
								</li>
							<?php endforeach; ?>
						</ol>
					</div>
				<?php endif; ?>
			</section>
		<?php endif; ?>

		<?php
		// L'import initial a rempli l'extrait et le corps avec le même texte.
		// Tant que la cliente n'a pas écrit une vraie description, afficher les
		// deux reviendrait à répéter la même phrase à deux endroits.
		$corps    = trim( wp_strip_all_tags( get_the_content() ) );
		$accroche = trim( wp_strip_all_tags( get_the_excerpt() ) );
		if ( '' !== $corps && $corps !== $accroche ) :
			?>
			<section class="produit-description" aria-labelledby="desc-title">
				<h2 id="desc-title"><?php esc_html_e( 'Ce que c\'est', 'fatichanelya' ); ?></h2>
				<div class="prose"><?php the_content(); ?></div>
			</section>
		<?php endif; ?>

		<p class="produit-legal"><?php echo esc_html( fati_opt( 'mention_sante' ) ); ?></p>

		<?php
		// Produits associés : la même catégorie d'abord, ce qui aide vraiment
		// à comparer. Sans catégorie, on ne propose rien plutôt qu'au hasard.
		if ( $cat ) :
			$associes = new WP_Query(
				array(
					'post_type'      => 'produit',
					'posts_per_page' => 4,
					'post__not_in'   => array( $id ),
					'no_found_rows'  => true,
					'orderby'        => array( 'menu_order' => 'ASC' ),
					'tax_query'      => array(
						array(
							'taxonomy' => 'categorie_produit',
							'field'    => 'term_id',
							'terms'    => $cat->term_id,
						),
					),
				)
			);

			if ( $associes->have_posts() ) :
				?>
				<section class="produit-associes" aria-labelledby="associes-title">
					<h2 id="associes-title">
						<?php
						printf(
							/* translators: %s : nom de la catégorie. */
							esc_html__( 'Autres produits — %s', 'fatichanelya' ),
							esc_html( $cat->name )
						);
						?>
					</h2>
					<ul class="product-grid">
						<?php
						$i = 0;
						while ( $associes->have_posts() ) :
							$associes->the_post();
							set_query_var( 'fati_index', $i++ );
							get_template_part( 'template-parts/components/product-card' );
						endwhile;
						wp_reset_postdata();
						?>
					</ul>
				</section>
				<?php
			endif;
		endif;
		?>

		<?php
		// Rappel d'achat en fin de lecture : la décision se prend souvent ici.
		// Même action que le bloc d'achat, pour ne pas ouvrir un second parcours.
		?>
		<aside class="produit-rappel" aria-label="<?php esc_attr_e( 'Rappel du produit', 'fatichanelya' ); ?>">
			<div>
				<p class="produit-rappel-nom"><?php echo esc_html( $titre ); ?></p>
				<p class="produit-rappel-prix<?php echo $prix ? '' : ' produit-rappel-prix--demande'; ?>">
					<?php echo $prix ? esc_html( fati_format_prix( $prix ) ) : esc_html__( 'Prix communiqué sur demande', 'fatichanelya' ); ?>
				</p>
			</div>
			<button class="button button-navy button-lg add-button" type="button" data-add="<?php echo esc_attr( $id ); ?>">
				<span class="add-label"><?php echo fati_icon( 'bag', 22 ); ?><?php esc_html_e( 'Ajouter au panier', 'fatichanelya' ); ?></span>
				<span class="add-done" aria-hidden="true"><?php echo fati_icon( 'check', 22 ); ?><?php esc_html_e( 'Ajouté', 'fatichanelya' ); ?></span>
			</button>
		</aside>
	</main>
	<?php
endwhile;

get_footer();
