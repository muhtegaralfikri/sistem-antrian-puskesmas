<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Antrian Puskesmas</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col items-center justify-center p-4">

    <div class="max-w-4xl w-full text-center mb-12">
        <div class="inline-flex items-center justify-center w-20 h-20 bg-primary-600 rounded-2xl mb-6 shadow-lg shadow-primary-500/30">
            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
            </svg>
        </div>
        <h1 class="text-4xl font-bold text-gray-900 mb-4">Sistem Antrian Puskesmas</h1>
        <p class="text-lg text-gray-600">Selamat datang. Silakan pilih mode akses aplikasi.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-5xl w-full">
        <!-- Kiosk Mode -->
        <a href="/kiosk" class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group text-center">
            <div class="w-20 h-20 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                </svg>
            </div>
            <h2 class="text-xl font-bold text-gray-800 mb-2">Ambil Antrian</h2>
            <p class="text-sm text-gray-500 mb-6">Mode Kiosk untuk pasien mengambil nomor antrian baru</p>
            <span class="inline-block px-4 py-2 bg-gray-50 text-gray-600 rounded-lg text-sm font-medium group-hover:bg-blue-50 group-hover:text-blue-700 transition">Buka Kiosk &rarr;</span>
        </a>

        <!-- Display Mode -->
        <a href="/display" class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group text-center">
            <div class="w-20 h-20 bg-green-50 text-green-600 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:bg-green-600 group-hover:text-white transition-colors duration-300">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
            <h2 class="text-xl font-bold text-gray-800 mb-2">Layar Antrian</h2>
            <p class="text-sm text-gray-500 mb-6">Mode Display TV untuk menampilkan status panggilan</p>
            <span class="inline-block px-4 py-2 bg-gray-50 text-gray-600 rounded-lg text-sm font-medium group-hover:bg-green-50 group-hover:text-green-700 transition">Buka Display &rarr;</span>
        </a>

        <!-- Admin/Staff Mode -->
        <a href="/auth/login" class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group text-center">
            <div class="w-20 h-20 bg-purple-50 text-purple-600 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:bg-purple-600 group-hover:text-white transition-colors duration-300">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
            <h2 class="text-xl font-bold text-gray-800 mb-2">Login Petugas</h2>
            <p class="text-sm text-gray-500 mb-6">Masuk ke panel admin atau dashboard petugas</p>
            <span class="inline-block px-4 py-2 bg-gray-50 text-gray-600 rounded-lg text-sm font-medium group-hover:bg-purple-50 group-hover:text-purple-700 transition">Login System &rarr;</span>
        </a>
    </div>

    <footer class="mt-12 text-center text-sm text-gray-400">
        <p>&copy; <?= date('Y') ?> Sistem Antrian Puskesmas. All rights reserved.</p>
    </footer>

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
                        }
                    }
                }
            }
        }
    </script>
</body>
</html>
