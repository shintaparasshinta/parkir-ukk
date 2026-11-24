{{--
  HALAMAN DAFTAR AREA PARKIR
  - Summary cards: Total, Tersedia, Penuh
  - Tabel daftar area (nama, kapasitas, status, tanggal)
  - Tombol Edit & Hapus per baris
--}}
@extends('layouts.dashboard') {{-- Menggunakan layout dashboard --}}

@section('title', 'Data Area Parkir - Parkir SMKN 8 Jember') {{-- Judul halaman --}}

@section('content') {{-- Bagian konten --}}
    <!-- Header: judul + tombol tambah -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6"> {{-- Header responsif --}}
        <div>
            <h1 class="text-2xl font-bold text-navy">Data Area Parkir</h1> {{-- Judul --}}
            <p class="text-sm text-slate-500 mt-1">Kelola informasi dan kapasitas area parkir.</p> {{-- Subjudul --}}
        </div>
        <a href="{{ route('area.create') }}"
            class="bg-yellow text-navy px-6 py-2.5 rounded-lg font-bold flex items-center gap-2 hover:bg-yellow/90 transition-all shadow-sm"> {{-- Tombol tambah --}}
            <span class="material-symbols-outlined text-[20px]">add</span> {{-- Ikon tambah --}}
            Tambah Area {{-- Label --}}
        </a>
    </div>

    <!-- Alert Messages -->
    @if (session('success')) {{-- Jika ada session sukses --}}
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4 flex items-center gap-2"> {{-- Alert hijau --}}
            <span class="material-symbols-outlined text-green-500">check_circle</span> {{-- Ikon centang --}}
            {{ session('success') }} {{-- Pesan sukses --}}
        </div>
    @endif

    @if (session('error')) {{-- Jika ada session error --}}
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4 flex items-center gap-2"> {{-- Alert merah --}}
            <span class="material-symbols-outlined text-red-500">error</span> {{-- Ikon error --}}
            {{ session('error') }} {{-- Pesan error --}}
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6"> {{-- Grid 3 kolom untuk stats --}}
        {{-- Card 1: Total Area --}}
        <div class="bg-white rounded-xl border border-gray p-6 shadow-sm"> {{-- Card total --}}
            <div class="flex justify-between items-start mb-4"> {{-- Header card --}}
                <div>
                    <h3 class="text-xl font-bold text-navy mb-1">Total Area</h3> {{-- Judul --}}
                    <span class="text-sm text-slate-500">Seluruh area parkir</span> {{-- Subjudul --}}
                </div>
                <div class="p-3 bg-blue-50 rounded-lg text-navy"> {{-- Container ikon --}}
                    <span class="material-symbols-outlined">local_parking</span> {{-- Ikon parkir --}}
                </div>
            </div>
            <p class="text-3xl font-bold text-navy">{{ $areaParkir->total() }}</p> {{-- Total area dari paginator --}}
        </div>

        {{-- Card 2: Area Tersedia --}}
        <div class="bg-white rounded-xl border border-gray p-6 shadow-sm"> {{-- Card tersedia --}}
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="text-xl font-bold text-navy mb-1">Tersedia</h3> {{-- Judul --}}
                    <span class="text-sm text-slate-500">Area dengan status tersedia</span> {{-- Subjudul --}}
                </div>
                <div class="p-3 bg-green-50 rounded-lg text-green-500"> {{-- Container ikon --}}
                    <span class="material-symbols-outlined">check_circle</span> {{-- Ikon centang --}}
                </div>
            </div>
            {{-- Hitung jumlah area dengan status 'tersedia' --}}
            <p class="text-3xl font-bold text-green-500">{{ $areaParkir->where('status', 'tersedia')->count() }}</p> {{-- Hitung dari collection (hanya halaman ini) --}}
        </div>

        {{-- Card 3: Area Penuh --}}
        <div class="bg-white rounded-xl border border-gray p-6 shadow-sm"> {{-- Card penuh --}}
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="text-xl font-bold text-navy mb-1">Penuh</h3> {{-- Judul --}}
                    <span class="text-sm text-slate-500">Area dengan status penuh</span> {{-- Subjudul --}}
                </div>
                <div class="p-3 bg-red-50 rounded-lg text-red-500"> {{-- Container ikon --}}
                    <span class="material-symbols-outlined">block</span> {{-- Ikon blok --}}
                </div>
            </div>
            <p class="text-3xl font-bold text-red-500">{{ $areaParkir->where('status', 'penuh')->count() }}</p> {{-- Hitung dari collection (hanya halaman ini) --}}
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white border border-gray rounded-xl overflow-hidden shadow-sm"> {{-- Container tabel --}}
        <div class="overflow-x-auto"> {{-- Scroll horizontal untuk mobile --}}
            <table class="w-full text-left border-collapse"> {{-- Tabel --}}
                <thead>
                    <tr class="bg-blue-50 border-b border-gray"> {{-- Baris header --}}
                        <th class="p-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Nama Area</th> {{-- Kolom nama area --}}
                        <th class="p-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Kapasitas</th> {{-- Kolom kapasitas --}}
                        <th class="p-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th> {{-- Kolom status --}}
                        <th class="p-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Dibuat Pada</th> {{-- Kolom tanggal --}}
                        <th class="p-4 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Aksi</th> {{-- Kolom aksi --}}
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray"> {{-- Body tabel --}}
                    @forelse($areaParkir as $item) {{-- Loop data dengan empty state --}}
                        <tr class="hover:bg-gray-50 transition-colors"> {{-- Baris data --}}
                            {{-- Kolom nama area: pakai icon + nama --}}
                            <td class="p-4">
                                <div class="flex items-center gap-3"> {{-- Container fleksibel --}}
                                    <div class="w-8 h-8 rounded bg-blue-50 flex items-center justify-center text-navy"> {{-- Kotak ikon --}}
                                        <span class="material-symbols-outlined text-[18px]">local_parking</span> {{-- Ikon parkir --}}
                                    </div>
                                    <span class="font-semibold text-navy">{{ $item->nama_area }}</span> {{-- Nama area --}}
                                </div>
                            </td>
                            <td class="p-4 text-slate-500">{{ $item->kapasitas }}</td> {{-- Kapasitas --}}
                            <td class="p-4">
                                {{-- Badge status: warna beda tiap status --}}
                                @if ($item->status == 'tersedia')
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-700">Tersedia</span> {{-- Badge hijau --}}
                                @elseif($item->status == 'hampir_penuh')
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-yellow-100 text-yellow-700">Hampir
                                        Penuh</span> {{-- Badge kuning --}}
                                @else
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-red-100 text-red-700">Penuh</span> {{-- Badge merah --}}
                                @endif
                            </td>
                            <td class="p-4 text-slate-500 text-sm">{{ $item->created_at->format('d M Y') }}</td> {{-- Tanggal dibuat --}}
                            <td class="p-4 text-right"> {{-- Kolom aksi --}}
                                {{-- Tombol edit & hapus --}}
                                <div class="flex justify-end gap-2"> {{-- Container tombol --}}
                                    <a href="{{ route('area.edit', $item->id) }}" class="text-slate-500 hover:text-navy p-1"
                                        title="Edit"> {{-- Tombol edit --}}
                                        <span class="material-symbols-outlined text-[20px]">edit</span> {{-- Ikon edit --}}
                                    </a>
                                    <form method="POST" action="{{ route('area.destroy', $item->id) }}"
                                        id="delete-form-{{ $item->id }}"> {{-- Form hapus --}}
                                        @csrf {{-- Token CSRF --}}
                                        @method('DELETE') {{-- Spoof method DELETE --}}
                                        <button type="button"
                                            onclick="openDeleteModal(this.closest('form'), '{{ $item->nama_area }}')"
                                            class="text-slate-500 hover:text-red-500 p-1 transition-colors" title="Hapus"> {{-- Tombol hapus, memicu modal konfirmasi --}}
                                            <span class="material-symbols-outlined text-[20px]">delete</span> {{-- Ikon hapus --}}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        {{-- Tampilan kalau data kosong --}}
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-500"> {{-- Baris empty state --}}
                                <span
                                    class="material-symbols-outlined text-5xl block mx-auto mb-3 text-slate-300">local_parking</span> {{-- Ikon besar --}}
                                <p class="text-sm">Belum ada data area parkir.</p> {{-- Pesan --}}
                                <a href="{{ route('area.create') }}"
                                    class="text-navy font-bold hover:underline text-sm">Tambah area sekarang</a> {{-- Link tambah --}}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="p-4 border-t border-gray flex flex-col sm:flex-row justify-between items-center gap-3 bg-white"> {{-- Container pagination --}}
            <span class="text-xs text-slate-500">Menampilkan
                {{ $areaParkir->firstItem() ?? 0 }}-{{ $areaParkir->lastItem() ?? 0 }} dari {{ $areaParkir->total() }}
                data</span> {{-- Info range data --}}
            <div class="flex gap-1">
                {{ $areaParkir->links() }} {{-- Link pagination bawaan Laravel --}}
            </div>
        </div>
    </div>
@endsection