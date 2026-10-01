<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import Button from '@/components/store/Button.vue';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    phone: '',
});

const submit = () => {
    form.post('/register', {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-50 px-4 sm:px-6 lg:px-8 py-12">
        <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-3xl border border-gray-200 shadow-lg">
            <div class="text-center">
                <Link href="/" class="inline-flex items-center space-x-2 mb-2">
                    <div class="w-12 h-12 bg-[#0B132B] rounded-2xl flex items-center justify-center text-orange-500 font-black text-2xl shadow">
                        FM
                    </div>
                </Link>
                <h2 class="text-2xl font-black text-gray-950 tracking-tight">Daftar Akun Baru</h2>
                <p class="text-xs text-gray-500 mt-1">Gabung bersama ribuan pemancing juara di Indonesia</p>
            </div>

            <form class="mt-8 space-y-5" @submit.prevent="submit">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Nama Lengkap</label>
                    <input
                        v-model="form.name"
                        type="text"
                        required
                        placeholder="Bambang Prakoso"
                        class="w-full px-4 py-3 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                    />
                    <div v-if="form.errors.name" class="text-xs text-red-600 mt-1">{{ form.errors.name }}</div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Email</label>
                    <input
                        v-model="form.email"
                        type="email"
                        required
                        placeholder="nama@email.com"
                        class="w-full px-4 py-3 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                    />
                    <div v-if="form.errors.email" class="text-xs text-red-600 mt-1">{{ form.errors.email }}</div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Nomor Telepon / WhatsApp</label>
                    <input
                        v-model="form.phone"
                        type="text"
                        placeholder="081234567890"
                        class="w-full px-4 py-3 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                    />
                    <div v-if="form.errors.phone" class="text-xs text-red-600 mt-1">{{ form.errors.phone }}</div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Password</label>
                    <input
                        v-model="form.password"
                        type="password"
                        required
                        placeholder="••••••••"
                        class="w-full px-4 py-3 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                    />
                    <div v-if="form.errors.password" class="text-xs text-red-600 mt-1">{{ form.errors.password }}</div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Konfirmasi Password</label>
                    <input
                        v-model="form.password_confirmation"
                        type="password"
                        required
                        placeholder="••••••••"
                        class="w-full px-4 py-3 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                    />
                </div>

                <Button
                    type="submit"
                    variant="primary"
                    size="lg"
                    class="w-full py-3.5 text-base font-bold shadow-md mt-2"
                    :disabled="form.processing"
                >
                    Daftar Akun
                </Button>

                <div class="text-center text-xs text-gray-500 pt-4 border-t">
                    Sudah punya akun?
                    <Link href="/login" class="text-orange-600 font-bold hover:underline ml-1">Masuk di sini</Link>
                </div>
            </form>
        </div>
    </div>
</template>
