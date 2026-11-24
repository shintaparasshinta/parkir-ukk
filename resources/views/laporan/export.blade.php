{{--
  HALAMAN EXPORT LAPORAN (Print View)
  - Standalone, tanpa sidebar. Cocok dicetak / save PDF.
  - Tombol Cetak & Kembali disembunyikan saat mode print.
  - Variabel dari controller: $transaksi, $startDate, $endDate
--}}
<!DOCTYPE html> {{-- Standalone HTML (tanpa layout) --}}
<html> {{-- Tag html --}}

<head>
    <meta charset="utf-8"> {{-- Encoding karakter --}}
    <title>Export Laporan Parkir</title> {{-- Judul tab --}}
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"> {{-- Font ikon --}}
    <style>
        /* Style global: font kecil, padding, background putih */
        body {
            font-family: 'Plus Jakarta Sans', Arial, sans-serif; /* Font utama */
            font-size: 12px; /* Font kecil agar muat banyak kolom */
            padding: 20px; /* Padding halaman */
            background: #fff; /* Latar putih (untuk print) */
        }

        /* Header laporan: judul + periode + tanggal cetak */
        .header {
            text-align: center; /* Rata tengah */
            margin-bottom: 30px; /* Jarak bawah */
            border-bottom: 2px solid #172554; /* Garis bawah navy */
            padding-bottom: 20px; /* Padding bawah */
        }

        .header h1 {
            color: #172554; /* Warna navy */
            font-size: 24px; /* Font besar */
            margin: 0; /* Hilangkan margin default */
        }

        /* Judul utama */
        .header p {
            color: #666; /* Abu-abu */
            margin: 5px 0; /* Jarak vertikal */
        }

        /* Teks di bawah judul */

        /* Tabel: full width, border rapat */
        table {
            width: 100%; /* Lebar penuh */
            border-collapse: collapse; /* Border rapat */
            margin-top: 20px; /* Jarak atas */
        }

        th {
            background-color: #172554; /* Latar navy */
            color: white; /* Teks putih */
            padding: 10px 12px; /* Padding */
            text-align: left; /* Rata kiri */
            font-size: 11px; /* Font kecil */
            text-transform: uppercase; /* Huruf kapital */
            letter-spacing: 0.5px; /* Spasi antar huruf */
        }

        /* Header tabel biru */
        td {
            padding: 8px 12px; /* Padding sel */
            border-bottom: 1px solid #e5e7eb; /* Border bawah abu */
        }

        /* Sel tabel */
        tr:hover {
            background-color: #f8f9ff; /* Hover baris */
        }

        /* Hover baris */

        /* Helper text alignment */
        .text-right {
            text-align: right; /* Rata kanan */
        }

        .text-center {
            text-align: center; /* Rata tengah */
        }

        /* Warna teks status (kalau dipakai) */
        .status-masuk {
            color: #2563eb; /* Biru */
            font-weight: bold; /* Tebal */
        }

        /* Biru */
        .status-keluar {
            color: #16a34a; /* Hijau */
            font-weight: bold; /* Tebal */
        }

        /* Hijau */

        /* Box ringkasan di bawah tabel */
        .summary {
            margin-top: 30px; /* Jarak atas */
            padding: 15px 20px; /* Padding */
            background: #f8f9ff; /* Latar biru sangat muda */
            border: 1px solid #e5e7eb; /* Border abu */
            border-radius: 8px; /* Sudut melengkung */
        }

        .summary p {
            margin: 5px 0; /* Jarak vertikal */
            font-size: 13px; /* Font */
        }

        /* Footer */
        .footer {
            text-align: center; /* Rata tengah */
            margin-top: 30px; /* Jarak atas */
            padding-top: 20px; /* Padding atas */
            border-top: 1px solid #e5e7eb; /* Border atas */
            color: #999; /* Abu-abu */
            font-size: 10px; /* Font kecil */
        }

        /* Badge status */
        .badge {
            display: inline-block; /* Blok inline */
            padding: 2px 10px; /* Padding */
            border-radius: 9999px; /* Sudut penuh */
            font-size: 10px; /* Font kecil */
            font-weight: 600; /* Semi-bold */
        }

        .badge-masuk {
            background: #dbeafe; /* Biru muda */
            color: #1e40af; /* Biru tua */
        }

        /* Biru muda */
        .badge-keluar {
            background: #dcfce7; /* Hijau muda */
            color: #166534; /* Hijau tua */
        }

        /* Hijau muda */

        /* Saat mode print: padding 0, sembunyikan tombol (.no-print) */
        @media print {
            body {
                padding: 0; /* Hilangkan padding saat print */
            }

            .no-print {
                display: none; /* Sembunyikan tombol saat print */
            }
        }
    </style>
</head>

