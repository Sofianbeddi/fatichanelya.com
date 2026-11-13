// Bouton WhatsApp flottant (1.8.0) et numéro WhatsApp, sur les 28 pages FR et AR, à 390 et 1440 px :
// tous les liens wa.me vers le numéro attendu, aucun ancien numéro dans la page, bouton calé au coin
// (gauche en arabe), absent du panier, effacé devant le pied de page, le menu mobile et la
// confirmation d'ajout, étiquette au survol, message de la fiche produit, plus de photo de profil
// dans « À propos ».
// BASE=… OUT=dossier ENGINE=chromium|webkit|firefox NUM=34604284263 node whatsapp-fati.mjs
// Sans danger sur la production : il ne fait que lire et remplir le panier du navigateur de test.
import * as pw from 'playwright';
import fs from 'node:fs';

const BASE = process.env.BASE || 'http://localhost/websites/fatichanelya.com/';
const OUT = process.env.OUT || 'captures';
const NUM = process.env.NUM || '34604284263';
// Numéro remplacé le 13 nov. 2026 : il ne doit plus apparaître nulle part.
const ANCIEN = /622[ .]?57[ .]?7|622577161/;
fs.mkdirSync(OUT, { recursive: true });

const ici = new URL('.', import.meta.url);
const fr = JSON.parse(fs.readFileSync(new URL('pages-fati.json', ici), 'utf8'));
const ar = JSON.parse(fs.readFileSync(new URL('pages-fati-ar.json', ici), 'utf8'));
const ko = [];
const ok = (cond, msg) => { console.log((cond ? '  ✔ ' : '  ✘ ') + msg); if (!cond) ko.push(msg); };

const browser = await pw[process.env.ENGINE || 'chromium'].launch();

async function etat(page) {
  return page.evaluate(() => {
    const el = document.querySelector('#wa-float');
    if (!el) return null;
    const cs = getComputedStyle(el), r = el.getBoundingClientRect();
    const lab = el.querySelector('.wa-float-label');
    return {
      href: decodeURIComponent(el.href), visible: cs.visibility === 'visible' && +cs.opacity > 0.99,
      x: Math.round(r.x), y: Math.round(r.y), w: Math.round(r.width), h: Math.round(r.height),
      droite: Math.round(innerWidth - r.right), bas: Math.round(innerHeight - r.bottom),
      label: lab ? lab.textContent : '', labelOpacity: lab ? +getComputedStyle(lab).opacity : null,
      aria: el.getAttribute('aria-label'),
    };
  });
}

