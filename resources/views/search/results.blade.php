{{--
  HALAMAN HASIL PENCARIAN
  - Menampilkan 3 section: Kendaraan, Transaksi, Pengguna
  - Kalau semua kosong, tampilkan pesan "tidak ada hasil"
  - Variabel dari controller: $query, $kendaraan, $transaksi, $users
--}}
@extends('layouts.dashboard')

@section('title', 'Hasil Pencarian - Parkir SMKN 8 Jember')

@section('content')
<!-- HEADER: judul + kata kunci yang dicari -->
<div class="mb-6">
    <h1 class="text-2xl font-bold text-navy">Hasil Pencarian</h1> {{-- Judul --}}
    <p class="text-sm text-slate-500 mt-1">Menampilkan hasil untuk: <strong>"{{ $query }}"</strong></p> {{-- Kata kunci --}}
</div>

{{-- Cek: kalau ketiga hasil kosong, tampilkan empty state --}}
@if($kendaraan->isEmpty() && $transaksi->isEmpty() && $users->isEmpty())
    <div class="bg-white rounded-xl border border-gray p-8 text-center">
        <span class="material-symbols-outlined text-5xl text-slate-300 block mb-3">search_off</span> {{-- Ikon search off --}}
        <p class="text-slate-500">Tidak ada hasil untuk pencarian <strong>"{{ $query }}"</strong></p> {{-- Pesan --}}
        <p class="text-sm text-slate-400 mt-1">Coba dengan kata kunci lain.</p> {{-- Saran --}}
        <a href="{{ route('dashboard') }}" class="inline-block mt-4 text-yellow-600 hover:text-yellow-800 font-semibold"> {{-- Tombol balik --}}
            Kembali ke Dashboard
        </a>
    </div>
