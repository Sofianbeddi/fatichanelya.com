<?php
/**
 * Engagements — bande bleu nuit de l'accueil.
 *
 * D'abord « Ce que je ne fais pas » : quatre phrases en négatif, en grand
 * corps. C'est la réponse à l'objection « c'est du MLM ? », et elle ne coûte
 * aucune preuve à produire — rien sur le site ne contredit ces phrases, et ça
 * se vérifie en le parcourant. Puis les trois engagements du back-office.
 *
 * @package Fatichanelya
 */

defined( 'ABSPATH' ) || exit;

$items = fati_opt_lines( 'pledge' );
if ( ! $items ) {
	return;
}

$non = array(
	__( 'Je ne recrute personne.', 'fatichanelya' ),
	__( 'Je ne promets aucun résultat de santé.', 'fatichanelya' ),
	__( 'Je n\'affiche aucun avis que je n\'ai pas reçu.', 'fatichanelya' ),
	__( 'Je ne mets pas de compte à rebours.', 'fatichanelya' ),
);
?>
<section class="section pledge" aria-labelledby="pledge-title">
	<header class="section-head">
		<p class="eyebrow light"><?php esc_html_e( 'Une relation claire', 'fatichanelya' ); ?></p>
		<h2 id="pledge-title"><?php echo wp_kses( fati_accent( __( 'Du conseil *avant la pression d\'acheter*.', 'fatichanelya' ) ), array( 'em' => array() ) ); ?></h2>
	</header>

	<div class="pledge-non">
		<h3 id="pledge-non-title"><?php esc_html_e( 'Ce que je ne fais pas', 'fatichanelya' ); ?></h3>
		<ul aria-labelledby="pledge-non-title">
			<?php foreach ( $non as $i => $phrase ) : ?>
				<li class="rule-cell rule-cell--side reveal" style="--i:<?php echo esc_attr( $i ); ?>">
					<?php echo esc_html( $phrase ); ?>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>

	<ul class="pledge-grid">
		<?php foreach ( $items as $i => $item ) : ?>
			<li class="reveal" style="--i:<?php echo esc_attr( $i ); ?>">
				<strong><?php echo esc_html( $item[0] ); ?></strong>
				<p><?php echo esc_html( isset( $item[1] ) ? $item[1] : '' ); ?></p>
			</li>
		<?php endforeach; ?>
	</ul>
</section>
