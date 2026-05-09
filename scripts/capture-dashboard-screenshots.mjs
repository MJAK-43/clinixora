import { chromium, devices } from 'playwright';
import path from 'node:path';
import fs from 'node:fs/promises';

const baseUrl = process.env.DASHBOARD_BASE_URL ?? 'http://127.0.0.1:8000';
const dashboardUrl = new URL('/dashboard', baseUrl).toString();
const loginUrl = new URL('/login', baseUrl).toString();
const outputDir = process.env.DASHBOARD_SCREENSHOT_DIR ?? 'docs/screenshots/dashboard';
const loginEmail = process.env.DASHBOARD_LOGIN_EMAIL ?? 'admin@clinixora.local';
const loginPassword = process.env.DASHBOARD_LOGIN_PASSWORD ?? 'password';

const desktopConfig = {
    name: 'desktop',
    viewport: { width: 1440, height: 900 },
};

const mobileConfig = {
    name: 'mobile',
    device: devices['iPhone 13'],
};

async function login(page) {
    await page.goto(loginUrl, { waitUntil: 'networkidle' });

    await page.getByLabel('Identifiant').fill(loginEmail);
    await page.getByLabel('Mot de passe').fill(loginPassword);
    await page.getByRole('button', { name: 'Se connecter' }).click();

    await page.waitForURL('**/dashboard', { timeout: 15000 });
    await page.waitForLoadState('networkidle');
}

async function capture() {
    await fs.mkdir(outputDir, { recursive: true });

    const browser = await chromium.launch({ headless: true });

    try {
        // Desktop screenshot
        const desktopContext = await browser.newContext({ viewport: desktopConfig.viewport });
        const desktopPage = await desktopContext.newPage();
        await login(desktopPage);
        await desktopPage.goto(dashboardUrl, { waitUntil: 'networkidle' });
        await desktopPage.screenshot({
            path: path.join(outputDir, 'dashboard-desktop.png'),
            fullPage: true,
        });
        await desktopContext.close();

        // Mobile screenshot
        const mobileContext = await browser.newContext({
            ...mobileConfig.device,
        });
        const mobilePage = await mobileContext.newPage();
        await login(mobilePage);
        await mobilePage.goto(dashboardUrl, { waitUntil: 'networkidle' });
        await mobilePage.screenshot({
            path: path.join(outputDir, 'dashboard-mobile.png'),
            fullPage: true,
        });
        await mobileContext.close();
    } finally {
        await browser.close();
    }
}

capture()
    .then(() => {
        console.log(`Screenshots generated in: ${outputDir}`);
        console.log(`- ${path.join(outputDir, 'dashboard-desktop.png')}`);
        console.log(`- ${path.join(outputDir, 'dashboard-mobile.png')}`);
    })
    .catch((error) => {
        console.error('Screenshot capture failed.');
        console.error(error);
        process.exit(1);
    });
