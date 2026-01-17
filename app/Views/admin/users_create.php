<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?> - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-blue-900 text-white">
            <div class="p-4">
                <h1 class="text-xl font-bold">Admin Panel</h1>
            </div>
            <nav class="mt-4">
                <a href="/admin" class="block py-2 px-4 hover:bg-blue-800">Dashboard</a>
                <a href="/admin/users" class="block py-2 px-4 bg-blue-800">Kelola Pengguna</a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6">
            <div class="max-w-2xl mx-auto">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-800"><?= esc($title) ?></h1>
                    <a href="/admin/users" class="text-blue-600 hover:text-blue-800">Kembali</a>
                </div>

                <?php if (session()->getFlashdata('errors')): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <ul>
                            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                <li><?= esc($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form action="/admin/users/create" method="post" class="bg-white rounded-lg shadow p-6">
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Username</label>
                        <input type="text" name="username" value="<?= old('username') ?>" required class="w-full border rounded-lg px-3 py-2" minlength="3" maxlength="50">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Password</label>
                        <input type="password" name="password" required class="w-full border rounded-lg px-3 py-2" minlength="6">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" value="<?= old('nama_lengkap') ?>" required class="w-full border rounded-lg px-3 py-2" minlength="2" maxlength="100">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Email (opsional)</label>
                        <input type="email" name="email" value="<?= old('email') ?>" class="w-full border rounded-lg px-3 py-2">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Role</label>
                        <select name="role" required class="w-full border rounded-lg px-3 py-2" onchange="togglePoli(this.value)">
                            <option value="petugas" <?= old('role') === 'petugas' ? 'selected' : '' ?>>Petugas</option>
                            <option value="admin" <?= old('role') === 'admin' ? 'selected' : '' ?>>Admin</option>
                        </select>
                    </div>

                    <div class="mb-4" id="poli_field">
                        <label class="block text-gray-700 font-medium mb-2">Poli (untuk Petugas)</label>
                        <select name="poli_id" class="w-full border rounded-lg px-3 py-2">
                            <option value="">-- Pilih Poli --</option>
                            <?php foreach ($polis as $poli): ?>
                                <option value="<?= $poli['id'] ?>" <?= old('poli_id') == $poli['id'] ? 'selected' : '' ?>>
                                    <?= esc($poli['nama']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                            Simpan
                        </button>
                        <a href="/admin/users" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script>
    function togglePoli(role) {
        const poliField = document.getElementById('poli_field');
        poliField.style.display = role === 'petugas' ? 'block' : 'none';
    }
    togglePoli('<?= old('role') ?: 'petugas' ?>');
    </script>
</body>
</html>
