<?= $this->extend('layouts/auth') ?>

<?= $this->section('title') ?>Login - Sistem Antrian Puskesmas<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div x-data="loginForm()" class="w-full max-w-md relative z-10">
    <!-- Card Container -->
    <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 p-8 md:p-10 relative overflow-hidden">
        <!-- Decorative Top Line -->
        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-primary-500 via-purple-500 to-pink-500"></div>

        <!-- Logo & Title -->
        <div class="text-center mb-10">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-primary-50 to-white rounded-2xl shadow-inner border border-white/50 mb-6 transform hover:scale-105 transition duration-300">
                <svg class="w-10 h-10 text-primary-600 drop-shadow-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <h1 class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-gray-900 to-gray-700">Sistem Antrian</h1>
            <p class="text-gray-500 mt-2 text-sm font-medium tracking-wide uppercase">Puskesmas Digital</p>
        </div>

        <!-- Login Form -->
        <div class="space-y-6">
            <div class="text-center mb-8">
                <h2 class="text-lg font-semibold text-gray-700">Selamat Dalang</h2>
                <p class="text-sm text-gray-500">Silakan login untuk melanjutkan</p>
            </div>

            <form @submit.prevent="submitLogin" class="space-y-5">
                <!-- Username -->
                <div class="group">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5 ml-1">Username</label>
                    <div class="relative transition-all duration-300 focus-within:transform focus-within:scale-[1.01]">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-gray-400 group-focus-within:text-primary-500 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </span>
                        <input type="text" x-model="form.username" required
                               class="w-full pl-11 pr-4 py-3.5 bg-gray-50/50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-primary-100 focus:border-primary-500 transition-all outline-none placeholder:text-gray-400 text-gray-700"
                               placeholder="Masukkan username anda">
                    </div>
                </div>

                <!-- Password -->
                <div class="group">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5 ml-1">Password</label>
                    <div class="relative transition-all duration-300 focus-within:transform focus-within:scale-[1.01]">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-gray-400 group-focus-within:text-primary-500 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </span>
                        <input :type="showPassword ? 'text' : 'password'" x-model="form.password" required
                               class="w-full pl-11 pr-12 py-3.5 bg-gray-50/50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-primary-100 focus:border-primary-500 transition-all outline-none placeholder:text-gray-400 text-gray-700"
                               placeholder="Masukkan password anda">
                        <button type="button" @click="showPassword = !showPassword"
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600 transition-colors p-1 rounded-r-xl">
                            <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg x-show="showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Error Message -->
                <div x-show="error" x-transition.opacity.duration.300ms class="bg-red-50/80 backdrop-blur-sm text-red-600 px-4 py-3 rounded-xl text-sm border border-red-100 flex items-start gap-2" style="display: none;">
                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span x-text="error"></span>
                </div>

                <!-- Submit Button -->
                <button type="submit" :disabled="loading"
                        class="w-full bg-gradient-to-r from-primary-600 to-primary-500 hover:from-primary-700 hover:to-primary-600 disabled:from-gray-400 disabled:to-gray-400 text-white font-bold py-3.5 px-4 rounded-xl shadow-lg shadow-primary-500/30 hover:shadow-primary-600/40 transform hover:-translate-y-0.5 transition duration-200 flex items-center justify-center gap-2">
                    <svg x-show="loading" class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24" style="display: none;">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span x-text="loading ? 'Memproses...' : 'Masuk ke Sistem'"></span>
                </button>
            </form>

            <!-- Footer -->
            <div class="pt-4 text-center text-xs text-gray-400 border-t border-gray-100">
                <p>Default Access: admin / admin123</p>
            </div>
        </div>
    </div>

    <!-- Back to Kiosk Link -->
    <div class="mt-8 text-center">
        <a href="/kiosk" class="inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-full bg-white/40 hover:bg-white/60 text-gray-700 hover:text-gray-900 backdrop-blur-sm border border-white/20 transition-all duration-300 text-sm font-medium shadow-sm hover:shadow-md group">
            <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Kiosk
        </a>
    </div>
</div>

<script>
function loginForm() {
    return {
        form: {
            username: '',
            password: ''
        },
        showPassword: false,
        loading: false,
        error: '',

        async submitLogin() {
            this.loading = true;
            this.error = '';

            try {
                const formData = new FormData();
                formData.append('username', this.form.username);
                formData.append('password', this.form.password);

                const response = await fetch('<?= base_url('auth/login') ?>', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    window.location.href = '<?= base_url('dashboard') ?>';
                } else {
                    this.error = data.message || 'Login gagal';
                }
            } catch (e) {
                this.error = 'Terjadi kesalahan. Silakan coba lagi.';
            } finally {
                this.loading = false;
            }
        }
    }
}
</script>
<?= $this->endSection() ?>
