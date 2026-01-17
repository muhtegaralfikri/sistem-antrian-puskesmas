<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?> - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-blue-900 text-white">
            <div class="p-4">
                <h1 class="text-xl font-bold">Admin Panel</h1>
                <p class="text-blue-200 text-sm">Sistem Antrian</p>
            </div>
            <nav class="mt-4">
                <a href="/admin" class="block py-2 px-4 hover:bg-blue-800">Dashboard</a>
                <a href="/admin/poli" class="block py-2 px-4 hover:bg-blue-800">Kelola Poli</a>
                <a href="/admin/users" class="block py-2 px-4 bg-blue-800">Kelola Pengguna</a>
                <a href="/admin/antrian" class="block py-2 px-4 hover:bg-blue-800">Kelola Antrian</a>
                <a href="/admin/laporan/harian" class="block py-2 px-4 hover:bg-blue-800">Laporan</a>
                <a href="/admin/settings" class="block py-2 px-4 hover:bg-blue-800">Pengaturan</a>
                <a href="/auth/logout" class="block py-2 px-4 hover:bg-blue-800 mt-4">Logout</a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800"><?= esc($title) ?></h1>
                <a href="/admin/users/create" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                    + Tambah Pengguna
                </a>
            </div>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    <?= esc(session()->getFlashdata('success')) ?>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <?= esc(session()->getFlashdata('error')) ?>
                </div>
            <?php endif; ?>

            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Username</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Lengkap</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Poli</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php if (empty($users)): ?>
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-gray-500">Belum ada pengguna</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td class="px-6 py-4 font-medium"><?= esc($user['username']) ?></td>
                                    <td class="px-6 py-4"><?= esc($user['nama_lengkap']) ?></td>
                                    <td class="px-6 py-4"><?= esc($user['email'] ?? '-') ?></td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 text-xs rounded-full <?= $user['role'] === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' ?>">
                                            <?= ucfirst(esc($user['role'])) ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4"><?= esc($user['poli_nama'] ?? '-') ?></td>
                                    <td class="px-6 py-4">
                                        <?php if ($user['aktif']): ?>
                                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Aktif</span>
                                        <?php else: ?>
                                            <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Nonaktif</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <a href="/admin/users/edit/<?= $user['id'] ?>" class="text-blue-600 hover:text-blue-800 mr-2">Edit</a>
                                        <a href="/admin/users/delete/<?= $user['id'] ?>" class="text-red-600 hover:text-red-800" onclick="return confirm('Hapus pengguna ini?')">Hapus</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>
