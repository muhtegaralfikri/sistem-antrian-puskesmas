<?= $this->extend('layouts/base') ?>

<?= $this->section('title') ?>Kelola Antrian - Admin Panel<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    [x-cloak] { display: none !important; }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-gray-50">
    <!-- Admin Navbar -->
    <nav class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="/admin" class="text-gray-600 hover:text-gray-800">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-lg font-bold text-gray-800">Kelola Antrian</h1>
                    <p class="text-sm text-gray-500">Ubah nomor atau hapus antrian</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="/admin" class="text-sm text-gray-600 hover:text-primary-600">Dashboard</a>
                <a href="/auth/logout" class="bg-red-50 hover:bg-red-100 text-red-600 px-4 py-2 rounded-lg text-sm font-medium">
                    Logout
                </a>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <div class="max-w-7xl mx-auto px-4 py-6" x-data="antrianData()" x-init="init()">
        <!-- Poli Selector -->
        <div class="bg-white rounded-xl shadow-sm border p-4 mb-6">
            <div class="flex items-center gap-4">
                <label class="text-sm font-medium text-gray-700">Pilih Poli:</label>
                <select x-model="selectedPoliId" @change="loadAntrian()" class="border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <?php foreach ($polis as $p): ?>
                    <option value="<?= $p['id'] ?>" <?= ($selected_poli_id ?? 0) == $p['id'] ? 'selected' : '' ?>>
                        <?= esc($p['nama']) ?> (<?= esc($p['prefix']) ?>)
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <!-- Antrian List -->
        <div class="bg-white rounded-xl shadow-sm border">
            <div class="p-4 border-b">
                <div class="flex items-center justify-between">
                    <h2 class="font-semibold text-gray-800">Daftar Antrian</h2>
                    <button @click="loadAntrian()" class="text-sm text-primary-600 hover:text-primary-700">
                        <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Refresh
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nomor</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pasien</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Waktu Ambil</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Waktu Panggil</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <template x-for="antrian in antrians" :key="antrian.id">
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    <span x-text="antrian.nomor" class="font-bold text-gray-900"></span>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600" x-text="antrian.nama_pasien || '-'"></td>
                                <td class="px-4 py-3">
                                    <span class="text-xs px-2 py-1 rounded-full font-medium"
                                          :class="{
                                            'bg-yellow-100 text-yellow-800': antrian.status === 'waiting',
                                            'bg-blue-100 text-blue-800': antrian.status === 'called' || antrian.status === 'serving',
                                            'bg-green-100 text-green-800': antrian.status === 'completed',
                                            'bg-red-100 text-red-800': antrian.status === 'skipped'
                                          }"
                                          x-text="getStatusText(antrian.status)">
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600" x-text="formatDateTime(antrian.waktu_ambil)"></td>
                                <td class="px-4 py-3 text-sm text-gray-600" x-text="formatDateTime(antrian.waktu_panggil)"></td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <button @click="openEditModal(antrian)" class="text-blue-600 hover:text-blue-800 text-sm">
                                            Ubah Nomor
                                        </button>
                                        <button @click="deleteAntrian(antrian.id)" class="text-red-600 hover:text-red-800 text-sm">
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                        <tr x-show="antrians.length === 0">
                            <td colspan="6" class="px-4 py-8 text-center text-gray-400">
                                Belum ada antrian untuk poli ini hari ini
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Edit Nomor Modal -->
    <div x-data="{ show: false }" x-show="show" x-cloak
         class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div @click.away="show = false" class="bg-white rounded-xl shadow-xl max-w-md w-full">
            <div class="p-4 border-b">
                <h3 class="font-semibold text-gray-800">Ubah Nomor Antrian</h3>
            </div>
            <div class="p-4">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Saat Ini</label>
                    <input type="text" :value="editingAntrian?.nomor" disabled
                           class="w-full border rounded-lg px-3 py-2 bg-gray-50 text-gray-500">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Baru</label>
                    <input type="text" x-model="newNomor" placeholder="Contoh: A-002"
                           class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                           @keyup.enter="saveNomor()">
                    <p class="text-xs text-gray-500 mt-1">Format: PREFIX-NOMOR (contoh: A-001)</p>
                </div>
                <div class="flex gap-2">
                    <button @click="saveNomor()" :disabled="saving"
                            class="flex-1 bg-primary-600 hover:bg-primary-700 disabled:bg-gray-300 text-white px-4 py-2 rounded-lg font-medium">
                        <span x-show="!saving">Simpan</span>
                        <span x-show="saving">Menyimpan...</span>
                    </button>
                    <button @click="show = false; editingAntrian = null; newNomor = ''"
                            class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg font-medium">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function antrianData() {
    return {
        selectedPoliId: <?= $selected_poli_id ?? 'null' ?>,
        antrians: [],
        editingAntrian: null,
        newNomor: '',
        saving: false,

        init() {
            this.loadAntrian();

            // Make edit modal accessible globally
            this.$watch('editingAntrian', (val) => {
                if (val) {
                    document.querySelector('[x-data="{ show: false }"]').show = true;
                    this.newNomor = val.nomor;
                }
            });
        },

        async loadAntrian() {
            try {
                const response = await fetch(`${this.apiUrl}/admin/antrian?poli_id=${this.selectedPoliId}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                const result = await response.json();
                if (result.success) {
                    this.antrians = result.data;
                }
            } catch (error) {
                console.error('Error loading antrian:', error);
            }
        },

        openEditModal(antrian) {
            this.editingAntrian = antrian;
        },

        async saveNomor() {
            if (!this.editingAntrian || !this.newNomor) return;

            this.saving = true;
            try {
                const formData = new FormData();
                formData.append('nomor', this.newNomor);

                const response = await fetch(`${this.apiUrl}/admin/antrian/${this.editingAntrian.id}/nomor`, {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();

                if (result.success) {
                    // Close modal
                    document.querySelector('[x-data="{ show: false }"]').show = false;
                    this.editingAntrian = null;
                    this.newNomor = '';

                    // Reload data
                    await this.loadAntrian();

                    // Show success message
                    alert('Nomor antrian berhasil diubah');
                } else {
                    alert(result.message || 'Gagal mengubah nomor antrian');
                }
            } catch (error) {
                console.error('Error updating nomor:', error);
                alert('Terjadi kesalahan saat mengubah nomor');
            } finally {
                this.saving = false;
            }
        },

        async deleteAntrian(id) {
            if (!confirm('Yakin ingin menghapus antrian ini?')) return;

            try {
                const response = await fetch(`${this.apiUrl}/admin/antrian/${id}`, {
                    method: 'DELETE'
                });
                const result = await response.json();

                if (result.success) {
                    await this.loadAntrian();
                    alert('Antrian berhasil dihapus');
                } else {
                    alert(result.message || 'Gagal menghapus antrian');
                }
            } catch (error) {
                console.error('Error deleting antrian:', error);
                alert('Terjadi kesalahan saat menghapus antrian');
            }
        },

        getStatusText(status) {
            const statusMap = {
                'waiting': 'Menunggu',
                'called': 'Dipanggil',
                'serving': 'Dilayani',
                'completed': 'Selesai',
                'skipped': 'Dilewati'
            };
            return statusMap[status] || status;
        },

        formatDateTime(dateStr) {
            if (!dateStr) return '-';
            const date = new Date(dateStr);
            return date.toLocaleString('id-ID', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }
    };
}
</script>
<?= $this->endSection() ?>
