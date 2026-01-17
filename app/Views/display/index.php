<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Antrian - Puskesmas</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb', // Brand Blue
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        },
                        secondary: {
                            50: '#f0fdfa',
                            100: '#ccfbf1',
                            200: '#99f6e4',
                            300: '#5eead4',
                            400: '#2dd4bf',
                            500: '#14b8a6', // Accent Teal
                            600: '#0d9488',
                            700: '#0f766e',
                            800: '#115e59',
                            900: '#134e4a',
                        },
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');
        * { font-family: 'Inter', system-ui, sans-serif; }
        body { 
            background: #f8fafc; /* Slate 50 */
            min-height: 100vh;
            overflow-x: hidden;
            background-image: 
                radial-gradient(circle at 10% 20%, rgba(37, 99, 235, 0.05) 0%, transparent 20%),
                radial-gradient(circle at 90% 80%, rgba(20, 184, 166, 0.05) 0%, transparent 20%);
        }
        .poli-card { 
            background: #ffffff; 
            border-radius: 24px; 
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            overflow: hidden; 
            border: 1px solid rgba(255,255,255,0.1);
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        .current-number { 
            font-size: 110px; 
            font-weight: 900; 
            line-height: 1; 
            color: #2563eb;
            letter-spacing: -4px;
            text-align: center;
            font-variant-numeric: tabular-nums;
        }
    </style>
</head>
<body x-data="displayData()">
    <!-- Audio Permission Overlay -->
    <div x-show="!audioEnabled" class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm">
        <div class="text-center bg-white p-8 rounded-2xl shadow-2xl max-w-md mx-4">
            <div class="mb-6 bg-blue-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto">
                <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/>
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Mulai Display Antrian</h2>
            <p class="text-gray-600 mb-4">Klik tombol untuk mengaktifkan suara</p>
            <div class="text-sm text-gray-500 mb-6">
                <p>Sistem menggunakan audio file pre-recorded</p>
                <p class="text-xs mt-2">Pastikan folder /voice sudah berisi file audio</p>
            </div>
            <button @click="enableAudio()"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-xl font-semibold text-lg transition-all transform hover:scale-105">
                Mulai Aplikasi
            </button>
        </div>
    </div>

    <!-- Main Content -->
    <div x-show="audioEnabled" class="min-h-screen p-6" style="display: none;">
        <header class="mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-6">
                    <div class="w-20 h-20 bg-gradient-to-br from-primary-600 to-primary-700 rounded-2xl flex items-center justify-center shadow-lg shadow-primary-600/20">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-4xl font-black text-gray-800 tracking-tight">Sistem Antrian</h1>
                        <p class="text-lg text-gray-500 font-medium tracking-wide">Puskesmas Digital</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-4xl font-bold text-gray-800" x-text="currentTime"></p>
                    <p class="text-gray-500" x-text="currentDate"></p>
                </div>
            </div>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
            <template x-for="item in polis" :key="item.poli.id">
                <div class="poli-card relative group">
                    <!-- Status Indicator Line -->
                    <div class="h-2 w-full" :class="item.current ? 'bg-secondary-500' : 'bg-gray-200'"></div>
                    
                    <div class="p-6 flex-1 flex flex-col">
                        <!-- Header -->
                        <div class="flex items-start justify-between mb-6">
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900" x-text="item.poli.nama"></h3>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="px-2.5 py-0.5 rounded-lg bg-gray-100 text-gray-600 text-xs font-bold font-mono tracking-wide" x-text="'KODE: ' + item.poli.prefix"></span>
                                </div>
                            </div>
                            <div class="px-4 py-2 rounded-full text-xs font-bold uppercase tracking-wider flex items-center gap-2"
                                 :class="item.current ? 'bg-secondary-50 text-secondary-700 ring-1 ring-secondary-500/20' : 'bg-gray-50 text-gray-500 ring-1 ring-gray-200'">
                                <span class="w-2 h-2 rounded-full" :class="item.current ? 'bg-secondary-500 animate-pulse' : 'bg-gray-400'"></span>
                                <span x-text="item.current ? 'Sedang Melayani' : 'Menunggu'"></span>
                            </div>
                        </div>

                        <!-- Main Number -->
                        <div class="flex-1 flex flex-col items-center justify-center py-4 mb-6">
                            <p class="text-gray-400 text-sm font-medium uppercase tracking-widest mb-2">Nomor Panggilan</p>
                            <div class="current-number transition-all duration-300 transform group-hover:scale-105" 
                                 x-text="item.current ? item.current.nomor : '--'"></div>
                        </div>

                        <!-- Footer / Recent -->
                        <div class="bg-gray-50 -mx-6 -mb-6 p-6 border-t border-gray-100">
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-sm font-semibold text-gray-500">Antrian Sebelumnya</span>
                                <span class="text-xs font-bold text-primary-600 bg-primary-50 px-2 py-1 rounded-md" x-text="(item.waiting_count || 0) + ' Menunggu'"></span>
                            </div>
                            
                            <div class="grid grid-cols-3 gap-3">
                                <template x-for="recent in (item.recent || []).slice(0, 3)" :key="recent.id">
                                    <div class="bg-white rounded-xl p-2 text-center border border-gray-100 shadow-sm">
                                        <div class="font-bold text-gray-700 text-lg" x-text="recent.nomor"></div>
                                    </div>
                                </template>
                                <div x-show="!item.recent || item.recent.length === 0" class="col-span-3 text-center text-gray-400 text-sm italic py-2">
                                    Belum ada riwayat
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <footer class="mt-8 text-center text-gray-400 text-sm">
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
            lastCallTime: {},
            audioEnabled: false,
            audioBaseUrl: '/voice/',
            isPlaying: false,
            audioQueue: [],
            currentAudio: null,
            indonesianVoice: null,
            globalAnnouncementQueue: [],
            isProcessingAnnouncement: false,

            init() {
                this.updateTime();
                setInterval(() => this.updateTime(), 1000);
                this.loadData();
                setInterval(() => this.loadData(), 3000);
            },

            enableAudio() {
                this.audioEnabled = true;
                // Load voices untuk fallback TTS dan cari suara Indonesia
                this.loadIndonesianVoice();
                // Load voices saat tersedia
                if (speechSynthesis.onvoiceschanged !== undefined) {
                    speechSynthesis.onvoiceschanged = () => this.loadIndonesianVoice();
                }
                this.loadData();
            },

            loadIndonesianVoice() {
                const voices = window.speechSynthesis.getVoices();
                // Cari suara Bahasa Indonesia (prioritaskan 'id-ID')
                this.indonesianVoice = voices.find(voice => voice.lang === 'id-ID') ||
                                       voices.find(voice => voice.lang.startsWith('id')) ||
                                       null;
                if (this.indonesianVoice) {
                    console.log('✓ Indonesian voice found:', this.indonesianVoice.name);
                } else {
                    console.warn('⚠ No Indonesian voice found, using default');
                }
            },

            async loadData() {
                try {
                    const res = await fetch('/display/data');
                    const result = await res.json();
                    if (result.success && result.data.polis) {
                        // Process polis to dedup recent history
                        this.polis = result.data.polis.map(poli => {
                            if (poli.recent && Array.isArray(poli.recent)) {
                                // Filter unique numbers (keep latest)
                                const uniqueRecent = [];
                                const seenNumbers = new Set();
                                
                                // Sort by time pending/call desc just in case
                                poli.recent.sort((a, b) => {
                                    const tA = new Date(a.waktu_selesai || a.waktu_panggil).getTime();
                                    const tB = new Date(b.waktu_selesai || b.waktu_panggil).getTime();
                                    return tB - tA;
                                });

                                for (const item of poli.recent) {
                                    if (!seenNumbers.has(item.nomor)) {
                                        seenNumbers.add(item.nomor);
                                        uniqueRecent.push(item);
                                    }
                                }
                                poli.recent = uniqueRecent;
                            }
                            return poli;
                        });
                        
                        this.checkForNewCalls(result.data.polis);
                    }
                    this.loading = false;
                } catch (e) {
                    console.error('Error:', e);
                    this.loading = false;
                }
            },

            checkForNewCalls(newPolis) {
                if (!this.audioEnabled) return;

                newPolis.forEach(item => {
                    if (item.current) {
                        const poliId = item.poli.id;
                        const nomorBaru = item.current.nomor;
                        const waktuPanggilBaru = new Date(item.current.waktu_panggil).getTime();
                        const waktuPanggilLama = this.lastCallTime[poliId] || 0;

                        if (nomorBaru !== this.lastCalled[poliId] || waktuPanggilBaru > waktuPanggilLama) {
                            this.lastCalled[poliId] = nomorBaru;
                            this.lastCallTime[poliId] = waktuPanggilBaru;
                            // Tambah ke global queue, bukan langsung play
                            this.globalAnnouncementQueue.push({
                                nomor: nomorBaru,
                                poli: item.poli
                            });
                            console.log('Added to queue:', nomorBaru, item.poli.nama, '(Queue size:', this.globalAnnouncementQueue.length + ')');
                        }
                    }
                });

                // Proses queue jika tidak sedang memproses
                this.processGlobalQueue();
            },

            async processGlobalQueue() {
                // Jangan proses jika sedang memproses atau queue kosong
                if (this.isProcessingAnnouncement || this.globalAnnouncementQueue.length === 0) {
                    return;
                }

                this.isProcessingAnnouncement = true;

                // Ambil dan hapus dari queue (FIFO)
                const announcement = this.globalAnnouncementQueue.shift();
                console.log('Now playing:', announcement.nomor, announcement.poli.nama, '(Remaining:', this.globalAnnouncementQueue.length + ')');

                // Play announcement
                await this.playAnnouncement(announcement.nomor, announcement.poli);

                // Tunggu selesai, lanjut ke berikutnya
                this.isProcessingAnnouncement = false;

                // Proses berikutnya jika ada
                if (this.globalAnnouncementQueue.length > 0) {
                    setTimeout(() => this.processGlobalQueue(), 500);
                }
            },

            async playAnnouncement(nomor, poli) {
                const parts = nomor.match(/([a-zA-Z]+)-(\d+)/);
                if (!parts) return;

                const huruf = parts[1].toUpperCase();
                const angkaStr = parts[2].padStart(3, '0'); // 001, 015, dll
                const poliSlug = this.getPoliSlug(poli.nama);

                // DEBUG: Log poli details
                console.log('=== AUDIO DEBUG ===');
                console.log('Poli nama (raw):', poli.nama);
                console.log('Poli slug:', poliSlug);
                console.log('Audio path:', `/voice/poli/${poliSlug}.mp3`);

                // Build audio queue: "Bel" + "Nomor antrian" + huruf + digit-per-digit + "silakan ke" + "poli" + nama polis
                this.audioQueue = [
                    { type: 'bell' },  // Suara bel terlebih dahulu
                    { type: 'word', name: 'nomor-antrian' },
                    { type: 'letter', name: huruf },
                    { type: 'number-digits', value: angkaStr },  // Special: play digit by digit
                    { type: 'word', name: 'silakan' },
                    { type: 'word', name: 'ke' },
                    { type: 'word', name: 'poli' },
                    { type: 'poli', name: poliSlug }
                ];

                console.log('Playing announcement:', nomor, poli.nama);
                await this.playNextAudio();
            },

            async playNextAudio() {
                if (this.audioQueue.length === 0) {
                    this.isPlaying = false;
                    return;
                }

                this.isPlaying = true;
                const item = this.audioQueue.shift();

                try {
                    // Special handling for number-digits (play 0-0-1 for 001)
                    if (item.type === 'number-digits') {
                        for (const digit of item.value) {
                            await this.playAudioFile(`${this.audioBaseUrl}numbers/${digit}.mp3`);
                            await this.delay(150); // Jeda antar digit
                        }
                    } else {
                        const audioPath = this.getAudioPath(item);
                        await this.playAudioFile(audioPath);

                        // Extra delay after bell sebelum announcement
                        if (item.type === 'bell') {
                            await this.delay(500); // Jeda 500ms setelah bel
                        }
                    }
                } catch (e) {
                    console.log('Audio not found:', item, '- using fallback');
                    // Fallback to TTS for missing audio
                    await this.fallbackSpeak(item);
                }

                // Jeda sebelum next item
                await this.delay(200);

                // Lanjut ke next item
                await this.playNextAudio();
            },

            delay(ms) {
                return new Promise(resolve => setTimeout(resolve, ms));
            },

            getPoliSlug(poliNama) {
                // Bersihkan nama poli untuk dapat nama file audio
                let slug = poliNama.toLowerCase();

                // Hapus prefix "poli " jika ada
                if (slug.startsWith('poli ')) {
                    slug = slug.replace('poli ', '');
                }
                // Hapus prefix "poli" tanpa spasi
                else if (slug.startsWith('poli')) {
                    slug = slug.replace('poli', '');
                }

                // Hapus karakter non-alphanumeric
                slug = slug.replace(/[^a-z0-9]/g, '_');

                return slug;
            },

            getAudioPath(item) {
                switch (item.type) {
                    case 'letter':
                        return `${this.audioBaseUrl}letters/${item.name}.mp3`;
                    case 'word':
                        return `${this.audioBaseUrl}words/${item.name}.mp3`;
                    case 'poli':
                        return `${this.audioBaseUrl}poli/${item.name}.mp3`;
                    case 'bell':
                        return `${this.audioBaseUrl}bel.mp3`;
                    default:
                        return '';
                }
            },

            playAudioFile(path) {
                return new Promise((resolve, reject) => {
                    const audio = new Audio(path);

                    audio.onended = () => {
                        this.currentAudio = null;
                        resolve();
                    };

                    audio.onerror = () => {
                        this.currentAudio = null;
                        reject(new Error('Audio load failed'));
                    };

                    this.currentAudio = audio;
                    audio.play().catch(reject);
                });
            },

            async fallbackSpeak(item) {
                // Fallback ke browser TTS untuk audio yang tidak ada
                let text = '';

                if (item.type === 'number-digits') {
                    // Baca digit per digit: "Nol Nol Satu"
                    const digitWords = ['Nol', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan'];
                    text = item.value.split('').map(d => digitWords[parseInt(d)] || d).join(' ');
                } else if (item.type === 'poli') {
                    // Poli nama
                    const poliMap = { 'umum': 'Umum', 'gigi': 'Gigi', 'anak': 'Anak' };
                    text = poliMap[item.name] || item.name.replace(/_/g, ' ');
                } else {
                    text = this.getItemText(item);
                }

                if (!text) return;

                return new Promise((resolve) => {
                    const utterance = new SpeechSynthesisUtterance(text);
                    utterance.lang = 'id-ID';
                    utterance.rate = 0.9;

                    // Gunakan suara Indonesia jika tersedia
                    if (this.indonesianVoice) {
                        utterance.voice = this.indonesianVoice;
                    }

                    utterance.onend = () => resolve();
                    window.speechSynthesis.speak(utterance);
                });
            },

            getItemText(item) {
                switch (item.type) {
                    case 'letter': return item.name;
                    case 'word':
                        const wordMap = { 'nomor-antrian': 'Nomor antrian', 'silakan': 'Silakan', 'ke': 'Ke', 'poli': 'Poli' };
                        return wordMap[item.name] || '';
                    default: return '';
                }
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
