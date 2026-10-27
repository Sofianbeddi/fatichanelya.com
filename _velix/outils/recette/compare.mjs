// Compare deux dossiers de captures pixel à pixel : node compare.mjs AVANT APRES [DIFF_DIR]
import fs from 'node:fs';
import path from 'node:path';
import { PNG } from 'pngjs';
import pixelmatch from 'pixelmatch';

const [a, b, diffDir] = process.argv.slice(2);
if (diffDir) fs.mkdirSync(diffDir, { recursive: true });
let total = 0, changed = 0;
for (const f of fs.readdirSync(a).filter((n) => n.endsWith('.png')).sort()) {
  const fb = path.join(b, f);
  if (!fs.existsSync(fb)) { console.log(`${f.padEnd(40)} absent dans APRES`); continue; }
  const A = PNG.sync.read(fs.readFileSync(path.join(a, f)));
  const B = PNG.sync.read(fs.readFileSync(fb));
  total++;
  if (A.width !== B.width || A.height !== B.height) { changed++; console.log(`${f.padEnd(40)} TAILLE ${A.width}x${A.height} -> ${B.width}x${B.height}`); continue; }
  const diff = diffDir ? new PNG({ width: A.width, height: A.height }) : null;
  const n = pixelmatch(A.data, B.data, diff ? diff.data : null, A.width, A.height, { threshold: 0.1 });
  const pct = (n / (A.width * A.height) * 100).toFixed(3);
  if (n > 0) { changed++; if (diff) fs.writeFileSync(path.join(diffDir, f), PNG.sync.write(diff)); }
  console.log(`${f.padEnd(40)} ${n === 0 ? 'identique' : n + ' px (' + pct + ' %)'}`);
}
console.log(`\n${total} captures, ${changed} différentes`);
