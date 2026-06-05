/**
 * Capture d’écran de la page Paramètres pour comparaison avec docs/images/parametres.png
 */
import { chromium } from 'playwright';
import path from 'node:path';
import fs from 'node:fs/promises';

const baseUrl = process.env.CLINIXORA_BASE_URL ?? process.env.DASHBOARD_BASE_URL ?? 'http://127.0.0.1:8000';
const loginUrl = new URL('/login', baseUrl).toString();
const parametresUrl = new URL('/parametres', baseUrl).toString();
const outputDir = process.env.PARAMETRES_SCREENSHOT_DIR ?? 'docs/screenshots/parametres';
const referenceSource = path.join('docs', 'images', 'parametres.png');
const referenceDest = path.join(outputDir, 'reference-maquette.png');
const loginEmail = process.env.DASHBOARD_LOGIN_EMAIL ?? 'admin@clinixora.local';
const loginPassword = process.env.DASHBOARD_LOGIN_PASSWORD ?? 'password';

const desktopViewport = { width: 1440, height: 900 };

async function login(page) {
    await page.goto(loginUrl, { waitUntil: 'domcontentloaded' });

    await page.getByLabel('Identifiant').fill(loginEmail);
    await page.getByLabel('Mot de passe').fill(loginPassword);
    await page.getByRole('button', { name: 'Se connecter' }).click();

    await page.waitForURL((url) => !url.pathname.includes('/login'), { timeout: 20000 });
    await page.waitForLoadState('networkidle');
}

async function copyReferenceIfPresent() {
    try {
        await fs.copyFile(referenceSource, referenceDest);
        console.log(`Référence copiée vers : ${referenceDest}`);
    } catch {
        console.warn(`Référence introuvable ou copie impossible : ${referenceSource}`);
    }
}

async function capture() {
    await fs.mkdir(outputDir, { recursive: true });
    await copyReferenceIfPresent();

    const browser = await chromium.launch({ headless: true });

    try {
        const context = await browser.newContext({ viewport: desktopViewport });
        const page = await context.newPage();
        await login(page);

        const response = await page.goto(parametresUrl, { waitUntil: 'networkidle' });
        if (!response?.ok()) {
            throw new Error(`HTTP ${response?.status() ?? '?'} sur /parametres`);
        }

        await page.waitForURL('**/parametres', { timeout: 15000 });
        await page.locator('#quick-access-heading').waitFor({ state: 'visible', timeout: 20000 });
        await page.locator('#parametres-page-title').scrollIntoViewIfNeeded();

        const outFile = path.join(outputDir, 'parametres-desktop.png');
        await page.screenshot({ path: outFile, fullPage: true });
        console.log(`Capture générée : ${outFile}`);
        console.log('Comparer visuellement avec : docs/images/parametres.png');
        await context.close();
    } finally {
        await browser.close();
    }
}

capture().catch((error) => {
    console.error('Échec de la capture Paramètres (serveur démarré ? compte admin@clinixora.local ?).');
    console.error(error);
    process.exit(1);
});
