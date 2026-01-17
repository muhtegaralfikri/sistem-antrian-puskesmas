<?= $this->extend('layouts/base') ?>

<?= $this->section('title') ?>Pengaturan - Admin<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div x-data="settingsManager(<?= json_encode($settings) ?>, <?= json_encode($polis) ?>)" class="min-h-screen bg-gray-50">
    <!-- Navbar -->
    <nav class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="/admin" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-lg font-bold text-gray-800">Pengaturan</h1>
                    <p class="text-sm text-gray-500">Konfigurasi sistem</p>
                </div>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <div class="max-w-4xl mx-auto px-4 py-6">
        <form @submit.prevent="saveSettings" class="space-y-6">
            <!-- Voice Settings -->
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Pengaturan Suara</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-medium text-gray-800">Aktifkan Suara</p>
                            <p class="text-sm text-gray-500">Panggilan antrian dengan suara</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" x-model="settings.voice_enabled" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                        </label>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Volume Suara</label>
                        <input type="range" x-model="settings.voice_volume" min="0" max="1" step="0.1" class="w-full">
                        <div class="flex justify-between text-xs text-gray-500 mt-1">
                            <span>0%</span>
                            <span x-text="Math.round(settings.voice_volume * 100) + '%'"></span>
                            <span>100%</span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Maksimal Panggilan Ulang</label>
                        <input type="number" x-model="settings.recall_max" min="1" max="10" class="w-full px-4 py-2 border rounded-lg">
                    </div>
                </div>
            </div>

            <!-- Display Settings -->
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Tampilan Display</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Antrian Ditampilkan</label>
                        <input type="number" x-model="settings.display_count" min="1" max="10" class="w-full px-4 py-2 border rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Interval Auto-Refresh (detik)</label>
                        <input type="number" x-model="settings.auto_refresh_interval" min="1" max="60" class="w-full px-4 py-2 border rounded-lg">
                        <p class="text-xs text-gray-500 mt-1">Fallback jika WebSocket tidak terhubung</p>
                    </div>
                </div>
            </div>

            <!-- Kiosk Settings -->
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Kiosk</h3>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-medium text-gray-800">Tampilkan Input Nama</p>
                        <p class="text-sm text-gray-500">Pasien dapat mengisi nama saat ambil tiket</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" x-model="settings.kiosk_show_name" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                    </label>
                </div>
            </div>

            <!-- Reset Settings -->
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Reset Antrian</h3>
                <p class="text-sm text-gray-500 mb-4">Reset nomor antrian akan memulai dari 001 lagi.</p>
                <div class="flex flex-wrap gap-3">
                    <template x-for="poli in polis" :key="poli.id">
                        <button type="button" @click="resetPoli(poli.id)" class="bg-orange-50 hover:bg-orange-100 text-orange-700 px-4 py-2 rounded-lg text-sm font-medium">
                            Reset <span x-text="poli.nama"></span>
                        </button>
                    </template>
                    <button type="button" @click="resetAll()" class="bg-red-50 hover:bg-red-100 text-red-700 px-4 py-2 rounded-lg text-sm font-medium">
                        Reset Semua
                    </button>
                </div>
            </div>

            <!-- Save Button -->
            <div class="flex justify-end gap-3">
                <a href="/admin" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-3 rounded-lg font-medium">
                    Kembali
                </a>
                <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-3 rounded-lg font-medium">
                    Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function settingsManager(settings, polis) {
    return {
        settings: settings,
        polis: polis,

        async saveSettings() {
            const formData = new FormData();
            for (const [key, value] of Object.entries(this.settings)) {
                formData.append(key, value);
            }

            try {
                const response = await fetch('/admin/settings/update', {
                    method: 'POST',
                    body: formData
                });

                if (response.ok) {
                    alert('Pengaturan berhasil disimpan');
                } else {
                    alert('Gagal menyimpan pengaturan');
                }
            } catch (e) {
                alert('Terjadi kesalahan');
            }
        },

        async resetPoli(poliId) {
            if (!confirm('Yakin ingin reset antrian untuk poli ini?')) return;

            const formData = new FormData();
            const response = await fetch(`/admin/settings/reset-antrian/${poliId}`, {
                method: 'POST',
                body: formData
            });

            if (response.ok) {
                alert('Antrian berhasil direset');
            } else {
                alert('Gagal reset antrian');
            }
        },

        async resetAll() {
            if (!confirm('Yakin ingin reset SEMUA antrian?')) return;

            const formData = new FormData();
            const response = await fetch('/admin/settings/reset-all', {
                method: 'POST',
                body: formData
            });

            if (response.ok) {
                alert('Semua antrian berhasil direset');
            } else {
                alert('Gagal reset antrian');
            }
        }
    }
}
</script>
<?= $this->endSection() ?>
