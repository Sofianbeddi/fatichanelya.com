<?php
/**
 * Sélecteur de langue (thème 1.7.1).
 *
 * Deux emplois :
 * - `menu` (en-tête) : un bouton compact — globe, code de la langue courante,
 *   chevron — qui ouvre une liste des langues en nom natif, chacune en lien
 *   réel (navigable, indexable) ; la courante est marquée.
 * - `liste` (pied de page) : la même liste, à plat.
 *
 * Alimenté par Polylang (`fati_languages()`), donc autant de langues que le
 * site en compte : deux aujourd'hui, quatre demain sans rien changer ici.
 * Sans Polylang, ou avec une seule langue, rien ne s'affiche.
 *
 *     set_query_var( 'fati_langue', array( 'mode' => 'liste' ) );
 *     get_template_part( 'template-parts/components/langue' );
 *
 * @package Fatichanelya
 */

defined( 'ABSPATH' ) || exit;

$args    = wp_parse_args( get_query_var( 'fati_langue', array() ), array( 'mode' => 'menu' ) );
$langues = fati_languages();
if ( count( $langues ) < 2 ) {
	return;
}
$courante = null;
foreach ( $langues as $l ) {
	if ( ! empty( $l['current_lang'] ) ) {
		$courante = $l;
	}
}
if ( ! $courante ) {
	$courante = reset( $langues );
}
$liens = static function () use ( $langues ) {
	foreach ( $langues as $l ) :
		$actuelle = ! empty( $l['current_lang'] );
		?>
		<li>
			<a href="<?php echo esc_url( $l['url'] ); ?>" lang="<?php echo esc_attr( $l['locale'] ); ?>" hreflang="<?php echo esc_attr( $l['locale'] ); ?>"<?php echo $actuelle ? ' aria-current="true"' : ''; ?>>
				<span class="lang-nom"><?php echo esc_html( $l['name'] ); ?></span>
				<span class="lang-abr" aria-hidden="true"><?php echo esc_html( strtoupper( $l['slug'] ) ); ?></span>
				<?php echo $actuelle ? fati_icon( 'check', 16 ) : ''; ?>
			</a>
		</li>
		<?php
	endforeach;
};
?>
<?php if ( 'liste' === $args['mode'] ) : ?>
	<nav class="footer-lang" aria-label="<?php esc_attr_e( 'Langues du site', 'fatichanelya' ); ?>">
		<ul><?php $liens(); ?></ul>
	</nav>
<?php else : ?>
	<div class="lang" data-lang>
		<button class="lang-toggle" type="button" aria-expanded="false" aria-controls="lang-menu"
		        aria-label="<?php echo esc_attr( sprintf( /* translators: %s : nom de la langue courante. */ __( 'Langue : %s. Changer de langue', 'fatichanelya' ), $courante['name'] ) ); ?>">
			<?php echo fati_icon( 'globe', 20 ); ?><span class="lang-code" aria-hidden="true"><?php echo esc_html( strtoupper( $courante['slug'] ) ); ?></span><?php echo fati_icon( 'chevron', 14 ); ?>
		</button>
		<ul class="lang-menu" id="lang-menu" hidden><?php $liens(); ?></ul>
	</div>
<?php endif; ?>
