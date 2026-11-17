#!/usr/bin/env python3
"""
Contrôle bloquant d'une langue traduite, avant tout import (thème 1.9.0).

    WP="php -d mysqli.default_socket=… /usr/local/bin/wp" python3 tools/traductions/verifier.py es
    python3 tools/traductions/verifier.py en --complet     # exige 103 produits, 6 formations, 4 pages légales

Vérifie, contre la source française :
  - clés : chaque entrée correspond à un contenu français existant ; avec --complet, rien ne manque ;
  - chiffres : mêmes nombres (décimales comparées sans leur séparateur), même nombre de lignes dans
    les modes d'emploi, points clés, réglages à lignes, mêmes barres « | » et astérisques ;
  - slugs : présents, ASCII minuscules, uniques entre les langues et jamais égaux à un slug français ;
  - mots interdits (allégations santé, voir GLOSSAIRE.md), hors phrases de négation listées ;
  - restes de français ;
  - pages légales : même suite de balises HTML que la source.
Sort en erreur (code 1) au premier problème de chaque fichier, après les avoir tous listés.
"""
import csv, json, os, re, shlex, subprocess, sys, pathlib

RACINE = pathlib.Path(__file__).resolve().parents[2]
TR = RACINE / 'tools/traductions'

INTERDITS = {
    'es': [r'\bcur(a|ar|an|e|en)\b', r'\bsan(a|ar|an)\b', r'\bprevien', r'\bprevenir', r'inmun', r'\bdefensas\b',
           r'depurat', r'\bdepurar', r'detox', r'desintoxic', r'adelgaz', r'quemagrasa', r'quemar grasa',
           r'pérdida de peso', r'diabetes', r'colesterol', r'tensión arterial', r'presión arterial', r'antiedad',
           r'antienvejecimiento', r'antioxidante', r'antiinflamatori', r'\brefuerza', r'\bfortalece', r'\bestimula',
           r'\bpotencia\b', r'milagro', r'sin efectos secundarios', r'beneficios para la salud', r'bueno para la salud',
           r'tratamiento'],
    'en': [r'\bcures?\b', r'\bheal(s|ed|ing)?\b', r'\bprevent', r'immun', r'\bdefences?\b', r'detox', r'\bcleanse', r'\bpurif',
           r'slimming', r'weight loss', r'fat-burning', r'diabetes', r'cholesterol', r'blood pressure', r'blood sugar',
           r'anti-?ag(e)?ing', r'antioxidant', r'anti-inflammatory', r'\bboost', r'\bstrengthen', r'\bstimulat',
           r'miracle', r'no side effects', r'health benefits', r'good for your health', r'treatment'],
}
# Phrases de négation reprises du français, seules exceptions admises.
NEGATIONS = {
    'es': ['los complementos alimenticios no curan', '¿sustituyen los complementos a un tratamiento?'],
    'en': ['food supplements do not cure anything', 'can supplements replace a treatment?'],
}
FRANCAIS = {
    'es': [r'\bavec\b', r'\bpour\b', r'\bdans\b', r'\bsans\b', r'\bdes\b', r'\best\b', r'\bet\b', r'\bvous\b', r'\bnous\b', r'\bune\b'],
    'en': [r'\bavec\b', r'\bpour\b', r'\bdans\b', r'\bsans\b', r'\bdes\b', r'\best\b', r'\bet\b', r'\bvous\b', r'\bles\b', r'\bune\b', r'\bde la\b'],
}

erreurs = []


def err(f, msg):
    erreurs.append(f'{f} : {msg}')


def nombres(s):
    return sorted(re.sub(r'(?<=\d)[.,](?=\d)', '', n) for n in re.findall(r'\d+(?:[.,]\d+)?', s or ''))


def lignes(s):
    return len([l for l in (s or '').split('\n')])


