<script setup>
import { ref, computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import Dropdown from '@/Components/Dropdown.vue';
import Avatar from '@/Components/Avatar.vue';

const page = usePage();
const user = computed(() => page.props.auth?.user);
const credits = computed(() => page.props.auth?.credits || {});

const sidebarOpen = ref(true);

// Navigation items
const navigation = [
    { 
        name: 'Dashboard', 
        href: '/dashboard', 
        icon: 'dashboard',
        current: () => route().current('dashboard')
    },
    { 
        name: 'Riset Produk', 
        href: '/products', 
        icon: 'search',
        current: () => route().current('products.*'),
        children: [
            { name: 'Pencarian', href: '/products/search' },
            { name: 'Trending', href: '/products/trending' },
            { name: 'Kategori', href: '/products/categories' },
        ]
    },
    { 
        name: 'Monitoring', 
        href: '/monitoring', 
        icon: 'chart',
        current: () => route().current('monitoring.*'),
        badge: credits.track_slots,
        children: [
            { name: 'Produk Dilacak', href: '/monitoring/products' },
            { name: 'Riwayat Harga', href: '/monitoring/history' },
            { name: 'Alert', href: '/monitoring/alerts' },
        ]
    },
    { 
        name: 'Market Intel', 
        href: '/intelligence', 
        icon: 'intel',
        current: () => route().current('intelligence.*'),
        children: [
            { name: 'Analisis Kategori', href: '/intelligence/categories' },
            { name: 'Top Sellers', href: '/intelligence/sellers' },
            { name: 'Brand Tracker', href: '/intelligence/brands' },
        ]
    },
    { 
        name: 'API', 
        href: '/api', 
        icon: 'code',
        current: () => route().current('api.*'),
    },
    { 
        name: 'Laporan', 
        href: '/reports', 
        icon: 'document',
        current: () => route().current('reports.*'),
    },
];

const bottomNav = [
    { name: 'Pengaturan', href: '/settings', icon: 'settings' },
    { name: 'Bantuan', href: '/help', icon: 'help' },
];

// Icon components (inline SVG for performance)
const icons = {
    dashboard: `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>`,
    search: `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>`,
    chart: `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>`,
    intel: `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>`,
    code: `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>`,
    document: `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>`,
    settings: `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>`,
    help: `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>`,
};
</script>

<template>
    <div class="min-h-screen bg-surface-950">
        <!-- Sidebar -->
        <aside class="sidebar">
            <!-- Logo -->
            <div class="p-6 border-b border-surface-800">
                <Link href="/dashboard" class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary-500 to-accent-500 flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="font-display font-bold text-white">SIP</h1>
                        <p class="text-[10px] text-surface-500 -mt-0.5">Shopee Intelligence</p>
                    </div>
                </Link>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 p-4 space-y-1 overflow-y-auto no-scrollbar">
                <template v-for="item in navigation" :key="item.name">
                    <Link
                        :href="item.href"
                        :class="[
                            item.current?.() ? 'nav-item-active' : 'nav-item'
                        ]"
                    >
                        <span v-html="icons[item.icon]" />
                        <span class="flex-1">{{ item.name }}</span>
                        <span v-if="item.badge" class="badge-primary text-[10px] px-1.5 py-0.5">
                            {{ item.badge }}
                        </span>
                    </Link>
                </template>
            </nav>

            <!-- Bottom nav -->
            <div class="p-4 border-t border-surface-800 space-y-1">
                <template v-for="item in bottomNav" :key="item.name">
                    <Link :href="item.href" class="nav-item">
                        <span v-html="icons[item.icon]" />
                        <span>{{ item.name }}</span>
                    </Link>
                </template>
            </div>

            <!-- User section -->
            <div class="p-4 border-t border-surface-800">
                <Dropdown align="left" width="60">
                    <template #trigger>
                        <button class="w-full flex items-center gap-3 p-2 rounded-xl hover:bg-surface-800/50 transition-colors">
                            <Avatar :name="user?.name" size="md" status="online" />
                            <div class="flex-1 text-left">
                                <p class="text-sm font-medium text-white truncate">{{ user?.name }}</p>
                                <p class="text-xs text-surface-500">{{ user?.email }}</p>
                            </div>
                            <svg class="w-4 h-4 text-surface-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/>
                            </svg>
                        </button>
                    </template>
                    <template #content>
                        <div class="px-4 py-3 border-b border-surface-800">
                            <p class="text-xs text-surface-500">Kredit Tersisa</p>
                            <p class="text-lg font-bold text-white">{{ credits.search || 0 }}</p>
                        </div>
                        <Link href="/settings/profile" class="dropdown-item">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Profil Saya
                        </Link>
                        <Link href="/settings/subscription" class="dropdown-item">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                            Langganan
                        </Link>
                        <div class="border-t border-surface-800 mt-2 pt-2">
                            <Link href="/logout" method="post" as="button" class="dropdown-item w-full text-danger-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                Keluar
                            </Link>
                        </div>
                    </template>
                </Dropdown>
            </div>
        </aside>

        <!-- Main content -->
        <main class="main-content">
            <!-- Page header -->
            <header class="page-header flex items-center justify-between">
                <div>
                    <slot name="header" />
                </div>
                
                <!-- Quick actions -->
                <div class="flex items-center gap-4">
                    <!-- Search -->
                    <div class="relative">
                        <input
                            type="text"
                            placeholder="Cari produk..."
                            class="w-64 pl-10 pr-4 py-2 bg-surface-800/50 border border-surface-700 rounded-xl text-sm
                                   placeholder-surface-500 focus:border-primary-500 focus:ring-1 focus:ring-primary-500/20"
                        />
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-surface-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>

                    <!-- Notifications -->
                    <button class="relative p-2 text-surface-400 hover:text-white hover:bg-surface-800 rounded-xl transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <span class="absolute top-1 right-1 w-2 h-2 bg-accent-500 rounded-full"></span>
                    </button>
                </div>
            </header>

            <!-- Page content -->
            <div class="p-8">
                <slot />
            </div>
        </main>
    </div>
</template>
