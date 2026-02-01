<script setup>
import { Link, Head } from '@inertiajs/vue3';

const features = [
    {
        icon: '🔍',
        title: 'Riset Produk',
        description: 'Temukan produk trending dan terlaris di Shopee dengan data real-time',
    },
    {
        icon: '📊',
        title: 'Monitor Harga',
        description: 'Lacak perubahan harga dan dapatkan notifikasi otomatis',
    },
    {
        icon: '📈',
        title: 'Analisis Pasar',
        description: 'Pahami tren pasar dan identifikasi peluang bisnis',
    },
    {
        icon: '🔌',
        title: 'API Akses',
        description: 'Integrasikan data Shopee ke sistem Anda via REST API',
    },
];

const plans = [
    { name: 'Starter', price: '99rb', features: ['500 pencarian/bulan', '20 produk tracking', 'Export CSV'] },
    { name: 'Pro', price: '299rb', features: ['2.000 pencarian/bulan', '100 produk tracking', 'API Access', 'WhatsApp Alert'], popular: true },
    { name: 'Business', price: '799rb', features: ['10.000 pencarian/bulan', '500 produk tracking', 'Market Intelligence', 'Priority Support'] },
];
</script>

<template>
    <Head title="Shopee Intelligence Platform - Riset & Analisis Produk Shopee" />

    <div class="min-h-screen bg-surface-950">
        <!-- Navbar -->
        <nav class="fixed top-0 left-0 right-0 z-50 bg-surface-950/80 backdrop-blur-xl border-b border-surface-800">
            <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
                <Link href="/" class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary-500 to-accent-500 flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <span class="font-display font-bold text-white text-xl">SIP</span>
                </Link>
                
                <div class="flex items-center gap-4">
                    <Link href="/login" class="text-surface-300 hover:text-white transition-colors">
                        Masuk
                    </Link>
                    <Link href="/register" class="btn-primary">
                        Daftar Gratis
                    </Link>
                </div>
            </div>
        </nav>

        <!-- Hero -->
        <section class="pt-32 pb-20 px-6 relative overflow-hidden">
            <!-- Background effects -->
            <div class="absolute inset-0 pointer-events-none">
                <div class="absolute top-1/4 -left-32 w-96 h-96 bg-primary-500/20 rounded-full blur-[100px]"></div>
                <div class="absolute bottom-1/4 -right-32 w-96 h-96 bg-accent-500/15 rounded-full blur-[100px]"></div>
            </div>

            <div class="max-w-5xl mx-auto text-center relative">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-surface-800/50 border border-surface-700 mb-8">
                    <span class="w-2 h-2 rounded-full bg-success-400 animate-pulse"></span>
                    <span class="text-sm text-surface-300">Platform Intelligence #1 untuk Seller Shopee</span>
                </div>
                
                <h1 class="text-5xl md:text-7xl font-display font-bold text-white leading-tight">
                    Riset Produk Shopee
                    <span class="text-gradient"> Lebih Cerdas</span>
                </h1>
                
                <p class="mt-6 text-xl text-surface-400 max-w-2xl mx-auto">
                    Temukan produk winning, monitor kompetitor, dan analisis pasar dengan data real-time dari Shopee Indonesia.
                </p>
                
                <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
                    <Link href="/register" class="btn-primary btn-lg">
                        Mulai Gratis 14 Hari
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </Link>
                    <a href="#features" class="btn-secondary btn-lg">
                        Pelajari Fitur
                    </a>
                </div>

                <!-- Stats -->
                <div class="mt-20 grid grid-cols-2 md:grid-cols-4 gap-8">
                    <div>
                        <p class="text-4xl font-display font-bold text-white">10jt+</p>
                        <p class="text-surface-500 mt-1">Produk Terindex</p>
                    </div>
                    <div>
                        <p class="text-4xl font-display font-bold text-white">500rb+</p>
                        <p class="text-surface-500 mt-1">Seller Dilacak</p>
                    </div>
                    <div>
                        <p class="text-4xl font-display font-bold text-white">5.000+</p>
                        <p class="text-surface-500 mt-1">User Aktif</p>
                    </div>
                    <div>
                        <p class="text-4xl font-display font-bold text-white">99.9%</p>
                        <p class="text-surface-500 mt-1">Uptime</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features -->
        <section id="features" class="py-20 px-6">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-display font-bold text-white">
                        Semua yang Anda Butuhkan
                    </h2>
                    <p class="mt-4 text-surface-400 max-w-xl mx-auto">
                        Tools lengkap untuk riset produk, analisis kompetitor, dan tracking harga.
                    </p>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div
                        v-for="feature in features"
                        :key="feature.title"
                        class="card p-6 hover:bg-surface-800/50 transition-all"
                    >
                        <div class="text-4xl mb-4">{{ feature.icon }}</div>
                        <h3 class="text-lg font-semibold text-white">{{ feature.title }}</h3>
                        <p class="mt-2 text-surface-400 text-sm">{{ feature.description }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Pricing -->
        <section id="pricing" class="py-20 px-6 bg-surface-900/50">
            <div class="max-w-5xl mx-auto">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-display font-bold text-white">
                        Harga Terjangkau
                    </h2>
                    <p class="mt-4 text-surface-400">
                        Mulai gratis, upgrade kapan saja.
                    </p>
                </div>

                <div class="grid md:grid-cols-3 gap-6">
                    <div
                        v-for="plan in plans"
                        :key="plan.name"
                        :class="[
                            'card p-6 relative',
                            plan.popular && 'border-primary-500 bg-primary-500/5'
                        ]"
                    >
                        <div v-if="plan.popular" class="absolute -top-3 left-1/2 -translate-x-1/2">
                            <span class="badge-primary">Populer</span>
                        </div>
                        
                        <h3 class="text-xl font-semibold text-white">{{ plan.name }}</h3>
                        <div class="mt-4">
                            <span class="text-4xl font-display font-bold text-white">Rp {{ plan.price }}</span>
                            <span class="text-surface-500">/bulan</span>
                        </div>
                        
                        <ul class="mt-6 space-y-3">
                            <li v-for="feature in plan.features" :key="feature" class="flex items-center gap-2 text-sm text-surface-300">
                                <svg class="w-4 h-4 text-success-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                {{ feature }}
                            </li>
                        </ul>
                        
                        <Link
                            href="/register"
                            :class="[
                                'mt-8 block text-center',
                                plan.popular ? 'btn-primary w-full' : 'btn-secondary w-full'
                            ]"
                        >
                            Mulai Sekarang
                        </Link>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA -->
        <section class="py-20 px-6">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-3xl md:text-4xl font-display font-bold text-white">
                    Siap Meningkatkan Bisnis Anda?
                </h2>
                <p class="mt-4 text-surface-400 text-lg">
                    Gabung dengan ribuan seller yang sudah menggunakan SIP untuk riset produk.
                </p>
                <Link href="/register" class="btn-accent btn-lg mt-8 inline-flex">
                    Daftar Gratis Sekarang
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </Link>
            </div>
        </section>

        <!-- Footer -->
        <footer class="border-t border-surface-800 py-12 px-6">
            <div class="max-w-6xl mx-auto flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-primary-500 to-accent-500 flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <span class="font-display font-bold text-white">Shopee Intelligence Platform</span>
                </div>
                
                <p class="text-sm text-surface-500">
                    © 2024 SIP. All rights reserved.
                </p>
            </div>
        </footer>
    </div>
</template>
