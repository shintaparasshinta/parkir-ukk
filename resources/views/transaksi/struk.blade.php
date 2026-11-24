{{--
  HALAMAN STRUK PARKIR (MINIMALIS + BARCODE)
  - Durasi parkir dihitung PER HARI
  - Barcode Code128 untuk verifikasi
--}}
<!DOCTYPE html> {{-- Standalone HTML (tanpa layout) --}}
<html lang="id"> {{-- Bahasa Indonesia --}}

<head>
    <meta charset="UTF-8"> {{-- Encoding --}}
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> {{-- Responsif --}}
    <title>Struk Parkir - {{ $transaksi->kode_transaksi }}</title> {{-- Judul tab dengan kode transaksi --}}
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; } /* Reset */

        body {
            font-family: 'Courier New', monospace; /* Font monospace ala struk */
            background: #f5f5f5; /* Latar abu */
            padding: 24px; /* Padding body */
            display: flex; /* Flexbox */
            flex-direction: column; /* Kolom */
            align-items: center; /* Center horizontal */
            justify-content: flex-start; /* Atas */
            min-height: 100vh; /* Full height */
            color: #000; /* Teks hitam */
        }

        .struk {
            background: #fff; /* Latar putih */
            width: 360px; /* Lebar struk */
            border: 1px solid #d1d5db; /* Border abu */
            padding: 28px 24px; /* Padding */
        }

        .header {
            text-align: center; /* Rata tengah */
            padding-bottom: 14px; /* Padding bawah */
            border-bottom: 2px solid #000; /* Border bawah */
            margin-bottom: 16px; /* Jarak bawah */
        }

        .header h2 {
            font-size: 18px; /* Font besar */
            font-weight: bold; /* Tebal */
            letter-spacing: 0.5px; /* Spasi huruf */
            margin-bottom: 4px; /* Jarak bawah */
        }

        .header .sub {
            font-size: 12px; /* Font kecil */
            letter-spacing: 1px; /* Spasi huruf */
            color: #333; /* Abu gelap */
            margin-bottom: 6px; /* Jarak bawah */
        }

        .header .address {
            font-size: 11px; /* Font sangat kecil */
            color: #333;
            line-height: 1.5; /* Line height */
        }

        .body { padding: 8px 0; } /* Padding body struk */

        .row {
            display: flex; /* Flexbox */
            justify-content: space-between; /* Kiri-kanan */
            align-items: flex-start; /* Atas */
            font-size: 13px; /* Font */
            padding: 6px 0; /* Padding vertikal */
            line-height: 1.5;
        }

        .row .label { color: #000; font-weight: normal; } /* Label */

        .row .value {
            color: #000;
            font-weight: bold; /* Nilai tebal */
            text-align: right; /* Rata kanan */
            max-width: 60%; /* Lebar maksimum */
            word-break: break-word; /* Break kata panjang */
        }

        .divider {
            border-top: 1px dashed #000; /* Garis putus-putus */
            margin: 12px 0; /* Jarak vertikal */
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 14px;
            font-weight: bold; /* Tebal */
            padding: 10px 0;
            border-top: 2px solid #000; /* Border atas */
            border-bottom: 2px solid #000; /* Border bawah */
            margin-top: 12px;
        }

        .total-row .total-value { font-size: 16px; } /* Nilai total lebih besar */

        .badge {
            display: inline-block; /* Inline block */
            padding: 3px 10px; /* Padding */
            border: 1px solid #000; /* Border */
            font-size: 11px; /* Font kecil */
            font-weight: bold;
            letter-spacing: 0.5px;
        }

        /* ============ BARCODE SECTION ============ */
        .barcode-section {
            text-align: center; /* Rata tengah */
            margin-top: 16px;
            padding: 12px;
            border: 1px dashed #000; /* Border putus-putus */
        }

        .barcode-section img {
            width: 100%;
            max-width: 260px; /* Lebar maksimum */
            height: auto;
            margin: 0 auto 8px; /* Center + jarak bawah */
            display: block;
        }

        .barcode-section .barcode-text {
            font-size: 10px; /* Font kecil */
            color: #333;
            line-height: 1.4;
        }

        .footer {
            text-align: center;
            padding-top: 16px;
            margin-top: 16px;
            border-top: 2px solid #000; /* Border atas */
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
            background: #172554; /* Navy */
            color: #fff;
            border: none;
            cursor: pointer;
            font-size: 13px;
            font-family: 'Courier New', monospace;
            text-decoration: none;
            margin: 4px;
            transition: 0.2s;
        }

        .btn:hover { background: #1e3a8a; } /* Hover biru lebih terang */

        .btn-gray { background: #6b7280; } /* Abu-abu */
        .btn-gray:hover { background: #4b5563; } /* Hover abu lebih gelap */

        @media print {
            body {
                background: #fff; /* Latar putih saat print */
                padding: 0;
                margin: 0;
                display: block; /* Reset flex */
            }

            .struk {
                width: 100%;
                max-width: 100%;
                border: none; /* Hilangkan border */
                padding: 15mm; /* Padding untuk print */
            }

            .btn-container { display: none; } /* Sembunyikan tombol */

            .header h2 { font-size: 20px; } /* Font lebih besar saat print */
            .header .sub { font-size: 13px; }
            .row { font-size: 14px; }
            .total-row { font-size: 15px; }
            .total-row .total-value { font-size: 18px; }
            .footer { font-size: 13px; }
            .barcode-section img { max-width: 300px; } /* Barcode lebih besar */

            @page {
                size: A4; /* Ukuran kertas A4 */
                margin: 0;
            }
        }
    </style>
</head>

<body>
    <div class="struk"> {{-- Container struk --}}
        <!-- HEADER -->
        <div class="header"> {{-- Header struk --}}
            <h2>PARKIR SMKN 8 JEMBER</h2> {{-- Nama --}}
            <div class="sub">SISTEM MANAJEMEN PARKIR</div> {{-- Subjudul --}}
            <div class="address"> {{-- Alamat --}}
                Jl. Contoh No. 123, Jember<br>
                Telp: 0331-123456
            </div>
        </div>

        <!-- BODY -->
        <div class="body"> {{-- Body struk --}}
            <div class="row"> {{-- Baris kode --}}
                <span class="label">Kode Transaksi</span>
                <span class="value">{{ $transaksi->kode_transaksi }}</span>
            </div>
            <div class="row"> {{-- Baris plat --}}
                <span class="label">Plat Nomor</span>
                <span class="value">{{ $transaksi->kendaraan->plat_nomor ?? '-' }}</span>
            </div>
            <div class="row"> {{-- Baris pemilik --}}
                <span class="label">Pemilik</span>
                <span class="value">{{ $transaksi->kendaraan->pemilik ?? '-' }}</span>
            </div>
            <div class="row"> {{-- Baris jenis --}}
                <span class="label">Jenis</span>
                <span class="value">{{ $transaksi->kendaraan->jenis_kendaraan ?? '-' }}</span>
            </div>
            <div class="row"> {{-- Baris area --}}
                <span class="label">Area Parkir</span>
                <span class="value">{{ $transaksi->areaParkir->nama_area ?? '-' }}</span>
            </div>

            <div class="divider"></div> {{-- Garis pemisah --}}

            <div class="row"> {{-- Baris waktu masuk --}}
                <span class="label">Waktu Masuk</span>
                <span class="value">{{ $transaksi->waktu_masuk ? $transaksi->waktu_masuk->format('d/m/Y H:i') : '-' }}</span>
            </div>

            @if ($transaksi->status == 'keluar') {{-- Jika sudah keluar --}}
                <div class="row"> {{-- Baris waktu keluar --}}
                    <span class="label">Waktu Keluar</span>
                    <span class="value">{{ $transaksi->waktu_keluar ? $transaksi->waktu_keluar->format('d/m/Y H:i') : '-' }}</span>
                </div>

                @php
                    $masuk = \Carbon\Carbon::parse($transaksi->waktu_masuk)->startOfDay(); // Normalisasi waktu masuk
                    $keluar = \Carbon\Carbon::parse($transaksi->waktu_keluar)->startOfDay(); // Normalisasi waktu keluar
                    $durasiHari = $masuk->diffInDays($keluar) + 1; // Hitung durasi hari
                    if ($durasiHari < 1) { // Safety check
                        $durasiHari = 1;
                    }
                @endphp

                <div class="row"> {{-- Baris durasi --}}
                    <span class="label">Durasi Parkir</span>
                    <span class="value">{{ $durasiHari }} Hari</span> {{-- Nilai durasi --}}
                </div>

                <div class="total-row"> {{-- Baris total --}}
                    <span>TOTAL BAYAR</span>
                    <span class="total-value">Rp {{ number_format($transaksi->tarif, 0, ',', '.') }}</span> {{-- Total terformat --}}
                </div>

                <div class="row" style="margin-top: 10px;"> {{-- Baris status --}}
                    <span class="label">Status</span>
                    <span class="badge">SELESAI</span> {{-- Badge selesai --}}
                </div>
            @else {{-- Jika masih masuk --}}
                <div class="row" style="margin-top: 6px;"> {{-- Baris status --}}
                    <span class="label">Status</span>
                    <span class="badge">MASUK</span> {{-- Badge masuk --}}
                </div>
            @endif
        </div>

        <!-- BARCODE Code128 (ganti dari QR Code) -->
        <div class="barcode-section"> {{-- Section barcode --}}
            <img src="https://barcode.tec-it.com/barcode.ashx?data={{ urlencode($transaksi->kode_transaksi) }}&code=Code128&dpi=96&translate-esc=on"
                 alt="Barcode {{ $transaksi->kode_transaksi }}"> {{-- Barcode dari API --}}
            <div class="barcode-text"> {{-- Teks barcode --}}
                Scan barcode ini untuk verifikasi<br>
                transaksi & status parkir
            </div>
        </div>

        <!-- FOOTER -->
        <div class="footer"> {{-- Footer struk --}}
            <div class="thanks">Terima kasih atas kunjungan Anda!</div> {{-- Ucapan --}}
            <div>Struk ini dicetak otomatis oleh sistem.</div> {{-- Info --}}
            <div>Simpan struk ini sebagai bukti.</div> {{-- Info --}}
            <div style="margin-top: 6px; font-size: 11px; color: #555;">
                {{ now()->format('d/m/Y H:i') }} &bull; Parkir SMKN 8 Jember {{-- Timestamp --}}
            </div>
        </div>
    </div>

    <!-- Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"> {{-- Font ikon --}}

    <!-- Tombol aksi -->
    <div class="btn-container"> {{-- Container tombol --}}
        <button class="btn" onclick="window.print()" style="display:inline-flex; align-items:center; gap:6px;"> {{-- Tombol cetak --}}
            <span class="material-symbols-outlined" style="font-size:18px;">print</span> {{-- Ikon print --}}
            Cetak Struk {{-- Label --}}
        </button>
        <a href="{{ route('transaksi.index') }}" class="btn btn-gray" style="display:inline-flex; align-items:center; gap:6px;"> {{-- Tombol kembali --}}
            <span class="material-symbols-outlined" style="font-size:18px;">arrow_back</span> {{-- Ikon panah --}}
            Kembali {{-- Label --}}
        </a>
    </div>
</body>

</html>