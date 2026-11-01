{{--
  HALAMAN DAFTAR TARIF (PER HARI)
  - Style badge "Jenis Kendaraan" disamakan dengan halaman Kendaraan
--}}
@extends('layouts.dashboard')

@section('title', 'Data Tarif Parkir - Parkir SMKN 8 Jember')

@section('content')
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-navy">Data Tarif Parkir</h1>
            <p class="text-sm text-slate-500 mt-1">Kelola tarif parkir berdasarkan jenis kendaraan (per hari).</p>
        </div>
        <a href="{{ route('tarif.create') }}"
            class="bg-yellow text-navy px-6 py-2.5 rounded-lg font-bold flex items-center gap-2 hover:bg-yellow/90 transition-all shadow-sm">
            <span class="material-symbols-outlined text-[18px]">add</span>
            Tambah Tarif
        </a>
    </div>

    <!-- Alert -->
    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-green-500">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-red-500">error</span>
            {{ session('error') }}
        </div>
    @endif

    <!-- Table -->
    <div class="bg-white rounded-xl border border-gray overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-blue-50 border-b border-gray">
                        <th class="py-4 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider">Area</th>
                        <th class="py-4 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider">Jenis Kendaraan
                        </th>
                        <th class="py-4 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider">Tarif / Hari
                        </th>
                        <th class="py-4 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray">
                    @forelse($tarifParkir as $item)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="py-4 px-6 text-sm text-navy font-medium">
                                {{ $item->areaParkir->nama_area ?? 'Area tidak ditemukan' }}
                            </td>
                            {{-- Kolom Jenis Kendaraan: badge disamakan dengan halaman Kendaraan --}}
                            <td class="py-4 px-6">
                                @if ($item->jenis_kendaraan == 'Motor')
                                    <span
                                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                        <span class="material-symbols-outlined text-[14px]">two_wheeler</span> Motor
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-yellow-50 text-yellow-700 border border-yellow-200">
                                        <span class="material-symbols-outlined text-[14px]">directions_car</span> Mobil
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-sm font-medium text-navy">
                                Rp {{ number_format($item->tarif, 0, ',', '.') }} / hari
                            </td>
                            <td class="py-4 px-6 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('tarif.edit', $item->id) }}"
                                        class="text-slate-500 hover:text-navy p-1 transition-colors" title="Edit">
                                        <span class="material-symbols-outlined text-[20px]">edit</span>
                                    </a>
                                    <form method="POST" action="{{ route('tarif.destroy', $item->id) }}"
                                        id="delete-form-{{ $item->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                            onclick="openDeleteModal(this.closest('form'), 'tarif {{ $item->areaParkir->nama_area ?? '' }} - {{ $item->jenis_kendaraan }}')"
                                            class="text-slate-500 hover:text-red-500 p-1 transition-colors" title="Hapus">
                                            <span class="material-symbols-outlined text-[20px]">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-slate-500">
                                <span
                                    class="material-symbols-outlined text-5xl block mx-auto mb-3 text-slate-300">payments</span>
                                <p class="text-sm">Belum ada data tarif parkir.</p>
                                <a href="{{ route('tarif.create') }}"
                                    class="text-navy font-bold hover:underline text-sm">Tambah tarif sekarang</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray flex flex-col sm:flex-row justify-between items-center gap-3 bg-white">
            <span class="text-xs text-slate-500">Menampilkan
                {{ $tarifParkir->firstItem() ?? 0 }}-{{ $tarifParkir->lastItem() ?? 0 }} dari {{ $tarifParkir->total() }}
                data</span>
            <div class="flex gap-1">
                {{ $tarifParkir->links() }}
            </div>
        </div>
    </div>
@endsection
