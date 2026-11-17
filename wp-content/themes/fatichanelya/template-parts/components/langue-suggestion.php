<?php
/**
 * Suggestion de langue (1.9.0).
 *
 * Un visiteur arrive sur une page intérieure dans une autre langue que la sienne (lien partagé,
 * résultat Google, TikTok) : une carte discrète lui propose la même page dans sa langue. Seul
 * l'accueil redirige automatiquement (Polylang) ; ailleurs on propose, on n'impose pas — un lien
 * partagé ouvre ce qui a été partagé, et les robots voient toutes les versions.
 *
 * Le gabarit est vide et caché : `main.js` choisit la langue (choix déjà fait dans le menu, sinon
 * langue du navigateur, sinon anglais), vérifie que la page existe dans cette langue (balises
 * `link rel="alternate" hreflang`), puis écrit les textes DANS la langue proposée — c'est la seule
 * langue que le visiteur lit à coup sûr. Rien n'apparaît sans JavaScript.
 *
 * @package Fatichanelya
 */

defined( 'ABSPATH' ) || exit;

if ( count( fati_languages() ) < 2 ) {
	return;
}
?>
<aside class="langue-suggestion" id="langue-suggestion" hidden>
	<p class="langue-suggestion-texte"><?php echo fati_icon( 'globe', 20 ); ?><span data-texte></span></p>
	<a class="button button-gold langue-suggestion-voir" data-voir href="#"></a>
	<button class="langue-suggestion-fermer" type="button" data-fermer><?php echo fati_icon( 'close', 18 ); ?></button>
</aside>
