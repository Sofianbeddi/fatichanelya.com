/**
 * Fatichanelya — comportements de façade.
 *
 * Aucune dépendance. La grille produits est rendue par PHP : ce fichier ne
 * fabrique pas de contenu, il filtre, ouvre les fiches et compose le message
 * WhatsApp. Sans JavaScript, le catalogue reste lisible et indexable.
 */
(() => {
  'use strict';

  const CFG  = window.FATI || {};
  const T    = CFG.i18n || {};
  const $    = (sel, root = document) => root.querySelector(sel);
  const $$   = (sel, root = document) => [...root.querySelectorAll(sel)];
  const fmt  = (s, ...args) => args.reduce((out, a) => out.replace(/%[sd]/, a), String(s || ''));

  /* ---------- Catalogue sérialisé par PHP ---------- */
  let PRODUCTS = [];
  try {
    const node = $('#fati-produits');
    if (node) PRODUCTS = JSON.parse(node.textContent) || [];
  } catch (e) {
    PRODUCTS = [];
  }
  const byId = (id) => PRODUCTS.find((p) => String(p.id) === String(id));

  const wa = (message) => {
    if (!CFG.whatsapp) return '#contact';
    return `https://wa.me/${CFG.whatsapp}` + (message ? `?text=${encodeURIComponent(message)}` : '');
  };
  const eur = (n) => new Intl.NumberFormat(document.documentElement.lang || 'fr', {
    style: 'currency', currency: 'EUR',
    minimumFractionDigits: n % 1 === 0 ? 0 : 2
  }).format(n);

  /* ---------- Header collant ---------- */
  const header = $('#header');
  const sentinel = $('#scroll-sentinel');
  if (header && sentinel && 'IntersectionObserver' in window) {
    new IntersectionObserver(([entry]) => {
      header.classList.toggle('is-stuck', !entry.isIntersecting);
    }, { rootMargin: '-1px 0px 0px 0px', threshold: 0 }).observe(sentinel);
  }

  /* ---------- Menu mobile ---------- */
  const burger = $('#burger');
  const panel  = $('#mobile-panel');
  if (burger && panel) {
    burger.addEventListener('click', () => {
      const open = panel.hidden;
      panel.hidden = !open;
      burger.setAttribute('aria-expanded', String(open));
    });
    panel.addEventListener('click', (e) => {
      if (e.target.closest('a')) {
        panel.hidden = true;
        burger.setAttribute('aria-expanded', 'false');
      }
    });
  }

  /* ---------- Sélecteur de langue ---------- */
  const lang = $('#lang-select');
  if (lang) {
    lang.addEventListener('change', (e) => {
      if (e.target.value) window.location.href = e.target.value;
    });
  }

  /* ---------- Reveal au défilement ----------
     Le CSS ne masque que si <html> porte .js-reveal : sans JS, sans
     IntersectionObserver ou en prefers-reduced-motion, tout reste visible. */
  if ('IntersectionObserver' in window &&
      !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    document.documentElement.classList.add('js-reveal');
    const io = new IntersectionObserver((entries) => {
      entries.forEach((en) => {
        if (en.isIntersecting) { en.target.classList.add('is-in'); io.unobserve(en.target); }
      });
    }, { rootMargin: '0px 0px -8% 0px', threshold: 0.1 });
    $$('.reveal').forEach((el) => io.observe(el));
    setTimeout(() => $$('.reveal:not(.is-in)').forEach((el) => {
      if (el.getBoundingClientRect().top < innerHeight * 1.5) el.classList.add('is-in');
    }), 3000);
  }

  /* ---------- Toast ---------- */
  const toast = $('#toast');
  let toastTimer;
  const say = (msg) => {
    if (!toast) return;
    toast.textContent = msg;
    toast.hidden = false;
    requestAnimationFrame(() => toast.classList.add('is-visible'));
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => {
      toast.classList.remove('is-visible');
      setTimeout(() => { toast.hidden = true; }, 300);
    }, 2600);
  };

  /* ---------- Filtres et recherche ---------- */
  const grid = $('#product-grid');
  if (grid) {
    const cards  = $$('.product-card', grid);
    const count  = $('#result-count');
    const noRes  = $('#no-result');
    const search = $('#product-search');
    let activeCat = 'all';

    const apply = () => {
      const q = search ? search.value.trim().toLowerCase() : '';
      let shown = 0;
      cards.forEach((card) => {
        const okCat = activeCat === 'all' || card.dataset.cat === activeCat;
        const okTxt = !q || (card.dataset.name || '').includes(q);
        card.hidden = !(okCat && okTxt);
        if (!card.hidden) shown++;
      });
      if (count) {
        count.textContent = shown === 0
          ? (T.none || '')
          : fmt(shown === 1 ? T.shown_one : T.shown, shown);
      }
      if (noRes) noRes.hidden = shown !== 0;
    };

    $$('.chip-button').forEach((btn) => {
      btn.addEventListener('click', () => {
        $$('.chip-button').forEach((b) => {
          b.classList.toggle('is-active', b === btn);
          b.setAttribute('aria-pressed', String(b === btn));
        });
        activeCat = btn.dataset.filter;
        apply();
      });
    });

    if (search) {
      let timer;
      search.addEventListener('input', () => {
        clearTimeout(timer);
        timer = setTimeout(apply, 160);
      });
    }
    const reset = $('#reset-search');
    if (reset && search) {
      reset.addEventListener('click', () => { search.value = ''; apply(); search.focus(); });
    }
  }

  /* ---------- Sélection (panier léger, sans paiement en ligne) ---------- */
  const bag       = new Map();
  const bagCount  = $('#bag-count');
  const drawer    = $('#bag-drawer');
  const backdrop  = $('#drawer-backdrop');
  const list      = $('#drawer-list');
  const empty     = $('#drawer-empty');
  const totalEl   = $('#drawer-total');
  const drawerWa  = $('#drawer-wa');
  const openBtn   = $('#open-bag');
  let lastFocused = null;

  const renderBag = () => {
    if (!list) return;
    const items = [...bag.values()];
    const units = items.reduce((s, it) => s + it.q, 0);
    const total = items.reduce((s, it) => s + (it.prix || 0) * it.q, 0);

    if (bagCount) bagCount.textContent = String(units);
    if (openBtn) {
      // Le nom accessible doit contenir le texte visible (le compteur) — WCAG 2.5.3
      openBtn.setAttribute('aria-label', fmt(units > 1 ? T.bags : T.bag, units));
    }
    if (empty)   empty.hidden = items.length > 0;
    if (totalEl) totalEl.textContent = eur(total);

    list.replaceChildren(...items.map((it) => {
      const li = document.createElement('li');

      const media = document.createElement('span');
      media.innerHTML = it.vignette || '';
      const img = media.querySelector('img');
      if (img) { img.alt = ''; img.width = 56; img.height = 56; li.appendChild(img); }

      const info = document.createElement('span');
      const name = document.createElement('span');
      name.className = 'dl-name';
      name.textContent = it.nom;
      const price = document.createElement('span');
      price.className = 'dl-price';
      price.textContent = `${it.q} × ${it.prixFmt || eur(it.prix || 0)}`;
      info.append(name, document.createElement('br'), price);
      li.appendChild(info);

      const del = document.createElement('button');
      del.type = 'button';
      del.className = 'icon-button';
      del.dataset.remove = it.id;
      del.setAttribute('aria-label', fmt(T.remove, it.nom));
      del.innerHTML = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true" focusable="false"><path d="M6 6l12 12M18 6 6 18"/></svg>';
      li.appendChild(del);

      return li;
    }));

    if (drawerWa) {
      const lines = items.map((it) => `• ${it.nom} × ${it.q} — ${eur((it.prix || 0) * it.q)}`).join('\n');
      drawerWa.href = wa(items.length
        ? `${T.waIntro}\n${lines}\n\n${T.waTotal} ${eur(total)}\n${T.waConfirm}`
        : T.waHello);
    }
  };

  const addToBag = (id) => {
    const p = byId(id);
    if (!p) return;
    const item = bag.get(String(id)) || { ...p, q: 0 };
    item.q += 1;
    bag.set(String(id), item);
    renderBag();
    say(fmt(T.added, p.nom));
  };

  const openDrawer = () => {
    if (!drawer) return;
    lastFocused = document.activeElement;
    drawer.hidden = false;
    if (backdrop) backdrop.hidden = false;
    if (openBtn) openBtn.setAttribute('aria-expanded', 'true');
    document.body.style.overflow = 'hidden';
    $('#close-bag').focus();
  };

  const closeDrawer = () => {
    if (!drawer) return;
    drawer.hidden = true;
    if (backdrop) backdrop.hidden = true;
    if (openBtn) openBtn.setAttribute('aria-expanded', 'false');
    document.body.style.overflow = '';
    (lastFocused || openBtn || document.body).focus();
  };

  if (openBtn) openBtn.addEventListener('click', openDrawer);
  if ($('#close-bag')) $('#close-bag').addEventListener('click', closeDrawer);
  if (backdrop) backdrop.addEventListener('click', closeDrawer);

  if (list) {
    list.addEventListener('click', (e) => {
      const btn = e.target.closest('[data-remove]');
      if (!btn) return;
      bag.delete(String(btn.dataset.remove));
      renderBag();
      (list.querySelector('button') || $('#close-bag')).focus();
    });
  }

  if (drawer) {
    drawer.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') { closeDrawer(); return; }
      if (e.key !== 'Tab') return;
      const f = $$('a[href],button:not([disabled]),input,select,[tabindex]:not([tabindex="-1"])', drawer)
        .filter((el) => el.offsetParent !== null);
      if (!f.length) return;
      const first = f[0], last = f[f.length - 1];
      if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
      else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
    });
  }

  renderBag();

  /* ---------- Fiche produit en <dialog> natif ---------- */
  const dlg = $('#product-dialog');

  const openProduct = (id) => {
    const p = byId(id);
    if (!p || !dlg) return;

    const media = $('#pd-picture');
    if (media) media.innerHTML = p.img || '';

    const set = (sel, value) => { const el = $(sel); if (el) el.textContent = value; };
    set('#pd-cat', p.catNom || '');
    set('#pd-title', p.nom);
    set('#pd-price', p.prixFmt || '');
    set('#pd-desc', p.desc || '');

    const cat = $('#pd-cat');
    if (cat) cat.hidden = !p.catNom;

    const add = $('#pd-add');
    if (add) add.dataset.add = p.id;

    const ask = $('#pd-wa');
    if (ask) ask.href = wa(fmt(T.waQuestion, p.nom));

    dlg.showModal();
  };

  if (dlg) {
    const close = $('#pd-close');
    if (close) close.addEventListener('click', () => dlg.close());
    dlg.addEventListener('click', (e) => { if (e.target === dlg) dlg.close(); });
  }

  document.addEventListener('click', (e) => {
    const open = e.target.closest('[data-open]');
    if (open) { openProduct(open.dataset.open); return; }
    const add = e.target.closest('[data-add]');
    if (add) addToBag(add.dataset.add);
  });
})();
