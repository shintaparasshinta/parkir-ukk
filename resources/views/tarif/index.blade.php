{{--
  HALAMAN DAFTAR TARIF (PER HARI)
  - Style badge "Jenis Kendaraan" disamakan dengan halaman Kendaraan
--}}
@extends('layouts.dashboard') {{-- Menggunakan layout dashboard --}}

@section('title', 'Data Tarif Parkir - Parkir SMKN 8 Jember') {{-- Judul halaman --}}

@section('content') {{-- Bagian konten --}}
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6"> {{-- Header responsif --}}
        <div>
            <h1 class="text-2xl font-bold text-navy">Data Tarif Parkir</h1> {{-- Judul --}}
            <p class="text-sm text-slate-500 mt-1">Kelola tarif parkir berdasarkan jenis kendaraan (per hari).</p> {{-- Subjudul --}}
        </div>
        <a href="{{ route('tarif.create') }}"
            class="bg-yellow text-navy px-6 py-2.5 rounded-lg font-bold flex items-center gap-2 hover:bg-yellow/90 transition-all shadow-sm"> {{-- Tombol tambah --}}
            <span class="material-symbols-outlined text-[18px]">add</span> {{-- Ikon tambah --}}
            Tambah Tarif {{-- Label --}}
        </a>
    </div>

    <!-- Alert -->
    @if (session('success')) {{-- Jika ada session sukses --}}
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4 flex items-center gap-2"> {{-- Alert hijau --}}
            <span class="material-symbols-outlined text-green-500">check_circle</span> {{-- Ikon centang --}}
            {{ session('success') }} {{-- Pesan --}}
        </div>
    @endif

    @if (session('error')) {{-- Jika ada session error --}}
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4 flex items-center gap-2"> {{-- Alert merah --}}
            <span class="material-symbols-outlined text-red-500">error</span> {{-- Ikon error --}}
            {{ session('error') }} {{-- Pesan --}}
        </div>
    @endif

    <!-- Table -->
    <div class="bg-white rounded-xl border border-gray overflow-hidden shadow-sm"> {{-- Container tabel --}}
        <div class="overflow-x-auto"> {{-- Scroll horizontal --}}
            <table class="w-full text-left border-collapse"> {{-- Tabel --}}
                <thead>
                    <tr class="bg-blue-50 border-b border-gray"> {{-- Baris header --}}
                        <th class="py-4 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider">Area</th> {{-- Kolom area --}}
                        <th class="py-4 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider">Jenis Kendaraan
                        </th> {{-- Kolom jenis --}}
                        <th class="py-4 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider">Tarif / Hari
                        </th> {{-- Kolom tarif --}}
                        <th class="py-4 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Aksi
                        </th> {{-- Kolom aksi --}}
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray"> {{-- Body tabel --}}
                    @forelse($tarifParkir as $item) {{-- Loop data tarif --}}
                        <tr class="hover:bg-gray-50 transition-colors"> {{-- Baris data --}}
                            <td class="py-4 px-6 text-sm text-navy font-medium">
                                {{ $item->areaParkir->nama_area ?? 'Area tidak ditemukan' }} {{-- Nama area atau fallback --}}
                            </td>
                            {{-- Kolom Jenis Kendaraan: badge disamakan dengan halaman Kendaraan --}}
                            <td class="py-4 px-6"> {{-- Kolom jenis --}}
                                @if ($item->jenis_kendaraan == 'Motor')
                                    <span
                                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200"> {{-- Badge motor --}}
                                        <span class="material-symbols-outlined text-[14px]">two_wheeler</span> Motor {{-- Ikon + teks --}}
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-yellow-50 text-yellow-700 border border-yellow-200"> {{-- Badge mobil --}}
                                        <span class="material-symbols-outlined text-[14px]">directions_car</span> Mobil {{-- Ikon + teks --}}
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-sm font-medium text-navy">
                                Rp {{ number_format($item->tarif, 0, ',', '.') }} / hari {{-- Tarif terformat --}}
                            </td>
                            <td class="py-4 px-6 text-right"> {{-- Kolom aksi --}}
                                <div class="flex justify-end gap-2"> {{-- Container tombol --}}
                                    <a href="{{ route('tarif.edit', $item->id) }}"
                                        class="text-slate-500 hover:text-navy p-1 transition-colors" title="Edit"> {{-- Tombol edit --}}
                                        <span class="material-symbols-outlined text-[20px]">edit</span> {{-- Ikon edit --}}
                                    </a>
                                    <form method="POST" action="{{ route('tarif.destroy', $item->id) }}"
                                        id="delete-form-{{ $item->id }}"> {{-- Form hapus --}}
                                        @csrf {{-- Token CSRF --}}
                                        @method('DELETE') {{-- Spoof method DELETE --}}
                                        <button type="button"
                                            onclick="openDeleteModal(this.closest('form'), 'tarif {{ $item->areaParkir->nama_area ?? '' }} - {{ $item->jenis_kendaraan }}')"
                                            class="text-slate-500 hover:text-red-500 p-1 transition-colors" title="Hapus"> {{-- Tombol hapus (buka modal) --}}
                                            <span class="material-symbols-outlined text-[20px]">delete</span> {{-- Ikon hapus --}}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr> {{-- Empty state --}}
                            <td colspan="4" class="py-8 text-center text-slate-500">
                                <span
                                    class="material-symbols-outlined text-5xl block mx-auto mb-3 text-slate-300">payments</span> {{-- Ikon besar --}}
                                <p class="text-sm">Belum ada data tarif parkir.</p> {{-- Pesan --}}
                                <a href="{{ route('tarif.create') }}"
                                    class="text-navy font-bold hover:underline text-sm">Tambah tarif sekarang</a> {{-- Link tambah --}}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray flex flex-col sm:flex-row justify-between items-center gap-3 bg-white"> {{-- Container pagination --}}
            <span class="text-xs text-slate-500">Menampilkan
                {{ $tarifParkir->firstItem() ?? 0 }}-{{ $tarifParkir->lastItem() ?? 0 }} dari {{ $tarifParkir->total() }}
                data</span> {{-- Info range --}}
            <div class="flex gap-1">
                {{ $tarifParkir->links() }} {{-- Link pagination --}}
            </div>
        </div>
    </div>
@endsection