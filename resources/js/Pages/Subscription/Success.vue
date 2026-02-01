<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Card from '@/Components/Card.vue';
import Button from '@/Components/Button.vue';

defineOptions({ layout: AppLayout });
</script>

<template>
    <Head title="Pembayaran Berhasil" />

    <div class="min-h-[60vh] flex items-center justify-center">
        <Card padding="lg" class="max-w-md w-full text-center">
            <!-- Success icon -->
            <div class="w-20 h-20 mx-auto rounded-full bg-success-500/20 flex items-center justify-center mb-6">
                <svg class="w-10 h-10 text-success-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>

            <h1 class="text-2xl font-display font-bold text-white">Pembayaran Berhasil!</h1>
            <p class="text-surface-400 mt-2">
                Terima kasih! Langganan Anda telah aktif.
            </p>

            <!-- What's next -->
            <div class="mt-8 space-y-4 text-left">
                <h3 class="text-sm font-semibold text-surface-400 uppercase tracking-wider">Langkah Selanjutnya</h3>
                
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-lg bg-primary-500/20 flex items-center justify-center flex-shrink-0">
                        <span class="text-sm font-bold text-primary-400">1</span>
                    </div>
                    <div>
                        <h4 class="font-medium text-white">Mulai Riset Produk</h4>
                        <p class="text-sm text-surface-500">Cari produk trending dan winning di Shopee</p>
                    </div>
                </div>

                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-lg bg-primary-500/20 flex items-center justify-center flex-shrink-0">
                        <span class="text-sm font-bold text-primary-400">2</span>
                    </div>
                    <div>
                        <h4 class="font-medium text-white">Lacak Produk Favorit</h4>
                        <p class="text-sm text-surface-500">Tambahkan produk ke tracking untuk pantau harga</p>
                    </div>
                </div>

                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-lg bg-primary-500/20 flex items-center justify-center flex-shrink-0">
                        <span class="text-sm font-bold text-primary-400">3</span>
                    </div>
                    <div>
                        <h4 class="font-medium text-white">Buat Alert Harga</h4>
                        <p class="text-sm text-surface-500">Dapatkan notifikasi saat harga turun</p>
                    </div>
                </div>
            </div>

            <div class="mt-8 space-y-3">
                <Link href="/dashboard" class="btn-primary w-full">
                    Ke Dashboard
                </Link>
                <Link href="/products/search" class="btn-secondary w-full">
                    Mulai Riset Produk
                </Link>
            </div>
        </Card>
    </div>
</template>
