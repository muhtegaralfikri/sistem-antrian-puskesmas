<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="/images/logo.png">
    <title><?= $this->renderSection('title', 'Sistem Antrian Puskesmas') ?></title>
    <meta name="csrf-token" content="<?= csrf_hash() ?>">
    <meta name="csrf-header" content="<?= csrf_header() ?>">

    <!-- Local Tailwind CSS (Built) -->
    <link rel="stylesheet" href="/css/app.css">

    <!-- Alpine.js (CDN) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Custom Styles -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

        body {
            font-family: 'Inter', system-ui, sans-serif;
        }

        /* Alpine.js x-cloak - hide elements before Alpine loads */
        [x-cloak] {
            display: none !important;
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

        /* Toast Notification Styles */
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes fadeOut {
            from { opacity: 1; }
            to { opacity: 0; }
        }

        .toast-container {
            position: fixed;
            top: 1rem;
            right: 1rem;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            pointer-events: none;
        }

        .toast {
            pointer-events: auto;
            min-width: 300px;
            max-width: 450px;
            animation: slideInRight 0.3s ease-out;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .toast.toast-removing {
            animation: fadeOut 0.3s ease-out forwards;
        }

        .toast-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }

        .toast-error {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }

        .toast-warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
        }

        .toast-info {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
        }
    </style>

    <?= $this->renderSection('styles') ?>
</head>
<body class="bg-gray-50 min-h-screen" x-data="appData()">
    <?= $this->renderSection('content') ?>

    <!-- Toast Container -->
    <div id="toast-container" class="toast-container"></div>

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

    <!-- Toast Notification Helper -->
    <script>
    // Toast Notification System
    const Toast = (function() {
        const container = document.getElementById('toast-container');
        let toastId = 0;

        const icons = {
            success: '<svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
            error: '<svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
            warning: '<svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>',
            info: '<svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
        };

        function show(message, type = 'info', options = {}) {
            const {
                duration = 5000,
                title = null,
                closeButton = true,
            } = options;

            const id = ++toastId;
            const toast = document.createElement('div');
            toast.id = `toast-${id}`;
            toast.className = `toast toast-${type} rounded-xl px-4 py-3 flex items-start gap-3`;

            const iconHtml = icons[type] || icons.info;

            let html = `
                ${iconHtml}
                <div class="flex-1 min-w-0">
                    ${title ? `<p class="font-semibold text-sm">${title}</p>` : ''}
                    <p class="text-sm ${title ? 'mt-0.5' : ''}">${message}</p>
                </div>
            `;

            if (closeButton) {
                html += `
                    <button type="button" class="flex-shrink-0 p-1 rounded-lg hover:bg-white/20 transition-colors" onclick="Toast.hide('${id}')">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                `;
            }

            toast.innerHTML = html;
            container.appendChild(toast);

            // Auto hide
            if (duration > 0) {
                setTimeout(() => {
                    hide(id);
                }, duration);
            }

            return id;
        }

        function hide(id) {
            const toast = document.getElementById(`toast-${id}`);
            if (toast) {
                toast.classList.add('toast-removing');
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }
        }

        function hideAll() {
            const toasts = container.querySelectorAll('.toast');
            toasts.forEach(toast => {
                toast.classList.add('toast-removing');
            });
            setTimeout(() => {
                container.innerHTML = '';
            }, 300);
        }

        // Convenience methods
        return {
            show,
            hide,
            hideAll,
            success: (message, options) => show(message, 'success', options),
            error: (message, options) => show(message, 'error', options),
            warning: (message, options) => show(message, 'warning', options),
            info: (message, options) => show(message, 'info', options),
        };
    })();

    // Auto-show flash messages
    document.addEventListener('DOMContentLoaded', function() {
        <?php if (session()->getFlashdata('success')): ?>
            Toast.success('<?= htmlspecialchars(session()->getFlashdata('success')) ?>');
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            Toast.error('<?= htmlspecialchars(session()->getFlashdata('error')) ?>');
        <?php endif; ?>

        <?php if (session()->getFlashdata('warning')): ?>
            Toast.warning('<?= htmlspecialchars(session()->getFlashdata('warning')) ?>');
        <?php endif; ?>

        <?php if (session()->getFlashdata('info')): ?>
            Toast.info('<?= htmlspecialchars(session()->getFlashdata('info')) ?>');
        <?php endif; ?>
    });
    </script>
</body>
</html>

<?= $this->renderSection('bottom') ?>
