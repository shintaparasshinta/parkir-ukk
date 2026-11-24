{{--
  HALAMAN DAFTAR KENDARAAN
  - FIX: plat nomor tidak wrap di mobile (whitespace-nowrap)
  - Table bisa di-scroll horizontal kalau layar kecil
--}}
@extends('layouts.dashboard') {{-- Menggunakan layout dashboard --}}

@section('title', 'Data Kendaraan - Parkir SMKN 8 Jember') {{-- Judul halaman --}}

@section('content') {{-- Bagian konten --}}
<!-- Alert Messages -->
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

<!-- Page Title & Button -->
<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4"> {{-- Header responsif --}}
    <div>
        <h1 class="text-2xl font-bold text-navy">Data Kendaraan</h1> {{-- Judul --}}
        <p class="text-sm text-slate-500 mt-1">Kelola informasi kendaraan yang terdaftar di lingkungan sekolah</p> {{-- Subjudul --}}
    </div>
    <a href="{{ route('kendaraan.create') }}" class="bg-yellow text-navy px-6 py-2.5 rounded-lg font-bold flex items-center gap-2 hover:bg-yellow/90 transition-all shadow-sm"> {{-- Tombol tambah --}}
        <span class="material-symbols-outlined text-[18px]">add</span> {{-- Ikon tambah --}}
        Tambah Kendaraan {{-- Label --}}
    </a>
</div>

<!-- Table -->
<div class="bg-white rounded-xl border border-gray overflow-hidden shadow-sm"> {{-- Container tabel --}}
    <div class="overflow-x-auto"> {{-- Scroll horizontal untuk mobile --}}
        <table class="w-full text-left border-collapse"> {{-- Tabel --}}
            <thead>
                <tr class="bg-blue-50 border-b border-gray"> {{-- Baris header --}}
                    <th class="py-4 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider w-16 whitespace-nowrap">No</th> {{-- Kolom nomor --}}
                    <th class="py-4 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider whitespace-nowrap">Plat Nomor</th> {{-- Kolom plat --}}
                    <th class="py-4 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider whitespace-nowrap">Pemilik</th> {{-- Kolom pemilik --}}
                    <th class="py-4 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider whitespace-nowrap">Jenis</th> {{-- Kolom jenis --}}
                    <th class="py-4 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider whitespace-nowrap">Merk</th> {{-- Kolom merk --}}
                    <th class="py-4 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider whitespace-nowrap">Warna</th> {{-- Kolom warna --}}
                    <th class="py-4 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right whitespace-nowrap">Aksi</th> {{-- Kolom aksi --}}
                </tr>
            </thead>
            <tbody class="divide-y divide-gray"> {{-- Body tabel --}}
                @forelse($kendaraan as $key => $item) {{-- Loop data kendaraan --}}
                <tr class="hover:bg-gray-50 transition-colors"> {{-- Baris data --}}
                    <td class="py-4 px-6 text-sm text-slate-500 whitespace-nowrap">{{ $key + $kendaraan->firstItem() }}</td> {{-- Nomor urut (offset by firstItem) --}}
                    
                    {{-- FIX: Plat nomor tidak wrap + min-width biar ga sempit --}}
                    <td class="py-4 px-6 whitespace-nowrap min-w-[120px]"> {{-- Kolom plat --}}
                        <span class="font-bold text-navy bg-gray-50 px-2 py-1 rounded border border-gray/30 text-sm inline-block whitespace-nowrap">
                            {{ $item->plat_nomor }} {{-- Plat nomor --}}
                        </span>
                    </td>
                    
                    <td class="py-4 px-6 whitespace-nowrap"> {{-- Kolom pemilik --}}
                        <div class="flex items-center gap-3"> {{-- Layout horizontal --}}
                            <div class="w-8 h-8 rounded-full bg-navy text-white flex items-center justify-center text-xs font-bold shrink-0"> {{-- Avatar inisial --}}
                                {{ strtoupper(substr($item->pemilik, 0, 2)) }} {{-- 2 huruf pertama pemilik --}}
                            </div>
                            <span class="text-sm font-medium text-navy">{{ $item->pemilik }}</span> {{-- Nama pemilik --}}
                        </div>
                    </td>
                    <td class="py-4 px-6 whitespace-nowrap"> {{-- Kolom jenis kendaraan --}}
                        @if($item->jenis_kendaraan == 'Motor')
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200"> {{-- Badge motor --}}
                                <span class="material-symbols-outlined text-[14px]">two_wheeler</span> Motor {{-- Ikon motor + teks --}}
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-yellow-50 text-yellow-700 border border-yellow-200"> {{-- Badge mobil --}}
                                <span class="material-symbols-outlined text-[14px]">directions_car</span> Mobil {{-- Ikon mobil + teks --}}
                            </span>
                        @endif
                    </td>
                    <td class="py-4 px-6 text-sm text-slate-500 whitespace-nowrap">{{ $item->merk }}</td> {{-- Merk --}}
                    <td class="py-4 px-6 text-sm text-slate-500 whitespace-nowrap">{{ $item->warna }}</td> {{-- Warna --}}
                    <td class="py-4 px-6 text-right whitespace-nowrap"> {{-- Kolom aksi --}}
                        <div class="flex justify-end gap-2"> {{-- Container tombol --}}
                            <a href="{{ route('kendaraan.edit', $item->id) }}" class="text-slate-500 hover:text-navy p-1 transition-colors" title="Edit"> {{-- Tombol edit --}}
                                <span class="material-symbols-outlined text-[20px]">edit</span> {{-- Ikon edit --}}
                            </a>
                            <form method="POST" action="{{ route('kendaraan.destroy', $item->id) }}" id="delete-form-{{ $item->id }}"> {{-- Form hapus --}}
                                @csrf {{-- Token CSRF --}}
                                @method('DELETE') {{-- Spoof method DELETE --}}
                                <button type="button" onclick="openDeleteModal(this.closest('form'), '{{ $item->plat_nomor }}')" class="text-slate-500 hover:text-red-500 p-1 transition-colors" title="Hapus"> {{-- Tombol hapus (buka modal) --}}
                                    <span class="material-symbols-outlined text-[20px]">delete</span> {{-- Ikon hapus --}}
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-12 text-center text-slate-500"> {{-- Empty state --}}
                        <span class="material-symbols-outlined text-5xl block mx-auto mb-3 text-slate-300">directions_car</span> {{-- Ikon besar --}}
                        <p class="text-sm">Belum ada data kendaraan.</p> {{-- Pesan --}}
                        <a href="{{ route('kendaraan.create') }}" class="text-navy font-bold hover:underline text-sm">Tambah kendaraan sekarang</a> {{-- Link tambah --}}
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="px-6 py-4 border-t border-gray flex flex-col sm:flex-row justify-between items-center gap-3 bg-white"> {{-- Container pagination --}}
        <span class="text-xs text-slate-500">Menampilkan {{ $kendaraan->firstItem() ?? 0 }}-{{ $kendaraan->lastItem() ?? 0 }} dari {{ $kendaraan->total() }} data</span> {{-- Info range data --}}
        <div class="flex gap-1">
            {{ $kendaraan->links() }} {{-- Link pagination --}}
        </div>
    </div>
</div>
@endsection