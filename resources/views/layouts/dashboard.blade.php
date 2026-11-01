{{-- 
  LAYOUT UTAMA (Sidebar + Navbar)
  - DARK MODE: toggle di navbar, tersimpan di localStorage
  - MOBILE FRIENDLY: sidebar bisa di-toggle di HP
  - Navbar: live clock + notifikasi + profil
  - Logout pakai modal konfirmasi
  - Avatar inisial (bg-navy) di-override jadi kuning di dark mode
--}}
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Parkir SMKN 8 Jember')</title>

    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
        tailwind.config = {
            darkMode: 'class'
        }
    </script>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet">

    {{-- Anti-FOUC: pasang dark mode SEBELUM halaman render --}}
    <script>
        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.classList.add('dark');
        }
    </script>

    <style>
        /* ============ GLOBAL ============ */
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F8FAFC;
            color: #475569;
            transition: background-color 0.3s, color 0.3s;
        }

        .dark body {
            background-color: #0f172a;
            color: #cbd5e1;
        }

        .text-navy {
            color: #172554;
        }

        .dark .text-navy {
            color: #e2e8f0;
        }

        .bg-navy {
            background-color: #172554;
        }

        .text-yellow {
            color: #F4C430;
        }

        .bg-yellow {
            background-color: #F4C430;
        }

        .border-gray {
            border-color: #E2E8F0;
        }

        .dark .border-gray {
            border-color: #1e293b;
        }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        /* ============ SIDEBAR ============ */
        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 16px;
            margin: 0 8px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.2s;
            color: #64748b;
        }

        .dark .sidebar-link {
            color: #94a3b8;
        }

        .sidebar-link:hover {
            color: #172554;
            background: #f1f5f9;
        }

        .dark .sidebar-link:hover {
            color: #fff;
            background: #1e293b;
        }

        .sidebar-link.active {
            background: #F4C430;
            color: #172554;
        }

        .sidebar-link.active .material-symbols-outlined {
            font-variation-settings: 'FILL' 1;
        }

        .sidebar-link .material-symbols-outlined {
            font-size: 22px;
        }

        /* Live clock */
        .live-clock {
            font-family: 'Courier New', monospace;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        /* Scrollbar */
        .sidebar-scroll::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar-scroll::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        .dark .sidebar-scroll::-webkit-scrollbar-thumb {
            background: #334155;
        }

        /* ============ DARK MODE OVERRIDE ============ */

        /* Background putih & abu terang -> gelap */
        .dark .bg-white {
            background-color: #1e293b !important;
        }

        .dark .bg-slate-50 {
            background-color: #0f172a !important;
        }

        .dark .bg-gray-50 {
            background-color: #1e293b !important;
        }

        .dark .bg-gray-100 {
            background-color: #334155 !important;
        }

        .dark .bg-blue-50 {
            background-color: #1e3a8a33 !important;
        }

        .dark .bg-gray-200 {
            background-color: #334155 !important;
        }

        /* Background untuk card dengan warna */
        .dark .bg-navy\/10 {
            background-color: rgba(183, 196, 253, 0.1) !important;
        }

        .dark .bg-green-50 {
            background-color: #064e3b33 !important;
        }

        .dark .bg-yellow-50 {
            background-color: #78350f33 !important;
        }

        .dark .bg-red-50 {
            background-color: #7f1d1d33 !important;
        }

        /* Teks abu-abu -> lebih terang */
        .dark .text-slate-400 {
            color: #94a3b8 !important;
        }

        .dark .text-slate-500 {
            color: #94a3b8 !important;
        }

        .dark .text-slate-600 {
            color: #cbd5e1 !important;
        }

        .dark .text-slate-700 {
            color: #e2e8f0 !important;
        }

        .dark .text-gray-400 {
            color: #94a3b8 !important;
        }

        .dark .text-gray-500 {
            color: #94a3b8 !important;
        }

        .dark .text-gray-700 {
            color: #e2e8f0 !important;
        }

        .dark .text-gray-800 {
            color: #f1f5f9 !important;
        }

        .dark .text-navy {
            color: #e2e8f0 !important;
        }

        /* Chart.js brightness */
        .dark canvas {
            filter: brightness(1.1);
        }

        /* Teks di background terang (badge, tombol kuning) -> tetap gelap */
        .dark .bg-yellow .text-navy,
        .dark .bg-yellow.text-navy {
            color: #172554 !important;
        }

        /* ============ FIX: AVATAR INISIAL (bg-navy + text-white + rounded-full) ============ */
        /* Di dark mode, avatar jadi KUNING biar kontras dengan background gelap */
        .dark .bg-navy.text-white.rounded-full,
        .dark .bg-navy.text-white.rounded-lg {
            background-color: #F4C430 !important;
            color: #172554 !important;
        }

        /* Tabel: header biru muda -> lebih gelap */
        .dark .bg-blue-50 thead th,
        .dark thead.bg-blue-50 th {
            color: #94a3b8 !important;
        }

        .dark table tbody tr:hover {
            background-color: #1e293b !important;
        }

        /* Border tabel di dark mode */
        .dark .divide-gray> :not([hidden])~ :not([hidden]) {
            border-color: #334155 !important;
        }

        /* Input, select, textarea */
        .dark input,
        .dark select,
        .dark textarea {
            background-color: #1e293b !important;
            border-color: #334155 !important;
            color: #e2e8f0 !important;
        }

        .dark input::placeholder {
            color: #64748b !important;
        }

        .dark input:focus,
        .dark select:focus,
        .dark textarea:focus {
            border-color: #F4C430 !important;
            box-shadow: 0 0 0 1px #F4C430 !important;
        }

        /* Badge status transaksi (biar tetap kelihatan) */
        .dark .bg-blue-100 {
            background-color: #1e40af !important;
            color: #dbeafe !important;
        }

        .dark .bg-green-100 {
            background-color: #166534 !important;
            color: #dcfce7 !important;
        }

        .dark .bg-red-100 {
            background-color: #991b1b !important;
            color: #fee2e2 !important;
        }

        .dark .bg-yellow-100 {
            background-color: #854d0e !important;
            color: #fef9c3 !important;
        }

        /* Border berwarna di badge -> redup */
        .dark .border-blue-200 {
            border-color: #1e40af !important;
        }

        .dark .border-green-200 {
            border-color: #166534 !important;
        }

        .dark .border-red-200 {
            border-color: #991b1b !important;
        }

        .dark .border-yellow-200 {
            border-color: #854d0e !important;
        }

        /* Kartu / card */
        .dark .shadow-sm,
        .dark .shadow-md {
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.3) !important;
        }

        /* Header navbar */
        .dark header {
            background-color: #1e293b !important;
        }

        /* Modal */
        .dark .bg-slate-100 {
            background-color: #334155 !important;
        }

        /* Animasi modal */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .animate-fadeIn {
            animation: fadeIn 0.2s ease-out;
        }
    </style>
