// Recette APE Maroc — captures + audit responsive (Playwright).
// node shoot.mjs --out DIR [--widths 390,768,1440] [--pages home,valeurs] [--rtl] [--browser chromium|webkit|firefox]
//                [--motion] (laisse jouer les animations, sinon prefers-reduced-motion) [--audit FILE.json] [--no-shot] [--height 900]
import { chromium, webkit, firefox } from 'playwright';
import fs from 'node:fs';
import path from 'node:path';

const BASE = process.env.BASE || 'http://localhost/websites/apemaroc.ma/';
const PAGES = {
  home: '',
  'presentation-mission': 'presentation-mission/',
  'mot-president': 'mot-president/',
  'structure-gouvernance': 'structure-gouvernance/',
  'membres-ape': 'membres-ape/',
  partenaires: 'partenaires/',
  valeurs: 'valeurs/',
  'devenir-membre': 'devenir-membre/',
  actualites: 'actualites/',
  'revue-presse': 'revue-presse/',
  publications: 'publications/',
  contact: 'contact/',
  'mentions-legales': 'mentions-legales/',
  article: 'barometre-gouvernance-responsable-2025/',
};

const args = process.argv.slice(2);
const opt = (k, d) => { const i = args.indexOf('--' + k); return i >= 0 ? args[i + 1] : d; };
const flag = (k) => args.includes('--' + k);
const out = opt('out', 'shots');
const widths = opt('widths', '390,768,1440').split(',').map(Number);
const pagesFile = opt('pagesfile', '');
if (pagesFile) { const extra = JSON.parse(fs.readFileSync(pagesFile, 'utf8')); for (const k of Object.keys(PAGES)) delete PAGES[k]; Object.assign(PAGES, extra); }
const pages = opt('pages', Object.keys(PAGES).join(',')).split(',');
const rtl = flag('rtl');
const motion = flag('motion');
const noShot = flag('no-shot');
const auditFile = opt('audit', '');
const height = Number(opt('height', 900));
const engine = { chromium, webkit, firefox }[opt('browser', 'chromium')];

fs.mkdirSync(out, { recursive: true });
const browser = await engine.launch();
const audit = [];

