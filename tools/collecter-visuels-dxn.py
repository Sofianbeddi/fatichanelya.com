#!/usr/bin/env python3
"""
Collecte les visuels officiels de la banque produits DXN (dxn2u.com) pour les
références du site qui n'ont pas de visuel, et produit une planche de contrôle.

    python3 tools/collecter-visuels-dxn.py            # télécharge et écrit la planche
    python3 tools/collecter-visuels-dxn.py --hors-ligne  # réutilise les pages déjà téléchargées

Ce que fait le script, et ce qu'il ne fait pas :
- il lit les quatre catégories de dxn2u.com (FFB, HDS, PCC, OTH), dont chaque
  fiche est un bloc « product-modal » : titre, vignettes (face, côté,
  variantes) et texte d'emballage ;
- il associe chaque produit du site sans visuel à une fiche DXN par une table
  de correspondance explicite (CORRESPONDANCES) — pas de recherche floue, les
  familles (RG 30/90/360, mégapacks…) partagent une fiche ;
- il télécharge toutes les vignettes de la fiche dans
  tools/medias/sources/dxn2u/<slug>/ avec un index.csv de provenance ;
- il écrit tools/medias/sources/dxn2u/planche.html pour choisir, à l'œil, la
  bonne vignette par produit.
Il ne touche ni au catalogue, ni au site : ces images portent le filigrane
« DXN's Property » et montrent l'emballage malaisien. Elles servent de
référence tant que Fati n'a pas fourni les visuels officiels de son espace
distributrice.
"""
import argparse, csv, html, json, pathlib, re, subprocess, sys

RACINE = pathlib.Path(__file__).resolve().parent.parent
DEST = RACINE / "tools" / "medias" / "sources" / "dxn2u"
PAGES = DEST / "pages"
CATEGORIES = ("FFB", "HDS", "PCC", "OTH")
UA = "Mozilla/5.0 (Macintosh) VelixCollecteur/1.0"

# slug du site → titre exact de la fiche dxn2u (famille = plusieurs formats sur une fiche)
CORRESPONDANCES = {
 "white-coffee-zhino": "DXN White Coffee Zhino", "cream-coffee": "DXN Cream Coffee",
 "zhi-mocha": "DXN Zhi Mocha", "zhi-mocha-mp": "DXN Zhi Mocha", "zhi-cafe-classic": "DXN Zhi Cafe Classic",
 "cocozhi": "DXN Cocozhi", "cocozhi-mp": "DXN Cocozhi",
 "lingzhi-black-coffee-mp": "DXN Lingzhi Black Coffee", "lingzhi-coffee-3-en-1-mp": "DXN Lingzhi Coffee 3 in 1",
 "nutrizhi": "DXN NutriZhi", "spirulina-cereal": "DXN Spirulina Cereal",
 "reishi-gano-tea": "DXN Reishi Gano Tea", "spica-tea": "DXN Spica Tea", "zhi-tea": "DXN Zhitea",
 "sprudle": "DXN Spirudle", "sprudle-tom-yam": "DXN Spirudle", "ococha-noodle": "DXN Oocha Noodle",
 "apple-fermented-jam": "DXN Apple Fermented Jam", "apple-enzyme-drink": "DXN Apple Enzyme Drink",
 "lemonzhi": "DXN Lemonzhi", "morinzhi-700": "DXN Morinzhi", "vinaigrette": "DXN Vinaigrette",
 "himalayan-salt": "DXN Himalayan Salt", "cordypine": "DXN Cordypine",
 "zhi-mint-plus": "DXN Zhi Mint and DXN Zhi Mint Plus", "zhi-ca-plus": "DXN Zhi Ca Plus",
 "goji-berries": "DXN Goji Berries / Dried Goji Berries",
 "rg-30": "Reishi Gano (RG)", "rg-360": "Reishi Gano (RG)",
 "gl-90": "Ganocelium (GL)", "gl-30": "Ganocelium (GL)", "gl-360": "Ganocelium (GL)",
 "rg-powder": "RG Powder & GL Powder", "gl-powder": "RG Powder & GL Powder",
 "reishi-powder-70": "DXN Reishilium Powder", "cordyceps-powder": "DXN Cordyceps", "spirulina-powder": "DXN Spirulina",
 "black-cumin-plus-90": "DXN Black Cumin Plus", "black-cumin-plus-30": "DXN Black Cumin Plus",
 "andro-g-90": "DXN Andro-G", "andro-g-30": "DXN Andro-G",
 "agaricus-90": "DXN Agaricus Blazei Murill", "agaricus-30": "DXN Agaricus Blazei Murill",
 "mengkudu-90": "DXN Mengkudu 350mg Capsule", "mengkudu-30": "DXN Mengkudu 350mg Capsule",
 "roselle-tablets": "DXN Roselle Tablet",
 "toiletries-travel-kit": "DXN Toiletries Travel Kit", "chubby-baby-oil": "DXN Chubby Baby Oil",
 "ganozhi-toothpaste": "DXN Ganozhi Toothpaste", "ganozhi-toothpaste-4x40": "DXN Ganozhi Toothpaste",
 "zhicare-toothpaste": "DXN Zhicare Toothpaste",
 # Candidat à vérifier : la fiche malaisienne VCO-L n'est pas forcément l'huile bio 500 ml du catalogue EU.
 "virgin-coconut-oil": "DXN VCO-L",
}
# Produits du catalogue EU sans équivalent sur dxn2u (gamme Kallow fabriquée en Grèce, références Europe).
ABSENTS = {"maca-vita", "maca-cafe", "brook-coffee"}


