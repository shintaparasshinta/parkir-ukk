{{-- 
  HALAMAN DASHBOARD
  - KPI Cards: Total Kendaraan, Total User, Area Parkir, Transaksi Hari Ini
  - Chart: Statistik 7 hari (bar) + Pendapatan 7 hari (line)
  - Status Area + Tabel Transaksi Terbaru
  - Data semua diambil dari DashboardController (variabel: $total_kendaraan, dll)
--}}
@extends('layouts.dashboard')

@section('title', 'Dashboard - Parkir SMKN 8 Jember')

@section('content')
    <!-- Page Title: sambutan user yang login --}}
    <div class="mb-6">
        <h1 class="text-2xl md:text-3xl font-bold text-navy">Dashboard</h1>
        <p class="text-sm text-slate-500 mt-1">Selamat datang kembali, {{ auth()->user()->nama }}</p>
    </div>

    <!-- KPI Cards: 4 kartu statistik utama -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <!-- Card 1: Total Kendaraan -->
        <div class="bg-white rounded-xl border border-gray p-6 shadow-sm hover:shadow-md transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 uppercase tracking-wider">Total Kendaraan</p>
                    <h3 class="text-2xl font-bold text-navy mt-1">{{ $total_kendaraan }}</h3> {{-- Data dari controller --}}
                </div>
                <div class="w-12 h-12 rounded-full bg-navy/10 flex items-center justify-center">
                    <span class="material-symbols-outlined text-navy text-2xl"
                        style="font-variation-settings: 'FILL' 1;">directions_car</span>
                </div>
            </div>
        </div>

        <!-- Card 2: Total User -->
        <div class="bg-white rounded-xl border border-gray p-6 shadow-sm hover:shadow-md transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 uppercase tracking-wider">Total User</p>
                    <h3 class="text-2xl font-bold text-navy mt-1">{{ $total_users }}</h3>
                </div>
                <div class="w-12 h-12 rounded-full bg-green-500/10 flex items-center justify-center">
                    <span class="material-symbols-outlined text-green-500 text-2xl"
                        style="font-variation-settings: 'FILL' 1;">people</span>
                </div>
            </div>
        </div>

        <!-- Card 3: Area Parkir -->
        <div class="bg-white rounded-xl border border-gray p-6 shadow-sm hover:shadow-md transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 uppercase tracking-wider">Area Parkir</p>
                    <h3 class="text-2xl font-bold text-navy mt-1">{{ $total_area }}</h3>
                </div>
                <div class="w-12 h-12 rounded-full bg-yellow-500/10 flex items-center justify-center">
                    <span class="material-symbols-outlined text-yellow-600 text-2xl"
                        style="font-variation-settings: 'FILL' 1;">local_parking</span>
                </div>
            </div>
        </div>

        <!-- Card 4: Transaksi Hari Ini -->
        <div class="bg-white rounded-xl border border-gray p-6 shadow-sm hover:shadow-md transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 uppercase tracking-wider">Transaksi Hari Ini</p>
                    <h3 class="text-2xl font-bold text-navy mt-1">{{ $transaksi_hari_ini }}</h3>
                </div>
                <div class="w-12 h-12 rounded-full bg-yellow-500/20 flex items-center justify-center">
                    <span class="material-symbols-outlined text-yellow-600 text-2xl"
                        style="font-variation-settings: 'FILL' 1;">receipt_long</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts: 2 chart berdampingan -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Chart 1: Statistik Parkir 7 Hari -->
        <div class="bg-white rounded-xl border border-gray p-6 shadow-sm">
            <div class="flex justify-between items-center mb-4 pb-4 border-b border-gray">
                <h3 class="text-lg font-semibold text-navy">Statistik Parkir 7 Hari Terakhir</h3>
            </div>
            <canvas id="dashboardAktivitasChart" height="200"></canvas> {{-- Tempat render Chart.js --}}
        </div>
        <!-- Chart 2: Pendapatan 7 Hari -->
        <div class="bg-white rounded-xl border border-gray p-6 shadow-sm">
            <div class="flex justify-between items-center mb-4 pb-4 border-b border-gray">
                <h3 class="text-lg font-semibold text-navy">Pendapatan 7 Hari Terakhir</h3>
            </div>
            <canvas id="dashboardPendapatanChart" height="200"></canvas>
        </div>
    </div>

    <!-- Status Area & Transaksi Terbaru -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Status Area Parkir -->
        <div class="bg-white rounded-xl border border-gray p-6 shadow-sm">
            <h3 class="text-lg font-semibold text-navy mb-4 pb-4 border-b border-gray">Status Area Parkir</h3>
            <div class="flex flex-col gap-5">
                @forelse($area_status as $area)
                    <div>
                        <div class="flex justify-between items-end mb-1.5">
                            <div>
                                <h4 class="text-sm font-semibold text-navy">{{ $area->nama_area }}</h4>
                                <p class="text-xs text-slate-500">Kapasitas: {{ $area->kapasitas }}</p>
                            </div>
                            {{-- Badge status: warna beda per status --}}
                            <span
                                class="px-2 py-0.5 rounded text-[10px] font-bold uppercase 
                        @if ($area->status == 'tersedia') bg-green-500/10 text-green-500
                        @elseif($area->status == 'hampir_penuh') bg-yellow-500/10 text-yellow-500
                        @else bg-red-500/10 text-red-500 @endif">
                                {{ $area->status }}
                            </span>
                        </div>
                        {{-- Progress bar: random width buat visual --}}
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="h-2 rounded-full 
                        @if ($area->status == 'tersedia') bg-green-500
                        @elseif($area->status == 'hampir_penuh') bg-yellow-500
                        @else bg-red-500 @endif"
                                style="width: {{ rand(20, 100) }}%">
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">Belum ada data area parkir.</p>
                @endforelse
            </div>
        </div>

        <!-- Transaksi Terbaru (5 transaksi terakhir) -->
        <div class="lg:col-span-2 bg-white rounded-xl border border-gray overflow-hidden shadow-sm">
            <div class="p-4 border-b border-gray">
                <h3 class="text-lg font-semibold text-navy">Transaksi Terbaru</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray">
                            <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Kode</th>
                            <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Plat</th>
                            <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Area</th>
                            <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">
                                Waktu</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray">
                        @forelse($transaksi_terbaru as $item)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3 text-sm font-medium text-navy">{{ $item->kode_transaksi }}</td>
                                <td class="px-4 py-3 text-sm">{{ $item->kendaraan->plat_nomor ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm text-slate-500">{{ $item->areaParkir->nama_area ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    {{-- Badge status: MASUK (biru) / KELUAR (hijau) --}}
                                    @if ($item->status == 'masuk')
                                        <span
                                            class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs font-semibold">MASUK</span>
                                    @else
                                        <span
                                            class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-semibold">KELUAR</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm text-slate-500 text-right">
                                    {{ $item->created_at->format('H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-slate-500 text-sm">
                                    Belum ada transaksi.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chartData = @json($chart_data ?? []);

            // Cek dark mode
            const isDark = document.documentElement.classList.contains('dark');
            const textColor = isDark ? '#e2e8f0' : '#475569';
            const gridColor = isDark ? '#334155' : '#e2e8f0';

            if (chartData.labels && chartData.labels.length > 0) {

                // ==================== GRAFIK AKTIVITAS ====================
                const ctx1 = document.getElementById('dashboardAktivitasChart').getContext('2d');
                new Chart(ctx1, {
                    type: 'bar',
                    data: {
                        labels: chartData.labels,
                        datasets: [{
                                label: 'Masuk',
                                data: chartData.masuk,
                                backgroundColor: 'rgba(37, 99, 235, 0.7)',
                                borderColor: '#2563eb',
                                borderWidth: 1,
                                borderRadius: 4
                            },
                            {
                                label: 'Keluar',
                                data: chartData.keluar,
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
                                    color: textColor,
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
                                    color: textColor,
                                    stepSize: 1
                                },
                                grid: {
                                    color: gridColor
                                }
                            },
                            x: {
                                ticks: {
                                    color: textColor,
                                    font: {
                                        size: 10
                                    }
                                },
                                grid: {
                                    color: gridColor
                                }
                            }
                        }
                    }
                });

                // ==================== GRAFIK PENDAPATAN ====================
                const ctx2 = document.getElementById('dashboardPendapatanChart').getContext('2d');
                new Chart(ctx2, {
                    type: 'line',
                    data: {
                        labels: chartData.labels,
                        datasets: [{
                            label: 'Pendapatan (Rp)',
                            data: chartData.pendapatan,
                            backgroundColor: 'rgba(244, 196, 48, 0.2)',
                            borderColor: '#F4C430',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.3,
                            pointBackgroundColor: '#F4C430',
                            pointBorderColor: isDark ? '#e2e8f0' : '#172554',
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
                                    color: textColor,
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
                                    color: textColor,
                                    callback: v => 'Rp ' + v.toLocaleString('id-ID')
                                },
                                grid: {
                                    color: gridColor
                                }
                            },
                            x: {
                                ticks: {
                                    color: textColor,
                                    font: {
                                        size: 10
                                    }
                                },
                                grid: {
                                    color: gridColor
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>

@endsection
