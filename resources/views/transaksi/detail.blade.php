{{--
  HALAMAN DETAIL TRANSAKSI
  - Menampilkan semua info transaksi
  - Tombol "Proses Keluar" (hanya kalau status = masuk)
    → Kirim transaksi_id ke halaman keluar biar dropdown auto-select
  - Tombol "Cetak Struk" (hanya kalau punya permission 'cetak_struk')
  - Variabel dari controller: $transaksi
--}}
@extends('layouts.dashboard')

@section('title', 'Detail Transaksi - Parkir SMKN 8 Jember')

@section('content')
    <div class="max-w-2xl mx-auto">
        <!-- HEADER -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-navy">Detail Transaksi</h1>
            <p class="text-sm text-slate-500 mt-1">Informasi lengkap transaksi parkir</p>
        </div>

        <div class="bg-white rounded-xl border border-gray p-6 shadow-sm">
            <div class="space-y-4">
                <!-- Baris: Kode Transaksi -->
                <div class="flex justify-between items-center border-b border-gray pb-3">
                    <span class="text-sm font-semibold text-slate-600">Kode Transaksi</span>
                    <span class="text-sm font-bold text-navy">{{ $transaksi->kode_transaksi }}</span>
                </div>

                <!-- Baris: Plat Nomor -->
                <div class="flex justify-between items-center border-b border-gray pb-3">
                    <span class="text-sm font-semibold text-slate-600">Plat Nomor</span>
                    <span class="text-sm font-bold text-navy">{{ $transaksi->kendaraan->plat_nomor ?? '-' }}</span>
                </div>

                <!-- Baris: Pemilik -->
                <div class="flex justify-between items-center border-b border-gray pb-3">
                    <span class="text-sm font-semibold text-slate-600">Pemilik</span>
                    <span class="text-sm text-navy">{{ $transaksi->kendaraan->pemilik ?? '-' }}</span>
                </div>

                <!-- Baris: Jenis Kendaraan -->
                <div class="flex justify-between items-center border-b border-gray pb-3">
                    <span class="text-sm font-semibold text-slate-600">Jenis Kendaraan</span>
                    <span class="text-sm text-navy">{{ $transaksi->kendaraan->jenis_kendaraan ?? '-' }}</span>
                </div>

                <!-- Baris: Area Parkir -->
                <div class="flex justify-between items-center border-b border-gray pb-3">
                    <span class="text-sm font-semibold text-slate-600">Area Parkir</span>
                    <span class="text-sm text-navy">{{ $transaksi->areaParkir->nama_area ?? '-' }}</span>
                </div>

                <!-- Baris: Petugas -->
                <div class="flex justify-between items-center border-b border-gray pb-3">
                    <span class="text-sm font-semibold text-slate-600">Petugas</span>
                    <span class="text-sm text-navy">{{ $transaksi->petugas->nama ?? '-' }}</span>
                </div>

                <!-- Baris: Waktu Masuk -->
                <div class="flex justify-between items-center border-b border-gray pb-3">
                    <span class="text-sm font-semibold text-slate-600">Waktu Masuk</span>
                    <span
                        class="text-sm text-navy">{{ $transaksi->waktu_masuk ? $transaksi->waktu_masuk->format('d/m/Y H:i') : '-' }}</span>
                </div>

                <!-- Baris: Waktu Keluar -->
                <div class="flex justify-between items-center border-b border-gray pb-3">
                    <span class="text-sm font-semibold text-slate-600">Waktu Keluar</span>
                    <span
                        class="text-sm text-navy">{{ $transaksi->waktu_keluar ? $transaksi->waktu_keluar->format('d/m/Y H:i') : '-' }}</span>
                </div>

                <!-- Baris: Tarif -->
                <div class="flex justify-between items-center border-b border-gray pb-3">
                    <span class="text-sm font-semibold text-slate-600">Tarif</span>
                    <span
                        class="text-sm font-bold text-navy">{{ $transaksi->tarif ? 'Rp ' . number_format($transaksi->tarif, 0, ',', '.') : '-' }}</span>
                </div>

                <!-- Baris: Status -->
                <div class="flex justify-between items-center">
                    <span class="text-sm font-semibold text-slate-600">Status</span>
                    @if ($transaksi->status == 'masuk')
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200">
                            Masuk
                        </span>
                    @else
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                            Keluar
                        </span>
                    @endif
                </div>
            </div>

            <!-- Tombol aksi -->
            <div class="flex flex-wrap gap-3 mt-6 pt-4 border-t border-gray">
                <a href="{{ route('transaksi.index') }}"
                    class="bg-gray-100 text-slate-600 font-bold px-6 py-2.5 rounded-lg hover:bg-gray-200 transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined">arrow_back</span>
                    Kembali
                </a>

                @if ($transaksi->status == 'masuk')
                    <a href="{{ route('transaksi.keluar', ['transaksi_id' => $transaksi->id]) }}"
                        class="bg-green-500 text-white font-bold px-6 py-2.5 rounded-lg hover:bg-green-600 transition-all flex items-center gap-2">
                        <span class="material-symbols-outlined">exit_to_app</span>
                        Proses Keluar
                    </a>
                @endif

                @if (auth()->user()->hasFeature('cetak_struk'))
                    <a href="{{ route('transaksi.struk', $transaksi->id) }}" target="_blank"
                        class="bg-navy text-white font-bold px-6 py-2.5 rounded-lg hover:bg-navy/80 transition-all flex items-center gap-2">
                        <span class="material-symbols-outlined">print</span>
                        Cetak Struk
                    </a>
                @endif
            </div>
        </div>
    </div>
@endsection