for (const [largeur, hauteur] of [[390, 844], [1440, 900]]) {
  const ctx = await browser.newContext({ viewport: { width: largeur, height: hauteur }, deviceScaleFactor: 2, hasTouch: largeur < 700 });
  const page = await ctx.newPage();
  const erreurs = [];
  page.on('pageerror', (e) => erreurs.push(e.message));
  console.log(`\n=== ${largeur} px ===`);

  for (const [jeu, pages, rtl] of [['fr', fr, false], ['ar', ar, true]]) {
    for (const [nom, chemin] of Object.entries(pages)) {
      const rep = await page.goto(BASE + chemin, { waitUntil: 'load' });
      await page.waitForTimeout(1400);
      const html = await page.content();
      const liens = await page.$$eval('a[href*="wa.me"]', (as) => as.map((a) => a.href));
      const mauvais = liens.filter((h) => !h.includes(`wa.me/${NUM}`));
      const e = await etat(page);
      const prefixe = `${jeu}/${nom}`;
      ok(!ANCIEN.test(html), `${prefixe} : aucun ancien numéro dans la page`);
      ok(liens.length > 0 && mauvais.length === 0, `${prefixe} : ${liens.length} liens WhatsApp, tous vers ${NUM}`);
      if (nom === 'panier') { ok(e === null, `${prefixe} : pas de bouton flottant`); continue; }
      if (!e) { ok(false, `${prefixe} : bouton flottant absent (HTTP ${rep.status()})`); continue; }
      const debordement = await page.evaluate(() => document.documentElement.scrollWidth - innerWidth);
      ok(debordement <= 0, `${prefixe} : pas de débordement horizontal (${debordement})`);
      const piedVisible = await page.evaluate(() => [...document.querySelectorAll('.footer-actions,.footer-bottom')].some((el) => { const r = el.getBoundingClientRect(); return r.top < innerHeight && r.bottom > 0; }));
      ok(e.visible === !piedVisible, `${prefixe} : bouton ${e.visible ? 'visible' : 'effacé'} en haut de page (pied à l'écran : ${piedVisible})`);
      const marge = largeur < 700 ? 16 : 24;
      const cote = rtl ? e.x : e.droite;
      ok(cote === marge && e.bas === marge, `${prefixe} : calé à ${cote}/${e.bas} px du coin ${rtl ? 'gauche' : 'droit'}`);
      if (nom === 'produit') ok(/question sur «|سؤال/.test(e.href) || e.href.includes('«'), `${prefixe} : message de la fiche → ${e.href.slice(e.href.indexOf('text=') + 5, e.href.indexOf('text=') + 75)}`);
      if (nom === 'home' || nom === 'produit' || nom === 'a-propos') await page.screenshot({ path: `${OUT}/${jeu}-${nom}-${largeur}.png` });
      if (nom === 'home') {
        console.log('    ', JSON.stringify(e));
        // Survol / focus : étiquette.
        if (largeur >= 700) {
          await page.hover('#wa-float'); await page.waitForTimeout(450);
          const s = await etat(page);
          ok(s.labelOpacity === 1, `${prefixe} : étiquette « ${s.label} » au survol`);
          await page.screenshot({ path: `${OUT}/${jeu}-home-${largeur}-survol.png`, clip: { x: rtl ? 0 : largeur - 360, y: hauteur - 160, width: 360, height: 160 } });
          await page.mouse.move(10, 10);
        } else {
          // Menu mobile ouvert.
          await page.click('#burger'); await page.waitForTimeout(450);
          ok(!(await etat(page)).visible, `${prefixe} : effacé quand le menu mobile est ouvert`);
          await page.screenshot({ path: `${OUT}/${jeu}-home-${largeur}-menu.png` });
          await page.click('#burger'); await page.waitForTimeout(450);
          ok((await etat(page)).visible, `${prefixe} : revient à la fermeture du menu`);
        }
        // Section À propos : plus de pastille.
        ok((await page.$('.about-avatar')) === null, `${prefixe} : plus de photo de profil dans « À propos »`);
        const galerie = await page.$('.about-gallery');
        if (galerie) { await galerie.scrollIntoViewIfNeeded(); await page.evaluate(() => scrollBy({ top: -120, behavior: 'instant' })); await page.waitForTimeout(900); await page.screenshot({ path: `${OUT}/${jeu}-apropos-section-${largeur}.png` }); }
        // Bas de page : le bouton s'efface.
        await page.evaluate(() => scrollTo({ top: document.documentElement.scrollHeight, behavior: 'instant' }));
        await page.waitForTimeout(700);
        ok(!(await etat(page)).visible, `${prefixe} : effacé en bas de page`);
        await page.screenshot({ path: `${OUT}/${jeu}-home-${largeur}-bas.png` });
      }
      if (nom === 'produits' && largeur < 700) {
        // Ajout au panier : la confirmation occupe le bas de l'écran.
        const bouton = page.locator('[data-add]:visible').first();
        if (await bouton.count()) {
          // Le pointeur quitte le bas de l'écran : posé sur la confirmation, il la retient.
          await bouton.scrollIntoViewIfNeeded(); await bouton.click(); await page.mouse.move(5, 5); await page.waitForTimeout(600);
          ok(!(await etat(page)).visible, `${prefixe} : effacé pendant la confirmation d'ajout`);
          await page.screenshot({ path: `${OUT}/${jeu}-produits-${largeur}-toast.png` });
          await page.waitForTimeout(5200);
          ok((await etat(page)).visible, `${prefixe} : revient après la confirmation`);
        }
      }
    }
  }
  ok(erreurs.length === 0, `aucune erreur JavaScript (${erreurs.slice(0, 2).join(' | ')})`);
  await ctx.close();
}
await browser.close();
console.log(ko.length ? `\n${ko.length} ÉCHEC(S)\n - ` + ko.join('\n - ') : '\nTOUT EST BON');
process.exit(ko.length ? 1 : 0);
