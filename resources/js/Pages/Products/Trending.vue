<script setup>
import { ref, computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Card from '@/Components/Card.vue';
import Badge from '@/Components/Badge.vue';

defineOptions({ layout: AppLayout });

const props = defineProps({
    products: { type: Array, default: () => [] },
    categories: { type: Array, default: () => [] },
    days: { type: Number, default: 7 },
});

const selectedDays = ref(props.days);
const selectedCategory = ref('');

const timeRanges = [
    { value: 7, label: '7 Hari' },
    { value: 30, label: '30 Hari' },
    { value: 90, label: '90 Hari' },
];

// Demo data
const trendingProducts = ref([
    { id: 1, rank: 1, name: 'Kaos Polos Premium Cotton Combed 30s', price: 45000, sold: 12500, velocity: 1785, change: 23, image: 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=200' },
    { id: 2, rank: 2, name: 'Celana Jogger Training Pria Wanita', price: 89000, sold: 8700, velocity: 1242, change: 18, image: 'https://images.unsplash.com/photo-1552902865-b72c031ac5ea?w=200' },
    { id: 3, rank: 3, name: 'Sepatu Sneakers Casual Import', price: 259000, sold: 5400, velocity: 771, change: 15, image: 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=200' },
    { id: 4, rank: 4, name: 'Tas Selempang Pria Anti Air', price: 75000, sold: 9200, velocity: 657, change: -5, image: 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=200' },
    { id: 5, rank: 5, name: 'Headphone Bluetooth Wireless', price: 125000, sold: 6800, velocity: 485, change: 12, image: 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=200' },
]);

const formatPrice = (price) => 'Rp ' + new Intl.NumberFormat('id-ID').format(price);
const formatNumber = (num) => {
    if (num >= 1000) return (num / 1000).toFixed(1) + 'rb';
    return num;
};
</script>

<template>
    <Head title="Produk Trending" />

    <template #header>
        <div>
            <h1 class="text-2xl font-display font-bold text-white">Produk Trending</h1>
            <p class="text-surface-400 text-sm mt-1">Produk dengan pertumbuhan penjualan tercepat</p>
        </div>
    </template>

    <div class="space-y-6">
        <!-- Filters -->
        <Card padding="md">
            <div class="flex flex-wrap items-center gap-4">
                <!-- Time range -->
                <div class="flex items-center gap-2">
                    <span class="text-sm text-surface-400">Periode:</span>
                    <div class="flex gap-1 p-1 bg-surface-800 rounded-xl">
                        <button
                            v-for="range in timeRanges"
                            :key="range.value"
                            @click="selectedDays = range.value"
                            :class="[
                                'px-4 py-2 text-sm font-medium rounded-lg transition-colors',
                                selectedDays === range.value
                                    ? 'bg-primary-500 text-white'
                                    : 'text-surface-400 hover:text-white'
                            ]"
                        >
                            {{ range.label }}
                        </button>
                    </div>
                </div>

                <!-- Category filter -->
                <select
                    v-model="selectedCategory"
                    class="px-4 py-2.5 bg-surface-800 border border-surface-700 rounded-xl text-sm text-surface-300"
                >
                    <option value="">Semua Kategori</option>
                    <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                        {{ cat.name }}
                    </option>
                </select>
            </div>
        </Card>

        <!-- Trending Table -->
        <Card padding="none">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="w-16">Rank</th>
                            <th>Produk</th>
                            <th class="text-right">Harga</th>
                            <th class="text-right">Terjual</th>
                            <th class="text-right">Velocity/Hari</th>
                            <th class="text-right">Perubahan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="product in trendingProducts" :key="product.id" class="cursor-pointer">
                            <td>
                                <div :class="[
                                    'w-8 h-8 rounded-lg flex items-center justify-center font-bold text-sm',
                                    product.rank === 1 ? 'bg-warning-500/20 text-warning-400' :
                                    product.rank === 2 ? 'bg-surface-400/20 text-surface-300' :
                                    product.rank === 3 ? 'bg-accent-500/20 text-accent-400' :
                                    'bg-surface-800 text-surface-500'
                                ]">
                                    {{ product.rank }}
                                </div>
                            </td>
                            <td>
                                <div class="flex items-center gap-3">
                                    <img :src="product.image" class="w-12 h-12 rounded-lg object-cover" />
                                    <span class="font-medium text-white line-clamp-2">{{ product.name }}</span>
                                </div>
                            </td>
                            <td class="text-right font-semibold text-accent-400">
                                {{ formatPrice(product.price) }}
                            </td>
                            <td class="text-right">
                                {{ formatNumber(product.sold) }}
                            </td>
                            <td class="text-right font-medium text-primary-400">
                                {{ formatNumber(product.velocity) }}
                            </td>
                            <td class="text-right">
                                <Badge :variant="product.change >= 0 ? 'success' : 'danger'" size="sm">
                                    {{ product.change >= 0 ? '+' : '' }}{{ product.change }}%
                                </Badge>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </Card>
    </div>
</template>
