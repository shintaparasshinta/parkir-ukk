{{--
  HALAMAN DETAIL TRANSAKSI
  - Menampilkan semua info transaksi
  - Tombol "Proses Keluar" (hanya kalau status = masuk)
    → Kirim transaksi_id ke halaman keluar biar dropdown auto-select
  - Tombol "Cetak Struk" (hanya kalau punya permission 'cetak_struk')
  - Variabel dari controller: $transaksi
--}}
@extends('layouts.dashboard') {{-- Menggunakan layout dashboard --}}

@section('title', 'Detail Transaksi - Parkir SMKN 8 Jember') {{-- Judul halaman --}}

@section('content') {{-- Bagian konten --}}
    <div class="max-w-2xl mx-auto"> {{-- Container max-width 2xl, posisi tengah --}}
        <!-- HEADER -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-navy">Detail Transaksi</h1> {{-- Judul --}}
            <p class="text-sm text-slate-500 mt-1">Informasi lengkap transaksi parkir</p> {{-- Subjudul --}}
        </div>

        <div class="bg-white rounded-xl border border-gray p-6 shadow-sm"> {{-- Card detail --}}
            <div class="space-y-4"> {{-- Stack vertikal --}}
                <!-- Baris: Kode Transaksi -->
                <div class="flex justify-between items-center border-b border-gray pb-3"> {{-- Baris kode --}}
                    <span class="text-sm font-semibold text-slate-600">Kode Transaksi</span> {{-- Label --}}
                    <span class="text-sm font-bold text-navy">{{ $transaksi->kode_transaksi }}</span> {{-- Nilai --}}
                </div>

                <!-- Baris: Plat Nomor -->
                <div class="flex justify-between items-center border-b border-gray pb-3"> {{-- Baris plat --}}
                    <span class="text-sm font-semibold text-slate-600">Plat Nomor</span> {{-- Label --}}
                    <span class="text-sm font-bold text-navy">{{ $transaksi->kendaraan->plat_nomor ?? '-' }}</span> {{-- Nilai --}}
                </div>

                <!-- Baris: Pemilik -->
                <div class="flex justify-between items-center border-b border-gray pb-3"> {{-- Baris pemilik --}}
                    <span class="text-sm font-semibold text-slate-600">Pemilik</span> {{-- Label --}}
                    <span class="text-sm text-navy">{{ $transaksi->kendaraan->pemilik ?? '-' }}</span> {{-- Nilai --}}
                </div>

                <!-- Baris: Jenis Kendaraan -->
                <div class="flex justify-between items-center border-b border-gray pb-3"> {{-- Baris jenis --}}
                    <span class="text-sm font-semibold text-slate-600">Jenis Kendaraan</span> {{-- Label --}}
                    <span class="text-sm text-navy">{{ $transaksi->kendaraan->jenis_kendaraan ?? '-' }}</span> {{-- Nilai --}}
                </div>

                <!-- Baris: Area Parkir -->
                <div class="flex justify-between items-center border-b border-gray pb-3"> {{-- Baris area --}}
                    <span class="text-sm font-semibold text-slate-600">Area Parkir</span> {{-- Label --}}
                    <span class="text-sm text-navy">{{ $transaksi->areaParkir->nama_area ?? '-' }}</span> {{-- Nilai --}}
                </div>

                <!-- Baris: Petugas -->
                <div class="flex justify-between items-center border-b border-gray pb-3"> {{-- Baris petugas --}}
                    <span class="text-sm font-semibold text-slate-600">Petugas</span> {{-- Label --}}
                    <span class="text-sm text-navy">{{ $transaksi->petugas->nama ?? '-' }}</span> {{-- Nilai --}}
                </div>

                <!-- Baris: Waktu Masuk -->
                <div class="flex justify-between items-center border-b border-gray pb-3"> {{-- Baris waktu masuk --}}
                    <span class="text-sm font-semibold text-slate-600">Waktu Masuk</span> {{-- Label --}}
                    <span
                        class="text-sm text-navy">{{ $transaksi->waktu_masuk ? $transaksi->waktu_masuk->format('d/m/Y H:i') : '-' }}</span> {{-- Nilai --}}
                </div>

                <!-- Baris: Waktu Keluar -->
                <div class="flex justify-between items-center border-b border-gray pb-3"> {{-- Baris waktu keluar --}}
                    <span class="text-sm font-semibold text-slate-600">Waktu Keluar</span> {{-- Label --}}
                    <span
                        class="text-sm text-navy">{{ $transaksi->waktu_keluar ? $transaksi->waktu_keluar->format('d/m/Y H:i') : '-' }}</span> {{-- Nilai --}}
                </div>

                <!-- Baris: Tarif -->
                <div class="flex justify-between items-center border-b border-gray pb-3"> {{-- Baris tarif --}}
                    <span class="text-sm font-semibold text-slate-600">Tarif</span> {{-- Label --}}
                    <span
                        class="text-sm font-bold text-navy">{{ $transaksi->tarif ? 'Rp ' . number_format($transaksi->tarif, 0, ',', '.') : '-' }}</span> {{-- Nilai terformat --}}
                </div>

                <!-- Baris: Status -->
                <div class="flex justify-between items-center"> {{-- Baris status --}}
                    <span class="text-sm font-semibold text-slate-600">Status</span> {{-- Label --}}
                    @if ($transaksi->status == 'masuk') {{-- Jika status masuk --}}
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200"> {{-- Badge biru --}}
                            Masuk {{-- Label --}}
                        </span>
                    @else {{-- Jika status keluar --}}
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200"> {{-- Badge hijau --}}
                            Keluar {{-- Label --}}
                        </span>
                    @endif
                </div>
            </div>

            <!-- Tombol aksi -->
            <div class="flex flex-wrap gap-3 mt-6 pt-4 border-t border-gray"> {{-- Container tombol --}}
                <a href="{{ route('transaksi.index') }}"
                    class="bg-gray-100 text-slate-600 font-bold px-6 py-2.5 rounded-lg hover:bg-gray-200 transition-all flex items-center gap-2"> {{-- Tombol kembali --}}
                    <span class="material-symbols-outlined">arrow_back</span> {{-- Ikon panah --}}
                    Kembali {{-- Label --}}
                </a>

                @if ($transaksi->status == 'masuk') {{-- Jika masih masuk --}}
                    <a href="{{ route('transaksi.keluar', ['transaksi_id' => $transaksi->id]) }}"
                        class="bg-green-500 text-white font-bold px-6 py-2.5 rounded-lg hover:bg-green-600 transition-all flex items-center gap-2"> {{-- Tombol proses keluar --}}
                        <span class="material-symbols-outlined">exit_to_app</span> {{-- Ikon keluar --}}
                        Proses Keluar {{-- Label --}}
                    </a>
                @endif

                @if (auth()->user()->hasFeature('cetak_struk')) {{-- Jika punya fitur cetak struk --}}
                    <a href="{{ route('transaksi.struk', $transaksi->id) }}" target="_blank"
                        class="bg-navy text-white font-bold px-6 py-2.5 rounded-lg hover:bg-navy/80 transition-all flex items-center gap-2"> {{-- Tombol cetak struk --}}
                        <span class="material-symbols-outlined">print</span> {{-- Ikon print --}}
                        Cetak Struk {{-- Label --}}
                    </a>
                @endif
            </div>
        </div>
    </div>
@endsection