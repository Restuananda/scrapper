<script setup>
import { ref, computed, watch } from 'vue';
import { router, Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Card from '@/Components/Card.vue';
import Button from '@/Components/Button.vue';
import Badge from '@/Components/Badge.vue';
import Input from '@/Components/Input.vue';
import Modal from '@/Components/Modal.vue';
import LoadingSpinner from '@/Components/LoadingSpinner.vue';

defineOptions({ layout: AppLayout });

const props = defineProps({
    products: { type: Array, default: () => [] },
    filters: { type: Object, default: () => ({}) },
    categories: { type: Array, default: () => [] },
    pagination: { type: Object, default: () => ({}) },
});

// Search state
const searchQuery = ref(props.filters.q || '');
const selectedCategory = ref(props.filters.category || '');
const priceMin = ref(props.filters.price_min || '');
const priceMax = ref(props.filters.price_max || '');
const sortBy = ref(props.filters.sort || 'relevance');
const isLoading = ref(false);

// View mode
const viewMode = ref('grid'); // grid or list

// Selected product for modal
const selectedProduct = ref(null);
const showProductModal = ref(false);

// Sort options
const sortOptions = [
    { value: 'relevance', label: 'Relevansi' },
    { value: 'sales', label: 'Terlaris' },
    { value: 'price_asc', label: 'Harga: Rendah ke Tinggi' },
    { value: 'price_desc', label: 'Harga: Tinggi ke Rendah' },
    { value: 'rating', label: 'Rating Tertinggi' },
    { value: 'newest', label: 'Terbaru' },
];

// Demo products (replace with real data)
const demoProducts = ref([
    {
        id: 1,
        shopee_id: '123456789',
        name: 'Kaos Polos Premium Cotton Combed 30s Unisex - All Size',
        price: 45000,
        original_price: 75000,
        discount: 40,
        sold: 12500,
        rating: 4.9,
        rating_count: 2340,
        location: 'Jakarta Barat',
        image: 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=400',
        seller: { name: 'TokoKaos Official', rating: 4.9 },
    },
    {
        id: 2,
        shopee_id: '234567890',
        name: 'Celana Jogger Training Pria Wanita Bahan Premium',
        price: 89000,
        original_price: 150000,
        discount: 41,
        sold: 8700,
        rating: 4.8,
        rating_count: 1890,
        location: 'Bandung',
        image: 'https://images.unsplash.com/photo-1552902865-b72c031ac5ea?w=400',
        seller: { name: 'Fashion Store', rating: 4.7 },
    },
    {
        id: 3,
        shopee_id: '345678901',
        name: 'Sepatu Sneakers Casual Import Premium Quality',
        price: 259000,
        original_price: 450000,
        discount: 42,
        sold: 5400,
        rating: 4.7,
        rating_count: 980,
        location: 'Surabaya',
        image: 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=400',
        seller: { name: 'Sneakers World', rating: 4.8 },
    },
    {
        id: 4,
        shopee_id: '456789012',
        name: 'Tas Selempang Pria Anti Air Waterproof USB Charging',
        price: 75000,
        original_price: 120000,
        discount: 38,
        sold: 9200,
        rating: 4.8,
        rating_count: 2100,
        location: 'Jakarta Selatan',
        image: 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=400',
        seller: { name: 'Bag Store ID', rating: 4.6 },
    },
    {
        id: 5,
        shopee_id: '567890123',
        name: 'Headphone Bluetooth Wireless Gaming RGB LED',
        price: 125000,
        original_price: 250000,
        discount: 50,
        sold: 6800,
        rating: 4.6,
        rating_count: 1560,
        location: 'Tangerang',
        image: 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=400',
        seller: { name: 'Tech Gadget', rating: 4.5 },
    },
    {
        id: 6,
        shopee_id: '678901234',
        name: 'Skincare Set Paket Lengkap Glowing Anti Aging',
        price: 189000,
        original_price: 350000,
        discount: 46,
        sold: 4500,
        rating: 4.9,
        rating_count: 890,
        location: 'Bekasi',
        image: 'https://images.unsplash.com/photo-1556228720-195a672e8a03?w=400',
        seller: { name: 'Beauty Care ID', rating: 4.9 },
    },
]);

const products = computed(() => props.products.length ? props.products : demoProducts.value);

// Search function
const search = () => {
    isLoading.value = true;
    
    router.get('/products/search', {
        q: searchQuery.value,
        category: selectedCategory.value,
        price_min: priceMin.value,
        price_max: priceMax.value,
        sort: sortBy.value,
    }, {
        preserveState: true,
        onFinish: () => {
            isLoading.value = false;
        },
    });
};

// Format functions
const formatPrice = (price) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
    }).format(price);
};

const formatNumber = (num) => {
    if (num >= 1000000) return (num / 1000000).toFixed(1) + 'jt';
    if (num >= 1000) return (num / 1000).toFixed(1) + 'rb';
    return num;
};

