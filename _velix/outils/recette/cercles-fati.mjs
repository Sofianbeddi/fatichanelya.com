// Cercles de catégories (1.6.0) : rangée de quatre, étiquettes sans débordement, bascule, accueil mobile.
// BASE=… OUT=dossier ENGINE=chromium|webkit|firefox node cercles-fati.mjs
import { chromium, webkit, firefox } from 'playwright'; import fs from 'node:fs'; import path from 'node:path';
const BASE = process.env.BASE || 'http://localhost/websites/fatichanelya.com/';
const OUT = process.env.OUT || '.'; fs.mkdirSync(OUT, { recursive: true });
const browser = await ({ chromium, webkit, firefox }[process.env.ENGINE || 'chromium']).launch();
const mobile = { isMobile: true, hasTouch: true };
async function run(ctxOpts, w, h, url, fn, name, { rtl = false, full = false } = {}) {
  const context = await browser.newContext({ viewport: { width: w, height: h }, reducedMotion: 'reduce', ...ctxOpts });
  const page = await context.newPage(); const errors = []; page.on('pageerror', (e) => errors.push(e.message));
  if (rtl) await page.addInitScript(() => { document.addEventListener('DOMContentLoaded', () => { document.documentElement.setAttribute('dir', 'rtl'); document.body.classList.add('rtl'); }); });
  await page.goto(url, { waitUntil: 'networkidle' }); const info = await fn(page);
  await page.screenshot({ path: path.join(OUT, name + '.png'), fullPage: full });
  console.log(name, JSON.stringify(info), errors.length ? 'JS-ERR ' + errors.join(' | ') : ''); await context.close();
}
const mesure = (sel) => {
  const f = document.querySelector(sel); if (!f) return { absent: sel };
  const cs = getComputedStyle(f);
  const cercles = [...f.querySelectorAll('.cat-circle')];
  const labels = cercles.map((c) => { const l = c.querySelector('.cat-circle-label'); const lh = parseFloat(getComputedStyle(l).lineHeight); return { t: l.textContent.trim(), lignes: Math.round(l.getBoundingClientRect().height / lh), deborde: l.scrollWidth > l.clientWidth + 1, fs: getComputedStyle(l).fontSize }; });
  const m = cercles[0].querySelector('.cat-circle-media').getBoundingClientRect();
  const r = f.getBoundingClientRect();
  return { display: cs.display, colonnes: cs.gridTemplateColumns.split(' ').length, n: cercles.length, cercle: Math.round(m.width), top: Math.round(r.top), bas: Math.round(r.bottom), h: Math.round(r.height), labels, docW: document.documentElement.scrollWidth, vw: innerWidth };
};
for (const w of [320, 360, 390, 430]) {
  await run(mobile, w, 844, BASE + 'produits/', async (page) => page.evaluate(mesure, '.shop-controls .filters'), 'boutique-' + w);
}
await run(mobile, 390, 844, BASE + 'produits/', async (page) => {
  const avant = await page.evaluate(() => document.querySelector('#result-count').textContent.trim());
  await page.click('.cat-circle[data-filter="complements"]'); await page.waitForTimeout(250);
  const un = await page.evaluate(() => ({ compte: document.querySelector('#result-count').textContent.trim(), actifs: [...document.querySelectorAll('.cat-circle.is-active')].map((c) => c.textContent.trim()), pressed: [...document.querySelectorAll('.cat-circle[data-filter]')].map((c) => c.getAttribute('aria-pressed')).join(''), visibles: [...document.querySelectorAll('.product-card')].filter((c) => !c.hidden).length }));
  await page.screenshot({ path: path.join(OUT, 'boutique-390-selection.png') });
  await page.click('.cat-circle[data-filter="beaute-soin"]'); await page.waitForTimeout(250);
  const deux = await page.evaluate(() => ({ compte: document.querySelector('#result-count').textContent.trim(), actifs: [...document.querySelectorAll('.cat-circle.is-active')].map((c) => c.textContent.trim()) }));
  await page.click('.cat-circle[data-filter="beaute-soin"]'); await page.waitForTimeout(250);
  const retour = await page.evaluate(() => ({ compte: document.querySelector('#result-count').textContent.trim(), actifs: document.querySelectorAll('.cat-circle.is-active').length, pressed: [...document.querySelectorAll('.cat-circle[data-filter]')].map((c) => c.getAttribute('aria-pressed')).join(''), visibles: [...document.querySelectorAll('.product-card')].filter((c) => !c.hidden).length }));
  return { avant, un, deux, retour };
}, 'boutique-390-bascule');
await run(mobile, 390, 740, BASE + 'produits/', async (page) => { await page.evaluate(() => scrollTo({ top: 900, behavior: 'instant' })); await page.waitForTimeout(400); return page.evaluate(() => { const h = document.querySelector('#header').getBoundingClientRect(); const c = document.querySelector('.shop-controls').getBoundingClientRect(); return { ecart: Math.round(c.top - h.bottom), controlsH: Math.round(c.height), header: Math.round(h.height), docW: document.documentElement.scrollWidth }; }); }, 'boutique-390-defilee');
await run(mobile, 390, 844, BASE + 'categorie-produit/complements/', async (page) => page.evaluate(() => { const a = document.querySelector('.shop-controls a.cat-circle.is-active'); return { liens: document.querySelectorAll('.shop-controls a.cat-circle').length, courant: a ? a.textContent.trim() : null, hrefCourant: a ? a.getAttribute('href') : null, ariaCurrent: a ? a.getAttribute('aria-current') : null, compte: document.querySelector('#result-count').textContent.trim() }; }), 'categorie-390');
await run(mobile, 390, 844, BASE, async (page) => { const m = await page.evaluate(mesure, ".hero-cats"); const facts = await page.evaluate(() => getComputedStyle(document.querySelector(".hero-facts")).display); return { ...m, visibleDansEcran: m.bas <= 844, facts }; }, "accueil-390");
await run(mobile, 390, 667, BASE, async (page) => page.evaluate(mesure, '.hero-cats'), 'accueil-390x667');
await run(mobile, 320, 568, BASE, async (page) => page.evaluate(mesure, '.hero-cats'), 'accueil-320');
await run({}, 768, 1024, BASE, async (page) => page.evaluate(mesure, '.hero-cats'), 'accueil-768');
await run({}, 768, 1024, BASE + 'produits/', async (page) => page.evaluate(mesure, '.shop-controls .filters'), 'boutique-768');
await run({}, 1440, 800, BASE, async (page) => page.evaluate(() => ({ heroCats: getComputedStyle(document.querySelector('.hero-cats')).display, facts: getComputedStyle(document.querySelector('.hero-facts')).display })), 'accueil-1440');
await run({}, 1440, 800, BASE + 'produits/', async (page) => { const m = await page.evaluate(mesure, '.shop-controls .filters'); const c = await page.evaluate(() => Math.round(document.querySelector('.shop-controls').getBoundingClientRect().height)); await page.hover('.cat-circle[data-filter="complements"]'); await page.waitForTimeout(350); return { ...m, controlsH: c }; }, 'boutique-1440');
await run({}, 1440, 800, BASE + 'produits/', async (page) => { await page.click('.cat-circle[data-filter="soin-personnel"]'); await page.waitForTimeout(250); await page.mouse.move(10, 10); await page.waitForTimeout(300); return page.evaluate(() => ({ compte: document.querySelector('#result-count').textContent.trim(), actif: document.querySelector('.cat-circle.is-active').textContent.trim() })); }, 'boutique-1440-selection');
await run({}, 1440, 800, BASE + 'produits/', async (page) => page.evaluate(() => { const c = document.querySelector('.shop-controls .cat-circle'); const m = c.querySelector('.cat-circle-media').getBoundingClientRect(); const l = c.querySelector('.cat-circle-label').getBoundingClientRect(); return { dir: getComputedStyle(c).direction, mediaX: Math.round(m.left), labelX: Math.round(l.left), rondADroiteDuTexte: m.left > l.left, docW: document.documentElement.scrollWidth }; }), 'boutique-1440-rtl', { rtl: true });
await run(mobile, 390, 844, BASE + 'produits/', async (page) => page.evaluate(mesure, '.shop-controls .filters'), 'boutique-390-rtl', { rtl: true });
await browser.close();
