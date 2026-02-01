/**
 * Product Scraper
 * 
 * Server-side adaptation of shopee-scraper-v3.js using Puppeteer
 */

const { getBrowser, closeBrowser } = require('../utils/browser');
const logger = require('../utils/logger');

/**
 * Scrape products from a Shopee search or shop page
 */
async function scrapeSearch(options) {
    const { 
        keyword, 
        shopId, 
        categoryId, 
        page = 1, 
        maxProducts = 50,
        sortBy = 'relevancy' // relevancy, sales, price, date
    } = options;

    let browser;
    let pageInstance;

    try {
        browser = await getBrowser();
        pageInstance = await browser.newPage();

        // Set viewport and user agent
        await pageInstance.setViewport({ width: 1920, height: 1080 });
        await pageInstance.setUserAgent(
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/121.0.0.0 Safari/537.36'
        );

        // Build URL
        let url;
        if (shopId) {
            url = `https://shopee.co.id/shop/${shopId}/search?page=${page - 1}&sortBy=${sortBy}`;
        } else if (keyword) {
            url = `https://shopee.co.id/search?keyword=${encodeURIComponent(keyword)}&page=${page - 1}&sortBy=${sortBy}`;
        } else if (categoryId) {
            url = `https://shopee.co.id/category/${categoryId}?page=${page - 1}&sortBy=${sortBy}`;
        } else {
            throw new Error('Must provide keyword, shopId, or categoryId');
        }

        logger.info(`Navigating to: ${url}`);
        await pageInstance.goto(url, { waitUntil: 'networkidle2', timeout: 30000 });

        // Wait for products to load
        await pageInstance.waitForSelector('.shopee-search-item-result__item, .shop-search-result-view__item', {
            timeout: 15000
        }).catch(() => {
            logger.warn('Product selector not found, trying fallback');
        });

        // Scroll to load more products
        await autoScroll(pageInstance);

        // Extract products using the same logic as browser script
        const products = await pageInstance.evaluate((maxProducts) => {
            // Selector finding logic (same as browser script)
            const mainSelectors = [
                '.shop-search-result-view__item',
                '.shopee-search-item-result__item',
            ];
            
            const fallbackSelectors = [
                '[data-sqe="item"]',
                '[data-item-id]',
                '.col-xs-2-4',
            ];

            let cards = [];
            
            for (const selector of mainSelectors) {
                const els = document.querySelectorAll(selector);
                if (els.length >= 3) {
                    cards = Array.from(els);
                    break;
                }
            }

            if (cards.length === 0) {
                for (const selector of fallbackSelectors) {
                    const els = document.querySelectorAll(selector);
                    if (els.length >= 3) {
                        cards = Array.from(els);
                        break;
                    }
                }
            }

            // Limit to maxProducts
            cards = cards.slice(0, maxProducts);

            // Extract data from each card
            return cards.map((card, index) => {
                // Extract name
                let name = 'N/A';
                const lineClamp = card.querySelector('.line-clamp-2, [class*="line-clamp"]');
                if (lineClamp) {
                    name = lineClamp.innerText?.trim() || name;
                } else {
                    const mainImg = card.querySelector('img[alt]:not([alt=""])');
                    if (mainImg && mainImg.alt && mainImg.alt.length > 10) {
                        name = mainImg.alt;
                    }
                }

                // Extract price
                let price = 'N/A';
                let priceNum = null;
                const allText = card.innerText;
                const priceMatch = allText.match(/Rp[\s]*([\d.,]+)/);
                if (priceMatch) {
                    price = 'Rp' + priceMatch[1].replace(/\s/g, '');
                    priceNum = parseInt(priceMatch[1].replace(/[.,]/g, ''));
                }

                // Extract discount
                let discount = null;
                const discountMatch = allText.match(/-?\d+%/);
                if (discountMatch) {
                    discount = parseInt(discountMatch[0]);
                }

                // Extract sold
                let sold = null;
                const soldMatch = allText.match(/(\d+[RBKrb]*\+?)\s*terjual/i);
                if (soldMatch) {
                    let soldStr = soldMatch[1].toUpperCase();
                    if (soldStr.includes('RB')) {
                        sold = parseInt(soldStr) * 1000;
                    } else if (soldStr.includes('K')) {
                        sold = parseInt(soldStr) * 1000;
                    } else {
                        sold = parseInt(soldStr);
                    }
                }

                // Extract rating
                let rating = null;
                const ratingMatch = allText.match(/([0-5]\.\d)/);
                if (ratingMatch) {
                    rating = parseFloat(ratingMatch[1]);
                }

                // Extract location
                let location = null;
                const cities = ['Jakarta', 'Bandung', 'Surabaya', 'Tangerang', 'Bekasi', 'Depok', 
                               'Semarang', 'Yogyakarta', 'Medan', 'Makassar', 'Palembang', 'Bogor'];
                for (const city of cities) {
                    if (allText.includes(city)) {
                        location = city;
                        break;
                    }
                }

                // Extract link
                let link = null;
                let shopeeId = null;
                const linkEl = card.querySelector('a[href*="-i."]') || card.querySelector('a.contents');
                if (linkEl) {
                    link = linkEl.href;
                    const idMatch = link.match(/-i\.(\d+)\.(\d+)/);
                    if (idMatch) {
                        shopeeId = idMatch[2];
                    }
                }

                // Extract image
                let image = null;
                const imgs = card.querySelectorAll('img');
                for (const img of imgs) {
                    const src = img.src || img.getAttribute('data-src');
                    const alt = img.alt || '';
                    if (alt.includes('label') || alt.includes('badge') || alt.includes('star')) continue;
                    if (src && (src.includes('susercontent.com') || src.includes('shopee'))) {
                        image = src;
                        break;
                    }
                }

                return {
                    index: index + 1,
                    shopee_id: shopeeId,
                    name: name.substring(0, 500),
                    price: priceNum,
                    price_display: price,
                    discount_percent: discount,
                    sold_count: sold,
                    rating: rating,
                    location: location,
                    link: link,
                    image_url: image,
                };
            });
        }, maxProducts);

        logger.info(`Scraped ${products.length} products`);
        return products;

    } catch (error) {
        logger.error('Scrape error', { error: error.message, stack: error.stack });
        throw error;
    } finally {
        if (pageInstance) await pageInstance.close();
    }
}

