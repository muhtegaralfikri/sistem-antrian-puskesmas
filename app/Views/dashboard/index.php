<?= $this->extend('layouts/base') ?>

<?= $this->section('title') ?>Monitor Antrian - Sistem Puskesmas<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap');
    
    body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f0f4f8; }
    
    .font-mono { font-family: 'JetBrains Mono', monospace; }
    
    .glass-header {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-bottom: 1px solid rgba(0,0,0,0.05);
    }
    
    .dashboard-bg {
        background-image: 
            radial-gradient(circle at 10% 20%, rgba(14, 165, 233, 0.05) 0%, transparent 20%),
            radial-gradient(circle at 90% 80%, rgba(124, 58, 237, 0.05) 0%, transparent 20%);
        background-attachment: fixed;
    }

    .poli-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .poli-card:hover { 
        transform: translateY(-2px);
        box-shadow: 0 20px 40px -10px rgba(0,0,0,0.08); 
    }

    .digital-display {
        background: linear-gradient(145deg, #ffffff, #f5f7fa);
        box-shadow: inset 2px 2px 5px rgba(0,0,0,0.03), inset -2px -2px 5px rgba(255,255,255,0.8);
    }

    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #e2e8f0; border-radius: 20px; }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div x-data="dashboardApp()" class="min-h-screen flex flex-col dashboard-bg">
    <!-- Navbar -->
    <nav class="glass-header sticky top-0 z-50">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16 md:h-20">
                <div class="flex items-center gap-3 md:gap-4">
                    <div class="bg-primary-600 text-white p-2 md:p-2.5 rounded-xl shadow-lg shadow-primary-600/20">
                        <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                    <div>
                        <h1 class="text-lg md:text-xl font-bold text-gray-900 tracking-tight leading-tight">Monitor Petugas</h1>
                        <p class="text-xs md:text-sm text-gray-500 font-medium" x-text="'Halo, ' + (user.nama_lengkap || 'Petugas')"></p>
                    </div>
                </div>

                <div class="flex items-center gap-2 md:gap-4">
                    <!-- Status Badge -->
                    <div class="flex items-center gap-1.5 md:gap-2 bg-green-50 text-green-700 px-2.5 py-1.5 md:px-4 md:py-2 rounded-full border border-green-100">
                        <span class="w-1.5 h-1.5 md:w-2 md:h-2 rounded-full bg-green-500 animate-pulse"></span>
                        <span class="text-[10px] md:text-sm font-bold hidden sm:inline">System Online</span>
                    </div>
                    
                    <!-- Admin Link -->
                    <template x-if="user.role === 'admin'">
                        <a href="/admin" class="flex items-center gap-1.5 md:gap-2 bg-blue-50 text-blue-700 px-2.5 py-1.5 md:px-4 md:py-2 rounded-full border border-blue-100 hover:bg-blue-100 transition-colors" title="Admin Panel">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span class="text-[10px] md:text-sm font-bold hidden sm:inline">Admin Panel</span>
                        </a>
                    </template>

                    <div class="h-6 w-px bg-gray-200 hidden md:block"></div>

                    <a href="/auth/logout" class="text-gray-500 hover:text-red-600 font-medium text-sm transition-colors flex items-center gap-2" title="Keluar">
                        <svg class="w-6 h-6 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        <span class="hidden md:inline">Keluar</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <main class="flex-1 max-w-[1600px] w-full mx-auto px-4 sm:px-6 lg:px-8 py-8" x-show="!loading">
        
        <!-- Stats Row -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
                <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Menunggu</p>
                    <p class="text-2xl font-bold text-gray-900" x-text="stats.total_waiting"></p>
                </div>
            </div>
            
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
                <div class="p-3 bg-purple-50 text-purple-600 rounded-xl">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Sedang Dilayani</p>
                    <p class="text-2xl font-bold text-gray-900" x-text="stats.total_serving"></p>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
                <div class="p-3 bg-green-50 text-green-600 rounded-xl">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Selesai Hari Ini</p>
                    <p class="text-2xl font-bold text-gray-900" x-text="stats.total_completed"></p>
                </div>
            </div>

            <div class="bg-gradient-to-br from-gray-900 to-gray-800 text-white p-5 rounded-2xl shadow-lg flex items-center gap-4">
                <div class="p-3 bg-white/10 rounded-xl">
                    <svg class="w-8 h-8 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-300">Waktu Sekarang</p>
                    <p class="text-2xl font-bold font-mono" x-text="currentTime"></p>
                </div>
            </div>
        </div>

        <!-- Poli Grid -->
        <h2 class="text-2xl font-bold text-gray-800 mb-6 pl-2 border-l-4 border-primary-600">Daftar Loket Pelayanan</h2>
        
        <div class="max-w-5xl mx-auto space-y-8 pb-20 relative z-10">
            <template x-for="poli in polis" :key="poli.poli.id">
                <div class="bg-white/80 backdrop-blur-xl rounded-[2.5rem] shadow-2xl shadow-gray-200/50 border border-white/50 overflow-hidden relative group hover:transform hover:scale-[1.01] transition-all duration-300">
                    
                    <!-- Decorative Background -->
                    <div class="absolute top-0 right-0 w-96 h-96 bg-gradient-to-br from-primary-50/50 to-transparent rounded-full blur-3xl -z-10 translate-x-1/3 -translate-y-1/3"></div>
                    <div class="absolute bottom-0 left-0 w-64 h-64 bg-gradient-to-tr from-blue-50/50 to-transparent rounded-full blur-3xl -z-10 -translate-x-1/3 translate-y-1/3"></div>

                    <div class="flex flex-col md:flex-row">
                        <!-- Main Section (Info & Current Serving) -->
                        <div class="flex-1 p-6 md:p-8 md:pr-12 relative">
                            <!-- Header Info -->
                            <div class="flex justify-between items-start mb-8">
                                <div class="flex items-center gap-5">
                                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-white to-gray-50 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 flex items-center justify-center text-2xl font-black text-gray-800 relative overflow-hidden">
                                        <div class="absolute inset-0 bg-gradient-to-br from-transparent to-black/5"></div>
                                        <span x-text="poli.poli.prefix" class="relative z-10"></span>
                                    </div>
                                    <div>
                                        <h3 class="text-2xl md:text-3xl font-bold text-gray-900 tracking-tight" x-text="poli.poli.nama"></h3>
                                        <div class="flex items-center gap-2.5 mt-2">
                                            <div class="px-3 py-1 rounded-full bg-green-100/50 border border-green-200/50 flex items-center gap-2">
                                                <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                                                <span class="text-xs font-bold text-green-700 uppercase tracking-wider">Sedang Melayani</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Call Next Button (Desktop) -->
                                <button @click="panggilBerikutnya(poli.poli.id)" :disabled="loading || poli.waiting_count === 0"
                                        class="hidden md:flex group relative px-8 py-4 bg-gray-900 hover:bg-black text-white rounded-2xl shadow-xl shadow-gray-900/20 transition-all active:scale-95 disabled:opacity-50 disabled:shadow-none items-center gap-3 overflow-hidden">
                                    <div class="absolute inset-0 bg-gradient-to-r from-white/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                    <span class="relative z-10 font-bold text-base">Panggil</span>
                                    <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center relative z-10 group-disabled:hidden">
                                        <svg class="w-4 h-4 transition-transform group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                    </div>
                                </button>
                            </div>

                            <!-- Big Current Number -->
                            <div class="flex flex-col md:flex-row items-center gap-8 md:gap-12 pl-2">
                                <div class="relative">
                                     <p class="text-xs font-bold text-gray-400 uppercase tracking-[0.2em] mb-4 text-center md:text-left">Nomor Panggilan</p>
                                     <template x-if="poli.current">
                                        <div>
                                            <div class="text-7xl md:text-8xl lg:text-9xl font-black text-transparent bg-clip-text bg-gradient-to-br from-gray-900 to-gray-600 tracking-tighter tabular-nums leading-none filter drop-shadow-sm whitespace-nowrap" x-text="poli.current.nomor"></div>
                                            <template x-if="poli.current.nama_pasien">
                                                <div class="mt-3 px-4 py-2 bg-primary-50 rounded-xl border border-primary-100 text-center">
                                                    <span class="text-xs text-primary-400 font-bold uppercase tracking-wider">Nama:</span>
                                                    <span class="text-base md:text-lg font-bold text-primary-700 ml-2 truncate" x-text="poli.current.nama_pasien"></span>
                                                </div>
                                            </template>
                                        </div>
                                     </template>
                                     <template x-if="!poli.current">
                                         <div class="text-7xl md:text-8xl font-black text-gray-200 tracking-tighter tabular-nums leading-none select-none">---</div>
                                     </template>
                                </div>

                                <!-- Controls for Current Number -->
                                <template x-if="poli.current">
                                    <div class="flex items-center gap-4">
                                        <button @click="recall(poli.poli.id, poli.current.id)" class="p-4 rounded-2xl bg-orange-50 text-orange-600 hover:bg-orange-100 hover:scale-105 active:scale-95 transition-all border border-orange-100/50 shadow-sm group" title="Panggil Ulang">
                                            <svg class="w-6 h-6 md:w-8 md:h-8 group-hover:rotate-180 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m-15.357-2a8.001 8.001 0 0015.357-2m0 0H15"/></svg>
                                        </button>
                                        <button @click="selesai(poli.poli.id, poli.current.id)" class="p-4 rounded-2xl bg-green-50 text-green-600 hover:bg-green-100 hover:scale-105 active:scale-95 transition-all border border-green-100/50 shadow-sm" title="Selesai">
                                            <svg class="w-6 h-6 md:w-8 md:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        </button>
                                    </div>
                                </template>
                            </div>
                            
                            <!-- Mobile Call Button -->
                            <button @click="panggilBerikutnya(poli.poli.id)" :disabled="loading || poli.waiting_count === 0"
                                    class="mt-8 w-full md:hidden group relative px-8 py-4 bg-gray-900 text-white rounded-2xl shadow-xl shadow-gray-900/20 transition-all active:scale-95 disabled:opacity-50 disabled:shadow-none flex items-center justify-center gap-3 overflow-hidden">
                                <span class="relative z-10 font-bold text-base">Panggil</span>
                                <svg class="w-5 h-5 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </button>
                        </div>

                        <!-- Right: Next Queue -->
                        <div class="w-full md:w-[320px] lg:w-[400px] bg-gray-50/50 border-t md:border-t-0 md:border-l border-gray-100 flex flex-col items-center py-6 md:py-8 backdrop-blur-sm px-6 md:px-8">
                            
                            <!-- Header & Badge Row -->
                            <div class="w-full flex flex-col md:flex-row justify-between items-center gap-4 mb-8">
                                <h4 class="text-xs md:text-sm font-bold text-gray-400 uppercase tracking-[0.2em] text-center md:text-left">Antrian Berikutnya</h4>
                                <div class="px-3 py-1.5 rounded-xl bg-white border border-gray-100 shadow-sm flex items-center gap-2 whitespace-nowrap">
                                     <svg class="w-3.5 h-3.5 md:w-4 md:h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    <span class="text-xs md:text-sm font-bold text-gray-600" x-text="(poli.waiting_count || 0) + ' Menunggu'"></span>
                                </div>
                            </div>

                            <template x-if="poli.waiting && poli.waiting.length > 0">
                                <div class="w-full flex flex-col items-center">
                                    <p class="text-[10px] md:text-xs font-bold text-primary-600 mb-2 uppercase tracking-wide">Siap Dipanggil</p>
                                    <div class="text-5xl md:text-6xl lg:text-7xl font-black text-gray-800 tracking-tighter tabular-nums mb-2 whitespace-nowrap" x-text="poli.waiting[0].nomor"></div>
                                    <template x-if="poli.waiting[0].nama_pasien">
                                        <div class="text-sm md:text-base font-semibold text-gray-500 mb-4 truncate max-w-full px-2" x-text="poli.waiting[0].nama_pasien"></div>
                                    </template>
                                    <template x-if="!poli.waiting[0].nama_pasien">
                                        <div class="mb-4"></div>
                                    </template>
                                    
                                    <div class="flex justify-center gap-2 relative z-10 w-full">
                                         <button @click="skip(poli.poli.id, poli.waiting[0].id)" class="w-full md:w-auto px-5 py-2.5 md:px-6 md:py-3 rounded-xl bg-red-50 text-red-600 font-bold text-xs md:text-sm hover:bg-red-100 transition-colors flex items-center justify-center gap-2">
                                            <svg class="w-3.5 h-3.5 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                            Lewati
                                         </button>
                                    </div>
                                </div>
                            </template>

                            <template x-if="!poli.waiting || poli.waiting.length === 0">
                                <div class="flex flex-col items-center justify-center text-center p-6 md:p-8 opacity-40">
                                    <div class="w-20 h-20 md:w-24 md:h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                        <svg class="w-8 h-8 md:w-10 md:h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7"/></svg>
                                    </div>
                                    <p class="text-sm md:text-base font-medium text-gray-400">Tidak ada antrian</p>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </main>
    <!-- Loading Overlay -->
    <div x-show="loading" class="fixed inset-0 z-[100] bg-white flex items-center justify-center">
        <div class="flex flex-col items-center gap-4">
            <div class="w-12 h-12 border-4 border-primary-200 border-t-primary-600 rounded-full animate-spin"></div>
            <div class="text-gray-500 font-medium animate-pulse">Memuat Data...</div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function dashboardApp() {
    return {
        user: {},
        polis: [],
        stats: { total_waiting: 0, total_serving: 0, total_completed: 0 },
        loading: true,
        currentTime: '',

        init() {
            this.loadData();
            this.updateTime();
            setInterval(() => this.loadData(), 5000);
            setInterval(() => this.updateTime(), 1000);
        },

        updateTime() {
            const now = new Date();
            this.currentTime = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
        },

        async loadData() {
            try {
                const response = await fetch('/monitor/data');
                const result = await response.json();
                if (result.success) {
                    this.user = result.data.user;
                    this.polis = result.data.polis;
                    this.stats = result.data.stats;
                }
                this.loading = false;
            } catch (e) {
                console.error(e);
                this.loading = false;
            }
        },

        // API Actions (reused with better error handling)
        async apiCall(endpoint, body = null) {
            this.loading = true;
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const csrfHeader = document.querySelector('meta[name="csrf-header"]').getAttribute('content');

                const options = { 
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        [csrfHeader]: csrfToken
                    }
                };
                if (body) options.body = body;

                const response = await fetch(endpoint, options);
                const result = await response.json();

                if (result.success) {
                    await this.loadData();
                    Toast.success(result.message || 'Berhasil');
                } else {
                    Toast.error(result.message || 'Gagal');
                }
            } catch (e) {
                console.error(e);
                Toast.error('Koneksi Error');
            } finally {
                this.loading = false;
            }
        },

        panggilBerikutnya(poliId) {
            const fd = new FormData();
            fd.append('poli_id', poliId);
            this.apiCall('/api/v1/antrian/panggil', fd);
        },

        recall(poliId, antrianId) {
            this.apiCall(`/api/v1/antrian/recall/${antrianId}`);
        },

        selesai(poliId, antrianId) {
            this.apiCall(`/api/v1/antrian/selesai/${antrianId}`);
        },

        skip(poliId, antrianId) {
            this.apiCall(`/api/v1/antrian/skip/${antrianId}`);
        }
    };
}
</script>
<?= $this->endSection() ?>
