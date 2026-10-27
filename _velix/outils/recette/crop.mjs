import fs from 'node:fs'; import { PNG } from 'pngjs';
const [src, out, x, y, w, h] = process.argv.slice(2);
const img = PNG.sync.read(fs.readFileSync(src)); const o = new PNG({ width: +w, height: +h });
PNG.bitblt(img, o, +x, +y, +w, +h, 0, 0); fs.writeFileSync(out, PNG.sync.write(o));
