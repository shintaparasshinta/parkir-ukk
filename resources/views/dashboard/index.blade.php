{{--
  HALAMAN DASHBOARD
  - KPI Cards: Total Kendaraan, Total User, Area Parkir, Transaksi Hari Ini
  - Chart: Statistik 7 hari (bar) + Pendapatan 7 hari (line)
  - Status Area + Tabel Transaksi Terbaru
  - Data semua diambil dari DashboardController (variabel: $total_kendaraan, dll)
--}}
@extends('layouts.dashboard') {{-- Menggunakan layout dashboard --}}

@section('title', 'Dashboard - Parkir SMKN 8 Jember') {{-- Judul halaman --}}

@section('content') {{-- Bagian konten --}}
    <!-- Page Title: sambutan user yang login --}}
    <div class="mb-6">
        <h1 class="text-2xl md:text-3xl font-bold text-navy">Dashboard</h1> {{-- Judul halaman --}}
        <p class="text-sm text-slate-500 mt-1">Selamat datang kembali, {{ auth()->user()->nama }}</p> {{-- Sapa user yang login --}}
    </div>

    <!-- KPI Cards: 4 kartu statistik utama -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6"> {{-- Grid responsif: 1/2/4 kolom --}}
        <!-- Card 1: Total Kendaraan -->
        <div class="bg-white rounded-xl border border-gray p-6 shadow-sm hover:shadow-md transition-all"> {{-- Card total kendaraan --}}
            <div class="flex items-center justify-between"> {{-- Layout fleksibel kiri-kanan --}}
                <div>
                    <p class="text-xs text-slate-500 uppercase tracking-wider">Total Kendaraan</p> {{-- Label --}}
                    <h3 class="text-2xl font-bold text-navy mt-1">{{ $total_kendaraan }}</h3> {{-- Data dari controller --}}
                </div>
                <div class="w-12 h-12 rounded-full bg-navy/10 flex items-center justify-center"> {{-- Container ikon --}}
                    <span class="material-symbols-outlined text-navy text-2xl"
                        style="font-variation-settings: 'FILL' 1;">directions_car</span> {{-- Ikon mobil --}}
                </div>
            </div>
        </div>

        <!-- Card 2: Total User -->
        <div class="bg-white rounded-xl border border-gray p-6 shadow-sm hover:shadow-md transition-all"> {{-- Card total user --}}
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 uppercase tracking-wider">Total User</p> {{-- Label --}}
                    <h3 class="text-2xl font-bold text-navy mt-1">{{ $total_users }}</h3> {{-- Data dari controller --}}
                </div>
                <div class="w-12 h-12 rounded-full bg-green-500/10 flex items-center justify-center"> {{-- Container ikon --}}
                    <span class="material-symbols-outlined text-green-500 text-2xl"
                        style="font-variation-settings: 'FILL' 1;">people</span> {{-- Ikon orang --}}
                </div>
            </div>
        </div>

        <!-- Card 3: Area Parkir -->
        <div class="bg-white rounded-xl border border-gray p-6 shadow-sm hover:shadow-md transition-all"> {{-- Card total area --}}
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 uppercase tracking-wider">Area Parkir</p> {{-- Label --}}
                    <h3 class="text-2xl font-bold text-navy mt-1">{{ $total_area }}</h3> {{-- Data dari controller --}}
                </div>
                <div class="w-12 h-12 rounded-full bg-yellow-500/10 flex items-center justify-center"> {{-- Container ikon --}}
                    <span class="material-symbols-outlined text-yellow-600 text-2xl"
                        style="font-variation-settings: 'FILL' 1;">local_parking</span> {{-- Ikon parkir --}}
                </div>
            </div>
        </div>

        <!-- Card 4: Transaksi Hari Ini -->
        <div class="bg-white rounded-xl border border-gray p-6 shadow-sm hover:shadow-md transition-all"> {{-- Card transaksi hari ini --}}
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 uppercase tracking-wider">Transaksi Hari Ini</p> {{-- Label --}}
                    <h3 class="text-2xl font-bold text-navy mt-1">{{ $transaksi_hari_ini }}</h3> {{-- Data dari controller --}}
                </div>
                <div class="w-12 h-12 rounded-full bg-yellow-500/20 flex items-center justify-center"> {{-- Container ikon --}}
                    <span class="material-symbols-outlined text-yellow-600 text-2xl"
                        style="font-variation-settings: 'FILL' 1;">receipt_long</span> {{-- Ikon struk --}}
                </div>
            </div>
        </div>
    </div>

    <!-- Charts: 2 chart berdampingan -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6"> {{-- Grid 2 kolom untuk chart --}}
        <!-- Chart 1: Statistik Parkir 7 Hari -->
        <div class="bg-white rounded-xl border border-gray p-6 shadow-sm"> {{-- Container chart aktivitas --}}
            <div class="flex justify-between items-center mb-4 pb-4 border-b border-gray">
                <h3 class="text-lg font-semibold text-navy">Statistik Parkir 7 Hari Terakhir</h3> {{-- Judul chart --}}
            </div>
            <canvas id="dashboardAktivitasChart" height="200"></canvas> {{-- Tempat render Chart.js --}}
        </div>
        <!-- Chart 2: Pendapatan 7 Hari -->
        <div class="bg-white rounded-xl border border-gray p-6 shadow-sm"> {{-- Container chart pendapatan --}}
            <div class="flex justify-between items-center mb-4 pb-4 border-b border-gray">
                <h3 class="text-lg font-semibold text-navy">Pendapatan 7 Hari Terakhir</h3> {{-- Judul chart --}}
            </div>
            <canvas id="dashboardPendapatanChart" height="200"></canvas> {{-- Tempat render Chart.js --}}
        </div>
    </div>

    <!-- Status Area & Transaksi Terbaru -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6"> {{-- Grid: 1 kolom status, 2 kolom transaksi --}}
        <!-- Status Area Parkir -->
        <div class="bg-white rounded-xl border border-gray p-6 shadow-sm"> {{-- Container status area --}}
            <h3 class="text-lg font-semibold text-navy mb-4 pb-4 border-b border-gray">Status Area Parkir</h3> {{-- Judul --}}
            <div class="flex flex-col gap-5"> {{-- Stack vertikal --}}
                @forelse($area_status as $area) {{-- Loop tiap area --}}
                    <div>
                        <div class="flex justify-between items-end mb-1.5"> {{-- Header area --}}
                            <div>
                                <h4 class="text-sm font-semibold text-navy">{{ $area->nama_area }}</h4> {{-- Nama area --}}
                                <p class="text-xs text-slate-500">Kapasitas: {{ $area->kapasitas }}</p> {{-- Kapasitas --}}
                            </div>
                            {{-- Badge status: warna beda per status --}}
                            <span
                                class="px-2 py-0.5 rounded text-[10px] font-bold uppercase 
                        @if ($area->status == 'tersedia') bg-green-500/10 text-green-500
                        @elseif($area->status == 'hampir_penuh') bg-yellow-500/10 text-yellow-500
                        @else bg-red-500/10 text-red-500 @endif">
                                {{ $area->status }} {{-- Tampilkan status --}}
                            </span>
                        </div>
                        {{-- Progress bar: random width buat visual --}}
                        <div class="w-full bg-gray-200 rounded-full h-2"> {{-- Track progress --}}
                            <div class="h-2 rounded-full 
                        @if ($area->status == 'tersedia') bg-green-500
                        @elseif($area->status == 'hampir_penuh') bg-yellow-500
                        @else bg-red-500 @endif"
                                style="width: {{ rand(20, 100) }}%"> {{-- Width RANDOM (bukan data real!) --}}
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">Belum ada data area parkir.</p> {{-- Empty state --}}
                @endforelse
            </div>
        </div>

        <!-- Transaksi Terbaru (5 transaksi terakhir) -->
        <div class="lg:col-span-2 bg-white rounded-xl border border-gray overflow-hidden shadow-sm"> {{-- Container transaksi (2 kolom) --}}
            <div class="p-4 border-b border-gray">
                <h3 class="text-lg font-semibold text-navy">Transaksi Terbaru</h3> {{-- Judul --}}
            </div>
            <div class="overflow-x-auto"> {{-- Scroll horizontal mobile --}}
                <table class="w-full text-left"> {{-- Tabel transaksi --}}
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray"> {{-- Baris header --}}
                            <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Kode</th> {{-- Kolom kode --}}
                            <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Plat</th> {{-- Kolom plat --}}
                            <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Area</th> {{-- Kolom area --}}
                            <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th> {{-- Kolom status --}}
                            <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">
                                Waktu</th> {{-- Kolom waktu --}}
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray"> {{-- Body tabel --}}
                        @forelse($transaksi_terbaru as $item) {{-- Loop data transaksi --}}
                            <tr class="hover:bg-gray-50 transition-colors"> {{-- Baris data --}}
                                <td class="px-4 py-3 text-sm font-medium text-navy">{{ $item->kode_transaksi }}</td> {{-- Kode transaksi --}}
                                <td class="px-4 py-3 text-sm">{{ $item->kendaraan->plat_nomor ?? '-' }}</td> {{-- Plat nomor --}}
                                <td class="px-4 py-3 text-sm text-slate-500">{{ $item->areaParkir->nama_area ?? '-' }}</td> {{-- Nama area --}}
                                <td class="px-4 py-3">
                                    {{-- Badge status: MASUK (biru) / KELUAR (hijau) --}}
                                    @if ($item->status == 'masuk')
                                        <span
                                            class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs font-semibold">MASUK</span> {{-- Badge masuk --}}
                                    @else
                                        <span
                                            class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-semibold">KELUAR</span> {{-- Badge keluar --}}
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm text-slate-500 text-right">
                                    {{ $item->created_at->format('H:i') }}</td> {{-- Waktu dibuat (jam:menit) --}}
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-slate-500 text-sm">
                                    Belum ada transaksi. {{-- Empty state --}}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> {{-- Load Chart.js via CDN --}}

    <script>
        document.addEventListener('DOMContentLoaded', function() { // Tunggu DOM siap
            const chartData = @json($chart_data ?? []); // Data grafik dari controller (di-encode JSON)

            // Cek dark mode
            const isDark = document.documentElement.classList.contains('dark'); // Cek apakah mode gelap aktif
            const textColor = isDark ? '#e2e8f0' : '#475569'; // Warna teks chart sesuai mode
            const gridColor = isDark ? '#334155' : '#e2e8f0'; // Warna grid chart sesuai mode

            if (chartData.labels && chartData.labels.length > 0) { // Hanya render jika ada data

                // ==================== GRAFIK AKTIVITAS ====================
                const ctx1 = document.getElementById('dashboardAktivitasChart').getContext('2d'); // Ambil canvas context
                new Chart(ctx1, { // Inisialisasi chart
                    type: 'bar', // Tipe: bar chart
                    data: { // Data chart
                        labels: chartData.labels, // Label sumbu X
                        datasets: [{ // Dataset masuk
                                label: 'Masuk', // Label dataset
                                data: chartData.masuk, // Data masuk
                                backgroundColor: 'rgba(37, 99, 235, 0.7)', // Warna batang (biru)
                                borderColor: '#2563eb', // Border biru
                                borderWidth: 1, // Lebar border
                                borderRadius: 4 // Sudut batang melengkung
                            },
                            { // Dataset keluar
                                label: 'Keluar', // Label dataset
                                data: chartData.keluar, // Data keluar
                                backgroundColor: 'rgba(22, 163, 74, 0.7)', // Warna batang (hijau)
                                borderColor: '#16a34a', // Border hijau
                                borderWidth: 1,
                                borderRadius: 4
                            }
                        ]
                    },
                    options: { // Opsi chart
                        responsive: true, // Responsif
                        maintainAspectRatio: true, // Pertahankan rasio aspek
                        plugins: { // Konfigurasi plugin
                            legend: { // Legenda
                                position: 'top', // Posisi legenda di atas
                                labels: { // Label legenda
                                    color: textColor, // Warna teks
                                    boxWidth: 12, // Lebar kotak legenda
                                    padding: 15, // Padding
                                    font: { size: 11 } // Ukuran font
                                }
                            }
                        },
                        scales: { // Konfigurasi sumbu
                            y: { // Sumbu Y
                                beginAtZero: true, // Mulai dari nol
                                ticks: { color: textColor, stepSize: 1 }, // Warna teks & step 1 (bulat)
                                grid: { color: gridColor } // Warna grid
                            },
                            x: { // Sumbu X
                                ticks: { color: textColor, font: { size: 10 } }, // Warna teks & ukuran font
                                grid: { color: gridColor }
                            }
                        }
                    }
                });

                // ==================== GRAFIK PENDAPATAN ====================
                const ctx2 = document.getElementById('dashboardPendapatanChart').getContext('2d'); // Ambil canvas context
                new Chart(ctx2, { // Inisialisasi chart
                    type: 'line', // Tipe: line chart
                    data: { // Data chart
                        labels: chartData.labels, // Label sumbu X
                        datasets: [{ // Dataset pendapatan
                            label: 'Pendapatan (Rp)', // Label dataset
                            data: chartData.pendapatan, // Data pendapatan
                            backgroundColor: 'rgba(244, 196, 48, 0.2)', // Warna area di bawah garis (kuning transparan)
                            borderColor: '#F4C430', // Warna garis (kuning)
                            borderWidth: 2, // Lebar garis
                            fill: true, // Isi area di bawah garis
                            tension: 0.3, // Kelengkungan garis
                            pointBackgroundColor: '#F4C430', // Warna titik data
                            pointBorderColor: isDark ? '#e2e8f0' : '#172554', // Border titik sesuai mode
                            pointBorderWidth: 2, // Lebar border titik
                            pointRadius: 4 // Radius titik
                        }]
                    },
                    options: { // Opsi chart
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: { // Legenda
                                position: 'top',
                                labels: {
                                    color: textColor,
                                    boxWidth: 12,
                                    padding: 15,
                                    font: { size: 11 }
                                }
                            }
                        },
                        scales: {
                            y: { // Sumbu Y
                                beginAtZero: true,
                                ticks: {
                                    color: textColor,
                                    callback: v => 'Rp ' + v.toLocaleString('id-ID') // Format nilai jadi rupiah
                                },
                                grid: { color: gridColor }
                            },
                            x: { // Sumbu X
                                ticks: { color: textColor, font: { size: 10 } },
                                grid: { color: gridColor }
                            }
                        }
                    }
                });
            }
        });
    </script>

@endsection