def comparer(f, cle, fr, tr, langue, sans_contenu=False):
    """Contrôles communs d'un texte traduit face à son original."""
    if (fr or '').strip() and not (tr or '').strip():
        err(f, f'{cle} : vide alors que le français ne l\'est pas')
        return
    if nombres(fr) != nombres(tr):
        err(f, f'{cle} : chiffres différents {nombres(fr)} ≠ {nombres(tr)}')
    if lignes(fr) != lignes(tr):
        err(f, f'{cle} : {lignes(fr)} ligne(s) en français, {lignes(tr)} traduites')
    if (fr or '').count('|') != (tr or '').count('|'):
        err(f, f'{cle} : barres « | » différentes')
    if (fr or '').count('*') != (tr or '').count('*'):
        err(f, f'{cle} : astérisques différents')
    bas = (tr or '').lower()
    for neg in NEGATIONS[langue]:
        bas = bas.replace(neg, '')
    for motif in INTERDITS[langue]:
        if re.search(motif, bas):
            err(f, f'{cle} : mot interdit /{motif}/')
    for motif in FRANCAIS[langue]:
        if re.search(motif, (tr or '').lower()):
            err(f, f'{cle} : reste de français /{motif}/')


def slug_ok(f, cle, slug, vus, slugs_fr):
    if not slug:
        err(f, f'{cle} : slug manquant')
        return
    if not re.fullmatch(r'[a-z0-9]+(-[a-z0-9]+)*', slug):
        err(f, f'{cle} : slug « {slug} » pas en ASCII minuscule')
    if slug in slugs_fr:
        err(f, f'{cle} : slug « {slug} » identique à un slug français')
    if slug in vus:
        err(f, f'{cle} : slug « {slug} » déjà utilisé par {vus[slug]}')
    vus[slug] = f'{f}:{cle}'


def reglages_fr():
    wp = shlex.split(os.environ.get('WP', 'wp'))
    code = ('$o=array();foreach(json_decode(file_get_contents("tools/traductions/ar/reglages.json"),true) as $k=>$v)'
            '{$o[$k]= $k==="blogdescription" ? get_option("blogdescription") : fati_opt_raw($k);}echo json_encode($o);')
    r = subprocess.run([*wp, 'eval', code], cwd=str(RACINE), capture_output=True, text=True)
    try:
        return json.loads(r.stdout)
    except ValueError:
        print('⚠ réglages français illisibles par WP-CLI (variable WP ?) : réglages non contrôlés')
        return None


def tags(html):
    return re.findall(r'</?([a-z0-9]+)', html)


