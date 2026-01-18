<?= $this->extend('layouts/admin') ?>

<?= $this->section('page_title') ?>Pengaturan<?= $this->endSection() ?>
<?= $this->section('page_subtitle') ?>Konfigurasi sistem<?= $this->endSection() ?>

<?= $this->section('content_body') ?>
<script>
    // Global data to avoid HTML attribute syntax errors
    window.settingsData = <?= json_encode($settings) ?>;
    window.polisData = <?= json_encode($polis) ?>;
</script>

<div x-data="settingsManager()">
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
                            <input type="checkbox" name="voice_enabled" x-model="settings.voice_enabled" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                        </label>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Volume Suara</label>
                        <input type="range" name="voice_volume" x-model="settings.voice_volume" min="0" max="1" step="0.1" class="w-full">
                        <div class="flex justify-between text-xs text-gray-500 mt-1">
                            <span>0%</span>
                            <span x-text="Math.round(settings.voice_volume * 100) + '%'"></span>
                            <span>100%</span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Maksimal Panggilan Ulang</label>
                        <input type="number" name="recall_max" x-model="settings.recall_max" min="1" max="10" class="w-full px-4 py-2 border rounded-lg">
                    </div>
                </div>
            </div>

            <!-- Display Settings -->
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Tampilan Display</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Antrian Ditampilkan</label>
                        <input type="number" name="display_count" x-model="settings.display_count" min="1" max="10" class="w-full px-4 py-2 border rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Interval Auto-Refresh (detik)</label>
                        <input type="number" name="auto_refresh_interval" x-model="settings.auto_refresh_interval" min="1" max="60" class="w-full px-4 py-2 border rounded-lg">
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
                        <input type="checkbox" name="kiosk_show_name" x-model="settings.kiosk_show_name" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                    </label>
                </div>
            </div>

            <!-- Reset Settings -->
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Reset Antrian</h3>
                <div class="mb-4">
                     <label class="block text-sm font-medium text-gray-700 mb-2">Waktu Reset Otomatis</label>
                     <input type="time" name="reset_time" x-model="settings.reset_time" class="w-full px-4 py-2 border rounded-lg max-w-xs">
                     <p class="text-xs text-gray-500 mt-1">Waktu untuk mereset nomor antrian setiap hari (Format 24 jam)</p>
                </div>
                
                <hr class="my-4 border-gray-100">
                
                <p class="text-sm text-gray-500 mb-4">Reset manual:</p>
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
function settingsManager() {
    return {
        // Use global variables
        settings: window.settingsData || {},
        polis: window.polisData || [],

        // Fix boolean initialization for checkboxes
        init() {
            // Ensure booleans are actually booleans
            this.settings.voice_enabled = this.settings.voice_enabled == '1';
            this.settings.kiosk_show_name = this.settings.kiosk_show_name == '1';
        },

        async saveSettings() {
            const formData = new FormData();
            
            // Convert booleans back to 1/0 for server
            const dataToSend = { ...this.settings };
            dataToSend.voice_enabled = this.settings.voice_enabled ? '1' : '0';
            dataToSend.kiosk_show_name = this.settings.kiosk_show_name ? '1' : '0';

            for (const [key, value] of Object.entries(dataToSend)) {
                formData.append(key, value);
            }

            try {
                const response = await fetch('/admin/settings/update', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();

                if (result.success) {
                    alert('Pengaturan berhasil disimpan');
                    window.location.reload();
                } else {
                    alert('Gagal menyimpan pengaturan: ' + (result.message || 'Error'));
                }
            } catch (e) {
                console.error(e);
                alert('Terjadi kesalahan koneksi');
            }
        },

        async resetPoli(poliId) {
            if (!confirm('Yakin ingin reset antrian untuk poli ini?')) return;

            const formData = new FormData();
            try {
                const response = await fetch(`/admin/settings/reset-antrian/${poliId}`, {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    alert('Antrian berhasil direset');
                    window.location.reload();
                } else {
                    alert('Gagal reset antrian: ' + (result.message || 'Error'));
                }
            } catch (e) {
                console.error(e);
                alert('Terjadi kesalahan koneksi');
            }
        },

        async resetAll() {
            if (!confirm('Yakin ingin reset SEMUA antrian?')) return;

            const formData = new FormData();
            try {
                const response = await fetch('/admin/settings/reset-all', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    alert('Semua antrian berhasil direset');
                    window.location.reload();
                } else {
                    alert('Gagal reset antrian: ' + (result.message || 'Error'));
                }
            } catch (e) {
                console.error(e);
                alert('Terjadi kesalahan koneksi');
            }
        }
    }
}
</script>
<?= $this->endSection() ?>
