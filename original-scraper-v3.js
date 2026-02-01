/**
 * =====================================================
 * SHOPEE PRODUCT DATA SCRAPER v3.0
 * =====================================================
 * Updated with exact Shopee selectors (Jan 2025)
 * =====================================================
 */

(function() {
    'use strict';

    // ==================== SELECTOR FINDER ====================
    
    /**
     * Find product cards automatically
     */
    function findProductCards(mode = 'all') {
        // mode: 'all' = both featured + main list, 'main' = only main list, 'featured' = only featured
        
        const mainSelectors = [
            '.shop-search-result-view__item',
            '.shopee-search-item-result__item',
        ];
        
        const featuredSelectors = [
            '.shop-collection-view__item',
        ];
        
        const fallbackSelectors = [
            '[data-sqe="item"]',
            '[data-item-id]',
            '.col-xs-2-4',
        ];

        let allCards = [];
        
        // Get main product list
        if (mode === 'all' || mode === 'main') {
            for (const selector of mainSelectors) {
                try {
                    const els = document.querySelectorAll(selector);
                    if (els.length >= 3) {
                        console.log(`✅ Found ${els.length} products in main list (${selector})`);
                        allCards = allCards.concat(Array.from(els));
                        break;
                    }
                } catch (e) {}
            }
        }
        
        // Get featured sections
        if (mode === 'all' || mode === 'featured') {
            for (const selector of featuredSelectors) {
                try {
                    const els = document.querySelectorAll(selector);
                    if (els.length >= 3) {
                        console.log(`✅ Found ${els.length} products in featured sections (${selector})`);
                        allCards = allCards.concat(Array.from(els));
                        break;
                    }
                } catch (e) {}
            }
        }
        
        // If nothing found, try fallbacks
        if (allCards.length === 0) {
            for (const selector of fallbackSelectors) {
                try {
                    const els = document.querySelectorAll(selector);
                    if (els.length >= 3) {
                        console.log(`✅ Found ${els.length} products with fallback (${selector})`);
                        return Array.from(els);
                    }
                } catch (e) {}
            }
        }
        
        // Remove duplicates (same product may appear in featured and main list)
        const uniqueCards = [];
        const seenLinks = new Set();
        
        for (const card of allCards) {
            const link = card.querySelector('a[href*="-i."]');
            const href = link ? link.href : null;
            
            if (href && !seenLinks.has(href)) {
                seenLinks.add(href);
                uniqueCards.push(card);
            } else if (!href) {
                uniqueCards.push(card);
            }
        }
        
        if (uniqueCards.length > 0) {
            console.log(`📦 Total unique products: ${uniqueCards.length}`);
        }
        
        return uniqueCards;
    }

    /**
     * Extract product name
     */
    function extractName(card) {
        // Method 1: div.line-clamp-2 (current Shopee)
        const lineClamp = card.querySelector('.line-clamp-2, [class*="line-clamp"]');
        if (lineClamp) {
            // Get text content, but filter out any badge text
            const text = lineClamp.innerText?.trim();
            if (text && text.length > 3) return text;
        }

        // Method 2: Image alt attribute (reliable backup)
        const mainImg = card.querySelector('img[alt]:not([alt=""])');
        if (mainImg && mainImg.alt && mainImg.alt.length > 10 && !mainImg.alt.includes('label') && !mainImg.alt.includes('star')) {
            return mainImg.alt;
        }

        // Method 3: Link href parsing
        const link = card.querySelector('a[href*="-i."]');
        if (link) {
            const href = link.href;
            const match = href.match(/\/([^/]+)-i\.\d+\.\d+/);
            if (match) {
                return match[1].replace(/-/g, ' ');
            }
        }

        // Method 4: First significant text
        const texts = Array.from(card.querySelectorAll('div, span'))
            .map(el => el.innerText?.trim())
            .filter(t => t && t.length > 10 && t.length < 200 && 
                        !t.includes('Rp') && !t.includes('%') && 
                        !t.includes('terjual') && !t.includes('Diskon'));
        
        if (texts[0]) return texts[0];

        return 'N/A';
    }

    /**
     * Extract price
     */
    function extractPrice(card) {
        // Method 1: Look for price structure with Rp
        const priceContainers = card.querySelectorAll('[class*="price"], [class*="Price"]');
        for (const container of priceContainers) {
            const text = container.innerText?.trim();
            if (text && text.includes('Rp')) {
                return text.replace(/\s+/g, '');
            }
        }

        // Method 2: Find Rp followed by numbers (current Shopee structure)
        const allText = card.innerText;
        const priceMatch = allText.match(/Rp[\s]*([\d.,]+)/);
        if (priceMatch) {
            return 'Rp' + priceMatch[1].replace(/\s/g, '');
        }

        // Method 3: Look for spans inside flex containers
        const flexDivs = card.querySelectorAll('.flex');
        for (const div of flexDivs) {
            const text = div.innerText?.trim();
            if (text && text.match(/^Rp[\d.,]+$/)) {
                return text;
            }
        }

        return 'N/A';
    }

    /**
     * Extract discount
     */
    function extractDiscount(card) {
        // Method 1: Shopee pink badge (current)
        const pinkBadge = card.querySelector('.bg-shopee-pink, [class*="shopee-pink"]');
        if (pinkBadge) {
            const text = pinkBadge.innerText?.trim();
            if (text && text.includes('%')) return text;
        }

        // Method 2: aria-label containing %
        const ariaLabels = card.querySelectorAll('[aria-label*="%"]');
        for (const el of ariaLabels) {
            const label = el.getAttribute('aria-label');
            if (label) return label;
        }

        // Method 3: Any text with % pattern
        const allText = card.innerText;
        const discountMatch = allText.match(/-?\d+%/);
        if (discountMatch) {
            return discountMatch[0];
        }

        return '-';
    }

    /**
     * Extract sold count
     */
    function extractSold(card) {
        // Method 1: Look for "terjual" text
        const elements = card.querySelectorAll('div, span');
        for (const el of elements) {
            const text = el.innerText?.trim();
            if (text && text.includes('terjual')) {
                return text;
            }
        }

        // Method 2: Pattern matching
        const allText = card.innerText;
        const soldMatch = allText.match(/(\d+[RBKrb]*\+?\s*terjual)/i);
        if (soldMatch) {
            return soldMatch[1];
        }

        // Method 3: RB+ pattern (Indonesian shorthand for thousands)
        const rbMatch = allText.match(/(\d+RB\+)/i);
        if (rbMatch) {
            return rbMatch[1] + ' terjual';
        }

        return '-';
    }

    /**
     * Extract rating
     */
    function extractRating(card) {
        // Method 1: Look near rating-star image
        const ratingStar = card.querySelector('img[alt*="rating"], img[alt*="star"]');
        if (ratingStar) {
            const parent = ratingStar.parentElement;
            const sibling = parent.querySelector('span');
            if (sibling) {
                const text = sibling.innerText?.trim();
                if (text && text.match(/^[0-5]\.\d$/)) {
                    return text;
                }
            }
        }

        // Method 2: Find rating pattern in text
        const allText = card.innerText;
        const ratingMatch = allText.match(/([0-5]\.\d)/);
        if (ratingMatch) {
            return ratingMatch[1];
        }

        return '-';
    }

    /**
     * Extract location
     */
    function extractLocation(card) {
        // Method 1: Location class
        const locEl = card.querySelector('[class*="location"], [class*="shop-loc"]');
        if (locEl) {
            return locEl.innerText?.trim() || '-';
        }

        // Method 2: Indonesian city names pattern
        const allText = card.innerText;
        const cities = ['Jakarta', 'Bandung', 'Surabaya', 'Tangerang', 'Bekasi', 'Depok', 
                       'Semarang', 'Yogyakarta', 'Medan', 'Makassar', 'Palembang', 'Bogor'];
        
        for (const city of cities) {
            if (allText.includes(city)) {
                const match = allText.match(new RegExp(`(${city}[^\\n]*|[^\\n]*${city})`, 'i'));
                if (match) return match[0].trim().substring(0, 50);
            }
        }

        // Method 3: Kota/Kab pattern
        const kotaMatch = allText.match(/(Kota|Kab\.?)\s+\w+/i);
        if (kotaMatch) {
            return kotaMatch[0];
        }

        return '-';
    }

    /**
     * Extract link
     */
    function extractLink(card) {
        const link = card.querySelector('a[href*="-i."]') || card.querySelector('a.contents');
        if (link) {
            return link.href;
        }
        
        if (card.tagName === 'A') {
            return card.href;
        }

        return 'N/A';
    }

    /**
     * Extract image
     */
    function extractImage(card) {
        // Get main product image (not badges/labels)
        const imgs = card.querySelectorAll('img');
        for (const img of imgs) {
            const src = img.src || img.getAttribute('data-src');
            const alt = img.alt || '';
            
            // Skip label/badge images
            if (alt.includes('label') || alt.includes('badge') || alt.includes('star') || 
                alt.includes('flag') || alt.includes('overlay') || alt.includes('promotion')) {
                continue;
            }
            
            // Main product image
            if (src && (src.includes('susercontent.com') || src.includes('shopee'))) {
                return src;
            }
        }
        
        return 'N/A';
    }

    /**
     * Main scraping function
     */
    function scrapeProducts(mode = 'main') {
        console.log('🔍 Starting scrape...\n');
        console.log(`📋 Mode: ${mode === 'all' ? 'All products' : mode === 'main' ? 'Main list only' : 'Featured only'}\n`);
        
        const cards = findProductCards(mode);
        
        if (cards.length === 0) {
            console.error('❌ No product cards found.');
            console.log('\n💡 Tips:');
            console.log('   1. Make sure you are on a Shopee search/shop page');
            console.log('   2. Scroll down to load products first');
            console.log('   3. Run ShopeeScraper.debug() for troubleshooting');
            return [];
        }

        console.log(`\n📦 Processing ${cards.length} products...\n`);

        const products = [];

        cards.forEach((card, index) => {
            try {
                const product = {
                    no: index + 1,
                    name: extractName(card),
                    price: extractPrice(card),
                    discount: extractDiscount(card),
                    sold: extractSold(card),
                    rating: extractRating(card),
                    location: extractLocation(card),
                    link: extractLink(card),
                    image: extractImage(card)
                };

                // Clean up name
                if (product.name !== 'N/A') {
                    product.name = product.name.substring(0, 200).trim();
                }

                products.push(product);
            } catch (e) {
                console.warn(`⚠️ Error on item ${index + 1}:`, e.message);
            }
        });

        return products;
    }

    /**
     * Debug: Show what's in a product card
     */
    function debugCard(index = 0, mode = 'main') {
        const cards = findProductCards(mode);
        if (cards.length === 0) {
            console.log('No cards found');
            return;
        }

        const card = cards[Math.min(index, cards.length - 1)];
        console.log('='.repeat(60));
        console.log(`CARD DEBUG - Card #${index + 1}`);
        console.log('='.repeat(60));
        
        console.log('\n📝 EXTRACTED DATA:');
        console.log('   Name:', extractName(card));
        console.log('   Price:', extractPrice(card));
        console.log('   Discount:', extractDiscount(card));
        console.log('   Sold:', extractSold(card));
        console.log('   Rating:', extractRating(card));
        console.log('   Location:', extractLocation(card));
        console.log('   Link:', extractLink(card));
        
        console.log('\n📄 RAW TEXT:');
        console.log(card.innerText);
        
        console.log('\n🔗 CLASSES:');
        console.log(card.className);
        
        return card;
    }

    /**
     * Auto scroll
     */
    async function autoScroll(times = 10) {
        console.log('📜 Auto-scrolling to load more products...');
        for (let i = 0; i < times; i++) {
            window.scrollBy(0, 800);
            await new Promise(r => setTimeout(r, 1500));
            console.log(`   Scroll ${i + 1}/${times}`);
        }
        window.scrollTo(0, 0);
        console.log('✅ Scroll complete!');
    }

    /**
     * Download as CSV
     */
    function downloadCSV(data, filename) {
        if (!data || data.length === 0) {
            console.warn('No data to download');
            return;
        }
        const headers = Object.keys(data[0]);
        const rows = data.map(row => 
            headers.map(h => `"${(row[h] || '').toString().replace(/"/g, '""').replace(/\n/g, ' ')}"`).join(',')
        );
        const csv = [headers.join(','), ...rows].join('\n');
        
        const blob = new Blob([csv], { type: 'text/csv;charset=utf-8' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = filename || `shopee_${Date.now()}.csv`;
        a.click();
        URL.revokeObjectURL(url);
        console.log('📥 CSV Downloaded!');
    }

    /**
     * Download as JSON
     */
    function downloadJSON(data, filename) {
        if (!data || data.length === 0) {
            console.warn('No data to download');
            return;
        }
        const json = JSON.stringify(data, null, 2);
        const blob = new Blob([json], { type: 'application/json' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = filename || `shopee_${Date.now()}.json`;
        a.click();
        URL.revokeObjectURL(url);
        console.log('📥 JSON Downloaded!');
    }

    /**
     * Download as MDX (Markdown with JSX)
     */
    function downloadMDX(data, filename, options = {}) {
        if (!data || data.length === 0) {
            console.warn('No data to download');
            return;
        }

        const { 
            title = 'Shopee Products', 
            includeImages = true,
            includeTable = true,
            includeCards = false 
        } = options;

        let mdx = `---
title: "${title}"
date: "${new Date().toISOString().split('T')[0]}"
totalProducts: ${data.length}
---

# ${title}

> Scraped on ${new Date().toLocaleString()} | Total: **${data.length} products**

`;

        if (includeTable) {
            mdx += `## Product List

| No | Name | Price | Discount | Sold | Rating |
|----|------|-------|----------|------|--------|
`;
            data.forEach(p => {
                const name = p.name.length > 50 ? p.name.substring(0, 50) + '...' : p.name;
                mdx += `| ${p.no} | ${name} | ${p.price} | ${p.discount} | ${p.sold} | ${p.rating} |\n`;
            });
            mdx += '\n';
        }

        if (includeCards) {
            mdx += `## Product Cards

<div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
`;
            data.forEach(p => {
                mdx += `
<div className="border rounded-lg p-4 shadow-sm">
${includeImages && p.image !== 'N/A' ? `  <img src="${p.image}" alt="${p.name.substring(0, 50)}" className="w-full h-48 object-cover rounded" />` : ''}
  <h3 className="font-semibold mt-2 text-sm">${p.name.substring(0, 80)}</h3>
  <p className="text-red-500 font-bold">${p.price}</p>
  <div className="flex justify-between text-xs text-gray-500">
    <span>⭐ ${p.rating}</span>
    <span>${p.sold}</span>
  </div>
  ${p.link !== 'N/A' ? `<a href="${p.link}" target="_blank" className="text-blue-500 text-xs">View Product →</a>` : ''}
</div>
`;
            });
            mdx += `</div>\n\n`;
        }

        // Add detailed list
        mdx += `## Product Details

`;
        data.forEach(p => {
            mdx += `### ${p.no}. ${p.name.substring(0, 100)}

- **Price:** ${p.price}
- **Discount:** ${p.discount}
- **Sold:** ${p.sold}
- **Rating:** ${p.rating}
- **Location:** ${p.location}
${p.link !== 'N/A' ? `- **Link:** [View on Shopee](${p.link})` : ''}
${includeImages && p.image !== 'N/A' ? `\n![Product Image](${p.image})` : ''}

---

`;
        });

        const blob = new Blob([mdx], { type: 'text/mdx' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = filename || `shopee_${Date.now()}.mdx`;
        a.click();
        URL.revokeObjectURL(url);
        console.log('📥 MDX Downloaded!');
    }

    /**
     * Download as Markdown (simpler format)
     */
    function downloadMD(data, filename) {
        if (!data || data.length === 0) {
            console.warn('No data to download');
            return;
        }

        let md = `# Shopee Products

> Scraped on ${new Date().toLocaleString()} | Total: **${data.length} products**

## Product Table

| No | Name | Price | Discount | Sold | Rating |
|----|------|-------|----------|------|--------|
`;
        data.forEach(p => {
            const name = p.name.length > 50 ? p.name.substring(0, 50) + '...' : p.name;
            md += `| ${p.no} | ${name} | ${p.price} | ${p.discount} | ${p.sold} | ${p.rating} |\n`;
        });

        md += `\n## Full Product List\n\n`;

        data.forEach(p => {
            md += `### ${p.no}. ${p.name}

| Field | Value |
|-------|-------|
| Price | ${p.price} |
| Discount | ${p.discount} |
| Sold | ${p.sold} |
| Rating | ${p.rating} |
| Location | ${p.location} |
| Link | ${p.link !== 'N/A' ? `[View](${p.link})` : 'N/A'} |

---

`;
        });

        const blob = new Blob([md], { type: 'text/markdown' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = filename || `shopee_${Date.now()}.md`;
        a.click();
        URL.revokeObjectURL(url);
        console.log('📥 Markdown Downloaded!');
    }

    /**
     * Get current page number
     */
    function getCurrentPage() {
        const activeBtn = document.querySelector('.shopee-page-controller .shopee-button-solid--primary');
        return activeBtn ? parseInt(activeBtn.innerText) : 1;
    }

    /**
     * Get total pages from mini controller or page controller
     */
    function getTotalPages() {
        // Try mini page controller first (more accurate)
        const miniTotal = document.querySelector('.shopee-mini-page-controller__total');
        if (miniTotal) {
            return parseInt(miniTotal.innerText) || 1;
        }
        
        // Fallback to page controller buttons
        const pageController = document.querySelector('.shopee-page-controller');
        if (!pageController) return 1;
        
        const buttons = pageController.querySelectorAll('button');
        let maxPage = 1;
        
        buttons.forEach(btn => {
            const num = parseInt(btn.innerText);
            if (!isNaN(num) && num > maxPage) {
                maxPage = num;
            }
        });
        
        return maxPage;
    }

    /**
     * Click next page button
     */
    function goToNextPage() {
        // Method 1: Try mini page controller next button
        const miniNext = document.querySelector('.shopee-mini-page-controller__next-btn:not([disabled])');
        if (miniNext) {
            miniNext.click();
            return true;
        }
        
        // Method 2: Try main page controller
        const nextBtn = document.querySelector('.shopee-page-controller .shopee-icon-button--right:not([disabled])');
        if (nextBtn) {
            nextBtn.click();
            return true;
        }
        
        // Method 3: Click the next page number directly
        const currentPage = getCurrentPage();
        const pageButtons = document.querySelectorAll('.shopee-page-controller button');
        for (const btn of pageButtons) {
            const num = parseInt(btn.innerText);
            if (num === currentPage + 1) {
                btn.click();
                return true;
            }
        }
        
        return false;
    }

    /**
     * Check if there's a next page
     */
    function hasNextPage() {
        const currentPage = getCurrentPage();
        const totalPages = getTotalPages();
        
        if (currentPage >= totalPages) return false;
        
        // Check mini controller
        const miniNext = document.querySelector('.shopee-mini-page-controller__next-btn');
        if (miniNext && !miniNext.disabled) return true;
        
        // Check main controller
        const nextBtn = document.querySelector('.shopee-page-controller .shopee-icon-button--right');
        if (nextBtn && !nextBtn.disabled && !nextBtn.classList.contains('shopee-button-outline--disabled')) {
            return true;
        }
        
        return currentPage < totalPages;
    }

    /**
     * Wait for page content to load after pagination
     */
    async function waitForPageLoad(expectedPage) {
        return new Promise((resolve) => {
            let attempts = 0;
            const maxAttempts = 30; // Increased attempts
            
            const checkLoaded = setInterval(() => {
                attempts++;
                const currentPage = getCurrentPage();
                const cards = findProductCards('main');
                
                // Page loaded when page number changed and we have products
                if ((currentPage === expectedPage && cards.length > 0) || attempts >= maxAttempts) {
                    clearInterval(checkLoaded);
                    setTimeout(resolve, 1000); // Extra wait for stability
                }
            }, 500); // Check every 500ms
        });
    }

    /**
     * Scroll page to load all products
     */
    async function scrollPage() {
        // Scroll down to ensure all products are loaded
        window.scrollTo(0, document.body.scrollHeight / 2);
        await new Promise(r => setTimeout(r, 500));
        window.scrollTo(0, document.body.scrollHeight);
        await new Promise(r => setTimeout(r, 500));
        window.scrollTo(0, 0);
        await new Promise(r => setTimeout(r, 300));
    }

    /**
     * Scrape all pages automatically
     */
    async function scrapeAllPages(maxPages = 999, mode = 'main') {
        console.log('🔍 Starting multi-page scrape...\n');
        
        let allProducts = [];
        let currentPage = getCurrentPage();
        const totalPages = getTotalPages();
        const pagesToScrape = Math.min(maxPages, totalPages);
        
        console.log(`📄 Current page: ${currentPage}`);
        console.log(`📄 Total pages detected: ${totalPages}`);
        console.log(`📄 Will scrape up to: ${pagesToScrape} pages\n`);
        
        let consecutiveFailures = 0;
        const maxFailures = 3;
        
        while (currentPage <= pagesToScrape) {
            console.log(`\n📖 Scraping page ${currentPage}/${pagesToScrape}...`);
            
            // Scroll to load all products on page
            await scrollPage();
            
            // Wait a bit for page to stabilize
            await new Promise(r => setTimeout(r, 1500));
            
            // Scrape current page
            const cards = findProductCards(mode);
            console.log(`   Found ${cards.length} products on this page`);
            
            if (cards.length === 0) {
                consecutiveFailures++;
                console.warn(`   ⚠️ No products found (attempt ${consecutiveFailures}/${maxFailures})`);
                
                if (consecutiveFailures >= maxFailures) {
                    console.error('   ❌ Too many failures, stopping...');
                    break;
                }
                
                // Wait and retry
                await new Promise(r => setTimeout(r, 2000));
                continue;
            }
            
            consecutiveFailures = 0; // Reset on success
            
            cards.forEach((card, index) => {
                try {
                    const product = {
                        no: allProducts.length + 1,
                        page: currentPage,
                        name: extractName(card),
                        price: extractPrice(card),
                        discount: extractDiscount(card),
                        sold: extractSold(card),
                        rating: extractRating(card),
                        location: extractLocation(card),
                        link: extractLink(card),
                        image: extractImage(card)
                    };
                    
                    if (product.name !== 'N/A') {
                        product.name = product.name.substring(0, 200).trim();
                    }
                    
                    allProducts.push(product);
                } catch (e) {
                    console.warn(`   ⚠️ Error on item ${index + 1}:`, e.message);
                }
            });
            
            console.log(`   ✅ Total collected: ${allProducts.length} products`);
            
            // Check if we should continue
            if (currentPage >= pagesToScrape) {
                console.log('\n🏁 Reached target page limit');
                break;
            }
            
            if (!hasNextPage()) {
                console.log('\n🏁 No more pages available');
                break;
            }
            
            // Go to next page
            const nextPage = currentPage + 1;
            console.log(`   ➡️ Moving to page ${nextPage}...`);
            
            const clicked = goToNextPage();
            if (!clicked) {
                console.warn('   ⚠️ Failed to click next page');
                break;
            }
            
            // Wait for new page to load
            await waitForPageLoad(nextPage);
            
            // Verify page changed
            const actualPage = getCurrentPage();
            if (actualPage !== nextPage) {
                console.warn(`   ⚠️ Page didn't change (expected ${nextPage}, got ${actualPage})`);
                // Try to continue anyway
            }
            
            currentPage = actualPage;
        }
        
        console.log(`\n✅ Scraping complete! Total: ${allProducts.length} products from ${currentPage} pages`);
        return allProducts;
    }

    // ==================== MAIN OBJECT ====================
    
    const ShopeeScraper = {
        data: null,

        // Scrape products (mode: 'main', 'featured', 'all')
        scrape(mode = 'main') {
            this.data = scrapeProducts(mode);
            console.log(`\n✅ Scraped ${this.data.length} products!\n`);
            console.table(this.data);
            return this.data;
        },

        // Scrape all products (main list + featured sections)
        scrapeAll() {
            return this.scrape('all');
        },

        // Scrape multiple pages automatically
        async scrapePages(maxPages = 10) {
            this.data = await scrapeAllPages(maxPages, 'main');
            console.log(`\n✅ Scraped ${this.data.length} products from multiple pages!\n`);
            console.table(this.data.slice(0, 20)); // Show first 20
            if (this.data.length > 20) {
                console.log(`... and ${this.data.length - 20} more products`);
            }
            return this.data;
        },

        // Quick scrape all pages and export CSV
        async scrapeAllPagesExport(maxPages = 10, filename) {
            await this.scrapePages(maxPages);
            this.exportCSV(filename);
        },

        // Scrape all pages and export JSON
        async scrapePagesJSON(maxPages = 10, filename) {
            await this.scrapePages(maxPages);
            this.exportJSON(filename);
        },

        // Scrape all pages and export MDX
        async scrapePagesMDX(maxPages = 10, filename, options) {
            await this.scrapePages(maxPages);
            this.exportMDX(filename, options);
        },

        // Scrape all pages and export ALL formats
        async scrapePagesAll(maxPages = 10, baseName) {
            await this.scrapePages(maxPages);
            this.exportAll(baseName);
        },

        // Get pagination info
        pageInfo() {
            console.log(`Current page: ${getCurrentPage()}`);
            console.log(`Total pages: ${getTotalPages()}`);
            console.log(`Has next page: ${hasNextPage()}`);
        },

        // Scroll then scrape
        async scrollScrape(scrollTimes = 10, mode = 'main') {
            await autoScroll(scrollTimes);
            return this.scrape(mode);
        },

        // Export CSV
        exportCSV(filename) {
            if (!this.data) {
                console.warn('⚠️ No data. Run ShopeeScraper.scrape() first');
                return;
            }
            downloadCSV(this.data, filename);
        },

        // Export JSON
        exportJSON(filename) {
            if (!this.data) {
                console.warn('⚠️ No data. Run ShopeeScraper.scrape() first');
                return;
            }
            downloadJSON(this.data, filename);
        },

        // Export MDX
        exportMDX(filename, options) {
            if (!this.data) {
                console.warn('⚠️ No data. Run ShopeeScraper.scrape() first');
                return;
            }
            downloadMDX(this.data, filename, options);
        },

        // Export Markdown
        exportMD(filename) {
            if (!this.data) {
                console.warn('⚠️ No data. Run ShopeeScraper.scrape() first');
                return;
            }
            downloadMD(this.data, filename);
        },

        // Export all formats at once
        exportAll(baseName) {
            if (!this.data) {
                console.warn('⚠️ No data. Run ShopeeScraper.scrape() first');
                return;
            }
            const name = baseName || `shopee_${Date.now()}`;
            downloadCSV(this.data, `${name}.csv`);
            downloadJSON(this.data, `${name}.json`);
            downloadMDX(this.data, `${name}.mdx`);
            console.log('📥 All formats downloaded!');
        },

        // Copy to clipboard
        copy() {
            if (!this.data) {
                console.warn('⚠️ No data. Run ShopeeScraper.scrape() first');
                return;
            }
            const json = JSON.stringify(this.data, null, 2);
            if (typeof copy === 'function') {
                copy(json);
            } else {
                navigator.clipboard.writeText(json);
            }
            console.log('📋 Copied to clipboard!');
        },

        // Debug helper
        debug(index = 0) {
            return debugCard(index);
        },

        // Quick export
        async quickExport(filename) {
            await this.scrollScrape();
            this.exportCSV(filename);
        },

        // Help
        help() {
            console.log(`
╔═══════════════════════════════════════════════════════════════╗
║              🛒 SHOPEE SCRAPER v3.1 - COMMANDS                ║
╠═══════════════════════════════════════════════════════════════╣
║  SINGLE PAGE:                                                 ║
║  .scrape()              → Scrape main product list            ║
║  .scrapeAll()           → Scrape ALL products (main+featured) ║
║  .scrollScrape()        → Scroll + Scrape                     ║
╠═══════════════════════════════════════════════════════════════╣
║  MULTI-PAGE (AUTO PAGINATION):                                ║
║  .scrapePages(10)       → Scrape 10 pages                     ║
║  .scrapeAllPagesExport(10)      → Scrape + CSV                ║
║  .scrapePagesJSON(10)           → Scrape + JSON               ║
║  .scrapePagesMDX(10)            → Scrape + MDX                ║
║  .scrapePagesAll(10, 'name')    → Scrape + ALL formats        ║
╠═══════════════════════════════════════════════════════════════╣
║  EXPORT (after scraping):                                     ║
║  .exportCSV('file.csv')         → Download CSV                ║
║  .exportJSON('file.json')       → Download JSON               ║
║  .exportMDX('file.mdx')         → Download MDX                ║
║  .exportMD('file.md')           → Download Markdown           ║
║  .exportAll('basename')         → Download ALL formats        ║
╠═══════════════════════════════════════════════════════════════╣
║  UTILITIES:                                                   ║
║  .pageInfo()            → Show pagination info                ║
║  .copy()                → Copy JSON to clipboard              ║
║  .debug(1)              → Debug card #1                       ║
╚═══════════════════════════════════════════════════════════════╝

📌 Examples:
   ShopeeScraper.scrapePages(20)                    // Scrape 20 pages
   ShopeeScraper.scrapePagesAll(10, 'my-products')  // Scrape 10 pages + export all
   ShopeeScraper.exportMDX('products.mdx', {includeCards: true})
            `);
        }
    };

    // Expose globally
    window.ShopeeScraper = ShopeeScraper;

    // Show welcome
    console.clear();
    console.log(`
╔═══════════════════════════════════════════════════════════════╗
║              🛒 SHOPEE SCRAPER v3.1 LOADED!                   ║
╠═══════════════════════════════════════════════════════════════╣
║  ✓ Auto-pagination with improved page detection               ║
║  ✓ Export: CSV, JSON, MDX, Markdown                           ║
║  ✓ Multi-page scraping with error recovery                    ║
╚═══════════════════════════════════════════════════════════════╝

📌 Quick Start:
   ShopeeScraper.scrape()              → Scrape current page
   ShopeeScraper.scrapePages(10)       → Scrape 10 pages
   ShopeeScraper.scrapePagesAll(10)    → Scrape 10 pages + Export ALL formats

📌 Export Options:
   .exportCSV()  .exportJSON()  .exportMDX()  .exportMD()  .exportAll()

📌 Type ShopeeScraper.help() for all commands
    `);

})();
