<?= $this->extend('layouts/admin') ?>

<?= $this->section('page_title') ?>
Tambah Pengguna Baru
<?= $this->endSection() ?>

<?= $this->section('page_subtitle') ?>
Tambahkan pengguna baru ke dalam sistem antrian
<?= $this->endSection() ?>

<?= $this->section('page_actions') ?>
<a href="/admin/users" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 bg-white border border-gray-300 hover:bg-gray-50 px-4 py-2 rounded-lg text-sm font-medium transition-colors">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
    </svg>
    Kembali
</a>
<?= $this->endSection() ?>

<?= $this->section('content_body') ?>

<div class="max-w-4xl mx-auto">
    <!-- Alert Messages -->
    <?php if (session()->getFlashdata('errors')): ?>
        <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4 flex gap-3 text-red-800">
            <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <h3 class="font-semibold text-sm">Terjadi Kesalahan</h3>
                <ul class="mt-1 list-disc list-inside text-sm opacity-90">
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    <?php endif; ?>

    <!-- Create Form Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 md:p-8">
            <form action="/admin/users/create" method="post">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                    
                    <!-- Username -->
                    <div class="col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                        <input type="text" name="username" value="<?= old('username') ?>" required 
                               class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-primary-100 focus:border-primary-500 transition-all outline-none" 
                               minlength="3" maxlength="50" placeholder="Contoh: petugas_loket">
                    </div>

                    <!-- Role Selection -->
                    <div class="col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Role Pengguna</label>
                        <select name="role" id="roleSelect" required 
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-primary-100 focus:border-primary-500 transition-all outline-none bg-white" 
                                onchange="togglePoli(this.value)">
                            <option value="petugas" <?= old('role') === 'petugas' ? 'selected' : '' ?>>Petugas Loket</option>
                            <option value="admin" <?= old('role') === 'admin' ? 'selected' : '' ?>>Administrator</option>
                        </select>
                    </div>

                    <!-- Nama Lengkap -->
                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" value="<?= old('nama_lengkap') ?>" required 
                               class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-primary-100 focus:border-primary-500 transition-all outline-none" 
                               minlength="2" maxlength="100" placeholder="Nama lengkap pengguna">
                    </div>

                    <!-- Password Section -->
                    <div class="col-span-1 md:col-span-2 pt-2">
                        <div class="p-4 bg-gray-50 rounded-xl border border-gray-200/60">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                            <input type="password" name="password" required
                                   class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-primary-100 focus:border-primary-500 transition-all outline-none bg-white" 
                                   minlength="6" placeholder="Masukkan password yang kuat">
                            <p class="text-xs text-gray-500 mt-2">Minimal 6 karakter.</p>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email (Opsional)</label>
                        <input type="email" name="email" value="<?= old('email') ?>" 
                               class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-primary-100 focus:border-primary-500 transition-all outline-none"
                               placeholder="email@puskesmas.com">
                    </div>

                    <!-- Poli Selection (Conditional) -->
                    <div class="col-span-1" id="poli_field">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tugaskan ke Poli</label>
                        <select name="poli_id" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-primary-100 focus:border-primary-500 transition-all outline-none bg-white">
                            <option value="">-- Pilih Poli --</option>
                            <?php foreach ($polis as $poli): ?>
                                <option value="<?= $poli['id'] ?>" <?= old('poli_id') == $poli['id'] ? 'selected' : '' ?>>
                                    <?= esc($poli['nama']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <p class="text-xs text-gray-500 mt-1.5">* Wajib dipilih jika role adalah Petugas</p>
                    </div>

                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end gap-4 mt-10 pt-6 border-t border-gray-100">
                    <a href="/admin/users" class="px-6 py-2.5 rounded-xl border border-gray-300 text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-primary-600 text-white font-medium hover:bg-primary-700 shadow-lg shadow-primary-500/30 hover:shadow-primary-600/40 transition-all transform hover:-translate-y-0.5">
                        Simpan Pengguna Baru
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
function togglePoli(role) {
    const poliField = document.getElementById('poli_field');
    if (role === 'petugas') {
        poliField.style.display = 'block';
        poliField.classList.remove('opacity-50', 'pointer-events-none');
    } else {
        poliField.style.display = 'none';
    }
}

// Initialize on load
document.addEventListener('DOMContentLoaded', function() {
    togglePoli(document.getElementById('roleSelect').value || 'petugas');
});
</script>

<?= $this->endSection() ?>
