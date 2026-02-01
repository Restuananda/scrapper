<script setup>
import { ref } from 'vue';
import { useForm, Link, Head } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import Input from '@/Components/Input.vue';
import Button from '@/Components/Button.vue';

defineOptions({ layout: GuestLayout });

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post('/login', {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Masuk" />

    <!-- Header -->
    <div class="text-center mb-8">
        <h2 class="text-2xl font-display font-bold text-white">Selamat Datang Kembali</h2>
        <p class="text-surface-400 mt-2">Masuk ke akun Anda untuk melanjutkan</p>
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
            Lanjutkan dengan Google
        </a>
    </div>

    <!-- Divider -->
    <div class="divider-text mb-6">
        <span>atau masuk dengan email</span>
    </div>

    <!-- Form -->
    <form @submit.prevent="submit" class="space-y-5">
        <Input
            v-model="form.email"
            type="email"
            label="Email"
            placeholder="nama@email.com"
            :error="form.errors.email"
            required
            autofocus
        />

        <Input
            v-model="form.password"
            type="password"
            label="Password"
            placeholder="••••••••"
            :error="form.errors.password"
            required
        />

        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 cursor-pointer">
                <input
                    v-model="form.remember"
                    type="checkbox"
                    class="w-4 h-4 rounded border-surface-600 bg-surface-800 text-primary-500 focus:ring-primary-500/20"
                />
                <span class="text-sm text-surface-400">Ingat saya</span>
            </label>

            <Link href="/forgot-password" class="text-sm text-primary-400 hover:text-primary-300">
                Lupa password?
            </Link>
        </div>

        <Button
            type="submit"
            variant="primary"
            size="lg"
            block
            :loading="form.processing"
        >
            Masuk
        </Button>
    </form>

    <!-- Footer -->
    <template #footer>
        <p>
            Belum punya akun?
            <Link href="/register" class="text-primary-400 hover:text-primary-300 font-medium">
                Daftar sekarang
            </Link>
        </p>
    </template>
</template>
