{{--
  HALAMAN STRUK PARKIR (MINIMALIS + BARCODE)
  - Durasi parkir dihitung PER HARI
  - Barcode Code128 untuk verifikasi
--}}
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Parkir - {{ $transaksi->kode_transaksi }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Courier New', monospace;
            background: #f5f5f5;
            padding: 24px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            min-height: 100vh;
            color: #000;
        }

        .struk {
            background: #fff;
            width: 360px;
            border: 1px solid #d1d5db;
            padding: 28px 24px;
        }

        .header {
            text-align: center;
            padding-bottom: 14px;
            border-bottom: 2px solid #000;
            margin-bottom: 16px;
        }

        .header h2 {
            font-size: 18px;
            font-weight: bold;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .header .sub {
            font-size: 12px;
            letter-spacing: 1px;
            color: #333;
            margin-bottom: 6px;
        }

        .header .address {
            font-size: 11px;
            color: #333;
            line-height: 1.5;
        }

        .body { padding: 8px 0; }

        .row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            font-size: 13px;
            padding: 6px 0;
            line-height: 1.5;
        }

        .row .label { color: #000; font-weight: normal; }

        .row .value {
            color: #000;
            font-weight: bold;
            text-align: right;
            max-width: 60%;
            word-break: break-word;
        }

        .divider {
            border-top: 1px dashed #000;
            margin: 12px 0;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 14px;
            font-weight: bold;
            padding: 10px 0;
            border-top: 2px solid #000;
            border-bottom: 2px solid #000;
            margin-top: 12px;
        }

        .total-row .total-value { font-size: 16px; }

        .badge {
            display: inline-block;
            padding: 3px 10px;
            border: 1px solid #000;
            font-size: 11px;
            font-weight: bold;
            letter-spacing: 0.5px;
        }

        /* ============ BARCODE SECTION ============ */
        .barcode-section {
            text-align: center;
            margin-top: 16px;
            padding: 12px;
            border: 1px dashed #000;
        }

        .barcode-section img {
            width: 100%;
            max-width: 260px;
            height: auto;
            margin: 0 auto 8px;
            display: block;
        }

        .barcode-section .barcode-text {
            font-size: 10px;
            color: #333;
            line-height: 1.4;
        }

        .footer {
            text-align: center;
            padding-top: 16px;
            margin-top: 16px;
            border-top: 2px solid #000;
            font-size: 12px;
            line-height: 1.7;
        }

        .footer .thanks {
            font-weight: bold;
            font-size: 13px;
            margin-bottom: 4px;
        }

        .btn-container {
            text-align: center;
            margin-top: 24px;
        }

        .btn {
            display: inline-block;
            padding: 10px 24px;
            background: #172554;
            color: #fff;
            border: none;
            cursor: pointer;
            font-size: 13px;
            font-family: 'Courier New', monospace;
            text-decoration: none;
            margin: 4px;
            transition: 0.2s;
        }

        .btn:hover { background: #1e3a8a; }

        .btn-gray { background: #6b7280; }
        .btn-gray:hover { background: #4b5563; }

        @media print {
            body {
                background: #fff;
                padding: 0;
                margin: 0;
                display: block;
            }

            .struk {
                width: 100%;
                max-width: 100%;
                border: none;
                padding: 15mm;
            }

            .btn-container { display: none; }

            .header h2 { font-size: 20px; }
            .header .sub { font-size: 13px; }
            .row { font-size: 14px; }
            .total-row { font-size: 15px; }
            .total-row .total-value { font-size: 18px; }
            .footer { font-size: 13px; }
            .barcode-section img { max-width: 300px; }

            @page {
                size: A4;
                margin: 0;
            }
        }
    </style>
</head>

<body>
    <div class="struk">
        <!-- HEADER -->
        <div class="header">
            <h2>PARKIR SMKN 8 JEMBER</h2>
            <div class="sub">SISTEM MANAJEMEN PARKIR</div>
            <div class="address">
                Jl. Contoh No. 123, Jember<br>
                Telp: 0331-123456
            </div>
        </div>

        <!-- BODY -->
        <div class="body">
            <div class="row">
                <span class="label">Kode Transaksi</span>
                <span class="value">{{ $transaksi->kode_transaksi }}</span>
            </div>
            <div class="row">
                <span class="label">Plat Nomor</span>
                <span class="value">{{ $transaksi->kendaraan->plat_nomor ?? '-' }}</span>
            </div>
            <div class="row">
                <span class="label">Pemilik</span>
                <span class="value">{{ $transaksi->kendaraan->pemilik ?? '-' }}</span>
            </div>
            <div class="row">
                <span class="label">Jenis</span>
                <span class="value">{{ $transaksi->kendaraan->jenis_kendaraan ?? '-' }}</span>
            </div>
            <div class="row">
                <span class="label">Area Parkir</span>
                <span class="value">{{ $transaksi->areaParkir->nama_area ?? '-' }}</span>
            </div>

            <div class="divider"></div>

            <div class="row">
                <span class="label">Waktu Masuk</span>
                <span class="value">{{ $transaksi->waktu_masuk ? $transaksi->waktu_masuk->format('d/m/Y H:i') : '-' }}</span>
            </div>

            @if ($transaksi->status == 'keluar')
                <div class="row">
                    <span class="label">Waktu Keluar</span>
                    <span class="value">{{ $transaksi->waktu_keluar ? $transaksi->waktu_keluar->format('d/m/Y H:i') : '-' }}</span>
                </div>

                @php
                    $masuk = \Carbon\Carbon::parse($transaksi->waktu_masuk)->startOfDay();
                    $keluar = \Carbon\Carbon::parse($transaksi->waktu_keluar)->startOfDay();
                    $durasiHari = $masuk->diffInDays($keluar) + 1;
                    if ($durasiHari < 1) {
                        $durasiHari = 1;
                    }
                @endphp

                <div class="row">
                    <span class="label">Durasi Parkir</span>
                    <span class="value">{{ $durasiHari }} Hari</span>
                </div>

                <div class="total-row">
                    <span>TOTAL BAYAR</span>
                    <span class="total-value">Rp {{ number_format($transaksi->tarif, 0, ',', '.') }}</span>
                </div>

                <div class="row" style="margin-top: 10px;">
                    <span class="label">Status</span>
                    <span class="badge">SELESAI</span>
                </div>
            @else
                <div class="row" style="margin-top: 6px;">
                    <span class="label">Status</span>
                    <span class="badge">MASUK</span>
                </div>
            @endif
        </div>

        <!-- BARCODE Code128 (ganti dari QR Code) -->
        <div class="barcode-section">
            <img src="https://barcode.tec-it.com/barcode.ashx?data={{ urlencode($transaksi->kode_transaksi) }}&code=Code128&dpi=96&translate-esc=on"
                 alt="Barcode {{ $transaksi->kode_transaksi }}">
            <div class="barcode-text">
                Scan barcode ini untuk verifikasi<br>
                transaksi & status parkir
            </div>
        </div>

        <!-- FOOTER -->
        <div class="footer">
            <div class="thanks">Terima kasih atas kunjungan Anda!</div>
            <div>Struk ini dicetak otomatis oleh sistem.</div>
            <div>Simpan struk ini sebagai bukti.</div>
            <div style="margin-top: 6px; font-size: 11px; color: #555;">
                {{ now()->format('d/m/Y H:i') }} &bull; Parkir SMKN 8 Jember
            </div>
        </div>
    </div>

    <!-- Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

    <!-- Tombol aksi -->
    <div class="btn-container">
        <button class="btn" onclick="window.print()" style="display:inline-flex; align-items:center; gap:6px;">
            <span class="material-symbols-outlined" style="font-size:18px;">print</span>
            Cetak Struk
        </button>
        <a href="{{ route('transaksi.index') }}" class="btn btn-gray" style="display:inline-flex; align-items:center; gap:6px;">
            <span class="material-symbols-outlined" style="font-size:18px;">arrow_back</span>
            Kembali
        </a>
    </div>
</body>

</html>