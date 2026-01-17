<?= $this->extend('layouts/admin') ?>

<?= $this->section('title') ?>Kelola Pengguna - Admin Panel<?= $this->endSection() ?>

<?= $this->section('page_title') ?>Kelola Pengguna<?= $this->endSection() ?>
<?= $this->section('page_subtitle') ?>Daftar semua pengguna yang memiliki akses sistem<?= $this->endSection() ?>

<?= $this->section('page_actions') ?>
<a href="/admin/users/create" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-xl flex items-center gap-2 font-medium shadow-sm transition">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
    </svg>
    Tambah Pengguna
</a>
<?= $this->endSection() ?>

<?= $this->section('content_body') ?>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Username</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Lengkap</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Role</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Poli</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php if (empty($users)): ?>
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-gray-500 italic">Belum ada pengguna</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($users as $user): ?>
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 font-medium text-gray-900"><?= esc($user['username']) ?></td>
                                    <td class="px-6 py-4 text-gray-600"><?= esc($user['nama_lengkap']) ?></td>
                                    <td class="px-6 py-4 text-gray-600"><?= esc($user['email'] ?? '-') ?></td>
                                    <td class="px-6 py-4">
                                        <span class="px-2.5 py-0.5 text-xs font-bold rounded-lg <?= $user['role'] === 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' ?>">
                                            <?= ucfirst(esc($user['role'])) ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-gray-600"><?= esc($user['poli_nama'] ?? '-') ?></td>
                                    <td class="px-6 py-4">
                                        <?php if ($user['aktif']): ?>
                                            <span class="px-2.5 py-0.5 text-xs font-bold rounded-lg bg-green-100 text-green-700">Aktif</span>
                                        <?php else: ?>
                                            <span class="px-2.5 py-0.5 text-xs font-bold rounded-lg bg-red-100 text-red-700">Nonaktif</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <a href="/admin/users/edit/<?= $user['id'] ?>" class="text-blue-600 hover:text-blue-800 font-medium text-sm transition-colors">Edit</a>
                                            <span class="text-gray-300">|</span>
                                            <a href="/admin/users/delete/<?= $user['id'] ?>" class="text-red-600 hover:text-red-800 font-medium text-sm transition-colors" onclick="return confirm('Hapus pengguna ini?')">Hapus</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
<?= $this->endSection() ?>
