{{--
  HALAMAN DAFTAR PENGGUNA
  - Tombol Tambah Pengguna
  - Summary Cards: Total, Admin, Petugas, Owner
  - Tabel: Pengguna (avatar), Username, Role, Dibuat, Aksi
  - Style tabel disamakan dengan halaman lain (header biru, padding lega)
  - Variabel dari controller: $users, $totalUsers, $totalAdmin, $totalPetugas, $totalOwner
--}}
@extends('layouts.dashboard') {{-- Menggunakan layout dashboard --}}

@section('title', 'Pengguna - Parkir SMKN 8 Jember') {{-- Judul halaman --}}

@section('content') {{-- Bagian konten --}}
<!-- HEADER: judul + tombol tambah -->
<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4"> {{-- Header responsif --}}
    <div>
        <h1 class="text-2xl font-bold text-navy">Pengguna</h1> {{-- Judul --}}
        <p class="text-sm text-slate-500 mt-1">Kelola akun dan hak akses pengguna sistem parkir.</p> {{-- Subjudul --}}
    </div>
    <a href="{{ route('pengguna.create') }}" class="bg-yellow text-navy px-6 py-2.5 rounded-lg font-bold flex items-center gap-2 hover:bg-yellow/90 transition-all shadow-sm"> {{-- Tombol tambah --}}
        <span class="material-symbols-outlined text-[18px]">add</span> {{-- Ikon tambah --}}
        Tambah Pengguna {{-- Label --}}
    </a>
</div>

<!-- ALERT: sukses & error -->
@if(session('success')) {{-- Jika ada session sukses --}}
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4 flex items-center gap-2"> {{-- Alert hijau --}}
        <span class="material-symbols-outlined text-green-500">check_circle</span> {{-- Ikon centang --}}
        {{ session('success') }} {{-- Pesan sukses --}}
    </div>
@endif

@if(session('error')) {{-- Jika ada session error --}}
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4 flex items-center gap-2"> {{-- Alert merah --}}
        <span class="material-symbols-outlined text-red-500">error</span> {{-- Ikon error --}}
        {{ session('error') }} {{-- Pesan error --}}
    </div>
@endif

<!-- SUMMARY CARDS -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6"> {{-- Grid 1/2/4 kolom --}}
    <div class="bg-white rounded-xl border border-gray p-6 shadow-sm"> {{-- Card total user --}}
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-slate-500 uppercase tracking-wider">Total Pengguna</p> {{-- Label --}}
                <h3 class="text-2xl font-bold text-navy mt-1">{{ $totalUsers }}</h3> {{-- Nilai --}}
            </div>
            <div class="w-12 h-12 rounded-full bg-navy/10 flex items-center justify-center"> {{-- Container ikon --}}
                <span class="material-symbols-outlined text-navy" style="font-variation-settings: 'FILL' 1;">group</span> {{-- Ikon group --}}
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-gray p-6 shadow-sm"> {{-- Card admin --}}
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-slate-500 uppercase tracking-wider">Administrator</p> {{-- Label --}}
                <h3 class="text-2xl font-bold text-navy mt-1">{{ $totalAdmin }}</h3> {{-- Nilai --}}
            </div>
            <div class="w-12 h-12 rounded-full bg-navy/10 flex items-center justify-center"> {{-- Container ikon --}}
                <span class="material-symbols-outlined text-navy" style="font-variation-settings: 'FILL' 1;">admin_panel_settings</span> {{-- Ikon admin --}}
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-gray p-6 shadow-sm"> {{-- Card petugas --}}
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-slate-500 uppercase tracking-wider">Petugas</p> {{-- Label --}}
                <h3 class="text-2xl font-bold text-blue-600 mt-1">{{ $totalPetugas }}</h3> {{-- Nilai --}}
            </div>
            <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center"> {{-- Container ikon --}}
                <span class="material-symbols-outlined text-blue-600" style="font-variation-settings: 'FILL' 1;">badge</span> {{-- Ikon badge --}}
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-gray p-6 shadow-sm"> {{-- Card owner --}}
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-slate-500 uppercase tracking-wider">Owner</p> {{-- Label --}}
                <h3 class="text-2xl font-bold text-yellow-600 mt-1">{{ $totalOwner }}</h3> {{-- Nilai --}}
            </div>
            <div class="w-12 h-12 rounded-full bg-yellow-50 flex items-center justify-center"> {{-- Container ikon --}}
                <span class="material-symbols-outlined text-yellow-600" style="font-variation-settings: 'FILL' 1;">verified</span> {{-- Ikon verified --}}
            </div>
        </div>
    </div>
