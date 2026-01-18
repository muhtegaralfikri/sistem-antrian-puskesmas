<?= $this->extend('layouts/admin') ?>

<?= $this->section('title') ?><?= esc($title) ?> - Admin<?= $this->endSection() ?>

<?= $this->section('page_title') ?>Laporan Bulanan<?= $this->endSection() ?>
<?= $this->section('page_subtitle') ?>Rekapitulasi data antrian per bulan<?= $this->endSection() ?>

<?= $this->section('content_body') ?>
            <!-- Filter Form -->
            <form method="get" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8">
                <div class="flex flex-col md:flex-row gap-4 items-end">
                    <div class="w-full md:w-auto">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Bulan</label>
                        <input type="month" name="bulan" value="<?= esc($bulan) ?>" class="w-full border-gray-200 rounded-xl px-4 py-2.5 focus:ring-primary-500 focus:border-primary-500 bg-gray-50">
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
                        <a href="/admin/laporan/bulanan" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2.5 rounded-xl font-medium transition">
                            Reset
                        </a>
                        <a href="/admin/laporan/export/bulanan?bulan=<?= esc($bulan) ?>&poli_id=<?= esc($poli_id) ?>" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2.5 rounded-xl font-medium transition shadow-sm hover:shadow-md flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            Export Excel
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

            <!-- Daily Chart -->
            <?php if (!empty($daily_stats)): ?>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8">
                <h3 class="font-bold text-gray-800 mb-6">Statistik Harian</h3>
                <div class="flex items-end gap-1 h-48">
                    <?php
                    $maxCount = max(array_column($daily_stats, 'total'));
                    foreach ($daily_stats as $date => $stat):
                        $height = $maxCount > 0 ? ($stat['total'] / $maxCount * 100) : 0;
                    ?>
                        <div class="flex-1 flex flex-col items-center group relative">
                            <div class="w-full bg-primary-200 rounded-t-lg group-hover:bg-primary-500 transition-all relative overflow-hidden" style="height: <?= $height ?>%">
                                <div class="absolute bottom-0 left-0 right-0 bg-primary-500 transition-all" style="height: <?= $stat['completed'] > 0 ? ($stat['completed'] / $stat['total'] * 100) : 0 ?>%"></div>
                            </div>
                            <div class="text-[10px] text-gray-400 mt-2 rotate-45 origin-left md:rotate-0"><?= date('d', strtotime($date)) ?></div>
                            
                            <!-- Tooltip -->
                            <div class="absolute bottom-full mb-2 hidden group-hover:block bg-gray-800 text-white text-xs rounded py-1 px-2 whitespace-nowrap z-10">
                                <?= date('d M Y', strtotime($date)) ?>: <?= $stat['completed'] ?>/<?= $stat['total'] ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Table -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">No</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nomor</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Poli</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Ambil</th>
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
                                    <td class="px-6 py-4 text-sm text-gray-600"><?= date('d/m/Y', strtotime($a['created_at'])) ?></td>
                                    <td class="px-6 py-4 text-sm text-gray-600 font-mono"><?= $a['waktu_ambil'] ? date('H:i', strtotime($a['waktu_ambil'])) : '-' ?></td>
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
<?= $this->endSection() ?>
