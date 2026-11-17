// Recette de la langue du visiteur (thème 1.9.0) : redirection de l'accueil par langue du
// navigateur, et carte de suggestion sur les pages intérieures.
// BASE=http://localhost/websites/fatichanelya.com/ [OUT=dossier] [ENGINE=webkit|firefox] node langue-suggestion.mjs
// Lançable en production (lecture seule : aucun formulaire, seulement des visites et des clics).
import { chromium, webkit, firefox } from 'playwright';
import fs from 'node:fs';

const BASE = process.env.BASE || 'http://localhost/websites/fatichanelya.com/';
const OUT = process.env.OUT || 'langue-suggestion';
const moteur = { chromium, webkit, firefox }[process.env.ENGINE || 'chromium'];
fs.mkdirSync(OUT, { recursive: true });
const FICHE_FR = 'produits/lingzhi-coffee/';
let echecs = 0;
const ok = (cond, msg) => { console.log(`${cond ? '✔' : '✗'} ${msg}`); if (!cond) echecs++; };

const b = await moteur.launch();
const page = async (locale, largeur = 1440) => {
  const ctx = await b.newContext({ locale, viewport: { width: largeur, height: largeur > 700 ? 900 : 844 }, reducedMotion: 'reduce' });
  return { ctx, pg: await ctx.newPage() };
};
const carte = async (pg) => {
  await pg.waitForTimeout(300);
  const el = pg.locator('#langue-suggestion');
  if (!(await el.isVisible())) return null;
  return { lang: await el.getAttribute('lang'), dir: await el.locator('.langue-suggestion-texte').getAttribute('dir'), texte: (await el.innerText()).trim(), href: await el.locator('[data-voir]').getAttribute('href') };
};

// 1. Accueil : redirection selon la langue du navigateur
for (const [locale, attendu] of [['es-ES', '/es/'], ['en-GB', '/en/'], ['ar-MA', '/ar/'], ['de-DE', '/en/'], ['fr-FR', null]]) {
  const { ctx, pg } = await page(locale);
  await pg.goto(BASE, { waitUntil: 'domcontentloaded' });
  const chemin = new URL(pg.url()).pathname;
  ok(attendu ? chemin.endsWith(attendu) : !/\/(es|en|ar)\/$/.test(chemin), `accueil ${locale} → ${chemin}`);
  await ctx.close();
}

// 2. Page intérieure française, navigateur espagnol : pas de redirection, carte en espagnol
{
  const { ctx, pg } = await page('es-ES');
  await pg.goto(BASE + FICHE_FR, { waitUntil: 'networkidle' });
  ok(pg.url().endsWith(FICHE_FR), 'fiche FR + navigateur es : pas de redirection');
  const c = await carte(pg);
  ok(c && c.lang === 'es' && /español/.test(c.texte) && /\/es\//.test(c.href), `carte proposée en espagnol vers ${c && c.href}`);
  await pg.screenshot({ path: `${OUT}/fiche-fr-navigateur-es-1440.png` });
  // « Ahora no » : mémorisé, la carte ne revient pas
  await pg.locator('[data-fermer]').click();
  ok(!(await pg.locator('#langue-suggestion').isVisible()), 'fermeture : la carte disparaît');
  await pg.reload({ waitUntil: 'networkidle' });
  ok(!(await carte(pg)), 'après rechargement : la carte ne revient pas');
  await ctx.close();
}

// 3. Navigateur allemand : repli anglais
{
  const { ctx, pg } = await page('de-DE');
  await pg.goto(BASE + FICHE_FR, { waitUntil: 'networkidle' });
  const c = await carte(pg);
  ok(c && c.lang === 'en' && /English/.test(c.texte), 'navigateur de : carte en anglais');
  await ctx.close();
}

// 4. Navigateur arabe : carte de droite à gauche, sur une page française
{
  const { ctx, pg } = await page('ar-MA', 390);
  await pg.goto(BASE + FICHE_FR, { waitUntil: 'networkidle' });
  const c = await carte(pg);
  ok(c && c.lang === 'ar' && c.dir === 'rtl', 'navigateur ar : carte en arabe, rtl');
  const box = await pg.locator('#langue-suggestion').boundingBox();
  const wa = pg.locator('#wa-float');
  if (await wa.count()) {
    const w = await wa.boundingBox();
    const vis = await wa.evaluate((e) => getComputedStyle(e).visibility);
    ok(vis === 'visible' && w && box && w.y >= box.y + box.height, `mobile : bouton WhatsApp visible, sous la carte (carte ${box && Math.round(box.y + box.height)} px, bouton ${w && Math.round(w.y)} px)`);
  }
  ok(box && box.x >= 15 && box.x + box.width <= 375, `mobile : carte dans l'écran (${box && Math.round(box.x)}–${box && Math.round(box.x + box.width)})`);
  await pg.screenshot({ path: `${OUT}/fiche-fr-navigateur-ar-390.png` });
  await ctx.close();
}

// 4 bis. Page arabe, navigateur anglais, grand écran : la carte reste du côté opposé au bouton WhatsApp
{
  const { ctx, pg } = await page('en-GB');
  await pg.goto(BASE + 'ar/produits/', { waitUntil: 'networkidle' });
  const c = await carte(pg);
  const a = await pg.locator('#langue-suggestion').boundingBox();
  const w = await pg.locator('#wa-float').boundingBox();
  const chevauche = a && w && !(a.x + a.width <= w.x || w.x + w.width <= a.x || a.y + a.height <= w.y || w.y + w.height <= a.y);
  ok(c && c.lang === 'en' && !chevauche, `page arabe + navigateur en : carte en anglais sans chevaucher WhatsApp (carte ${a && Math.round(a.x)}–${a && Math.round(a.x + a.width)}, bouton ${w && Math.round(w.x)})`);
  await pg.screenshot({ path: `${OUT}/page-ar-navigateur-en-1440.png` });
  await ctx.close();
}

// 5. Navigateur français sur une page française : rien
{
  const { ctx, pg } = await page('fr-FR');
  await pg.goto(BASE + FICHE_FR, { waitUntil: 'networkidle' });
  ok(!(await carte(pg)), 'navigateur fr sur page fr : aucune carte');
  await ctx.close();
}

// 6. Choix dans le menu de langues : il prime sur le navigateur
{
  const { ctx, pg } = await page('es-ES');
  await pg.goto(BASE + 'es/produits/', { waitUntil: 'networkidle' });
  await pg.locator('.lang-toggle').click();
  await Promise.all([pg.waitForNavigation(), pg.locator('.lang-menu a[hreflang^="fr"]').click()]);
  ok(!(await carte(pg)), 'choix « Français » dans le menu : pas de carte vers l’espagnol ensuite');
  await pg.goto(BASE + FICHE_FR, { waitUntil: 'networkidle' });
  ok(!(await carte(pg)), 'et sur une autre page française non plus');
  await ctx.close();
}

await b.close();
console.log(echecs ? `\n✗ ${echecs} échec(s)` : '\n✔ tout est conforme');
process.exit(echecs ? 1 : 0);
