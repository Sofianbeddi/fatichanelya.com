/**
 * Fatichanelya — comportements de façade.
 *
 * Aucune dépendance. Les pages sont rendues par PHP : ce fichier ne fabrique
 * pas de contenu, il filtre la boutique, tient le panier et compose le message
 * WhatsApp. Sans JavaScript, le catalogue reste lisible, chaque carte mène à
 * sa fiche et l'icône du panier mène à la page Panier.
 */
(() => {
  'use strict';

  const CFG  = window.FATI || {};
  const T    = CFG.i18n || {};
  const $    = (sel, root = document) => root.querySelector(sel);
  const $$   = (sel, root = document) => [...root.querySelectorAll(sel)];
  /* Remplacement par fonction : un nom de produit contenant « $ » ne doit pas
     être lu comme un motif de remplacement. */
  const fmt  = (s, ...args) => args.reduce((out, a) => out.replace(/%[sd]/, () => a), String(s || ''));
  const borne = (n, min, max) => Math.min(max, Math.max(min, n));
  const reduit = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* ---------- Catalogue sérialisé par PHP ---------- */
  let PRODUCTS = [];
  try {
    const node = $('#fati-produits');
    if (node) PRODUCTS = JSON.parse(node.textContent) || [];
  } catch (e) {
    PRODUCTS = [];
  }
  const byId = (id) => PRODUCTS.find((p) => String(p.id) === String(id));

  /* Sans numéro WhatsApp, le lien mène à la page Contact : jamais à une ancre
     qui n'existe pas sur la page courante. */
  const wa = (message) => {
    if (!CFG.whatsapp) return CFG.contact || '/';
    return `https://wa.me/${CFG.whatsapp}` + (message ? `?text=${encodeURIComponent(message)}` : '');
  };
  const eur = (n) => new Intl.NumberFormat(document.documentElement.lang || 'fr', {
    style: 'currency', currency: 'EUR',
    minimumFractionDigits: n % 1 === 0 ? 0 : 2
  }).format(n);

  /* Un produit sans prix en base arrive avec `prix: null`. Il reste
     commandable, mais sa ligne dit « prix à confirmer » et il n'entre pas
     dans le total, dont le libellé le signale. */
  const sansPrix     = (items) => items.some((it) => it.prix == null);
  const montantLigne = (it) => (it.prix == null ? (T.aConfirmer || 'prix à confirmer') : eur(it.prix * it.q));
  const libelleTotal = (partiel, defaut) => (partiel ? (T.totalPartiel || defaut) : defaut);

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
  if ('IntersectionObserver' in window && !reduit) {
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

  /* ---------- Carrousel ----------
     Le défilement est natif : le geste tactile et la molette fonctionnent sans
     JavaScript, et les cartes restent toutes dans le HTML, donc lisibles par
     les moteurs de recherche. Les boutons ne servent qu'à la souris. */
  $$('[data-carousel]').forEach((carousel) => {
    const piste = $('[data-carousel-piste]', carousel);
    const prec  = $('[data-carousel-prec]', carousel);
    const suiv  = $('[data-carousel-suiv]', carousel);
    if (!piste) return;

    const pas = () => {
      const premiere = piste.firstElementChild;
      if (!premiere) return piste.clientWidth;
      const largeur = premiere.getBoundingClientRect().width;
      const espace  = parseFloat(getComputedStyle(piste).columnGap) || 0;
      /* Un écran plein, arrondi à un nombre entier de cartes : une carte
         coupée en fin de course donne une impression d'à-peu-près.
         On ne borne pas sur la distance restante — au départ elle vaut le
         parcours entier, et le premier clic sautait alors jusqu'au bout. */
      const parEcran = Math.max(1, Math.floor(piste.clientWidth / (largeur + espace)));
      return parEcran * (largeur + espace);
    };

    const majBoutons = () => {
      const max = piste.scrollWidth - piste.clientWidth - 1;
      if (prec) prec.disabled = piste.scrollLeft <= 0;
      if (suiv) suiv.disabled = piste.scrollLeft >= max;
    };

    const glisser = (sens) => {
      piste.scrollBy({ left: sens * pas(), behavior: reduit ? 'auto' : 'smooth' });
    };

    if (prec) prec.addEventListener('click', () => glisser(-1));
    if (suiv) suiv.addEventListener('click', () => glisser(1));
    piste.addEventListener('scroll', majBoutons, { passive: true });
    window.addEventListener('resize', majBoutons);
    majBoutons();
  });

  /* =====================================================================
     PANIER
     Il vit dans le navigateur de la visiteuse : rien n'est envoyé au serveur
     et il survit au changement de page. Le paiement en ligne n'est pas
     branché, la commande part en message à Fati.

     Trois endroits l'affichent — les cartes et la fiche produit, le tiroir de
     l'en-tête, la page Panier — et tous se redessinent depuis `renderBag`.
     ===================================================================== */
  const BAG_KEY = 'fati-bag-v1';

  /* On ne garde que ce qui sert à réafficher une ligne : copier le produit
     entier mettrait trop de balisage dans le stockage du navigateur, qui est
     limité et partagé avec le reste du site. */
  const ligneDepuis = (p, q) => ({
    id: p.id, nom: p.nom, prix: p.prix, catNom: p.catNom,
    vignette: p.vignette, url: p.url, q,
  });

  const loadBag = () => {
    try {
      const brut = localStorage.getItem(BAG_KEY);
      if (!brut) return new Map();
      const lu = JSON.parse(brut);
      if (!Array.isArray(lu)) return new Map();
      return new Map(lu
        .filter((it) => it && it.id != null && Number.isFinite(+it.q) && +it.q > 0)
        .map((it) => {
          /* Le nom et surtout le prix sont relus dans le catalogue du jour :
             un panier laissé la veille ne garde pas un ancien tarif. */
          const p = byId(it.id);
          const q = borne(Math.round(+it.q), 1, 99);
          return [String(it.id), p ? ligneDepuis(p, q) : { ...it, q }];
        }));
    } catch (e) {
      /* Navigation privée, stockage plein ou désactivé : on repart à vide. */
      return new Map();
    }
  };

  const saveBag = () => {
    try {
      localStorage.setItem(BAG_KEY, JSON.stringify([...bag.values()]));
    } catch (e) { /* le panier reste en mémoire pour cette page */ }
  };

  const bag       = loadBag();
  const qDe       = (id) => (bag.get(String(id)) || { q: 0 }).q;
  const bagCount  = $('#bag-count');
  const drawer    = $('#bag-drawer');
  const backdrop  = $('#drawer-backdrop');
  const list      = $('#drawer-list');
  const empty     = $('#drawer-empty');
  const foot      = $('#drawer-foot');
  const totalEl   = $('#drawer-total');
  const totalLbl  = $('#drawer-total-label');
  const totalLblDefaut = totalLbl ? totalLbl.textContent : '';
  const drawerWa  = $('#drawer-wa');
  const openBtn   = $('#open-bag');
  let lastFocused = null;
  /* Faux pendant le premier dessin : les cartes prennent leur état sans
     animation, comme si la page avait toujours été ainsi. */
  let pret = false;

  /* ---------- Fabrique des éléments de ligne ---------- */
  const TRAITS = { moins: 'M6 12h12', plus: 'M6 12h12M12 6v12', croix: 'M6 6l12 12M18 6 6 18' };
  const icone = (trait) => `<svg class="i" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="${trait}"/></svg>`;

  /* `vignette` est une balise <img> complète produite côté serveur : on la
     relit telle quelle plutôt que de reconstruire une URL. */
  const vignette = (it) => {
    if (!it.vignette) return null;
    const gabarit = document.createElement('template');
    gabarit.innerHTML = String(it.vignette).trim();
    const img = gabarit.content.querySelector('img');
    if (img) { img.alt = ''; img.removeAttribute('loading'); }
    return img;
  };

  const lienProduit = (it, classe) => {
    const el = document.createElement(it.url ? 'a' : 'span');
    if (it.url) el.href = it.url;
    el.className = classe;
    return el;
  };

  /* Sélecteur « − 2 + » relié au panier : même balisage que celui que PHP
     pose sur les cartes, donc mêmes styles et même écoute des clics. */
  const qtyEl = (it, classe) => {
    const box = document.createElement('div');
    box.className = `qty ${classe || ''}`.trim();
    box.setAttribute('role', 'group');
    box.setAttribute('aria-label', fmt(T.quantite, it.nom));
    const bouton = (pas, libelle, trait) => {
      const b = document.createElement('button');
      b.type = 'button'; b.className = 'qty-btn';
      b.dataset.step = pas; b.dataset.id = it.id;
      b.setAttribute('aria-label', fmt(libelle, it.nom));
      b.innerHTML = icone(trait);
      return b;
    };
    const val = document.createElement('output');
    val.className = 'qty-val';
    val.textContent = String(it.q);
    box.append(bouton('-1', T.moins, TRAITS.moins), val, bouton('1', T.plus, TRAITS.plus));
    return box;
  };

  const retirerEl = (it, classe) => {
    const b = document.createElement('button');
    b.type = 'button'; b.className = classe;
    b.dataset.remove = it.id;
    b.setAttribute('aria-label', fmt(T.remove, it.nom));
    b.innerHTML = icone(TRAITS.croix);
    return b;
  };

  /* Une liste redessinée perd le focus : on le rend au même bouton de la même
     ligne, sinon au premier contrôle restant. */
  const memoFocus = (racine) => {
    const el = document.activeElement;
    if (!el || !racine.contains(el)) return null;
    return { id: el.dataset.id || el.dataset.remove, step: el.dataset.step };
  };
  const rendreFocus = (racine, memo, repli) => {
    if (!memo) return;
    const cible = (memo.step && racine.querySelector(`[data-step="${memo.step}"][data-id="${memo.id}"]`))
      || racine.querySelector('button') || repli;
    if (cible) cible.focus();
  };

  /* ---------- Cartes et fiche produit ----------
     Un produit présent dans le panier montre « − n + » à la place du bouton
     « Ajouter » ; la fiche rappelle combien il y en a déjà. */
  const syncCartes = () => {
    $$('[data-qty-box]').forEach((box) => {
      const q   = qDe(box.dataset.qtyBox);
      const add = $('[data-add]', box.parentElement);
      const val = $('[data-qty]', box);
      if (val && q) val.textContent = String(q);

      const present = q > 0;
      if (box.hidden !== present) return;

      const actif = document.activeElement;
      const avaitFocus = box.contains(actif) || add === actif;
      box.hidden = !present;
      if (add) add.hidden = present;
      if (avaitFocus) {
        const cible = present ? $('[data-step="1"]', box) : add;
        if (cible) cible.focus();
      }
      if (present && pret) {
        box.classList.remove('is-pop');
        void box.offsetWidth;
        box.classList.add('is-pop');
      }
    });

    $$('[data-inbag]').forEach((el) => {
      const q = qDe(el.dataset.inbag);
      el.hidden = q === 0;
      const texte = $('[data-inbag-texte]', el);
      if (texte) texte.textContent = fmt(T.dansPanier, q);
    });
  };

  /* ---------- Tiroir ---------- */
  const ligneTiroir = (it) => {
    const li = document.createElement('li');
    li.className = 'drawer-item';

    const media = lienProduit(it, 'drawer-item-media');
    media.setAttribute('aria-hidden', 'true');
    if (it.url) media.tabIndex = -1;
    const img = vignette(it);
    if (img) media.append(img);

    const nom = lienProduit(it, 'drawer-item-nom');
    nom.textContent = it.nom;

    const unite = document.createElement('p');
    unite.className = 'drawer-item-unite';
    unite.textContent = it.prix == null ? (T.aConfirmer || '') : fmt(T.unite, eur(it.prix));

    const montant = document.createElement('p');
    montant.className = 'drawer-item-montant';
    montant.textContent = it.prix == null ? '' : eur(it.prix * it.q);

    li.append(media, nom, retirerEl(it, 'drawer-item-retirer'), unite, qtyEl(it, 'qty--sm'), montant);
    return li;
  };

  const messageCommande = (items, total, partiel) => {
    if (!items.length) return T.waHello;
    const lignes = items.map((it) => `• ${it.nom} × ${it.q} — ${montantLigne(it)}`).join('\n');
    /* `waTotal` porte déjà son deux-points (« Total indicatif : »). */
    const lblTotal = partiel ? `${T.totalPartiel} :` : T.waTotal;
    return `${T.waIntro}\n${lignes}\n\n${lblTotal} ${eur(total)}\n${T.waConfirm}`;
  };

  const renderBag = () => {
    saveBag();
    const items = [...bag.values()];
    const units = items.reduce((s, it) => s + it.q, 0);
    const total = items.reduce((s, it) => s + (it.prix || 0) * it.q, 0);
    const partiel = sansPrix(items);

    /* Le compteur de l'en-tête existe sur toutes les pages : il se met à jour
       avant toute sortie anticipée. À zéro, la pastille disparaît. */
    if (bagCount) {
      bagCount.textContent = String(units);
      bagCount.hidden = units === 0;
    }
    if (openBtn) {
      // Le nom accessible doit contenir le texte visible (le compteur) — WCAG 2.5.3
      openBtn.setAttribute('aria-label', fmt(units > 1 ? T.bags : T.bag, units));
    }

    syncCartes();

    /* La page Panier écoute cet événement pour se redessiner. */
    document.dispatchEvent(new CustomEvent('fati:bag', { detail: { items, units, total, partiel } }));

    if (!list) return;
    if (empty)    empty.hidden = items.length > 0;
    if (foot)     foot.hidden  = items.length === 0;
    if (totalEl)  totalEl.textContent  = eur(total);
    if (totalLbl) totalLbl.textContent = libelleTotal(partiel, totalLblDefaut);

    const memo = memoFocus(list);
    list.replaceChildren(...items.map(ligneTiroir));
    rendreFocus(list, memo, $('#close-bag'));

    if (drawerWa) drawerWa.href = wa(messageCommande(items, total, partiel));
  };

  /* Seule porte d'entrée pour changer une quantité : cartes, fiche, tiroir et
     page Panier passent tous par ici. */
  const setQty = (id, q) => {
    id = String(id);
    if (q <= 0) {
      bag.delete(id);
    } else {
      const p = byId(id);
      const base = bag.get(id) || (p ? ligneDepuis(p, 0) : null);
      if (!base) return false;
      bag.set(id, { ...base, q: borne(q, 1, 99) });
    }
    renderBag();
    return true;
  };

  const openDrawer = () => {
    if (!drawer) return;
    cacherToast();
    lastFocused = document.activeElement;
    drawer.hidden = false;
    if (backdrop) backdrop.hidden = false;
    document.body.style.overflow = 'hidden';
    $('#close-bag').focus();
  };

  const closeDrawer = () => {
    if (!drawer || drawer.hidden) return;
    drawer.hidden = true;
    if (backdrop) backdrop.hidden = true;
    document.body.style.overflow = '';
    const retour = lastFocused && document.contains(lastFocused) && lastFocused.offsetParent !== null
      ? lastFocused : openBtn;
    if (retour) retour.focus();
  };

  if ($('#close-bag')) $('#close-bag').addEventListener('click', closeDrawer);
  if (backdrop) backdrop.addEventListener('click', closeDrawer);

  if (drawer) {
    drawer.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') { closeDrawer(); return; }
      if (e.key !== 'Tab') return;
      const f = $$('a[href],button:not([disabled]),input,select,[tabindex]:not([tabindex="-1"])', drawer)
        .filter((el) => el.offsetParent !== null && el.tabIndex !== -1);
      if (!f.length) return;
      const first = f[0], last = f[f.length - 1];
      if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
      else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
    });
  }

  /* ---------- Confirmation d'ajout ----------
     Trois signes pour un ajout : le visuel file vers l'icône du panier, la
     pastille de l'en-tête rebondit avec le nouveau total, et une confirmation
     nomme le produit et donne l'accès au panier. */
  const toast       = $('#toast');
  const toastMedia  = $('#toast-media');
  const toastText   = $('#toast-text');
  const toastAction = $('#toast-action');
  let toastTimer;

  function cacherToast() {
    if (!toast || toast.hidden) return;
    clearTimeout(toastTimer);
    toast.classList.remove('is-visible');
    toastTimer = setTimeout(() => { toast.hidden = true; }, 300);
  }
  const programmerToast = () => {
    clearTimeout(toastTimer);
    toastTimer = setTimeout(cacherToast, 4200);
  };

  const annoncer = (p, n) => {
    if (!toast) return;
    clearTimeout(toastTimer);
    if (toastMedia) {
      const img = vignette(p);
      toastMedia.replaceChildren(...(img ? [img] : []));
    }
    if (toastText) toastText.textContent = fmt(T.ajoute, p.nom, n);
    /* Sur grand écran la confirmation se pose sous l'en-tête, près de l'icône
       du panier ; sa hauteur change selon que l'en-tête est réduit ou non. */
    if (header) toast.style.setProperty('--toast-top', `${Math.round(header.getBoundingClientRect().bottom + 12)}px`);
    toast.hidden = false;
    requestAnimationFrame(() => toast.classList.add('is-visible'));
    programmerToast();
  };

  if (toast) {
    /* La confirmation ne s'éclipse pas pendant qu'on la lit ou qu'on vise
       son bouton. */
    toast.addEventListener('mouseenter', () => clearTimeout(toastTimer));
    toast.addEventListener('mouseleave', programmerToast);
    toast.addEventListener('focusin', () => clearTimeout(toastTimer));
    toast.addEventListener('focusout', programmerToast);
  }
  if (toastAction) toastAction.addEventListener('click', openDrawer);

  const rebond = () => {
    if (!openBtn) return;
    openBtn.classList.remove('is-bump');
    void openBtn.offsetWidth;
    openBtn.classList.add('is-bump');
  };

  const voler = (img) => {
    if (reduit || !img || !openBtn || !img.animate) { rebond(); return; }
    const a = img.getBoundingClientRect();
    const b = openBtn.getBoundingClientRect();
    if (!a.width || !b.width || a.bottom < 0 || a.top > innerHeight) { rebond(); return; }

    const cote = Math.min(a.width, a.height, 132);
    const fantome = document.createElement('img');
    fantome.src = img.currentSrc || img.src;
    fantome.alt = '';
    fantome.className = 'fly';
    fantome.style.cssText = `left:${a.left + a.width / 2 - cote / 2}px;top:${a.top + a.height / 2 - cote / 2}px;width:${cote}px;height:${cote}px`;
    document.body.append(fantome);

    const dx = b.left + b.width / 2 - (a.left + a.width / 2);
    const dy = b.top + b.height / 2 - (a.top + a.height / 2);
    const vol = fantome.animate([
      { transform: 'translate(0,0) scale(1)', opacity: 1 },
      { transform: `translate(${dx * 0.45}px,${dy * 0.45 - 36}px) scale(.72)`, opacity: 1, offset: 0.45 },
      { transform: `translate(${dx}px,${dy}px) scale(.16)`, opacity: 0.25 },
    ], { duration: 640, easing: 'cubic-bezier(.4,0,.2,1)' });
    const fin = () => { fantome.remove(); rebond(); };
    vol.onfinish = fin;
    vol.oncancel = fin;
  };

  const ajouter = (id, n, bouton) => {
    const p = byId(id);
    if (!p || !setQty(id, qDe(id) + n)) return;

    const cadre = bouton && bouton.closest('.product-card, .produit-achat');
    voler(cadre ? $('.product-media img, .produit-media img', cadre) : null);

    /* Sur la fiche, le bouton confirme sur place : « Ajouté ». */
    if (bouton && bouton.classList.contains('add-button')) {
      bouton.classList.add('is-added');
      clearTimeout(bouton.fatiRetour);
      bouton.fatiRetour = setTimeout(() => bouton.classList.remove('is-added'), 1800);
    }
    annoncer(p, n);
  };

  /* ---------- Page Panier ----------
     Elle réutilise le même panier que le tiroir, avec la place d'ajuster
     confortablement : le récapitulatif se dessine à partir de ce que le
     navigateur a enregistré, et le bouton prépare un message complet. */
  const selItems = $('#selection-items');

  if (selItems) {
    const selVide     = $('#selection-vide');
    const selActions  = $('#selection-actions');
    const selArticles = $('#resume-articles');
    const selSousTot  = $('#resume-soustotal');
    const selSousLbl  = $('#resume-soustotal-label');
    const selSousLblDefaut = selSousLbl ? selSousLbl.textContent : '';
    const selWa       = $('#selection-wa');
    const selVider    = $('#selection-vider');

    const ligne = (it) => {
      const li = document.createElement('li');
      li.className = 'selection-item';

      const media = lienProduit(it, 'selection-item-media');
      media.setAttribute('aria-hidden', 'true');
      if (it.url) media.tabIndex = -1;
      const img = vignette(it);
      if (img) media.append(img);

      const nom = document.createElement('div');
      nom.className = 'selection-item-nom';
      const titre = lienProduit(it, 'selection-item-titre');
      titre.textContent = it.nom;
      nom.append(titre);
      if (it.catNom) {
        const cat = document.createElement('span');
        cat.textContent = it.catNom;
        nom.append(cat);
      }

      const prix = document.createElement('p');
      prix.className = 'selection-item-prix';
      prix.textContent = it.prix == null ? (T.aConfirmer || 'prix à confirmer') : eur(it.prix);

      const soustotal = document.createElement('p');
      soustotal.className = 'selection-item-soustotal';
      soustotal.textContent = montantLigne(it);

      li.append(retirerEl(it, 'selection-item-retirer'), media, nom, prix, qtyEl(it, 'selection-item-qte'), soustotal);
      return li;
    };

    const dessiner = ({ items, units, total, partiel }) => {
      const memo = memoFocus(selItems);
      selItems.replaceChildren(...items.map(ligne));
      rendreFocus(selItems, memo, $('a', selVide));

      if (selVide)    selVide.hidden    = items.length > 0;
      if (selActions) selActions.hidden = items.length === 0;
      if (selArticles) selArticles.textContent = String(units);
      if (selSousTot)  selSousTot.textContent  = items.length ? eur(total) : '—';
      if (selSousLbl)  selSousLbl.textContent  = libelleTotal(partiel, selSousLblDefaut);
      if (selWa) selWa.href = wa(messageCommande(items, total, partiel));
    };

    document.addEventListener('fati:bag', (e) => dessiner(e.detail));

    if (selVider) {
      selVider.addEventListener('click', () => {
        bag.clear();
        renderBag();
      });
    }
  }

  renderBag();
  pret = true;

  /* ---------- Quantité choisie avant l'ajout (fiche produit) ---------- */
  const lirePick = (input) => borne(parseInt(input.value, 10) || 1, 1, 99);
  document.addEventListener('change', (e) => {
    if (e.target.matches('[data-pick]')) e.target.value = String(lirePick(e.target));
  });

  /* ---------- Un seul écouteur pour tous les clics du panier ---------- */
  document.addEventListener('click', (e) => {
    const pick = e.target.closest('[data-pick-step]');
    if (pick) {
      const input = $('[data-pick]', pick.closest('.qty'));
      if (input) input.value = String(borne(lirePick(input) + Number(pick.dataset.pickStep), 1, 99));
      return;
    }

    const pas = e.target.closest('[data-step][data-id]');
    if (pas) {
      const sens = Number(pas.dataset.step);
      setQty(pas.dataset.id, qDe(pas.dataset.id) + sens);
      if (sens > 0) rebond();
      return;
    }

    const retrait = e.target.closest('[data-remove]');
    if (retrait) { setQty(retrait.dataset.remove, 0); return; }

    const add = e.target.closest('[data-add]');
    if (add) {
      const input = add.dataset.addPick ? document.getElementById(add.dataset.addPick) : null;
      ajouter(add.dataset.add, input ? lirePick(input) : 1, add);
      if (input) input.value = '1';
      return;
    }

    /* L'icône de l'en-tête est un vrai lien vers la page Panier. Ici on
       l'intercepte pour ouvrir le tiroir — sauf sur la page Panier, où il
       doublerait la page, et sauf si la visiteuse demande un nouvel onglet. */
    const ouvrir = e.target.closest('#open-bag, [data-open-bag]');
    if (ouvrir && drawer && !CFG.surPanier && !(e.metaKey || e.ctrlKey || e.shiftKey || e.altKey)) {
      e.preventDefault();
      openDrawer();
    }
  });
})();
