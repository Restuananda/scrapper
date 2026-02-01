<script setup>
import { ref, onMounted } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Card from '@/Components/Card.vue';
import Button from '@/Components/Button.vue';
import Badge from '@/Components/Badge.vue';

defineOptions({ layout: AppLayout });

const props = defineProps({
    plan: { type: Object, required: true },
    snapToken: { type: String, required: true },
    orderId: { type: String, required: true },
});

const isProcessing = ref(false);
const paymentStatus = ref(null); // null, 'success', 'pending', 'error'

const formatPrice = (price) => 'Rp ' + new Intl.NumberFormat('id-ID').format(price);

// Initialize Midtrans Snap
onMounted(() => {
    // Load Midtrans Snap script
    const script = document.createElement('script');
    script.src = 'https://app.sandbox.midtrans.com/snap/snap.js';
    script.setAttribute('data-client-key', 'YOUR_CLIENT_KEY'); // Will be replaced by env
    document.head.appendChild(script);
});

const processPayment = () => {
    isProcessing.value = true;
    
    // Open Midtrans Snap popup
    window.snap.pay(props.snapToken, {
        onSuccess: function(result) {
            paymentStatus.value = 'success';
            isProcessing.value = false;
            // Redirect to success page
            setTimeout(() => {
                router.visit('/subscription/success');
            }, 2000);
        },
        onPending: function(result) {
            paymentStatus.value = 'pending';
            isProcessing.value = false;
        },
        onError: function(result) {
            paymentStatus.value = 'error';
            isProcessing.value = false;
        },
        onClose: function() {
            isProcessing.value = false;
        }
    });
};
</script>

<template>
    <Head title="Checkout" />

    <template #header>
        <div>
            <h1 class="text-2xl font-display font-bold text-white">Checkout</h1>
            <p class="text-surface-400 text-sm mt-1">Selesaikan pembayaran untuk mengaktifkan paket</p>
        </div>
    </template>

    <div class="max-w-2xl mx-auto space-y-6">
        <!-- Order Summary -->
        <Card padding="none">
            <div class="p-6 border-b border-surface-800">
                <h3 class="text-lg font-semibold text-white">Ringkasan Pesanan</h3>
            </div>
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h4 class="text-xl font-bold text-white">{{ plan.name }}</h4>
                        <p class="text-surface-400 mt-1">Langganan bulanan</p>
                    </div>
                    <Badge variant="primary">1 Bulan</Badge>
                </div>

                <!-- Features -->
                <div class="space-y-3 mb-6">
                    <div class="flex items-center gap-2 text-sm text-surface-300">
                        <svg class="w-4 h-4 text-success-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        {{ plan.features?.search_credits?.toLocaleString() || '2,000' }} kredit pencarian
                    </div>
                    <div class="flex items-center gap-2 text-sm text-surface-300">
                        <svg class="w-4 h-4 text-success-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        {{ plan.features?.track_slots || 100 }} slot tracking
                    </div>
                    <div class="flex items-center gap-2 text-sm text-surface-300">
                        <svg class="w-4 h-4 text-success-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Alert Email & WhatsApp
                    </div>
                </div>

                <!-- Price breakdown -->
                <div class="border-t border-surface-800 pt-4 space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-surface-400">Subtotal</span>
                        <span class="text-white">{{ plan.price_formatted }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-surface-400">PPN (11%)</span>
                        <span class="text-white">Termasuk</span>
                    </div>
                    <div class="flex justify-between text-lg font-bold pt-3 border-t border-surface-800">
                        <span class="text-white">Total</span>
                        <span class="text-accent-400">{{ plan.price_formatted }}</span>
                    </div>
                </div>
            </div>
        </Card>

        <!-- Payment Methods Info -->
        <Card padding="md">
            <h3 class="font-semibold text-white mb-4">Metode Pembayaran</h3>
            <div class="grid grid-cols-4 gap-4">
                <div class="p-3 bg-surface-800 rounded-xl flex items-center justify-center">
                    <span class="text-xs text-surface-400">BCA</span>
                </div>
                <div class="p-3 bg-surface-800 rounded-xl flex items-center justify-center">
                    <span class="text-xs text-surface-400">Mandiri</span>
                </div>
                <div class="p-3 bg-surface-800 rounded-xl flex items-center justify-center">
                    <span class="text-xs text-surface-400">GoPay</span>
                </div>
                <div class="p-3 bg-surface-800 rounded-xl flex items-center justify-center">
                    <span class="text-xs text-surface-400">OVO</span>
                </div>
            </div>
            <p class="text-xs text-surface-500 mt-4">
                Pembayaran diproses secara aman oleh Midtrans.
            </p>
        </Card>

        <!-- Payment Status -->
        <Card v-if="paymentStatus" padding="md" :class="{
            'border-success-500 bg-success-500/10': paymentStatus === 'success',
            'border-warning-500 bg-warning-500/10': paymentStatus === 'pending',
            'border-danger-500 bg-danger-500/10': paymentStatus === 'error',
        }">
            <div class="flex items-center gap-4">
                <div v-if="paymentStatus === 'success'" class="w-12 h-12 rounded-full bg-success-500/20 flex items-center justify-center">
                    <svg class="w-6 h-6 text-success-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <div v-else-if="paymentStatus === 'pending'" class="w-12 h-12 rounded-full bg-warning-500/20 flex items-center justify-center">
                    <svg class="w-6 h-6 text-warning-400 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div v-else class="w-12 h-12 rounded-full bg-danger-500/20 flex items-center justify-center">
                    <svg class="w-6 h-6 text-danger-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
                <div>
                    <h4 class="font-semibold text-white">
                        {{ paymentStatus === 'success' ? 'Pembayaran Berhasil!' : 
                           paymentStatus === 'pending' ? 'Menunggu Pembayaran' : 'Pembayaran Gagal' }}
                    </h4>
                    <p class="text-sm text-surface-400">
                        {{ paymentStatus === 'success' ? 'Mengalihkan ke dashboard...' : 
                           paymentStatus === 'pending' ? 'Selesaikan pembayaran Anda' : 'Silakan coba lagi' }}
                    </p>
                </div>
            </div>
        </Card>

        <!-- Pay Button -->
        <Button
            variant="accent"
            size="lg"
            block
            :loading="isProcessing"
            :disabled="paymentStatus === 'success'"
            @click="processPayment"
        >
            <svg v-if="!isProcessing" class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            {{ isProcessing ? 'Memproses...' : `Bayar ${plan.price_formatted}` }}
        </Button>

        <!-- Terms -->
        <p class="text-xs text-surface-500 text-center">
            Dengan melanjutkan, Anda menyetujui 
            <a href="/terms" class="text-primary-400 hover:underline">Syarat & Ketentuan</a>
            dan
            <a href="/privacy" class="text-primary-400 hover:underline">Kebijakan Privasi</a>
            kami.
        </p>
    </div>
</template>