def main():
    if len(sys.argv) < 2 or sys.argv[1] not in ('es', 'en'):
        sys.exit('Usage : verifier.py <es|en> [--complet]')
    langue, complet = sys.argv[1], '--complet' in sys.argv
    d = TR / langue
    autre = TR / ('en' if langue == 'es' else 'es')

    produits_fr = {r['slug']: r for r in csv.DictReader(open(RACINE / 'tools/catalogue.csv', encoding='utf-8'))}
    formations_fr = {r['slug']: r for r in csv.DictReader(open(RACINE / 'tools/formations.csv', encoding='utf-8'))}
    slugs_fr = set(produits_fr) | set(formations_fr) | {'accueil', 'a-propos', 'contact', 'panier', 'ma-selection',
        'mentions-legales', 'confidentialite', 'conditions-generales-de-vente', 'livraison-retours',
        'alimentation-boissons', 'beaute-soin', 'complements', 'soin-personnel'}
    vus = {}
    # slugs de l'autre langue latine : ils ne doivent pas être repris
    for fichier in sorted(autre.glob('*.json')):
        for cle, v in json.load(open(fichier, encoding='utf-8')).items():
            if isinstance(v, dict) and v.get('slug'):
                vus[v['slug']] = f'{autre.name}/{fichier.name}:{cle}'

    # Produits
    produits = {}
    for lot in sorted(d.glob('produits-*.json')):
        for cle, p in json.load(open(lot, encoding='utf-8')).items():
            f = f'{langue}/{lot.name}'
            if cle in produits:
                err(f, f'{cle} : en double')
            produits[cle] = p
            if cle not in produits_fr:
                err(f, f'{cle} : aucun produit français de ce slug')
                continue
            src = produits_fr[cle]
            slug_ok(f, cle, p.get('slug'), vus, slugs_fr)
            for champ in ('nom', 'accroche', 'description', 'format', 'composition', 'usage', 'points'):
                comparer(f, f'{cle}.{champ}', src[champ], p.get(champ, ''), langue)
    if complet and set(produits) != set(produits_fr):
        err(langue, f'produits manquants : {sorted(set(produits_fr) - set(produits))}')

    # Formations
    fichier = d / 'formations.json'
    if fichier.exists():
        for cle, x in json.load(open(fichier, encoding='utf-8')).items():
            f = f'{langue}/formations.json'
            if cle not in formations_fr:
                err(f, f'{cle} : aucune formation française de ce slug')
                continue
            slug_ok(f, cle, x.get('slug'), vus, slugs_fr)
            comparer(f, f'{cle}.nom', formations_fr[cle]['nom'], x.get('nom'), langue)
            comparer(f, f'{cle}.accroche', formations_fr[cle]['accroche'], x.get('accroche'), langue)
            comparer(f, f'{cle}.duree', formations_fr[cle]['duree'], x.get('duree'), langue)
            md_fr = RACINE / f'tools/formations/{cle}.md'
            md_tr = d / f'formations/{cle}.md'
            if not md_tr.exists():
                err(f, f'{cle} : programme {md_tr.name} absent')
            else:
                a, b = md_fr.read_text(encoding='utf-8'), md_tr.read_text(encoding='utf-8')
                comparer(f'{langue}/formations/{cle}.md', 'programme', a, b, langue)
                genre = lambda t: ['h' if l.startswith('## ') else 'li' if l.startswith('- ') else '' if not l.strip() else 'p'
                                   for l in t.split('\n')]
                if genre(a) != genre(b):
                    err(f'{langue}/formations/{cle}.md', 'structure (titres, puces, lignes vides) différente')
        if complet and len(json.load(open(fichier))) != len(formations_fr):
            err(langue, 'formations manquantes')

    # Pages, catégories, menus
    for nom, cles in (('pages.json', {'accueil', 'a-propos', 'contact', 'panier'}),
                      ('termes.json', {'alimentation-boissons', 'beaute-soin', 'complements', 'soin-personnel'})):
        data = json.load(open(d / nom, encoding='utf-8'))
        if set(data) != cles:
            err(f'{langue}/{nom}', f'clés {sorted(data)} ≠ {sorted(cles)}')
        for cle, v in data.items():
            slug_ok(f'{langue}/{nom}', cle, v.get('slug'), vus, slugs_fr)
    menus = json.load(open(d / 'menus.json', encoding='utf-8'))
    ref = json.load(open(TR / 'ar/menus.json', encoding='utf-8'))
    if set(menus) != set(ref):
        err(f'{langue}/menus.json', 'libellés différents de ceux de l\'arabe')

    # Réglages
    fr = reglages_fr()
    tr = json.load(open(d / 'reglages.json', encoding='utf-8'))
    if fr is not None:
        if set(tr) != set(fr):
            err(f'{langue}/reglages.json', f'clés manquantes ou en trop : {sorted(set(fr) ^ set(tr))}')
        for cle in fr:
            comparer(f'{langue}/reglages.json', cle, fr[cle], tr.get(cle, ''), langue)

    # Pages légales
    for md in sorted((d / 'legal').glob('*.md')) if (d / 'legal').exists() else []:
        src = RACINE / f'tools/traductions/ar/legal/{md.name}'
        bloc = lambda t: (re.search(r'```html\n(.*?)\n```', t, re.S) or [None, ''])[1]
        h = bloc(md.read_text(encoding='utf-8'))
        if not h:
            err(f'{langue}/legal/{md.name}', 'bloc html absent')
            continue
        if src.exists() and tags(bloc(src.read_text(encoding='utf-8'))) != tags(h):
            err(f'{langue}/legal/{md.name}', 'suite de balises différente de la version arabe (même source française)')
        if not re.search(r'\*\*Slug traduit\*\*', md.read_text(encoding='utf-8')):
            err(f'{langue}/legal/{md.name}', 'ligne **Slug traduit** absente')
    if complet and len(list((d / 'legal').glob('*.md')) if (d / 'legal').exists() else []) != 4:
        err(langue, 'pages légales manquantes')

    if erreurs:
        print(f'✗ {len(erreurs)} problème(s) en « {langue} » :')
        for e in erreurs:
            print('  -', e)
        sys.exit(1)
    print(f'✔ « {langue} » : {len(produits)} produit(s), réglages, pages, catégories, menus — aucun problème')


if __name__ == '__main__':
    main()
