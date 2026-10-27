// Menu de langues : ouverture, clavier, fermeture, RTL, captures (FR et AR, 390 et 1440).
import { chromium } from 'playwright'; import fs from 'node:fs';
const BASE = process.env.BASE || 'http://localhost/websites/fatichanelya.com/'; const OUT = process.env.OUT || 'langues'; fs.mkdirSync(OUT, { recursive: true });
const b = await chromium.launch();
async function scene(url, w, h, nom, opts = {}) {
  const ctx = await b.newContext({ viewport: { width: w, height: h }, deviceScaleFactor: 2, ...opts }); const p = await ctx.newPage(); const errs = []; p.on('pageerror', (e) => errs.push(e.message));
  await p.goto(url, { waitUntil: 'networkidle' });
  const avant = await p.evaluate(() => ({ bouton: document.querySelector('.lang-toggle')?.textContent.trim(), aria: document.querySelector('.lang-toggle')?.getAttribute('aria-label'), cache: document.querySelector('.lang-menu')?.hidden, pied: [...document.querySelectorAll('.footer-lang a')].map((a) => a.textContent.trim() + (a.hasAttribute('aria-current') ? '*' : '')) }));
  await p.click('.lang-toggle'); await p.waitForTimeout(300);
  const ouvert = await p.evaluate(() => { const m = document.querySelector('.lang-menu'); const r = m.getBoundingClientRect(); const b = document.querySelector('.lang-toggle').getBoundingClientRect(); return { visible: !m.hidden, expanded: document.querySelector('.lang-toggle').getAttribute('aria-expanded'), entrees: [...m.querySelectorAll('a')].map((a) => a.textContent.trim().replace(/\s+/g, ' ') + (a.hasAttribute('aria-current') ? '*' : '')), dansEcran: r.left >= 0 && r.right <= innerWidth, sousBouton: Math.round(r.top - b.bottom), alignement: Math.abs(r.right - b.right) < 2 ? 'droite' : (Math.abs(r.left - b.left) < 2 ? 'gauche' : 'autre') }; });
  await p.screenshot({ path: `${OUT}/${nom}-ouvert.png`, clip: { x: 0, y: 0, width: w, height: Math.min(h, 420) } });
  // clavier : flèche bas → focus sur une entrée, Échap → fermé et focus sur le bouton
  await p.keyboard.press('ArrowDown'); await p.waitForTimeout(100);
  const focus1 = await p.evaluate(() => document.activeElement.textContent.trim().replace(/\s+/g, ' '));
  await p.keyboard.press('ArrowDown'); const focus2 = await p.evaluate(() => document.activeElement.textContent.trim().replace(/\s+/g, ' '));
  await p.keyboard.press('Escape'); await p.waitForTimeout(100);
  const apresEchap = await p.evaluate(() => ({ cache: document.querySelector('.lang-menu').hidden, focusBouton: document.activeElement === document.querySelector('.lang-toggle') }));
  await p.click('.lang-toggle'); await p.waitForTimeout(200); await p.mouse.click(w / 2, 300); await p.waitForTimeout(150);
  const apresClicAilleurs = await p.evaluate(() => document.querySelector('.lang-menu').hidden);
  console.log(nom, JSON.stringify({ avant, ouvert, focus1, focus2, apresEchap, apresClicAilleurs, errs }));
  await ctx.close();
}
await scene(BASE, 1440, 900, 'fr-1440');
await scene(BASE + 'ar/', 1440, 900, 'ar-1440');
await scene(BASE, 390, 844, 'fr-390', { isMobile: true, hasTouch: true });
await scene(BASE + 'ar/produits/', 390, 844, 'ar-390', { isMobile: true, hasTouch: true });
await b.close();
