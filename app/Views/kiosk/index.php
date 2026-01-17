<?= $this->extend('layouts/base') ?>

<?= $this->section('title') ?>Kiosk Antrian - Sistem Antrian Puskesmas<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    .poli-card {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid #e2e8f0;
    }
    .poli-card:active {
        transform: scale(0.98);
    }
    .poli-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 25px 50px -12px rgba(37, 99, 235, 0.15); /* Primary-600 shadow */
        border-color: #2563eb;
    }
    /* Animasi background bergerak halus */
    @keyframes gradient-xy {
        0%, 100% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
    }
    .animate-gradient {
        background-size: 200% 200%;
        animation: gradient-xy 15s ease infinite;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Kiosk App Wrapper with Alpine.js -->
<div x-data="{
    init() {
        this.updateTime();
        setInterval(() => this.updateTime(), 1000);
    },
    currentTime: '',
    currentDate: '',
    loading: false,
    showModal: false,
    ticketData: null,
    updateTime() {
        const now = new Date();
        this.currentTime = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
        this.currentDate = now.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
    },
    async ambilNomor(poliId) {
        this.loading = true;
        try {
            const formData = new FormData();
            formData.append('poli_id', poliId);
            const response = await fetch('<?= base_url('kiosk/ambil') ?>', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });
            const result = await response.json();
            if (result.success) {
                this.ticketData = result.data;
                this.showModal = true;
            } else {
                alert(result.message || 'Terjadi kesalahan');
            }
        } catch (e) {
            alert('Terjadi kesalahan koneksi');
        } finally {
            this.loading = false;
        }
    },
    printTicket() {
        if (this.ticketData?.id) {
            window.open('<?= base_url('kiosk/tiket') ?>/' + this.ticketData.id, '_blank');
        }
    }
}" class="min-h-screen bg-gradient-to-br from-primary-50 via-white to-primary-100">

    <!-- Header -->
    <header class="bg-white/90 backdrop-blur-md shadow-sm sticky top-0 z-40 border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gradient-to-br from-primary-500 to-primary-700 rounded-2xl flex items-center justify-center shadow-lg shadow-primary-500/30">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-800 tracking-tight">Sistem Antrian</h1>
                    <p class="text-sm font-medium text-primary-600">Puskesmas Digital</p>
                </div>
            </div>
            <div class="text-right hidden md:block">
                <p class="text-xl font-bold text-gray-800 tabular-nums" x-text="currentTime"></p>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider" x-text="currentDate"></p>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 py-12">
        <!-- Welcome -->
        <div class="text-center mb-16">
            <h2 class="text-4xl font-extrabold text-gray-900 mb-3 tracking-tight">Selamat Datang</h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">Silakan pilih poliklinik tujuan Anda untuk mengambil nomor antrian baru.</p>
        </div>

        <!-- Poli Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach($polis as $poli): ?>
            <button @click="ambilNomor(<?= $poli['id'] ?>)"
                    class="poli-card bg-white rounded-3xl p-8 text-left relative overflow-hidden group">
                
                <!-- Decorative Background Blob -->
                <div class="absolute -right-6 -top-6 w-32 h-32 bg-<?= match($poli['prefix']) {
                    'A' => 'primary',
                    'B' => 'secondary',
                    'C' => 'purple',
                    default => 'gray'
                } ?>-50 rounded-full group-hover:scale-150 transition-transform duration-500 ease-in-out z-0"></div>

                <!-- Content -->
                <div class="relative z-10">
                    <!-- Icon -->
                    <div class="w-16 h-16 bg-<?= match($poli['prefix']) {
                        'A' => 'primary',
                        'B' => 'secondary',
                        'C' => 'purple',
                        default => 'gray'
                    } ?>-50 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition duration-300 shadow-sm">
                        <svg class="w-8 h-8 text-<?= match($poli['prefix']) {
                            'A' => 'primary',
                            'B' => 'secondary',
                            'C' => 'purple',
                            default => 'gray'
                        } ?>-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>

                    <!-- Poli Info -->
                    <h3 class="text-2xl font-bold text-gray-800 mb-2 group-hover:text-primary-700 transition-colors"><?= esc($poli['nama']) ?></h3>
                    <div class="flex items-center gap-3 mb-6">
                        <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-xs font-bold font-mono">KODE: <?= esc($poli['kode']) ?></span>
                    </div>

                    <!-- Status -->
                    <div class="flex items-center gap-2 text-sm bg-green-50 w-fit px-3 py-1.5 rounded-full border border-green-100">
                        <span class="relative flex h-2.5 w-2.5">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-green-500"></span>
                        </span>
                        <span class="text-green-700 font-semibold">Layanan Tersedia</span>
                    </div>
                </div>
            </button>
            <?php endforeach; ?>
        </div>

        <!-- Info -->
        <div class="mt-16 bg-white border border-blue-100 rounded-3xl p-8 shadow-sm flex flex-col md:flex-row items-center gap-6">
            <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center flex-shrink-0">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <h4 class="text-lg font-bold text-blue-900 mb-2">Panduan Pengambilan Antrian</h4>
                <ul class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-2 text-blue-800/80 text-sm font-medium">
                    <li class="flex items-center gap-2">
                        <span class="w-1.5 h-1.5 bg-blue-400 rounded-full"></span>
                        Pilih poliklinik sesuai tujuan berobat
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="w-1.5 h-1.5 bg-blue-400 rounded-full"></span>
                        Ambil struk antrian yang keluar
                    </li>
                    <li class="flex items-center gap-2">
                         <span class="w-1.5 h-1.5 bg-blue-400 rounded-full"></span>
                         Tunggu nomor Anda dipanggil di layar
                    </li>
                     <li class="flex items-center gap-2">
                        <span class="w-1.5 h-1.5 bg-blue-400 rounded-full"></span>
                        Silakan menuju loket saat dipanggil
                    </li>
                </ul>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="max-w-7xl mx-auto px-4 py-6 text-center text-sm text-gray-500">
        <p>&copy; <?= date('Y') ?> Puskesmas. Sistem Antrian</p>
    </footer>

    <!-- Loading Overlay -->
    <div x-show="loading" x-transition.opacity
         class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center" style="display: none;">
        <div class="bg-white rounded-2xl p-8 shadow-xl text-center">
            <div class="animate-spin w-12 h-12 border-4 border-primary-200 border-t-primary-600 rounded-full mx-auto mb-4"></div>
            <p class="text-gray-700 font-medium">Memproses...</p>
        </div>
    </div>

    <!-- Success Modal -->
    <div x-show="showModal" style="display: none;"
          class="fixed inset-0 z-[60] flex items-center justify-center px-4">
        
        <!-- Backdrop -->
        <div x-show="showModal" 
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" 
             @click="showModal = false"></div>

        <!-- Modal Panel -->
        <div x-show="showModal"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="bg-white rounded-3xl shadow-2xl overflow-hidden transform transition-all sm:max-w-sm w-full relative z-10">
            
            <!-- Success Header with Confetti vibes -->
            <div class="bg-gradient-to-br from-secondary-400 to-secondary-600 px-6 py-6 text-center relative overflow-hidden">
                <div class="absolute inset-0 opacity-10 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMiIgY3k9IjIiIHI9IjIiIGZpbGw9IiNmZmYiLz48L3N2Zz4=')]"></div>
                
                <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center mx-auto mb-3 shadow-inner">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-white">Berhasil Diambil!</h3>
                <p class="text-secondary-50 text-sm">Nomor antrian Anda telah diterbitkan</p>
            </div>

            <!-- Ticket Info -->
            <div class="p-6">
                <div class="text-center mb-6">
                    <p class="text-gray-500 text-sm mb-1">Nomor Antrian Anda</p>
                    <p class="text-5xl font-bold text-gray-800" x-text="ticketData?.nomor || ''"></p>
                    <p class="text-gray-600 mt-2" x-text="ticketData?.poli_nama || ''"></p>
                </div>

                <div class="bg-gray-50 rounded-xl p-4 mb-6">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Waktu Ambil</span>
                        <span class="text-gray-800" x-text="ticketData?.waktu_ambil || ''"></span>
                    </div>
                    <div class="flex justify-between text-sm mt-2">
                        <span class="text-gray-500">Estimasi Antrian</span>
                        <span class="text-primary-600 font-medium" x-text="'± ' + (ticketData?.waiting_count || 0) + ' antrian lagi'"></span>
                    </div>
                </div>

                <div class="flex gap-3">
                    <button @click="printTicket()" class="flex-1 bg-primary-600 hover:bg-primary-700 text-white font-semibold py-3 px-4 rounded-lg flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                        </svg>
                        Cetak Tiket
                    </button>
                    <button @click="showModal = false" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-3 px-4 rounded-lg">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
