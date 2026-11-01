{{--
  HALAMAN LOG AKTIVITAS
  - Tombol "Hapus Semua" (muncul kalau ada log)
  - Summary Cards: Total, Hari Ini, Pengguna Aktif, Total User
  - Filter: search, user, tanggal
  - Tabel log: Waktu, Pengguna (avatar), Aktivitas, Aksi
  - Variabel dari controller: $logs, $users, $totalLogs, $todayLogs, $activeUsers
--}}
@extends('layouts.dashboard')

@section('title', 'Log Aktivitas - Parkir SMKN 8 Jember')

@section('content')
<!-- HEADER: judul + tombol Hapus Semua -->
<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h1 class="text-2xl font-bold text-navy">Log Aktivitas</h1> {{-- Judul --}}
        <p class="text-sm text-slate-500 mt-1">Pantau dan lacak seluruh aktivitas pengguna dalam sistem.</p> {{-- Deskripsi --}}
    </div>

    @if($totalLogs > 0) {{-- Tombol hapus hanya muncul kalau ada log --}}
    <form method="POST" action="{{ route('log_aktivitas.destroy-all') }}"
          onsubmit="return confirm('Yakin ingin menghapus SEMUA log aktivitas ({{ $totalLogs }} data)? Tindakan ini tidak bisa dibatalkan.')"> {{-- Konfirmasi --}}
        @csrf
        @method('DELETE') {{-- Spoof method DELETE --}}
        <button type="submit" class="bg-red-500 text-white px-4 py-2.5 rounded-lg font-bold flex items-center gap-2 hover:bg-red-600 transition-all shadow-sm whitespace-nowrap">
            <span class="material-symbols-outlined text-sm">delete_forever</span>
            Hapus Semua
        </button>
    </form>
    @endif
</div>

<!-- ALERT: sukses hapus / aksi -->
@if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4 flex items-center gap-2">
        <span class="material-symbols-outlined text-green-500">check_circle</span>
        {{ session('success') }}
    </div>
@endif

<!-- SUMMARY CARDS: 4 kartu rekap -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <!-- Card 1: Total Aktivitas -->
    <div class="bg-white rounded-xl border border-gray p-6 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-slate-500 uppercase tracking-wider">Total Aktivitas</p> {{-- Label --}}
                <h3 class="text-2xl font-bold text-navy mt-1">{{ number_format($totalLogs) }}</h3> {{-- Jumlah semua log --}}
            </div>
            <div class="w-12 h-12 rounded-full bg-navy/10 flex items-center justify-center">
                <span class="material-symbols-outlined text-navy" style="font-variation-settings: 'FILL' 1;">list_alt</span> {{-- Ikon list --}}
            </div>
        </div>
    </div>

    <!-- Card 2: Aktivitas Hari Ini -->
    <div class="bg-white rounded-xl border border-gray p-6 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-slate-500 uppercase tracking-wider">Aktivitas Hari Ini</p>
                <h3 class="text-2xl font-bold text-navy mt-1">{{ number_format($todayLogs) }}</h3> {{-- Log hari ini --}}
            </div>
            <div class="w-12 h-12 rounded-full bg-yellow-500/10 flex items-center justify-center">
                <span class="material-symbols-outlined text-yellow-600" style="font-variation-settings: 'FILL' 1;">today</span> {{-- Ikon kalender --}}
            </div>
        </div>
    </div>

    <!-- Card 3: Pengguna Aktif -->
    <div class="bg-white rounded-xl border border-gray p-6 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-slate-500 uppercase tracking-wider">Pengguna Aktif</p>
                <h3 class="text-2xl font-bold text-navy mt-1">{{ number_format($activeUsers) }}</h3> {{-- Jumlah user aktif --}}
            </div>
            <div class="w-12 h-12 rounded-full bg-blue-500/10 flex items-center justify-center">
                <span class="material-symbols-outlined text-blue-600" style="font-variation-settings: 'FILL' 1;">group</span> {{-- Ikon group --}}
            </div>
        </div>
    </div>

    <!-- Card 4: Total Pengguna -->
    <div class="bg-white rounded-xl border border-gray p-6 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-slate-500 uppercase tracking-wider">Total Pengguna</p>
                <h3 class="text-2xl font-bold text-navy mt-1">{{ number_format(\App\Models\User::count()) }}</h3> {{-- Count user langsung dari model --}}
            </div>
            <div class="w-12 h-12 rounded-full bg-green-500/10 flex items-center justify-center">
                <span class="material-symbols-outlined text-green-600" style="font-variation-settings: 'FILL' 1;">person</span> {{-- Ikon person --}}
            </div>
        </div>
    </div>
</div>

