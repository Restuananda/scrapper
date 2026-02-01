<script setup>
import { ref, computed } from 'vue';
import { Link, Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Card from '@/Components/Card.vue';
import Badge from '@/Components/Badge.vue';
import Button from '@/Components/Button.vue';

defineOptions({ layout: AppLayout });

const props = defineProps({
    stats: { type: Object, default: () => ({}) },
    recentProducts: { type: Array, default: () => [] },
    trendingProducts: { type: Array, default: () => [] },
    priceAlerts: { type: Array, default: () => [] },
});

// Demo data (replace with real props)
const stats = computed(() => props.stats || {
    searchCredits: 347,
    searchCreditsTotal: 500,
    trackedProducts: 15,
    trackedProductsTotal: 20,
    activeAlerts: 8,
    reportsGenerated: 3,
});

const trendingProducts = ref([
    {
        id: 1,
        name: 'Kaos Polos Premium Cotton Combed 30s',
        price: 45000,
        sold: 12500,
        rating: 4.9,
        image: 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=200',
        trend: 'up',
        trendPercent: 23,
    },
    {
        id: 2,
        name: 'Celana Jogger Training Pria Wanita',
        price: 89000,
        sold: 8700,
        rating: 4.8,
        image: 'https://images.unsplash.com/photo-1552902865-b72c031ac5ea?w=200',
        trend: 'up',
        trendPercent: 18,
    },
    {
        id: 3,
        name: 'Sepatu Sneakers Casual Import',
        price: 259000,
        sold: 5400,
        rating: 4.7,
        image: 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=200',
        trend: 'up',
        trendPercent: 15,
    },
    {
        id: 4,
        name: 'Tas Selempang Pria Anti Air',
        price: 75000,
        sold: 9200,
        rating: 4.8,
        image: 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=200',
        trend: 'down',
        trendPercent: -5,
    },
]);

const priceAlerts = ref([
    { id: 1, product: 'iPhone 15 Pro Max 256GB', oldPrice: 21999000, newPrice: 19999000, change: -9 },
    { id: 2, product: 'Samsung Galaxy S24 Ultra', oldPrice: 19500000, newPrice: 18500000, change: -5 },
    { id: 3, product: 'MacBook Air M3 256GB', oldPrice: 17999000, newPrice: 16499000, change: -8 },
]);

const formatPrice = (price) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
    }).format(price);
};

const formatNumber = (num) => {
    if (num >= 1000) return (num / 1000).toFixed(1) + 'rb';
    return num;
};
</script>

