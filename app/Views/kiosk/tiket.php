<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiket Antrian - <?= esc($antrian['nomor']) ?></title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

        * {
            font-family: 'Inter', system-ui, sans-serif;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .ticket {
            background: white;
            border-radius: 20px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            max-width: 400px;
            width: 100%;
            overflow: hidden;
        }

        .ticket-header {
            background: linear-gradient(135deg, #0ea5e9 0%, #0369a1 100%);
            padding: 30px 20px;
            text-align: center;
            color: white;
        }

        .ticket-number {
            font-size: 72px;
            font-weight: 800;
            line-height: 1;
            letter-spacing: -2px;
        }

        .ticket-body {
            padding: 30px 20px;
        }

        .ticket-divider {
            border-style: dashed;
            border-width: 2px;
            border-color: #e2e8f0;
            margin: 20px 0;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            color: #64748b;
            font-size: 14px;
        }

        .info-value {
            color: #1e293b;
            font-weight: 600;
            font-size: 14px;
        }

        .btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            padding: 16px;
            border: 2px solid transparent; /* Ensure consistent size with secondary */
            border-radius: 16px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, #0ea5e9 0%, #0369a1 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(14, 165, 233, 0.3);
        }

        .btn-secondary {
            background: white;
            border-color: #e2e8f0;
            color: #64748b;
            margin-top: 12px;
        }

        .btn-secondary:hover {
            background: #e2e8f0;
        }

        /* Print styles */
        @media print {
            @page {
                margin: 0;
                size: auto;
            }
            body { 
                background: white; 
                padding: 10px; 
                min-height: auto; 
                display: block; 
            }
            .ticket { 
                box-shadow: none; 
                border: none; 
                max-width: 100%; 
                width: 100%;
                border-radius: 0; 
                margin: 0 auto;
            }
            .ticket-header { 
                color: black !important; 
                background: none !important; 
                border-bottom: 1px solid #000; 
                padding: 10px; 
            }
            .ticket-divider {
                border-top: 1px dashed #000;
            }
            .ticket-divider::before, .ticket-divider::after { display: none; }
            .no-print { display: none !important; }
            .btn { display: none; }
            
            /* Hide URL printing in some browsers */
            a[href]:after { content: none !important; }
        }
    </style>
</head>
<body>
    <div class="ticket">
        <!-- Header -->
        <div class="ticket-header">
            <div style="font-size: 14px; opacity: 0.9; margin-bottom: 8px;">Tiket Antrian</div>
            <div class="ticket-number"><?= esc($antrian['nomor']) ?></div>
            <div style="font-size: 18px; font-weight: 600; margin-top: 8px;"><?= esc($antrian['poli_nama']) ?></div>
        </div>

        <!-- Body -->
        <div class="ticket-body">
            <!-- Info -->
            <div class="info-row">
                <span class="info-label">Poli</span>
                <span class="info-value"><?= esc($antrian['poli_nama']) ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Nomor</span>
                <span class="info-value"><?= esc($antrian['nomor']) ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Tanggal</span>
                <span class="info-value"><?= date('d/m/Y', strtotime($antrian['waktu_ambil'])) ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Jam</span>
                <span class="info-value"><?= date('H:i', strtotime($antrian['waktu_ambil'])) ?></span>
            </div>

            <div class="ticket-divider"></div>

            <!-- Waiting Count -->
            <div style="text-align: center; padding: 16px; background: #f0f9ff; border-radius: 12px;">
                <div style="color: #64748b; font-size: 12px; margin-bottom: 4px;">Estimasi Antrian di Depan</div>
                <div style="font-size: 32px; font-weight: 700; color: #0ea5e9;">
                    ± <?= $waiting_count ?>
                </div>
            </div>

            <div class="ticket-divider"></div>

            <!-- Instructions -->
            <div style="font-size: 12px; color: #64748b; line-height: 1.6;">
                <div style="margin-bottom: 8px;">⚠️ Harap diperhatikan:</div>
                <ul style="margin: 0; padding-left: 20px;">
                    <li>Simpan tiket ini sebagai bukti antrian</li>
                    <li>Perhatikan panggilan di layar display</li>
                    <li>Segera masuk ketika nomor dipanggil</li>
                </ul>
            </div>
        </div>

        <!-- Actions (no print) -->
        <div class="no-print" style="padding: 0 20px 20px;">
            <button onclick="window.print()" class="btn btn-primary">
                🖨️ Cetak Tiket
            </button>
            <a href="/kiosk" class="btn btn-secondary">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                </svg>
                Kembali ke Kiosk
            </a>
        </div>
    </div>

    <script>
        // Auto print when page loads
        window.onload = function() {
            setTimeout(() => {
                window.print();
            }, 500);
        };
    </script>
</body>
</html>
