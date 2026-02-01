<script setup>
import { ref } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Card from '@/Components/Card.vue';
import Badge from '@/Components/Badge.vue';
import Button from '@/Components/Button.vue';
import Modal from '@/Components/Modal.vue';

defineOptions({ layout: AppLayout });

const props = defineProps({
    alerts: { type: Array, default: () => [] },
});

const showCreateModal = ref(false);
const selectedAlert = ref(null);

// Demo data
const demoAlerts = [
    {
        id: 1,
        product: { id: 1, name: 'iPhone 15 Pro Max 256GB', price: 19999000, image: 'https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=200' },
        type: 'price_below',
        threshold: 19000000,
        is_active: true,
        notify_email: true,
        notify_whatsapp: true,
        last_triggered: '2 hari lalu',
        trigger_count: 3,
    },
    {
        id: 2,
        product: { id: 2, name: 'Samsung Galaxy S24 Ultra', price: 18500000, image: 'https://images.unsplash.com/photo-1610945415295-d9bbf067e59c?w=200' },
        type: 'price_drop_percent',
        threshold: 10,
        is_active: true,
        notify_email: true,
        notify_whatsapp: false,
        last_triggered: null,
        trigger_count: 0,
    },
    {
        id: 3,
        product: { id: 3, name: 'MacBook Air M3 256GB', price: 16499000, image: 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=200' },
        type: 'any_change',
        threshold: null,
        is_active: false,
        notify_email: true,
        notify_whatsapp: false,
        last_triggered: '1 minggu lalu',
        trigger_count: 5,
    },
];

const alerts = props.alerts.length ? props.alerts : demoAlerts;

const formatPrice = (price) => 'Rp ' + new Intl.NumberFormat('id-ID').format(price);

const alertTypeLabel = (type) => {
    switch (type) {
        case 'price_below': return 'Harga di bawah';
        case 'price_drop_percent': return 'Turun';
        case 'any_change': return 'Semua perubahan';
        default: return type;
    }
};

const toggleAlert = (alert) => {
    router.patch(`/monitoring/alerts/${alert.id}/toggle`);
};

const deleteAlert = (alert) => {
    if (confirm('Yakin ingin menghapus alert ini?')) {
        router.delete(`/monitoring/alerts/${alert.id}`);
    }
};

// Form for creating new alert
const form = ref({
    tracked_product_id: '',
    alert_type: 'price_below',
    threshold_value: '',
    notify_email: true,
    notify_whatsapp: false,
});
</script>

<template>
    <Head title="Alert Harga" />

    <template #header>
        <div>
            <h1 class="text-2xl font-display font-bold text-white">Alert Harga</h1>
            <p class="text-surface-400 text-sm mt-1">Dapatkan notifikasi saat harga berubah</p>
        </div>
    </template>

    <div class="space-y-6">
        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <Card padding="md">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-success-500/20 flex items-center justify-center">
                        <svg class="w-6 h-6 text-success-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-white">{{ alerts.filter(a => a.is_active).length }}</p>
                        <p class="text-sm text-surface-400">Alert Aktif</p>
                    </div>
                </div>
            </Card>

            <Card padding="md">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-primary-500/20 flex items-center justify-center">
                        <svg class="w-6 h-6 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-white">{{ alerts.reduce((sum, a) => sum + a.trigger_count, 0) }}</p>
                        <p class="text-sm text-surface-400">Total Triggered</p>
                    </div>
                </div>
            </Card>

            <Card padding="md" class="flex items-center justify-center">
                <Button variant="primary" @click="showCreateModal = true">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Buat Alert Baru
                </Button>
            </Card>
        </div>

        <!-- Alerts List -->
        <Card padding="none">
            <div class="p-4 border-b border-surface-800 flex items-center justify-between">
                <h3 class="font-semibold text-white">Daftar Alert</h3>
                <select class="px-3 py-1.5 bg-surface-800 border border-surface-700 rounded-lg text-sm text-surface-300">
                    <option value="all">Semua</option>
                    <option value="active">Aktif</option>
                    <option value="inactive">Nonaktif</option>
                </select>
            </div>

            <div class="divide-y divide-surface-800">
                <div
                    v-for="alert in alerts"
                    :key="alert.id"
                    class="p-4 flex items-center gap-4"
                >
                    <img :src="alert.product.image" class="w-14 h-14 rounded-xl object-cover" />
                    
                    <div class="flex-1 min-w-0">
                        <h4 class="font-medium text-white truncate">{{ alert.product.name }}</h4>
                        <div class="flex items-center gap-2 mt-1">
                            <Badge :variant="alert.is_active ? 'success' : 'warning'" size="sm">
                                {{ alert.is_active ? 'Aktif' : 'Nonaktif' }}
                            </Badge>
                            <span class="text-sm text-surface-400">
                                {{ alertTypeLabel(alert.type) }}
                                <span v-if="alert.threshold" class="text-primary-400 font-medium">
                                    {{ alert.type === 'price_drop_percent' ? `${alert.threshold}%` : formatPrice(alert.threshold) }}
                                </span>
                            </span>
                        </div>
                    </div>

                    <div class="text-right">
                        <div class="flex items-center gap-2 justify-end mb-1">
                            <span v-if="alert.notify_email" class="w-6 h-6 rounded-lg bg-surface-800 flex items-center justify-center" title="Email">
                                <svg class="w-3.5 h-3.5 text-surface-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </span>
                            <span v-if="alert.notify_whatsapp" class="w-6 h-6 rounded-lg bg-success-500/20 flex items-center justify-center" title="WhatsApp">
                                <svg class="w-3.5 h-3.5 text-success-400" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                </svg>
                            </span>
                        </div>
                        <p class="text-xs text-surface-500">
                            {{ alert.last_triggered ? `Triggered: ${alert.last_triggered}` : 'Belum pernah triggered' }}
                        </p>
                    </div>

                    <div class="flex items-center gap-2">
                        <!-- Toggle -->
                        <button
                            @click="toggleAlert(alert)"
                            :class="[
                                'relative w-11 h-6 rounded-full transition-colors',
                                alert.is_active ? 'bg-success-500' : 'bg-surface-700'
                            ]"
                        >
                            <span
                                :class="[
                                    'absolute top-1 w-4 h-4 rounded-full bg-white transition-transform',
                                    alert.is_active ? 'left-6' : 'left-1'
                                ]"
                            />
                        </button>

                        <!-- Delete -->
                        <Button variant="ghost" size="sm" icon @click="deleteAlert(alert)">
                            <svg class="w-4 h-4 text-danger-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </Button>
                    </div>
                </div>

                <!-- Empty state -->
                <div v-if="!alerts.length" class="p-12 text-center">
                    <svg class="w-16 h-16 mx-auto text-surface-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-white">Belum ada alert</h3>
                    <p class="mt-2 text-surface-500">Buat alert untuk mendapatkan notifikasi perubahan harga</p>
                    <Button variant="primary" class="mt-4" @click="showCreateModal = true">
                        Buat Alert Pertama
                    </Button>
                </div>
            </div>
        </Card>

        <!-- Alert Types Info -->
        <Card padding="md">
            <h3 class="font-semibold text-white mb-4">Jenis Alert</h3>
            <div class="grid md:grid-cols-3 gap-4">
                <div class="p-4 rounded-xl bg-surface-800/50">
                    <h4 class="font-medium text-white">Harga di Bawah</h4>
                    <p class="text-sm text-surface-400 mt-1">
                        Notifikasi saat harga turun di bawah nilai yang ditentukan
                    </p>
                </div>
                <div class="p-4 rounded-xl bg-surface-800/50">
                    <h4 class="font-medium text-white">Penurunan Persen</h4>
                    <p class="text-sm text-surface-400 mt-1">
                        Notifikasi saat harga turun sebesar persentase tertentu
                    </p>
                </div>
                <div class="p-4 rounded-xl bg-surface-800/50">
                    <h4 class="font-medium text-white">Semua Perubahan</h4>
                    <p class="text-sm text-surface-400 mt-1">
                        Notifikasi untuk setiap perubahan harga (naik/turun)
                    </p>
                </div>
            </div>
        </Card>
    </div>

    <!-- Create Alert Modal -->
    <Modal :show="showCreateModal" @close="showCreateModal = false">
        <template #header>
            <h3 class="text-lg font-semibold text-white">Buat Alert Baru</h3>
        </template>

        <div class="space-y-4">
            <div>
                <label class="label">Pilih Produk</label>
                <select v-model="form.tracked_product_id" class="input">
                    <option value="">Pilih produk yang dilacak...</option>
                    <option v-for="alert in alerts" :key="alert.id" :value="alert.product.id">
                        {{ alert.product.name }}
                    </option>
                </select>
            </div>

            <div>
                <label class="label">Jenis Alert</label>
                <select v-model="form.alert_type" class="input">
                    <option value="price_below">Harga di bawah</option>
                    <option value="price_drop_percent">Penurunan persen</option>
                    <option value="any_change">Semua perubahan</option>
                </select>
            </div>

            <div v-if="form.alert_type !== 'any_change'">
                <label class="label">
                    {{ form.alert_type === 'price_below' ? 'Harga Target (Rp)' : 'Persentase (%)' }}
                </label>
                <input
                    v-model="form.threshold_value"
                    type="number"
                    class="input"
                    :placeholder="form.alert_type === 'price_below' ? '15000000' : '10'"
                />
            </div>

            <div class="space-y-3">
                <label class="label">Kirim Notifikasi via</label>
                <label class="flex items-center gap-3 cursor-pointer">
                    <input v-model="form.notify_email" type="checkbox" class="w-4 h-4 rounded border-surface-600 bg-surface-800 text-primary-500" />
                    <span class="text-surface-300">Email</span>
                </label>
                <label class="flex items-center gap-3 cursor-pointer">
                    <input v-model="form.notify_whatsapp" type="checkbox" class="w-4 h-4 rounded border-surface-600 bg-surface-800 text-primary-500" />
                    <span class="text-surface-300">WhatsApp</span>
                    <Badge variant="primary" size="sm">Pro</Badge>
                </label>
            </div>
        </div>

        <template #footer>
            <Button variant="secondary" @click="showCreateModal = false">Batal</Button>
            <Button variant="primary">Buat Alert</Button>
        </template>
    </Modal>
</template>
