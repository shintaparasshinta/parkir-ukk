{{--
  HALAMAN LOG AKTIVITAS
  - Tombol "Hapus Semua" (muncul kalau ada log)
  - Summary Cards: Total, Hari Ini, Pengguna Aktif, Total User
  - Filter: search, user, tanggal
  - Tabel log: Waktu, Pengguna (avatar), Aktivitas, Aksi
  - Variabel dari controller: $logs, $users, $totalLogs, $todayLogs, $activeUsers
--}}
@extends('layouts.dashboard') {{-- Menggunakan layout dashboard --}}

@section('title', 'Log Aktivitas - Parkir SMKN 8 Jember') {{-- Judul halaman --}}

@section('content') {{-- Bagian konten --}}
<!-- HEADER: judul + tombol Hapus Semua -->
<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4"> {{-- Header responsif --}}
    <div>
        <h1 class="text-2xl font-bold text-navy">Log Aktivitas</h1> {{-- Judul --}}
        <p class="text-sm text-slate-500 mt-1">Pantau dan lacak seluruh aktivitas pengguna dalam sistem.</p> {{-- Deskripsi --}}
    </div>

    @if($totalLogs > 0) {{-- Tombol hapus hanya muncul kalau ada log --}}
    <form method="POST" action="{{ route('log_aktivitas.destroy-all') }}"
          onsubmit="return confirm('Yakin ingin menghapus SEMUA log aktivitas ({{ $totalLogs }} data)? Tindakan ini tidak bisa dibatalkan.')"> {{-- Konfirmasi JS --}}
        @csrf {{-- Token CSRF --}}
        @method('DELETE') {{-- Spoof method DELETE --}}
        <button type="submit" class="bg-red-500 text-white px-4 py-2.5 rounded-lg font-bold flex items-center gap-2 hover:bg-red-600 transition-all shadow-sm whitespace-nowrap"> {{-- Tombol hapus --}}
            <span class="material-symbols-outlined text-sm">delete_forever</span> {{-- Ikon hapus --}}
            Hapus Semua {{-- Label --}}
        </button>
    </form>
    @endif
</div>

<!-- ALERT: sukses hapus / aksi -->
@if(session('success')) {{-- Jika ada session sukses --}}
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4 flex items-center gap-2"> {{-- Alert hijau --}}
        <span class="material-symbols-outlined text-green-500">check_circle</span> {{-- Ikon centang --}}
        {{ session('success') }} {{-- Pesan --}}
    </div>
@endif

<!-- SUMMARY CARDS: 4 kartu rekap -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6"> {{-- Grid 1/2/4 kolom --}}
    <!-- Card 1: Total Aktivitas -->
    <div class="bg-white rounded-xl border border-gray p-6 shadow-sm"> {{-- Card total --}}
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-slate-500 uppercase tracking-wider">Total Aktivitas</p> {{-- Label --}}
                <h3 class="text-2xl font-bold text-navy mt-1">{{ number_format($totalLogs) }}</h3> {{-- Jumlah semua log --}}
            </div>
            <div class="w-12 h-12 rounded-full bg-navy/10 flex items-center justify-center"> {{-- Container ikon --}}
                <span class="material-symbols-outlined text-navy" style="font-variation-settings: 'FILL' 1;">list_alt</span> {{-- Ikon list --}}
            </div>
        </div>
    </div>

    <!-- Card 2: Aktivitas Hari Ini -->
    <div class="bg-white rounded-xl border border-gray p-6 shadow-sm"> {{-- Card hari ini --}}
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-slate-500 uppercase tracking-wider">Aktivitas Hari Ini</p> {{-- Label --}}
                <h3 class="text-2xl font-bold text-navy mt-1">{{ number_format($todayLogs) }}</h3> {{-- Log hari ini --}}
            </div>
            <div class="w-12 h-12 rounded-full bg-yellow-500/10 flex items-center justify-center"> {{-- Container ikon --}}
                <span class="material-symbols-outlined text-yellow-600" style="font-variation-settings: 'FILL' 1;">today</span> {{-- Ikon kalender --}}
            </div>
        </div>
    </div>

    <!-- Card 3: Pengguna Aktif -->
    <div class="bg-white rounded-xl border border-gray p-6 shadow-sm"> {{-- Card user aktif --}}
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-slate-500 uppercase tracking-wider">Pengguna Aktif</p> {{-- Label --}}
                <h3 class="text-2xl font-bold text-navy mt-1">{{ number_format($activeUsers) }}</h3> {{-- Jumlah user aktif --}}
            </div>
            <div class="w-12 h-12 rounded-full bg-blue-500/10 flex items-center justify-center"> {{-- Container ikon --}}
                <span class="material-symbols-outlined text-blue-600" style="font-variation-settings: 'FILL' 1;">group</span> {{-- Ikon group --}}
            </div>
        </div>
    </div>

    <!-- Card 4: Total Pengguna -->
    <div class="bg-white rounded-xl border border-gray p-6 shadow-sm"> {{-- Card total user --}}
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-slate-500 uppercase tracking-wider">Total Pengguna</p> {{-- Label --}}
                <h3 class="text-2xl font-bold text-navy mt-1">{{ number_format(\App\Models\User::count()) }}</h3> {{-- Count user langsung dari model --}}
            </div>
            <div class="w-12 h-12 rounded-full bg-green-500/10 flex items-center justify-center"> {{-- Container ikon --}}
                <span class="material-symbols-outlined text-green-600" style="font-variation-settings: 'FILL' 1;">person</span> {{-- Ikon person --}}
            </div>
        </div>
    </div>
