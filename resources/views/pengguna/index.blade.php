{{--
  HALAMAN DAFTAR PENGGUNA
  - Tombol Tambah Pengguna
  - Summary Cards: Total, Admin, Petugas, Owner
  - Tabel: Pengguna (avatar), Username, Role, Dibuat, Aksi
  - Style tabel disamakan dengan halaman lain (header biru, padding lega)
  - Variabel dari controller: $users, $totalUsers, $totalAdmin, $totalPetugas, $totalOwner
--}}
@extends('layouts.dashboard')

@section('title', 'Pengguna - Parkir SMKN 8 Jember')

@section('content')
<!-- HEADER: judul + tombol tambah -->
<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h1 class="text-2xl font-bold text-navy">Pengguna</h1>
        <p class="text-sm text-slate-500 mt-1">Kelola akun dan hak akses pengguna sistem parkir.</p>
    </div>
    <a href="{{ route('pengguna.create') }}" class="bg-yellow text-navy px-6 py-2.5 rounded-lg font-bold flex items-center gap-2 hover:bg-yellow/90 transition-all shadow-sm">
        <span class="material-symbols-outlined text-[18px]">add</span>
        Tambah Pengguna
    </a>
</div>

<!-- ALERT: sukses & error -->
@if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4 flex items-center gap-2">
        <span class="material-symbols-outlined text-green-500">check_circle</span>
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4 flex items-center gap-2">
        <span class="material-symbols-outlined text-red-500">error</span>
        {{ session('error') }}
    </div>
@endif

<!-- SUMMARY CARDS -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl border border-gray p-6 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-slate-500 uppercase tracking-wider">Total Pengguna</p>
                <h3 class="text-2xl font-bold text-navy mt-1">{{ $totalUsers }}</h3>
            </div>
            <div class="w-12 h-12 rounded-full bg-navy/10 flex items-center justify-center">
                <span class="material-symbols-outlined text-navy" style="font-variation-settings: 'FILL' 1;">group</span>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-gray p-6 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-slate-500 uppercase tracking-wider">Administrator</p>
                <h3 class="text-2xl font-bold text-navy mt-1">{{ $totalAdmin }}</h3>
            </div>
            <div class="w-12 h-12 rounded-full bg-navy/10 flex items-center justify-center">
                <span class="material-symbols-outlined text-navy" style="font-variation-settings: 'FILL' 1;">admin_panel_settings</span>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-gray p-6 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-slate-500 uppercase tracking-wider">Petugas</p>
                <h3 class="text-2xl font-bold text-blue-600 mt-1">{{ $totalPetugas }}</h3>
            </div>
            <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center">
                <span class="material-symbols-outlined text-blue-600" style="font-variation-settings: 'FILL' 1;">badge</span>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-gray p-6 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-slate-500 uppercase tracking-wider">Owner</p>
                <h3 class="text-2xl font-bold text-yellow-600 mt-1">{{ $totalOwner }}</h3>
            </div>
            <div class="w-12 h-12 rounded-full bg-yellow-50 flex items-center justify-center">
                <span class="material-symbols-outlined text-yellow-600" style="font-variation-settings: 'FILL' 1;">verified</span>
            </div>
        </div>
    </div>
</div>

<!-- TABLE: daftar pengguna -->
<div class="bg-white rounded-xl border border-gray overflow-hidden shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-blue-50 border-b border-gray">
                    <th class="py-4 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider">Pengguna</th>
                    <th class="py-4 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider">Username</th>
                    <th class="py-4 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider">Role</th>
                    <th class="py-4 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider">Dibuat Pada</th>
                    <th class="py-4 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray">
                @forelse($users as $item)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="py-4 px-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-navy text-white flex items-center justify-center font-bold text-sm">
                                {{ strtoupper(substr($item->nama, 0, 2)) }}
                            </div>
                            <span class="font-medium text-sm text-navy">{{ $item->nama }}</span>
                        </div>
                    </td>
                    <td class="py-4 px-6 text-sm text-slate-500">{{ $item->username }}</td>
                    <td class="py-4 px-6">
                        @if($item->role == 'admin')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-navy/10 text-navy">Administrator</span>
                        @elseif($item->role == 'petugas')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-600">Petugas</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-yellow-50 text-yellow-600">Owner</span>
                        @endif
                    </td>
                    <td class="py-4 px-6 text-sm text-slate-500">{{ $item->created_at->format('d M Y') }}</td>
                    <td class="py-4 px-6 text-right">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('pengguna.edit', $item->id) }}" class="text-slate-500 hover:text-navy p-1 transition-colors" title="Edit">
                                <span class="material-symbols-outlined text-[20px]">edit</span>
                            </a>
                            @if($item->id != auth()->user()->id)
                            <form method="POST" action="{{ route('pengguna.destroy', $item->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-slate-500 hover:text-red-500 p-1 transition-colors" title="Hapus">
                                    <span class="material-symbols-outlined text-[20px]">delete</span>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-8 text-center text-slate-500">
                        <span class="material-symbols-outlined text-5xl block mx-auto mb-3 text-slate-300">group</span>
                        <p class="text-sm">Belum ada pengguna.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- PAGINATION -->
    <div class="px-6 py-4 border-t border-gray flex flex-col sm:flex-row justify-between items-center gap-3 bg-white">
        <span class="text-xs text-slate-500">Menampilkan {{ $users->firstItem() ?? 0 }}-{{ $users->lastItem() ?? 0 }} dari {{ $users->total() }} pengguna</span>
        <div class="flex gap-1">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection