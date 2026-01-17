<?= $this->extend('layouts/base') ?>

<?= $this->section('title') ?>Admin Panel - Sistem Antrian Puskesmas<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-gray-50">
    <!-- Admin Navbar -->
    <nav class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-purple-600 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-lg font-bold text-gray-800">Admin Panel</h1>
                    <p class="text-sm text-gray-500">Manajemen Sistem Antrian</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="/dashboard" class="text-sm text-gray-600 hover:text-primary-600">Dashboard</a>
                <a href="/auth/logout" class="bg-red-50 hover:bg-red-100 text-red-600 px-4 py-2 rounded-lg text-sm font-medium">
                    Logout
                </a>
            </div>
        </div>
    </nav>

    <!-- Admin Content -->
    <div class="max-w-7xl mx-auto px-4 py-6">
        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-xl p-5 shadow-sm border">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Total Poli</p>
                        <p class="text-2xl font-bold text-gray-800"><?= count($polis) ?></p>
                    </div>
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl p-5 shadow-sm border">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Total Users</p>
                        <p class="text-2xl font-bold text-gray-800"><?= count($users) ?></p>
                    </div>
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl p-5 shadow-sm border">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Antrian Hari Ini</p>
                        <p class="text-2xl font-bold text-gray-800"><?= $today_stats['total'] ?? 0 ?></p>
                    </div>
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl p-5 shadow-sm border">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Selesai</p>
                        <p class="text-2xl font-bold text-gray-800"><?= $today_stats['completed'] ?? 0 ?></p>
                    </div>
                    <div class="w-10 h-10 bg-teal-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <a href="/admin/antrian" class="bg-white rounded-xl p-6 shadow-sm border hover:shadow-md transition group">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800">Kelola Antrian</h3>
                        <p class="text-sm text-gray-500">Ubah/hapus nomor antrian</p>
                    </div>
                </div>
            </a>

            <a href="/admin/poli" class="bg-white rounded-xl p-6 shadow-sm border hover:shadow-md transition group">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800">Manajemen Poli</h3>
                        <p class="text-sm text-gray-500">Tambah/edit/hapus poli</p>
                    </div>
                </div>
            </a>

            <a href="/admin/users" class="bg-white rounded-xl p-6 shadow-sm border hover:shadow-md transition group">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800">Manajemen User</h3>
                        <p class="text-sm text-gray-500">Kelola petugas & admin</p>
                    </div>
                </div>
            </a>

            <a href="/admin/laporan/harian" class="bg-white rounded-xl p-6 shadow-sm border hover:shadow-md transition group">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800">Laporan</h3>
                        <p class="text-sm text-gray-500">Lihat laporan harian/bulanan</p>
                    </div>
                </div>
            </a>

            <a href="/admin/settings" class="bg-white rounded-xl p-6 shadow-sm border hover:shadow-md transition group">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800">Pengaturan</h3>
                        <p class="text-sm text-gray-500">Konfigurasi sistem & reset</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Recent Activity -->
        <div class="mt-8 bg-white rounded-xl shadow-sm border p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Link Cepat</h2>
            <div class="flex flex-wrap gap-3">
                <a href="/kiosk" target="_blank" class="inline-flex items-center gap-2 bg-primary-50 hover:bg-primary-100 text-primary-700 px-4 py-2 rounded-lg text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    Buka Kiosk
                </a>
                <a href="/display" target="_blank" class="inline-flex items-center gap-2 bg-green-50 hover:bg-green-100 text-green-700 px-4 py-2 rounded-lg text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Buka Display
                </a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
