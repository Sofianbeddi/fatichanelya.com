// Fatichanelya — états interactifs en LTR et RTL : menu mobile, carrousel, ajout au panier (toast + tiroir).
import { chromium, webkit, firefox } from 'playwright'; import fs from 'node:fs'; import { PNG } from 'pngjs';
const BASE = 'http://localhost/websites/fatichanelya.com/';
const engine = { chromium, webkit, firefox }[process.env.ENGINE || 'chromium']; const browser = await engine.launch();
const tiles = [];
async function shot(ctxOpts, rtl, fn, w, h, name, url = BASE) {
  const context = await browser.newContext({ viewport: { width: w, height: h }, reducedMotion: 'reduce', ...ctxOpts });
  if (rtl) await context.addInitScript(() => { const set = () => { const el = document.documentElement; if (!el) return false; el.setAttribute('dir', 'rtl'); return true; }; if (!set()) new MutationObserver((m, o) => { if (set()) o.disconnect(); }).observe(document, { childList: true }); });
  const page = await context.newPage(); const errors = [];
  page.on('pageerror', (e) => errors.push(e.message));
  await page.goto(url, { waitUntil: 'networkidle' });
  const info = await fn(page);
  const buf = await page.screenshot({ clip: { x: 0, y: 0, width: w, height: h } });
  tiles.push({ name, buf }); console.log(name, JSON.stringify(info || {}), errors.length ? 'JS-ERR ' + errors.join(' | ') : '');
  await context.close();
}
for (const rtl of [false, true]) {
  const tag = rtl ? 'rtl' : 'ltr';
  await shot({ isMobile: true, hasTouch: true }, rtl, async (page) => {
    await page.click('#burger'); await page.waitForTimeout(300);
    return await page.evaluate(() => { const p = document.querySelector('#mobile-panel'); const r = p.getBoundingClientRect(); return { hidden: p.hidden, top: Math.round(r.top), h: Math.round(r.height), maxH: getComputedStyle(p).maxBlockSize, scrollH: p.scrollHeight }; });
  }, 844, 390, `menu-paysage-${tag}`);
  await shot({}, rtl, async (page) => {
    await page.evaluate(() => document.querySelector('#shop').scrollIntoView()); await page.waitForTimeout(300);
    await page.click('[data-carousel-suiv]'); await page.waitForTimeout(600);
    const r = await page.evaluate(() => { const t = document.querySelector('[data-carousel-piste]'); const b = document.querySelector('[data-carousel-barre]'); const prec = document.querySelector('.carousel-prec .i'); return { scrollLeft: t.scrollLeft, precDisabled: document.querySelector('[data-carousel-prec]').disabled, suivDisabled: document.querySelector('[data-carousel-suiv]').disabled, progres: b.style.getPropertyValue('--progres'), compte: document.querySelector('[data-carousel-compte]').textContent, precTransform: getComputedStyle(prec).transform }; });
    await page.evaluate(() => { const y = document.querySelector('#shop').getBoundingClientRect().top + scrollY; scrollTo(0, y); });
    return r;
  }, 1440, 760, `carousel-${tag}`);
  await shot({}, rtl, async (page) => {
    await page.evaluate(() => document.querySelector('#shop').scrollIntoView()); await page.waitForTimeout(300);
    await page.click('.product-card [data-add]'); await page.waitForTimeout(700);
    const t = await page.evaluate(() => { const toast = document.querySelector('#toast'); const r = toast.getBoundingClientRect(); const qty = document.querySelector('.product-card .qty:not([hidden])'); return { toastVisible: toast.classList.contains('is-visible'), toastLeft: Math.round(r.left), toastRight: Math.round(r.right), bagCount: document.querySelector('#bag-count').textContent, qtyShown: !!qty, drawerHidden: document.querySelector('#bag-drawer').hidden }; });
    await page.click('#toast-action'); await page.waitForTimeout(500);
    const d = await page.evaluate(() => { const dr = document.querySelector('#bag-drawer'); const r = dr.getBoundingClientRect(); return { drawerHidden: dr.hidden, drawerLeft: Math.round(r.left), drawerRight: Math.round(r.right), lignes: document.querySelectorAll('#drawer-list li').length, total: document.querySelector('#drawer-total').textContent }; });
    await page.evaluate(() => { const y = document.querySelector('#shop').getBoundingClientRect().top + scrollY; scrollTo(0, y); });
    return { ...t, ...d };
  }, 1440, 760, `panier-${tag}`);
}
const imgs = tiles.map((t) => PNG.sync.read(t.buf)); const W = Math.max(...imgs.map((i) => i.width)); const H = imgs.reduce((s, i) => s + i.height + 6, 0);
const sheet = new PNG({ width: W, height: H }); let y = 0; for (const i of imgs) { PNG.bitblt(i, sheet, 0, 0, i.width, i.height, 0, y); y += i.height + 6; }
fs.writeFileSync(`interact-fati-${process.env.ENGINE || 'chromium'}.png`, PNG.sync.write(sheet)); await browser.close();
