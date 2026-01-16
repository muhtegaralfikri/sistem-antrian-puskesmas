<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->renderSection('title', 'Sistem Antrian Puskesmas') ?></title>

    <!-- Tailwind CSS (CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine.js (CDN) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Tailwind Config -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        },
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                    },
                }
            }
        }
    </script>

    <!-- Custom Styles -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

        body {
            font-family: 'Inter', system-ui, sans-serif;
        }

        /* Print styles for ticket */
        @media print {
            .no-print {
                display: none !important;
            }
            .print-only {
                display: block !important;
            }
            body {
                background: white !important;
            }
        }

        /* Animation for voice indicator */
        @keyframes pulse-ring {
            0% { transform: scale(0.8); opacity: 1; }
            100% { transform: scale(1.4); opacity: 0; }
        }

        .voice-active::before {
            content: '';
            position: absolute;
            inset: -4px;
            border-radius: 50%;
            background: #22c55e;
            animation: pulse-ring 1.5s ease-out infinite;
        }

        /* Scrollbar styling */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>

    <?= $this->renderSection('styles') ?>
</head>
<body class="bg-gray-50 min-h-screen" x-data="appData()">
    <?= $this->renderSection('content') ?>

    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('success')): ?>
    <div x-data="{ show: true }" x-show="show" x-transition.opacity.duration.500ms
         class="fixed top-4 right-4 z-50 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center gap-2"
         x-init="setTimeout(() => show = false, 5000)">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        <span><?= session()->getFlashdata('success') ?></span>
    </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
    <div x-data="{ show: true }" x-show="show" x-transition.opacity.duration.500ms
         class="fixed top-4 right-4 z-50 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center gap-2"
         x-init="setTimeout(() => show = false, 5000)">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
        <span><?= session()->getFlashdata('error') ?></span>
    </div>
    <?php endif; ?>

    <!-- Loading Overlay (hidden by default) -->
    <div x-data="{ loading: false }" x-show="loading" x-transition.opacity
         class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center" style="display: none;">
        <div class="bg-white rounded-lg p-6 shadow-xl">
            <div class="animate-spin w-8 h-8 border-4 border-primary-200 border-t-primary-600 rounded-full"></div>
        </div>
    </div>

    <?= $this->renderSection('scripts') ?>

    <!-- Global App Data -->
    <script>
        function appData() {
            return {
                baseUrl: '<?= base_url() ?>',
                apiUrl: '<?= base_url('api/v1') ?>',
                csrfToken: '<?= csrf_hash() ?>',
                currentUser: <?= session()->get('user_id') ? json_encode([
                    'id' => session()->get('user_id'),
                    'username' => session()->get('username'),
                    'nama_lengkap' => session()->get('nama_lengkap'),
                    'role' => session()->get('user_role'),
                ]) : 'null' ?>,
            }
        }
    </script>
</body>
</html>

<?= $this->renderSection('bottom') ?>
