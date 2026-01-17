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
        * { font-family: 'Inter', system-ui, sans-serif; }
        body { background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); min-height: 100vh; }
        .poli-card { background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%); border-radius: 24px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); overflow: hidden; }
        .current-number { font-size: 80px; font-weight: 900; line-height: 1; background: linear-gradient(135deg, #0ea5e9 0%, #0369a1 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .recent-item { background: #f1f5f9; border-radius: 12px; padding: 12px 16px; }
        .status-badge { display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; border-radius: 20px; font-size: 14px; font-weight: 600; background: #fef3c7; color: #92400e; }
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

        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
            <template x-for="item in polis" :key="item.poli.id">
                <div class="poli-card">
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
                    <div class="p-6 text-center">
                        <p class="text-gray-500 text-sm mb-2">Nomor Sedang Dilayani</p>
                        <div class="current-number" x-text="item.current ? item.current.nomor : '--'"></div>
                    </div>
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
                    <div class="px-6 pb-6">
                        <div class="bg-blue-50 rounded-xl p-4 flex items-center justify-between">
                            <span class="text-blue-700">Menunggu</span>
                            <span class="text-2xl font-bold text-blue-700" x-text="item.waiting_count || 0"></span>
                        </div>
                    </div>
                </div>
            </template>
        </div>

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
                        this.polis = result.data.polis;
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

                // Build audio queue: "Nomor antrian" + huruf + digit-per-digit + "silakan ke" + "poli" + nama polis
                this.audioQueue = [
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