<!-- FILTER: search + user + tanggal -->
<div class="bg-white rounded-xl border border-gray p-6 shadow-sm mb-6">
    <form method="GET" action="{{ route('log_aktivitas.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
        <!-- Search -->
        <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Cari</label>
            <input type="text" name="search" value="{{ request('search') }}" 
                   class="w-full bg-white border border-gray rounded-lg px-3 py-2 text-sm focus:border-navy focus:ring-1 focus:ring-navy outline-none transition-all"
                   placeholder="Cari aktivitas atau pengguna...">
        </div>
        <!-- Filter Pengguna -->
        <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Pengguna</label>
            <select name="user_id" class="w-full bg-white border border-gray rounded-lg px-3 py-2 text-sm focus:border-navy focus:ring-1 focus:ring-navy outline-none transition-all">
                <option value="">Semua Pengguna</option> {{-- Default --}}
                @foreach($users as $user) {{-- Loop user --}}
                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}> {{-- Selected kalau filter aktif --}}
                        {{ $user->nama }}
                    </option>
                @endforeach
            </select>
        </div>
        <!-- Filter Tanggal -->
        <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Tanggal</label>
            <input type="date" name="start_date" value="{{ request('start_date') }}" 
                   class="w-full bg-white border border-gray rounded-lg px-3 py-2 text-sm focus:border-navy focus:ring-1 focus:ring-navy outline-none transition-all">
        </div>
        <!-- Tombol Filter & Reset -->
        <div class="flex gap-2">
            <button type="submit" class="bg-yellow text-navy font-bold px-6 py-2 rounded-lg hover:bg-yellow/90 transition-all shadow-sm flex-1">Filter</button>
            <a href="{{ route('log_aktivitas.index') }}" class="bg-gray-100 text-slate-600 font-bold px-4 py-2 rounded-lg hover:bg-gray-200 transition-all">Reset</a>
        </div>
    </form>
</div>

<!-- TABLE: daftar log aktivitas -->
<div class="bg-white rounded-xl border border-gray overflow-hidden shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray">
                    <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Waktu</th> {{-- Kolom waktu --}}
                    <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Pengguna</th> {{-- Kolom pengguna --}}
                    <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Aktivitas</th> {{-- Kolom aktivitas --}}
                    <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">Aksi</th> {{-- Kolom aksi --}}
                </tr>
            </thead>
            <tbody class="divide-y divide-gray">
                @forelse($logs as $item) {{-- Loop data log --}}
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3 text-sm text-slate-500 whitespace-nowrap">
                        {{ $item->created_at->format('d/m/Y H:i') }} {{-- Tanggal & jam --}}
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            {{-- Avatar: pakai foto kalau ada, kalau tidak pakai inisial --}}
                            @if($item->user && $item->user->foto)
                                <img src="{{ asset('storage/' . $item->user->foto) }}" 
                                     alt="{{ $item->user->nama }}" 
                                     class="w-8 h-8 rounded-full object-cover border border-gray">
                            @else
                                <div class="w-8 h-8 rounded-full bg-navy text-white flex items-center justify-center text-xs font-bold">
                                    {{ strtoupper(substr($item->user->nama ?? '-', 0, 2)) }} {{-- 2 huruf pertama --}}
                                </div>
                            @endif
                            <span class="font-medium text-sm text-navy">{{ $item->user->nama ?? 'User Dihapus' }}</span> {{-- Nama user --}}
                        </div>
                    </td>
                    <td class="px-4 py-3 text-sm text-slate-600">
                        {{ $item->aktivitas }} {{-- Teks aktivitas --}}
                    </td>
                    <td class="px-4 py-3 text-center">
                        <a href="{{ route('log_aktivitas.show', $item->id) }}" class="text-slate-400 hover:text-navy transition-colors" title="Detail"> {{-- Tombol detail --}}
                            <span class="material-symbols-outlined text-sm">visibility</span>
                        </a>
                    </td>
                </tr>
                @empty
                <tr> {{-- Tampilan kalau data kosong --}}
                    <td colspan="4" class="px-4 py-8 text-center text-slate-500">
                        <span class="material-symbols-outlined text-4xl block mx-auto mb-2 text-slate-300">history</span>
                        <p class="text-sm">Belum ada aktivitas tercatat.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- PAGINATION -->
    <div class="px-4 py-3 border-t border-gray flex flex-col sm:flex-row justify-between items-center gap-3 bg-white">
        <span class="text-xs text-slate-500">Menampilkan {{ $logs->firstItem() ?? 0 }}-{{ $logs->lastItem() ?? 0 }} dari {{ $logs->total() }} data</span> {{-- Info range --}}
        <div class="flex gap-1">
            {{ $logs->links() }} {{-- Link pagination --}}
        </div>
    </div>
</div>
@endsection