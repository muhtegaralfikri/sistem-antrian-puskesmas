<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Antrian - Puskesmas</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');

        * {
            font-family: 'Inter', system-ui, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            min-height: 100vh;
        }

        .poli-card {
            background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            overflow: hidden;
        }

        .current-number {
            font-size: 80px;
            font-weight: 900;
            line-height: 1;
            background: linear-gradient(135deg, #0ea5e9 0%, #0369a1 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .recent-item {
            background: #f1f5f9;
            border-radius: 12px;
            padding: 12px 16px;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            background: #fef3c7;
            color: #92400e;
        }
    </style>
</head>
<body x-data="displayData()">
    <div class="min-h-screen p-6">
        <!-- Header -->
        <header class="mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-700 rounded-2xl flex items-center justify-center shadow-lg">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-white">Sistem Antrian</h1>
                        <p class="text-blue-200">Puskesmas</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-4xl font-bold text-white" x-text="currentTime"></p>
                    <p class="text-blue-200" x-text="currentDate"></p>
                </div>
            </div>
        </header>

        <!-- Loading -->
        <div x-show="loading" class="text-center py-12">
            <p class="text-white text-xl">Memuat data...</p>
        </div>

        <!-- Poli Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6" x-show="!loading">
            <template x-for="item in polis" :key="item.poli.id">
                <div class="poli-card">
                    <!-- Poli Header -->
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-xl font-bold text-white" x-text="item.poli.nama"></h3>
                                <p class="text-blue-200 text-sm" x-text="'Kode: ' + item.poli.prefix"></p>
                            </div>
                            <div class="status-badge">
                                <span class="w-2 h-2 rounded-full bg-yellow-600"></span>
                                <span x-text="item.current ? 'Sedang Melayani' : 'Menunggu'"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Current Number -->
                    <div class="p-6 text-center">
                        <p class="text-gray-500 text-sm mb-2">Nomor Sedang Dilayani</p>
                        <div class="current-number" x-text="item.current ? item.current.nomor : '--'"></div>
                    </div>

                    <!-- Recent Numbers -->
                    <div class="px-6 pb-6">
                        <p class="text-gray-500 text-sm mb-3">Antrian Sebelumnya</p>
                        <div class="space-y-2">
                            <template x-for="recent in (item.recent || []).slice(0, 3)" :key="recent.id">
                                <div class="recent-item flex items-center justify-between">
                                    <span class="font-bold text-gray-800" x-text="recent.nomor"></span>
                                    <span class="text-sm text-gray-500" x-text="formatTime(recent.waktu_selesai || recent.waktu_panggil)"></span>
                                </div>
                            </template>
                            <div x-show="!item.recent || item.recent.length === 0" class="text-center text-gray-400 text-sm py-4">
                                Belum ada antrian
                            </div>
                        </div>
                    </div>

                    <!-- Waiting Count -->
                    <div class="px-6 pb-6">
                        <div class="bg-blue-50 rounded-xl p-4 flex items-center justify-between">
                            <span class="text-blue-700">Menunggu</span>
                            <span class="text-2xl font-bold text-blue-700" x-text="item.waiting_count || 0"></span>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- Footer -->
        <footer class="mt-8 text-center text-blue-200 text-sm">
            <p>&copy; <?= date('Y') ?> Puskesmas | Sistem Antrian</p>
        </footer>
    </div>

    <script>
        function displayData() {
            return {
                polis: [],
                loading: true,
                currentTime: '',
                currentDate: '',
                lastCalled: {},

                init() {
                    // Update time
                    this.updateTime();
                    setInterval(() => this.updateTime(), 1000);

                    // Load data
                    this.loadData();

                    // Auto refresh
                    setInterval(() => this.loadData(), 5000);
                },

                async loadData() {
                    try {
                        const res = await fetch('/display/data');
                        const result = await res.json();

                        if (result.success && result.data.polis) {
                            this.polis = result.data.polis;
                            this.checkVoice(result.data.polis);
                        }
                        this.loading = false;
                    } catch (e) {
                        console.error('Error:', e);
                        this.loading = false;
                    }
                },

                checkVoice(newPolis) {
                    newPolis.forEach(item => {
                        if (item.current && item.current.nomor !== this.lastCalled[item.poli.id]) {
                            this.lastCalled[item.poli.id] = item.current.nomor;
                            this.speak(item.current.nomor, item.poli.nama);
                        }
                    });
                },

                speak(nomor, poliNama) {
                    if (!('speechSynthesis' in window)) return;

                    const text = `Nomor antrian, ${nomor}, ${poliNama}, dimohon dipersilahkan masuk`;
                    const utterance = new SpeechSynthesisUtterance(text);
                    utterance.lang = 'id-ID';
                    utterance.rate = 0.9;
                    utterance.volume = 0.8;
                    window.speechSynthesis.speak(utterance);
                },

                updateTime() {
                    const now = new Date();
                    this.currentTime = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
                    this.currentDate = now.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
                },

                formatTime(time) {
                    if (!time) return '--';
                    const date = new Date(time);
                    return date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
                }
            };
        }
    </script>
</body>
</html>
