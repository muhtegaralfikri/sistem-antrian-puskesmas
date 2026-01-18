<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Antrian - Puskesmas</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: { 50:'#eff6ff', 100:'#dbeafe', 200:'#bfdbfe', 300:'#93c5fd', 400:'#60a5fa', 500:'#3b82f6', 600:'#2563eb', 700:'#1d4ed8', 800:'#1e40af', 900:'#1e3a8a' },
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        mono: ['JetBrains Mono', 'monospace'],
                    },
                    animation: {
                        'gradient': 'gradient 8s ease infinite',
                        'scroll': 'scroll 20s linear infinite',
                    },
                    keyframes: {
                        gradient: {
                            '0%, 100%': { backgroundPosition: '0% 50%' },
                            '50%': { backgroundPosition: '100% 50%' },
                        },
                        scroll: {
                            '0%': { transform: 'translateX(100%)' },
                            '100%': { transform: 'translateX(-100%)' },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { overflow: hidden; }
        .glass { background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.5); }
        .hero-pattern {
            background-color: #f8fafc;
            background-image: radial-gradient(#e2e8f0 1px, transparent 1px);
            background-size: 40px 40px;
        }
    </style>
</head>
<body x-data="displayData()" class="h-screen flex flex-col hero-pattern text-gray-800">

    <!-- Audio Permission Overlay -->
    <div x-show="!audioEnabled" class="fixed inset-0 z-50 flex items-center justify-center bg-black/90 backdrop-blur-md">
        <div class="text-center bg-white p-10 rounded-3xl shadow-2xl max-w-lg mx-4 border-4 border-primary-100">
            <div class="mb-8 w-24 h-24 bg-primary-50 rounded-full flex items-center justify-center mx-auto animate-bounce">
                <svg class="w-12 h-12 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/>
                </svg>
            </div>
            <h2 class="text-3xl font-bold text-gray-900 mb-3">Display Antrian TV</h2>
            <p class="text-gray-500 mb-8 text-lg">Klik untuk memulai mode layar penuh & audio</p>
            <button @click="enableAudio()" class="w-full bg-primary-600 hover:bg-primary-700 text-white px-8 py-4 rounded-xl font-bold text-xl shadow-lg shadow-primary-600/30 transition-all transform hover:scale-105 active:scale-95">
                Mulai Display
            </button>
        </div>
    </div>

    <!-- Top Bar -->
    <header class="bg-white/90 backdrop-blur-md border-b border-gray-200 h-24 flex-none z-40 relative">
        <div class="h-full px-8 flex items-center justify-between">
            <div class="flex items-center gap-6">
                <!-- Logo Layout -->
                <div class="w-16 h-16 bg-gradient-to-br from-primary-600 to-primary-800 rounded-2xl flex items-center justify-center shadow-lg shadow-primary-600/20 text-white font-bold text-2xl">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                </div>
                <div>
                    <h1 class="text-3xl font-black text-gray-900 tracking-tight leading-none">PUSKESMAS SEHAT</h1>
                    <p class="text-gray-500 font-medium tracking-wide">Sistem Antrian Terpadu</p>
                </div>
            </div>
            
            <div class="flex items-center gap-8">
                <div class="text-right">
                    <div class="text-4xl font-black font-mono text-gray-900 tracking-tight" x-text="currentTime">--:--</div>
                    <div class="text-gray-500 font-medium" x-text="currentDate">-- -- --</div>
                </div>
            </div>
        </div>
    </header>

    <!-- Content Area -->
    <div class="flex-1 min-h-0 p-4 md:p-6 gap-4 md:gap-6 flex flex-col md:flex-row relative z-10">
        
        <!-- Left: Active Call / Video -->
        <div class="w-full md:w-7/12 flex flex-col gap-4 md:gap-6 h-full">
            
            <!-- Hero Card (Active Call) -->
            <div class="bg-white rounded-3xl shadow-xl border border-gray-100 flex-1 min-h-0 flex flex-col items-center justify-center relative overflow-hidden group">
                <!-- Background Decoration -->
                <div class="absolute inset-0 bg-gradient-to-br from-primary-50 to-white"></div>
                
                <div class="relative z-10 text-center w-full px-4 md:px-12 flex flex-col items-center justify-center h-full">
                    <template x-if="activeCall">
                        <div class="animate-pulse-once flex flex-col items-center justify-center h-full py-4">
                            <span class="inline-block px-4 py-1.5 rounded-full bg-primary-100 text-primary-700 font-bold text-sm md:text-lg uppercase tracking-widest mb-2 md:mb-6 shrink-0">
                                Sedang Dipanggil
                            </span>
                            
                            <div class="leading-none font-black font-mono text-gray-900 tracking-tighter shrink-0"
                                 style="font-size: clamp(80px, 20vh, 180px);"
                                 x-text="activeCall.nomor">
                                --
                            </div>
                            
                            <div class="text-2xl md:text-4xl font-bold text-gray-600 mt-2 md:mt-6 mb-2 shrink-0">Silakan Menuju</div>
                            <div class="text-3xl md:text-5xl font-black text-primary-600 bg-white/50 backdrop-blur-sm px-6 py-2 md:p-4 rounded-xl border border-primary-100 inline-block shadow-sm shrink-0 max-w-full truncate"
                                 x-text="activeCall.poli">
                                --
                            </div>
                        </div>
                    </template>
                    
                    <template x-if="!activeCall">
                        <div class="flex flex-col items-center opacity-50">
                            <svg class="w-20 h-20 md:w-32 md:h-32 text-gray-300 mb-4 md:mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <h2 class="text-2xl md:text-3xl font-bold text-gray-400">Menunggu Panggilan...</h2>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Info / Video Placeholder (Bottom Left) -->
            <div class="h-auto min-h-[140px] md:h-48 bg-gradient-to-r from-gray-900 to-gray-800 rounded-3xl shadow-lg border border-gray-700 flex items-center justify-center relative overflow-hidden text-white p-6 md:p-8 shrink-0">
                <!-- Simple animation or placeholder for video -->
                <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
                <div class="flex items-center gap-4 md:gap-6 z-10">
                    <div class="w-12 h-12 md:w-16 md:h-16 bg-white/10 rounded-full flex items-center justify-center backdrop-blur shrink-0">
                        <svg class="w-6 h-6 md:w-8 md:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-xl md:text-2xl font-bold mb-1">Budayakan Antre</h3>
                        <p class="text-gray-300 text-sm md:text-lg text-pretty">Mohon menunggu nomor antrian Anda dipanggil.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: List of Polis -->
        <div class="w-full md:w-5/12 flex flex-col gap-4 overflow-hidden h-full">
            <template x-for="(poli, index) in polis" :key="poli.poli.id">
                <div class="flex-1 bg-white rounded-2xl shadow-sm border border-gray-100 p-4 md:p-6 flex items-center justify-between transition-all duration-500"
                     :class="poli.current && activeCall && activeCall.nomor === poli.current.nomor ? 'ring-4 ring-primary-400 scale-[1.02] shadow-xl z-10' : 'opacity-90'">
                    
                    <div class="flex flex-col justify-center overflow-hidden mr-2">
                        <h3 class="text-lg md:text-2xl font-bold text-gray-700 mb-1 md:mb-2 truncate" x-text="poli.poli.nama"></h3>
                        <div class="flex items-center gap-2">
                             <div :class="poli.current ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500'" class="px-3 py-1 rounded-lg text-sm font-bold flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full" :class="poli.current ? 'bg-green-500 animate-pulse' : 'bg-gray-400'"></span>
                                <span x-text="poli.current ? 'Melayani' : 'Menunggu'"></span>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col items-end">
                        <div class="text-6xl font-black font-mono tracking-tighter" 
                             :class="poli.current ? 'text-gray-900' : 'text-gray-300'"
                             x-text="poli.current ? poli.current.nomor : '--'">
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <!-- Running Text Footer -->
    <footer class="bg-primary-900 text-white h-16 flex-none flex items-center overflow-hidden z-20 relative shadow-[0_-4px_20px_rgba(0,0,0,0.2)]">
        <div class="bg-primary-700 px-6 h-full flex items-center z-20 font-bold tracking-widest text-sm relative shadow-lg">
            INFO
        </div>
        <div class="whitespace-nowrap flex-1 overflow-hidden relative">
            <div class="animate-scroll absolute top-1/2 -translate-y-1/2 font-medium text-lg tracking-wide w-full" style="width: 200%">
               Selamat Datang di Puskesmas Percontohan. Mohon menunggu panggilan sesuai nomor antrian. Jam operasional: Senin - Jumat (08:00 - 15:00), Sabtu (08:00 - 12:00). Tetap patuhi protokol kesehatan. Terima kasih. &nbsp;&nbsp;&bull;&nbsp;&nbsp; Budayakan Antre untuk kenyamanan bersama. &nbsp;&nbsp;&bull;&nbsp;&nbsp; Silakan ambil nomor antrian di Kiosk jika belum memiliki tiket.
            </div>
        </div>
    </footer>

    <script>
    function displayData() {
        return {
            polis: [],
            loading: true,
            currentTime: '',
            currentDate: '',
            activeCall: null, // { nomor: 'A-001', poli: 'Poli Umum' }
            
            // Audio vars
            audioEnabled: false,
            audioBaseUrl: '/voice/',
            audioQueue: [],
            indonesianVoice: null,
            isPlaying: false,
            
            // Logic vars
            globalAnnouncementQueue: [],
            lastCalled: {},
            lastCallTime: {},
            isProcessingAnnouncement: false,

            init() {
                this.updateTime();
                setInterval(() => this.updateTime(), 1000);
            },

            enableAudio() {
                this.audioEnabled = true;
                this.requestFullScreen();
                this.loadIndonesianVoice();
                this.loadData();
                setInterval(() => this.loadData(), 2000); // 2s polling
            },
            
            requestFullScreen() {
                const elem = document.documentElement;
                if (elem.requestFullscreen) elem.requestFullscreen().catch(err => console.log(err));
            },

            async loadData() {
                if(!this.audioEnabled) return;
                
                try {
                    const res = await fetch('/display/data');
                    const result = await res.json();
                    if (result.success && result.data.polis) {
                         this.polis = result.data.polis; // Direct assign, list is minimal
                         this.checkForNewCalls(result.data.polis);
                    }
                } catch (e) {
                    console.error("Connection Error");
                }
            },
            
            // --- Audio Logic (Preserved & Enhanced) ---
            
            loadIndonesianVoice() {
                const voices = window.speechSynthesis.getVoices();
                this.indonesianVoice = voices.find(v => v.lang.includes('id')) || null;
                if (speechSynthesis.onvoiceschanged !== undefined) {
                    speechSynthesis.onvoiceschanged = () => {
                         this.indonesianVoice = window.speechSynthesis.getVoices().find(v => v.lang.includes('id')) || null;
                    };
                }
            },

            checkForNewCalls(newPolis) {
                newPolis.forEach(item => {
                    if (item.current) {
                        const poliId = item.poli.id;
                        const nomorBaru = item.current.nomor;
                        const waktuPanggilBaru = new Date(item.current.waktu_panggil).getTime();
                        const waktuPanggilLama = this.lastCallTime[poliId] || 0;

                        if (nomorBaru !== this.lastCalled[poliId] || waktuPanggilBaru > waktuPanggilLama) {
                            this.lastCalled[poliId] = nomorBaru;
                            this.lastCallTime[poliId] = waktuPanggilBaru;
                            this.globalAnnouncementQueue.push({ nomor: nomorBaru, poli: item.poli });
                        }
                    }
                });
                this.processGlobalQueue();
            },

            async processGlobalQueue() {
                if (this.isProcessingAnnouncement || this.globalAnnouncementQueue.length === 0) return;
                this.isProcessingAnnouncement = true;

                const announcement = this.globalAnnouncementQueue.shift();
                
                // === VISUAL UPDATE ===
                // Set active call large display
                this.activeCall = {
                    nomor: announcement.nomor,
                    poli: announcement.poli.nama
                };

                // Remove active call display after 10 seconds automatically if needed
                // setTimeout(() => { if(this.activeCall?.nomor === announcement.nomor) this.activeCall = null }, 15000);

                await this.playAnnouncement(announcement.nomor, announcement.poli);
                this.isProcessingAnnouncement = false;
                
                if (this.globalAnnouncementQueue.length > 0) {
                     setTimeout(() => this.processGlobalQueue(), 500);
                }
            },

            async playAnnouncement(nomor, poli) {
                const parts = nomor.match(/([a-zA-Z]+)-(\d+)/);
                if (!parts) return;

                const huruf = parts[1].toUpperCase();
                const angkaStr = parts[2].padStart(3, '0');
                const poliSlug = this.getPoliSlug(poli.nama);

                this.audioQueue = [
                   { type: 'bell' },
                   { type: 'word', name: 'nomor-antrian' },
                   { type: 'letter', name: huruf },
                   { type: 'number-digits', value: angkaStr },
                   { type: 'word', name: 'silakan' },
                   { type: 'word', name: 'ke' },
                   { type: 'word', name: 'poli' },
                   { type: 'poli', name: poliSlug }
                ];
                
                await this.playNextAudio();
            },
            
            async playNextAudio() {
                if(this.audioQueue.length === 0) return;
                
                const item = this.audioQueue.shift();
                try {
                    if (item.type === 'number-digits') {
                        for (const digit of item.value) {
                             await this.playAudioFile(`${this.audioBaseUrl}numbers/${digit}.mp3`);
                             await this.delay(150);
                        }
                    } else {
                        const path = this.getAudioPath(item);
                        await this.playAudioFile(path);
                        if (item.type === 'bell') await this.delay(500);
                    }
                } catch (e) {
                    await this.fallbackSpeak(item);
                }
                await this.delay(100);
                await this.playNextAudio();
            },

            playAudioFile(path) {
                return new Promise((resolve, reject) => {
                    const audio = new Audio(path);
                    audio.onended = resolve;
                    audio.onerror = reject;
                    audio.play().catch(reject);
                });
            },

            async fallbackSpeak(item) {
                 let text = '';
                 if (item.type === 'number-digits') {
                     const digitWords = ['Nol', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan'];
                     text = item.value.split('').map(d => digitWords[parseInt(d)]).join(' ');
                 } else if (item.type === 'poli') {
                     text = item.name.replace(/_/g, ' ');
                 } else {
                     text = this.getItemText(item);
                 }
                 if(!text) return;

                 return new Promise(resolve => {
                     const u = new SpeechSynthesisUtterance(text);
                     u.lang = 'id-ID';
                     u.rate = 0.9;
                     if(this.indonesianVoice) u.voice = this.indonesianVoice;
                     u.onend = resolve;
                     window.speechSynthesis.speak(u);
                 });
            },
            
            // ... (Helpers: getPoliSlug, getAudioPath, getItemText, delay - reused from prev code)
            getPoliSlug(n) { return n.toLowerCase().replace('poli ', '').replace(/[^a-z0-9]/g, '_'); },
            getAudioPath(i) {
                switch(i.type) {
                    case 'letter': return `${this.audioBaseUrl}letters/${i.name}.mp3`;
                    case 'word': return `${this.audioBaseUrl}words/${i.name}.mp3`;
                    case 'poli': return `${this.audioBaseUrl}poli/${i.name}.mp3`;
                    case 'bell': return `${this.audioBaseUrl}bel.mp3`;
                    default: return '';
                }
            },
            getItemText(i) {
                const map = { 'nomor-antrian': 'Nomor antrian', 'silakan': 'Silakan ke', 'ke': ' ', 'poli': 'Poli' };
                return map[i.name] || i.name;
            },
            delay(ms) { return new Promise(r => setTimeout(r, ms)); },
            
            updateTime() {
                const now = new Date();
                this.currentTime = now.toLocaleTimeString('id-ID', {hour:'2-digit', minute:'2-digit'});
                this.currentDate = now.toLocaleDateString('id-ID', {weekday:'long', day:'numeric', month:'long', year:'numeric'});
            }
        };
    }
    </script>
</body>
</html>