for (const width of widths) {
  const context = await browser.newContext({
    viewport: { width, height },
    deviceScaleFactor: 1,
    reducedMotion: motion ? 'no-preference' : 'reduce',
    isMobile: width < 820,
    hasTouch: width < 820,
  });
  if (rtl) {
    await context.addInitScript(() => {
      const set = () => { const h = document.documentElement; if (!h) return false; h.setAttribute('dir', 'rtl'); h.setAttribute('lang', 'ar'); return true; };
      if (!set()) new MutationObserver((m, o) => { if (set()) o.disconnect(); }).observe(document, { childList: true });
      document.addEventListener('DOMContentLoaded', () => document.body.classList.add('rtl'));
    });
  }
  for (const slug of pages) {
    const page = await context.newPage();
    const url = BASE + PAGES[slug];
    const errors = [];
    page.on('pageerror', (e) => errors.push('pageerror: ' + e.message));
    page.on('console', (m) => { if (m.type() === 'error') errors.push('console: ' + m.text()); });
    await page.goto(url, { waitUntil: 'networkidle' });
    await page.evaluate(async () => { await document.fonts.ready; });
    // Fait défiler toute la page pour charger les images paresseuses et déclencher les révélations.
    await page.evaluate(async () => {
      const step = Math.max(300, Math.floor(innerHeight * 0.8));
      for (let y = 0; y < document.documentElement.scrollHeight; y += step) { scrollTo({ top: y, behavior: 'instant' }); await new Promise((r) => setTimeout(r, 90)); }
      scrollTo({ top: 0, behavior: 'instant' });
      await new Promise((r) => setTimeout(r, 250));
    });
    if (motion) await page.waitForTimeout(1800);

    const result = await page.evaluate(([isMobile, SEL]) => {
      const vw = document.documentElement.clientWidth;
      const docW = document.documentElement.scrollWidth;
      const visible = (el) => { const r = el.getBoundingClientRect(); const cs = getComputedStyle(el); return r.width > 0 && r.height > 0 && cs.visibility !== 'hidden' && cs.display !== 'none' && cs.opacity !== '0'; };
      const label = (el) => { let s = el.tagName.toLowerCase(); if (el.id) s += '#' + el.id; if (el.classList.length) s += '.' + [...el.classList].slice(0, 3).join('.'); return s; };
      const inScroller = (el) => { let p = el.parentElement; while (p && p !== document.body) { const o = getComputedStyle(p).overflowX; if (o === 'auto' || o === 'scroll' || o === 'hidden' || o === 'clip') return true; p = p.parentElement; } return false; };
      const overflow = [];
      const smallTargets = [];
      const tinyText = new Map();
      const noDims = [];
      for (const el of document.querySelectorAll('body *')) {
        if (!visible(el)) continue;
        const r = el.getBoundingClientRect();
        if (((r.right > vw + 1 && r.left < 5000) || (r.left < -1 && r.left > -5000)) && !inScroller(el) && overflow.length < 20) overflow.push({ el: label(el), left: Math.round(r.left), right: Math.round(r.right), width: Math.round(r.width) });
        if (el.matches('a, button, input, select, textarea, [role=button]') && !el.matches('[aria-disabled=true], [tabindex="-1"]')) {
          const min = isMobile ? 40 : 24;
          if ((r.width < min || r.height < min) && smallTargets.length < 20) smallTargets.push({ el: label(el), w: Math.round(r.width), h: Math.round(r.height), text: (el.textContent || el.getAttribute('aria-label') || '').trim().slice(0, 30) });
        }
        const fs = parseFloat(getComputedStyle(el).fontSize);
        if (fs < 12 && el.childNodes.length && [...el.childNodes].some((n) => n.nodeType === 3 && n.textContent.trim())) tinyText.set(label(el), fs);
        if (el.tagName === 'IMG' && !(el.getAttribute('width') && el.getAttribute('height')) && !el.closest('.hero') ) noDims.push(label(el) + ' ' + (el.getAttribute('src') || '').split('/').pop());
      }
      return {
        vw, docW, hOverflow: docW > vw, overflow, smallTargets, tinyText: [...tinyText.entries()].slice(0, 15), noDims: noDims.slice(0, 10),
        h1: document.querySelectorAll('h1').length,
        h1w: (() => { const h = document.querySelector('h1'); if (!h) return 0; const r = h.getBoundingClientRect(); return Math.round(r.width); })(),
        h1x: (() => { const h = document.querySelector('h1'); if (!h) return 0; return Math.round(h.getBoundingClientRect().left); })(),
        notShown: document.querySelectorAll(SEL.notShown).length,
        ghosts: [...document.querySelectorAll(SEL.ghost)].filter((el) => { const r = el.getBoundingClientRect(); return getComputedStyle(el).opacity === '0' && r.height > 0 && r.right > 0 && r.left < vw; }).map((el) => el.tagName.toLowerCase() + '.' + [...el.classList].slice(0, 3).join('.')).slice(0, 8),
        dir: document.documentElement.dir || 'ltr',
      };
    }, [width < 820, { notShown: opt('notshown', '.reveal:not(.show), .stagger:not(.show)'), ghost: opt('ghost', '.reveal, .stagger > *, .section-head.reveal > *') }]);
    result.page = slug; result.width = width; result.errors = errors;
    audit.push(result);
    const badge = result.hOverflow ? `OVERFLOW ${result.docW}>${result.vw}` : 'ok';
    console.log(`${slug.padEnd(22)} ${String(width).padStart(5)} ${rtl ? 'rtl' : 'ltr'}  ${badge}${errors.length ? '  JS-ERR ' + errors.length : ''}`);
    if (!noShot) {
      // Firefox refuse une capture de plus de 32 767 px de haut (boutique de 103 produits à 390 px) : on tronque.
      const file = path.join(out, `${slug}-${width}${rtl ? '-rtl' : ''}.png`);
      const docH = await page.evaluate(() => document.documentElement.scrollHeight);
      if (docH > 32000) await page.screenshot({ path: file, clip: { x: 0, y: 0, width, height: 32000 }, fullPage: true });
      else await page.screenshot({ path: file, fullPage: true });
    }
    await page.close();
  }
  await context.close();
}
await browser.close();
if (auditFile) fs.writeFileSync(auditFile, JSON.stringify(audit, null, 2));
