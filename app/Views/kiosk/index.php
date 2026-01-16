<?= $this->extend('layouts/base') ?>

<?= $this->section('title') ?>Kiosk Antrian - Sistem Antrian Puskesmas<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    .poli-card {
        transition: all 0.3s ease;
    }
    .poli-card:active {
        transform: scale(0.98);
    }
    .poli-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-gradient-to-br from-primary-50 via-white to-primary-100">
    <!-- Header -->
    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-primary-600 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-gray-800">Sistem Antrian</h1>
                    <p class="text-sm text-gray-500">Puskesmas</p>
                </div>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500" x-text="currentTime"></p>
                <p class="text-xs text-gray-400" x-text="currentDate"></p>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 py-8">
        <!-- Welcome -->
        <div class="text-center mb-10">
            <h2 class="text-3xl font-bold text-gray-800 mb-2">Selamat Datang</h2>
            <p class="text-gray-600">Silakan pilih poliklinik untuk mengambil nomor antrian</p>
        </div>

        <!-- Poli Grid -->
        <div x-data="kioskApp()" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach($polis as $poli): ?>
            <button @click="ambilNomor(<?= $poli['id'] ?>)"
                    class="poli-card bg-white rounded-2xl shadow-lg p-8 text-left border-2 border-transparent hover:border-primary-500 cursor-pointer group">
                <!-- Icon -->
                <div class="w-16 h-16 bg-<?= match($poli['prefix']) {
                    'A' => 'blue',
                    'B' => 'green',
                    'C' => 'purple',
                    default => 'gray'
                } ?>-100 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition">
                    <svg class="w-8 h-8 text-<?= match($poli['prefix']) {
                        'A' => 'blue',
                        'B' => 'green',
                        'C' => 'purple',
                        default => 'gray'
                    } ?>-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>

                <!-- Poli Info -->
                <h3 class="text-2xl font-bold text-gray-800 mb-1"><?= esc($poli['nama']) ?></h3>
                <p class="text-gray-500 mb-4">Kode: <?= esc($poli['kode']) ?></p>

                <!-- Status -->
                <div class="flex items-center gap-2 text-sm">
                    <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                    <span class="text-gray-600">Tersedia</span>
                </div>
            </button>
            <?php endforeach; ?>
        </div>

        <!-- Info -->
        <div class="mt-10 bg-blue-50 border border-blue-200 rounded-xl p-6">
            <div class="flex items-start gap-4">
                <svg class="w-6 h-6 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <h4 class="font-semibold text-blue-900 mb-1">Informasi Antrian</h4>
                    <ul class="text-sm text-blue-700 space-y-1">
                        <li>• Tekan tombol poliklinik untuk mengambil nomor antrian</li>
                        <li>• Simpan/tunjukkan tiket yang diberikan</li>
                        <li>• Perhatikan panggilan nomor antrian di display</li>
                        <li>• Pastikan datang ketika nomor Anda dipanggil</li>
                    </ul>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="max-w-7xl mx-auto px-4 py-6 text-center text-sm text-gray-500">
        <p>&copy; <?= date('Y') ?> Puskesmas. Sistem Antrian</p>
    </footer>
</div>

<!-- Loading Overlay -->
<div x-data="{ show: false }" x-show="show" x-transition.opacity
     class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center" style="display: none;">
    <div class="bg-white rounded-2xl p-8 shadow-xl text-center">
        <div class="animate-spin w-12 h-12 border-4 border-primary-200 border-t-primary-600 rounded-full mx-auto mb-4"></div>
        <p class="text-gray-700 font-medium">Memproses...</p>
    </div>
</div>

<!-- Success Modal -->
<div x-data="successModal()" x-show="show" x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
     class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4" style="display: none;">
    <div @click.away="closeModal()" class="bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-90"
         x-transition:enter-end="opacity-100 scale-100">
        <!-- Success Header -->
        <div class="bg-green-500 px-6 py-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <div class="text-white">
                    <p class="font-semibold">Nomor Antrian Berhasil Diambil!</p>
                </div>
            </div>
        </div>

        <!-- Ticket Info -->
        <div class="p-6">
            <div class="text-center mb-6">
                <p class="text-gray-500 text-sm mb-1">Nomor Antrian Anda</p>
                <p class="text-5xl font-bold text-gray-800" x-text="data?.nomor || ''"></p>
                <p class="text-gray-600 mt-2" x-text="data?.poli_nama || ''"></p>
            </div>

            <div class="bg-gray-50 rounded-xl p-4 mb-6">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Waktu Ambil</span>
                    <span class="text-gray-800" x-text="data?.waktu_ambil || ''"></span>
                </div>
                <div class="flex justify-between text-sm mt-2">
                    <span class="text-gray-500">Estimasi Antrian</span>
                    <span class="text-primary-600 font-medium" x-text="'± ' + (data?.waiting_count || 0) + ' antrian lagi'"></span>
                </div>
            </div>

            <div class="flex gap-3">
                <button @click="printTicket()" class="flex-1 bg-primary-600 hover:bg-primary-700 text-white font-semibold py-3 px-4 rounded-lg flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    Cetak Tiket
                </button>
                <button @click="closeModal()" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-3 px-4 rounded-lg">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function kioskApp() {
    return {
        init() {
            this.updateTime();
            setInterval(() => this.updateTime(), 1000);
        },
        currentTime: '',
        currentDate: '',

        updateTime() {
            const now = new Date();
            this.currentTime = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
            this.currentDate = now.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
        },

        async ambilNomor(poliId) {
            const loadingEl = document.querySelector('[x-data="{ show: false }"]');
            if (loadingEl) loadingEl.__x.$data.show = true;

            try {
                const formData = new FormData();
                formData.append('poli_id', poliId);

                const response = await fetch('<?= base_url('kiosk/ambil') ?>', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    // Show success modal
                    const modal = document.querySelector('[x-data="successModal()"]');
                    if (modal) {
                        modal.__x.data = result.data;
                        modal.__x.$data.show = true;
                    }
                } else {
                    alert(result.message || 'Terjadi kesalahan');
                }
            } catch (e) {
                alert('Terjadi kesalahan koneksi');
            } finally {
                if (loadingEl) loadingEl.__x.$data.show = false;
            }
        }
    }
}

function successModal() {
    return {
        show: false,
        data: null,

        closeModal() {
            this.show = false;
            window.location.reload();
        },

        async printTicket() {
            if (this.data?.id) {
                window.open('<?= base_url('kiosk/tiket') ?>/' + this.data.id, '_blank');
            }
        }
    }
}
</script>
<?= $this->endSection() ?>
