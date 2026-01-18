<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-gray-50 flex flex-col">
    <!-- Admin Navbar -->
    <nav class="bg-white shadow-sm border-b sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="/admin" class="flex items-center gap-3 hover:opacity-80 transition">
                    <div class="w-10 h-10 bg-primary-600 rounded-xl flex items-center justify-center shadow-lg shadow-primary-500/30">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold text-gray-800">Admin Panel</h1>
                        <p class="text-sm text-gray-500">Manajemen Sistem Antrian</p>
                    </div>
                </a>
            </div>
            
            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center gap-6">
                <a href="/admin" class="text-sm font-medium text-gray-600 hover:text-primary-600 transition <?= uri_string() == 'admin' ? 'text-primary-600' : '' ?>">Dashboard</a>
                <a href="/monitor" target="_blank" class="text-sm font-medium text-gray-600 hover:text-primary-600 transition">Monitor</a>
                <a href="/admin/poli" class="text-sm font-medium text-gray-600 hover:text-primary-600 transition <?= strpos(uri_string(), 'poli') !== false ? 'text-primary-600' : '' ?>">Poli</a>
                <a href="/admin/users" class="text-sm font-medium text-gray-600 hover:text-primary-600 transition <?= strpos(uri_string(), 'users') !== false ? 'text-primary-600' : '' ?>">Users</a>
                <a href="/admin/antrian" class="text-sm font-medium text-gray-600 hover:text-primary-600 transition <?= strpos(uri_string(), 'antrian') !== false ? 'text-primary-600' : '' ?>">Antrian</a>
                <a href="/admin/laporan/harian" class="text-sm font-medium text-gray-600 hover:text-primary-600 transition <?= strpos(uri_string(), 'laporan') !== false ? 'text-primary-600' : '' ?>">Laporan</a>
                <a href="/admin/settings" class="text-sm font-medium text-gray-600 hover:text-primary-600 transition <?= strpos(uri_string(), 'settings') !== false ? 'text-primary-600' : '' ?>">Settings</a>
                <a href="/admin/audit-log" class="text-sm font-medium text-gray-600 hover:text-primary-600 transition <?= strpos(uri_string(), 'audit-log') !== false ? 'text-primary-600' : '' ?>">Log Aktivitas</a>
                
                <div class="h-6 w-px bg-gray-200"></div>

                <a href="/auth/logout" class="bg-red-50 hover:bg-red-100 text-red-600 px-4 py-2 rounded-lg text-sm font-medium transition">
                    Logout
                </a>
            </div>

            <!-- Mobile Menu Button (Hamburger) - Optional Implementation -->
            <div class="md:hidden">
                <a href="/auth/logout" class="text-red-500 text-sm font-medium">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Main Content Wrapper -->
    <div class="flex-1 max-w-7xl w-full mx-auto px-4 py-6">
        <!-- Breadcrumb / Title Section -->
        <?php if ($this->renderSection('page_title')): ?>
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800"><?= $this->renderSection('page_title') ?></h1>
                <?php if ($this->renderSection('page_subtitle')): ?>
                    <p class="text-gray-500 mt-1"><?= $this->renderSection('page_subtitle') ?></p>
                <?php endif; ?>
            </div>
            <div>
                <?= $this->renderSection('page_actions') ?>
            </div>
        </div>
        <?php endif; ?>

        <?= $this->renderSection('content_body') ?>
    </div>

    <!-- Simple Footer -->
    <footer class="bg-white border-t py-4 mt-auto">
        <div class="max-w-7xl mx-auto px-4 text-center text-sm text-gray-400">
            &copy; <?= date('Y') ?> Puskesmas Modern. All rights reserved.
        </div>
    </footer>
</div>
<?= $this->endSection() ?>
