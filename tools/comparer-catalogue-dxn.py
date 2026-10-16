#!/usr/bin/env python3
"""
Compare le catalogue du site (tools/catalogue.csv, via les codes DXN de
tools/rediger-catalogue.py) au relevé du catalogue DXN Espagne
(tools/catalogue-dxn-2026-02.csv) et écrit le rapport en Markdown.

    python3 tools/comparer-catalogue-dxn.py [--avant tools/catalogue-avant.csv]

Le prix du site doit être le RP (precio de venta recomendado) du catalogue.
Avec --avant, le rapport montre aussi l'ancien prix du site.
"""
import argparse, csv, importlib.util, pathlib, sys

RACINE = pathlib.Path(__file__).resolve().parent
spec = importlib.util.spec_from_file_location("rediger", RACINE / "rediger-catalogue.py")
rediger = importlib.util.module_from_spec(spec); spec.loader.exec_module(rediger)

parseur = argparse.ArgumentParser()
parseur.add_argument("--avant", help="ancien catalogue.csv, pour afficher l'ancien prix")
parseur.add_argument("--sortie", default=str(RACINE.parent / "_velix" / "comparatif-catalogue-dxn-2026-02.md"))
opt = parseur.parse_args()

dxn = {r["code"]: r for r in csv.DictReader(open(RACINE / "catalogue-dxn-2026-02.csv", encoding="utf-8"))}
site = list(csv.DictReader(open(RACINE / "catalogue.csv", encoding="utf-8")))
avant = {r["slug"]: r for r in csv.DictReader(open(opt.avant, encoding="utf-8"))} if opt.avant else {}

def eur(v):
    return f"{float(v):.2f} €".replace(".", ",") if v else "—"

lignes, ecarts, hors_catalogue, codes_vus = [], [], [], set()
for p in site:
    code = rediger.CODES.get(p["slug"], "")
    if not code:
        hors_catalogue.append(p); continue
    r = dxn[code]; codes_vus.add(code)
    ok = abs(float(p["prix"]) - float(r["rp"])) < 0.005
    if not ok: ecarts.append(p["slug"])
    a = avant.get(p["slug"], {}).get("prix", "")
    lignes.append((p["categorie"], p["nom"], code, r["nom_catalogue"], r["format"], eur(a) if avant else None, eur(r["rp"]), eur(p["prix"]), "✔" if ok else "✘"))

manquants = [r for c, r in dxn.items() if c not in codes_vus and not r["exclu"]]
exclus = [r for r in dxn.values() if r["exclu"]]