def curl(url, dest=None):
    args = ["curl", "-sL", "-A", UA, "--max-time", "60", url]
    if dest:
        args += ["-o", str(dest)]
    r = subprocess.run(args, stdout=subprocess.PIPE)
    return r.stdout.decode("utf-8", "ignore") if not dest else r.returncode == 0


def normaliser_titre(t):
    return re.sub(r"\s+", " ", html.unescape(t)).strip()


def lire_fiches(hors_ligne):
    PAGES.mkdir(parents=True, exist_ok=True)
    fiches = {}
    for cat in CATEGORIES:
        page = PAGES / f"{cat}.html"
        if not (hors_ligne and page.is_file()):
            page.write_text(curl(f"https://www.dxn2u.com/product/products.php?cat_id={cat}&lang=en"), encoding="utf-8")
        h = re.sub(r"\s+", " ", page.read_text(encoding="utf-8"))
        for m in re.finditer(r'<div id="(prd\d+)" class="product-modal">(.*?)<div class="product-modal-footer">', h):
            pid, bloc = m.group(1), m.group(2)
            titre = normaliser_titre(re.search(r'class="product-modal-title">(.*?)</h3>', bloc).group(1))
            # Les vignettes des « produits associés » suivent le texte : on ne garde que celles de la fiche.
            images = []
            for src in re.findall(r'src="(https://www\.dxn2u\.com/product/images/[^"]+)"', bloc.split("Other Related Products")[0]):
                if src not in images and "noimg" not in src:
                    images.append(src)
            # Le texte d'emballage s'arrête avant les produits associés.
            texte = bloc.split("Other Related Products")[0]
            texte = re.sub(r"\s+", " ", html.unescape(re.sub(r"<[^>]+>", " ", texte))).strip()
            texte = re.sub(r"^.*?×\s*", "", texte)  # retire le titre et la croix de fermeture
            fiches[titre] = dict(id=pid, cat=cat, titre=titre, images=images, texte=texte)
    return fiches


def produits_sans_visuel():
    cat = list(csv.DictReader(open(RACINE / "tools" / "catalogue.csv", encoding="utf-8")))
    dxn = {r["code"]: r for r in csv.DictReader(open(RACINE / "tools" / "catalogue-dxn-2026-02.csv", encoding="utf-8"))}
    import importlib.util
    spec = importlib.util.spec_from_file_location("rediger", RACINE / "tools" / "rediger-catalogue.py")
    rediger = importlib.util.module_from_spec(spec); spec.loader.exec_module(rediger)
    out = []
    for p in cat:
        if p["image"]:
            continue
        code = rediger.CODES.get(p["slug"], "")
        out.append(dict(slug=p["slug"], nom=p["nom"], format=p["format"], categorie=p["categorie"], code=code,
                        nom_catalogue=dxn[code]["nom_catalogue"] if code else "", format_catalogue=dxn[code]["format"] if code else ""))
    return out


