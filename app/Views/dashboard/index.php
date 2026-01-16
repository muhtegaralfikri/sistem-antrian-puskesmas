<?= $this->extend('layouts/base') ?>

<?= $this->section('title') ?>Dashboard - Sistem Antrian Puskesmas<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    .queue-card {
        transition: all 0.2s ease;
    }
    .queue-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.1);
    }
    .queue-number {
        font-size: 48px;
        font-weight: 800;
        line-height: 1;
    }
    .action-btn {
        transition: all 0.2s ease;
    }
    .action-btn:active {
        transform: scale(0.95);
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div x-data="dashboardApp(<?= json_encode($user) ?>, <?= json_encode($polis) ?>)" class="min-h-screen bg-gray-50">
    <!-- Navbar -->
    <nav class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-primary-600 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-lg font-bold text-gray-800">Dashboard Petugas</h1>
                    <p class="text-sm text-gray-500">Halo, <?= esc($user['nama_lengkap']) ?></p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="/admin" x-show="user.role === 'admin'" class="text-sm text-gray-600 hover:text-primary-600">
                    Admin Panel
                </a>
                <a href="/auth/logout" class="action-btn bg-red-50 hover:bg-red-100 text-red-600 px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Logout
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 py-6">
        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-xl p-5 shadow-sm border">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Total Menunggu</p>
                        <p class="text-3xl font-bold text-gray-800" x-text="stats.total_waiting"></p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl p-5 shadow-sm border">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Sedang Dilayani</p>
                        <p class="text-3xl font-bold text-gray-800" x-text="stats.total_serving"></p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl p-5 shadow-sm border">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Selesai</p>
                        <p class="text-3xl font-bold text-gray-800" x-text="stats.total_completed"></p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl p-5 shadow-sm border">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Total Poli</p>
                        <p class="text-3xl font-bold text-gray-800" x-text="polis.length"></p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Poli Cards -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <template x-for="poli in polis" :key="poli.poli.id">
                <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
                    <!-- Header -->
                    <div class="bg-gradient-to-r from-primary-600 to-primary-700 px-5 py-4 flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-white" x-text="poli.poli.nama"></h3>
                            <p class="text-primary-200 text-sm">Kode: <span x-text="poli.poli.kode"></span></p>
                        </div>
                        <div class="text-right">
                            <p class="text-primary-200 text-sm">Menunggu</p>
                            <p class="text-2xl font-bold text-white" x-text="poli.waiting_count"></p>
                        </div>
                    </div>

                    <!-- Currently Serving -->
                    <div class="p-5 bg-blue-50 border-b">
                        <p class="text-sm text-blue-700 font-medium mb-3">Sedang Dilayani</p>
                        <div x-show="poli.current" class="flex items-center justify-between">
                            <div>
                                <p class="queue-number text-primary-600" x-text="poli.current.nomor"></p>
                                <p class="text-sm text-gray-500 mt-1">
                                    Sejak: <span x-text="formatTime(poli.current.waktu_panggil)"></span>
                                </p>
                            </div>
                            <div class="flex gap-2">
                                <button @click="recall(poli.poli.id, poli.current.id)" :disabled="loading"
                                        class="action-btn bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg font-medium flex items-center gap-1 disabled:bg-gray-300">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                    Panggil Ulang
                                </button>
                                <button @click="selesai(poli.poli.id, poli.current.id)" :disabled="loading"
                                        class="action-btn bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg font-medium flex items-center gap-1 disabled:bg-gray-300">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Selesai
                                </button>
                            </div>
                        </div>
                        <div x-show="!poli.current" class="text-center text-gray-400 py-4">
                            Tidak ada antrian sedang dilayani
                        </div>
                    </div>

                    <!-- Waiting Queue -->
                    <div class="p-5">
                        <div class="flex items-center justify-between mb-3">
                            <p class="text-sm text-gray-700 font-medium">Antrian Menunggu</p>
                            <button @click="panggilBerikutnya(poli.poli.id)" :disabled="loading || poli.waiting_count === 0"
                                    class="action-btn bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg font-medium flex items-center gap-1 disabled:bg-gray-300">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                                Panggil Berikutnya
                            </button>
                        </div>
                        <div class="grid grid-cols-4 gap-2">
                            <template x-for="item in poli.waiting.slice(0, 8)" :key="item.id">
                                <div class="queue-card bg-gray-100 rounded-lg p-3 text-center">
                                    <p class="text-lg font-bold text-gray-800" x-text="item.nomor"></p>
                                    <p class="text-xs text-gray-500" x-text="formatTime(item.waktu_ambil)"></p>
                                </div>
                            </template>
                            <div x-show="!poli.waiting || poli.waiting.length === 0" class="col-span-4 text-center text-gray-400 py-6">
                                Tidak ada antrian menunggu
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </main>

    <!-- Loading Overlay -->
    <div x-show="loading" x-transition.opacity class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center" style="display: none;">
        <div class="bg-white rounded-xl p-6 shadow-xl">
            <div class="animate-spin w-8 h-8 border-4 border-primary-200 border-t-primary-600 rounded-full"></div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function dashboardApp(user, polis) {
    return {
        user: user,
        polis: polis,
        stats: { total_waiting: 0, total_serving: 0, total_completed: 0 },
        loading: false,
        ws: null,

        init() {
            // Calculate initial stats
            this.calculateStats();

            // Connect WebSocket
            this.connectWebSocket();

            // Periodic refresh fallback
            setInterval(() => this.refreshData(), 10000);
        },

        calculateStats() {
            this.polis.forEach(poli => {
                this.stats.total_waiting += poli.waiting_count || 0;
                this.stats.total_serving += poli.serving_count || 0;
                this.stats.total_completed += poli.completed_count || 0;
            });
        },

        async refreshData() {
            if (this.loading) return;

            try {
                const response = await fetch('/dashboard/data');
                const result = await response.json();

                if (result.success && result.data.polis) {
                    this.polis = result.data.polis;
                    this.stats = result.data.stats;
                }
            } catch (e) {
                console.error('Error refreshing data:', e);
            }
        },

        connectWebSocket() {
            try {
                this.ws = new WebSocket('ws://' + window.location.hostname + ':8080');

                this.ws.onopen = () => {
                    console.log('WebSocket connected');
                };

                this.ws.onmessage = (event) => {
                    const data = JSON.parse(event.data);
                    if (data.event === 'display:update') {
                        this.refreshData();
                    }
                };

                this.ws.onclose = () => {
                    setTimeout(() => this.connectWebSocket(), 5000);
                };
            } catch (e) {
                console.log('WebSocket not available');
            }
        },

        async panggilBerikutnya(poliId) {
            this.loading = true;

            try {
                const formData = new FormData();
                formData.append('poli_id', poliId);

                const response = await fetch('/api/v1/antrian/panggil', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    await this.refreshData();
                } else {
                    alert(result.message || 'Gagal memanggil antrian');
                }
            } catch (e) {
                alert('Terjadi kesalahan');
            } finally {
                this.loading = false;
            }
        },

        async recall(poliId, antrianId) {
            this.loading = true;

            try {
                const response = await fetch(`/api/v1/antrian/recall/${antrianId}`, {
                    method: 'POST'
                });

                const result = await response.json();

                if (result.success) {
                    await this.refreshData();
                } else {
                    alert(result.message || 'Gagal memanggil ulang');
                }
            } catch (e) {
                alert('Terjadi kesalahan');
            } finally {
                this.loading = false;
            }
        },

        async selesai(poliId, antrianId) {
            if (!confirm('Tandai antrian ini sebagai selesai?')) return;

            this.loading = true;

            try {
                const response = await fetch(`/api/v1/antrian/selesai/${antrianId}`, {
                    method: 'POST'
                });

                const result = await response.json();

                if (result.success) {
                    await this.refreshData();
                } else {
                    alert(result.message || 'Gagal menyelesaikan antrian');
                }
            } catch (e) {
                alert('Terjadi kesalahan');
            } finally {
                this.loading = false;
            }
        },

        formatTime(time) {
            if (!time) return '--';
            const date = new Date(time);
            return date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
        }
    }
}
</script>
<?= $this->endSection() ?>
