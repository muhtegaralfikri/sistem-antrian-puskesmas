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
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center gap-4">
                    <div class="bg-primary-600 text-white p-2.5 rounded-xl shadow-lg shadow-primary-600/20">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-900 tracking-tight">Monitor Petugas</h1>
                        <p class="text-sm text-gray-500 font-medium" x-text="'Halo, ' + (user.nama_lengkap || 'Petugas')"></p>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <!-- Status Badge -->
                    <div class="hidden md:flex items-center gap-2 bg-green-50 text-green-700 px-4 py-2 rounded-full border border-green-100">
                        <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                        <span class="text-sm font-bold">System Online</span>
                    </div>
                    
                    <div class="h-8 w-px bg-gray-200 hidden md:block"></div>

                    <a href="/auth/logout" class="text-gray-500 hover:text-red-600 font-medium text-sm transition-colors flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        Keluar
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
        
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-8 pb-10">
            <template x-for="poli in polis" :key="poli.poli.id">
                <div class="poli-card bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden group">
                    <!-- Header -->
                    <div class="bg-gray-50 px-8 py-5 border-b border-gray-100 flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-white shadow-sm border border-gray-200 flex items-center justify-center text-xl font-bold text-gray-800">
                                <span x-text="poli.poli.prefix"></span>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900" x-text="poli.poli.nama"></h3>
                                <div class="flex items-center gap-2 mt-0.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                    <p class="text-xs font-mono text-gray-500 uppercase tracking-wide">Buka</p>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                             <button @click="panggilBerikutnya(poli.poli.id)" :disabled="loading || poli.waiting_count === 0"
                                    class="group/btn relative px-5 py-2.5 bg-gray-900 hover:bg-black text-white rounded-xl shadow-lg shadow-gray-900/10 transition-all active:scale-95 disabled:opacity-50 disabled:shadow-none flex items-center gap-2 overflow-hidden">
                                <span class="relative z-10 font-bold text-sm">Panggil Next</span>
                                <svg class="w-4 h-4 relative z-10 transition-transform group-hover/btn:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </button>
                        </div>
                    </div>

                    <div class="flex flex-col md:flex-row h-[340px]">
                        <!-- Left: Current Serving -->
                        <div class="w-full md:w-1/2 p-8 border-b md:border-b-0 md:border-r border-gray-100 flex flex-col justify-between bg-white relative overflow-hidden">
                            <!-- Background Decoration -->
                            <div class="absolute -top-20 -left-20 w-40 h-40 bg-primary-50 rounded-full blur-3xl opacity-50"></div>

                            <div class="relative text-center">
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-[0.2em] mb-4">Sedang Dilayani</p>
                                
                                <template x-if="poli.current">
                                    <div class="transform transition-all duration-500">
                                        <div class="digital-display rounded-3xl p-8 mb-6 inline-block min-w-[200px]">
                                            <span class="block text-7xl font-black text-gray-900 tracking-tighter" x-text="poli.current.nomor"></span>
                                        </div>
                                        <div class="flex justify-center gap-4">
                                            <button @click="recall(poli.poli.id, poli.current.id)" class="p-3 text-orange-500 bg-orange-50 hover:bg-orange-100 rounded-xl transition-colors tooltip-trigger" title="Panggil Ulang">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.933 12.8a1 1 0 00-.933.8V19a2 2 0 01-2 2h-1a2 2 0 01-2-2v-6.6a1 1 0 00-.333-.74L.5 7.5a1 1 0 011 .5h17a1 1 0 011-.5L20 12M4 7V5a2 2 0 012-2h12a2 2 0 012 2v2"/></svg>
                                                <span class="sr-only">Recall</span>
                                            </button>
                                            <button @click="selesai(poli.poli.id, poli.current.id)" class="p-3 text-green-600 bg-green-50 hover:bg-green-100 rounded-xl transition-colors tooltip-trigger" title="Selesai">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                <span class="sr-only">Selesai</span>
                                            </button>
                                        </div>
                                    </div>
                                </template>

                                <template x-if="!poli.current">
                                    <div class="h-[200px] flex flex-col items-center justify-center text-gray-300">
                                        <div class="w-20 h-20 rounded-full border-2 border-dashed border-gray-200 flex items-center justify-center mb-4">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                                        </div>
                                        <p class="font-medium text-gray-400">Belum ada panggilan</p>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Right: Waiting List -->
                        <div class="w-full md:w-1/2 bg-gray-50 flex flex-col">
                            <div class="p-4 border-b border-gray-100 bg-gray-50/50 backdrop-blur-sm sticky top-0 flex justify-between items-center">
                                <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wide">Daftar Tunggu</h4>
                                <span class="bg-white px-2 py-0.5 rounded text-xs fonts-bold shadow-sm border border-gray-100" x-text="(poli.waiting_count || 0) + ' Antrian'"></span>
                            </div>
                            
                            <div class="flex-1 overflow-y-auto custom-scrollbar p-4">
                                <template x-if="poli.waiting && poli.waiting.length > 0">
                                    <div class="space-y-2.5">
                                        <template x-for="(item, idx) in poli.waiting" :key="item.id">
                                            <div class="flex items-center justify-between p-3.5 bg-white rounded-xl shadow-sm border border-gray-100 hover:border-primary-200 transition-colors group">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-8 h-8 rounded-lg bg-gray-50 text-gray-500 font-bold text-xs flex items-center justify-center group-hover:bg-primary-50 group-hover:text-primary-600 transition-colors" x-text="idx + 1"></div>
                                                    <span class="font-bold text-lg text-gray-700 font-mono" x-text="item.nomor"></span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                     <button @click="skip(poli.poli.id, item.id)" class="text-xs text-gray-300 hover:text-red-500 p-1.5 hover:bg-red-50 rounded transition-all" title="Lewati">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                     </button>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </template>

                                <template x-if="!poli.waiting || poli.waiting.length === 0">
                                    <div class="h-full flex flex-col items-center justify-center text-center p-6">
                                        <img src="https://illustrations.popsy.co/gray/success.svg" class="w-24 h-24 opacity-50 mb-4 mix-blend-multiply" alt="Empty">
                                        <p class="text-sm font-medium text-gray-400">Tidak ada antrian menunggu</p>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </main>
</div>

<div x-show="loading" class="fixed inset-0 z-[100] bg-white flex items-center justify-center">
    <div class="flex flex-col items-center gap-4">
        <div class="w-12 h-12 border-4 border-primary-200 border-t-primary-600 rounded-full animate-spin"></div>
        <div class="text-gray-500 font-medium animate-pulse">Memuat Data...</div>
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
                const options = { method: 'POST' };
                if (body) options.body = body;
                
                const response = await fetch(endpoint, options);
                const result = await response.json();
                
                if (result.success) {
                    await this.loadData();
                } else {
                    alert(result.message || 'Gagal'); // Simple alert for now
                }
            } catch (e) {
                alert('Connection Error');
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
            if(confirm('Selesai?')) this.apiCall(`/api/v1/antrian/selesai/${antrianId}`);
        },
        
        skip(poliId, antrianId) {
            if(confirm('Lewati antrian ini?')) this.apiCall(`/api/v1/antrian/skip/${antrianId}`);
        }
    };
}
</script>
<?= $this->endSection() ?>
