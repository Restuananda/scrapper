/**
 * Scrapers Index
 * 
 * Export all scraper functions
 */

const { scrapeSearch, scrapeProduct } = require('./product');

/**
 * Scrape seller information
 */
async function scrapeSeller(options) {
    // TODO: Implement in Phase 4
    const { sellerId, username } = options;
    throw new Error('Seller scraping not yet implemented');
}

module.exports = {
    scrapeSearch,
    scrapeProduct,
    scrapeSeller,
};
