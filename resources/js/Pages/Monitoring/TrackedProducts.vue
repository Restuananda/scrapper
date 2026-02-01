<script setup>
import { ref } from 'vue';
import { router, Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Card from '@/Components/Card.vue';
import Badge from '@/Components/Badge.vue';
import Button from '@/Components/Button.vue';
import Modal from '@/Components/Modal.vue';

defineOptions({ layout: AppLayout });

const props = defineProps({
    trackedProducts: { type: Array, default: () => [] },
    stats: { type: Object, default: () => ({ total: 0, limit: 20, active_alerts: 0 }) },
});

const showAddModal = ref(false);
const productUrl = ref('');
const isAdding = ref(false);

// Demo data
const demoProducts = ref([
    {
        id: 1,
        product: { id: 1, name: 'iPhone 15 Pro Max 256GB', price: 19999000, original_price: 21999000, image: 'https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=200', sold: 2500, seller: 'Apple Store' },
        check_interval: 12,
        last_checked: '2 jam yang lalu',
        price_change: { direction: 'down', percent_change: -9 },
        alerts_count: 2,
    },
    {
        id: 2,
        product: { id: 2, name: 'Samsung Galaxy S24 Ultra', price: 18500000, original_price: 19500000, image: 'https://images.unsplash.com/photo-1610945415295-d9bbf067e59c?w=200', sold: 1800, seller: 'Samsung Official' },
        check_interval: 24,
        last_checked: '5 jam yang lalu',
        price_change: { direction: 'down', percent_change: -5 },
        alerts_count: 1,
    },
]);

const trackedProducts = props.trackedProducts.length ? props.trackedProducts : demoProducts.value;

const formatPrice = (price) => 'Rp ' + new Intl.NumberFormat('id-ID').format(price);

const addProduct = () => {
    isAdding.value = true;
    router.post('/monitoring/products', {
        shopee_url: productUrl.value,
    }, {
        onFinish: () => {
            isAdding.value = false;
            showAddModal.value = false;
            productUrl.value = '';
        },
    });
};

const removeProduct = (id) => {
    if (confirm('Yakin ingin menghapus produk dari tracking?')) {
        router.delete(`/monitoring/products/${id}`);
    }
};
</script>

<template>
    <Head title="Produk Dilacak" />

    <template #header>
        <div>
            <h1 class="text-2xl font-display font-bold text-white">Produk Dilacak</h1>
            <p class="text-surface-400 text-sm mt-1">Pantau perubahan harga produk favorit Anda</p>
        </div>
    </template>

    <div class="space-y-6">
        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <Card padding="md">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-primary-500/20 flex items-center justify-center">
                        <svg class="w-6 h-6 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-white">{{ stats.total }}/{{ stats.limit }}</p>
                        <p class="text-sm text-surface-400">Produk Dilacak</p>
                    </div>
                </div>
            </Card>
            
            <Card padding="md">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-success-500/20 flex items-center justify-center">
                        <svg class="w-6 h-6 text-success-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-white">{{ stats.active_alerts }}</p>
                        <p class="text-sm text-surface-400">Alert Aktif</p>
                    </div>
                </div>
            </Card>

            <Card padding="md" class="flex items-center justify-center">
                <Button variant="primary" @click="showAddModal = true">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Tambah Produk
                </Button>
            </Card>
        </div>

        <!-- Product List -->
        <Card padding="none">
            <div class="divide-y divide-surface-800">
                <div
                    v-for="item in trackedProducts"
                    :key="item.id"
                    class="p-4 flex items-center gap-4 hover:bg-surface-800/30 transition-colors"
                >
                    <img :src="item.product.image" class="w-16 h-16 rounded-xl object-cover" />
                    
                    <div class="flex-1 min-w-0">
                        <h3 class="font-medium text-white truncate">{{ item.product.name }}</h3>
                        <p class="text-sm text-surface-500">{{ item.product.seller }}</p>
                        <div class="flex items-center gap-3 mt-1">
                            <span class="text-lg font-bold text-accent-400">{{ formatPrice(item.product.price) }}</span>
                            <span v-if="item.product.original_price" class="text-sm text-surface-500 line-through">
                                {{ formatPrice(item.product.original_price) }}
                            </span>
                        </div>
                    </div>

                    <div class="text-right">
                        <Badge 
                            v-if="item.price_change"
                            :variant="item.price_change.direction === 'down' ? 'success' : item.price_change.direction === 'up' ? 'danger' : 'primary'"
                        >
                            {{ item.price_change.percent_change >= 0 ? '+' : '' }}{{ item.price_change.percent_change }}%
                        </Badge>
                        <p class="text-xs text-surface-500 mt-2">
                            Cek: setiap {{ item.check_interval }}jam
                        </p>
                        <p class="text-xs text-surface-500">
                            {{ item.last_checked }}
                        </p>
                    </div>

                    <div class="flex items-center gap-2">
                        <Button variant="ghost" size="sm" icon>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                        </Button>
                        <Button variant="ghost" size="sm" icon @click="removeProduct(item.id)">
                            <svg class="w-4 h-4 text-danger-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </Button>
                    </div>
                </div>

                <!-- Empty state -->
                <div v-if="!trackedProducts.length" class="p-12 text-center">
                    <svg class="w-16 h-16 mx-auto text-surface-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-white">Belum ada produk</h3>
                    <p class="mt-2 text-surface-500">Tambahkan produk untuk mulai melacak harga</p>
                    <Button variant="primary" class="mt-4" @click="showAddModal = true">
                        Tambah Produk Pertama
                    </Button>
                </div>
            </div>
        </Card>
    </div>

    <!-- Add Product Modal -->
    <Modal :show="showAddModal" @close="showAddModal = false">
        <template #header>
            <h3 class="text-lg font-semibold text-white">Tambah Produk</h3>
        </template>

        <div class="space-y-4">
            <p class="text-surface-400 text-sm">
                Masukkan URL produk Shopee yang ingin Anda lacak.
            </p>
            <input
                v-model="productUrl"
                type="url"
                placeholder="https://shopee.co.id/..."
                class="input"
            />
        </div>

        <template #footer>
            <Button variant="secondary" @click="showAddModal = false">Batal</Button>
            <Button variant="primary" :loading="isAdding" @click="addProduct">
                Tambah Produk
            </Button>
        </template>
    </Modal>
</template>