</head>

<body class="bg-[#F8FAFC] dark:bg-[#0f172a] text-navy antialiased min-h-screen">

    <!-- ============ OVERLAY MOBILE ============ -->
    <div id="mobileOverlay" onclick="closeSidebar()" class="fixed inset-0 bg-black/50 z-40 hidden md:hidden"></div>

    <!-- ============ SIDEBAR ============ -->
    <aside id="sidebar"
        class="w-[280px] h-screen flex flex-col fixed left-0 top-0 bg-white dark:bg-slate-900 border-r border-gray z-50 transform -translate-x-full md:translate-x-0 transition-transform duration-300">

        <!-- Logo & Nama (FIXED di atas) -->
        <div class="p-6 flex items-center gap-3 border-b border-gray shrink-0">
            <img alt="School Logo"
                class="w-10 h-10 object-contain rounded-md bg-slate-50 dark:bg-slate-800 p-1 border border-gray"
                src="{{ asset('images/smkn.png') }}">
            <div>
                <h1 class="text-base font-bold text-navy tracking-tight leading-tight">PARKIR SMKN 8 JEMBER</h1>
                <p class="text-[10px] text-slate-400 uppercase tracking-wider font-medium">Sistem Manajemen Parkir</p>
            </div>
            <button onclick="closeSidebar()" class="md:hidden ml-auto text-slate-500 hover:text-navy">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <!-- MENU (SCROLLABLE, flex-1) -->
        <nav class="flex-1 overflow-y-auto sidebar-scroll py-4 flex flex-col gap-1">
            <a class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                href="{{ route('dashboard') }}">
                <span class="material-symbols-outlined">dashboard</span>
                Dashboard
            </a>

            @if (auth()->check() && auth()->user()->isAdmin())
                <a class="sidebar-link {{ request()->routeIs('roles.*') ? 'active' : '' }}"
                    href="{{ route('roles.index') }}">
                    <span class="material-symbols-outlined">admin_panel_settings</span>
                    Manajemen Role
                </a>
            @endif

            @if (auth()->check() && auth()->user()->hasFeature('kelola_kendaraan'))
                <a class="sidebar-link {{ request()->routeIs('kendaraan.*') ? 'active' : '' }}"
                    href="{{ route('kendaraan.index') }}">
                    <span class="material-symbols-outlined">directions_car</span>
                    Kendaraan
                </a>
            @endif

            @if (auth()->check() && auth()->user()->hasFeature('crud_area'))
                <a class="sidebar-link {{ request()->routeIs('area.*') ? 'active' : '' }}"
                    href="{{ route('area.index') }}">
                    <span class="material-symbols-outlined">local_parking</span>
                    Area Parkir
                </a>
            @endif

            @if (auth()->check() && auth()->user()->hasFeature('crud_tarif'))
                <a class="sidebar-link {{ request()->routeIs('tarif.*') ? 'active' : '' }}"
                    href="{{ route('tarif.index') }}">
                    <span class="material-symbols-outlined">payments</span>
                    Tarif Parkir
                </a>
            @endif

            @if (auth()->check() && auth()->user()->hasFeature('transaksi'))
                <a class="sidebar-link {{ request()->routeIs('transaksi.*') ? 'active' : '' }}"
                    href="{{ route('transaksi.index') }}">
                    <span class="material-symbols-outlined">receipt_long</span>
                    Transaksi
                </a>
            @endif

            @if (auth()->check() && auth()->user()->hasFeature('rekap_laporan'))
                <a class="sidebar-link {{ request()->routeIs('laporan.*') ? 'active' : '' }}"
                    href="{{ route('laporan.index') }}">
                    <span class="material-symbols-outlined">assessment</span>
                    Laporan
                </a>
            @endif

            @if (auth()->check() && auth()->user()->hasFeature('crud_user'))
                <a class="sidebar-link {{ request()->routeIs('pengguna.*') ? 'active' : '' }}"
                    href="{{ route('pengguna.index') }}">
                    <span class="material-symbols-outlined">group</span>
                    Pengguna
                </a>
            @endif

            @if (auth()->check() && auth()->user()->hasFeature('akses_log'))
                <a class="sidebar-link {{ request()->routeIs('log_aktivitas.*') ? 'active' : '' }}"
                    href="{{ route('log_aktivitas.index') }}">
                    <span class="material-symbols-outlined">history</span>
                    Log Aktivitas
                </a>
            @endif
        </nav>

        <!-- FOOTER (FIXED di bawah, tidak ikut scroll) -->
        <div class="p-4 border-t border-gray flex flex-col gap-1 shrink-0">
            <a class="sidebar-link text-sm {{ request()->routeIs('profile.*') ? 'active' : '' }}"
                href="{{ route('profile.index') }}">
                <span class="material-symbols-outlined text-[20px]">account_circle</span>
                Profil Saya
            </a>
            <button type="button" onclick="openLogoutModal()"
                class="sidebar-link text-sm w-full text-left text-red-500 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-900/20">
                <span class="material-symbols-outlined text-[20px]">logout</span>
                Keluar
            </button>
        </div>
    </aside>

    <!-- ============ MAIN CONTENT ============ -->
    <div class="flex-1 md:ml-[280px] flex flex-col min-h-screen transition-all duration-300">
        <!-- TOP NAVBAR -->
        <header
            class="h-16 w-full flex justify-between items-center px-4 md:px-8 border-b border-gray sticky top-0 z-40 bg-white dark:bg-slate-900 shadow-sm">

            <div class="flex items-center gap-4">
                <button onclick="openSidebar()" class="md:hidden text-navy">
                    <span class="material-symbols-outlined">menu</span>
                </button>
                <div class="hidden md:block">
                    <span class="text-sm font-semibold text-navy">@yield('title', 'Dashboard')</span>
                </div>
            </div>

            <div
                class="hidden lg:flex items-center gap-2 px-4 py-2 bg-slate-50 dark:bg-slate-800 border border-gray rounded-lg">
                <span class="material-symbols-outlined text-navy text-[18px]">schedule</span>
                <div class="flex flex-col leading-none">
                    <span class="live-clock text-sm text-navy" id="liveTime">00:00:00</span>
                    <span class="text-[10px] text-slate-400" id="liveDate">Memuat tanggal...</span>
                </div>
            </div>

            <div class="flex items-center gap-2 md:gap-4">
                <button onclick="toggleDarkMode()" title="Ganti Tema"
                    class="text-slate-500 hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors p-2 rounded-full">
                    <span class="material-symbols-outlined text-[24px]" id="themeIcon">dark_mode</span>
                </button>

                @php
                    $unreadCount = 0;
                    if (auth()->check()) {
                        $unreadCount = App\Helpers\NotificationHelper::getUnreadCount(auth()->user()->id);
                    }
                @endphp

                <a href="{{ route('notifications.index') }}"
                    class="text-slate-500 hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors p-2 rounded-full relative">
                    <span class="material-symbols-outlined text-[24px]">notifications</span>
                    @if ($unreadCount > 0)
                        <span
                            class="absolute -top-0.5 -right-0.5 w-5 h-5 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center border-2 border-white dark:border-slate-900">
                            {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                        </span>
                    @endif
                </a>

                <div class="flex items-center gap-3">
                    <div class="hidden md:block text-right">
                        <p class="text-sm font-semibold text-navy">{{ auth()->user()->nama ?? 'User' }}</p>
                        <p class="text-xs text-slate-400 capitalize">{{ auth()->user()->role ?? '' }}</p>
                    </div>
                    <a href="{{ route('profile.index') }}"
                        class="w-9 h-9 rounded-full overflow-hidden border-2 border-yellow cursor-pointer hover:opacity-80 transition-opacity">
                        @if (auth()->user()->foto)
                            <img alt="Profile" class="w-full h-full object-cover"
                                src="{{ asset('storage/' . auth()->user()->foto) }}">
                        @else
                            <img alt="Profile" class="w-full h-full object-cover"
                                src="https://ui-avatars.com/api/?name={{ auth()->user()->nama ?? 'User' }}&background=F4C430&color=172554&size=36">
                        @endif
                    </a>
                </div>
            </div>
        </header>

        <main class="flex-1 p-4 md:p-8">
            @yield('content')
        </main>
    </div>

    <!-- ============ MODAL LOGOUT ============ -->
    <div id="logoutModal" class="fixed inset-0 z-[100] hidden items-center justify-center px-4">
        <div class="absolute inset-0 bg-black/50" onclick="closeLogoutModal()"></div>
        <div
            class="relative bg-white dark:bg-slate-800 rounded-2xl shadow-2xl max-w-md w-full p-6 z-10 animate-fadeIn">
            <div class="flex justify-center mb-4">
                <div class="w-16 h-16 rounded-full bg-red-100 flex items-center justify-center">
                    <span class="material-symbols-outlined text-red-500 text-4xl">logout</span>
                </div>
            </div>
            <h3 class="text-xl font-bold text-navy text-center mb-2">Konfirmasi Keluar</h3>
            <p class="text-sm text-slate-500 text-center mb-6">
                Apakah Anda yakin ingin keluar dari sistem? Anda perlu login kembali untuk mengakses aplikasi.
            </p>
            <div class="flex gap-3">
                <button type="button" onclick="closeLogoutModal()"
                    class="flex-1 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-200 font-bold py-2.5 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-600 transition-all">
                    Batal
                </button>
                <form method="POST" action="{{ route('logout') }}" class="flex-1">
                    @csrf
                    <button type="submit"
                        class="w-full bg-red-500 text-white font-bold py-2.5 rounded-lg hover:bg-red-600 transition-all flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">logout</span>
                        Ya, Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- ============ MODAL HAPUS (GLOBAL) ============ -->
    <div id="deleteModal" class="fixed inset-0 z-[100] hidden items-center justify-center px-4">
        <div class="absolute inset-0 bg-black/50" onclick="closeDeleteModal()"></div>
        <div
            class="relative bg-white dark:bg-slate-800 rounded-2xl shadow-2xl max-w-md w-full p-6 z-10 animate-fadeIn">
            <div class="flex justify-center mb-4">
                <div class="w-16 h-16 rounded-full bg-red-100 flex items-center justify-center">
                    <span class="material-symbols-outlined text-red-500 text-4xl">delete_forever</span>
                </div>
            </div>
            <h3 class="text-xl font-bold text-navy text-center mb-2">Konfirmasi Hapus</h3>
            <p class="text-sm text-slate-500 text-center mb-6" id="deleteMessage">
                Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak bisa dibatalkan.
            </p>
            <div class="flex gap-3">
                <button type="button" onclick="closeDeleteModal()"
                    class="flex-1 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-200 font-bold py-2.5 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-600 transition-all">
                    Batal
                </button>
                <button type="button" onclick="confirmDelete()"
                    class="flex-1 bg-red-500 text-white font-bold py-2.5 rounded-lg hover:bg-red-600 transition-all flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">delete</span>
                    Ya, Hapus
                </button>
            </div>
        </div>
    </div>

    <!-- ============ JAVASCRIPT ============ -->
    <script>
        // ============ LIVE CLOCK ============
        function updateClock() {
            const now = new Date();
            const hh = String(now.getHours()).padStart(2, '0');
            const mm = String(now.getMinutes()).padStart(2, '0');
            const ss = String(now.getSeconds()).padStart(2, '0');

            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const dayName = days[now.getDay()];

            const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September',
                'Oktober', 'November', 'Desember'
            ];
            const monthName = months[now.getMonth()];

            document.getElementById('liveTime').textContent = `${hh}:${mm}:${ss}`;
            document.getElementById('liveDate').textContent =
                `${dayName}, ${now.getDate()} ${monthName} ${now.getFullYear()}`;
        }
        updateClock();
        setInterval(updateClock, 1000);

        // ============ DARK MODE ============
        function toggleDarkMode() {
            const html = document.documentElement;
            const icon = document.getElementById('themeIcon');

            if (html.classList.contains('dark')) {
                html.classList.remove('dark');
                localStorage.setItem('theme', 'light');
                icon.textContent = 'dark_mode';
            } else {
                html.classList.add('dark');
                localStorage.setItem('theme', 'dark');
                icon.textContent = 'light_mode';
            }

            // Reload halaman biar chart update warna
            setTimeout(() => location.reload(), 100);
        }

        document.addEventListener('DOMContentLoaded', function() {
            const icon = document.getElementById('themeIcon');
            if (document.documentElement.classList.contains('dark')) {
                icon.textContent = 'light_mode';
            } else {
                icon.textContent = 'dark_mode';
            }
        });


        // ============ MOBILE SIDEBAR ============
        function openSidebar() {
            document.getElementById('sidebar').classList.remove('-translate-x-full');
            document.getElementById('mobileOverlay').classList.remove('hidden');
        }

        function closeSidebar() {
            document.getElementById('sidebar').classList.add('-translate-x-full');
            document.getElementById('mobileOverlay').classList.add('hidden');
        }

        // ============ MODAL LOGOUT ============
        function openLogoutModal() {
            const modal = document.getElementById('logoutModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeLogoutModal() {
            const modal = document.getElementById('logoutModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeLogoutModal();
        });

        // ============ MODAL HAPUS (GLOBAL) ============
        let formToDelete = null; // Menyimpan form yang akan di-submit

        function openDeleteModal(formElement, namaData = null) {
            formToDelete = formElement;
            const messageEl = document.getElementById('deleteMessage');

            if (namaData) {
                messageEl.textContent =
                    `Apakah Anda yakin ingin menghapus "${namaData}"? Tindakan ini tidak bisa dibatalkan.`;
            } else {
                messageEl.textContent = 'Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak bisa dibatalkan.';
            }

            const modal = document.getElementById('deleteModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeDeleteModal() {
            const modal = document.getElementById('deleteModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            formToDelete = null;
        }

        function confirmDelete() {
            if (formToDelete) {
                formToDelete.submit(); // Submit form hapus
            }
        }

        // ESC untuk tutup modal hapus
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeDeleteModal();
        });
    </script>
</body>

</html>
