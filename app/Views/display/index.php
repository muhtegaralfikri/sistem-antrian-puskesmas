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
            transition: all 0.3s ease;
        }

        .poli-card.serving {
            border: 3px solid #22c55e;
            animation: pulse-border 2s infinite;
        }

        @keyframes pulse-border {
            0%, 100% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.4); }
            50% { box-shadow: 0 0 0 15px rgba(34, 197, 94, 0); }
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
            transition: all 0.2s ease;
        }

        .recent-item:hover {
            background: #e2e8f0;
            transform: scale(1.02);
        }

        .voice-indicator {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 60px;
            height: 60px;
            background: #22c55e;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 30px rgba(34, 197, 94, 0.4);
        }

        .voice-indicator.speaking {
            animation: speaking 0.5s infinite;
        }

        @keyframes speaking {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
        }

        .status-badge.serving {
            background: #dcfce7;
            color: #166534;
        }

        .status-badge.waiting {
            background: #fef3c7;
            color: #92400e;
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #1e293b;
        }
        ::-webkit-scrollbar-thumb {
            background: #475569;
            border-radius: 4px;
        }
    </style>
</head>
<body x-data="displayApp(<?= json_encode($polis) ?>, <?= json_encode($voice_enabled) ?>, <?= json_encode($voice_volume) ?>, <?= json_encode($auto_refresh_interval) ?>)">
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

        <!-- Poli Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
            <template x-for="poli in polis" :key="poli.id">
                <div class="poli-card" :class="{ 'serving': poli.current }">
                    <!-- Poli Header -->
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-xl font-bold text-white" x-text="poli.nama"></h3>
                                <p class="text-blue-200 text-sm">Kode: <span x-text="poli.kode"></span></p>
                            </div>
                            <div class="status-badge" :class="poli.current ? 'serving' : 'waiting'">
                                <span class="w-2 h-2 rounded-full" :class="poli.current ? 'bg-green-600' : 'bg-yellow-600'"></span>
                                <span x-text="poli.current ? 'Sedang Melayani' : 'Menunggu'"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Current Number -->
                    <div class="p-6 text-center">
                        <p class="text-gray-500 text-sm mb-2">Nomor Sedang Dilayani</p>
                        <div class="current-number" x-text="poli.current?.nomor || '--'"></div>
                        <p class="text-gray-500 mt-2" x-show="!poli.current">Belum ada antrian</p>
                    </div>

                    <!-- Recent Numbers -->
                    <div class="px-6 pb-6">
                        <p class="text-gray-500 text-sm mb-3">Antrian Sebelumnya</p>
                        <div class="space-y-2">
                            <template x-for="item in poli.recent.slice(0, 3)" :key="item.id">
                                <div class="recent-item flex items-center justify-between">
                                    <span class="font-bold text-gray-800" x-text="item.nomor"></span>
                                    <span class="text-sm text-gray-500" x-text="formatTime(item.waktu_selesai || item.waktu_panggil)"></span>
                                </div>
                            </template>
                            <div x-show="!poli.recent || poli.recent.length === 0" class="text-center text-gray-400 text-sm py-4">
                                Belum ada antrian
                            </div>
                        </div>
                    </div>

                    <!-- Waiting Count -->
                    <div class="px-6 pb-6">
                        <div class="bg-blue-50 rounded-xl p-4 flex items-center justify-between">
                            <span class="text-blue-700">Menunggu</span>
                            <span class="text-2xl font-bold text-blue-700" x-text="poli.waiting_count || 0"></span>
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

    <!-- Voice Indicator -->
    <div class="voice-indicator" :class="{ 'speaking': isSpeaking }" x-show="voiceEnabled">
        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/>
        </svg>
    </div>

    <script>
        function displayApp(polis, voiceEnabled, voiceVolume, autoRefreshInterval) {
            return {
                polis: polis,
                voiceEnabled: voiceEnabled,
                voiceVolume: voiceVolume,
                isSpeaking: false,
                lastCalledNumber: null,
                currentTime: '',
                currentDate: '',
                ws: null,

                init() {
                    this.updateTime();
                    setInterval(() => this.updateTime(), 1000);

                    // Initial data load
                    this.loadDisplayData();

                    // Auto-refresh fallback
                    setInterval(() => {
                        if (!this.ws || this.ws.readyState !== WebSocket.OPEN) {
                            this.loadDisplayData();
                        }
                    }, autoRefreshInterval * 1000);

                    // Connect WebSocket
                    this.connectWebSocket();
                },

                updateTime() {
                    const now = new Date();
                    this.currentTime = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
                    this.currentDate = now.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
                },

                async loadDisplayData() {
                    try {
                        const response = await fetch('/display/data');
                        const result = await response.json();

                        if (result.success && result.data.polis) {
                            this.checkForNewCalls(result.data.polis);
                            this.polis = result.data.polis;
                        }
                    } catch (e) {
                        console.error('Error loading display data:', e);
                    }
                },

                checkForNewCalls(newPolis) {
                    if (!this.voiceEnabled) return;

                    newPolis.forEach(poli => {
                        if (poli.current && poli.current.nomor !== this.lastCalledNumber) {
                            // New number called!
                            this.lastCalledNumber = poli.current.nomor;
                            this.speak(poli.current.nomor, poli.poli?.nama || poli.nama);
                        }
                    });
                },

                speak(nomor, poliNama) {
                    if (!('speechSynthesis' in window)) {
                        console.warn('Speech synthesis not supported');
                        return;
                    }

                    // Cancel any ongoing speech
                    window.speechSynthesis.cancel();

                    const text = `Nomor antrian, ${nomor}, ${poliNama}, dimohon dipersilahkan masuk`;

                    const utterance = new SpeechSynthesisUtterance(text);
                    utterance.lang = 'id-ID';
                    utterance.rate = 0.9;
                    utterance.volume = this.voiceVolume;
                    utterance.pitch = 1;

                    utterance.onstart = () => {
                        this.isSpeaking = true;
                    };

                    utterance.onend = () => {
                        this.isSpeaking = false;
                    };

                    utterance.onerror = () => {
                        this.isSpeaking = false;
                    };

                    window.speechSynthesis.speak(utterance);
                },

                connectWebSocket() {
                    try {
                        // Try to connect to WebSocket server
                        this.ws = new WebSocket('ws://' + window.location.hostname + ':8080');

                        this.ws.onopen = () => {
                            console.log('WebSocket connected');
                            // Subscribe to display updates
                            this.ws.send(JSON.stringify({
                                type: 'subscribe',
                                channel: 'display'
                            }));
                        };

                        this.ws.onmessage = (event) => {
                            const data = JSON.parse(event.data);
                            this.handleWebSocketMessage(data);
                        };

                        this.ws.onclose = () => {
                            console.log('WebSocket disconnected, reconnecting...');
                            setTimeout(() => this.connectWebSocket(), 5000);
                        };

                        this.ws.onerror = () => {
                            console.log('WebSocket error');
                        };
                    } catch (e) {
                        console.log('WebSocket not available, using auto-refresh');
                    }
                },

                handleWebSocketMessage(data) {
                    if (data.event === 'antrian:panggil' && data.data) {
                        // Update display data
                        this.loadDisplayData();

                        // Speak if enabled
                        if (this.voiceEnabled) {
                            this.speak(data.data.nomor, data.data.poli_nama);
                        }
                    } else if (data.event === 'antrian:baru' || data.event === 'antrian:selesai') {
                        this.loadDisplayData();
                    } else if (data.event === 'display:update') {
                        this.polis = data.data.polis || this.polis;
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
</body>
</html>
