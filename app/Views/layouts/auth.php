<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->renderSection('title', 'Login - Sistem Antrian') ?></title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', system-ui, sans-serif; }
    </style>
</head>
<body class="bg-gradient-to-br from-primary-600 to-primary-800 min-h-screen flex items-center justify-center p-4">

    <?= $this->renderSection('content') ?>

    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('error')): ?>
    <div x-data="{ show: true }" x-show="show" x-transition.opacity
         class="fixed top-4 right-4 z-50 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg"
         x-init="setTimeout(() => show = false, 5000)">
        <?= session()->getFlashdata('error') ?>
    </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('success')): ?>
    <div x-data="{ show: true }" x-show="show" x-transition.opacity
         class="fixed top-4 right-4 z-50 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg"
         x-init="setTimeout(() => show = false, 5000)">
        <?= session()->getFlashdata('success') ?>
    </div>
    <?php endif; ?>

</body>
</html>
