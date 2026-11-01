{{--
  FORM KENDARAAN KELUAR
  - Dropdown transaksi aktif (status='masuk')
  - Kalau datang dari detail transaksi (?transaksi_id=X), dropdown AUTO-SELECT
  - Kalau tidak ada parameter, user pilih manual
  - Submit ke route('transaksi.proses-keluar')
  - Variabel dari controller: $transaksiMasuk
--}}
@extends('layouts.dashboard')

@section('title', 'Kendaraan Keluar - Parkir SMKN 8 Jember')

@section('content')
<div class="max-w-2xl mx-auto">
    <!-- HEADER -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-navy">Kendaraan Keluar</h1>
        <p class="text-sm text-slate-500 mt-1">Catat kendaraan yang keluar dari area parkir</p>
    </div>

    <div class="bg-white rounded-xl border border-gray p-6 shadow-sm">
        <form method="POST" action="{{ route('transaksi.proses-keluar') }}">
            @csrf

            <div class="space-y-4">
                <!-- Dropdown Transaksi Aktif -->
                <div>
                    <label for="transaksi_id" class="block text-sm font-semibold text-slate-700 mb-1">Pilih Kendaraan yang Keluar</label>
                    <select id="transaksi_id" name="transaksi_id" 
                            class="w-full px-4 py-2.5 border border-gray rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-yellow/50 focus:border-yellow transition-all @error('transaksi_id') border-red-500 @enderror" required>
                        <option value="">Pilih Kendaraan</option>
                        @foreach($transaksiMasuk as $item)
                            {{-- AUTO-SELECT: prioritas old() dulu, kalau tidak ada pakai request('transaksi_id') --}}
                            <option value="{{ $item->id }}" {{ old('transaksi_id', request('transaksi_id')) == $item->id ? 'selected' : '' }}>
                                {{ $item->kendaraan->plat_nomor ?? '-' }} - {{ $item->kendaraan->pemilik ?? '-' }}
                                (Masuk: {{ $item->waktu_masuk ? $item->waktu_masuk->format('H:i') : '-' }})
                            </option>
                        @endforeach
                    </select>
                    @error('transaksi_id')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Info tambahan -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <p class="text-sm text-blue-700 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">info</span>
                        Tarif akan dihitung otomatis berdasarkan durasi parkir.
                    </p>
                </div>
            </div>

            <!-- Tombol Proses Keluar & Batal -->
            <div class="flex gap-3 mt-6 pt-4 border-t border-gray">
                <button type="submit" class="bg-green-500 text-white font-bold px-6 py-2.5 rounded-lg hover:bg-green-600 transition-all shadow-sm flex items-center gap-2">
                    <span class="material-symbols-outlined">exit_to_app</span>
                    Proses Keluar
                </button>
                <a href="{{ route('transaksi.index') }}" class="bg-gray-100 text-slate-600 font-bold px-6 py-2.5 rounded-lg hover:bg-gray-200 transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined">close</span>
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection