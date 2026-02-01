<script setup>
import { ref, computed } from 'vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Card from '@/Components/Card.vue';
import Button from '@/Components/Button.vue';
import Input from '@/Components/Input.vue';
import Avatar from '@/Components/Avatar.vue';

defineOptions({ layout: AppLayout });

const page = usePage();
const user = computed(() => page.props.auth?.user || {
    name: 'John Doe',
    email: 'john@example.com',
    phone: '08123456789',
    company: 'PT Example',
    avatar: null,
});

// Profile form
const profileForm = useForm({
    name: user.value.name,
    email: user.value.email,
    phone: user.value.phone || '',
    company: user.value.company || '',
});

// Password form
const passwordForm = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updateProfile = () => {
    profileForm.put('/settings/profile', {
        preserveScroll: true,
        onSuccess: () => {
            // Show success message
        },
    });
};

const updatePassword = () => {
    passwordForm.put('/settings/password', {
        preserveScroll: true,
        onSuccess: () => {
            passwordForm.reset();
        },
    });
};

const deleteAccount = () => {
    if (confirm('Apakah Anda yakin ingin menghapus akun? Tindakan ini tidak dapat dibatalkan.')) {
        // Delete account
    }
};
</script>

<template>
    <Head title="Profil" />

    <template #header>
        <div>
            <h1 class="text-2xl font-display font-bold text-white">Pengaturan Profil</h1>
            <p class="text-surface-400 text-sm mt-1">Kelola informasi akun Anda</p>
        </div>
    </template>

    <div class="max-w-2xl space-y-6">
        <!-- Profile Information -->
        <Card padding="none">
            <div class="p-6 border-b border-surface-800">
                <h3 class="text-lg font-semibold text-white">Informasi Profil</h3>
                <p class="text-surface-500 text-sm">Update informasi profil dan email Anda</p>
            </div>
            <form @submit.prevent="updateProfile" class="p-6 space-y-6">
                <!-- Avatar -->
                <div class="flex items-center gap-6">
                    <Avatar :name="user.name" :src="user.avatar" size="xl" />
                    <div>
                        <Button type="button" variant="secondary" size="sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Ganti Foto
                        </Button>
                        <p class="text-xs text-surface-500 mt-2">JPG, PNG max 2MB</p>
                    </div>
                </div>

                <Input
                    v-model="profileForm.name"
                    label="Nama Lengkap"
                    type="text"
                    :error="profileForm.errors.name"
                    required
                />

                <Input
                    v-model="profileForm.email"
                    label="Email"
                    type="email"
                    :error="profileForm.errors.email"
                    required
                />

                <Input
                    v-model="profileForm.phone"
                    label="Nomor Telepon"
                    type="tel"
                    placeholder="08123456789"
                    :error="profileForm.errors.phone"
                    hint="Untuk notifikasi WhatsApp"
                />

                <Input
                    v-model="profileForm.company"
                    label="Nama Perusahaan"
                    type="text"
                    placeholder="PT Example"
                    :error="profileForm.errors.company"
                />

                <div class="flex justify-end">
                    <Button type="submit" variant="primary" :loading="profileForm.processing">
                        Simpan Perubahan
                    </Button>
                </div>
            </form>
        </Card>

        <!-- Update Password -->
        <Card padding="none">
            <div class="p-6 border-b border-surface-800">
                <h3 class="text-lg font-semibold text-white">Ubah Password</h3>
                <p class="text-surface-500 text-sm">Pastikan menggunakan password yang kuat</p>
            </div>
            <form @submit.prevent="updatePassword" class="p-6 space-y-6">
                <Input
                    v-model="passwordForm.current_password"
                    label="Password Saat Ini"
                    type="password"
                    :error="passwordForm.errors.current_password"
                    required
                />

                <Input
                    v-model="passwordForm.password"
                    label="Password Baru"
                    type="password"
                    :error="passwordForm.errors.password"
                    hint="Minimal 8 karakter"
                    required
                />

                <Input
                    v-model="passwordForm.password_confirmation"
                    label="Konfirmasi Password Baru"
                    type="password"
                    :error="passwordForm.errors.password_confirmation"
                    required
                />

                <div class="flex justify-end">
                    <Button type="submit" variant="primary" :loading="passwordForm.processing">
                        Update Password
                    </Button>
                </div>
            </form>
        </Card>

        <!-- Notifications -->
        <Card padding="none">
            <div class="p-6 border-b border-surface-800">
                <h3 class="text-lg font-semibold text-white">Preferensi Notifikasi</h3>
                <p class="text-surface-500 text-sm">Pilih bagaimana Anda ingin menerima notifikasi</p>
            </div>
            <div class="p-6 space-y-4">
                <label class="flex items-center justify-between p-4 rounded-xl bg-surface-800/50 cursor-pointer">
                    <div>
                        <h4 class="font-medium text-white">Email Notifikasi</h4>
                        <p class="text-sm text-surface-500">Terima alert harga via email</p>
                    </div>
                    <input type="checkbox" checked class="w-5 h-5 rounded border-surface-600 bg-surface-800 text-primary-500" />
                </label>

                <label class="flex items-center justify-between p-4 rounded-xl bg-surface-800/50 cursor-pointer">
                    <div>
                        <h4 class="font-medium text-white">WhatsApp Notifikasi</h4>
                        <p class="text-sm text-surface-500">Terima alert harga via WhatsApp</p>
                    </div>
                    <input type="checkbox" class="w-5 h-5 rounded border-surface-600 bg-surface-800 text-primary-500" />
                </label>

                <label class="flex items-center justify-between p-4 rounded-xl bg-surface-800/50 cursor-pointer">
                    <div>
                        <h4 class="font-medium text-white">Newsletter</h4>
                        <p class="text-sm text-surface-500">Tips dan update fitur baru</p>
                    </div>
                    <input type="checkbox" checked class="w-5 h-5 rounded border-surface-600 bg-surface-800 text-primary-500" />
                </label>
            </div>
        </Card>

        <!-- Danger Zone -->
        <Card padding="none" class="border-danger-500/50">
            <div class="p-6 border-b border-surface-800">
                <h3 class="text-lg font-semibold text-danger-400">Zona Berbahaya</h3>
                <p class="text-surface-500 text-sm">Tindakan permanen yang tidak dapat dibatalkan</p>
            </div>
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="font-medium text-white">Hapus Akun</h4>
                        <p class="text-sm text-surface-500">
                            Semua data Anda akan dihapus secara permanen
                        </p>
                    </div>
                    <Button variant="danger" @click="deleteAccount">
                        Hapus Akun
                    </Button>
                </div>
            </div>
        </Card>
    </div>
</template>
