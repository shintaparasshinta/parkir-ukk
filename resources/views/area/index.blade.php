{{-- 
  HALAMAN DAFTAR AREA PARKIR
  - Summary cards: Total, Tersedia, Penuh
  - Tabel daftar area (nama, kapasitas, status, tanggal)
  - Tombol Edit & Hapus per baris
--}}
@extends('layouts.dashboard')

@section('title', 'Data Area Parkir - Parkir SMKN 8 Jember')

@section('content')
    <!-- Header: judul + tombol tambah -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-navy">Data Area Parkir</h1>
            <p class="text-sm text-slate-500 mt-1">Kelola informasi dan kapasitas area parkir.</p>
        </div>
        <a href="{{ route('area.create') }}"
            class="bg-yellow text-navy px-6 py-2.5 rounded-lg font-bold flex items-center gap-2 hover:bg-yellow/90 transition-all shadow-sm">
            <span class="material-symbols-outlined text-[20px]">add</span>
            Tambah Area
        </a>
    </div>

    <!-- Alert Messages -->
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

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        {{-- Card 1: Total Area --}}
        <div class="bg-white rounded-xl border border-gray p-6 shadow-sm">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="text-xl font-bold text-navy mb-1">Total Area</h3>
                    <span class="text-sm text-slate-500">Seluruh area parkir</span>
                </div>
                <div class="p-3 bg-blue-50 rounded-lg text-navy">
                    <span class="material-symbols-outlined">local_parking</span>
                </div>
            </div>
            <p class="text-3xl font-bold text-navy">{{ $areaParkir->total() }}</p>
        </div>

        {{-- Card 2: Area Tersedia --}}
        <div class="bg-white rounded-xl border border-gray p-6 shadow-sm">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="text-xl font-bold text-navy mb-1">Tersedia</h3>
                    <span class="text-sm text-slate-500">Area dengan status tersedia</span>
                </div>
                <div class="p-3 bg-green-50 rounded-lg text-green-500">
                    <span class="material-symbols-outlined">check_circle</span>
                </div>
            </div>
            {{-- Hitung jumlah area dengan status 'tersedia' --}}
            <p class="text-3xl font-bold text-green-500">{{ $areaParkir->where('status', 'tersedia')->count() }}</p>
        </div>

        {{-- Card 3: Area Penuh --}}
        <div class="bg-white rounded-xl border border-gray p-6 shadow-sm">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="text-xl font-bold text-navy mb-1">Penuh</h3>
                    <span class="text-sm text-slate-500">Area dengan status penuh</span>
                </div>
                <div class="p-3 bg-red-50 rounded-lg text-red-500">
                    <span class="material-symbols-outlined">block</span>
                </div>
            </div>
            <p class="text-3xl font-bold text-red-500">{{ $areaParkir->where('status', 'penuh')->count() }}</p>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white border border-gray rounded-xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-blue-50 border-b border-gray">
                        <th class="p-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Nama Area</th>
                        <th class="p-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Kapasitas</th>
                        <th class="p-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="p-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Dibuat Pada</th>
                        <th class="p-4 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray">
                    @forelse($areaParkir as $item)
                        <tr class="hover:bg-gray-50 transition-colors">
                            {{-- Kolom nama area: pakai icon + nama --}}
                            <td class="p-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded bg-blue-50 flex items-center justify-center text-navy">
                                        <span class="material-symbols-outlined text-[18px]">local_parking</span>
                                    </div>
                                    <span class="font-semibold text-navy">{{ $item->nama_area }}</span>
                                </div>
                            </td>
                            <td class="p-4 text-slate-500">{{ $item->kapasitas }}</td>
                            <td class="p-4">
                                {{-- Badge status: warna beda tiap status --}}
                                @if ($item->status == 'tersedia')
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-700">Tersedia</span>
                                @elseif($item->status == 'hampir_penuh')
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-yellow-100 text-yellow-700">Hampir
                                        Penuh</span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-red-100 text-red-700">Penuh</span>
                                @endif
                            </td>
                            <td class="p-4 text-slate-500 text-sm">{{ $item->created_at->format('d M Y') }}</td>
                            <td class="p-4 text-right">
                                {{-- Tombol edit & hapus --}}
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('area.edit', $item->id) }}" class="text-slate-500 hover:text-navy p-1"
                                        title="Edit">
                                        <span class="material-symbols-outlined text-[20px]">edit</span>
                                    </a>
                                    <form method="POST" action="{{ route('area.destroy', $item->id) }}"
                                        id="delete-form-{{ $item->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                            onclick="openDeleteModal(this.closest('form'), '{{ $item->nama_area }}')"
                                            class="text-slate-500 hover:text-red-500 p-1 transition-colors" title="Hapus">
                                            <span class="material-symbols-outlined text-[20px]">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        {{-- Tampilan kalau data kosong --}}
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-500">
                                <span
                                    class="material-symbols-outlined text-5xl block mx-auto mb-3 text-slate-300">local_parking</span>
                                <p class="text-sm">Belum ada data area parkir.</p>
                                <a href="{{ route('area.create') }}"
                                    class="text-navy font-bold hover:underline text-sm">Tambah area sekarang</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="p-4 border-t border-gray flex flex-col sm:flex-row justify-between items-center gap-3 bg-white">
            <span class="text-xs text-slate-500">Menampilkan
                {{ $areaParkir->firstItem() ?? 0 }}-{{ $areaParkir->lastItem() ?? 0 }} dari {{ $areaParkir->total() }}
                data</span>
            <div class="flex gap-1">
                {{ $areaParkir->links() }}
            </div>
        </div>
    </div>
@endsection
