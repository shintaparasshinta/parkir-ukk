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
@extends('layouts.dashboard')

@section('title', 'Laporan Parkir - Parkir SMKN 8 Jember')

@section('content')
    <!-- HEADER: judul + info periode filter -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-navy">Laporan Parkir</h1>
        <p class="text-sm text-slate-500 mt-1">Rekap aktivitas dan transaksi parkir sekolah</p>
        @if ($startDate && $endDate)
            <p class="text-xs text-slate-400 mt-1">
                Periode: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} -
                {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}
            </p>
        @else
            <p class="text-xs text-slate-400 mt-1">Menampilkan semua transaksi</p>
        @endif
    </div>

    <!-- FILTERS: form filter laporan -->
    <div class="bg-white rounded-xl border border-gray p-6 shadow-sm mb-6">
        <form method="GET" action="{{ route('laporan.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Tanggal Mulai</label>
                <input type="date" name="start_date" value="{{ $startDate }}"
                    class="w-full bg-white border border-gray rounded-lg px-3 py-2 text-sm focus:border-navy focus:ring-1 focus:ring-navy outline-none transition-all">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Tanggal
                    Akhir</label>
                <input type="date" name="end_date" value="{{ $endDate }}"
                    class="w-full bg-white border border-gray rounded-lg px-3 py-2 text-sm focus:border-navy focus:ring-1 focus:ring-navy outline-none transition-all">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Area Parkir</label>
                <select name="area_id"
                    class="w-full bg-white border border-gray rounded-lg px-3 py-2 text-sm focus:border-navy focus:ring-1 focus:ring-navy outline-none transition-all">
                    <option value="">Semua Area</option>
                    @foreach ($areas as $area)
                        <option value="{{ $area->id }}" {{ $areaId == $area->id ? 'selected' : '' }}>
                            {{ $area->nama_area }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Jenis
                    Kendaraan</label>
                <select name="jenis_kendaraan"
                    class="w-full bg-white border border-gray rounded-lg px-3 py-2 text-sm focus:border-navy focus:ring-1 focus:ring-navy outline-none transition-all">
                    <option value="">Semua</option>
                    <option value="Motor" {{ $jenisKendaraan == 'Motor' ? 'selected' : '' }}>Motor</option>
                    <option value="Mobil" {{ $jenisKendaraan == 'Mobil' ? 'selected' : '' }}>Mobil</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit"
                    class="bg-yellow text-navy font-bold px-6 py-2 rounded-lg hover:bg-yellow/90 transition-all shadow-sm flex-1">Filter</button>
                <a href="{{ route('laporan.index') }}"
                    class="bg-gray-100 text-slate-600 font-bold px-4 py-2 rounded-lg hover:bg-gray-200 transition-all">Reset</a>
            </div>
        </form>
    </div>

    <!-- SUMMARY CARDS: 4 kartu rekap -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-gray p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 uppercase tracking-wider">Total Transaksi</p>
                    <p class="text-2xl font-bold text-navy mt-1">{{ number_format($totalTransaksi) }}</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center">
                    <span class="material-symbols-outlined text-navy">receipt_long</span>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 uppercase tracking-wider">Kendaraan Masuk</p>
                    <p class="text-2xl font-bold text-blue-600 mt-1">{{ number_format($totalMasuk) }}</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center">
                    <span class="material-symbols-outlined text-blue-600">login</span>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 uppercase tracking-wider">Kendaraan Keluar</p>
                    <p class="text-2xl font-bold text-green-600 mt-1">{{ number_format($totalKeluar) }}</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-green-50 flex items-center justify-center">
                    <span class="material-symbols-outlined text-green-600">logout</span>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 uppercase tracking-wider">Total Pendapatan</p>
                    <p class="text-2xl font-bold text-yellow-600 mt-1">Rp
                        {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-yellow-50 flex items-center justify-center">
                    <span class="material-symbols-outlined text-yellow-600">account_balance_wallet</span>
                </div>
            </div>
        </div>
    </div>

    <!-- CHARTS: 2 grafik berdampingan -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-gray p-6 shadow-sm">
            <h3 class="text-lg font-semibold text-navy mb-4 border-b border-gray pb-2">Aktivitas Parkir</h3>
            <canvas id="aktivitasChart" height="200"></canvas>
        </div>
        <div class="bg-white rounded-xl border border-gray p-6 shadow-sm">
            <h3 class="text-lg font-semibold text-navy mb-4 border-b border-gray pb-2">Pendapatan Parkir</h3>
            <canvas id="pendapatanChart" height="200"></canvas>
        </div>
    </div>

    <!-- DETAIL TABLE: tabel detail transaksi (dikasih id untuk scroll target) -->
    <div class="bg-white rounded-xl border border-gray shadow-sm overflow-hidden" id="tabelLaporan">
        <div class="p-4 border-b border-gray flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <h3 class="text-lg font-semibold text-navy">Detail Transaksi</h3>
            <div class="flex flex-col sm:flex-row gap-2">
                <div class="relative">
                    <span
                        class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">search</span>
                    {{-- Input search: filter tabel client-side by plat nomor --}}
                    <input type="text" id="searchPlat" placeholder="Cari plat nomor..."
                        class="pl-10 pr-4 py-2 bg-white border border-gray rounded-lg text-sm focus:border-navy focus:ring-1 focus:ring-navy outline-none w-full sm:w-56">
                </div>
                <div class="flex gap-2">
                    {{-- Tombol Export PDF (yang lama) --}}
                    <a href="{{ route('laporan.export', request()->all()) }}" target="_blank"
                        class="bg-gray-100 text-slate-600 font-semibold px-4 py-2 rounded-lg hover:bg-gray-200 transition-all flex items-center gap-2 text-sm"
                        title="Export PDF">
                        <span class="material-symbols-outlined text-sm">picture_as_pdf</span>
                        PDF
                    </a>

                    {{-- Tombol Export Excel (baru) --}}
                    <a href="{{ route('laporan.export-excel', request()->all()) }}"
                        class="bg-green-600 text-white font-semibold px-4 py-2 rounded-lg hover:bg-green-700 transition-all flex items-center gap-2 text-sm"
                        title="Export Excel">
                        <span class="material-symbols-outlined text-sm">table_view</span>
                        Excel
                    </a>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray">
                        <th class="py-3 px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Kode</th>
                        <th class="py-3 px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Plat Nomor</th>
                        <th class="py-3 px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Area</th>
                        <th class="py-3 px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Waktu Masuk</th>
                        <th class="py-3 px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Waktu Keluar
                        </th>
                        <th class="py-3 px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Tarif
                        </th>
                        <th class="py-3 px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">
                            Status</th>
                        <th class="py-3 px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray" id="tabelTransaksi">
                    @forelse($transaksi as $item)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="py-3 px-4 text-sm text-slate-500 font-mono">{{ $item->kode_transaksi }}</td>
                            {{-- Class 'plat-nomor' dipakai JS search --}}
                            <td class="py-3 px-4 text-sm font-medium text-navy plat-nomor">
                                {{ $item->kendaraan->plat_nomor ?? '-' }}</td>
                            <td class="py-3 px-4 text-sm text-slate-600">{{ $item->areaParkir->nama_area ?? '-' }}</td>
                            <td class="py-3 px-4 text-sm text-slate-500">
                                {{ $item->waktu_masuk ? $item->waktu_masuk->format('d/m/Y H:i') : '-' }}</td>
                            <td class="py-3 px-4 text-sm text-slate-500">
                                {{ $item->waktu_keluar ? $item->waktu_keluar->format('d/m/Y H:i') : '-' }}</td>
                            <td class="py-3 px-4 text-sm text-navy text-right font-medium">
                                {{ $item->tarif ? 'Rp ' . number_format($item->tarif, 0, ',', '.') : '-' }}
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if ($item->status == 'masuk')
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-full text-[10px] font-semibold bg-blue-100 text-blue-700">MASUK</span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-full text-[10px] font-semibold bg-green-100 text-green-700">KELUAR</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center">
                                <a href="{{ route('transaksi.detail', $item->id) }}"
                                    class="text-slate-400 hover:text-navy transition-colors">
                                    <span class="material-symbols-outlined text-sm">visibility</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-slate-500">
                                <span
                                    class="material-symbols-outlined text-4xl block mx-auto mb-2 text-slate-300">assessment</span>
                                <p class="text-sm">Tidak ada data transaksi untuk periode ini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- PAGINATION -->
        <div class="p-4 border-t border-gray flex flex-col sm:flex-row justify-between items-center gap-3 bg-white">
            <span class="text-xs text-slate-500">Menampilkan
                {{ $transaksi->firstItem() ?? 0 }}-{{ $transaksi->lastItem() ?? 0 }} dari {{ $transaksi->total() }}
                data</span>
            <div class="flex gap-1">
                {{-- appends: pertahankan filter, fragment: auto-scroll ke #tabelLaporan --}}
                {{ $transaksi->appends(request()->query())->fragment('tabelLaporan')->links() }}
            </div>
        </div>
    </div>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Ambil data chart dari controller
            const labels = @json($chartLabelsFormatted ?? []);
            const masukData = @json($chartMasuk ?? []);
            const keluarData = @json($chartKeluar ?? []);
            const pendapatanData = @json($chartPendapatan ?? []);

            const hasData = labels.length > 0;

            // ==================== GRAFIK AKTIVITAS (Bar) ====================
            const ctx1 = document.getElementById('aktivitasChart').getContext('2d');
            new Chart(ctx1, {
                type: 'bar',
                data: {
                    labels: hasData ? labels : ['Belum Ada Data'],
                    datasets: [{
                            label: 'Masuk',
                            data: hasData ? masukData : [0],
                            backgroundColor: 'rgba(37, 99, 235, 0.7)',
                            borderColor: '#2563eb',
                            borderWidth: 1,
                            borderRadius: 4
                        },
                        {
                            label: 'Keluar',
                            data: hasData ? keluarData : [0],
                            backgroundColor: 'rgba(22, 163, 74, 0.7)',
                            borderColor: '#16a34a',
                            borderWidth: 1,
                            borderRadius: 4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                boxWidth: 12,
                                padding: 15,
                                font: {
                                    size: 11
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        },
                        x: {
                            ticks: {
                                font: {
                                    size: 10
                                }
                            }
                        }
                    }
                }
            });

            // ==================== GRAFIK PENDAPATAN (Line) ====================
            const ctx2 = document.getElementById('pendapatanChart').getContext('2d');
            new Chart(ctx2, {
                type: 'line',
                data: {
                    labels: hasData ? labels : ['Belum Ada Data'],
                    datasets: [{
                        label: 'Pendapatan (Rp)',
                        data: hasData ? pendapatanData : [0],
                        backgroundColor: 'rgba(244, 196, 48, 0.2)',
                        borderColor: '#F4C430',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.3,
                        pointBackgroundColor: '#F4C430',
                        pointBorderColor: '#172554',
                        pointBorderWidth: 2,
                        pointRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                boxWidth: 12,
                                padding: 15,
                                font: {
                                    size: 11
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            }
                        },
                        x: {
                            ticks: {
                                font: {
                                    size: 10
                                }
                            }
                        }
                    }
                }
            });

            // ==================== SEARCH TABEL (CLIENT-SIDE) ====================
            const searchInput = document.getElementById('searchPlat');
            const tabelBody = document.getElementById('tabelTransaksi');

            searchInput.addEventListener('keyup', function() {
                const keyword = this.value.toLowerCase();
                Array.from(tabelBody.querySelectorAll('tr')).forEach(row => {
                    const platNomor = row.querySelector('.plat-nomor');
                    if (!platNomor) return;
                    const text = platNomor.textContent.toLowerCase();
                    row.style.display = text.includes(keyword) ? '' : 'none';
                });
            });

            // ==================== AUTO-SCROLL SETELAH PAGINATION ====================
            // Kalau URL punya ?page=, scroll ke tabel
            if (window.location.search.includes('page=')) {
                const tabel = document.getElementById('tabelLaporan');
                if (tabel) {
                    setTimeout(() => {
                        tabel.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }, 100); // Delay dikit biar render selesai
                }
            }
        });
    </script>

@endsection
