import fs from 'fs';
import path from 'path';

const manifestPath = 'public/build/manifest.json';
const buildAssetsDir = 'public/build/assets';
const outputDir = 'public/assets/vite';

if (!fs.existsSync(manifestPath)) {
    console.error('Missing public/build/manifest.json. Run vite build first.');
    process.exit(1);
}

const manifest = JSON.parse(fs.readFileSync(manifestPath, 'utf8'));
fs.mkdirSync(outputDir, { recursive: true });

const cssFiles = [];

if (manifest['resources/css/app.css']?.file) {
    cssFiles.push(manifest['resources/css/app.css'].file);
}

for (const chunk of manifest['resources/js/app.js']?.css ?? []) {
    if (!cssFiles.includes(chunk)) {
        cssFiles.push(chunk);
    }
}

let combinedCss = cssFiles
    .map((file) => fs.readFileSync(path.join('public/build', file), 'utf8'))
    .join('\n');

combinedCss = combinedCss.replaceAll('/build/assets/', '/assets/vite/');
fs.writeFileSync(path.join(outputDir, 'app.css'), combinedCss);

const jsFile = manifest['resources/js/app.js']?.file;
if (jsFile) {
    fs.copyFileSync(path.join('public/build', jsFile), path.join(outputDir, 'app.js'));
}

if (fs.existsSync(buildAssetsDir)) {
    for (const file of fs.readdirSync(buildAssetsDir)) {
        fs.copyFileSync(
            path.join(buildAssetsDir, file),
            path.join(outputDir, file),
        );
    }
}

console.log(`Synced fallback assets to ${outputDir}/`);
