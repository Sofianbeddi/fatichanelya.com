import { chromium } from 'playwright';
const b = await chromium.launch();
for (const width of [1440, 390]) {
  const c = await b.newContext({ viewport: { width, height: 900 }, reducedMotion: 'reduce' });
  await c.addInitScript(() => { const set = () => { const el = document.documentElement; if (!el) return false; el.setAttribute('dir', 'rtl'); el.setAttribute('lang', 'ar'); return true; }; if (!set()) new MutationObserver((m, o) => { if (set()) o.disconnect(); }).observe(document, { childList: true }); });
  const p = await c.newPage();
  await p.goto('http://localhost/websites/fatichanelya.com/', { waitUntil: 'networkidle' });
  await p.evaluate(() => {
    const t = {
      '.hero .eyebrow': 'عافية طبيعية · تعلّم ملموس',
      '.hero h1': 'اختيارات بسيطة <em>للعناية بنفسك</em>.',
      '.hero .lead': 'اكتشفي تشكيلة ناتوريكسا وتكوينات فاتي العملية، مع تواصل مباشر على واتساب عند الحاجة لنصيحة.',
      '.hero-actions .button': 'اكتشفي المنتجات', '.hero-actions .text-button': 'شاهدي التكوينات',
      '.categories .eyebrow': 'أصنافنا', '.categories h2': 'اعثري على ما <em>يناسبك</em>.',
      '.shop--apercu .eyebrow': 'تشكيلة فاتيشانيليا', '.shop--apercu h2': 'كتالوج ناتوريكسا، <em>معروض بوضوح</em>.',
      '.shop--apercu .section-intro': 'المراجع مرتبة حسب الصنف، مع أسمائها وأسعارها وصورها. تجمعين سلتك هنا، ويتم تأكيد الطلب على واتساب مع فاتي.',
      '.training .eyebrow': 'تكوينات عن بُعد', '.training h2': 'مهارات <em>تنفع فعلاً</em>.',
    };
    for (const [sel, html] of Object.entries(t)) { const el = document.querySelector(sel); if (!el) continue; const svg = el.querySelector('svg'); el.innerHTML = html; if (svg) el.appendChild(svg); }
    document.querySelectorAll('.hero-facts li').forEach((li, i) => li.textContent = ['كتالوج ناتوريكسا', 'تواصل مباشر على واتساب', 'رائدة أعمال · صانعة محتوى · مرشدة'][i] || li.textContent);
    document.querySelectorAll('.categorie-nom').forEach((s, i) => s.textContent = ['تغذية ومشروبات', 'جمال وعناية', 'مكملات', 'عناية شخصية'][i]);
    document.querySelectorAll('.product-card .product-add').forEach((b) => { const svg = b.querySelector('svg'); b.textContent = 'أضيفي'; b.prepend(svg); });
    document.querySelectorAll('.programmes-tab-titre').forEach((s, i) => s.textContent = ['الذكاء الاصطناعي يومياً', 'التسويق الرقمي', 'البيع بثقة', 'صناعة المحتوى والشبكات', 'شراء الإعلانات', 'الأتمتة والمساعدون'][i]);
  });
  await p.evaluate(async () => { await document.fonts.ready; });
  const fonts = await p.evaluate(() => [...document.fonts].filter((f) => f.status === 'loaded').map((f) => f.family + ' ' + f.weight));
  const h1 = await p.evaluate(() => { const h = document.querySelector('.hero h1'); const cs = getComputedStyle(h); return { ls: cs.letterSpacing, lh: cs.lineHeight, fs: cs.fontSize }; });
  console.log(width, 'polices :', fonts.join(' | '), '| h1', JSON.stringify(h1));
  await p.screenshot({ path: `arabe-fati-${width}.png`, clip: { x: 0, y: 0, width, height: width === 1440 ? 2400 : 2600 }, fullPage: true });
  await c.close();
}
await b.close();
