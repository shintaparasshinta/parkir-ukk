{{-- 
  HALAMAN LANDING (Publik)
  - Standalone (tanpa sidebar/navbar)
  - Berisi: Hero, Stats, Fitur (3 card), CTA, Footer
  - Tombol "Login" mengarah ke route('login')
  - Mockup dashboard: dibuat pakai HTML/CSS (bukan gambar), biar selalu muncul
--}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parkir SMKN 8 Jember - Beranda</title>

    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

    <style>
        /* Style global landing */
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8F9FF; color: #172554; }
        .text-yellow { color: #F4C430; }     /* Aksen kuning (teks) */
        .bg-yellow { background-color: #F4C430; } /* Background kuning (tombol, badge) */
        .bg-navy { background-color: #172554; }   /* Biru tua */
        .text-navy { color: #172554; }
        .btn-yellow { background-color: #F4C430; color: #172554; font-weight: bold; }
        .btn-yellow:hover { background-color: #E0B020; }
        .border-gray { border-color: #E2E8F0; }
    </style>
</head>
<body class="min-h-screen flex flex-col">

    <!-- NAVBAR SEDERHANA (Hanya Logo + Login) -->
    <header class="h-20 w-full bg-white border-b border-gray sticky top-0 z-50 flex items-center px-4 md:px-8">
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/smkn.png') }}" alt="Logo" class="w-10 h-10">
            <h1 class="text-lg font-bold text-navy">PARKIR SMKN 8 JEMBER</h1>
        </div>

        <div class="ml-auto">
            {{-- Tombol Login: link ke route('login') --}}
            <a href="{{ route('login') }}" class="btn-yellow px-6 py-2.5 rounded-lg flex items-center gap-2">
                Login <span class="material-symbols-outlined text-[18px]">login</span>
            </a>
        </div>
    </header>

    <!-- MAIN CONTENT -->
    <main class="flex-1">
        <!-- HERO SECTION: judul besar + CTA + mockup dashboard -->
        <section class="max-w-7xl mx-auto px-4 md:px-8 py-16 md:py-24">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div class="flex flex-col gap-6">
                    {{-- Badge kecil di atas judul --}}
                    <div class="inline-flex items-center gap-2 bg-[#FFF4CC] text-navy px-4 py-1.5 rounded-full w-fit">
                        <span class="material-symbols-outlined text-yellow text-sm">local_parking</span>
                        <span class="text-sm font-bold">DIGITAL PARKING MANAGEMENT SYSTEM</span>
                    </div>
                    <h1 class="text-4xl md:text-5xl font-bold text-navy leading-tight">
                        Kelola Parkir SMKN 8 Jember dengan Lebih <span class="text-yellow">Mudah</span> dan <span class="text-yellow">Teratur</span>
                    </h1>
                    <p class="text-lg text-slate-500 max-w-lg">
                        Sistem informasi manajemen parkir modern untuk mencatat, memantau, dan mengelola kendaraan warga sekolah secara efisien dan aman.
                    </p>
                    <div class="flex flex-wrap items-center gap-4 pt-4">
                        <a href="{{ route('login') }}" class="btn-yellow px-8 py-3.5 rounded-lg flex items-center gap-2">
                            Masuk ke Sistem <span class="material-symbols-outlined">arrow_forward</span>
                        </a>
                        <a href="#fitur" class="bg-transparent border border-gray text-slate-600 font-bold px-8 py-3.5 rounded-lg hover:bg-gray-50 transition-all">
                            Pelajari Fitur
                        </a>
                    </div>
                </div>

                {{-- MOCKUP DASHBOARD (HTML/CSS, bukan gambar) --}}
                <div class="relative rounded-xl overflow-hidden border border-gray shadow-2xl bg-white">
                    {{-- Bar ala browser --}}
                    <div class="bg-gray-100 h-8 flex items-center px-4 gap-2 border-b border-gray">
                        <div class="w-3 h-3 rounded-full bg-red-400"></div>
                        <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                        <div class="w-3 h-3 rounded-full bg-green-400"></div>
                        <span class="ml-3 text-[10px] text-slate-400">localhost/parkir-sekolah/dashboard</span>
                    </div>

                    {{-- Isi mockup: sidebar + konten --}}
                    <div class="flex" style="min-height: 320px;">

                        {{-- Sidebar mini --}}
                        <div class="w-32 bg-[#172554] p-3 flex flex-col gap-2">
                            <div class="flex items-center gap-1.5 mb-3">
                                <div class="w-5 h-5 rounded bg-white/10 flex items-center justify-center">
                                    <span class="material-symbols-outlined text-white text-[12px]">local_parking</span>
                                </div>
                                <span class="text-white text-[9px] font-bold leading-tight">PARKIR SMKN 8</span>
                            </div>
                            <div class="flex items-center gap-1.5 px-1.5 py-1 rounded bg-yellow-400/90">
                                <span class="material-symbols-outlined text-[#172554] text-[10px]">dashboard</span>
                                <span class="text-[#172554] text-[8px] font-bold">Dashboard</span>
                            </div>
                            <div class="flex items-center gap-1.5 px-1.5 py-1">
                                <span class="material-symbols-outlined text-white/50 text-[10px]">directions_car</span>
                                <span class="text-white/50 text-[8px]">Kendaraan</span>
                            </div>
                            <div class="flex items-center gap-1.5 px-1.5 py-1">
                                <span class="material-symbols-outlined text-white/50 text-[10px]">local_parking</span>
                                <span class="text-white/50 text-[8px]">Area Parkir</span>
                            </div>
                            <div class="flex items-center gap-1.5 px-1.5 py-1">
                                <span class="material-symbols-outlined text-white/50 text-[10px]">receipt_long</span>
                                <span class="text-white/50 text-[8px]">Transaksi</span>
                            </div>
                            <div class="flex items-center gap-1.5 px-1.5 py-1">
                                <span class="material-symbols-outlined text-white/50 text-[10px]">assessment</span>
                                <span class="text-white/50 text-[8px]">Laporan</span>
                            </div>
                        </div>

                        {{-- Konten mockup --}}
                        <div class="flex-1 p-3 bg-slate-50">

                            {{-- Header mini: judul + profil --}}
                            <div class="flex justify-between items-center mb-3">
                                <div>
                                    <p class="text-[9px] font-bold text-[#172554]">Dashboard</p>
                                    <p class="text-[7px] text-slate-400">Selamat datang, Admin</p>
                                </div>
                                <div class="w-4 h-4 rounded-full bg-yellow-400 flex items-center justify-center">
                                    <span class="text-[6px] font-bold text-[#172554]">A</span>
                                </div>
                            </div>

                            {{-- KPI Cards mini --}}
                            <div class="grid grid-cols-3 gap-1.5 mb-2">
                                <div class="bg-white p-1.5 rounded border border-slate-200">
                                    <p class="text-[6px] text-slate-400">Kendaraan</p>
                                    <p class="text-[11px] font-bold text-[#172554]">142</p>
                                </div>
                                <div class="bg-white p-1.5 rounded border border-slate-200">
                                    <p class="text-[6px] text-slate-400">Transaksi</p>
                                    <p class="text-[11px] font-bold text-green-600">25</p>
                                </div>
                                <div class="bg-white p-1.5 rounded border border-slate-200">
                                    <p class="text-[6px] text-slate-400">Pendapatan</p>
                                    <p class="text-[9px] font-bold text-yellow-600">Rp 3.0jt</p>
                                </div>
                            </div>

                            {{-- Grafik mini (CSS bar chart) --}}
                            <div class="bg-white p-2 rounded border border-slate-200 mb-2">
                                <p class="text-[6px] text-slate-400 mb-1.5">Statistik 7 Hari</p>
                                <div class="flex items-end justify-between gap-0.5" style="height: 50px;">
                                    <div class="bg-blue-500 rounded-t" style="width: 8%; height: 40%;"></div>
                                    <div class="bg-blue-500 rounded-t" style="width: 8%; height: 60%;"></div>
                                    <div class="bg-blue-500 rounded-t" style="width: 8%; height: 30%;"></div>
                                    <div class="bg-blue-500 rounded-t" style="width: 8%; height: 75%;"></div>
                                    <div class="bg-blue-500 rounded-t" style="width: 8%; height: 50%;"></div>
                                    <div class="bg-blue-500 rounded-t" style="width: 8%; height: 85%;"></div>
                                    <div class="bg-blue-500 rounded-t" style="width: 8%; height: 45%;"></div>
                                    <div class="bg-yellow-400 rounded-t" style="width: 8%; height: 65%;"></div>
                                    <div class="bg-yellow-400 rounded-t" style="width: 8%; height: 90%;"></div>
                                    <div class="bg-yellow-400 rounded-t" style="width: 8%; height: 55%;"></div>
                                </div>
                            </div>

                            {{-- Tabel transaksi mini --}}
                            <div class="bg-white p-2 rounded border border-slate-200">
                                <p class="text-[6px] text-slate-400 mb-1.5">Transaksi Terbaru</p>
                                <div class="flex justify-between items-center border-b border-slate-100 pb-1 mb-1">
                                    <span class="text-[6px] text-slate-400">TRX-001</span>
                                    <span class="text-[6px] text-slate-600">B 1234 ABC</span>
                                    <span class="text-[6px] bg-blue-100 text-blue-700 px-1 rounded">MASUK</span>
                                </div>
                                <div class="flex justify-between items-center border-b border-slate-100 pb-1 mb-1">
                                    <span class="text-[6px] text-slate-400">TRX-002</span>
                                    <span class="text-[6px] text-slate-600">B 5678 DEF</span>
                                    <span class="text-[6px] bg-green-100 text-green-700 px-1 rounded">KELUAR</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-[6px] text-slate-400">TRX-003</span>
                                    <span class="text-[6px] text-slate-600">B 9012 GHI</span>
                                    <span class="text-[6px] bg-blue-100 text-blue-700 px-1 rounded">MASUK</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- STATS SECTION: 4 angka besar -->
        <section class="bg-white border-y border-gray">
            <div class="max-w-7xl mx-auto px-4 md:px-8 py-12">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                    <div class="text-center"><h3 class="text-3xl font-bold text-navy mb-2">3+</h3><p class="text-sm text-slate-500">Area Parkir</p></div>
                    <div class="text-center"><h3 class="text-3xl font-bold text-navy mb-2">1.000+</h3><p class="text-sm text-slate-500">Kendaraan Terdata</p></div>
                    <div class="text-center"><h3 class="text-3xl font-bold text-navy mb-2">24/7</h3><p class="text-sm text-slate-500">Data Tersimpan</p></div>
                    <div class="text-center"><h3 class="text-3xl font-bold text-navy mb-2">100%</h3><p class="text-sm text-slate-500">Digital</p></div>
                </div>
            </div>
        </section>

        <!-- FEATURES SECTION: 3 card fitur utama -->
        <section id="fitur" class="max-w-7xl mx-auto px-4 md:px-8 py-20 bg-[#F8F9FF]">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-navy mb-4">Fitur Lengkap &amp; Terpadu</h2>
                <p class="text-md text-slate-500 max-w-2xl mx-auto">Semua alat yang Anda butuhkan untuk mengelola sistem parkir sekolah dengan mudah dalam satu platform yang terintegrasi.</p>
            </div>
            <div class="grid md:grid-cols-3 gap-6">
                <!-- Fitur 1: Manajemen Kendaraan -->
                <div class="bg-white p-6 rounded-xl border border-gray hover:shadow-ambient transition-all group">
                    <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center mb-6 group-hover:bg-navy transition-colors">
                        <span class="material-symbols-outlined text-navy group-hover:text-white">directions_car</span>
                    </div>
                    <h3 class="text-xl font-semibold text-navy mb-3">Manajemen Kendaraan</h3>
                    <p class="text-sm text-slate-500">Daftarkan dan kelola data kendaraan siswa, guru, dan staf sekolah secara terpusat.</p>
                </div>
                <!-- Fitur 2: Area Parkir -->
                <div class="bg-white p-6 rounded-xl border border-gray hover:shadow-ambient transition-all group">
                    <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center mb-6 group-hover:bg-navy transition-colors">
                        <span class="material-symbols-outlined text-navy group-hover:text-white">local_parking</span>
                    </div>
                    <h3 class="text-xl font-semibold text-navy mb-3">Area Parkir</h3>
                    <p class="text-sm text-slate-500">Pantau kapasitas area parkir secara real-time dan alokasikan zona parkir khusus.</p>
                </div>
                <!-- Fitur 3: Transaksi Parkir -->
                <div class="bg-white p-6 rounded-xl border border-gray hover:shadow-ambient transition-all group">
                    <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center mb-6 group-hover:bg-navy transition-colors">
                        <span class="material-symbols-outlined text-navy group-hover:text-white">receipt_long</span>
                    </div>
                    <h3 class="text-xl font-semibold text-navy mb-3">Transaksi Parkir</h3>
                    <p class="text-sm text-slate-500">Catat setiap kendaraan yang masuk dan keluar dengan cepat dan akurat.</p>
                </div>
            </div>
        </section>

        <!-- CTA SECTION: ajakan login -->
        <section class="bg-navy py-20 px-4 md:px-8">
            <div class="max-w-4xl mx-auto text-center flex flex-col items-center">
                <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">Siap Mengelola Parkir dengan Lebih Mudah?</h2>
                <p class="text-lg text-blue-200 mb-10 max-w-2xl">Mulai digitalisasi sistem parkir sekolah Anda hari ini dan rasakan kemudahannya.</p>
                {{-- Tombol CTA: pakai btn-yellow biar background & hover-nya rapi --}}
                <a href="{{ route('login') }}" class="btn-yellow px-10 py-4 rounded-xl transition-all shadow-lg flex items-center gap-3 text-lg">
                    Masuk ke Sistem <span class="material-symbols-outlined">arrow_forward</span>
                </a>
            </div>
        </section>
    </main>

    <!-- FOOTER: logo, tautan cepat, bantuan, copyright -->
    <footer class="bg-[#0B1530] text-white py-16 px-4 md:px-8 border-t border-navy/50">
        <div class="max-w-7xl mx-auto grid md:grid-cols-4 gap-12">
            <div class="md:col-span-2">
                <div class="flex items-center gap-3 mb-6">
                    <div class="bg-white p-2 rounded-lg">
                        <img alt="Logo SMK" class="w-10 h-10 object-contain" src="{{ asset('images/smkn.png') }}">
                    </div>
                    <span class="text-2xl font-bold">PARKIR SMKN 8 JEMBER</span>
                </div>
                <p class="text-sm text-blue-200/70 max-w-sm mb-6">Sistem Manajemen Parkir Digital untuk mendukung ketertiban dan keamanan kendaraan di lingkungan sekolah.</p>
                <p class="text-sm font-semibold text-blue-200">SMK Negeri 8 Jember</p>
            </div>
            <div>
                <h4 class="text-xl font-semibold mb-6">Tautan Cepat</h4>
                <ul class="flex flex-col gap-4">
                    <li><a class="text-sm text-blue-200/70 hover:text-yellow transition-colors" href="#">Beranda</a></li>
                    <li><a class="text-sm text-blue-200/70 hover:text-yellow transition-colors" href="#fitur">Fitur</a></li>
                    <li><a class="text-sm text-blue-200/70 hover:text-yellow transition-colors" href="#">Cara Kerja</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-xl font-semibold mb-6">Bantuan</h4>
                <ul class="flex flex-col gap-4">
                    <li><a class="text-sm text-blue-200/70 hover:text-yellow transition-colors" href="#">Kebijakan Privasi</a></li>
                    <li><a class="text-sm text-blue-200/70 hover:text-yellow transition-colors" href="#">Syarat &amp; Ketentuan</a></li>
                    <li><a class="text-sm text-blue-200/70 hover:text-yellow transition-colors" href="#">Hubungi Kami</a></li>
                </ul>
            </div>
        </div>
        <div class="max-w-7xl mx-auto mt-16 pt-8 border-t border-white/10 text-center">
            <p class="text-sm text-blue-200/50">© 2026 PARKIR SMKN 8 JEMBER. Sistem Manajemen Parkir Modern.</p>
        </div>
    </footer>
</body>
</html>
