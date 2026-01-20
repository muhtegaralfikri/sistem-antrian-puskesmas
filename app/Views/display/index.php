<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="/images/logo.png">
    <title>Display Antrian - Puskesmas</title>
    <link rel="stylesheet" href="/css/app.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@700&display=swap" rel="stylesheet">
    <style>
        /* Desktop: Fixed, No Scroll */
        @media (min-width: 768px) {
            body { overflow: hidden; height: 100vh; }
        }
        /* Mobile: Scroll allowed */
        @media (max-width: 767px) {
            body { overflow-y: auto; height: auto; min-height: 100vh; }
        }

        .glass-panel {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.6);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        }
        .hero-pattern {
            background-color: #f0fdfa;
            background-image: radial-gradient(#ccfbf1 1px, transparent 1px);
            background-size: 32px 32px;
        }
        /* Hide scrollbar for queue list */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        /* Mobile specific styles */
        @media (max-width: 640px) {
            .queue-card {
                padding: 0.5rem !important;
            }
            .queue-number {
                font-size: 1.5rem !important;
            }
            .active-number {
                font-size: clamp(60px, 15vw, 100px) !important;
            }
            .poli-name {
                font-size: 1.25rem !important;
            }
        }

        @media (min-width: 641px) and (max-width: 1024px) {
            /* Tablet styles */
            .active-number {
                font-size: clamp(80px, 12vw, 140px) !important;
            }
        }
    </style>
</head>
<body x-data="displayData()" class="flex flex-col hero-pattern text-gray-800 relative">

    <!-- Decorative Blobs -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden z-0">
        <div class="absolute top-0 left-1/4 w-64 h-64 md:w-96 md:h-96 bg-primary-200 rounded-full mix-blend-multiply filter blur-2xl md:blur-3xl opacity-30 animate-blob"></div>
        <div class="absolute top-0 right-1/4 w-64 h-64 md:w-96 md:h-96 bg-medical-200 rounded-full mix-blend-multiply filter blur-2xl md:blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
        <div class="absolute -bottom-32 left-1/3 w-64 h-64 md:w-96 md:h-96 bg-pink-200 rounded-full mix-blend-multiply filter blur-2xl md:blur-3xl opacity-30 animate-blob animation-delay-4000"></div>
    </div>

    <!-- Audio Permission Overlay -->
    <div x-show="!audioEnabled" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm transition-opacity duration-500">
        <div class="text-center bg-white p-6 md:p-10 rounded-3xl shadow-2xl max-w-lg mx-4 border border-white/50 relative overflow-hidden">
             <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-primary-400 to-medical-400"></div>
            <div class="mb-4 md:mb-6 w-16 h-16 md:w-20 md:h-20 bg-gradient-to-br from-primary-100 to-medical-100 rounded-2xl flex items-center justify-center mx-auto shadow-inner">
                <svg class="w-8 h-8 md:w-10 md:h-10 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/>
                </svg>
            </div>
            <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-1 md:mb-2">Selamat Datang</h2>
            <p class="text-sm md:text-base text-gray-500 mb-4 md:mb-8">Klik tombol di bawah untuk mengaktifkan suara antrian.</p>
            <button @click="enableAudio()" class="w-full bg-slate-900 hover:bg-slate-800 text-white px-6 md:px-8 py-3 md:py-3.5 rounded-xl font-bold text-base md:text-lg shadow-lg shadow-slate-900/20 transition-all transform hover:scale-[1.02] active:scale-[0.98]">
                Mulai Aplikasi
            </button>
        </div>
    </div>

    <!-- Top Bar -->
    <header class="h-14 sm:h-16 md:h-20 lg:h-24 flex-none z-40 px-3 sm:px-4 md:px-6 lg:px-8 flex items-center justify-between relative bg-white/50 backdrop-blur-sm md:bg-transparent">
        <div class="flex items-center gap-2 md:gap-4">
            <img src="/images/logo.png" alt="Logo Puskesmas" class="w-12 h-12 md:w-16 md:h-16 object-contain drop-shadow-md">
            <div>
                <h1 class="text-base sm:text-lg md:text-xl lg:text-2xl font-black text-gray-900 leading-tight">PUSKESMAS<span class="text-medical-600">SEHAT</span></h1>
                <p class="text-[10px] sm:text-xs md:text-sm text-gray-500 font-medium tracking-wide uppercase hidden sm:block">Melayani Sepenuh Hati</p>
            </div>
        </div>

        <div class="flex items-center gap-3 md:gap-6">
            <!-- Desktop Time -->
            <div class="text-right hidden lg:block">
                <div class="text-2xl md:text-3xl font-bold text-gray-900 tabular-nums tracking-tight" x-text="currentTime">--:--</div>
                <div class="text-xs md:text-sm text-gray-500 font-medium" x-text="currentDate">-- -- --</div>
            </div>
            <!-- Tablet Time -->
            <div class="text-right hidden md:block lg:hidden">
                <div class="text-xl md:text-2xl font-bold text-gray-900 tabular-nums tracking-tight" x-text="currentTime">--:--</div>
                <div class="text-[10px] md:text-xs text-gray-500 font-medium" x-text="currentDate">-- -- --</div>
            </div>
            <!-- Mobile Time -->
            <div class="text-right md:hidden">
                <div class="text-lg font-bold text-gray-900 tabular-nums" x-text="currentTime">--:--</div>
            </div>
        </div>
    </header>

    <!-- Content Layout -->
    <div class="flex-1 p-2 sm:p-3 md:p-4 lg:p-6 md:pt-0 gap-2 md:gap-4 lg:gap-6 flex flex-col md:flex-row relative z-10 md:overflow-hidden h-full">

        <!-- Main Area: Active Call -->
        <div class="w-full md:w-full lg:w-7/12 xl:w-7/12 flex flex-col gap-2 md:gap-4 lg:gap-6 md:h-full">

            <!-- Active Call Card (Premium) -->
            <div class="glass-panel rounded-[1rem] md:rounded-[1.5rem] lg:rounded-[2rem] flex flex-col items-center justify-center relative overflow-hidden flex-1 transition-all duration-700 min-h-[35vh] md:min-h-0 h-full">
                <div class="absolute top-0 w-full h-1 md:h-1.5 bg-gradient-to-r from-medical-400 via-primary-400 to-medical-400"></div>

                <template x-if="activeCall">
                    <div class="relative z-10 w-full h-full flex flex-col items-center justify-between py-6 md:py-10 px-4 md:justify-evenly">

                        <!-- Status Badge -->
                        <div class="animate-pulse shrink-0">
                            <span class="px-4 py-1.5 md:px-6 md:py-2 rounded-full bg-medical-50 text-medical-700 font-bold text-xs md:text-base lg:text-lg uppercase tracking-[0.2em] border border-medical-100 shadow-sm">
                                Panggilan Saat Ini
                            </span>
                        </div>

                        <!-- Main Number Display -->
                        <div class="relative shrink-0 flex flex-col items-center justify-center flex-1 w-full my-4">
                            <span class="active-number font-black font-mono text-slate-800 tracking-tighter leading-none drop-shadow-sm select-none whitespace-nowrap"
                                  :style="'font-size: clamp(80px, 18vw, 220px)'"
                                  x-text="activeCall.nomor">
                                --
                            </span>
                            <!-- Patient Name Badge -->
                            <template x-if="activeCall.nama">
                                <div class="mt-4 md:mt-6 px-6 py-2 md:py-3 bg-gradient-to-r from-primary-50 to-medical-50 rounded-2xl border border-primary-100 shadow-sm">
                                    <span class="text-lg md:text-2xl lg:text-3xl font-bold text-primary-700 tracking-wide" x-text="activeCall.nama"></span>
                                </div>
                            </template>
                        </div>

                        <!-- Divider Line -->
                        <div class="w-24 h-1 md:w-32 md:h-1.5 bg-gray-200 rounded-full shrink-0 mb-6"></div>

                        <!-- Poli & Label -->
                        <div class="text-center shrink-0 w-full px-4">
                            <p class="text-gray-400 font-bold text-xs md:text-lg uppercase tracking-widest mb-2">Silakan Menuju Ke</p>
                            <div class="relative w-full flex justify-center items-center">
                                <h2 class="poli-name text-3xl md:text-4xl lg:text-5xl xl:text-6xl font-black text-medical-700 tracking-tight leading-tight break-words text-wrap drop-shadow-sm"
                                    x-text="activeCall.poli">
                                    --
                                </h2>
                            </div>
                        </div>

                    </div>
                </template>

                <template x-if="!activeCall">
                    <div class="flex flex-col items-center opacity-40 justify-center h-full gap-4">
                        <div class="w-24 h-24 md:w-32 md:h-32 bg-gray-100 rounded-full flex items-center justify-center shadow-inner">
                            <svg class="w-12 h-12 md:w-16 md:h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/></svg>
                        </div>
                        <h2 class="text-xl md:text-3xl font-bold text-gray-400 tracking-tight">Menunggu Antrian...</h2>
                    </div>
                </template>
            </div>

        </div>

        <!-- Right Column: Queue Sidebar -->
        <div class="w-full md:w-full lg:w-5/12 xl:w-5/12 flex flex-col md:h-full relative pb-28 md:pb-0">
            <div class="flex-1 grid grid-cols-1 gap-3 md:gap-4 lg:gap-5 content-start md:overflow-y-auto no-scrollbar p-1 md:p-0">

                <!-- Mobile: Horizontal scroll for queue cards -->
                <div class="md:hidden flex overflow-x-auto gap-2 pb-2 -mx-2 px-2">
                    <template x-for="(poli, index) in polis" :key="poli.poli.id">
                        <div class="queue-card flex-shrink-0 w-40 bg-white/80 backdrop-blur-sm p-4 rounded-xl border border-white/60 shadow-sm transition-all duration-300"
                             :class="poli.current && activeCall && activeCall.nomor === poli.current.nomor ? 'ring-2 ring-medical-400 shadow-md bg-white' : 'hover:bg-white/90'">

                            <div class="flex items-center justify-between mb-3">
                                <h4 class="text-sm font-bold text-gray-800 truncate flex-1" x-text="poli.poli.nama"></h4>
                                <div class="flex items-center gap-1.5 ml-2">
                                    <span class="w-1.5 h-1.5 rounded-full flex-shrink-0" :class="poli.current ? 'bg-green-500 animate-pulse' : 'bg-gray-300'"></span>
                                </div>
                            </div>

                            <div class="queue-number font-mono font-black text-3xl whitespace-nowrap"
                                 :class="poli.current ? 'text-gray-900' : 'text-gray-300'"
                                 x-text="poli.current ? poli.current.nomor : '--'">
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Desktop: Vertical queue cards -->
                <div class="hidden md:grid grid-cols-1 gap-4 lg:gap-5">
                    <template x-for="(poli, index) in pagedPolis" :key="poli.poli.id">
                        <div class="bg-white/85 backdrop-blur-md p-5 lg:p-6 rounded-2xl border border-white/70 shadow-sm transition-all duration-300 group"
                             :class="poli.current && activeCall && activeCall.nomor === poli.current.nomor ? 'ring-4 ring-medical-200 shadow-xl scale-[1.02] bg-white z-10' : 'hover:shadow-md'">
                            
                            <div class="flex items-center justify-between">
                                <div class="flex flex-col gap-1 min-w-0 flex-1">
                                    <h4 class="text-lg lg:text-xl font-bold text-gray-700 truncate group-hover:text-medical-700 transition-colors" x-text="poli.poli.nama"></h4>
                                    <div class="flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full" :class="poli.current ? 'bg-green-500 animate-pulse' : 'bg-gray-300'"></span>
                                        <span class="text-xs font-bold uppercase tracking-wider"
                                              :class="poli.current ? 'text-green-600' : 'text-gray-400'"
                                              x-text="poli.current ? 'Melayani' : 'Menunggu'"></span>
                                    </div>
                                </div>

                                <div class="queue-number font-mono font-black text-4xl lg:text-5xl whitespace-nowrap"
                                     :class="poli.current ? 'text-gray-900' : 'text-gray-300'"
                                     x-text="poli.current ? poli.current.nomor : '--'">
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

            </div>

            <!-- Page Indicators -->
            <div class="fixed bottom-14 md:bottom-16 lg:bottom-18 left-0 right-0 flex justify-center gap-1.5 py-2 md:py-3 z-30 pointer-events-none md:absolute">
                <template x-for="i in totalPages">
                    <div class="h-1.5 md:h-2 w-1.5 md:w-2 rounded-full transition-all duration-500 shadow-sm"
                         :class="currentPage === i ? 'bg-gray-800 w-3 md:w-4' : 'bg-gray-300'"></div>
                </template>
            </div>
        </div>

    </div>

    <!-- Running Text Footer -->
    <footer class="h-10 md:h-12 lg:h-14 bg-slate-900 text-white flex-none flex items-center relative z-20 overflow-hidden fixed bottom-0 w-full shadow-2xl">
        <div class="relative h-full flex items-center px-4 z-20">
             <div class="bg-medical-600 text-white px-4 md:px-6 py-1 md:py-1.5 rounded-full font-bold tracking-widest text-[10px] md:text-xs lg:text-sm shadow-lg border border-medical-400 flex items-center gap-2">
                <span class="w-1.5 h-1.5 md:w-2 md:h-2 bg-white rounded-full animate-pulse"></span>
                <span class="hidden sm:inline">INFORMASI</span>
                <span class="sm:hidden">INFO</span>
            </div>
        </div>

        <div class="flex-1 overflow-hidden relative h-full flex items-center">
            <div class="animate-scroll whitespace-nowrap absolute font-medium text-[10px] md:text-sm lg:text-base text-gray-200 tracking-wide w-auto py-1">
                Selamat Datang di Puskesmas Sehat. Budayakan antre untuk kenyamanan bersama. &nbsp;&nbsp;✦&nbsp;&nbsp; Jam Operasional: 08:00 - 15:00 WIB. &nbsp;&nbsp;✦&nbsp;&nbsp; Jagalah kebersihan lingkungan. &nbsp;&nbsp;✦&nbsp;&nbsp; Gunakan masker jika sedang batuk atau flu.
            </div>
        </div>
    </footer>

    <script>
    function displayData() {
        return {
            polis: [],
            pagedPolis: [],
            currentPage: 1,
            itemsPerPage: 4,
            totalPages: 1,
            pageInterval: null,

            loading: true,
            currentTime: '',
            currentDate: '',
            activeCall: null,

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
                this.startPagination();

                // Responsive items per page
                this.adjustItemsPerPage();
                window.addEventListener('resize', () => {
                     this.adjustItemsPerPage();
                });
            },

            adjustItemsPerPage() {
                const width = window.innerWidth;
                const height = window.innerHeight;

                // Mobile portrait - show all in horizontal scroll
                if (width < 768) {
                    this.itemsPerPage = 10; // Show all in horizontal scroll
                }
                // Tablet portrait / small desktop
                else if (width < 1024 || height < 800) {
                    this.itemsPerPage = 3;
                }
                // Standard desktop
                else if (height < 900) {
                    this.itemsPerPage = 4;
                }
                // Large desktop
                else if (height < 1200) {
                    this.itemsPerPage = 5;
                }
                // Extra large
                else {
                    this.itemsPerPage = 6;
                }
                this.updatePagedPolis();
            },

            enableAudio() {
                this.audioEnabled = true;
                this.requestFullScreen();
                this.loadIndonesianVoice();
                this.loadData();
                setInterval(() => this.loadData(), 2000);
            },

            requestFullScreen() {
                const elem = document.documentElement;
                if (elem.requestFullscreen) elem.requestFullscreen().catch(err => console.log(err));
            },

            startPagination() {
                if(this.pageInterval) clearInterval(this.pageInterval);
                this.pageInterval = setInterval(() => {
                    this.nextPage();
                }, 8000);
            },

            nextPage() {
                if (this.totalPages <= 1) return;
                this.currentPage++;
                if (this.currentPage > this.totalPages) this.currentPage = 1;
                this.updatePagedPolis();
            },

            updatePagedPolis() {
                if (!this.polis || this.polis.length === 0) {
                    this.pagedPolis = [];
                    return;
                }
                this.totalPages = Math.ceil(this.polis.length / this.itemsPerPage) || 1;
                if (this.currentPage > this.totalPages) this.currentPage = 1;

                const start = (this.currentPage - 1) * this.itemsPerPage;
                const end = start + this.itemsPerPage;
                this.pagedPolis = this.polis.slice(start, end);
            },

            async loadData() {
                if(!this.audioEnabled) return;

                try {
                    const res = await fetch('/display/data');
                    const result = await res.json();
                    if (result.success && result.data.polis) {
                         this.polis = result.data.polis;
                         this.updatePagedPolis();
                         this.checkForNewCalls(result.data.polis);
                    }
                } catch (e) {
                    // Silent fail
                }
            },

            // --- Audio Logic ---

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
                            this.globalAnnouncementQueue.push({ 
                                nomor: nomorBaru, 
                                poli: item.poli,
                                nama: item.current.nama_pasien || null
                            });
                        }
                    }
                });
                this.processGlobalQueue();
            },

            async processGlobalQueue() {
                if (this.isProcessingAnnouncement || this.globalAnnouncementQueue.length === 0) return;
                this.isProcessingAnnouncement = true;

                const announcement = this.globalAnnouncementQueue.shift();

                this.activeCall = {
                    nomor: announcement.nomor,
                    poli: announcement.poli.nama,
                    nama: announcement.nama
                };

                await this.playAnnouncement(announcement.nomor, announcement.poli);
                this.isProcessingAnnouncement = false;

                if (this.globalAnnouncementQueue.length > 0) {
                     setTimeout(() => this.processGlobalQueue(), 500);
                }
            },

            getNumberAudioParts(n) {
                const parts = [];
                n = parseInt(n);
                if (n < 10) { parts.push(n.toString()); }
                else if (n === 10) { parts.push('sepuluh'); }
                else if (n === 11) { parts.push('sebelas'); }
                else if (n < 20) { parts.push((n - 10).toString()); parts.push('belas'); }
                else if (n < 100) { parts.push(Math.floor(n / 10).toString()); parts.push('puluh'); if (n % 10 > 0) parts.push((n % 10).toString()); }
                else if (n < 200) { parts.push('seratus'); if (n % 100 > 0) parts.push(...this.getNumberAudioParts(n % 100)); }
                else if (n < 1000) { parts.push(Math.floor(n / 100).toString()); parts.push('ratus'); if (n % 100 > 0) parts.push(...this.getNumberAudioParts(n % 100)); }
                return parts;
            },

            async playAnnouncement(nomor, poli) {
                const parts = nomor.match(/([a-zA-Z]+)-(\d+)/);
                if (!parts) return;
                const huruf = parts[1].toUpperCase();
                const angkaInt = parseInt(parts[2], 10);
                const poliSlug = this.getPoliSlug(poli.nama);

                this.audioQueue = [
                   { type: 'bell' },
                   { type: 'word', name: 'nomor-antrian' },
                   { type: 'letter', name: huruf }
                ];
                const numberParts = this.getNumberAudioParts(angkaInt);
                numberParts.forEach(part => { this.audioQueue.push({ type: 'number_file', name: part }); });
                this.audioQueue.push(
                   { type: 'word', name: 'poli' },
                   { type: 'poli', name: poliSlug },
                   { type: 'word', name: 'silakan' }
                );
                await this.playNextAudio();
            },

            async playNextAudio() {
                if(this.audioQueue.length === 0) return;
                const item = this.audioQueue.shift();
                try {
                    let path = '';
                    if (item.type === 'number_file') { path = `${this.audioBaseUrl}numbers/${item.name}.mp3`; }
                    else { path = this.getAudioPath(item); }

                    if (path) {
                        await this.playAudioFile(path);
                        if (item.type === 'bell') await this.delay(500);
                        if (item.type === 'number_file') await this.delay(50);
                    }
                } catch (e) {}
                await this.delay(50);
                await this.playNextAudio();
            },

            playAudioFile(path) {
                return new Promise((resolve, reject) => {
                    const audio = new Audio(path);
                    audio.onended = resolve; audio.onerror = reject; audio.play().catch(reject);
                });
            },

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
