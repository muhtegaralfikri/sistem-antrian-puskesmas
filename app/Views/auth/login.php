<?= $this->extend('layouts/auth') ?>

<?= $this->section('title') ?>Login - Sistem Antrian Puskesmas<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div x-data="loginForm()" class="min-h-screen w-full flex items-center justify-center p-4 relative bg-slate-50 selection:bg-medical-500 selection:text-white overflow-y-auto">
    
    <!-- Dynamic Aurora Background -->
    <div class="fixed inset-0 w-full h-full z-0 pointer-events-none overflow-hidden">
        <div class="absolute top-[-10%] left-[-10%] w-64 h-64 md:w-96 md:h-96 bg-purple-300 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob"></div>
        <div class="absolute top-[-10%] right-[-10%] w-64 h-64 md:w-96 md:h-96 bg-medical-300 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob animation-delay-2000"></div>
        <div class="absolute bottom-[-20%] left-[20%] w-64 h-64 md:w-96 md:h-96 bg-pink-300 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob animation-delay-4000"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-64 h-64 md:w-96 md:h-96 bg-blue-300 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob animation-delay-2000"></div>
        
        <!-- Grid Pattern Overlay -->
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMSIgY3k9IjEiIHI9IjEiIGZpbGw9InJnYmEoMTQ4LCAxNjMsIDE4NCwgMC4xKSIvPjwvc3ZnPg==')] [mask-image:linear-gradient(to_bottom,white,transparent)]"></div>
    </div>

    <!-- Main Card -->
    <div class="w-full max-w-[440px] relative z-10 transition-all duration-500 my-auto">
        <div class="bg-white/70 backdrop-blur-2xl rounded-3xl md:rounded-[2.5rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] ring-1 ring-white/60 p-6 md:p-12 relative overflow-hidden">
            
            <!-- Glossy Shine Effect -->
            <div class="absolute inset-0 bg-gradient-to-br from-white/40 to-transparent pointer-events-none"></div>

            <!-- Content -->
            <div class="relative z-10">
                <!-- Logo Zone -->
                <div class="text-center mb-8 md:mb-10">
                    <div class="inline-flex items-center justify-center mb-6 transform hover:scale-105 transition-transform duration-300">
                        <img src="/images/logo.png" alt="Logo Puskesmas" class="h-16 md:h-20 w-auto drop-shadow-lg">
                    </div>
                    <h1 class="text-xl md:text-2xl font-bold text-slate-800 tracking-tight">Selamat Datang</h1>
                    <p class="text-slate-500 text-sm mt-2 font-medium">Sistem Antrian Puskesmas Digital</p>
                </div>

                <!-- Form -->
                <form @submit.prevent="submitLogin" class="space-y-4 md:space-y-5">
                    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">

                    <!-- Username -->
                    <div class="group">
                        <label class="block text-[13px] font-semibold text-slate-700 mb-2 pl-1">USERNAME</label>
                        <div class="relative transition-all duration-300 focus-within:transform focus-within:-translate-y-0.5">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-slate-400 group-focus-within:text-medical-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <input x-model="form.username" type="text" required 
                                   class="block w-full pl-11 pr-4 py-3 md:py-3.5 bg-white/50 border border-slate-200 rounded-xl text-base md:text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-medical-500/20 focus:border-medical-500 focus:bg-white transition-all shadow-sm font-medium hover:border-slate-300"
                                   placeholder="Masukkan nip/username">
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="group">
                        <label class="block text-[13px] font-semibold text-slate-700 mb-2 pl-1">PASSWORD</label>
                        <div class="relative transition-all duration-300 focus-within:transform focus-within:-translate-y-0.5">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-slate-400 group-focus-within:text-medical-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input x-model="form.password" :type="showPassword ? 'text' : 'password'" required
                                   class="block w-full pl-11 pr-12 py-3 md:py-3.5 bg-white/50 border border-slate-200 rounded-xl text-base md:text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-medical-500/20 focus:border-medical-500 focus:bg-white transition-all shadow-sm font-medium hover:border-slate-300"
                                   placeholder="••••••••">
                            <button type="button" @click="showPassword = !showPassword" 
                                    class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none transition-colors">
                                <svg x-show="!showPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="showPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Error Alert -->
                    <div x-show="error" x-transition.all.duration.300ms 
                         class="bg-red-50 text-red-600 px-4 py-3 rounded-xl text-sm border border-red-100 flex items-start gap-3 shadow-sm" 
                         style="display: none;">
                        <svg class="w-5 h-5 flex-shrink-0 mt-0.5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <span x-text="error" class="font-medium leading-tight pt-0.5"></span>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" :disabled="loading" 
                            class="group relative w-full flex justify-center py-3 md:py-3.5 px-4 mt-6 border border-transparent text-sm font-bold rounded-xl text-white bg-medical-600 hover:bg-medical-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-medical-500 disabled:opacity-70 disabled:cursor-not-allowed shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5">
                        
                        <div class="absolute inset-0 rounded-xl overflow-hidden">
                             <div class="absolute inset-0 bg-gradient-to-r from-medical-600 to-medical-500 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        </div>

                        <div class="relative flex items-center gap-2">
                            <svg x-show="loading" class="animate-spin h-5 w-5 text-white/90" fill="none" viewBox="0 0 24 24" style="display: none;">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span x-text="loading ? 'Memverifikasi...' : 'Masuk Dashboard'"></span>

                        </div>
                    </button>
                </form>

                <!-- Footer Links -->
                <div class="mt-8 text-center">
                    <a href="/" class="inline-flex items-center text-sm font-medium text-slate-500 hover:text-medical-600 transition-colors group">
                        <span class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center mr-2 group-hover:bg-medical-50 group-hover:scale-110 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                        </span>
                        Kembali ke Halaman Utama
                    </a>
                    
                    <div class="text-[11px] text-slate-400 font-medium tracking-wide uppercase pt-4">
                        &copy; 2026 Puskesmas Digital
                    </div>
                </div>
            </div>
        </div>
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

            // Simulate slight delay for "premium" feel if response is too fast
            const minDelay = new Promise(resolve => setTimeout(resolve, 600));

            try {
                const formData = new FormData();
                formData.append('username', this.form.username);
                formData.append('password', this.form.password);
                formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

                const fetchPromise = fetch('<?= base_url('auth/login') ?>', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
                    },
                    body: formData
                });

                const [response] = await Promise.all([fetchPromise, minDelay]);
                const data = await response.json();

                if (data.success) {
                    window.location.href = '<?= base_url('dashboard') ?>';
                } else {
                    this.error = data.message || 'Kredensial tidak valid.';
                }
            } catch (e) {
                this.error = 'Gagal terhubung ke server. Periksa koneksi internet Anda.';
            } finally {
                this.loading = false;
            }
        }
    }
}
</script>
<?= $this->endSection() ?>
