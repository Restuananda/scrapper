<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Card from '@/Components/Card.vue';
import Button from '@/Components/Button.vue';
import Badge from '@/Components/Badge.vue';
import Modal from '@/Components/Modal.vue';

defineOptions({ layout: AppLayout });

const props = defineProps({
    subscription: { type: Object, default: null },
    credits: { type: Object, default: () => ({ search: 0, track: 0, api: 0, export: 0 }) },
    transactions: { type: Array, default: () => [] },
});

const showCancelModal = ref(false);
const isCancelling = ref(false);

// Demo data
const subscription = props.subscription || {
    plan: 'Pro',
    status: 'active',
    current_period_end: '15 Feb 2025',
    is_cancelled: false,
};

const credits = props.credits || {
    search: 1547,
    track: 85,
    api: 750,
    export: 15,
};

const transactions = props.transactions.length ? props.transactions : [
    { date: '01 Jan 2025', description: 'Langganan Pro - Januari', amount: -299000 },
    { date: '01 Des 2024', description: 'Langganan Pro - Desember', amount: -299000 },
    { date: '15 Nov 2024', description: 'Upgrade ke Pro', amount: -299000 },
];

const formatPrice = (amount) => {
    const formatted = new Intl.NumberFormat('id-ID').format(Math.abs(amount));
    return amount < 0 ? `-Rp ${formatted}` : `Rp ${formatted}`;
};

const cancelSubscription = () => {
    isCancelling.value = true;
    router.post('/subscription/cancel', {}, {
        onFinish: () => {
            isCancelling.value = false;
            showCancelModal.value = false;
        }
    });
};
</script>

