#!/usr/bin/env python3
"""
Contrôle bloquant d'une langue traduite, avant tout import (thème 1.9.0).

    WP="php -d mysqli.default_socket=… /usr/local/bin/wp" python3 tools/traductions/verifier.py es
    python3 tools/traductions/verifier.py en --complet     # exige 103 produits, 6 formations, 4 pages légales
    python3 tools/traductions/verifier.py es --seul produits-2.json   # un seul lot (aussi : formations, legal)

Vérifie, contre la source française :
  - clés : chaque entrée correspond à un contenu français existant ; avec --complet, rien ne manque ;
  - chiffres : mêmes nombres (décimales comparées sans leur séparateur), même nombre de lignes dans
    les modes d'emploi, points clés, réglages à lignes, mêmes barres « | » et astérisques ;
  - slugs : présents, ASCII minuscules, uniques entre les langues et jamais égaux à un slug français ;
  - mots interdits (allégations santé, voir GLOSSAIRE.md), hors phrases de négation listées ;
  - restes de français ;
  - pages légales : même suite de balises HTML et mêmes chiffres que la source française
    (_velix/copy-legal.md), hors note « pour information » en tête ; pas de contrôle des mots
    interdits (« tratamiento de datos » y est légitime).
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
    'en': [r'\bcures?\b', r'\bheal(s|ed|ing)?\b', r'\bprevent', r'immun', r'\bdefences?\b', r'detox', r'\bcleanse\b',
           r'slimming', r'weight loss', r'fat-burning', r'diabetes', r'cholesterol', r'blood pressure', r'blood sugar',
           r'anti-?ag(e)?ing', r'antioxidant', r'anti-inflammatory', r'\bboost', r'\bstrengthen', r'\bstimulat',
           r'miracle', r'no side effects', r'health benefits', r'good for your health', r'treatment'],
}
# Seules exceptions admises : les négations et les mises en garde reprises du français
# (« en cas de traitement médical ou de grossesse, demandez l'avis… », « traitement
# anticoagulant »). Ce ne sont pas des allégations, la traduction doit rester fidèle.
# « Purified » / « cleanser » ne sont pas interdits : faits d'étiquette, nom officiel Kallow.
NEGATIONS = {
    'es': ['los complementos alimenticios no curan', '¿sustituyen los complementos a un tratamiento?',
           'tratamiento médico', 'tratamiento anticoagulante'],
    'en': ['food supplements do not cure anything', 'can supplements replace a treatment?',
           'medical treatment', 'anticoagulant treatment'],
}
FRANCAIS = {
    'es': [r'\bavec\b', r'\bpour\b', r'\bdans\b', r'\bsans\b', r'\bdes\b', r'\best\b', r'\bet\b', r'\bvous\b', r'\bnous\b'],  # « une » est aussi un verbe espagnol (unir)
    'en': [r'\bavec\b', r'\bdans\b', r'\bsans\b', r'\bdes\b', r'\best\b', r'\bet\b', r'\bvous\b', r'\bles\b', r'\bune\b', r'\bde la\b'],
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
    seul = sys.argv[sys.argv.index('--seul') + 1] if '--seul' in sys.argv else None
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
        try:
            contenu = json.load(open(fichier, encoding='utf-8'))
        except ValueError:
            continue  # fichier en cours d'écriture par un autre traducteur
        for cle, v in contenu.items():
            if isinstance(v, dict) and v.get('slug'):
                vus[v['slug']] = f'{autre.name}/{fichier.name}:{cle}'

    # Produits
    produits = {}
    lots = sorted(d.glob('produits-*.json'))
    if seul:
        lots = [l for l in lots if l.name == seul]
    for lot in lots:
        try:
            contenu_lot = json.load(open(lot, encoding='utf-8'))
        except ValueError as e:
            err(f'{langue}/{lot.name}', f'JSON invalide : {e}')
            continue
        for cle, p in contenu_lot.items():
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
    if complet and not seul and set(produits) != set(produits_fr):
        err(langue, f'produits manquants : {sorted(set(produits_fr) - set(produits))}')

    # Formations
    fichier = d / 'formations.json'
    if fichier.exists() and seul in (None, 'formations'):
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
    for nom, cles in () if seul else (('pages.json', {'accueil', 'a-propos', 'contact', 'panier'}),
                      ('termes.json', {'alimentation-boissons', 'beaute-soin', 'complements', 'soin-personnel'})):
        data = json.load(open(d / nom, encoding='utf-8'))
        if set(data) != cles:
            err(f'{langue}/{nom}', f'clés {sorted(data)} ≠ {sorted(cles)}')
        for cle, v in data.items():
            slug_ok(f'{langue}/{nom}', cle, v.get('slug'), vus, slugs_fr)
    menus = json.load(open(d / 'menus.json', encoding='utf-8'))
    ref = json.load(open(TR / 'ar/menus.json', encoding='utf-8'))
    if not seul and set(menus) != set(ref):
        err(f'{langue}/menus.json', 'libellés différents de ceux de l\'arabe')

    # Réglages
    fr = reglages_fr() if not seul else None
    tr = json.load(open(d / 'reglages.json', encoding='utf-8'))
    if fr is not None:
        if set(tr) != set(fr):
            err(f'{langue}/reglages.json', f'clés manquantes ou en trop : {sorted(set(fr) ^ set(tr))}')
        for cle in fr:
            comparer(f'{langue}/reglages.json', cle, fr[cle], tr.get(cle, ''), langue)

    # Pages légales : comparées au HTML français de _velix/copy-legal.md
    sources = {m.group(1): m.group(2) for m in re.finditer(r'\*\*Slug\*\* : `([^`]+)`.*?```html\n(.*?)\n```',
               (RACINE / '_velix/copy-legal.md').read_text(encoding='utf-8'), re.S)}
    textes = lambda h: re.sub(r'<[^>]+>', ' ', h)
    pages_legales = sorted((d / 'legal').glob('*.md')) if (d / 'legal').exists() and seul in (None, 'legal') else []
    for md in pages_legales:
        f = f'{langue}/legal/{md.name}'
        t = md.read_text(encoding='utf-8')
        h = (re.search(r'```html\n(.*?)\n```', t, re.S) or [None, ''])[1]
        slug_src = (re.search(r'\*\*Slug\*\*\s*:\s*`([^`]+)`', t) or [None, ''])[1]
        if not h or slug_src not in sources:
            err(f, 'bloc html absent ou **Slug** français inconnu')
            continue
        h = re.sub(r'^\s*<p class="legal-note">.*?</p>\s*', '', h, flags=re.S)
        if tags(sources[slug_src]) != tags(h):
            err(f, 'suite de balises HTML différente de la source française')
        if nombres(textes(sources[slug_src])) != nombres(textes(h)):
            err(f, f'chiffres différents : {sorted(set(nombres(textes(sources[slug_src]))) ^ set(nombres(textes(h))))}')
        for motif in FRANCAIS[langue]:
            if re.search(motif, textes(h).lower()):
                err(f, f'reste de français /{motif}/')
        tr_slug = (re.search(r'\*\*Slug traduit\*\*\s*:\s*`([^`]+)`', t) or [None, ''])[1]
        slug_ok(f, 'slug traduit', tr_slug, vus, slugs_fr)
        if not re.search(r'\*\*Titre\*\*\s*:', t) or not re.search(r'\*\*Meta description\*\*[^\n]*\n`[^`]+`', t):
            err(f, 'ligne **Titre** ou **Meta description** absente ou mal formée')
    if complet and not seul and len(pages_legales) != 4:
        err(langue, 'pages légales manquantes')

    if erreurs:
        print(f'✗ {len(erreurs)} problème(s) en « {langue} » :')
        for e in erreurs:
            print('  -', e)
        sys.exit(1)
    print(f'✔ « {langue} »{" (" + seul + ")" if seul else ""} : {len(produits)} produit(s), {len(pages_legales)} page(s) légale(s) — aucun problème')


if __name__ == '__main__':
    main()