<template>
    <Head title="Dashboard" />

    <template #header>
        <div>
            <h1 class="text-2xl font-display font-bold text-white">Dashboard</h1>
            <p class="text-surface-400 text-sm mt-1">Selamat datang kembali! Berikut ringkasan aktivitas Anda.</p>
        </div>
    </template>

    <div class="space-y-8">
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Search Credits -->
            <Card padding="md" class="relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-primary-500/10 rounded-full -translate-y-1/2 translate-x-1/2"></div>
                <div class="relative">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-xl bg-primary-500/20 flex items-center justify-center">
                            <svg class="w-5 h-5 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <span class="text-sm text-surface-400">Kredit Pencarian</span>
                    </div>
                    <p class="stat-value">{{ stats.searchCredits }}</p>
                    <div class="mt-3">
                        <div class="flex items-center justify-between text-xs text-surface-500 mb-1">
                            <span>Tersisa</span>
                            <span>{{ stats.searchCredits }}/{{ stats.searchCreditsTotal }}</span>
                        </div>
                        <div class="h-1.5 bg-surface-800 rounded-full overflow-hidden">
                            <div 
                                class="h-full bg-gradient-to-r from-primary-500 to-primary-400 rounded-full transition-all"
                                :style="{ width: `${(stats.searchCredits / stats.searchCreditsTotal) * 100}%` }"
                            ></div>
                        </div>
                    </div>
                </div>
            </Card>

            <!-- Tracked Products -->
            <Card padding="md" class="relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-accent-500/10 rounded-full -translate-y-1/2 translate-x-1/2"></div>
                <div class="relative">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-xl bg-accent-500/20 flex items-center justify-center">
                            <svg class="w-5 h-5 text-accent-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </div>
                        <span class="text-sm text-surface-400">Produk Dilacak</span>
                    </div>
                    <p class="stat-value">{{ stats.trackedProducts }}</p>
                    <div class="mt-3">
                        <div class="flex items-center justify-between text-xs text-surface-500 mb-1">
                            <span>Slot terpakai</span>
                            <span>{{ stats.trackedProducts }}/{{ stats.trackedProductsTotal }}</span>
                        </div>
                        <div class="h-1.5 bg-surface-800 rounded-full overflow-hidden">
                            <div 
                                class="h-full bg-gradient-to-r from-accent-500 to-accent-400 rounded-full transition-all"
                                :style="{ width: `${(stats.trackedProducts / stats.trackedProductsTotal) * 100}%` }"
                            ></div>
                        </div>
                    </div>
                </div>
            </Card>

            <!-- Active Alerts -->
            <Card padding="md" class="relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-success-500/10 rounded-full -translate-y-1/2 translate-x-1/2"></div>
                <div class="relative">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-xl bg-success-500/20 flex items-center justify-center">
                            <svg class="w-5 h-5 text-success-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                        </div>
                        <span class="text-sm text-surface-400">Alert Aktif</span>
                    </div>
                    <p class="stat-value">{{ stats.activeAlerts }}</p>
                    <p class="stat-change stat-change-up mt-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                        </svg>
                        3 alert baru hari ini
                    </p>
                </div>
            </Card>

            <!-- Reports -->
            <Card padding="md" class="relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-warning-500/10 rounded-full -translate-y-1/2 translate-x-1/2"></div>
                <div class="relative">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-xl bg-warning-500/20 flex items-center justify-center">
                            <svg class="w-5 h-5 text-warning-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <span class="text-sm text-surface-400">Laporan Dibuat</span>
                    </div>
                    <p class="stat-value">{{ stats.reportsGenerated }}</p>
                    <p class="text-sm text-surface-500 mt-2">Bulan ini</p>
                </div>
            </Card>
        </div>

        <!-- Main content grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Trending Products -->
            <div class="lg:col-span-2">
                <Card padding="none">
                    <div class="p-6 border-b border-surface-800 flex items-center justify-between">
                        <div>
                            <h3 class="font-semibold text-white">Produk Trending</h3>
                            <p class="text-sm text-surface-500">Produk dengan penjualan tertinggi minggu ini</p>
                        </div>
                        <Link href="/products/trending" class="text-sm text-primary-400 hover:text-primary-300">
                            Lihat Semua →
                        </Link>
                    </div>
                    <div class="divide-y divide-surface-800">
                        <div
                            v-for="product in trendingProducts"
                            :key="product.id"
                            class="p-4 flex items-center gap-4 hover:bg-surface-800/30 transition-colors"
                        >
                            <img
                                :src="product.image"
                                :alt="product.name"
                                class="w-16 h-16 rounded-xl object-cover bg-surface-800"
                            />
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-medium text-white truncate">{{ product.name }}</h4>
                                <p class="text-lg font-bold text-accent-400 mt-1">{{ formatPrice(product.price) }}</p>
                                <div class="flex items-center gap-4 mt-1 text-xs text-surface-400">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 text-warning-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                        {{ product.rating }}
                                    </span>
                                    <span>{{ formatNumber(product.sold) }} terjual</span>
                                </div>
                            </div>
                            <div class="text-right">
                                <Badge :variant="product.trend === 'up' ? 'success' : 'danger'" size="sm">
                                    <svg v-if="product.trend === 'up'" class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                                    </svg>
                                    <svg v-else class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                                    </svg>
                                    {{ Math.abs(product.trendPercent) }}%
                                </Badge>
                            </div>
                        </div>
                    </div>
                </Card>
            </div>

            <!-- Price Alerts -->
            <div>
                <Card padding="none">
                    <div class="p-6 border-b border-surface-800">
                        <h3 class="font-semibold text-white">Notifikasi Harga</h3>
                        <p class="text-sm text-surface-500">Perubahan harga terbaru</p>
                    </div>
                    <div class="divide-y divide-surface-800">
                        <div
                            v-for="alert in priceAlerts"
                            :key="alert.id"
                            class="p-4 hover:bg-surface-800/30 transition-colors"
                        >
                            <h4 class="text-sm font-medium text-white truncate">{{ alert.product }}</h4>
                            <div class="flex items-center gap-2 mt-2">
                                <span class="text-sm text-surface-500 line-through">{{ formatPrice(alert.oldPrice) }}</span>
                                <svg class="w-4 h-4 text-surface-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                </svg>
                                <span class="text-sm font-semibold text-success-400">{{ formatPrice(alert.newPrice) }}</span>
                            </div>
                            <Badge variant="success" size="sm" class="mt-2">
                                Turun {{ Math.abs(alert.change) }}%
                            </Badge>
                        </div>
                    </div>
                    <div class="p-4 border-t border-surface-800">
                        <Link href="/monitoring/alerts" class="btn-ghost w-full text-center text-sm">
                            Lihat Semua Alert →
                        </Link>
                    </div>
                </Card>
            </div>
        </div>

        <!-- Quick Actions -->
        <Card padding="md">
            <h3 class="font-semibold text-white mb-4">Mulai Cepat</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <Link
                    href="/products/search"
                    class="flex items-center gap-4 p-4 rounded-xl bg-surface-800/50 border border-surface-700 hover:bg-surface-800 hover:border-surface-600 transition-all group"
                >
                    <div class="w-12 h-12 rounded-xl bg-primary-500/20 flex items-center justify-center group-hover:bg-primary-500/30 transition-colors">
                        <svg class="w-6 h-6 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-medium text-white">Cari Produk</h4>
                        <p class="text-xs text-surface-500">Temukan produk terlaris</p>
                    </div>
                </Link>

                <Link
                    href="/monitoring/products/add"
                    class="flex items-center gap-4 p-4 rounded-xl bg-surface-800/50 border border-surface-700 hover:bg-surface-800 hover:border-surface-600 transition-all group"
                >
                    <div class="w-12 h-12 rounded-xl bg-accent-500/20 flex items-center justify-center group-hover:bg-accent-500/30 transition-colors">
                        <svg class="w-6 h-6 text-accent-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-medium text-white">Lacak Produk</h4>
                        <p class="text-xs text-surface-500">Pantau perubahan harga</p>
                    </div>
                </Link>

                <Link
                    href="/intelligence/categories"
                    class="flex items-center gap-4 p-4 rounded-xl bg-surface-800/50 border border-surface-700 hover:bg-surface-800 hover:border-surface-600 transition-all group"
                >
                    <div class="w-12 h-12 rounded-xl bg-success-500/20 flex items-center justify-center group-hover:bg-success-500/30 transition-colors">
                        <svg class="w-6 h-6 text-success-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-medium text-white">Analisis Pasar</h4>
                        <p class="text-xs text-surface-500">Lihat tren kategori</p>
                    </div>
                </Link>

                <Link
                    href="/reports/create"
                    class="flex items-center gap-4 p-4 rounded-xl bg-surface-800/50 border border-surface-700 hover:bg-surface-800 hover:border-surface-600 transition-all group"
                >
                    <div class="w-12 h-12 rounded-xl bg-warning-500/20 flex items-center justify-center group-hover:bg-warning-500/30 transition-colors">
                        <svg class="w-6 h-6 text-warning-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-medium text-white">Buat Laporan</h4>
                        <p class="text-xs text-surface-500">Export data PDF/Excel</p>
                    </div>
                </Link>
            </div>
        </Card>
    </div>
</template>
