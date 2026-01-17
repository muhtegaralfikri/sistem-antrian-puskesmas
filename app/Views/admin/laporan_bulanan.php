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
                <a href="/admin/laporan/bulanan" class="block py-2 px-4 bg-blue-800">Laporan Bulanan</a>
                <a href="/admin/settings" class="block py-2 px-4 hover:bg-blue-800">Pengaturan</a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-6"><?= esc($title) ?></h1>

            <!-- Filter Form -->
            <form method="get" class="bg-white rounded-lg shadow p-4 mb-6">
                <div class="flex gap-4 items-end">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Bulan</label>
                        <input type="month" name="bulan" value="<?= esc($bulan) ?>" class="border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Poli</label>
                        <select name="poli_id" class="border rounded px-3 py-2">
                            <option value="">Semua Poli</option>
                            <?php foreach ($polis as $poli): ?>
                                <option value="<?= $poli['id'] ?>" <?= $poli_id == $poli['id'] ? 'selected' : '' ?>>
                                    <?= esc($poli['nama']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                        Filter
                    </button>
                    <a href="/admin/laporan/bulanan" class="text-gray-600 hover:text-gray-800 px-4 py-2">
                        Reset
                    </a>
                </div>
            </form>

            <!-- Statistics -->
            <div class="grid grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-lg shadow p-4">
                    <p class="text-gray-500 text-sm">Total</p>
                    <p class="text-2xl font-bold text-blue-600"><?= $stats['total'] ?></p>
                </div>
                <div class="bg-white rounded-lg shadow p-4">
                    <p class="text-gray-500 text-sm">Menunggu</p>
                    <p class="text-2xl font-bold text-yellow-600"><?= $stats['waiting'] ?></p>
                </div>
                <div class="bg-white rounded-lg shadow p-4">
                    <p class="text-gray-500 text-sm">Selesai</p>
                    <p class="text-2xl font-bold text-green-600"><?= $stats['completed'] ?></p>
                </div>
                <div class="bg-white rounded-lg shadow p-4">
                    <p class="text-gray-500 text-sm">Dilewat</p>
                    <p class="text-2xl font-bold text-red-600"><?= $stats['skipped'] ?></p>
                </div>
            </div>

            <!-- Daily Chart -->
            <?php if (!empty($daily_stats)): ?>
            <div class="bg-white rounded-lg shadow p-4 mb-6">
                <h3 class="font-bold text-gray-800 mb-4">Statistik Harian</h3>
                <div class="flex items-end gap-1 h-40">
                    <?php
                    $maxCount = max(array_column($daily_stats, 'total'));
                    foreach ($daily_stats as $date => $stat):
                        $height = $maxCount > 0 ? ($stat['total'] / $maxCount * 100) : 0;
                    ?>
                        <div class="flex-1 flex flex-col items-center">
                            <div class="w-full bg-blue-500 rounded-t" style="height: <?= $height ?>%"></div>
                            <div class="text-xs text-gray-500 mt-1"><?= date('d M', strtotime($date)) ?></div>
                            <div class="text-xs font-bold"><?= $stat['completed'] ?>/<?= $stat['total'] ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Table -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nomor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Poli</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ambil</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Selesai</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php if (empty($antrian)): ?>
                            <tr>
                                <td colspan="8" class="px-6 py-4 text-center text-gray-500">Tidak ada data</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($antrian as $i => $a): ?>
                                <tr>
                                    <td class="px-6 py-4"><?= $i + 1 ?></td>
                                    <td class="px-6 py-4 font-bold"><?= esc($a['nomor']) ?></td>
                                    <td class="px-6 py-4"><?= esc($a['poli_nama']) ?></td>
                                    <td class="px-6 py-4"><?= esc($a['nama_pasien'] ?? '-') ?></td>
                                    <td class="px-6 py-4 text-sm"><?= date('d/m/Y', strtotime($a['created_at'])) ?></td>
                                    <td class="px-6 py-4 text-sm"><?= $a['waktu_ambil'] ? date('H:i', strtotime($a['waktu_ambil'])) : '-' ?></td>
                                    <td class="px-6 py-4 text-sm"><?= $a['waktu_selesai'] ? date('H:i', strtotime($a['waktu_selesai'])) : '-' ?></td>
                                    <td class="px-6 py-4">
                                        <?php
                                        $statusColors = [
                                            'waiting' => 'bg-yellow-100 text-yellow-800',
                                            'called' => 'bg-blue-100 text-blue-800',
                                            'serving' => 'bg-purple-100 text-purple-800',
                                            'completed' => 'bg-green-100 text-green-800',
                                            'skipped' => 'bg-red-100 text-red-800',
                                        ];
                                        $statusLabels = [
                                            'waiting' => 'Menunggu',
                                            'called' => 'Dipanggil',
                                            'serving' => 'Dilayani',
                                            'completed' => 'Selesai',
                                            'skipped' => 'Dilewat',
                                        ];
                                        ?>
                                        <span class="px-2 py-1 text-xs rounded-full <?= $statusColors[$a['status']] ?? 'bg-gray-100 text-gray-800' ?>">
                                            <?= $statusLabels[$a['status']] ?? $a['status'] ?>
                                        </span>
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
