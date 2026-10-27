// Panier partagé entre langues + polices arabes chargées + prix LTR dans le tiroir.
import { chromium } from 'playwright';
const BASE = process.env.BASE || 'http://localhost/websites/fatichanelya.com/';
const b = await chromium.launch(); const ctx = await b.newContext({ viewport: { width: 1440, height: 900 } }); const page = await ctx.newPage();
const errs = []; page.on('pageerror', (e) => errs.push(e.message));
await page.goto(BASE + 'ar/produits/', { waitUntil: 'networkidle' });
const fonts = await page.evaluate(async () => { await document.fonts.ready; return { plex: document.fonts.check('16px "IBM Plex Sans Arabic"'), alexandria: document.fonts.check('600 24px Alexandria'), chargées: [...document.fonts].filter((f) => f.status === 'loaded').map((f) => f.family + ' ' + f.weight) }; });
const corps = await page.evaluate(() => { const p = document.querySelector('.section-intro'); const h = document.querySelector('h2'); const b = document.querySelector('.product-add'); return { p: getComputedStyle(p).fontFamily.split(',')[0], h2: getComputedStyle(h).fontFamily.split(',')[0], bouton: getComputedStyle(b).fontFamily.split(',')[0], dir: document.dir, lang: document.documentElement.lang }; });
await page.click('.product-add[data-add]'); await page.waitForTimeout(700);
const tiroir = await page.evaluate(() => { const d = document.querySelector('.drawer'); const prix = document.querySelector('.drawer-item-montant'); return { ouvert: d && !d.hidden, titre: document.querySelector('.drawer h2, .drawer-head h2, .drawer [id*=title]')?.textContent.trim(), montant: prix?.textContent.trim(), dirPrix: prix ? getComputedStyle(prix).direction : null, total: document.querySelector('#drawer-total')?.textContent.trim(), stock: localStorage.getItem(Object.keys(localStorage).find((k) => /bag|panier/i.test(k)) || '') }; });
await page.screenshot({ path: 'ar-tiroir-1440.png' });
// passage en français : le panier doit suivre
await page.goto(BASE + 'produits/', { waitUntil: 'networkidle' }); await page.waitForTimeout(500);
const fr = await page.evaluate(() => ({ compte: document.querySelector('#bag-count')?.textContent.trim(), cache: document.querySelector('#bag-count')?.hidden, qtyVisible: !!document.querySelector('[data-qty-box]:not([hidden])') }));
await page.click('#open-bag'); await page.waitForTimeout(600);
const frTiroir = await page.evaluate(() => ({ nom: document.querySelector('.drawer-item-nom')?.textContent.trim(), montant: document.querySelector('.drawer-item-montant')?.textContent.trim() }));
// retour en arabe
await page.goto(BASE + 'ar/produits/', { waitUntil: 'networkidle' }); await page.waitForTimeout(500);
const ar2 = await page.evaluate(() => ({ compte: document.querySelector('#bag-count')?.textContent.trim(), qtyVisible: !!document.querySelector('[data-qty-box]:not([hidden])') }));
await page.click('#open-bag'); await page.waitForTimeout(600);
const arTiroir = await page.evaluate(() => ({ nom: document.querySelector('.drawer-item-nom')?.textContent.trim(), montant: document.querySelector('.drawer-item-montant')?.textContent.trim(), wa: document.querySelector('.drawer-wa')?.getAttribute('href')?.slice(0, 120) }));
console.log(JSON.stringify({ fonts, corps, tiroir, fr, frTiroir, ar2, arTiroir, errs }, null, 1));
await b.close();