<body>

    <!-- HEADER: judul laporan + periode + tanggal cetak -->
    <div class="header">
        <h1>LAPORAN PARKIR</h1> {{-- Judul laporan --}}
        <p><strong>SMKN 8 JEMBER</strong></p> {{-- Nama sekolah --}}
        <p>
            Periode:
            {{-- Kalau ada filter tanggal, tampilkan periode. Kalau tidak, "Semua Periode" --}}
            @if ($startDate && $endDate)
                {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{-- Tanggal mulai --}}
                {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }} {{-- Tanggal akhir --}}
            @else
                Semua Periode {{-- Fallback --}}
            @endif
        </p>
        <p>Dicetak: {{ now()->format('d/m/Y H:i') }}</p> {{-- Tanggal & jam cetak --}}
    </div>

    <!-- RINGKASAN SINGKAT: total transaksi & pendapatan -->
    <div style="margin-bottom: 20px;">
        <p><strong>Total Transaksi:</strong> {{ $transaksi->count() }}</p> {{-- Hitung total transaksi --}}
        <p><strong>Total Pendapatan:</strong> Rp
            {{ number_format($transaksi->where('status', 'keluar')->sum('tarif'), 0, ',', '.') }}</p>
        {{-- Sum tarif yang status keluar --}}
    </div>

    <!-- TABEL DETAIL TRANSAKSI -->
    <table>
        <thead>
            <tr>
                <th>No</th> {{-- Nomor urut --}}
                <th>Kode Transaksi</th> {{-- Kode TRX-xxx --}}
                <th>Plat Nomor</th> {{-- Plat kendaraan --}}
                <th>Pemilik</th> {{-- Nama pemilik --}}
                <th>Area</th> {{-- Nama area parkir --}}
                <th>Waktu Masuk</th> {{-- Waktu masuk --}}
                <th>Waktu Keluar</th> {{-- Waktu keluar --}}
                <th style="text-align:right">Tarif</th> {{-- Tarif, align kanan --}}
                <th style="text-align:center">Status</th> {{-- Status, align tengah --}}
            </tr>
        </thead>
        <tbody>
            @forelse($transaksi as $index => $item) {{-- Loop transaksi --}}
                <tr>
                    <td>{{ $index + 1 }}</td> {{-- Nomor urut mulai 1 --}}
                    <td><strong>{{ $item->kode_transaksi }}</strong></td> {{-- Kode transaksi bold --}}
                    <td>{{ $item->kendaraan->plat_nomor ?? '-' }}</td> {{-- Plat atau strip --}}
                    <td>{{ $item->kendaraan->pemilik ?? '-' }}</td> {{-- Pemilik atau strip --}}
                    <td>{{ $item->areaParkir->nama_area ?? '-' }}</td> {{-- Area atau strip --}}
                    <td>{{ $item->waktu_masuk ? $item->waktu_masuk->format('d/m/Y H:i') : '-' }}</td>
                    {{-- Waktu masuk --}}
                    <td>{{ $item->waktu_keluar ? $item->waktu_keluar->format('d/m/Y H:i') : '-' }}</td>
                    {{-- Waktu keluar --}}
                    <td style="text-align:right; font-weight:bold;"> {{-- Tarif align kanan --}}
                        {{ $item->tarif ? 'Rp ' . number_format($item->tarif, 0, ',', '.') : '-' }}
                    </td>
                    <td style="text-align:center;"> {{-- Badge status --}}
                        @if ($item->status == 'masuk')
                            <span class="badge badge-masuk">MASUK</span> {{-- Biru --}}
                        @else
                            <span class="badge badge-keluar">KELUAR</span> {{-- Hijau --}}
                        @endif
                    </td>
                </tr>
            @empty
                {{-- Kalau data kosong: tampilkan pesan --}}
                <tr>
                    <td colspan="9" style="text-align:center; padding:40px; color:#999;">
                        <p style="font-size:16px;">Tidak ada data transaksi</p> {{-- Pesan kosong --}}
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- BOX RINGKASAN: rekap angka penting -->
    <div class="summary">
        <p><strong>RINGKASAN:</strong></p> {{-- Judul ringkasan --}}
        <p>Total Transaksi: <strong>{{ $transaksi->count() }}</strong></p> {{-- Semua transaksi --}}
        <p>Total Masuk: <strong>{{ $transaksi->where('status', 'masuk')->count() }}</strong></p> {{-- Yang status masuk --}}
        <p>Total Keluar: <strong>{{ $transaksi->where('status', 'keluar')->count() }}</strong></p>
        {{-- Yang status keluar --}}
        <p>Total Pendapatan: <strong style="color:#172554;">Rp
                {{ number_format($transaksi->where('status', 'keluar')->sum('tarif'), 0, ',', '.') }}</strong></p>
        {{-- Sum tarif keluar --}}
    </div>

    <!-- FOOTER: keterangan cetak -->
    <div class="footer">
        Dicetak dari Sistem Parkir SMKN 8 Jember &bull; {{ now()->format('d/m/Y H:i') }} {{-- Info cetak --}}
    </div>

    <!-- Tombol aksi -->
    <div style="text-align:center; margin-top:20px;" class="no-print"> {{-- Class no-print agar disembunyikan saat print --}}
        <button onclick="window.print()"
            style="padding:10px 24px; background:#172554; color:white; border:none; border-radius:8px; cursor:pointer; font-weight:bold; display:inline-flex; align-items:center; gap:6px; font-family:'Plus Jakarta Sans',sans-serif;"> {{-- Tombol cetak --}}
            <span class="material-symbols-outlined" style="font-size:18px;">print</span> {{-- Ikon print --}}
            Cetak / Print {{-- Label --}}
        </button>
        <a href="{{ route('laporan.index') }}"
            style="padding:10px 24px; background:#e5e7eb; color:#333; text-decoration:none; border:none; border-radius:8px; cursor:pointer; font-weight:bold; margin-left:10px; display:inline-flex; align-items:center; gap:6px; font-family:'Plus Jakarta Sans',sans-serif;"> {{-- Tombol kembali --}}
            <span class="material-symbols-outlined" style="font-size:18px;">arrow_back</span> {{-- Ikon panah --}}
            Kembali ke Laporan {{-- Label --}}
        </a>
    </div>

</body>

</html>