<script setup>
import { useForm, Link, Head } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import Input from '@/Components/Input.vue';
import Button from '@/Components/Button.vue';

defineOptions({ layout: GuestLayout });

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    terms: false,
});

const submit = () => {
    form.post('/register', {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Daftar" />

    <!-- Header -->
    <div class="text-center mb-8">
        <h2 class="text-2xl font-display font-bold text-white">Buat Akun Baru</h2>
        <p class="text-surface-400 mt-2">Mulai riset produk Shopee Anda sekarang</p>
    </div>

    <!-- Social login -->
    <div class="space-y-3 mb-6">
        <a
            href="/auth/google"
            class="flex items-center justify-center gap-3 w-full px-4 py-3 bg-white text-gray-800 font-medium rounded-xl hover:bg-gray-100 transition-colors"
        >
            <svg class="w-5 h-5" viewBox="0 0 24 24">
                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
            </svg>
            Daftar dengan Google
        </a>
    </div>

    <!-- Divider -->
    <div class="divider-text mb-6">
        <span>atau daftar dengan email</span>
    </div>

    <!-- Form -->
    <form @submit.prevent="submit" class="space-y-5">
        <Input
            v-model="form.name"
            type="text"
            label="Nama Lengkap"
            placeholder="John Doe"
            :error="form.errors.name"
            required
            autofocus
        />

        <Input
            v-model="form.email"
            type="email"
            label="Email"
            placeholder="nama@email.com"
            :error="form.errors.email"
            required
        />

        <Input
            v-model="form.password"
            type="password"
            label="Password"
            placeholder="Minimal 8 karakter"
            :error="form.errors.password"
            required
        />

        <Input
            v-model="form.password_confirmation"
            type="password"
            label="Konfirmasi Password"
            placeholder="Ketik ulang password"
            :error="form.errors.password_confirmation"
            required
        />

        <label class="flex items-start gap-3 cursor-pointer">
            <input
                v-model="form.terms"
                type="checkbox"
                class="mt-1 w-4 h-4 rounded border-surface-600 bg-surface-800 text-primary-500 focus:ring-primary-500/20"
            />
            <span class="text-sm text-surface-400">
                Saya setuju dengan
                <a href="/terms" class="text-primary-400 hover:text-primary-300">Syarat & Ketentuan</a>
                dan
                <a href="/privacy" class="text-primary-400 hover:text-primary-300">Kebijakan Privasi</a>
            </span>
        </label>
        <p v-if="form.errors.terms" class="form-error -mt-3">{{ form.errors.terms }}</p>

        <Button
            type="submit"
            variant="primary"
            size="lg"
            block
            :loading="form.processing"
        >
            Daftar Sekarang
        </Button>
    </form>

    <!-- Features -->
    <div class="mt-8 pt-6 border-t border-surface-800">
        <p class="text-xs text-surface-500 text-center mb-4">Apa yang Anda dapatkan:</p>
        <div class="grid grid-cols-2 gap-3 text-xs">
            <div class="flex items-center gap-2 text-surface-400">
                <svg class="w-4 h-4 text-success-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                500 kredit pencarian
            </div>
            <div class="flex items-center gap-2 text-surface-400">
                <svg class="w-4 h-4 text-success-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                20 slot tracking
            </div>
            <div class="flex items-center gap-2 text-surface-400">
                <svg class="w-4 h-4 text-success-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Trending products
            </div>
            <div class="flex items-center gap-2 text-surface-400">
                <svg class="w-4 h-4 text-success-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Export CSV
            </div>
        </div>
    </div>

    <!-- Footer -->
    <template #footer>
        <p>
            Sudah punya akun?
            <Link href="/login" class="text-primary-400 hover:text-primary-300 font-medium">
                Masuk di sini
            </Link>
        </p>
    </template>
</template>