NOTES = """
## Décisions prises le 16 octobre 2026 et points à confirmer avec Fati

- **Cordyceps Coffee** : le site décrivait une version « 1 in 1 » ; le catalogue européen ne vend que le **3 in 1** (café, crème végétale, sucre de canne). Fiche réécrite et renommée « DXN Cordyceps Coffee 3 in 1 » ; le visuel (découpé dans l'affiche du gérant) est conservé, à vérifier qu'il montre bien le 3 in 1.
- **Huile de coco** : le site vendait une « Virgin Coconut Oil with Ganoderma » (285 ml, hors catalogue EU). Le catalogue ne connaît que l'**Organic Virgin Coconut Oil (Bio) 500 ml, sans Ganoderma**, à 25,10 €. Fiche réécrite ; l'ancien visuel (pot au Ganoderma) est retiré, car il ne correspond plus — visuel d'attente en attendant une photo du vrai pot.
- **Reishilium / Reishi Mushroom Powder** à 87,50 € sans contenance : tranché par le catalogue, qui a deux formats. Le produit existant devient le **22 g à 32,90 €** (son visuel affiche 22 g) ; un **70 g à 101,10 €** est ajouté, sans visuel.
- **Lion's Mane Coffee** : renommé « DXN Lion's Mane Coffee » (nom du catalogue), au lieu de « Premix Coffee ».
- **Ganozhi Soap à 23,70 €** : c'est bien le RP du catalogue (DP 18,50 €, PV 14), appliqué comme demandé. Les revendeurs vendent ce savon de 80 g autour de 7,60 € : le catalogue tarife peut-être un lot. **À confirmer avec Fati avant d'encaisser.**
- **Coffret Kallow Divine Touch à 200 €** : composition propre à Fatichanelya, hors catalogue, inchangé.
- **SC103** : le PDF lui donne le même titre que SC102 (« Lifting Impact Face Serum », 10 × 2 ml) à un prix différent (22,70 € contre 34,80 €) et le décrit comme une crème visage. Publié comme **monodoses de la Lifting Impact Face Cream** — hypothèse à confirmer.
- **Cocozhi** : le PDF dit « bebida de coco » ; le produit DXN est une boisson **cacaotée**. La fiche dit cacao.
- **Zhi Cafe Classic** : le PDF dit « café en grano » mais vend 20 sachets de 20 g ; la fiche parle de sachets, sans trancher sur la mouture.
- **Zhi Mint Plus** : le PDF recopie la description du Zhi Ca Plus (« caramelos sabor café ») ; la fiche dit pastilles à la menthe, d'après le nom.
- Les descriptions du PDF pour **PC004 (shampoing), PC005 (mousse) et PC007 (huile de massage)** sont des copier-coller erronés ; les fiches du site s'appuient sur les noms et contenances.
- **Monodoses et échantillons** (SC101 à SC106) : publiés car ils ont un RP au catalogue. À retirer en une ligne chacun si Fati ne les vend pas au détail.
- **Visuels** : aucun des 75 ajouts n'a de visuel (solde Higgsfield 2 crédits, images du PDF trop petites : 110 × 135 px). Le thème affiche son visuel d'attente et la mention « Visuel à venir ».
"""

out = []
out.append("# Comparatif site ↔ catalogue DXN Espagne (février 2026)\n")
out.append("Généré par `tools/comparer-catalogue-dxn.py`. Source : `tools/catalogue-dxn-2026-02.csv` (relevé du PDF "
           "`tools/medias/sources/catalogue-dxn-2026-02.pdf`). Règle : **le prix affiché sur le site est le RP** "
           "(precio de venta recomendado) du catalogue.\n")
out.append(f"- Produits sur le site : **{len(site)}**, dont {len(lignes)} rattachés à une référence DXN et {len(hors_catalogue)} hors catalogue.")
out.append(f"- Références vendables du catalogue : **{sum(1 for r in dxn.values() if not r['exclu'])}** ; absentes du site : **{len(manquants)}** ; exclues volontairement : {len(exclus)}.")
out.append(f"- Écarts de prix restants : **{len(ecarts)}**" + (f" ({', '.join(ecarts)})" if ecarts else "") + ".\n")

entete = "| Catégorie | Produit (site) | Réf. | Nom catalogue | Format | " + ("Ancien prix | " if avant else "") + "RP catalogue | Prix site | |"
sep = "|---|---|---|---|---|" + ("---|" if avant else "") + "---|---|---|"
out.append("## Prix, produit par produit\n"); out.append(entete); out.append(sep)
for l in lignes:
    cells = [c for c in l if c is not None]
    out.append("| " + " | ".join(cells) + " |")
if hors_catalogue:
    out.append("\n## Sur le site, hors catalogue DXN\n")
    for p in hors_catalogue:
        out.append(f"- **{p['nom']}** — {eur(p['prix'])} : composition propre à Fatichanelya, prix inchangé.")
if manquants:
    out.append("\n## Au catalogue, absents du site\n")
    for r in manquants:
        out.append(f"- {r['code']} {r['nom_catalogue']} ({r['format']}) — RP {eur(r['rp'])}")
out.append(NOTES)
out.append("\n## Exclus volontairement (dernière page, hors des quatre catégories)\n")
for r in exclus:
    out.append(f"- {r['code']} {r['nom_catalogue']} — {r['exclu']}")
pathlib.Path(opt.sortie).write_text("\n".join(out) + "\n", encoding="utf-8")
print(f"{len(lignes)} rattachés, {len(ecarts)} écarts, {len(manquants)} manquants → {opt.sortie}")
sys.exit(1 if ecarts or manquants else 0)
