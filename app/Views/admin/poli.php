<?= $this->extend('layouts/admin') ?>

<?= $this->section('page_title') ?>Manajemen Poli<?= $this->endSection() ?>
<?= $this->section('page_subtitle') ?>Tambah, edit, atau hapus poli<?= $this->endSection() ?>

<?= $this->section('content_body') ?>
<script>
    // Debugging data assignment
    var polisData = <?= json_encode($polis) ?>;
    console.log('Polis Data:', polisData);
</script>
<div x-data="poliManager(polisData)">

    <!-- Top Action -->
    <div class="mb-4 flex justify-end">
        <button @click="openAddModal()" class="w-full md:w-auto bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center justify-center gap-2 shadow-sm transition-all hover:shadow-md">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Poli
        </button>
    </div>
    
    <!-- Mobile View (Cards) -->
    <div class="grid grid-cols-1 gap-4 md:hidden mb-6">
        <template x-for="(poli, index) in polis" :key="poli.id">
            <div class="bg-white rounded-xl shadow-sm border p-4 space-y-3">
                <div class="flex items-start justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg flex items-center justify-center" :class="'bg-' + ['blue', 'green', 'purple', 'orange', 'teal'][index % 5] + '-100'">
                            <svg class="w-5 h-5" :class="'text-' + ['blue', 'green', 'purple', 'orange', 'teal'][index % 5] + '-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800" x-text="poli.nama"></h3>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="bg-gray-100 text-gray-600 text-xs px-2 py-0.5 rounded-full" x-text="'Prefix: ' + poli.prefix"></span>
                                <span class="bg-gray-100 text-gray-600 text-xs px-2 py-0.5 rounded-full" x-text="'Urut: ' + poli.urutan"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-3 border-t">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium"
                          :class="poli.aktif ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'">
                        <span class="w-1.5 h-1.5 rounded-full" :class="poli.aktif ? 'bg-green-500' : 'bg-red-500'"></span>
                        <span x-text="poli.aktif ? 'Aktif' : 'Non-aktif'"></span>
                    </span>
                    
                    <div class="flex items-center gap-2">
                        <button @click="editPoli(poli)" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </button>
                        <button @click="deletePoli(poli)" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <!-- Desktop View (Table) -->
    <div class="hidden md:block bg-white rounded-xl shadow-sm border overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">No</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Nama Poli</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Kode</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Prefix</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Urutan</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <template x-for="(poli, index) in polis" :key="poli.id">
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-800" x-text="index + 1"></td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg flex items-center justify-center" :class="'bg-' + ['blue', 'green', 'purple', 'orange', 'teal'][index % 5] + '-100'">
                                        <svg class="w-5 h-5" :class="'text-' + ['blue', 'green', 'purple', 'orange', 'teal'][index % 5] + '-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                    </div>
                                    <span class="font-medium text-gray-800" x-text="poli.nama"></span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600" x-text="poli.kode"></td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-gray-100 text-gray-800" x-text="poli.prefix"></span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600" x-text="poli.urutan"></td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium"
                                      :class="poli.aktif ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'">
                                    <span class="w-1.5 h-1.5 rounded-full" :class="poli.aktif ? 'bg-green-500' : 'bg-red-500'"></span>
                                    <span x-text="poli.aktif ? 'Aktif' : 'Non-aktif'"></span>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button @click="editPoli(poli)" class="text-blue-600 hover:text-blue-800 mr-3">
                                    <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                <button @click="deletePoli(poli)" class="text-red-600 hover:text-red-800">
                                    <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

    <!-- Add/Edit Modal -->
    <div x-show="showAddModal || showEditModal" x-cloak x-transition.opacity class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div @click.self="closeModal()" class="bg-white rounded-xl shadow-xl max-w-md w-full" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100">
            <div class="px-6 py-4 border-b">
                <h3 class="text-lg font-semibold text-gray-800" x-text="showEditModal ? 'Edit Poli' : 'Tambah Poli Baru'"></h3>
            </div>
            <form @submit.prevent="submitForm" class="p-6 space-y-4">
                <input type="hidden" x-model="form.id">

                <!-- Nama Poli -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Poli</label>
                    <input type="text" x-model="form.nama" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                </div>

                <!-- Kode & Prefix -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kode</label>
                        <input type="text" x-model="form.kode" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Prefix</label>
                        <input type="text" x-model="form.prefix" required maxlength="1" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Urutan</label>
                    <input type="number" x-model="form.urutan" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" id="aktif" x-model="form.aktif" class="rounded text-primary-600">
                    <label for="aktif" class="text-sm text-gray-700">Aktif</label>
                </div>
                <div class="flex gap-3 pt-4">
                    <button type="submit" class="flex-1 bg-primary-600 hover:bg-primary-700 text-white py-2 rounded-lg font-medium">
                        Simpan
                    </button>
                    <button type="button" @click="closeModal()" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 py-2 rounded-lg font-medium">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Debugging data assignment
    var polisData = <?= json_encode($polis) ?>;
</script>
<script>
function poliManager(polis) {
    return {
        polis: polis,
        showAddModal: false,
        showEditModal: false,
        form: { id: null, nama: '', kode: '', prefix: '', urutan: 0, aktif: true },

        resetForm() {
            this.form = { id: null, nama: '', kode: '', prefix: '', urutan: 0, aktif: true };
        },

        openAddModal() {
            this.resetForm();
            this.showAddModal = true;
        },

        closeModal() {
            this.showAddModal = false;
            this.showEditModal = false;
            this.resetForm();
        },

        editPoli(poli) {
            this.form = { ...poli };
            this.showEditModal = true;
        },

        async submitForm() {
            const formData = new FormData();
            formData.append('nama', this.form.nama);
            formData.append('kode', this.form.kode);
            formData.append('prefix', this.form.prefix);
            formData.append('urutan', this.form.urutan || 0);
            formData.append('aktif', this.form.aktif ? 1 : 0);

            const url = this.form.id ? `/admin/poli/update/${this.form.id}` : '/admin/poli/create';

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const csrfHeader = document.querySelector('meta[name="csrf-header"]').getAttribute('content');

                const response = await fetch(url, { 
                    method: 'POST', 
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        [csrfHeader]: csrfToken
                    }
                });
                const result = await response.json();

                if (result.success) {
                    window.location.reload();
                } else {
                    let msg = result.message || 'Terjadi kesalahan';
                    if (result.errors) {
                        msg += '\n' + Object.values(result.errors).join('\n');
                    }
                    alert(msg);
                }
            } catch (e) {
                console.error(e);
                alert('Terjadi kesalahan sistem');
            }
        },

        async deletePoli(poli) {
            if (!confirm(`Yakin ingin menghapus poli "${poli.nama}"?`)) return;

            try {
                const formData = new FormData();
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const csrfHeader = document.querySelector('meta[name="csrf-header"]').getAttribute('content');

                const response = await fetch(`/admin/poli/delete/${poli.id}`, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        [csrfHeader]: csrfToken
                    }
                });
                const result = await response.json();

                if (result.success) {
                    window.location.reload();
                } else {
                    alert(result.message || 'Gagal menghapus poli');
                }
            } catch (e) {
                console.error(e);
                alert('Terjadi kesalahan sistem');
            }
        }
    }
}
</script>
<?= $this->endSection() ?>
