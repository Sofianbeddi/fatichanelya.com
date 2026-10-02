const puppeteer = require('/Users/B.Oussama/.npm/_npx/0f94ee7615faf582/node_modules/puppeteer-core');
(async () => {
  const b = await puppeteer.launch({ executablePath: '/Applications/Google Chrome.app/Contents/MacOS/Google Chrome', headless: 'new', args: ['--allow-file-access-from-files'] });
  const p = await b.newPage(); await p.setViewport({ width: 1200, height: 630, deviceScaleFactor: 1 });
  await p.goto('file://' + process.argv[2], { waitUntil: 'networkidle0' });
  await p.evaluate(() => document.fonts.ready);
  await p.screenshot({ path: process.argv[3], type: 'png' });
  await b.close();
})();
