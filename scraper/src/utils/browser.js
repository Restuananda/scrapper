/**
 * Browser Manager
 * 
 * Manages Puppeteer browser instance with stealth and proxy support
 */

const puppeteer = require('puppeteer-extra');
const StealthPlugin = require('puppeteer-extra-plugin-stealth');
const logger = require('./logger');

// Add stealth plugin
puppeteer.use(StealthPlugin());

let browserInstance = null;

/**
 * Get or create browser instance
 */
async function getBrowser() {
    if (browserInstance && browserInstance.isConnected()) {
        return browserInstance;
    }

    const args = [
        '--no-sandbox',
        '--disable-setuid-sandbox',
        '--disable-dev-shm-usage',
        '--disable-accelerated-2d-canvas',
        '--disable-gpu',
        '--window-size=1920x1080',
    ];

    // Add proxy if configured
    if (process.env.PROXY_URL) {
        args.push(`--proxy-server=${process.env.PROXY_URL}`);
    }

    browserInstance = await puppeteer.launch({
        headless: 'new',
        args,
        defaultViewport: {
            width: 1920,
            height: 1080,
        },
    });

    logger.info('Browser launched');

    browserInstance.on('disconnected', () => {
        logger.info('Browser disconnected');
        browserInstance = null;
    });

    return browserInstance;
}

/**
 * Close browser instance
 */
async function closeBrowser() {
    if (browserInstance) {
        await browserInstance.close();
        browserInstance = null;
        logger.info('Browser closed');
    }
}

/**
 * Get a new page with common settings
 */
async function getPage(browser) {
    const page = await browser.newPage();

    // Set user agent
    await page.setUserAgent(
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/121.0.0.0 Safari/537.36'
    );

    // Set extra headers
    await page.setExtraHTTPHeaders({
        'Accept-Language': 'id-ID,id;q=0.9,en-US;q=0.8,en;q=0.7',
    });

    // Block unnecessary resources for faster loading
    await page.setRequestInterception(true);
    page.on('request', (req) => {
        const resourceType = req.resourceType();
        if (['image', 'stylesheet', 'font', 'media'].includes(resourceType)) {
            // Allow images for product scraping, block others
            if (resourceType !== 'image') {
                req.abort();
                return;
            }
        }
        req.continue();
    });

    return page;
}

module.exports = {
    getBrowser,
    closeBrowser,
    getPage,
};