</div>

<!-- FILTER: search + user + tanggal -->
<div class="bg-white rounded-xl border border-gray p-6 shadow-sm mb-6"> {{-- Card filter --}}
    <form method="GET" action="{{ route('log_aktivitas.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end"> {{-- Form GET --}}
        <!-- Search -->
        <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Cari</label> {{-- Label --}}
            <input type="text" name="search" value="{{ request('search') }}" 
                   class="w-full bg-white border border-gray rounded-lg px-3 py-2 text-sm focus:border-navy focus:ring-1 focus:ring-navy outline-none transition-all"
                   placeholder="Cari aktivitas atau pengguna..."> {{-- Input search --}}
        </div>
        <!-- Filter Pengguna -->
        <div>
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Pengguna</label> {{-- Label --}}
            <select name="user_id" class="w-full bg-white border border-gray rounded-lg px-3 py-2 text-sm focus:border-navy focus:ring-1 focus:ring-navy outline-none transition-all"> {{-- Select user --}}
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
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Tanggal</label> {{-- Label --}}
            <input type="date" name="start_date" value="{{ request('start_date') }}" 
                   class="w-full bg-white border border-gray rounded-lg px-3 py-2 text-sm focus:border-navy focus:ring-1 focus:ring-navy outline-none transition-all"> {{-- Input tanggal --}}
        </div>
        <!-- Tombol Filter & Reset -->
        <div class="flex gap-2"> {{-- Container tombol --}}
            <button type="submit" class="bg-yellow text-navy font-bold px-6 py-2 rounded-lg hover:bg-yellow/90 transition-all shadow-sm flex-1">Filter</button> {{-- Tombol filter --}}
            <a href="{{ route('log_aktivitas.index') }}" class="bg-gray-100 text-slate-600 font-bold px-4 py-2 rounded-lg hover:bg-gray-200 transition-all">Reset</a> {{-- Tombol reset --}}
        </div>
    </form>
</div>

<!-- TABLE: daftar log aktivitas -->
<div class="bg-white rounded-xl border border-gray overflow-hidden shadow-sm"> {{-- Container tabel --}}
    <div class="overflow-x-auto"> {{-- Scroll horizontal mobile --}}
        <table class="w-full text-left border-collapse"> {{-- Tabel --}}
            <thead>
                <tr class="bg-gray-50 border-b border-gray"> {{-- Baris header --}}
                    <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Waktu</th> {{-- Kolom waktu --}}
                    <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Pengguna</th> {{-- Kolom pengguna --}}
                    <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Aktivitas</th> {{-- Kolom aktivitas --}}
                    <th class="px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">Aksi</th> {{-- Kolom aksi --}}
                </tr>
            </thead>
            <tbody class="divide-y divide-gray"> {{-- Body tabel --}}
                @forelse($logs as $item) {{-- Loop data log --}}
                <tr class="hover:bg-gray-50 transition-colors"> {{-- Baris data --}}
                    <td class="px-4 py-3 text-sm text-slate-500 whitespace-nowrap">
                        {{ $item->created_at->format('d/m/Y H:i') }} {{-- Tanggal & jam --}}
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3"> {{-- Layout horizontal --}}
                            {{-- Avatar: pakai foto kalau ada, kalau tidak pakai inisial --}}
                            @if($item->user && $item->user->foto)
                                <img src="{{ asset('storage/' . $item->user->foto) }}" 
                                     alt="{{ $item->user->nama }}" 
                                     class="w-8 h-8 rounded-full object-cover border border-gray"> {{-- Foto user --}}
                            @else
                                <div class="w-8 h-8 rounded-full bg-navy text-white flex items-center justify-center text-xs font-bold"> {{-- Avatar inisial --}}
                                    {{ strtoupper(substr($item->user->nama ?? '-', 0, 2)) }} {{-- 2 huruf pertama --}}
                                </div>
                            @endif
                            <span class="font-medium text-sm text-navy">{{ $item->user->nama ?? 'User Dihapus' }}</span> {{-- Nama user --}}
                        </div>
                    </td>
                    <td class="px-4 py-3 text-sm text-slate-600">
                        {{ $item->aktivitas }} {{-- Teks aktivitas --}}
                    </td>
                    <td class="px-4 py-3 text-center"> {{-- Kolom aksi --}}
                        <a href="{{ route('log_aktivitas.show', $item->id) }}" class="text-slate-400 hover:text-navy transition-colors" title="Detail"> {{-- Tombol detail --}}
                            <span class="material-symbols-outlined text-sm">visibility</span> {{-- Ikon mata --}}
                        </a>
                    </td>
                </tr>
                @empty
                <tr> {{-- Tampilan kalau data kosong --}}
                    <td colspan="4" class="px-4 py-8 text-center text-slate-500"> {{-- Empty state --}}
                        <span class="material-symbols-outlined text-4xl block mx-auto mb-2 text-slate-300">history</span> {{-- Ikon besar --}}
                        <p class="text-sm">Belum ada aktivitas tercatat.</p> {{-- Pesan --}}
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- PAGINATION -->
    <div class="px-4 py-3 border-t border-gray flex flex-col sm:flex-row justify-between items-center gap-3 bg-white"> {{-- Container pagination --}}
        <span class="text-xs text-slate-500">Menampilkan {{ $logs->firstItem() ?? 0 }}-{{ $logs->lastItem() ?? 0 }} dari {{ $logs->total() }} data</span> {{-- Info range --}}
        <div class="flex gap-1">
            {{ $logs->links() }} {{-- Link pagination --}}
        </div>
    </div>
</div>
@endsection