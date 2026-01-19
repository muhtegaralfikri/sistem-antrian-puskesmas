<?= $this->extend('layouts/admin') ?>

<?= $this->section('title') ?>Kelola Pengguna - Admin Panel<?= $this->endSection() ?>

<?= $this->section('page_title') ?>Kelola Pengguna<?= $this->endSection() ?>
<?= $this->section('page_subtitle') ?>Daftar semua pengguna yang memiliki akses sistem<?= $this->endSection() ?>

<?= $this->section('page_actions') ?>
<a href="/admin/users/create" class="w-full md:w-auto bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-xl flex items-center justify-center gap-2 font-medium shadow-sm transition">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
    </svg>
    Tambah Pengguna
</a>
<?= $this->endSection() ?>

<?= $this->section('content_body') ?>
    <!-- Mobile View (Cards) -->
    <div class="grid grid-cols-1 gap-4 md:hidden mb-6">
        <?php if (empty($users)): ?>
            <div class="bg-white rounded-xl shadow-sm border p-6 text-center text-gray-500 italic">
                Belum ada pengguna
            </div>
        <?php else: ?>
            <?php foreach ($users as $user): ?>
                <div class="bg-white rounded-xl shadow-sm border p-4 space-y-3">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg flex items-center justify-center bg-gray-100">
                                <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-800"><?= esc($user['username']) ?></h3>
                                <p class="text-sm text-gray-500"><?= esc($user['nama_lengkap']) ?></p>
                            </div>
                        </div>
                        <span class="px-2.5 py-0.5 text-xs font-bold rounded-lg <?= $user['role'] === 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' ?>">
                            <?= ucfirst(esc($user['role'])) ?>
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-2 text-sm text-gray-600 border-t pt-2 mt-2">
                        <div>
                            <span class="block text-xs text-gray-400">Email</span>
                            <?= esc($user['email'] ?? '-') ?>
                        </div>
                        <div>
                            <span class="block text-xs text-gray-400">Poli</span>
                            <?= esc($user['poli_nama'] ?? '-') ?>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-3 border-t">
                        <?php if ($user['aktif']): ?>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                Aktif
                            </span>
                        <?php else: ?>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                Nonaktif
                            </span>
                        <?php endif; ?>
                        
                        <div class="flex items-center gap-2">
                            <a href="/admin/users/edit/<?= $user['id'] ?>" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            <form action="/admin/users/delete/<?= $user['id'] ?>" method="post" class="inline" onsubmit="return confirm('Hapus pengguna ini?');">
                                <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors bg-transparent border-none cursor-pointer">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Desktop View (Table) -->
    <div class="hidden md:block bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
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
                                            <form action="/admin/users/delete/<?= $user['id'] ?>" method="post" class="inline" onsubmit="return confirm('Hapus pengguna ini?');">
                                                <button type="submit" class="text-red-600 hover:text-red-800 font-medium text-sm transition-colors bg-transparent border-none p-0 cursor-pointer">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
<?= $this->endSection() ?>
