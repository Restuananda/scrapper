<script setup>
import { ref, computed, onMounted } from 'vue';
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Card from '@/Components/Card.vue';
import Badge from '@/Components/Badge.vue';

defineOptions({ layout: AppLayout });

const props = defineProps({
    trackedProducts: { type: Array, default: () => [] },
});

const selectedProduct = ref(null);
const selectedPeriod = ref('30'); // days

// Demo data
const demoProducts = [
    {
        id: 1,
        product: { id: 1, name: 'iPhone 15 Pro Max 256GB', price: 19999000, image: 'https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=200' },
        history: [
            { date: 'Jan 01', price: 21999000 },
            { date: 'Jan 05', price: 21499000 },
            { date: 'Jan 10', price: 20999000 },
            { date: 'Jan 15', price: 20499000 },
            { date: 'Jan 20', price: 19999000 },
            { date: 'Jan 25', price: 19999000 },
            { date: 'Jan 30', price: 19999000 },
        ],
    },
    {
        id: 2,
        product: { id: 2, name: 'Samsung Galaxy S24 Ultra', price: 18500000, image: 'https://images.unsplash.com/photo-1610945415295-d9bbf067e59c?w=200' },
        history: [
            { date: 'Jan 01', price: 19500000 },
            { date: 'Jan 10', price: 19500000 },
            { date: 'Jan 15', price: 19000000 },
            { date: 'Jan 20', price: 18750000 },
            { date: 'Jan 25', price: 18500000 },
            { date: 'Jan 30', price: 18500000 },
        ],
    },
];

const trackedProducts = computed(() => 
    props.trackedProducts.length ? props.trackedProducts : demoProducts
);

onMounted(() => {
    if (trackedProducts.value.length > 0) {
        selectedProduct.value = trackedProducts.value[0];
    }
});

const formatPrice = (price) => 'Rp ' + new Intl.NumberFormat('id-ID').format(price);

const priceStats = computed(() => {
    if (!selectedProduct.value?.history?.length) return null;
    
    const prices = selectedProduct.value.history.map(h => h.price);
    const current = prices[prices.length - 1];
    const highest = Math.max(...prices);
    const lowest = Math.min(...prices);
    const first = prices[0];
    const change = ((current - first) / first * 100).toFixed(1);
    
    return { current, highest, lowest, change };
});

const chartPath = computed(() => {
    if (!selectedProduct.value?.history?.length) return '';
    
    const history = selectedProduct.value.history;
    const prices = history.map(h => h.price);
    const maxPrice = Math.max(...prices);
    const minPrice = Math.min(...prices);
    const range = maxPrice - minPrice || 1;
    
    const width = 100;
    const height = 40;
    const points = history.map((h, i) => {
        const x = (i / (history.length - 1)) * width;
        const y = height - ((h.price - minPrice) / range) * height;
        return `${x},${y}`;
    });
    
    return `M ${points.join(' L ')}`;
});

const chartAreaPath = computed(() => {
    if (!chartPath.value) return '';
    return `${chartPath.value} L 100,40 L 0,40 Z`;
});
</script>