<template>
    <Head title="Billing & Langganan" />

    <template #header>
        <div>
            <h1 class="text-2xl font-display font-bold text-white">Billing & Langganan</h1>
            <p class="text-surface-400 text-sm mt-1">Kelola langganan dan lihat penggunaan kredit</p>
        </div>
    </template>

    <div class="max-w-4xl space-y-6">
        <!-- Current Plan -->
        <Card padding="none">
            <div class="p-6 border-b border-surface-800 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-white">Paket Saat Ini</h3>
                    <p class="text-surface-500 text-sm">Kelola langganan Anda</p>
                </div>
                <Badge :variant="subscription?.status === 'active' ? 'success' : 'warning'">
                    {{ subscription?.status === 'active' ? 'Aktif' : subscription?.status }}
                </Badge>
            </div>
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="text-2xl font-bold text-white">{{ subscription?.plan || 'Free' }}</h4>
                        <p v-if="subscription" class="text-surface-400 mt-1">
                            Berlaku hingga {{ subscription.current_period_end }}
                        </p>
                        <p v-else class="text-surface-400 mt-1">
                            Anda belum berlangganan
                        </p>
                    </div>
                    <div class="flex gap-3">
                        <Link href="/subscription/plans" class="btn-primary">
                            {{ subscription ? 'Upgrade Paket' : 'Lihat Paket' }}
                        </Link>
                        <Button 
                            v-if="subscription && !subscription.is_cancelled" 
                            variant="ghost" 
                            @click="showCancelModal = true"
                        >
                            Batalkan
                        </Button>
                    </div>
                </div>

                <div v-if="subscription?.is_cancelled" class="mt-4 p-4 rounded-xl bg-warning-500/10 border border-warning-500/30">
                    <p class="text-warning-400 text-sm">
                        ⚠️ Langganan Anda akan berakhir pada {{ subscription.current_period_end }}. 
                        Anda masih bisa menggunakan semua fitur hingga tanggal tersebut.
                    </p>
                </div>
            </div>
        </Card>

        <!-- Credit Usage -->
        <Card padding="none">
            <div class="p-6 border-b border-surface-800">
                <h3 class="text-lg font-semibold text-white">Penggunaan Kredit</h3>
                <p class="text-surface-500 text-sm">Kredit tersisa bulan ini</p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <!-- Search Credits -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-surface-400">Pencarian</span>
                            <span class="text-sm font-medium text-white">{{ credits.search.toLocaleString() }}</span>
                        </div>
                        <div class="h-2 bg-surface-800 rounded-full overflow-hidden">
                            <div 
                                class="h-full bg-gradient-to-r from-primary-500 to-primary-400 rounded-full"
                                :style="{ width: `${(credits.search / 2000) * 100}%` }"
                            />
                        </div>
                        <p class="text-xs text-surface-500 mt-1">dari 2,000</p>
                    </div>

                    <!-- Track Slots -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-surface-400">Tracking</span>
                            <span class="text-sm font-medium text-white">{{ credits.track }}</span>
                        </div>
                        <div class="h-2 bg-surface-800 rounded-full overflow-hidden">
                            <div 
                                class="h-full bg-gradient-to-r from-accent-500 to-accent-400 rounded-full"
                                :style="{ width: `${(credits.track / 100) * 100}%` }"
                            />
                        </div>
                        <p class="text-xs text-surface-500 mt-1">dari 100</p>
                    </div>

                    <!-- API Calls -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-surface-400">API Calls</span>
                            <span class="text-sm font-medium text-white">{{ credits.api.toLocaleString() }}</span>
                        </div>
                        <div class="h-2 bg-surface-800 rounded-full overflow-hidden">
                            <div 
                                class="h-full bg-gradient-to-r from-success-500 to-success-400 rounded-full"
                                :style="{ width: `${(credits.api / 1000) * 100}%` }"
                            />
                        </div>
                        <p class="text-xs text-surface-500 mt-1">dari 1,000</p>
                    </div>

                    <!-- Export -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-surface-400">Export</span>
                            <span class="text-sm font-medium text-white">{{ credits.export }}</span>
                        </div>
                        <div class="h-2 bg-surface-800 rounded-full overflow-hidden">
                            <div 
                                class="h-full bg-gradient-to-r from-warning-500 to-warning-400 rounded-full"
                                :style="{ width: `${(credits.export / 20) * 100}%` }"
                            />
                        </div>
                        <p class="text-xs text-surface-500 mt-1">dari 20</p>
                    </div>
                </div>

                <p class="text-xs text-surface-500 mt-6">
                    * Kredit akan di-reset setiap awal periode langganan
                </p>
            </div>
        </Card>

        <!-- Transaction History -->
        <Card padding="none">
            <div class="p-6 border-b border-surface-800">
                <h3 class="text-lg font-semibold text-white">Riwayat Transaksi</h3>
            </div>
            <div class="divide-y divide-surface-800">
                <div
                    v-for="(tx, index) in transactions"
                    :key="index"
                    class="p-4 flex items-center justify-between"
                >
                    <div>
                        <p class="font-medium text-white">{{ tx.description }}</p>
                        <p class="text-sm text-surface-500">{{ tx.date }}</p>
                    </div>
                    <span :class="tx.amount < 0 ? 'text-white' : 'text-success-400'" class="font-medium">
                        {{ formatPrice(tx.amount) }}
                    </span>
                </div>

                <div v-if="!transactions.length" class="p-8 text-center text-surface-500">
                    Belum ada transaksi
                </div>
            </div>
        </Card>

        <!-- Payment Method -->
        <Card padding="md">
            <h3 class="font-semibold text-white mb-4">Metode Pembayaran</h3>
            <p class="text-surface-400 text-sm">
                Pembayaran dilakukan melalui Midtrans. Anda bisa memilih metode pembayaran 
                (transfer bank, e-wallet, kartu kredit) saat checkout.
            </p>
        </Card>
    </div>

    <!-- Cancel Modal -->
    <Modal :show="showCancelModal" @close="showCancelModal = false">
        <template #header>
            <h3 class="text-lg font-semibold text-white">Batalkan Langganan</h3>
        </template>

        <div class="space-y-4">
            <p class="text-surface-300">
                Apakah Anda yakin ingin membatalkan langganan?
            </p>
            <div class="p-4 rounded-xl bg-warning-500/10 border border-warning-500/30">
                <ul class="text-sm text-warning-300 space-y-2">
                    <li>• Anda masih bisa menggunakan fitur hingga akhir periode</li>
                    <li>• Kredit yang tersisa tidak bisa di-refund</li>
                    <li>• Anda bisa berlangganan kembali kapan saja</li>
                </ul>
            </div>
        </div>

        <template #footer>
            <Button variant="secondary" @click="showCancelModal = false">
                Tidak, Tetap Berlangganan
            </Button>
            <Button variant="danger" :loading="isCancelling" @click="cancelSubscription">
                Ya, Batalkan
            </Button>
        </template>
    </Modal>
</template>
