{{--
  HALAMAN DAFTAR TRANSAKSI
  - Tombol "Kendaraan Masuk" & "Kendaraan Keluar"
  - Tabel semua transaksi: Kode, Plat, Area, Petugas, Waktu, Tarif, Status, Aksi
  - Tombol hapus conditional (permission 'hapus_transaksi')
  - Variabel dari controller: $transaksi (paginate)
--}}
@extends('layouts.dashboard')

@section('title', 'Transaksi Parkir - Parkir SMKN 8 Jember')

@section('content')
<!-- HEADER: judul + tombol masuk/keluar -->
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-navy">Transaksi Parkir</h1> {{-- Judul --}}
        <p class="text-sm text-slate-500 mt-1">Kelola dan pantau seluruh transaksi parkir kendaraan.</p> {{-- Subjudul --}}
    </div>
    <div class="flex gap-2">
        {{-- Tombol kendaraan masuk --}}
        <a href="{{ route('transaksi.masuk') }}" class="bg-yellow text-navy px-6 py-2.5 rounded-lg font-bold flex items-center gap-2 hover:bg-yellow/90 transition-all shadow-sm">
            <span class="material-symbols-outlined text-sm">add</span>
            Kendaraan Masuk
        </a>
        {{-- Tombol kendaraan keluar --}}
        <a href="{{ route('transaksi.keluar') }}" class="bg-green-500 text-white px-6 py-2.5 rounded-lg font-bold flex items-center gap-2 hover:bg-green-600 transition-all shadow-sm">
            <span class="material-symbols-outlined text-sm">exit_to_app</span>
            Kendaraan Keluar
        </a>
    </div>
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

<!-- TABLE: daftar transaksi -->
<div class="bg-white rounded-xl border border-gray overflow-hidden shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-blue-50 border-b border-gray">
                    <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Kode Transaksi</th> {{-- Header kode --}}
                    <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Plat Nomor</th> {{-- Header plat --}}
                    <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Area</th> {{-- Header area --}}
                    <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Petugas</th> {{-- Header petugas --}}
                    <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Waktu Masuk</th> {{-- Header waktu masuk --}}
                    <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Waktu Keluar</th> {{-- Header waktu keluar --}}
                    <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Tarif</th> {{-- Header tarif --}}
                    <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">Status</th> {{-- Header status --}}
                    <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">Aksi</th> {{-- Header aksi --}}
                </tr>
            </thead>
            <tbody class="divide-y divide-gray">
                @forelse($transaksi as $item) {{-- Loop data transaksi --}}
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 text-sm text-navy font-medium">{{ $item->kode_transaksi }}</td> {{-- Kode transaksi --}}
                    <td class="px-6 py-4 text-sm font-bold text-navy">{{ $item->kendaraan->plat_nomor ?? '-' }}</td> {{-- Plat --}}
                    <td class="px-6 py-4 text-sm text-slate-600">{{ $item->areaParkir->nama_area ?? '-' }}</td> {{-- Area --}}
                    <td class="px-6 py-4 text-sm text-slate-600">{{ $item->petugas->nama ?? '-' }}</td> {{-- Petugas --}}
                    <td class="px-6 py-4 text-sm text-slate-500">{{ $item->waktu_masuk ? $item->waktu_masuk->format('d/m/Y H:i') : '-' }}</td> {{-- Waktu masuk --}}
                    <td class="px-6 py-4 text-sm text-slate-500">{{ $item->waktu_keluar ? $item->waktu_keluar->format('d/m/Y H:i') : '-' }}</td> {{-- Waktu keluar --}}
                    <td class="px-6 py-4 text-sm text-navy text-right font-medium">
                        {{ $item->tarif ? 'Rp ' . number_format($item->tarif, 0, ',', '.') : '-' }} {{-- Tarif --}}
                    </td>
                    <td class="px-6 py-4 text-center">
                        {{-- Badge status: biru masuk / hijau keluar --}}
                        @if($item->status == 'masuk')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200">Masuk</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">Keluar</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center gap-3">
                            <!-- Tombol detail -->
                            <a href="{{ route('transaksi.detail', $item->id) }}" class="text-navy hover:text-yellow transition-colors" title="Detail">
                                <span class="material-symbols-outlined">visibility</span>
                            </a>

                            {{-- Tombol hapus: hanya kalau punya permission 'hapus_transaksi' --}}
                            @if(auth()->user()->hasFeature('hapus_transaksi'))
                            <form method="POST" action="{{ route('transaksi.destroy', $item->id) }}"
                                  onsubmit="return confirm('Yakin ingin menghapus transaksi {{ $item->kode_transaksi }}? Tindakan ini tidak bisa dibatalkan.')"> {{-- Konfirmasi --}}
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 transition-colors" title="Hapus">
                                    <span class="material-symbols-outlined">delete</span>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr> {{-- Kalau data kosong --}}
                    <td colspan="9" class="py-8 text-center text-slate-500">
                        <span class="material-symbols-outlined text-5xl block mx-auto mb-3 text-slate-300">receipt_long</span>
                        <p class="text-sm">Belum ada transaksi.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- PAGINATION -->
    <div class="px-6 py-4 border-t border-gray flex flex-col sm:flex-row justify-between items-center gap-3 bg-white">
        <span class="text-xs text-slate-500">Menampilkan {{ $transaksi->firstItem() ?? 0 }}-{{ $transaksi->lastItem() ?? 0 }} dari {{ $transaksi->total() }} data</span> {{-- Info range --}}
        <div class="flex gap-1">
            {{ $transaksi->links() }} {{-- Link pagination --}}
        </div>
    </div>
</div>
@endsection