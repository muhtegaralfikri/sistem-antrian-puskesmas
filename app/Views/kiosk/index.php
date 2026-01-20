<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="icon" type="image/png" href="/images/logo.png">
    <title>Anjungan Mandiri - Puskesmas Sehat</title>
    <meta name="csrf-token" content="<?= csrf_hash() ?>">
    <meta name="csrf-header" content="<?= csrf_header() ?>">
    <link rel="stylesheet" href="/css/app.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@700&display=swap" rel="stylesheet">
    <style>
        .hero-pattern {
            background-color: #f0fdfa;
            background-image: radial-gradient(#ccfbf1 1px, transparent 1px);
            background-size: 32px 32px;
        }
        .perspective { perspective: 1000px; }
        .card-3d { transition: transform 0.2s ease, box-shadow 0.2s ease; }
        .card-3d:active { transform: scale(0.98); }
        .glass-panel {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        
        /* Mobile vs Desktop Scroll Behavior */
        @media (min-width: 768px) {
            body { height: 100vh; overflow: hidden; }
            main { overflow-y: hidden; }
            .grid-container { overflow-y: auto; }
        }
        @media (max-width: 767px) {
            body { min-height: 100vh; overflow-y: auto; }
            main { height: auto; overflow: visible; }
            .grid-container { overflow: visible; height: auto; padding-bottom: 80px; }
        }
    </style>
</head>
<body class="bg-gray-50 flex flex-col hero-pattern font-sans antialiased text-gray-800 select-none relative" x-data="kioskApp()">

    <!-- Decorative Blobs (Fixed) -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden z-0">
        <div class="absolute -top-32 -left-32 w-64 h-64 md:w-80 md:h-80 bg-medical-200 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob"></div>
        <div class="absolute top-0 right-0 w-64 h-64 md:w-80 md:h-80 bg-primary-200 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob animation-delay-2000"></div>
        <div class="absolute -bottom-32 left-1/3 w-64 h-64 md:w-80 md:h-80 bg-pink-100 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob animation-delay-4000"></div>
    </div>

    <!-- Header -->
    <header class="h-16 md:h-24 bg-white/80 backdrop-blur-md border-b border-white/50 sticky top-0 z-30 flex-none shadow-sm transition-all px-4 md:px-6">
        <div class="h-full max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-3 md:gap-4">
                <img src="/images/logo.png" alt="Logo Puskesmas" class="w-12 h-12 md:w-16 md:h-16 object-contain drop-shadow-md shrink-0">
                <div>
                    <h1 class="text-lg md:text-3xl font-black text-gray-900 tracking-tight leading-none">PUSKESMAS<span class="text-medical-600">SEHAT</span></h1>
                    <p class="text-[10px] md:text-sm text-gray-500 font-bold uppercase tracking-widest mt-0.5 md:mt-1">Anjungan Mandiri</p>
                </div>
            </div>
            
            <!-- Clock -->
            <div class="text-right">
                <div class="text-xl md:text-4xl font-black font-mono text-gray-800 tracking-tight" x-text="currentTime">--:--</div>
                <div class="text-[10px] md:text-sm font-semibold text-gray-500 uppercase tracking-widest hidden md:block" x-text="currentDate">-- -- --</div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 w-full max-w-7xl mx-auto px-4 md:px-6 py-4 md:py-8 relative z-10 flex flex-col">
        <!-- Hero Title -->
        <div class="text-center mb-6 md:mb-10 flex-none animate-float">
            <span class="inline-block py-1 px-3 md:px-4 rounded-full bg-medical-50 text-medical-700 text-[10px] md:text-xs font-bold tracking-wider uppercase mb-2 md:mb-3 border border-medical-100 shadow-sm">Selamat Datang</span>
            <h2 class="text-2xl md:text-5xl font-extrabold text-gray-900 mb-2 md:mb-4 tracking-tight">Ambil Antrian</h2>
            <p class="text-sm md:text-lg text-gray-600 max-w-2xl mx-auto font-medium leading-relaxed">Pilih layanan poli yang Anda tuju.</p>
        </div>

        <!-- Grid Cards Container -->
        <div class="grid-container w-full no-scrollbar">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-3 md:gap-6 perspective pb-4 md:pb-20">
                <?php foreach($polis as $poli): ?>
                <button @click="ambilNomor(<?= $poli['id'] ?>)" class="card-3d group relative bg-white/80 backdrop-blur-sm rounded-2xl md:rounded-3xl p-4 md:p-8 shadow-sm border border-white/60 hover:shadow-2xl hover:border-medical-400 hover:-translate-y-2 overflow-hidden text-left flex items-center gap-4 md:gap-8 min-h-[90px] md:min-h-[176px] transition-all duration-300 w-full outline-none focus:ring-4 ring-medical-200">
                    
                    <!-- Background Gradient on Hover -->
                    <div class="absolute inset-0 bg-gradient-to-r from-medical-50/80 to-primary-50/80 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>

                    <!-- Icon Box (Left) -->
                    <div class="relative z-10 w-14 h-14 md:w-24 md:h-24 rounded-xl md:rounded-2xl shrink-0 bg-gradient-to-br from-white to-gray-50 border border-gray-100 flex items-center justify-center shadow-md group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300">
                        <span class="font-black text-2xl md:text-5xl font-mono text-gray-800 group-hover:text-medical-600 transition-colors"><?= $poli['prefix'] ?></span>
                    </div>

                    <!-- Content (Right) -->
                    <div class="relative z-10 flex-1 flex flex-col justify-center h-full min-w-0">
                        <div class="flex items-center justify-between mb-1">
                            <div class="px-2 py-0.5 md:py-1 rounded-md bg-green-100 text-green-700 text-[9px] md:text-xs font-bold uppercase tracking-wider border border-green-200 inline-flex items-center gap-1 shadow-sm group-hover:bg-green-200 transition-colors">
                                <span class="w-1 md:w-1.5 h-1 md:h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                                Buka
                            </div>
                        </div>
                        
                        <h3 class="text-lg md:text-3xl font-black text-gray-800 group-hover:text-medical-700 transition-colors leading-tight truncate w-full group-hover:tracking-tight"><?= esc($poli['nama']) ?></h3>
                        <p class="text-[10px] md:text-sm text-gray-400 font-semibold group-hover:text-medical-600 mt-1 md:mt-1.5 flex items-center gap-1 uppercase tracking-wide transition-colors">
                            Pilih
                            <svg class="w-3 h-3 md:w-4 md:h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </p>
                    </div>
                </button>
                <?php endforeach; ?>
            </div>
        </div>
    </main>
    
    <!-- Footer (Fixed on Desktop, Relative on Mobile) -->
    <footer class="h-12 md:h-16 bg-white/80 backdrop-blur-md border-t border-gray-200 flex items-center justify-center text-gray-400 text-[10px] md:text-sm font-semibold z-20 flex-none gap-2 px-4 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)] md:shadow-none">
        <svg class="w-3 h-3 md:w-5 md:h-5 shrink-0 text-medical-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span class="text-center">Jika butuh bantuan, hubungi petugas loket.</span>
    </footer>

    <!-- Loading Overlay -->
    <div x-show="loading" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-white/90 backdrop-blur-md">
        <div class="flex flex-col items-center">
            <div class="w-16 h-16 md:w-24 md:h-24 mb-4 md:mb-6 rounded-full border-4 border-gray-100 border-t-medical-500 animate-spin"></div>
            <h3 class="text-xl md:text-2xl font-bold text-gray-800 animate-pulse tracking-tight">Mencetak Tiket...</h3>
            <p class="text-sm md:text-base text-gray-500 mt-1 font-medium">Mohon tunggu sebentar</p>
        </div>
    </div>

    <!-- Success Modal -->
    <template x-teleport="body">
        <div x-show="showModal" class="fixed inset-0 flex items-end md:items-center justify-center p-0 md:p-4" style="z-index: 9999;" x-cloak>
            <div x-show="showModal" x-transition.opacity class="absolute inset-0 bg-gray-900/40 backdrop-blur-md" @click="showModal = false"></div>
            
            <div x-show="showModal" 
                 x-transition:enter="transition ease-out duration-300" 
                 x-transition:enter-start="translate-y-full opacity-0 md:scale-95 md:translate-y-10" 
                 x-transition:enter-end="translate-y-0 opacity-100 md:scale-100 md:translate-y-0" 
                 x-transition:leave="transition ease-in duration-200" 
                 x-transition:leave-start="translate-y-0 opacity-100 md:scale-100 md:translate-y-0" 
                 x-transition:leave-end="translate-y-full opacity-0 md:scale-95 md:translate-y-10" 
                 class="bg-white rounded-t-[2.5rem] md:rounded-[2.5rem] shadow-2xl w-full max-w-md relative overflow-hidden flex flex-col items-center text-center p-2 max-h-[90vh] pb-8 md:pb-2"
                 style="z-index: 10000;">
                
                <!-- Ticket Paper Style -->
                <div class="bg-white w-full p-5 md:p-8 rounded-[2rem] border-0 md:border-[3px] border-medical-50 relative overflow-hidden">
                     <div class="absolute top-0 left-0 w-full h-1.5 md:h-2 bg-gradient-to-r from-medical-400 to-primary-500"></div>

                    <!-- Cutout Circles -->
                    <div class="absolute -left-4 top-1/2 w-8 h-8 bg-gray-900/10 backdrop-blur-md rounded-full border-r border-gray-200 hidden md:block"></div>
                    <div class="absolute -right-4 top-1/2 w-8 h-8 bg-gray-900/10 backdrop-blur-md rounded-full border-l border-gray-200 hidden md:block"></div>
                    
                    <!-- Swipe indicator for mobile -->
                    <div class="w-12 h-1.5 bg-gray-200 rounded-full mx-auto mb-4 md:hidden"></div>

                    <div class="w-14 h-14 md:w-20 md:h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3 md:mb-6 shadow-inner ring-4 ring-green-50">
                        <svg class="w-7 h-7 md:w-10 md:h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                    </div>

                    <h3 class="text-xl md:text-3xl font-black text-gray-900 mb-1 tracking-tight">Berhasil!</h3>
                    <p class="text-sm md:text-base text-gray-500 mb-4 md:mb-8 font-medium">Silakan ambil struk antrian Anda</p>

                    <div class="bg-gray-50 rounded-xl md:rounded-2xl p-3 md:p-6 border-2 border-gray-200 border-dashed mb-3 md:mb-4 relative">
                        <div class="text-[10px] md:text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Nomor Antrian</div>
                        <div class="text-4xl md:text-6xl font-black text-gray-900 font-mono tracking-tighter leading-none whitespace-nowrap" x-text="ticketData?.nomor">--</div>
                        <div class="text-sm md:text-lg font-bold text-medical-600 mt-1 md:mt-2 line-clamp-1" x-text="ticketData?.poli_nama">--</div>
                    </div>
                    
                    <!-- Patient Name Display -->
                    <div class="bg-primary-50 rounded-xl p-2.5 mb-4 border border-primary-100" x-show="ticketData?.nama_pasien">
                        <div class="text-[10px] font-bold text-primary-400 uppercase tracking-widest mb-0.5">Nama Pasien</div>
                        <div class="text-base md:text-xl font-bold text-primary-700 truncate" x-text="ticketData?.nama_pasien || '-'"></div>
                    </div>

                    <div class="space-y-2 md:space-y-3">
                        <button @click="printTicket()" class="w-full bg-slate-900 hover:bg-slate-800 active:scale-[0.98] transition-all text-white font-bold text-sm md:text-lg py-3 md:py-4 rounded-xl md:rounded-2xl shadow-xl shadow-slate-900/20 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                            Cetak Struk
                        </button>
                        <button @click="showModal = false" class="w-full bg-white border-2 border-gray-100 hover:bg-gray-50 text-gray-600 font-bold text-sm md:text-lg py-3 md:py-4 rounded-xl md:rounded-2xl transition-colors">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <!-- Name Input Modal with On-Screen Keyboard -->
    <template x-teleport="body">
        <div x-show="showNameModal" class="fixed inset-0 flex items-end md:items-center justify-center p-0 md:p-4" style="z-index: 9998;" x-cloak>
            <div x-show="showNameModal" x-transition.opacity class="absolute inset-0 bg-gray-900/50 backdrop-blur-md" @click="closeNameModal()"></div>
            
            <div x-show="showNameModal" 
                 x-transition:enter="transition ease-out duration-300" 
                 x-transition:enter-start="translate-y-full opacity-0" 
                 x-transition:enter-end="translate-y-0 opacity-100" 
                 x-transition:leave="transition ease-in duration-200" 
                 x-transition:leave-start="translate-y-0 opacity-100" 
                 x-transition:leave-end="translate-y-full opacity-0" 
                 class="bg-white rounded-t-[2rem] md:rounded-[2rem] shadow-2xl w-full max-w-lg relative overflow-hidden flex flex-col max-h-[95vh] md:max-h-[90vh]">
                
                <!-- Header -->
                <div class="bg-gradient-to-r from-medical-500 to-primary-500 p-4 md:p-6 text-white text-center relative">
                    <div class="w-10 h-1 bg-white/30 rounded-full mx-auto mb-4 md:hidden"></div>
                    <h3 class="text-xl md:text-2xl font-bold tracking-tight">Masukkan Nama Anda</h3>
                    <p class="text-sm text-white/80 mt-1" x-text="'Poli: ' + selectedPoliName"></p>
                </div>

                <!-- Name Input Display -->
                <div class="p-4 md:p-6 bg-gray-50 border-b border-gray-100">
                    <div class="bg-white rounded-xl md:rounded-2xl p-4 border-2 border-gray-200 shadow-inner min-h-[60px] flex items-center justify-center">
                        <span class="text-2xl md:text-3xl font-bold text-gray-800 tracking-wide text-center break-all" 
                              x-text="namaPasien || 'Ketik nama Anda...'" 
                              :class="namaPasien ? 'text-gray-800' : 'text-gray-300'"></span>
                    </div>
                    <p class="text-xs text-gray-400 text-center mt-2">Maksimal 50 karakter</p>
                </div>

                <!-- On-Screen Keyboard -->
                <div class="p-3 md:p-4 bg-gray-100 flex-1 overflow-y-auto">
                    <!-- Row 1: Q-P -->
                    <div class="flex justify-center gap-1.5 md:gap-2 mb-1.5 md:mb-2">
                        <template x-for="key in ['Q','W','E','R','T','Y','U','I','O','P']">
                            <button @click="addChar(key)" class="w-8 h-10 md:w-12 md:h-14 bg-white rounded-lg md:rounded-xl shadow-sm border border-gray-200 text-gray-800 font-bold text-base md:text-xl hover:bg-gray-50 active:bg-gray-100 active:scale-95 transition-all" x-text="key"></button>
                        </template>
                    </div>
                    <!-- Row 2: A-L -->
                    <div class="flex justify-center gap-1.5 md:gap-2 mb-1.5 md:mb-2">
                        <template x-for="key in ['A','S','D','F','G','H','J','K','L']">
                            <button @click="addChar(key)" class="w-8 h-10 md:w-12 md:h-14 bg-white rounded-lg md:rounded-xl shadow-sm border border-gray-200 text-gray-800 font-bold text-base md:text-xl hover:bg-gray-50 active:bg-gray-100 active:scale-95 transition-all" x-text="key"></button>
                        </template>
                    </div>
                    <!-- Row 3: Z-M -->
                    <div class="flex justify-center gap-1.5 md:gap-2 mb-3 md:mb-4">
                        <template x-for="key in ['Z','X','C','V','B','N','M']">
                            <button @click="addChar(key)" class="w-8 h-10 md:w-12 md:h-14 bg-white rounded-lg md:rounded-xl shadow-sm border border-gray-200 text-gray-800 font-bold text-base md:text-xl hover:bg-gray-50 active:bg-gray-100 active:scale-95 transition-all" x-text="key"></button>
                        </template>
                    </div>
                    <!-- Row 4: Space & Backspace -->
                    <div class="flex justify-center gap-2 md:gap-3">
                        <button @click="addChar(' ')" class="flex-1 max-w-[200px] h-10 md:h-14 bg-white rounded-lg md:rounded-xl shadow-sm border border-gray-200 text-gray-600 font-bold text-sm md:text-base hover:bg-gray-50 active:bg-gray-100 active:scale-95 transition-all">
                            SPASI
                        </button>
                        <button @click="deleteChar()" class="w-16 md:w-24 h-10 md:h-14 bg-red-50 rounded-lg md:rounded-xl shadow-sm border border-red-200 text-red-600 font-bold text-sm md:text-base hover:bg-red-100 active:bg-red-200 active:scale-95 transition-all flex items-center justify-center gap-1">
                            <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2M3 12l6.414 6.414a2 2 0 001.414.586H19a2 2 0 002-2V7a2 2 0 00-2-2h-8.172a2 2 0 00-1.414.586L3 12z"/></svg>
                            <span class="hidden md:inline">HAPUS</span>
                        </button>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="p-4 md:p-6 bg-white border-t border-gray-100 flex gap-3">
                    <button @click="closeNameModal()" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-base md:text-lg py-3 md:py-4 rounded-xl md:rounded-2xl transition-colors">
                        Batal
                    </button>
                    <button @click="submitWithName()" :disabled="!namaPasien.trim()" class="flex-[2] bg-medical-600 hover:bg-medical-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white font-bold text-base md:text-lg py-3 md:py-4 rounded-xl md:rounded-2xl shadow-lg shadow-medical-600/30 transition-all active:scale-[0.98] flex items-center justify-center gap-2">
                        <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Ambil Antrian
                    </button>
                </div>
            </div>
        </div>
    </template>

    <script>
    function kioskApp() {
        return {
            currentTime: '',
            currentDate: '',
            loading: false,
            showModal: false,
            ticketData: null,
            
            // Name input modal states
            showNameModal: false,
            selectedPoliId: null,
            selectedPoliName: '',
            namaPasien: '',

            init() {
                this.updateTime();
                setInterval(() => this.updateTime(), 1000);
            },
            
            updateTime() {
                const now = new Date();
                this.currentTime = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
                this.currentDate = now.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
            },

            // Step 1: Open name input modal when poli is selected
            ambilNomor(poliId) {
                // Find poli name from button text (hacky but works)
                const buttons = document.querySelectorAll('button[\\@click^="ambilNomor"]');
                let poliName = 'Poli';
                buttons.forEach(btn => {
                    if (btn.getAttribute('@click').includes(poliId)) {
                        const h3 = btn.querySelector('h3');
                        if (h3) poliName = h3.textContent.trim();
                    }
                });
                
                this.selectedPoliId = poliId;
                this.selectedPoliName = poliName;
                this.namaPasien = '';
                this.showNameModal = true;
            },

            // Keyboard functions
            addChar(char) {
                if (this.namaPasien.length < 50) {
                    this.namaPasien += char;
                }
            },

            deleteChar() {
                this.namaPasien = this.namaPasien.slice(0, -1);
            },

            closeNameModal() {
                this.showNameModal = false;
                this.selectedPoliId = null;
                this.selectedPoliName = '';
                this.namaPasien = '';
            },

            // Step 2: Submit with name
            async submitWithName() {
                if (!this.namaPasien.trim() || !this.selectedPoliId) return;
                
                this.showNameModal = false;
                this.loading = true;
                
                try {
                    await new Promise(r => setTimeout(r, 600));

                    const formData = new FormData();
                    formData.append('poli_id', this.selectedPoliId);
                    formData.append('nama_pasien', this.namaPasien.trim());
                    
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    const csrfHeader = document.querySelector('meta[name="csrf-header"]').getAttribute('content');

                    const response = await fetch('<?= base_url('kiosk/ambil') ?>', {
                        method: 'POST',
                        headers: { 
                            'X-Requested-With': 'XMLHttpRequest',
                            [csrfHeader]: csrfToken
                        },
                        body: formData
                    });
                    const result = await response.json();
                    
                    if (result.success) {
                        this.ticketData = result.data;
                        this.showModal = true;
                    } else {
                        alert(result.message || 'Gagal mengambil antrian');
                    }
                } catch (e) {
                    alert('Terjadi kesalahan sistem. Silakan hubungi petugas.');
                    console.error(e);
                } finally {
                    this.loading = false;
                    this.selectedPoliId = null;
                    this.selectedPoliName = '';
                    this.namaPasien = '';
                }
            },

            printTicket() {
                if (!this.ticketData?.id) return;
                
                const url = '<?= base_url('kiosk/tiket') ?>/' + this.ticketData.id + '?print=1';
                
                let iframe = document.getElementById('print-frame');
                if (!iframe) {
                    iframe = document.createElement('iframe');
                    iframe.id = 'print-frame';
                    iframe.style.display = 'none';
                    document.body.appendChild(iframe);
                }
                
                iframe.src = url;
                
                iframe.onload = () => {
                    setTimeout(() => {
                        const originalTitle = document.title;
                        document.title = "Tiket_Antrian_" + (this.ticketData.nomor || 'Baru');
                        iframe.contentWindow.print();
                        setTimeout(() => { document.title = originalTitle; }, 1000);
                    }, 500);
                };
            }
        };
    }
    </script>
</body>
</html>
