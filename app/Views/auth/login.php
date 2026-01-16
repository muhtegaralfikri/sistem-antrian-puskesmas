<?= $this->extend('layouts/auth') ?>

<?= $this->section('title') ?>Login - Sistem Antrian Puskesmas<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div x-data="loginForm()" class="w-full max-w-md">
    <!-- Logo & Title -->
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-white rounded-2xl shadow-lg mb-4">
            <svg class="w-10 h-10 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-white">Sistem Antrian</h1>
        <p class="text-primary-200 mt-1">Puskesmas</p>
    </div>

    <!-- Login Form -->
    <div class="bg-white rounded-2xl shadow-2xl p-8">
        <h2 class="text-xl font-semibold text-gray-800 mb-6">Login Petugas</h2>

        <form @submit.prevent="submitLogin" class="space-y-5">
            <!-- Username -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </span>
                    <input type="text" x-model="form.username" required
                           class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition"
                           placeholder="Masukkan username">
                </div>
            </div>

            <!-- Password -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </span>
                    <input :type="showPassword ? 'text' : 'password'" x-model="form.password" required
                           class="w-full pl-10 pr-12 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition"
                           placeholder="Masukkan password">
                    <button type="button" @click="showPassword = !showPassword"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
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
            <div x-show="error" x-transition class="bg-red-50 text-red-600 px-4 py-3 rounded-lg text-sm" style="display: none;">
                <span x-text="error"></span>
            </div>

            <!-- Submit Button -->
            <button type="submit" :disabled="loading"
                    class="w-full bg-primary-600 hover:bg-primary-700 disabled:bg-gray-400 text-white font-semibold py-3 px-4 rounded-lg transition duration-200 flex items-center justify-center gap-2">
                <svg x-show="loading" class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24" style="display: none;">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span x-text="loading ? 'Memproses...' : 'Login'"></span>
            </button>
        </form>

        <!-- Footer -->
        <div class="mt-6 text-center text-sm text-gray-500">
            <p>Default: admin / admin123</p>
        </div>
    </div>

    <!-- Back to Kiosk Link -->
    <div class="mt-6 text-center">
        <a href="/kiosk" class="text-white/80 hover:text-white text-sm flex items-center justify-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
