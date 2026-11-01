{{-- Sama seperti layouts/dashboard, tapi avatar profile hanya pakai ui-avatars --}}
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Parkir SMKN 8 Jember')</title>

    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

    <style>
        /* Style sama dengan layouts/dashboard */
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; color: #475569; }
        .text-navy { color: #172554; } .bg-navy { background-color: #172554; }
        .text-yellow { color: #F4C430; } .bg-yellow { background-color: #F4C430; }
        .border-gray { border-color: #E2E8F0; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        .sidebar-link { display: flex; align-items: center; gap: 12px; padding: 10px 16px; margin: 0 8px; border-radius: 8px; font-size: 14px; font-weight: 600; transition: all 0.2s; color: rgba(255, 255, 255, 0.6); }
        .sidebar-link:hover { color: white; background: rgba(255, 255, 255, 0.1); }
        .sidebar-link.active { background: #F4C430; color: #172554; transform: scale(0.95); }
        .sidebar-link.active .material-symbols-outlined { font-variation-settings: 'FILL' 1; }
        .sidebar-link .material-symbols-outlined { font-size: 22px; }
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.2); border-radius: 4px; }
    </style>
</head>

<body class="bg-[#F8FAFC] text-navy antialiased min-h-screen flex">
    <aside class="w-[280px] h-screen flex flex-col fixed left-0 top-0 bg-navy z-50 hidden md:flex">
        {{-- Logo --}}
        <div class="p-6 flex items-center gap-3 border-b border-white/10">
            <img alt="School Logo" class="w-10 h-10 object-contain rounded-md bg-white p-1" src="{{ asset('images/smkn.png') }}">
            <div>
                <h1 class="text-lg font-bold text-white tracking-tight">PARKIR SMKN 8 JEMBER</h1>
                <p class="text-[10px] text-white/70 uppercase tracking-wider font-medium">Sistem Manajemen Parkir</p>
            </div>
        </div>

        {{-- MENU: SETIAP MENU DIBUNGKUS CEK PERMISSION --}}
        <nav class="flex-1 overflow-y-auto py-4 flex flex-col gap-1">
            <a class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                <span class="material-symbols-outlined">dashboard</span>
                Dashboard
            </a>
            @if(auth()->check() && (auth()->user()->isOwner() || auth()->user()->isAdmin()))
            <a class="sidebar-link {{ request()->routeIs('roles.*') ? 'active' : '' }}" href="{{ route('roles.index') }}">
                <span class="material-symbols-outlined">admin_panel_settings</span>
                Manajemen Role
            </a>
            @endif
            @if(auth()->check() && auth()->user()->hasFeature('kelola_kendaraan'))
            <a class="sidebar-link {{ request()->routeIs('kendaraan.*') ? 'active' : '' }}" href="{{ route('kendaraan.index') }}">
                <span class="material-symbols-outlined">directions_car</span>
                Kendaraan
            </a>
            @endif
            @if(auth()->check() && auth()->user()->hasFeature('crud_area'))
            <a class="sidebar-link {{ request()->routeIs('area.*') ? 'active' : '' }}" href="{{ route('area.index') }}">
                <span class="material-symbols-outlined">local_parking</span>
                Area Parkir
            </a>
            @endif
            @if(auth()->check() && auth()->user()->hasFeature('crud_tarif'))
            <a class="sidebar-link {{ request()->routeIs('tarif.*') ? 'active' : '' }}" href="{{ route('tarif.index') }}">
                <span class="material-symbols-outlined">payments</span>
                Tarif Parkir
            </a>
            @endif
            @if(auth()->check() && auth()->user()->hasFeature('transaksi'))
            <a class="sidebar-link {{ request()->routeIs('transaksi.*') ? 'active' : '' }}" href="{{ route('transaksi.index') }}">
                <span class="material-symbols-outlined">receipt_long</span>
                Transaksi
            </a>
            @endif
            @if(auth()->check() && auth()->user()->hasFeature('rekap_laporan'))
            <a class="sidebar-link {{ request()->routeIs('laporan.*') ? 'active' : '' }}" href="{{ route('laporan.index') }}">
                <span class="material-symbols-outlined">assessment</span>
                Laporan
            </a>
            @endif
            @if(auth()->check() && auth()->user()->hasFeature('crud_user'))
            <a class="sidebar-link {{ request()->routeIs('pengguna.*') ? 'active' : '' }}" href="{{ route('pengguna.index') }}">
                <span class="material-symbols-outlined">group</span>
                Pengguna
            </a>
            @endif
            @if(auth()->check() && auth()->user()->hasFeature('akses_log'))
            <a class="sidebar-link {{ request()->routeIs('log_aktivitas.*') ? 'active' : '' }}" href="{{ route('log_aktivitas.index') }}">
                <span class="material-symbols-outlined">history</span>
                Log Aktivitas
            </a>
            @endif
        </nav>

        {{-- Footer: profil & logout --}}
        <div class="p-4 border-t border-white/10 flex flex-col gap-1">
            <a class="sidebar-link text-sm {{ request()->routeIs('profile.*') ? 'active' : '' }}" href="{{ route('profile.index') }}">
                <span class="material-symbols-outlined text-[20px]">account_circle</span>
                Profil Saya
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="sidebar-link text-sm w-full text-left text-red-400 hover:text-red-300 hover:bg-red-500/10">
                    <span class="material-symbols-outlined text-[20px]">logout</span>
                    Keluar
                </button>
            </form>
        </div>
    </aside>

    {{-- MAIN CONTENT --}}
    <div class="flex-1 ml-[280px] flex flex-col min-h-screen">
        <header class="h-16 w-full flex justify-between items-center px-4 md:px-8 border-b border-gray sticky top-0 z-40 bg-white shadow-sm">
            <div class="flex items-center gap-4">
                <button class="md:hidden text-navy"><span class="material-symbols-outlined">menu</span></button>
                <div class="hidden md:flex items-center">
                    <form method="GET" action="{{ route('search') }}" class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">search</span>
                        <input type="text" name="q" value="{{ request('q') }}" class="pl-10 pr-16 py-2 border border-gray/50 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-yellow/50 focus:border-yellow w-64 bg-white/50 transition-all" placeholder="Cari kendaraan, transaksi..." autocomplete="off">
                        <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 text-xs text-white bg-navy hover:bg-navy/80 px-3 py-1 rounded-md transition-colors">Cari</button>
                    </form>
                </div>
            </div>

            <div class="flex items-center gap-4">
                @php
                    $unreadCount = 0;
                    if (auth()->check()) {
                        $unreadCount = App\Helpers\NotificationHelper::getUnreadCount(auth()->user()->id);
                    }
                @endphp
                <a href="{{ route('notifications.index') }}" class="text-slate-500 hover:bg-gray-50 transition-colors p-2 rounded-full relative">
                    <span class="material-symbols-outlined text-[24px]">notifications</span>
                    @if($unreadCount > 0)
                        <span class="absolute -top-0.5 -right-0.5 w-5 h-5 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center border-2 border-white">
                            {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                        </span>
                    @endif
                </a>
                <div class="flex items-center gap-3">
                    <div class="hidden md:block text-right">
                        <p class="text-sm font-semibold text-navy">{{ auth()->user()->nama ?? 'User' }}</p>
                        <p class="text-xs text-slate-400 capitalize">{{ auth()->user()->role ?? '' }}</p>
                    </div>
                    {{-- Avatar pakai ui-avatars (tidak ngecek foto) --}}
                    <a href="{{ route('profile.index') }}" class="w-9 h-9 rounded-full overflow-hidden border-2 border-yellow cursor-pointer hover:opacity-80 transition-opacity">
                        <img alt="Profile" class="w-full h-full object-cover" src="https://ui-avatars.com/api/?name={{ auth()->user()->nama ?? 'User' }}&background=F4C430&color=172554&size=36">
                    </a>
                </div>
            </div>
        </header>

        <main class="flex-1 p-4 md:p-8">
            @yield('content')
        </main>
    </div>
</body>
</html>