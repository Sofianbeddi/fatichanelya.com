"""
Génération des fichiers .po du thème, commune à toutes les langues (thème 1.9.0).

Chaque langue a son dictionnaire dans tools/traductions/<langue>/chaines.py, qui appelle
`generer()`. Les chaînes sont extraites du thème et du mu-plugin par `wp i18n make-pot` ;
celles qui manquent au dictionnaire (libellés d'administration, en-tête du thème, palette de
l'éditeur) restent en français, l'administration du site étant en français.

Contrôle des langues ajoutées après l'arabe : toute chaîne publique traduite en arabe
(référence) doit l'être aussi, sinon le script s'arrête — une chaîne oubliée s'afficherait en
français sur la page espagnole ou anglaise.
"""
import re, sys, pathlib, subprocess, tempfile

RACINE = pathlib.Path(__file__).resolve().parents[2]
THEME = RACINE / 'wp-content/themes/fatichanelya'


def _lire_pot(chemin):
    s = chemin.read_text(encoding='utf-8')
    entrees = []
    for bloc in s.split('\n\n'):
        m = re.search(r'^msgid "(.*)"$', bloc, re.M)
        if not m or m.group(1) == '':
            continue
        ctx = re.search(r'^msgctxt "(.*)"$', bloc, re.M)
        pl = re.search(r'^msgid_plural "(.*)"$', bloc, re.M)
        comm = '\n'.join(l for l in bloc.splitlines() if l.startswith('#'))
        entrees.append((ctx.group(1) if ctx else None, m.group(1), pl.group(1) if pl else None, comm))
    return entrees


def _de(s):  # décoder les échappements du PO
    return s.encode().decode('unicode_escape').encode('latin-1').decode('utf-8') if '\\' in s else s


def _enc(s):
    return s.replace('\\', '\\\\').replace('"', '\\"').replace('\n', '\\n')


def chaines_source():
    """Toutes les chaînes du thème puis du mu-plugin, sans doublon : (ctx, msgid brut, pluriel, commentaires)."""
    tmp = pathlib.Path(tempfile.mkdtemp())
    vues, entrees = set(), []
    for dossier, nom in (("wp-content/themes/fatichanelya", "theme.pot"), ("wp-content/mu-plugins", "core.pot")):
        subprocess.run(["wp", "i18n", "make-pot", str(RACINE / dossier), str(tmp / nom), "--domain=fatichanelya"],
                       cwd=str(RACINE), check=True, capture_output=True)
        for ctx, mid, pl, comm in _lire_pot(tmp / nom):
            if (ctx, mid) in vues:
                continue
            vues.add((ctx, mid))
            entrees.append((ctx, mid, pl, comm))
    return entrees


def reference_arabe():
    """Le dictionnaire arabe (référence des chaînes publiques), sans lancer sa génération."""
    import runpy
    return runpy.run_path(str(RACINE / 'tools/traductions/ar/chaines.py'))['T']


def version_theme():
    m = re.search(r'^Version:\s*(\S+)', (THEME / 'style.css').read_text(encoding='utf-8'), re.M)
    return m.group(1) if m else '?'


def generer(T, langue, fichier, nplurals, plural, reference=None):
    """Écrit languages/<fichier>.po. `reference` : dictionnaire dont toutes les clés doivent être traduites."""
    out, manquantes = [], []
    for ctx, mid, pl, comm in chaines_source():
        src = _de(mid)
        tr = T.get(src)
        if tr is None:
            manquantes.append(src)
            continue
        bloc = [comm] if comm else []
        if ctx:
            bloc.append(f'msgctxt "{ctx}"')
        bloc.append(f'msgid "{mid}"')
        if pl:
            bloc.append(f'msgid_plural "{pl}"')
            formes = tr if isinstance(tr, list) else [tr] * nplurals
            if len(formes) != nplurals:
                sys.exit(f'✗ {src!r} : {len(formes)} formes plurielles, {nplurals} attendues')
            for i, f in enumerate(formes):
                bloc.append(f'msgstr[{i}] "{_enc(f)}"')
        else:
            bloc.append(f'msgstr "{_enc(tr if isinstance(tr, str) else tr[0])}"')
        out.append('\n'.join(bloc))

    if reference is not None:
        source = {_de(mid) for _, mid, _, _ in chaines_source()}
        oubliees = [k for k in reference if k in source and k not in T]
        if oubliees:
            print(f'✗ {len(oubliees)} chaîne(s) publique(s) non traduite(s) en {langue} :')
            for k in oubliees:
                print('   -', k)
            sys.exit(1)

    entete = f'''msgid ""
msgstr ""
"Project-Id-Version: Fatichanelya {version_theme()}\\n"
"Language: {langue}\\n"
"MIME-Version: 1.0\\n"
"Content-Type: text/plain; charset=UTF-8\\n"
"Content-Transfer-Encoding: 8bit\\n"
"Plural-Forms: nplurals={nplurals}; plural={plural};\\n"
"X-Generator: tools/traductions/{langue.split('_')[0]} (chaines.py)\\n"
'''
    dest = THEME / f'languages/{fichier}.po'
    dest.parent.mkdir(exist_ok=True)
    dest.write_text(entete + '\n' + '\n\n'.join(out) + '\n', encoding='utf-8')
    print(f'{fichier}.po : traduites', len(out), '| non traduites (admin ou en-tête) :', len(manquantes))
    if reference is None:
        for m in manquantes:
            print('   -', m[:70])
    return dest
