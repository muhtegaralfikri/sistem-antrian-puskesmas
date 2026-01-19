<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Antrian Puskesmas</title>
    <link rel="stylesheet" href="/css/app.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.05);
        }
        .hero-pattern {
            background-color: #f0fdfa;
            background-image: radial-gradient(#ccfbf1 1px, transparent 1px);
            background-size: 40px 40px;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col relative overflow-x-hidden hero-pattern">

    <!-- Decorative Background -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden z-0">
        <div class="absolute -top-40 -left-40 w-96 h-96 bg-medical-200 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob"></div>
        <div class="absolute top-0 right-0 w-96 h-96 bg-primary-200 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob animation-delay-2000"></div>
        <div class="absolute -bottom-40 left-1/3 w-96 h-96 bg-pink-100 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob animation-delay-4000"></div>
    </div>

    <!-- Navbar -->
    <nav class="w-full z-10 px-6 py-4 flex justify-between items-center max-w-7xl mx-auto">
        <div class="flex items-center gap-3">
            <img src="/images/logo.png" alt="Logo Puskesmas" class="h-12 w-auto drop-shadow-md hover:scale-105 transition-transform duration-300">
            <div>
                <h1 class="text-xl font-bold text-gray-900 leading-none">PUSKESMAS<span class="text-medical-600">SEHAT</span></h1>
                <p class="text-[10px] uppercase tracking-widest text-gray-500 font-semibold">Sistem Antrian Terpadu</p>
            </div>
        </div>
        <div class="text-right hidden sm:block">
            <?php
            $hari = [
                'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 
                'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
            ];
            $bulan = [
                'January' => 'Januari', 'February' => 'Februari', 'March' => 'Maret', 'April' => 'April', 
                'May' => 'Mei', 'June' => 'Juni', 'July' => 'Juli', 'August' => 'Agustus', 
                'September' => 'September', 'October' => 'Oktober', 'November' => 'November', 'December' => 'Desember'
            ];
            ?>
            <p class="text-sm font-medium text-gray-500"><?= $hari[date('l')] . ', ' . date('d') . ' ' . $bulan[date('F')] . ' ' . date('Y') ?></p>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-1 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 flex flex-col justify-center items-center relative z-10">
        
        <!-- Hero Section -->
        <div class="text-center max-w-3xl mx-auto mb-16 animate-float">
            <span class="inline-block py-1 px-3 rounded-full bg-medical-100 text-medical-700 text-xs font-bold tracking-wider uppercase mb-4 border border-medical-200">Selamat Datang</span>
            <h1 class="text-4xl md:text-6xl font-extrabold text-gray-900 tracking-tight mb-6 leading-tight">
                Pelayanan Kesehatan <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-medical-600 to-primary-600">Lebih Cepat & Nyaman</span>
            </h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto leading-relaxed">
                Silakan pilih salah satu menu di bawah ini untuk mengakses layanan antrian Puskesmas. Kami siap melayani Anda sepenuh hati.
            </p>
        </div>

        <!-- Menu Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 w-full max-w-6xl">
            
            <!-- Card 1: Kiosk -->
            <a href="/kiosk" class="group relative">
                <div class="absolute inset-0 bg-gradient-to-r from-blue-400 to-indigo-500 rounded-3xl blur opacity-20 group-hover:opacity-40 transition duration-500"></div>
                <div class="glass-card h-full p-8 rounded-3xl relative overflow-hidden transition-all duration-300 transform group-hover:-translate-y-2 group-hover:bg-white/80">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-blue-50 to-transparent rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
                    
                    <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center mb-6 shadow-sm group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                    </div>
                    
                    <h3 class="text-2xl font-bold text-gray-900 mb-2 group-hover:text-blue-600 transition-colors">Ambil Antrian</h3>
                    <p class="text-gray-500 mb-6 text-sm leading-relaxed">
                        Pasien Baru atau Lama? Ambil nomor antrian Anda di sini secara mandiri melalui Kiosk.
                    </p>
                    
                    <div class="flex items-center text-blue-600 font-bold text-sm tracking-wide group-hover:translate-x-2 transition-transform">
                        BUKA KIOSK <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </div>
                </div>
            </a>

            <!-- Card 2: Display -->
            <a href="/display" class="group relative">
                <div class="absolute inset-0 bg-gradient-to-r from-medical-400 to-emerald-500 rounded-3xl blur opacity-20 group-hover:opacity-40 transition duration-500"></div>
                <div class="glass-card h-full p-8 rounded-3xl relative overflow-hidden transition-all duration-300 transform group-hover:-translate-y-2 group-hover:bg-white/80">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-medical-50 to-transparent rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
                    
                    <div class="w-16 h-16 bg-medical-100 text-medical-600 rounded-2xl flex items-center justify-center mb-6 shadow-sm group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    
                    <h3 class="text-2xl font-bold text-gray-900 mb-2 group-hover:text-medical-600 transition-colors">Layar Informasi</h3>
                    <p class="text-gray-500 mb-6 text-sm leading-relaxed">
                        Tampilan layar utama untuk di ruang tunggu. Cek status antrian yang sedang dipanggil.
                    </p>
                    
                    <div class="flex items-center text-medical-600 font-bold text-sm tracking-wide group-hover:translate-x-2 transition-transform">
                        BUKA DISPLAY <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </div>
                </div>
            </a>

            <!-- Card 3: Login -->
            <a href="/auth/login" class="group relative">
                <div class="absolute inset-0 bg-gradient-to-r from-indigo-400 to-purple-500 rounded-3xl blur opacity-20 group-hover:opacity-40 transition duration-500"></div>
                <div class="glass-card h-full p-8 rounded-3xl relative overflow-hidden transition-all duration-300 transform group-hover:-translate-y-2 group-hover:bg-white/80">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-indigo-50 to-transparent rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
                    
                    <div class="w-16 h-16 bg-indigo-100 text-indigo-600 rounded-2xl flex items-center justify-center mb-6 shadow-sm group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </div>
                    
                    <h3 class="text-2xl font-bold text-gray-900 mb-2 group-hover:text-indigo-600 transition-colors">Akses Petugas</h3>
                    <p class="text-gray-500 mb-6 text-sm leading-relaxed">
                        Khusus untuk dokter, admin, dan petugas loket. Masuk ke dashboard manajemen.
                    </p>
                    
                    <div class="flex items-center text-indigo-600 font-bold text-sm tracking-wide group-hover:translate-x-2 transition-transform">
                        LOGIN SYSTEM <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </div>
                </div>
            </a>

        </div>

        <div class="mt-16 text-center text-gray-400 text-sm">
            <p>&copy; <?= date('Y') ?> Sistem Antrian Puskesmas. All rights reserved.</p>
        </div>

    </main>

</body>
</html>