def main():
    ap = argparse.ArgumentParser(description=__doc__, formatter_class=argparse.RawDescriptionHelpFormatter)
    ap.add_argument("--hors-ligne", action="store_true", help="ne retélécharge ni les pages ni les images déjà présentes")
    opt = ap.parse_args()

    fiches = lire_fiches(opt.hors_ligne)
    print(f"{len(fiches)} fiches DXN lues")
    produits = produits_sans_visuel()
    index, planche = [], []
    for p in produits:
        slug = p["slug"]
        titre = CORRESPONDANCES.get(slug)
        if slug in ABSENTS or titre is None:
            statut = "absent de dxn2u" if slug in ABSENTS or slug.startswith(("kallow", "divine", "summer", "pink")) else "non associé"
            index.append(dict(slug=slug, code=p["code"], statut=statut, dxn2u_id="", dxn2u_titre="", url="", fichier=""))
            planche.append((p, None, [], statut)); continue
        if titre not in fiches:
            sys.exit(f"Fiche introuvable sur dxn2u : {titre!r} (pour {slug})")
        f = fiches[titre]
        partage = sum(1 for s, t in CORRESPONDANCES.items() if t == titre) > 1
        statut = "famille (plusieurs formats sur la fiche)" if partage else "fiche dédiée"
        if slug == "virgin-coconut-oil":
            statut = "à vérifier : VCO-L malaisien ≠ huile bio 500 ml EU ?"
        dossier = DEST / slug; dossier.mkdir(parents=True, exist_ok=True)
        locaux = []
        for i, url in enumerate(f["images"], 1):
            ext = pathlib.Path(url.split("?")[0]).suffix.lower() or ".jpg"
            fichier = dossier / f"{i:02d}{ext}"
            if not (opt.hors_ligne and fichier.is_file()):
                if not curl(url, fichier) or fichier.stat().st_size < 2000:
                    fichier.unlink(missing_ok=True); continue
            locaux.append((url, fichier))
            index.append(dict(slug=slug, code=p["code"], statut=statut, dxn2u_id=f["id"], dxn2u_titre=titre, url=url, fichier=str(fichier.relative_to(RACINE))))
        planche.append((p, f, locaux, statut))
        print(f"   {slug:34s} ← {titre} ({len(locaux)} images, {statut})")

    with open(DEST / "index.csv", "w", encoding="utf-8", newline="") as fh:
        w = csv.DictWriter(fh, fieldnames=["slug", "code", "statut", "dxn2u_id", "dxn2u_titre", "url", "fichier"])
        w.writeheader(); w.writerows(index)

    # Planche de contrôle
    h = ["<!doctype html><html lang='fr'><meta charset='utf-8'><title>Visuels DXN — planche de contrôle</title>",
         "<style>body{font:14px/1.45 -apple-system,Helvetica,sans-serif;margin:24px;color:#16233D;background:#F7F1E8}h1{font-size:22px}"
         ".p{display:grid;grid-template-columns:280px 1fr;gap:16px;background:#fff;border:1px solid #e3dccf;padding:14px;margin:0 0 14px}"
         ".p h2{font-size:16px;margin:0 0 4px}.m{color:#6b6257;font-size:12px}.s{display:inline-block;padding:2px 8px;border-radius:12px;font-size:12px;background:#EFE6D8}"
         ".s.ok{background:#DCEFD9}.s.warn{background:#FBE7C6}.s.ko{background:#F6D5D0}.imgs{display:flex;flex-wrap:wrap;gap:8px}.imgs figure{margin:0;width:150px;text-align:center}"
         ".imgs img{width:150px;height:196px;object-fit:contain;background:#fff;border:1px solid #eee}.imgs figcaption{font-size:11px;color:#6b6257;word-break:break-all}.t{font-size:12px;color:#3f3a33;margin-top:6px}</style>",
         f"<h1>Visuels DXN officiels (dxn2u.com) — {len(produits)} produits du site sans visuel</h1>",
         "<p class='m'>Images téléchargées depuis la banque produits DXN Malaisie, filigrane « DXN's Property » inclus, emballage malaisien. "
         "Planche de choix : pour chaque produit, noter le numéro de la vignette à retenir. Rien n'est publié sur le site par ce script.</p>"]
    for p, f, locaux, statut in planche:
        cls = "ok" if statut == "fiche dédiée" else ("warn" if locaux else "ko")
        h.append(f"<section class='p' id='{p['slug']}'><div><h2>{html.escape(p['nom'])}</h2><div class='m'>{html.escape(p['code'])} · {html.escape(p['format_catalogue'])} · {html.escape(p['categorie'])}</div>"
                 f"<div style='margin-top:8px'><span class='s {cls}'>{html.escape(statut)}</span></div>"
                 + (f"<div class='t'><b>Fiche DXN :</b> {html.escape(f['titre'])} ({f['id']})<br>{html.escape(f['texte'][:420])}</div>" if f else "") + "</div><div class='imgs'>")
        for url, fichier in locaux:
            rel = fichier.relative_to(DEST).as_posix()
            h.append(f"<figure><a href='{rel}' target='_blank'><img src='{rel}' loading='lazy' alt=''></a><figcaption>{fichier.parent.name}/{fichier.name}</figcaption></figure>")
        if not locaux:
            h.append("<p class='m'>Aucune image : à demander à Fati (visuels officiels de l'espace distributrice).</p>")
        h.append("</div></section>")
    (DEST / "planche.html").write_text("\n".join(h), encoding="utf-8")
    n_ok = sum(1 for _, f, l, _ in planche if l)
    print(f"{n_ok}/{len(produits)} produits avec au moins une image → {DEST / 'planche.html'}")


if __name__ == "__main__":
    main()