// Open product modal
const openProduct = (product) => {
    selectedProduct.value = product;
    showProductModal.value = true;
};

// Track product
const trackProduct = (product) => {
    router.post('/monitoring/products', {
        shopee_id: product.shopee_id,
    });
};

// Add to collection
const addToCollection = (product) => {
    // TODO: Open collection modal
};
</script>

<template>
    <Head title="Pencarian Produk" />

    <template #header>
        <div>
            <h1 class="text-2xl font-display font-bold text-white">Pencarian Produk</h1>
            <p class="text-surface-400 text-sm mt-1">Temukan produk terlaris di Shopee</p>
        </div>
    </template>

    <div class="space-y-6">
        <!-- Search & Filters -->
        <Card padding="md">
            <form @submit.prevent="search" class="space-y-4">
                <!-- Search input -->
                <div class="relative">
                    <input
                        v-model="searchQuery"
                        type="text"
                        placeholder="Cari produk, kategori, atau seller..."
                        class="w-full pl-12 pr-32 py-4 bg-surface-800 border border-surface-700 rounded-2xl text-white
                               placeholder-surface-500 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 text-lg"
                    />
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-surface-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <Button
                        type="submit"
                        variant="primary"
                        :loading="isLoading"
                        class="absolute right-2 top-1/2 -translate-y-1/2"
                    >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Cari
                    </Button>
                </div>

                <!-- Filters row -->
                <div class="flex flex-wrap items-center gap-4">
                    <!-- Category -->
                    <select
                        v-model="selectedCategory"
                        class="px-4 py-2.5 bg-surface-800 border border-surface-700 rounded-xl text-sm text-surface-300
                               focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20"
                    >
                        <option value="">Semua Kategori</option>
                        <option value="fashion">Fashion</option>
                        <option value="electronics">Elektronik</option>
                        <option value="health">Kesehatan & Kecantikan</option>
                        <option value="home">Rumah & Kehidupan</option>
                        <option value="food">Makanan & Minuman</option>
                    </select>

                    <!-- Price range -->
                    <div class="flex items-center gap-2">
                        <input
                            v-model="priceMin"
                            type="number"
                            placeholder="Harga Min"
                            class="w-32 px-3 py-2.5 bg-surface-800 border border-surface-700 rounded-xl text-sm text-surface-300
                                   placeholder-surface-500 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20"
                        />
                        <span class="text-surface-500">-</span>
                        <input
                            v-model="priceMax"
                            type="number"
                            placeholder="Harga Max"
                            class="w-32 px-3 py-2.5 bg-surface-800 border border-surface-700 rounded-xl text-sm text-surface-300
                                   placeholder-surface-500 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20"
                        />
                    </div>

                    <!-- Sort -->
                    <select
                        v-model="sortBy"
                        class="px-4 py-2.5 bg-surface-800 border border-surface-700 rounded-xl text-sm text-surface-300
                               focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20"
                    >
                        <option v-for="opt in sortOptions" :key="opt.value" :value="opt.value">
                            {{ opt.label }}
                        </option>
                    </select>

                    <!-- Spacer -->
                    <div class="flex-1"></div>

                    <!-- View mode -->
                    <div class="flex items-center gap-1 p-1 bg-surface-800 rounded-xl">
                        <button
                            type="button"
                            @click="viewMode = 'grid'"
                            :class="[
                                'p-2 rounded-lg transition-colors',
                                viewMode === 'grid' ? 'bg-surface-700 text-white' : 'text-surface-500 hover:text-white'
                            ]"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                            </svg>
                        </button>
                        <button
                            type="button"
                            @click="viewMode = 'list'"
                            :class="[
                                'p-2 rounded-lg transition-colors',
                                viewMode === 'list' ? 'bg-surface-700 text-white' : 'text-surface-500 hover:text-white'
                            ]"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </form>
        </Card>

        <!-- Results info -->
        <div class="flex items-center justify-between">
            <p class="text-sm text-surface-400">
                Menampilkan <span class="text-white font-medium">{{ products.length }}</span> produk
                <span v-if="searchQuery">untuk "<span class="text-primary-400">{{ searchQuery }}</span>"</span>
            </p>
            <Button variant="secondary" size="sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Export CSV
            </Button>
        </div>

        <!-- Loading state -->
        <div v-if="isLoading" class="flex items-center justify-center py-20">
            <LoadingSpinner size="lg" />
        </div>

        <!-- Product Grid -->
        <div v-else :class="[
            viewMode === 'grid' 
                ? 'grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6'
                : 'space-y-4'
        ]">
            <div
                v-for="product in products"
                :key="product.id"
                :class="[
                    viewMode === 'grid' ? 'product-card' : 'card-hover flex gap-4 p-4'
                ]"
                @click="openProduct(product)"
            >
                <!-- Grid view -->
                <template v-if="viewMode === 'grid'">
                    <div class="product-card-image">
                        <img :src="product.image" :alt="product.name" />
                        <Badge v-if="product.discount" variant="accent" class="product-card-badge">
                            -{{ product.discount }}%
                        </Badge>
                    </div>
                    <div class="product-card-body">
                        <h3 class="product-card-title">{{ product.name }}</h3>
                        <div class="flex items-baseline gap-2 mt-2">
                            <span class="product-card-price">{{ formatPrice(product.price) }}</span>
                            <span v-if="product.original_price" class="product-card-original-price">
                                {{ formatPrice(product.original_price) }}
                            </span>
                        </div>
                        <div class="product-card-stats">
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-warning-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                {{ product.rating }}
                            </span>
                            <span>{{ formatNumber(product.sold) }} terjual</span>
                            <span>{{ product.location }}</span>
                        </div>
                    </div>
                </template>

                <!-- List view -->
                <template v-else>
                    <img :src="product.image" :alt="product.name" class="w-24 h-24 rounded-xl object-cover" />
                    <div class="flex-1 min-w-0">
                        <h3 class="font-medium text-white truncate">{{ product.name }}</h3>
                        <div class="flex items-center gap-3 mt-2 text-xs text-surface-400">
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-warning-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                {{ product.rating }}
                            </span>
                            <span>{{ formatNumber(product.sold) }} terjual</span>
                            <span>{{ product.location }}</span>
                        </div>
                        <p class="text-sm text-surface-500 mt-1">{{ product.seller?.name }}</p>
                    </div>
                    <div class="text-right">
                        <div class="flex items-baseline gap-2">
                            <span class="text-xl font-bold text-accent-400">{{ formatPrice(product.price) }}</span>
                            <Badge v-if="product.discount" variant="accent" size="sm">-{{ product.discount }}%</Badge>
                        </div>
                        <span v-if="product.original_price" class="text-sm text-surface-500 line-through">
                            {{ formatPrice(product.original_price) }}
                        </span>
                    </div>
                </template>
            </div>
        </div>

        <!-- Empty state -->
        <div v-if="!isLoading && products.length === 0" class="text-center py-20">
            <svg class="w-16 h-16 mx-auto text-surface-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <h3 class="mt-4 text-lg font-medium text-white">Tidak ada produk ditemukan</h3>
            <p class="mt-2 text-surface-500">Coba ubah kata kunci atau filter pencarian Anda</p>
        </div>
    </div>

    <!-- Product Detail Modal -->
    <Modal :show="showProductModal" @close="showProductModal = false" max-width="2xl">
        <template #header>
            <h3 class="text-lg font-semibold text-white">Detail Produk</h3>
        </template>

        <div v-if="selectedProduct" class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <img :src="selectedProduct.image" :alt="selectedProduct.name" class="w-full aspect-square object-cover rounded-xl" />
            <div>
                <Badge v-if="selectedProduct.discount" variant="accent" class="mb-3">
                    Diskon {{ selectedProduct.discount }}%
                </Badge>
                <h2 class="text-xl font-semibold text-white">{{ selectedProduct.name }}</h2>
                <div class="flex items-center gap-3 mt-3 text-sm text-surface-400">
                    <span class="flex items-center gap-1">
                        <svg class="w-4 h-4 text-warning-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        {{ selectedProduct.rating }} ({{ formatNumber(selectedProduct.rating_count) }} ulasan)
                    </span>
                    <span>{{ formatNumber(selectedProduct.sold) }} terjual</span>
                </div>
                <div class="mt-4">
                    <span class="text-3xl font-bold text-accent-400">{{ formatPrice(selectedProduct.price) }}</span>
                    <span v-if="selectedProduct.original_price" class="text-lg text-surface-500 line-through ml-3">
                        {{ formatPrice(selectedProduct.original_price) }}
                    </span>
                </div>
                <div class="mt-6 space-y-3">
                    <div class="flex items-center gap-3 text-sm">
                        <span class="text-surface-500 w-24">Seller:</span>
                        <span class="text-white">{{ selectedProduct.seller?.name }}</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm">
                        <span class="text-surface-500 w-24">Lokasi:</span>
                        <span class="text-white">{{ selectedProduct.location }}</span>
                    </div>
                </div>
            </div>
        </div>

        <template #footer>
            <Button variant="secondary" @click="addToCollection(selectedProduct)">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                </svg>
                Simpan
            </Button>
            <Button variant="accent" @click="trackProduct(selectedProduct)">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                Lacak Harga
            </Button>
            <a
                :href="`https://shopee.co.id/product-i.${selectedProduct?.shopee_id}`"
                target="_blank"
                class="btn-primary"
            >
                Buka di Shopee
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
            </a>
        </template>
    </Modal>
</template>
