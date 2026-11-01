{{-- 
  HALAMAN DAFTAR KENDARAAN
  - FIX: plat nomor tidak wrap di mobile (whitespace-nowrap)
  - Table bisa di-scroll horizontal kalau layar kecil
--}}
@extends('layouts.dashboard')

@section('title', 'Data Kendaraan - Parkir SMKN 8 Jember')

@section('content')
<!-- Alert Messages -->
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

<!-- Page Title & Button -->
<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h1 class="text-2xl font-bold text-navy">Data Kendaraan</h1>
        <p class="text-sm text-slate-500 mt-1">Kelola informasi kendaraan yang terdaftar di lingkungan sekolah</p>
    </div>
    <a href="{{ route('kendaraan.create') }}" class="bg-yellow text-navy px-6 py-2.5 rounded-lg font-bold flex items-center gap-2 hover:bg-yellow/90 transition-all shadow-sm">
        <span class="material-symbols-outlined text-[18px]">add</span>
        Tambah Kendaraan
    </a>
</div>

<!-- Table -->
<div class="bg-white rounded-xl border border-gray overflow-hidden shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-blue-50 border-b border-gray">
                    <th class="py-4 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider w-16 whitespace-nowrap">No</th>
                    <th class="py-4 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider whitespace-nowrap">Plat Nomor</th>
                    <th class="py-4 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider whitespace-nowrap">Pemilik</th>
                    <th class="py-4 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider whitespace-nowrap">Jenis</th>
                    <th class="py-4 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider whitespace-nowrap">Merk</th>
                    <th class="py-4 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider whitespace-nowrap">Warna</th>
                    <th class="py-4 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right whitespace-nowrap">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray">
                @forelse($kendaraan as $key => $item)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="py-4 px-6 text-sm text-slate-500 whitespace-nowrap">{{ $key + $kendaraan->firstItem() }}</td>
                    
                    {{-- FIX: Plat nomor tidak wrap + min-width biar ga sempit --}}
                    <td class="py-4 px-6 whitespace-nowrap min-w-[120px]">
                        <span class="font-bold text-navy bg-gray-50 px-2 py-1 rounded border border-gray/30 text-sm inline-block whitespace-nowrap">
                            {{ $item->plat_nomor }}
                        </span>
                    </td>
                    
                    <td class="py-4 px-6 whitespace-nowrap">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-navy text-white flex items-center justify-center text-xs font-bold shrink-0">
                                {{ strtoupper(substr($item->pemilik, 0, 2)) }}
                            </div>
                            <span class="text-sm font-medium text-navy">{{ $item->pemilik }}</span>
                        </div>
                    </td>
                    <td class="py-4 px-6 whitespace-nowrap">
                        @if($item->jenis_kendaraan == 'Motor')
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                <span class="material-symbols-outlined text-[14px]">two_wheeler</span> Motor
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-yellow-50 text-yellow-700 border border-yellow-200">
                                <span class="material-symbols-outlined text-[14px]">directions_car</span> Mobil
                            </span>
                        @endif
                    </td>
                    <td class="py-4 px-6 text-sm text-slate-500 whitespace-nowrap">{{ $item->merk }}</td>
                    <td class="py-4 px-6 text-sm text-slate-500 whitespace-nowrap">{{ $item->warna }}</td>
                    <td class="py-4 px-6 text-right whitespace-nowrap">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('kendaraan.edit', $item->id) }}" class="text-slate-500 hover:text-navy p-1 transition-colors" title="Edit">
                                <span class="material-symbols-outlined text-[20px]">edit</span>
                            </a>
                            <form method="POST" action="{{ route('kendaraan.destroy', $item->id) }}" id="delete-form-{{ $item->id }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="openDeleteModal(this.closest('form'), '{{ $item->plat_nomor }}')" class="text-slate-500 hover:text-red-500 p-1 transition-colors" title="Hapus">
                                    <span class="material-symbols-outlined text-[20px]">delete</span>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-12 text-center text-slate-500">
                        <span class="material-symbols-outlined text-5xl block mx-auto mb-3 text-slate-300">directions_car</span>
                        <p class="text-sm">Belum ada data kendaraan.</p>
                        <a href="{{ route('kendaraan.create') }}" class="text-navy font-bold hover:underline text-sm">Tambah kendaraan sekarang</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="px-6 py-4 border-t border-gray flex flex-col sm:flex-row justify-between items-center gap-3 bg-white">
        <span class="text-xs text-slate-500">Menampilkan {{ $kendaraan->firstItem() ?? 0 }}-{{ $kendaraan->lastItem() ?? 0 }} dari {{ $kendaraan->total() }} data</span>
        <div class="flex gap-1">
            {{ $kendaraan->links() }}
        </div>
    </div>
</div>
@endsection