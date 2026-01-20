<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="/images/logo.png">
    <title><?= $this->renderSection('title', 'Login - Sistem Antrian') ?></title>

    <link rel="stylesheet" href="/css/app.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>


    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', system-ui, sans-serif; }
        
        .animate-blob {
            animation: blob 7s infinite;
        }
        .animation-delay-2000 {
            animation-delay: 2s;
        }
        .animation-delay-4000 {
            animation-delay: 4s;
        }
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
    </style>
</head>
<body class="bg-slate-50 min-h-screen font-sans text-slate-900 antialiased">
    
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
