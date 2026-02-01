<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Card from '@/Components/Card.vue';
import Badge from '@/Components/Badge.vue';
import Button from '@/Components/Button.vue';

defineOptions({ layout: AppLayout });

const props = defineProps({
    plans: { type: Array, default: () => [] },
    currentPlan: { type: String, default: null },
});

// Demo plans if not provided
const plans = props.plans.length ? props.plans : [
    {
        id: 1,
        name: 'starter',
        display_name: 'Starter',
        price: 99000,
        price_formatted: 'Rp 99.000',
        search_credits: 500,
        track_slots: 20,
        api_calls: 0,
        export_quota: 3,
        features: { price_history_days: 7, check_interval_hours: 24, alerts: ['email'] },
    },
    {
        id: 2,
        name: 'pro',
        display_name: 'Pro',
        price: 299000,
        price_formatted: 'Rp 299.000',
        search_credits: 2000,
        track_slots: 100,
        api_calls: 1000,
        export_quota: 20,
        features: { price_history_days: 30, check_interval_hours: 12, alerts: ['email', 'whatsapp'], api_access: true },
    },
    {
        id: 3,
        name: 'business',
        display_name: 'Business',
        price: 799000,
        price_formatted: 'Rp 799.000',
        search_credits: 10000,
        track_slots: 500,
        api_calls: 10000,
        export_quota: 100,
        features: { price_history_days: 90, check_interval_hours: 6, alerts: ['email', 'whatsapp', 'webhook'], api_access: true, market_intelligence: true },
    },
];

const featureList = [
    { key: 'search_credits', label: 'Kredit Pencarian' },
    { key: 'track_slots', label: 'Slot Tracking' },
    { key: 'price_history_days', label: 'Riwayat Harga', suffix: ' hari' },
    { key: 'check_interval_hours', label: 'Interval Cek', suffix: ' jam' },
    { key: 'export_quota', label: 'Export/bulan' },
    { key: 'api_calls', label: 'API Calls' },
];
</script>

<template>
    <Head title="Pilih Paket" />

    <template #header>
        <div>
            <h1 class="text-2xl font-display font-bold text-white">Pilih Paket</h1>
            <p class="text-surface-400 text-sm mt-1">Upgrade untuk akses fitur lebih lengkap</p>
        </div>
    </template>

    <div class="max-w-5xl mx-auto">
        <!-- Plans grid -->
        <div class="grid md:grid-cols-3 gap-6">
            <Card
                v-for="plan in plans"
                :key="plan.id"
                :class="[
                    'relative p-6',
                    plan.name === 'pro' && 'border-primary-500 bg-primary-500/5'
                ]"
            >
                <!-- Popular badge -->
                <div v-if="plan.name === 'pro'" class="absolute -top-3 left-1/2 -translate-x-1/2">
                    <Badge variant="primary">Populer</Badge>
                </div>

                <!-- Current badge -->
                <div v-if="currentPlan === plan.name" class="absolute top-4 right-4">
                    <Badge variant="success">Aktif</Badge>
                </div>

                <h3 class="text-xl font-semibold text-white">{{ plan.display_name }}</h3>
                
                <div class="mt-4">
                    <span class="text-4xl font-display font-bold text-white">{{ plan.price_formatted }}</span>
                    <span class="text-surface-500">/bulan</span>
                </div>

                <ul class="mt-6 space-y-3">
                    <li class="flex items-center justify-between text-sm">
                        <span class="text-surface-400">Pencarian</span>
                        <span class="font-medium text-white">{{ plan.search_credits.toLocaleString() }}/bln</span>
                    </li>
                    <li class="flex items-center justify-between text-sm">
                        <span class="text-surface-400">Tracking</span>
                        <span class="font-medium text-white">{{ plan.track_slots }} produk</span>
                    </li>
                    <li class="flex items-center justify-between text-sm">
                        <span class="text-surface-400">Riwayat Harga</span>
                        <span class="font-medium text-white">{{ plan.features.price_history_days }} hari</span>
                    </li>
                    <li class="flex items-center justify-between text-sm">
                        <span class="text-surface-400">Cek Harga</span>
                        <span class="font-medium text-white">Setiap {{ plan.features.check_interval_hours }} jam</span>
                    </li>
                    <li class="flex items-center justify-between text-sm">
                        <span class="text-surface-400">Export</span>
                        <span class="font-medium text-white">{{ plan.export_quota }}/bln</span>
                    </li>
                    <li v-if="plan.api_calls > 0" class="flex items-center justify-between text-sm">
                        <span class="text-surface-400">API Calls</span>
                        <span class="font-medium text-white">{{ plan.api_calls.toLocaleString() }}/bln</span>
                    </li>
                </ul>

                <!-- Features -->
                <div class="mt-6 pt-6 border-t border-surface-800">
                    <ul class="space-y-2">
                        <li class="flex items-center gap-2 text-sm text-surface-400">
                            <svg class="w-4 h-4 text-success-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Alert Email
                        </li>
                        <li v-if="plan.features.alerts?.includes('whatsapp')" class="flex items-center gap-2 text-sm text-surface-400">
                            <svg class="w-4 h-4 text-success-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Alert WhatsApp
                        </li>
                        <li v-if="plan.features.api_access" class="flex items-center gap-2 text-sm text-surface-400">
                            <svg class="w-4 h-4 text-success-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            API Access
                        </li>
                        <li v-if="plan.features.market_intelligence" class="flex items-center gap-2 text-sm text-surface-400">
                            <svg class="w-4 h-4 text-success-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Market Intelligence
                        </li>
                    </ul>
                </div>

                <Link
                    v-if="currentPlan !== plan.name"
                    :href="`/subscription/checkout/${plan.id}`"
                    :class="[
                        'mt-6 block text-center',
                        plan.name === 'pro' ? 'btn-primary w-full' : 'btn-secondary w-full'
                    ]"
                >
                    {{ currentPlan ? 'Upgrade' : 'Pilih Paket' }}
                </Link>
                <Button v-else variant="secondary" disabled block class="mt-6">
                    Paket Aktif
                </Button>
            </Card>
        </div>

        <!-- FAQ -->
        <Card padding="md" class="mt-12">
            <h3 class="text-lg font-semibold text-white mb-4">Pertanyaan Umum</h3>
            <div class="space-y-4">
                <div>
                    <h4 class="font-medium text-white">Bisa upgrade/downgrade kapan saja?</h4>
                    <p class="text-sm text-surface-400 mt-1">Ya, Anda bisa upgrade atau downgrade paket kapan saja. Perubahan akan berlaku di periode berikutnya.</p>
                </div>
                <div>
                    <h4 class="font-medium text-white">Bagaimana sistem kredit bekerja?</h4>
                    <p class="text-sm text-surface-400 mt-1">Kredit di-reset setiap bulan sesuai paket. Kredit tidak bisa di-rollover ke bulan berikutnya.</p>
                </div>
                <div>
                    <h4 class="font-medium text-white">Metode pembayaran apa yang diterima?</h4>
                    <p class="text-sm text-surface-400 mt-1">Kami menerima transfer bank, e-wallet (GoPay, OVO, DANA), dan kartu kredit.</p>
                </div>
            </div>
        </Card>
    </div>
</template>
