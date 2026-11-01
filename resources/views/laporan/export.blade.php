{{--
  HALAMAN EXPORT LAPORAN (Print View)
  - Standalone, tanpa sidebar. Cocok dicetak / save PDF.
  - Tombol Cetak & Kembali disembunyikan saat mode print.
  - Variabel dari controller: $transaksi, $startDate, $endDate
--}}
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8"> {{-- Encoding karakter --}}
    <title>Export Laporan Parkir</title> {{-- Judul tab --}}
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <style>
        /* Style global: font kecil, padding, background putih */
        body {
            font-family: 'Plus Jakarta Sans', Arial, sans-serif;
            font-size: 12px;
            padding: 20px;
            background: #fff;
        }

        /* Header laporan: judul + periode + tanggal cetak */
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #172554;
            padding-bottom: 20px;
        }

        .header h1 {
            color: #172554;
            font-size: 24px;
            margin: 0;
        }

        /* Judul utama */
        .header p {
            color: #666;
            margin: 5px 0;
        }

        /* Teks di bawah judul */

        /* Tabel: full width, border rapat */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th {
            background-color: #172554;
            color: white;
            padding: 10px 12px;
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Header tabel biru */
        td {
            padding: 8px 12px;
            border-bottom: 1px solid #e5e7eb;
        }

        /* Sel tabel */
        tr:hover {
            background-color: #f8f9ff;
        }

        /* Hover baris */

        /* Helper text alignment */
        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        /* Warna teks status (kalau dipakai) */
        .status-masuk {
            color: #2563eb;
            font-weight: bold;
        }

        /* Biru */
        .status-keluar {
            color: #16a34a;
            font-weight: bold;
        }

        /* Hijau */

        /* Box ringkasan di bawah tabel */
        .summary {
            margin-top: 30px;
            padding: 15px 20px;
            background: #f8f9ff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
        }

        .summary p {
            margin: 5px 0;
            font-size: 13px;
        }

        /* Footer */
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            color: #999;
            font-size: 10px;
        }

        /* Badge status */
        .badge {
            display: inline-block;
            padding: 2px 10px;
            border-radius: 9999px;
            font-size: 10px;
            font-weight: 600;
        }

        .badge-masuk {
            background: #dbeafe;
            color: #1e40af;
        }

        /* Biru muda */
        .badge-keluar {
            background: #dcfce7;
            color: #166534;
        }

        /* Hijau muda */

        /* Saat mode print: padding 0, sembunyikan tombol (.no-print) */
        @media print {
            body {
                padding: 0;
            }

            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>

    <!-- HEADER: judul laporan + periode + tanggal cetak -->
    <div class="header">
        <h1>LAPORAN PARKIR</h1>
        <p><strong>SMKN 8 JEMBER</strong></p>
        <p>
            Periode:
            {{-- Kalau ada filter tanggal, tampilkan periode. Kalau tidak, "Semua Periode" --}}
            @if ($startDate && $endDate)
                {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} -
                {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}
            @else
                Semua Periode
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
            @forelse($transaksi as $index => $item)
                {{-- Loop transaksi --}}
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
                        <p style="font-size:16px;">Tidak ada data transaksi</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- BOX RINGKASAN: rekap angka penting -->
    <div class="summary">
        <p><strong>RINGKASAN:</strong></p>
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
        Dicetak dari Sistem Parkir SMKN 8 Jember &bull; {{ now()->format('d/m/Y H:i') }}
    </div>

    <!-- Tombol aksi -->
    <div style="text-align:center; margin-top:20px;" class="no-print">
        <button onclick="window.print()"
            style="padding:10px 24px; background:#172554; color:white; border:none; border-radius:8px; cursor:pointer; font-weight:bold; display:inline-flex; align-items:center; gap:6px; font-family:'Plus Jakarta Sans',sans-serif;">
            <span class="material-symbols-outlined" style="font-size:18px;">print</span>
            Cetak / Print
        </button>
        <a href="{{ route('laporan.index') }}"
            style="padding:10px 24px; background:#e5e7eb; color:#333; text-decoration:none; border:none; border-radius:8px; cursor:pointer; font-weight:bold; margin-left:10px; display:inline-flex; align-items:center; gap:6px; font-family:'Plus Jakarta Sans',sans-serif;">
            <span class="material-symbols-outlined" style="font-size:18px;">arrow_back</span>
            Kembali ke Laporan
        </a>
    </div>

</body>

</html>
