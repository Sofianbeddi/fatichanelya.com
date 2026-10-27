// Menu mobile sous l'en-tête (haut de page et en-tête réduit), filtres + recherche collants (boutique, 1440 et 390).
import { chromium } from 'playwright'; import fs from 'node:fs'; import { PNG } from 'pngjs';
const BASE = process.env.BASE || 'http://localhost/websites/fatichanelya.com/';
const browser = await chromium.launch(); const tiles = [];
async function shot(ctxOpts, w, h, url, fn, name) {
  const context = await browser.newContext({ viewport: { width: w, height: h }, reducedMotion: 'reduce', ...ctxOpts });
  const page = await context.newPage(); const errors = []; page.on('pageerror', (e) => errors.push(e.message));
  await page.goto(url, { waitUntil: 'networkidle' });
  const info = await fn(page);
  tiles.push({ buf: await page.screenshot({ clip: { x: 0, y: 0, width: w, height: h } }) });
  console.log(name, JSON.stringify(info), errors.length ? 'JS-ERR ' + errors.join(' | ') : '');
  await context.close();
}
const mesureMenu = async (page) => page.evaluate(() => { const h = document.querySelector('#header').getBoundingClientRect(); const p = document.querySelector('#mobile-panel'); const r = p.getBoundingClientRect(); return { headerBottom: Math.round(h.bottom), panelTop: Math.round(r.top), panelH: Math.round(r.height), maxH: getComputedStyle(p).maxBlockSize, bodyOverflow: document.body.style.overflow, ecart: Math.round(r.top - h.bottom) }; });
await shot({ isMobile: true, hasTouch: true }, 390, 740, BASE, async (page) => { await page.click('#burger'); await page.waitForTimeout(350); return await mesureMenu(page); }, 'menu-haut-de-page');
await shot({ isMobile: true, hasTouch: true }, 390, 740, BASE, async (page) => { await page.evaluate(() => scrollTo({ top: 600, behavior: 'instant' })); await page.waitForTimeout(450); await page.click('#burger'); await page.waitForTimeout(350); return await mesureMenu(page); }, 'menu-en-tete-reduit');
await shot({ isMobile: true, hasTouch: true }, 844, 390, BASE, async (page) => { await page.evaluate(() => scrollTo({ top: 600, behavior: 'instant' })); await page.waitForTimeout(450); await page.click('#burger'); await page.waitForTimeout(350); return await mesureMenu(page); }, 'menu-paysage');
const mesureControles = async (page) => page.evaluate(() => { const h = document.querySelector('#header').getBoundingClientRect(); const c = document.querySelector('.shop-controls').getBoundingClientRect(); const f = document.querySelector('.filters'); return { headerBottom: Math.round(h.bottom), controlsTop: Math.round(c.top), controlsH: Math.round(c.height), ecart: Math.round(c.top - h.bottom), headerVar: getComputedStyle(document.documentElement).getPropertyValue('--header-h').trim(), chipsScroll: f.scrollWidth > f.clientWidth, stuck: document.querySelector('#header').classList.contains('is-stuck') }; });
await shot({}, 1440, 800, BASE + 'produits/', async (page) => { await page.evaluate(() => scrollTo({ top: 900, behavior: 'instant' })); await page.waitForTimeout(450); return await mesureControles(page); }, 'boutique-1440-defilee');
await shot({ isMobile: true, hasTouch: true }, 390, 740, BASE + 'produits/', async (page) => { await page.evaluate(() => scrollTo({ top: 900, behavior: 'instant' })); await page.waitForTimeout(450); return await mesureControles(page); }, 'boutique-390-defilee');
await shot({ isMobile: true, hasTouch: true }, 390, 740, BASE + 'produits/', async (page) => { return await mesureControles(page); }, 'boutique-390-haut');
const imgs = tiles.map((t) => PNG.sync.read(t.buf)); const W = Math.max(...imgs.map((i) => i.width)); const H = imgs.reduce((s, i) => s + i.height + 6, 0);
const sheet = new PNG({ width: W, height: H }); let y = 0; for (const i of imgs) { PNG.bitblt(i, sheet, 0, 0, i.width, i.height, 0, y); y += i.height + 6; }
fs.writeFileSync('sticky-fati.png', PNG.sync.write(sheet)); await browser.close();
