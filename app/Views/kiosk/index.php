<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anjungan Mandiri - Puskesmas Sehat</title>
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
                        'float': 'float 6s ease-in-out infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-10px)' },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .hero-pattern {
            background-color: #f8fafc;
            background-image: radial-gradient(#cbd5e1 1px, transparent 1px);
            background-size: 40px 40px;
        }
        .perspective {
            perspective: 1000px;
        }
        .card-3d {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card-3d:active {
            transform: scale(0.95);
        }
        /* Hide scrollbar for Chrome, Safari and Opera */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        /* Hide scrollbar for IE, Edge and Firefox */
        .no-scrollbar {
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;  /* Firefox */
        }
    </style>
</head>
<body class="bg-gray-50 h-screen flex flex-col overflow-hidden hero-pattern font-sans antialiased text-gray-800 select-none" x-data="kioskApp()">

    <!-- Header -->
    <header class="h-auto py-4 md:py-0 md:h-24 bg-white/80 backdrop-blur-xl border-b border-gray-200 sticky top-0 z-30 flex-none shadow-sm transition-all">
        <div class="h-full max-w-7xl mx-auto px-4 md:px-6 flex flex-col md:flex-row items-center justify-between gap-4 md:gap-0">
            <div class="flex items-center gap-3 md:gap-5 w-full md:w-auto">
                <div class="w-10 h-10 md:w-14 md:h-14 bg-gradient-to-br from-primary-600 to-primary-800 rounded-xl md:rounded-2xl flex items-center justify-center shadow-lg shadow-primary-500/30 text-white shrink-0">
                    <svg class="w-6 h-6 md:w-8 md:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                </div>
                <div>
                    <h1 class="text-xl md:text-3xl font-black text-gray-900 tracking-tight leading-none">PUSKESMAS SEHAT</h1>
                    <p class="text-xs md:text-base text-gray-500 font-medium tracking-wide">Anjungan Pendaftaran Mandiri</p>
                </div>
                <!-- Time on Mobile Right -->
                 <div class="ml-auto text-right md:hidden">
                    <div class="text-lg font-black font-mono text-gray-900 tracking-tight" x-text="currentTime">--:--</div>
                </div>
            </div>
            <div class="text-right hidden md:block">
                <div class="text-4xl font-black font-mono text-gray-900 tracking-tight" x-text="currentTime">--:--</div>
                <div class="text-gray-500 font-medium" x-text="currentDate">-- -- --</div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 relative overflow-hidden flex flex-col z-10 w-full max-w-7xl mx-auto px-4 md:px-6 py-4 md:py-8">
        <!-- Hero Title -->
        <div class="text-center mb-6 md:mb-10 animate-float">
            <h2 class="text-2xl md:text-5xl font-extrabold text-gray-900 mb-2 md:mb-4 tracking-tight">Selamat Datang</h2>
            <p class="text-sm md:text-xl text-gray-600 max-w-2xl mx-auto">Silakan sentuh salah satu layanan di bawah ini untuk mengambil nomor antrian.</p>
        </div>

        <!-- Grid Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6 pb-20 overflow-y-auto px-1 md:px-4 perspective no-scrollbar">
            <?php foreach($polis as $poli): ?>
            <button @click="ambilNomor(<?= $poli['id'] ?>)" class="card-3d group relative bg-white rounded-2xl md:rounded-3xl p-4 md:p-6 shadow-xl border border-gray-100/50 hover:shadow-2xl hover:border-primary-400 overflow-hidden text-left flex items-center gap-4 md:gap-6 min-h-[100px] h-auto md:h-40 transition-all duration-300 w-full outline-none focus:ring-4 ring-primary-200">
                
                <!-- Background Gradient on Hover -->
                <div class="absolute inset-0 bg-gradient-to-r from-transparent to-gray-50 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                <!-- Icon Box (Left) -->
                <div class="relative z-10 w-16 h-16 md:w-24 md:h-24 rounded-xl md:rounded-2xl shrink-0 bg-gradient-to-br <?= match($poli['prefix']) { 'A' => 'from-blue-50 to-blue-100 text-blue-600', 'B' => 'from-emerald-50 to-emerald-100 text-emerald-600', 'C' => 'from-purple-50 to-purple-100 text-purple-600', default => 'from-gray-50 to-gray-100 text-gray-600' } ?> flex items-center justify-center shadow-inner group-hover:scale-105 transition-transform duration-300">
                    <span class="font-black text-3xl md:text-5xl font-mono"><?= $poli['prefix'] ?></span>
                </div>

                <!-- Content (Right) -->
                <div class="relative z-10 flex-1 flex flex-col justify-center h-full">
                    <div class="flex items-center justify-between mb-1">
                        <div class="px-2 py-0.5 md:px-2.5 md:py-1 rounded-lg bg-green-100 text-green-700 text-[10px] md:text-xs font-bold uppercase tracking-wider border border-green-200 inline-flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                            Buka
                        </div>
                    </div>
                    
                    <h3 class="text-lg md:text-3xl font-black text-gray-800 group-hover:text-primary-700 transition-colors leading-tight line-clamp-2 w-full"><?= esc($poli['nama']) ?></h3>
                    <p class="text-xs md:text-sm text-gray-400 font-medium group-hover:text-primary-500 mt-1 flex items-center gap-1">
                        Sentuh ambil antrian
                        <svg class="w-3 h-3 md:w-4 md:h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </p>
                </div>
            </button>
            <?php endforeach; ?>
        </div>
    </main>
    
    <!-- Footer -->
    <footer class="h-14 md:h-16 bg-white border-t border-gray-200 flex items-center justify-center text-gray-400 text-xs md:text-sm font-medium z-20 flex-none gap-8">
        <div class="flex items-center gap-2 px-4 text-center">
            <svg class="w-4 h-4 md:w-5 md:h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Butuh bantuan? Silakan hubungi petugas.
        </div>
    </footer>

    <!-- Loading Overlay -->
    <div x-show="loading" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-white/80 backdrop-blur-md">
        <div class="flex flex-col items-center">
            <div class="w-16 h-16 border-4 border-primary-200 border-t-primary-600 rounded-full animate-spin mb-4"></div>
            <h3 class="text-xl font-bold text-gray-800 animate-pulse">Mencetak Nomor Antrian...</h3>
        </div>
    </div>

    <!-- Success Modal -->
    <template x-teleport="body">
        <div x-show="showModal" class="fixed inset-0 z-[60] flex items-center justify-center p-4" x-cloak>
            <div x-show="showModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="absolute inset-0 bg-gray-900/40 backdrop-blur-md" @click="showModal = false"></div>
            
            <div x-show="showModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90 translate-y-10" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100 translate-y-0" x-transition:leave-end="opacity-0 scale-90 translate-y-10" class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-md relative overflow-hidden flex flex-col items-center text-center p-1">
                
                <!-- Ticket Paper Effect -->
                <div class="bg-white w-full p-8 rounded-[2rem] border-2 border-gray-100 relative">
                    <!-- Cutout Circles (Ticket Aesthetic) -->
                    <div class="absolute -left-3 top-1/2 w-6 h-6 bg-gray-900/40 rounded-full"></div>
                    <div class="absolute -right-3 top-1/2 w-6 h-6 bg-gray-900/40 rounded-full"></div>
                    
                    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                    </div>

                    <h3 class="text-2xl font-bold text-gray-900 mb-1">Berhasil!</h3>
                    <p class="text-gray-500 mb-8">Silakan ambil struk antrian Anda</p>

                    <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200 dashed-border mb-8">
                        <div class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-2">Nomor Antrian</div>
                        <div class="text-7xl font-black text-gray-900 font-mono tracking-tighter" x-text="ticketData?.nomor">--</div>
                        <div class="text-xl font-bold text-primary-600 mt-2" x-text="ticketData?.poli_nama">--</div>
                    </div>

                    <div class="space-y-3">
                        <button @click="printTicket()" class="w-full bg-primary-600 hover:bg-primary-700 active:scale-95 transition-all text-white font-bold text-lg py-4 rounded-xl shadow-lg shadow-primary-500/30 flex items-center justify-center gap-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                            Cetak Struk
                        </button>
                        <button @click="showModal = false" class="w-full bg-white border-2 border-gray-100 hover:bg-gray-50 text-gray-600 font-bold text-lg py-4 rounded-xl transition-colors">
                            Tutup
                        </button>
                    </div>
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

            init() {
                this.updateTime();
                setInterval(() => this.updateTime(), 1000);
            },
            
            updateTime() {
                const now = new Date();
                this.currentTime = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
                this.currentDate = now.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
            },

            async ambilNomor(poliId) {
                this.loading = true;
                try {
                    // Simulate network delay for effect
                    await new Promise(r => setTimeout(r, 600));

                    const formData = new FormData();
                    formData.append('poli_id', poliId);
                    const response = await fetch('<?= base_url('kiosk/ambil') ?>', {
                        method: 'POST',
                        headers: { 'X-Requested-With': 'XMLHttpRequest' },
                        body: formData
                    });
                    const result = await response.json();
                    
                    if (result.success) {
                        this.ticketData = result.data;
                        this.showModal = true;
                        // Auto print optional?
                        // this.printTicket();
                    } else {
                        alert(result.message || 'Gagal mengambil antrian');
                    }
                } catch (e) {
                    alert('Terjadi kesalahan sistem. Silakan hubungi petugas.');
                    console.error(e);
                } finally {
                    this.loading = false;
                }
            },

            printTicket() {
                if (!this.ticketData?.id) return;
                
                const url = '<?= base_url('kiosk/tiket') ?>/' + this.ticketData.id + '?print=1';
                
                // Create invisible iframe
                let iframe = document.getElementById('print-frame');
                if (!iframe) {
                    iframe = document.createElement('iframe');
                    iframe.id = 'print-frame';
                    iframe.style.display = 'none';
                    document.body.appendChild(iframe);
                }
                
                iframe.src = url;
                
                // Wait for load then print
                iframe.onload = () => {
                    setTimeout(() => {
                        // Hack: Change parent title allows 'Save as PDF' to use proper filename
                        const originalTitle = document.title;
                        document.title = "Tiket_Antrian_" + (this.ticketData.nomor || 'Baru');
                        
                        iframe.contentWindow.print();
                        
                        // Restore title
                        setTimeout(() => {
                            document.title = originalTitle;
                        }, 1000);
                    }, 500);
                };
            }
        };
    }
    </script>
</body>
</html>
