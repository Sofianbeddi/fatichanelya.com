// Localise les zones qui diffèrent dans une image de diff pixelmatch (pixels rouges) : node bbox.mjs DIFF.png
import fs from 'node:fs';
import { PNG } from 'pngjs';
const img = PNG.sync.read(fs.readFileSync(process.argv[2]));
const rows = new Map();
for (let y = 0; y < img.height; y++) for (let x = 0; x < img.width; x++) {
  const i = (img.width * y + x) * 4;
  if (img.data[i] === 255 && img.data[i + 1] === 0 && img.data[i + 2] === 0) { const b = Math.floor(y / 40); const r = rows.get(b) || { y0: y, y1: y, x0: x, x1: x, n: 0 }; r.y1 = y; r.x0 = Math.min(r.x0, x); r.x1 = Math.max(r.x1, x); r.n++; rows.set(b, r); }
}
for (const r of rows.values()) console.log(`y ${r.y0}-${r.y1}  x ${r.x0}-${r.x1}  ${r.n} px`);