@else
    <!-- SECTION 1: Hasil Kendaraan (muncul hanya kalau ada) -->
    @if($kendaraan->isNotEmpty())
    <div class="bg-white rounded-xl border border-gray overflow-hidden shadow-sm mb-6">
        <div class="px-6 py-3 border-b border-gray bg-gray-50 flex justify-between items-center">
            <h3 class="text-sm font-bold text-navy flex items-center gap-2"> {{-- Judul section --}}
                <span class="material-symbols-outlined text-navy">directions_car</span> {{-- Ikon --}}
                Kendaraan ({{ $kendaraan->count() }}) {{-- Jumlah hasil --}}
            </h3>
            <a href="{{ route('kendaraan.index') }}" class="text-xs text-yellow-600 hover:text-yellow-800">Lihat Semua</a> {{-- Link ke halaman kendaraan --}}
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray">
                        <th class="px-4 py-2 text-xs font-semibold text-slate-500">Plat Nomor</th> {{-- Header: plat --}}
                        <th class="px-4 py-2 text-xs font-semibold text-slate-500">Pemilik</th> {{-- Header: pemilik --}}
                        <th class="px-4 py-2 text-xs font-semibold text-slate-500">Jenis</th> {{-- Header: jenis --}}
                        <th class="px-4 py-2 text-xs font-semibold text-slate-500 text-right">Aksi</th> {{-- Header: aksi --}}
                    </tr>
                </thead>
                <tbody>
                    @foreach($kendaraan as $item) {{-- Loop hasil kendaraan --}}
                    <tr class="border-b border-gray hover:bg-gray-50">
                        <td class="px-4 py-2 text-sm font-medium text-navy">{{ $item->plat_nomor }}</td> {{-- Plat --}}
                        <td class="px-4 py-2 text-sm">{{ $item->pemilik }}</td> {{-- Pemilik --}}
                        <td class="px-4 py-2 text-sm">{{ $item->jenis_kendaraan }}</td> {{-- Jenis --}}
                        <td class="px-4 py-2 text-right">
                            <a href="{{ route('kendaraan.edit', $item->id) }}" class="text-yellow-600 hover:text-yellow-800 text-sm">Edit</a> {{-- Tombol edit --}}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- SECTION 2: Hasil Transaksi (muncul hanya kalau ada) -->
    @if($transaksi->isNotEmpty())
    <div class="bg-white rounded-xl border border-gray overflow-hidden shadow-sm mb-6">
        <div class="px-6 py-3 border-b border-gray bg-gray-50 flex justify-between items-center">
            <h3 class="text-sm font-bold text-navy flex items-center gap-2">
                <span class="material-symbols-outlined text-navy">receipt_long</span>
                Transaksi ({{ $transaksi->count() }})
            </h3>
            <a href="{{ route('transaksi.index') }}" class="text-xs text-yellow-600 hover:text-yellow-800">Lihat Semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray">
                        <th class="px-4 py-2 text-xs font-semibold text-slate-500">Kode</th> {{-- Header: kode --}}
                        <th class="px-4 py-2 text-xs font-semibold text-slate-500">Plat</th> {{-- Header: plat --}}
                        <th class="px-4 py-2 text-xs font-semibold text-slate-500">Status</th> {{-- Header: status --}}
                        <th class="px-4 py-2 text-xs font-semibold text-slate-500 text-right">Aksi</th> {{-- Header: aksi --}}
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaksi as $item)
                    <tr class="border-b border-gray hover:bg-gray-50">
                        <td class="px-4 py-2 text-sm font-medium text-navy">{{ $item->kode_transaksi }}</td> {{-- Kode --}}
                        <td class="px-4 py-2 text-sm">{{ $item->kendaraan->plat_nomor ?? '-' }}</td> {{-- Plat atau strip --}}
                        <td class="px-4 py-2 text-sm">
                            {{-- Badge status: biru masuk, hijau keluar --}}
                            @if($item->status == 'masuk')
                                <span class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded text-xs">Masuk</span>
                            @else
                                <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded text-xs">Keluar</span>
                            @endif
                        </td>
                        <td class="px-4 py-2 text-right">
                            <a href="{{ route('transaksi.detail', $item->id) }}" class="text-yellow-600 hover:text-yellow-800 text-sm">Detail</a> {{-- Tombol detail --}}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- SECTION 3: Hasil Pengguna (muncul hanya kalau ada) -->
    @if($users->isNotEmpty())
    <div class="bg-white rounded-xl border border-gray overflow-hidden shadow-sm">
        <div class="px-6 py-3 border-b border-gray bg-gray-50 flex justify-between items-center">
            <h3 class="text-sm font-bold text-navy flex items-center gap-2">
                <span class="material-symbols-outlined text-navy">group</span>
                Pengguna ({{ $users->count() }})
            </h3>
            <a href="{{ route('pengguna.index') }}" class="text-xs text-yellow-600 hover:text-yellow-800">Lihat Semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray">
                        <th class="px-4 py-2 text-xs font-semibold text-slate-500">Nama</th> {{-- Header: nama --}}
                        <th class="px-4 py-2 text-xs font-semibold text-slate-500">Username</th> {{-- Header: username --}}
                        <th class="px-4 py-2 text-xs font-semibold text-slate-500">Role</th> {{-- Header: role --}}
                        <th class="px-4 py-2 text-xs font-semibold text-slate-500 text-right">Aksi</th> {{-- Header: aksi --}}
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $item)
                    <tr class="border-b border-gray hover:bg-gray-50">
                        <td class="px-4 py-2 text-sm font-medium text-navy">{{ $item->nama }}</td> {{-- Nama --}}
                        <td class="px-4 py-2 text-sm">{{ $item->username }}</td> {{-- Username --}}
                        <td class="px-4 py-2 text-sm capitalize">{{ $item->role }}</td> {{-- Role --}}
                        <td class="px-4 py-2 text-right">
                            <a href="{{ route('pengguna.edit', $item->id) }}" class="text-yellow-600 hover:text-yellow-800 text-sm">Edit</a> {{-- Tombol edit --}}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Tombol Kembali ke Dashboard -->
    <div class="mt-4">
        <a href="{{ route('dashboard') }}" class="text-slate-500 hover:text-navy text-sm flex items-center gap-1">
            <span class="material-symbols-outlined text-sm">arrow_back</span>
            Kembali ke Dashboard
        </a>
    </div>
@endif
@endsection