<?= $this->extend('layouts/admin') ?>

<?= $this->section('page_title') ?>Kelola Antrian<?= $this->endSection() ?>
<?= $this->section('page_subtitle') ?>Ubah nomor atau hapus antrian<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    [x-cloak] { display: none !important; }
</style>
<?= $this->endSection() ?>

<?= $this->section('content_body') ?>
<div x-data="antrianData()" x-init="init()">
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

    <!-- Pagination -->
    <div class="mt-4 flex items-center justify-between" x-show="antrians.length > 0 && pagination.total_pages > 1">
        <div class="text-sm text-gray-500">
            Hal <span x-text="pagination.current_page"></span> dari <span x-text="pagination.total_pages"></span>
            (<span x-text="pagination.total_items"></span> data)
        </div>
        <div class="flex gap-2">
            <button @click="loadAntrian(pagination.current_page - 1)" 
                    :disabled="pagination.current_page <= 1"
                    class="px-3 py-1.5 border border-gray-300 rounded-lg text-sm hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                Previous
            </button>
            <button @click="loadAntrian(pagination.current_page + 1)" 
                    :disabled="pagination.current_page >= pagination.total_pages"
                    class="px-3 py-1.5 border border-gray-300 rounded-lg text-sm hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                Next
            </button>
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
        
        pagination: {
            current_page: 1,
            total_pages: 1,
            total_items: 0,
            per_page: 10
        },
        
        init() {
            this.loadAntrian();
        },

        async loadAntrian(page = 1) {
            try {
                const response = await fetch(`${this.apiUrl}/admin/antrian?poli_id=${this.selectedPoliId}&page=${page}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                const result = await response.json();
                if (result.success) {
                    this.antrians = result.data;
                    if (result.pager) {
                        this.pagination = result.pager;
                    }
                }
            } catch (error) {
                console.error('Error loading antrian:', error);
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
                    await this.loadAntrian(this.pagination.current_page);
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