/**
 * Scrape single product details
 */
async function scrapeProduct(options) {
    const { productId, url } = options;

    let browser;
    let pageInstance;

    try {
        browser = await getBrowser();
        pageInstance = await browser.newPage();

        await pageInstance.setViewport({ width: 1920, height: 1080 });

        const productUrl = url || `https://shopee.co.id/product-i.${productId}`;
        logger.info(`Scraping product: ${productUrl}`);

        await pageInstance.goto(productUrl, { waitUntil: 'networkidle2', timeout: 30000 });

        // Wait for product info to load
        await pageInstance.waitForSelector('[class*="product-briefing"]', { timeout: 15000 });

        // Extract product details
        const product = await pageInstance.evaluate(() => {
            const getText = (selector) => {
                const el = document.querySelector(selector);
                return el?.innerText?.trim() || null;
            };

            // Get product name
            const name = getText('._44qnta') || getText('[class*="product-name"]') || 
                        document.querySelector('h1')?.innerText?.trim();

            // Get price
            let price = null;
            let originalPrice = null;
            const priceSection = document.querySelector('[class*="product-price"]');
            if (priceSection) {
                const priceText = priceSection.innerText;
                const prices = priceText.match(/Rp[\s]*([\d.,]+)/g);
                if (prices) {
                    price = parseInt(prices[0].replace(/[^\d]/g, ''));
                    if (prices.length > 1) {
                        originalPrice = parseInt(prices[1].replace(/[^\d]/g, ''));
                    }
                }
            }

            // Get rating
            let rating = null;
            let ratingCount = null;
            const ratingSection = document.querySelector('[class*="product-rating"]');
            if (ratingSection) {
                const ratingMatch = ratingSection.innerText.match(/(\d+\.\d)/);
                if (ratingMatch) rating = parseFloat(ratingMatch[1]);
                
                const countMatch = ratingSection.innerText.match(/([\d.,]+)\s*Penilaian/i);
                if (countMatch) ratingCount = parseInt(countMatch[1].replace(/[.,]/g, ''));
            }

            // Get sold count
            let soldCount = null;
            const pageText = document.body.innerText;
            const soldMatch = pageText.match(/([\d.,]+[RBKrbk]*\+?)\s*Terjual/i);
            if (soldMatch) {
                let soldStr = soldMatch[1].toUpperCase().replace(/[.,]/g, '');
                if (soldStr.includes('RB') || soldStr.includes('K')) {
                    soldCount = parseInt(soldStr) * 1000;
                } else {
                    soldCount = parseInt(soldStr);
                }
            }

            // Get stock
            let stock = null;
            const stockMatch = pageText.match(/Stok:\s*(\d+)/i);
            if (stockMatch) stock = parseInt(stockMatch[1]);

            // Get images
            const images = [];
            document.querySelectorAll('[class*="product-image"] img, [class*="slider"] img').forEach(img => {
                const src = img.src || img.getAttribute('data-src');
                if (src && src.includes('susercontent.com') && !images.includes(src)) {
                    images.push(src.replace(/_tn$/, '')); // Get full size
                }
            });

            // Get variants
            const variants = [];
            document.querySelectorAll('[class*="variation"] button, [class*="tier"] button').forEach(btn => {
                const text = btn.innerText?.trim();
                if (text && text.length < 100) {
                    variants.push(text);
                }
            });

            // Get seller info
            let seller = null;
            const sellerSection = document.querySelector('[class*="shop-info"], [class*="seller-info"]');
            if (sellerSection) {
                seller = {
                    name: sellerSection.querySelector('[class*="shop-name"], a')?.innerText?.trim(),
                };
            }

            // Get description
            const description = getText('[class*="product-detail"] [class*="content"]') ||
                               getText('[class*="description"]');

            return {
                name,
                price,
                original_price: originalPrice,
                discount_percent: originalPrice && price ? Math.round((1 - price/originalPrice) * 100) : null,
                rating,
                rating_count: ratingCount,
                sold_count: soldCount,
                stock,
                images,
                variants,
                seller,
                description: description?.substring(0, 2000),
            };
        });

        return product;

    } catch (error) {
        logger.error('Product scrape error', { error: error.message });
        throw error;
    } finally {
        if (pageInstance) await pageInstance.close();
    }
}

/**
 * Auto scroll to load lazy-loaded content
 */
async function autoScroll(page) {
    await page.evaluate(async () => {
        await new Promise((resolve) => {
            let totalHeight = 0;
            const distance = 500;
            const maxScrolls = 10;
            let scrollCount = 0;
            
            const timer = setInterval(() => {
                window.scrollBy(0, distance);
                totalHeight += distance;
                scrollCount++;
                
                if (scrollCount >= maxScrolls || totalHeight >= document.body.scrollHeight) {
                    clearInterval(timer);
                    window.scrollTo(0, 0);
                    resolve();
                }
            }, 500);
        });
    });
}

module.exports = {
    scrapeSearch,
    scrapeProduct,
};
