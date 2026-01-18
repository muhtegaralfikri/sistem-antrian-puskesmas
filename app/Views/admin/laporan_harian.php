<?= $this->extend('layouts/admin') ?>

<?= $this->section('title') ?><?= esc($title) ?> - Admin<?= $this->endSection() ?>

<?= $this->section('page_title') ?>Laporan Harian<?= $this->endSection() ?>
<?= $this->section('page_subtitle') ?>Rekapitulasi data antrian per hari<?= $this->endSection() ?>

<?= $this->section('content_body') ?>
            <!-- Tab Navigation -->
            <div class="mb-6 border-b border-gray-200">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    <a href="/admin/laporan/harian" class="border-primary-500 text-primary-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Laporan Harian
                    </a>
                    <a href="/admin/laporan/bulanan" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Laporan Bulanan
                    </a>
                </nav>
            </div>

            <!-- Filter Form -->
            <form method="get" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8">
                <div class="flex flex-col md:flex-row gap-4 items-end">
                    <div class="w-full md:w-auto">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal</label>
                        <input type="date" name="tanggal" value="<?= esc($tanggal) ?>" class="w-full border-gray-200 rounded-xl px-4 py-2.5 focus:ring-primary-500 focus:border-primary-500 bg-gray-50">
                    </div>
                    <div class="w-full md:w-auto">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Poli</label>
                        <select name="poli_id" class="w-full border-gray-200 rounded-xl px-4 py-2.5 focus:ring-primary-500 focus:border-primary-500 bg-gray-50">
                            <option value="">Semua Poli</option>
                            <?php foreach ($polis as $poli): ?>
                                <option value="<?= $poli['id'] ?>" <?= $poli_id == $poli['id'] ? 'selected' : '' ?>>
                                    <?= esc($poli['nama']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="flex gap-2 w-full md:w-auto mt-4 md:mt-0">
                        <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-2.5 rounded-xl font-medium transition shadow-sm hover:shadow-md">
                            Filter
                        </button>
                        <a href="/admin/laporan/harian" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2.5 rounded-xl font-medium transition">
                            Reset
                        </a>
                        <a href="/admin/laporan/export/harian?tanggal=<?= esc($tanggal) ?>&poli_id=<?= esc($poli_id) ?>" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2.5 rounded-xl font-medium transition shadow-sm hover:shadow-md flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            Export Excel
                        </a>
                    </div>
                </div>
            </form>

            <!-- Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Antrian</p>
                        <p class="text-3xl font-bold text-gray-800"><?= $stats['total'] ?></p>
                    </div>
                    <div class="w-12 h-12 bg-primary-50 rounded-xl flex items-center justify-center text-primary-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                </div>
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Menunggu</p>
                        <p class="text-3xl font-bold text-yellow-600"><?= $stats['waiting'] ?></p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-50 rounded-xl flex items-center justify-center text-yellow-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Selesai</p>
                        <p class="text-3xl font-bold text-green-600"><?= $stats['completed'] ?></p>
                    </div>
                    <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center text-green-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                </div>
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Dilewat</p>
                        <p class="text-3xl font-bold text-red-600"><?= $stats['skipped'] ?></p>
                    </div>
                    <div class="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center text-red-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">No</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nomor</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Poli</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Ambil</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Panggil</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Selesai</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php if (empty($antrian)): ?>
                            <tr>
                                <td colspan="8" class="px-6 py-8 text-center text-gray-500 italic">Tidak ada data antrian untuk filter ini</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($antrian as $i => $a): ?>
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 text-gray-500"><?= $i + 1 ?></td>
                                    <td class="px-6 py-4 font-bold text-gray-800"><?= esc($a['nomor']) ?></td>
                                    <td class="px-6 py-4"><?= esc($a['poli_nama']) ?></td>
                                    <td class="px-6 py-4 text-gray-900"><?= esc($a['nama_pasien'] ?? '-') ?></td>
                                    <td class="px-6 py-4 text-sm text-gray-600 font-mono"><?= $a['waktu_ambil'] ? date('H:i', strtotime($a['waktu_ambil'])) : '-' ?></td>
                                    <td class="px-6 py-4 text-sm text-gray-600 font-mono"><?= $a['waktu_panggil'] ? date('H:i', strtotime($a['waktu_panggil'])) : '-' ?></td>
                                    <td class="px-6 py-4 text-sm text-gray-600 font-mono"><?= $a['waktu_selesai'] ? date('H:i', strtotime($a['waktu_selesai'])) : '-' ?></td>
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
                                        <span class="px-2.5 py-0.5 text-xs font-bold rounded-lg <?= $statusColors[$a['status']] ?? 'bg-gray-100 text-gray-800' ?>">
                                            <?= $statusLabels[$a['status']] ?? $a['status'] ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="mt-4">
                <?= $pager->links('default', 'tailwind_full') ?>
            </div>
<?= $this->endSection() ?>
