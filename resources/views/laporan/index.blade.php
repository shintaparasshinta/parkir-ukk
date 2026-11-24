{{--
  HALAMAN LAPORAN
  - Filter: tanggal mulai/akhir, area, jenis kendaraan
  - Summary Cards: Total Transaksi, Masuk, Keluar, Pendapatan
  - Chart: Aktivitas Parkir (bar) & Pendapatan (line) via Chart.js
  - Tabel Detail Transaksi + tombol Export (buka tab baru)
  - Search tabel: client-side (filter by plat nomor)
  - Pagination: auto-scroll ke tabel + filter tersimpan (appends)
  - Variabel dari controller: $transaksi, $areas, $startDate, $endDate, $areaId,
    $jenisKendaraan, $totalTransaksi, $totalMasuk, $totalKeluar, $totalPendapatan,
    $chartLabelsFormatted, $chartMasuk, $chartKeluar, $chartPendapatan
--}}
@extends('layouts.dashboard') {{-- Menggunakan layout dashboard --}}

@section('title', 'Laporan Parkir - Parkir SMKN 8 Jember') {{-- Judul halaman --}}

@section('content') {{-- Bagian konten --}}
    <!-- HEADER: judul + info periode filter -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-navy">Laporan Parkir</h1> {{-- Judul --}}
        <p class="text-sm text-slate-500 mt-1">Rekap aktivitas dan transaksi parkir sekolah</p> {{-- Subjudul --}}
        @if ($startDate && $endDate) {{-- Jika filter tanggal diisi --}}
            <p class="text-xs text-slate-400 mt-1">
                Periode: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} -
                {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }} {{-- Info periode --}}
            </p>
        @else
            <p class="text-xs text-slate-400 mt-1">Menampilkan semua transaksi</p> {{-- Info default --}}
        @endif
    </div>

    <!-- FILTERS: form filter laporan -->
    <div class="bg-white rounded-xl border border-gray p-6 shadow-sm mb-6"> {{-- Card filter --}}
        <form method="GET" action="{{ route('laporan.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end"> {{-- Form GET --}}
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Tanggal Mulai</label> {{-- Label --}}
                <input type="date" name="start_date" value="{{ $startDate }}"
                    class="w-full bg-white border border-gray rounded-lg px-3 py-2 text-sm focus:border-navy focus:ring-1 focus:ring-navy outline-none transition-all"> {{-- Input tanggal --}}
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Tanggal
                    Akhir</label> {{-- Label --}}
                <input type="date" name="end_date" value="{{ $endDate }}"
                    class="w-full bg-white border border-gray rounded-lg px-3 py-2 text-sm focus:border-navy focus:ring-1 focus:ring-navy outline-none transition-all"> {{-- Input tanggal --}}
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Area Parkir</label> {{-- Label --}}
                <select name="area_id"
                    class="w-full bg-white border border-gray rounded-lg px-3 py-2 text-sm focus:border-navy focus:ring-1 focus:ring-navy outline-none transition-all"> {{-- Select area --}}
                    <option value="">Semua Area</option> {{-- Opsi default --}}
                    @foreach ($areas as $area) {{-- Loop area --}}
                        <option value="{{ $area->id }}" {{ $areaId == $area->id ? 'selected' : '' }}> {{-- Selected kalau cocok --}}
                            {{ $area->nama_area }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Jenis
                    Kendaraan</label> {{-- Label --}}
                <select name="jenis_kendaraan"
                    class="w-full bg-white border border-gray rounded-lg px-3 py-2 text-sm focus:border-navy focus:ring-1 focus:ring-navy outline-none transition-all"> {{-- Select jenis --}}
                    <option value="">Semua</option> {{-- Opsi default --}}
                    <option value="Motor" {{ $jenisKendaraan == 'Motor' ? 'selected' : '' }}>Motor</option> {{-- Opsi motor --}}
                    <option value="Mobil" {{ $jenisKendaraan == 'Mobil' ? 'selected' : '' }}>Mobil</option> {{-- Opsi mobil --}}
                </select>
            </div>
            <div class="flex gap-2"> {{-- Container tombol --}}
                <button type="submit"
                    class="bg-yellow text-navy font-bold px-6 py-2 rounded-lg hover:bg-yellow/90 transition-all shadow-sm flex-1">Filter</button> {{-- Tombol filter --}}
                <a href="{{ route('laporan.index') }}"
                    class="bg-gray-100 text-slate-600 font-bold px-4 py-2 rounded-lg hover:bg-gray-200 transition-all">Reset</a> {{-- Tombol reset --}}
            </div>
        </form>
    </div>

    <!-- SUMMARY CARDS: 4 kartu rekap -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6"> {{-- Grid 1/2/4 kolom --}}
        <div class="bg-white rounded-xl border border-gray p-6 shadow-sm"> {{-- Card total transaksi --}}
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 uppercase tracking-wider">Total Transaksi</p> {{-- Label --}}
                    <p class="text-2xl font-bold text-navy mt-1">{{ number_format($totalTransaksi) }}</p> {{-- Nilai --}}
                </div>
                <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center"> {{-- Container ikon --}}
                    <span class="material-symbols-outlined text-navy">receipt_long</span> {{-- Ikon struk --}}
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray p-6 shadow-sm"> {{-- Card total masuk --}}
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 uppercase tracking-wider">Kendaraan Masuk</p> {{-- Label --}}
                    <p class="text-2xl font-bold text-blue-600 mt-1">{{ number_format($totalMasuk) }}</p> {{-- Nilai --}}
                </div>
                <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center"> {{-- Container ikon --}}
                    <span class="material-symbols-outlined text-blue-600">login</span> {{-- Ikon masuk --}}
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray p-6 shadow-sm"> {{-- Card total keluar --}}
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 uppercase tracking-wider">Kendaraan Keluar</p> {{-- Label --}}
                    <p class="text-2xl font-bold text-green-600 mt-1">{{ number_format($totalKeluar) }}</p> {{-- Nilai --}}
                </div>
                <div class="w-12 h-12 rounded-full bg-green-50 flex items-center justify-center"> {{-- Container ikon --}}
                    <span class="material-symbols-outlined text-green-600">logout</span> {{-- Ikon keluar --}}
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray p-6 shadow-sm"> {{-- Card total pendapatan --}}
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 uppercase tracking-wider">Total Pendapatan</p> {{-- Label --}}
                    <p class="text-2xl font-bold text-yellow-600 mt-1">Rp
                        {{ number_format($totalPendapatan, 0, ',', '.') }}</p> {{-- Nilai terformat --}}
                </div>
                <div class="w-12 h-12 rounded-full bg-yellow-50 flex items-center justify-center"> {{-- Container ikon --}}
                    <span class="material-symbols-outlined text-yellow-600">account_balance_wallet</span> {{-- Ikon dompet --}}
                </div>
            </div>
        </div>
    </div>

    <!-- CHARTS: 2 grafik berdampingan -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6"> {{-- Grid 1/2 kolom --}}
        <div class="bg-white rounded-xl border border-gray p-6 shadow-sm"> {{-- Card chart aktivitas --}}
            <h3 class="text-lg font-semibold text-navy mb-4 border-b border-gray pb-2">Aktivitas Parkir</h3> {{-- Judul --}}
            <canvas id="aktivitasChart" height="200"></canvas> {{-- Canvas chart --}}
        </div>
        <div class="bg-white rounded-xl border border-gray p-6 shadow-sm"> {{-- Card chart pendapatan --}}
            <h3 class="text-lg font-semibold text-navy mb-4 border-b border-gray pb-2">Pendapatan Parkir</h3> {{-- Judul --}}
            <canvas id="pendapatanChart" height="200"></canvas> {{-- Canvas chart --}}
        </div>
    </div>

    <!-- DETAIL TABLE: tabel detail transaksi (dikasih id untuk scroll target) -->
    <div class="bg-white rounded-xl border border-gray shadow-sm overflow-hidden" id="tabelLaporan"> {{-- Container tabel --}}
        <div class="p-4 border-b border-gray flex flex-col sm:flex-row sm:items-center justify-between gap-3"> {{-- Header tabel --}}
            <h3 class="text-lg font-semibold text-navy">Detail Transaksi</h3> {{-- Judul --}}
            <div class="flex flex-col sm:flex-row gap-2"> {{-- Container search & export --}}
                <div class="relative"> {{-- Container search --}}
                    <span
                        class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">search</span> {{-- Ikon search --}}
                    {{-- Input search: filter tabel client-side by plat nomor --}}
                    <input type="text" id="searchPlat" placeholder="Cari plat nomor..."
                        class="pl-10 pr-4 py-2 bg-white border border-gray rounded-lg text-sm focus:border-navy focus:ring-1 focus:ring-navy outline-none w-full sm:w-56"> {{-- Input search --}}
                </div>
                <div class="flex gap-2"> {{-- Container tombol export --}}
                    {{-- Tombol Export PDF (yang lama) --}}
                    <a href="{{ route('laporan.export', request()->all()) }}" target="_blank"
                        class="bg-gray-100 text-slate-600 font-semibold px-4 py-2 rounded-lg hover:bg-gray-200 transition-all flex items-center gap-2 text-sm"
                        title="Export PDF"> {{-- Tombol PDF --}}
                        <span class="material-symbols-outlined text-sm">picture_as_pdf</span> {{-- Ikon PDF --}}
                        PDF {{-- Label --}}
                    </a>

                    {{-- Tombol Export Excel (baru) --}}
                    <a href="{{ route('laporan.export-excel', request()->all()) }}"
                        class="bg-green-600 text-white font-semibold px-4 py-2 rounded-lg hover:bg-green-700 transition-all flex items-center gap-2 text-sm"
                        title="Export Excel"> {{-- Tombol Excel --}}
                        <span class="material-symbols-outlined text-sm">table_view</span> {{-- Ikon Excel --}}
                        Excel {{-- Label --}}
                    </a>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto"> {{-- Scroll horizontal untuk mobile --}}
            <table class="w-full text-left border-collapse"> {{-- Tabel detail --}}
                <thead>
                    <tr class="bg-gray-50 border-b border-gray"> {{-- Baris header --}}
                        <th class="py-3 px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Kode</th> {{-- Kolom kode --}}
                        <th class="py-3 px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Plat Nomor</th> {{-- Kolom plat --}}
                        <th class="py-3 px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Area</th> {{-- Kolom area --}}
                        <th class="py-3 px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Waktu Masuk</th> {{-- Kolom masuk --}}
                        <th class="py-3 px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Waktu Keluar
                        </th> {{-- Kolom keluar --}}
                        <th class="py-3 px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Tarif
                        </th> {{-- Kolom tarif --}}
                        <th class="py-3 px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">
                            Status</th> {{-- Kolom status --}}
                        <th class="py-3 px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">
                            Aksi</th> {{-- Kolom aksi --}}
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray" id="tabelTransaksi"> {{-- Body tabel --}}
                    @forelse($transaksi as $item) {{-- Loop data transaksi --}}
                        <tr class="hover:bg-gray-50 transition-colors"> {{-- Baris data --}}
                            <td class="py-3 px-4 text-sm text-slate-500 font-mono">{{ $item->kode_transaksi }}</td> {{-- Kode --}}
                            {{-- Class 'plat-nomor' dipakai JS search --}}
                            <td class="py-3 px-4 text-sm font-medium text-navy plat-nomor">
                                {{ $item->kendaraan->plat_nomor ?? '-' }}</td> {{-- Plat nomor --}}
                            <td class="py-3 px-4 text-sm text-slate-600">{{ $item->areaParkir->nama_area ?? '-' }}</td> {{-- Area --}}
                            <td class="py-3 px-4 text-sm text-slate-500">
                                {{ $item->waktu_masuk ? $item->waktu_masuk->format('d/m/Y H:i') : '-' }}</td> {{-- Waktu masuk --}}
                            <td class="py-3 px-4 text-sm text-slate-500">
                                {{ $item->waktu_keluar ? $item->waktu_keluar->format('d/m/Y H:i') : '-' }}</td> {{-- Waktu keluar --}}
                            <td class="py-3 px-4 text-sm text-navy text-right font-medium">
                                {{ $item->tarif ? 'Rp ' . number_format($item->tarif, 0, ',', '.') : '-' }} {{-- Tarif --}}
                            </td>
                            <td class="py-3 px-4 text-center"> {{-- Kolom status --}}
                                @if ($item->status == 'masuk')
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-full text-[10px] font-semibold bg-blue-100 text-blue-700">MASUK</span> {{-- Badge masuk --}}
                                @else
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-full text-[10px] font-semibold bg-green-100 text-green-700">KELUAR</span> {{-- Badge keluar --}}
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center"> {{-- Kolom aksi --}}
                                <a href="{{ route('transaksi.detail', $item->id) }}"
                                    class="text-slate-400 hover:text-navy transition-colors"> {{-- Tombol detail --}}
                                    <span class="material-symbols-outlined text-sm">visibility</span> {{-- Ikon mata --}}
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-slate-500"> {{-- Empty state --}}
                                <span
                                    class="material-symbols-outlined text-4xl block mx-auto mb-2 text-slate-300">assessment</span> {{-- Ikon besar --}}
                                <p class="text-sm">Tidak ada data transaksi untuk periode ini.</p> {{-- Pesan --}}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- PAGINATION -->
        <div class="p-4 border-t border-gray flex flex-col sm:flex-row justify-between items-center gap-3 bg-white"> {{-- Container pagination --}}
            <span class="text-xs text-slate-500">Menampilkan
                {{ $transaksi->firstItem() ?? 0 }}-{{ $transaksi->lastItem() ?? 0 }} dari {{ $transaksi->total() }}
                data</span> {{-- Info range --}}
            <div class="flex gap-1">
                {{-- appends: pertahankan filter, fragment: auto-scroll ke #tabelLaporan --}}
                {{ $transaksi->appends(request()->query())->fragment('tabelLaporan')->links() }} {{-- Link pagination --}}
            </div>
        </div>
    </div>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> {{-- Load Chart.js --}}

    <script>
        document.addEventListener('DOMContentLoaded', function() { // Tunggu DOM siap
            // Ambil data chart dari controller
            const labels = @json($chartLabelsFormatted ?? []); {{-- Label chart --}}
            const masukData = @json($chartMasuk ?? []); {{-- Data masuk --}}
            const keluarData = @json($chartKeluar ?? []); {{-- Data keluar --}}
            const pendapatanData = @json($chartPendapatan ?? []); {{-- Data pendapatan --}}

            const hasData = labels.length > 0; {{-- Cek apakah ada data --}}

            // ==================== GRAFIK AKTIVITAS (Bar) ====================
            const ctx1 = document.getElementById('aktivitasChart').getContext('2d'); {{-- Ambil context canvas --}}
            new Chart(ctx1, { {{-- Inisialisasi chart --}}
                type: 'bar', {{-- Tipe bar --}}
                data: {
                    labels: hasData ? labels : ['Belum Ada Data'], {{-- Label --}}
                    datasets: [{
                            label: 'Masuk', {{-- Label dataset --}}
                            data: hasData ? masukData : [0], {{-- Data masuk --}}
                            backgroundColor: 'rgba(37, 99, 235, 0.7)', {{-- Warna batang biru --}}
                            borderColor: '#2563eb', {{-- Border biru --}}
                            borderWidth: 1,
                            borderRadius: 4
                        },
                        {
                            label: 'Keluar', {{-- Label dataset --}}
                            data: hasData ? keluarData : [0], {{-- Data keluar --}}
                            backgroundColor: 'rgba(22, 163, 74, 0.7)', {{-- Warna batang hijau --}}
                            borderColor: '#16a34a', {{-- Border hijau --}}
                            borderWidth: 1,
                            borderRadius: 4
                        }
                    ]
                },
                options: {
                    responsive: true, {{-- Responsif --}}
                    maintainAspectRatio: true, {{-- Pertahankan rasio --}}
                    plugins: {
                        legend: { {{-- Legenda --}}
                            position: 'top',
                            labels: {
                                boxWidth: 12,
                                padding: 15,
                                font: { size: 11 }
                            }
                        }
                    },
                    scales: {
                        y: { {{-- Sumbu Y --}}
                            beginAtZero: true,
                            ticks: { stepSize: 1 } {{-- Step 1 --}}
                        },
                        x: { {{-- Sumbu X --}}
                            ticks: { font: { size: 10 } }
                        }
                    }
                }
            });

            // ==================== GRAFIK PENDAPATAN (Line) ====================
            const ctx2 = document.getElementById('pendapatanChart').getContext('2d'); {{-- Ambil context canvas --}}
            new Chart(ctx2, { {{-- Inisialisasi chart --}}
                type: 'line', {{-- Tipe line --}}
                data: {
                    labels: hasData ? labels : ['Belum Ada Data'], {{-- Label --}}
                    datasets: [{
                        label: 'Pendapatan (Rp)', {{-- Label dataset --}}
                        data: hasData ? pendapatanData : [0], {{-- Data pendapatan --}}
                        backgroundColor: 'rgba(244, 196, 48, 0.2)', {{-- Warna area kuning transparan --}}
                        borderColor: '#F4C430', {{-- Warna garis kuning --}}
                        borderWidth: 2,
                        fill: true, {{-- Isi area --}}
                        tension: 0.3, {{-- Kelengkungan garis --}}
                        pointBackgroundColor: '#F4C430', {{-- Warna titik --}}
                        pointBorderColor: '#172554', {{-- Border titik --}}
                        pointBorderWidth: 2,
                        pointRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: { {{-- Legenda --}}
                            position: 'top',
                            labels: {
                                boxWidth: 12,
                                padding: 15,
                                font: { size: 11 }
                            }
                        }
                    },
                    scales: {
                        y: { {{-- Sumbu Y --}}
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + value.toLocaleString('id-ID'); {{-- Format rupiah --}}
                                }
                            }
                        },
                        x: { {{-- Sumbu X --}}
                            ticks: { font: { size: 10 } }
                        }
                    }
                }
            });

            // ==================== SEARCH TABEL (CLIENT-SIDE) ====================
            const searchInput = document.getElementById('searchPlat'); {{-- Input search --}}
            const tabelBody = document.getElementById('tabelTransaksi'); {{-- Body tabel --}}

            searchInput.addEventListener('keyup', function() { {{-- Saat user mengetik --}}
                const keyword = this.value.toLowerCase(); {{-- Ambil kata kunci (lowercase) --}}
                Array.from(tabelBody.querySelectorAll('tr')).forEach(row => { {{-- Loop tiap baris --}}
                    const platNomor = row.querySelector('.plat-nomor'); {{-- Ambil elemen plat nomor --}}
                    if (!platNomor) return; {{-- Skip jika tidak ada (misal baris empty) --}}
                    const text = platNomor.textContent.toLowerCase(); {{-- Ambil teks plat --}}
                    row.style.display = text.includes(keyword) ? '' : 'none'; {{-- Tampilkan/sembunyikan --}}
                });
            });

            // ==================== AUTO-SCROLL SETELAH PAGINATION ====================
            // Kalau URL punya ?page=, scroll ke tabel
            if (window.location.search.includes('page=')) { {{-- Cek apakah ada parameter page --}}
                const tabel = document.getElementById('tabelLaporan'); {{-- Ambil elemen tabel --}}
                if (tabel) {
                    setTimeout(() => { {{-- Delay sedikit --}}
                        tabel.scrollIntoView({
                            behavior: 'smooth', {{-- Smooth scroll --}}
                            block: 'start'
                        });
                    }, 100);
                }
            }
        });
    </script>

@endsection