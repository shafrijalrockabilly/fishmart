<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import Button from '@/components/store/Button.vue';

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
    <div class="min-h-screen flex items-center justify-center bg-gray-50 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-3xl border border-gray-200 shadow-lg">
            <!-- Header Logo -->
            <div class="text-center">
                <Link href="/" class="inline-flex items-center space-x-2 mb-2">
                    <div class="w-12 h-12 bg-[#0B132B] rounded-2xl flex items-center justify-center text-orange-500 font-black text-2xl shadow">
                        FM
                    </div>
                </Link>
                <h2 class="text-2xl font-black text-gray-950 tracking-tight">Masuk ke FishMart</h2>
                <p class="text-xs text-gray-500 mt-1">Pusat Perlengkapan Memancing Terlengkap & Terpercaya</p>
            </div>

            <!-- Login Form -->
            <form class="mt-8 space-y-6" @submit.prevent="submit">
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1.5">Email</label>
                        <input
                            v-model="form.email"
                            type="email"
                            required
                            placeholder="nama@email.com"
                            class="w-full px-4 py-3 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition"
                        />
                        <div v-if="form.errors.email" class="text-xs text-red-600 mt-1 font-medium">{{ form.errors.email }}</div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-bold text-gray-700 uppercase">Password</label>
                            <a href="#" class="text-xs text-orange-600 hover:underline font-semibold">Lupa password?</a>
                        </div>
                        <input
                            v-model="form.password"
                            type="password"
                            required
                            placeholder="••••••••"
                            class="w-full px-4 py-3 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition"
                        />
                        <div v-if="form.errors.password" class="text-xs text-red-600 mt-1 font-medium">{{ form.errors.password }}</div>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center space-x-2 text-sm text-gray-700 cursor-pointer">
                        <input v-model="form.remember" type="checkbox" class="rounded text-orange-600 focus:ring-orange-500 w-4 h-4" />
                        <span>Ingat saya</span>
                    </label>
                </div>

                <Button
                    type="submit"
                    variant="primary"
                    size="lg"
                    class="w-full py-3.5 text-base font-bold shadow-md"
                    :disabled="form.processing"
                >
                    Masuk Sekarang
                </Button>

                <div class="text-center text-xs text-gray-500 pt-4 border-t">
                    Belum punya akun FishMart?
                    <Link href="/register" class="text-orange-600 font-bold hover:underline ml-1">Daftar sekarang</Link>
                </div>
            </form>
        </div>
    </div>
</template>
