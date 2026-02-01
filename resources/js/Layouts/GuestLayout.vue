<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen auth-bg flex flex-col">
        <!-- Header -->
        <header class="p-6">
            <Link href="/" class="inline-flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary-500 to-accent-500 flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="font-display font-bold text-white text-lg">Shopee Intelligence</h1>
                </div>
            </Link>
        </header>

        <!-- Main content -->
        <main class="flex-1 flex items-center justify-center p-6">
            <div class="w-full max-w-md">
                <!-- Card -->
                <div class="bg-surface-900/80 backdrop-blur-xl border border-surface-800 rounded-3xl p-8 shadow-2xl">
                    <slot />
                </div>

                <!-- Footer links -->
                <div class="mt-8 text-center text-sm text-surface-500">
                    <slot name="footer" />
                </div>
            </div>
        </main>

        <!-- Decorative elements -->
        <div class="fixed inset-0 pointer-events-none overflow-hidden -z-10">
            <!-- Gradient orbs -->
            <div class="absolute top-1/4 -left-32 w-96 h-96 bg-primary-500/20 rounded-full blur-[100px]"></div>
            <div class="absolute bottom-1/4 -right-32 w-96 h-96 bg-accent-500/15 rounded-full blur-[100px]"></div>
            
            <!-- Grid pattern -->
            <div class="absolute inset-0 pattern-grid opacity-[0.02]"></div>
        </div>
    </div>
</template>