</div>

<!-- TABLE: daftar pengguna -->
<div class="bg-white rounded-xl border border-gray overflow-hidden shadow-sm"> {{-- Container tabel --}}
    <div class="overflow-x-auto"> {{-- Scroll horizontal mobile --}}
        <table class="w-full text-left border-collapse"> {{-- Tabel --}}
            <thead>
                <tr class="bg-blue-50 border-b border-gray"> {{-- Baris header --}}
                    <th class="py-4 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider">Pengguna</th> {{-- Kolom pengguna --}}
                    <th class="py-4 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider">Username</th> {{-- Kolom username --}}
                    <th class="py-4 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider">Role</th> {{-- Kolom role --}}
                    <th class="py-4 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider">Dibuat Pada</th> {{-- Kolom tanggal --}}
                    <th class="py-4 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Aksi</th> {{-- Kolom aksi --}}
                </tr>
            </thead>
            <tbody class="divide-y divide-gray"> {{-- Body tabel --}}
                @forelse($users as $item) {{-- Loop data user --}}
                <tr class="hover:bg-gray-50 transition-colors"> {{-- Baris data --}}
                    <td class="py-4 px-6">
                        <div class="flex items-center gap-3"> {{-- Layout horizontal --}}
                            <div class="w-10 h-10 rounded-full bg-navy text-white flex items-center justify-center font-bold text-sm"> {{-- Avatar inisial --}}
                                {{ strtoupper(substr($item->nama, 0, 2)) }} {{-- 2 huruf pertama nama --}}
                            </div>
                            <span class="font-medium text-sm text-navy">{{ $item->nama }}</span> {{-- Nama --}}
                        </div>
                    </td>
                    <td class="py-4 px-6 text-sm text-slate-500">{{ $item->username }}</td> {{-- Username --}}
                    <td class="py-4 px-6"> {{-- Kolom role --}}
                        @if($item->role == 'admin')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-navy/10 text-navy">Administrator</span> {{-- Badge admin --}}
                        @elseif($item->role == 'petugas')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-600">Petugas</span> {{-- Badge petugas --}}
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-yellow-50 text-yellow-600">Owner</span> {{-- Badge owner --}}
                        @endif
                    </td>
                    <td class="py-4 px-6 text-sm text-slate-500">{{ $item->created_at->format('d M Y') }}</td> {{-- Tanggal dibuat --}}
                    <td class="py-4 px-6 text-right"> {{-- Kolom aksi --}}
                        <div class="flex justify-end gap-2"> {{-- Container tombol --}}
                            <a href="{{ route('pengguna.edit', $item->id) }}" class="text-slate-500 hover:text-navy p-1 transition-colors" title="Edit"> {{-- Tombol edit --}}
                                <span class="material-symbols-outlined text-[20px]">edit</span> {{-- Ikon edit --}}
                            </a>
                            @if($item->id != auth()->user()->id) {{-- Cegah hapus akun sendiri --}}
                            <form method="POST" action="{{ route('pengguna.destroy', $item->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')"> {{-- Form hapus --}}
                                @csrf {{-- Token CSRF --}}
                                @method('DELETE') {{-- Spoof method DELETE --}}
                                <button type="submit" class="text-slate-500 hover:text-red-500 p-1 transition-colors" title="Hapus"> {{-- Tombol hapus --}}
                                    <span class="material-symbols-outlined text-[20px]">delete</span> {{-- Ikon hapus --}}
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr> {{-- Empty state --}}
                    <td colspan="5" class="py-8 text-center text-slate-500">
                        <span class="material-symbols-outlined text-5xl block mx-auto mb-3 text-slate-300">group</span> {{-- Ikon besar --}}
                        <p class="text-sm">Belum ada pengguna.</p> {{-- Pesan --}}
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- PAGINATION -->
    <div class="px-6 py-4 border-t border-gray flex flex-col sm:flex-row justify-between items-center gap-3 bg-white"> {{-- Container pagination --}}
        <span class="text-xs text-slate-500">Menampilkan {{ $users->firstItem() ?? 0 }}-{{ $users->lastItem() ?? 0 }} dari {{ $users->total() }} pengguna</span> {{-- Info range --}}
        <div class="flex gap-1">
            {{ $users->links() }} {{-- Link pagination --}}
        </div>
    </div>
</div>
@endsection