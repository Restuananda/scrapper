/**
 * SIP Scraper Service
 * 
 * This service runs server-side and processes scrape jobs from Redis queue.
 * It uses Puppeteer to scrape Shopee pages.
 */

require('dotenv').config();
const Queue = require('bull');
const { scrapeProduct, scrapeSeller, scrapeSearch } = require('./scrapers');
const logger = require('./utils/logger');

// Redis connection
const REDIS_URL = process.env.REDIS_URL || 'redis://localhost:6379';

// Create queue
const scrapeQueue = new Queue('scrape-jobs', REDIS_URL, {
    defaultJobOptions: {
        attempts: 3,
        backoff: {
            type: 'exponential',
            delay: 5000,
        },
        removeOnComplete: 100,
        removeOnFail: 50,
    },
});

// Process jobs
scrapeQueue.process('product', 2, async (job) => {
    logger.info(`Processing product job: ${job.id}`, job.data);
    return await scrapeProduct(job.data);
});

scrapeQueue.process('seller', 2, async (job) => {
    logger.info(`Processing seller job: ${job.id}`, job.data);
    return await scrapeSeller(job.data);
});

scrapeQueue.process('search', 3, async (job) => {
    logger.info(`Processing search job: ${job.id}`, job.data);
    return await scrapeSearch(job.data);
});

// Event handlers
scrapeQueue.on('completed', (job, result) => {
    logger.info(`Job ${job.id} completed`, { type: job.name, resultCount: result?.length || 1 });
});

scrapeQueue.on('failed', (job, err) => {
    logger.error(`Job ${job.id} failed`, { type: job.name, error: err.message });
});

scrapeQueue.on('error', (err) => {
    logger.error('Queue error', { error: err.message });
});

logger.info('🚀 SIP Scraper Service started');
logger.info(`📡 Connected to Redis: ${REDIS_URL}`);

// Graceful shutdown
process.on('SIGTERM', async () => {
    logger.info('Shutting down...');
    await scrapeQueue.close();
    process.exit(0);
});