<template>
    <Head title="Riwayat Harga" />

    <template #header>
        <div>
            <h1 class="text-2xl font-display font-bold text-white">Riwayat Harga</h1>
            <p class="text-surface-400 text-sm mt-1">Lihat pergerakan harga produk yang dilacak</p>
        </div>
    </template>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Product List -->
        <div class="lg:col-span-1">
            <Card padding="none">
                <div class="p-4 border-b border-surface-800">
                    <h3 class="font-semibold text-white">Produk Dilacak</h3>
                </div>
                <div class="divide-y divide-surface-800 max-h-[600px] overflow-y-auto">
                    <button
                        v-for="item in trackedProducts"
                        :key="item.id"
                        @click="selectedProduct = item"
                        :class="[
                            'w-full p-4 flex items-center gap-3 text-left transition-colors',
                            selectedProduct?.id === item.id ? 'bg-primary-500/10' : 'hover:bg-surface-800/50'
                        ]"
                    >
                        <img :src="item.product.image" class="w-12 h-12 rounded-lg object-cover" />
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-medium text-white truncate">{{ item.product.name }}</h4>
                            <p class="text-sm text-accent-400 font-semibold">{{ formatPrice(item.product.price) }}</p>
                        </div>
                    </button>
                </div>
            </Card>
        </div>

        <!-- Chart Area -->
        <div class="lg:col-span-2 space-y-6">
            <Card v-if="selectedProduct" padding="md">
                <!-- Product Info -->
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-4">
                        <img :src="selectedProduct.product.image" class="w-16 h-16 rounded-xl object-cover" />
                        <div>
                            <h3 class="font-semibold text-white">{{ selectedProduct.product.name }}</h3>
                            <p class="text-2xl font-bold text-accent-400 mt-1">
                                {{ formatPrice(selectedProduct.product.price) }}
                            </p>
                        </div>
                    </div>
                    
                    <!-- Period selector -->
                    <div class="flex gap-1 p-1 bg-surface-800 rounded-xl">
                        <button
                            v-for="period in ['7', '30', '90']"
                            :key="period"
                            @click="selectedPeriod = period"
                            :class="[
                                'px-3 py-1.5 text-sm font-medium rounded-lg transition-colors',
                                selectedPeriod === period ? 'bg-primary-500 text-white' : 'text-surface-400 hover:text-white'
                            ]"
                        >
                            {{ period }}h
                        </button>
                    </div>
                </div>

                <!-- Price Stats -->
                <div v-if="priceStats" class="grid grid-cols-4 gap-4 mb-6">
                    <div class="p-3 rounded-xl bg-surface-800/50">
                        <p class="text-xs text-surface-500">Harga Saat Ini</p>
                        <p class="text-lg font-bold text-white">{{ formatPrice(priceStats.current) }}</p>
                    </div>
                    <div class="p-3 rounded-xl bg-surface-800/50">
                        <p class="text-xs text-surface-500">Tertinggi</p>
                        <p class="text-lg font-bold text-danger-400">{{ formatPrice(priceStats.highest) }}</p>
                    </div>
                    <div class="p-3 rounded-xl bg-surface-800/50">
                        <p class="text-xs text-surface-500">Terendah</p>
                        <p class="text-lg font-bold text-success-400">{{ formatPrice(priceStats.lowest) }}</p>
                    </div>
                    <div class="p-3 rounded-xl bg-surface-800/50">
                        <p class="text-xs text-surface-500">Perubahan</p>
                        <p :class="[
                            'text-lg font-bold',
                            parseFloat(priceStats.change) < 0 ? 'text-success-400' : 
                            parseFloat(priceStats.change) > 0 ? 'text-danger-400' : 'text-surface-400'
                        ]">
                            {{ priceStats.change > 0 ? '+' : '' }}{{ priceStats.change }}%
                        </p>
                    </div>
                </div>

                <!-- Chart (SVG) -->
                <div class="relative h-64 bg-surface-800/30 rounded-xl p-4">
                    <svg viewBox="0 0 100 40" preserveAspectRatio="none" class="w-full h-full">
                        <!-- Grid lines -->
                        <line x1="0" y1="10" x2="100" y2="10" stroke="currentColor" class="text-surface-700" stroke-width="0.2" />
                        <line x1="0" y1="20" x2="100" y2="20" stroke="currentColor" class="text-surface-700" stroke-width="0.2" />
                        <line x1="0" y1="30" x2="100" y2="30" stroke="currentColor" class="text-surface-700" stroke-width="0.2" />
                        
                        <!-- Area fill -->
                        <path 
                            :d="chartAreaPath" 
                            fill="url(#chartGradient)" 
                            opacity="0.3"
                        />
                        
                        <!-- Line -->
                        <path 
                            :d="chartPath" 
                            fill="none" 
                            stroke="url(#lineGradient)" 
                            stroke-width="0.5"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        />
                        
                        <!-- Gradients -->
                        <defs>
                            <linearGradient id="chartGradient" x1="0%" y1="0%" x2="0%" y2="100%">
                                <stop offset="0%" stop-color="#2c91ff" stop-opacity="0.5" />
                                <stop offset="100%" stop-color="#2c91ff" stop-opacity="0" />
                            </linearGradient>
                            <linearGradient id="lineGradient" x1="0%" y1="0%" x2="100%" y2="0%">
                                <stop offset="0%" stop-color="#2c91ff" />
                                <stop offset="100%" stop-color="#ff7711" />
                            </linearGradient>
                        </defs>
                    </svg>
                    
                    <!-- Date labels -->
                    <div class="absolute bottom-0 left-0 right-0 flex justify-between px-4 text-xs text-surface-500">
                        <span v-for="(item, index) in selectedProduct.history" :key="index" v-show="index % 2 === 0">
                            {{ item.date }}
                        </span>
                    </div>
                </div>

                <!-- History Table -->
                <div class="mt-6">
                    <h4 class="text-sm font-semibold text-surface-400 mb-3">Riwayat Detail</h4>
                    <div class="table-container">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th class="text-right">Harga</th>
                                    <th class="text-right">Perubahan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in selectedProduct.history.slice().reverse()" :key="index">
                                    <td>{{ item.date }}</td>
                                    <td class="text-right font-medium text-white">{{ formatPrice(item.price) }}</td>
                                    <td class="text-right">
                                        <Badge 
                                            v-if="index < selectedProduct.history.length - 1"
                                            :variant="item.price < selectedProduct.history[selectedProduct.history.length - 2 - index]?.price ? 'success' : 
                                                     item.price > selectedProduct.history[selectedProduct.history.length - 2 - index]?.price ? 'danger' : 'primary'"
                                            size="sm"
                                        >
                                            {{ item.price === selectedProduct.history[selectedProduct.history.length - 2 - index]?.price ? '0%' : 
                                               item.price < selectedProduct.history[selectedProduct.history.length - 2 - index]?.price ? '↓' : '↑' }}
                                        </Badge>
                                        <span v-else class="text-surface-500">-</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </Card>

            <!-- Empty state -->
            <Card v-else padding="lg" class="text-center">
                <svg class="w-16 h-16 mx-auto text-surface-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-white">Pilih Produk</h3>
                <p class="mt-2 text-surface-500">Pilih produk dari daftar untuk melihat riwayat harga</p>
            </Card>
        </div>
    </div>
</template>
