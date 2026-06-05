/**
 * Capture d’écran de la page Pays / Villes / Quartiers.
 */
import { chromium } from 'playwright';
import path from 'node:path';
import fs from 'node:fs/promises';

const baseUrl = process.env.CLINIXORA_BASE_URL ?? process.env.DASHBOARD_BASE_URL ?? 'http://127.0.0.1:8000';
const loginUrl = new URL('/login', baseUrl).toString();
const geographyUrl = new URL('/parametres/administration/pays', baseUrl).toString();
const outputDir = process.env.GEOGRAPHY_SCREENSHOT_DIR ?? 'docs/screenshots/geography';
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

async function capture() {
    await fs.mkdir(outputDir, { recursive: true });

    const browser = await chromium.launch({ headless: true });

    try {
        const context = await browser.newContext({ viewport: desktopViewport });
        const page = await context.newPage();
        await login(page);

        await page.goto(geographyUrl, { waitUntil: 'networkidle' });
        await page.locator('#cities-panel-heading').waitFor({ state: 'visible', timeout: 20000 });

        const cameroonLink = page.getByRole('link', { name: /Cameroun/i }).first();
        if (await cameroonLink.isVisible()) {
            await cameroonLink.click();
            await page.waitForLoadState('networkidle');
        }

        const citySearch = page.locator('input[name="city_search"]');
        await citySearch.fill('Yaound');
        await citySearch.press('Enter');
        await page.waitForLoadState('networkidle');

        const outFile = path.join(outputDir, 'geography-pays-yaounde.png');
        await page.screenshot({ path: outFile, fullPage: true });
        console.log(`Capture générée : ${outFile}`);

        await context.close();
    } finally {
        await browser.close();
    }
}

capture().catch((error) => {
    console.error('Échec capture géographie (serveur démarré ? données seedées ?).');
    console.error(error);
    process.exit(1);
});
