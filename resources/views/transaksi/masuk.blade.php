{{--
  FORM KENDARAAN MASUK
  - Dropdown kendaraan
  - Dropdown area parkir: OTOMATIS TERFILTER berdasarkan jenis kendaraan (JS)
  - Submit ke route('transaksi.proses-masuk') dengan method POST
  - Variabel dari controller: $kendaraan, $areaParkir
--}}
@extends('layouts.dashboard')

@section('title', 'Kendaraan Masuk - Parkir SMKN 8 Jember')

@section('content')
<div class="max-w-2xl mx-auto">
    <!-- HEADER -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-navy">Kendaraan Masuk</h1> {{-- Judul --}}
        <p class="text-sm text-slate-500">Catat kendaraan yang masuk ke area parkir</p> {{-- Subjudul --}}
    </div>

    <div class="bg-white rounded-xl border border-gray p-6 shadow-sm">
        <form method="POST" action="{{ route('transaksi.proses-masuk') }}"> {{-- Form POST --}}
            @csrf

            <div class="space-y-4">
                <!-- Dropdown Kendaraan -->
                <div>
                    <label for="kendaraan_id" class="block text-sm font-semibold text-slate-700 mb-1">Pilih Kendaraan</label>
                    <select id="kendaraan_id" name="kendaraan_id" 
                            class="w-full px-4 py-2.5 border border-gray rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-yellow/50 focus:border-yellow transition-all @error('kendaraan_id') border-red-500 @enderror" required>
                        <option value="">Pilih Kendaraan</option> {{-- Default --}}
                        @foreach($kendaraan as $item) {{-- Loop daftar kendaraan --}}
                            {{-- data-jenis: dipakai JS untuk filter area --}}
                            <option value="{{ $item->id }}" data-jenis="{{ $item->jenis_kendaraan }}" {{ old('kendaraan_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->plat_nomor }} - {{ $item->pemilik }} ({{ $item->jenis_kendaraan }})
                            </option>
                        @endforeach
                    </select>
                    @error('kendaraan_id')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Dropdown Area Parkir -->
                <div>
                    <label for="area_id" class="block text-sm font-semibold text-slate-700 mb-1">Pilih Area Parkir</label>
                    <select id="area_id" name="area_id" 
                            class="w-full px-4 py-2.5 border border-gray rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-yellow/50 focus:border-yellow transition-all @error('area_id') border-red-500 @enderror" required>
                        <option value="">Pilih Area</option> {{-- Default --}}
                        @foreach($areaParkir as $item)
                            {{-- data-jenis: jenis kendaraan yang cocok --}}
                            {{-- data-tersedia: sisa kapasitas --}}
                            <option value="{{ $item->id }}" 
                                    data-jenis="{{ $item->jenis_kendaraan }}" 
                                    data-tersedia="{{ $item->tersedia }}" 
                                    {{ old('area_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->nama_area }} (Tersedia: {{ $item->tersedia }}/{{ $item->kapasitas }}) - {{ ucfirst($item->status) }}
                            </option>
                        @endforeach
                    </select>
                    @error('area_id')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Info: filter otomatis -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <p class="text-sm text-blue-700 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">info</span>
                        Area parkir yang muncul hanya yang sesuai dengan jenis kendaraan dan masih tersedia.
                    </p>
                </div>
            </div>

            <!-- Tombol Proses Masuk & Batal -->
            <div class="flex gap-3 mt-6 pt-4 border-t border-gray">
                <button type="submit" class="bg-yellow text-navy font-bold px-6 py-2.5 rounded-lg hover:bg-yellow/90 transition-all shadow-sm flex items-center gap-2">
                    <span class="material-symbols-outlined">login</span>
                    Proses Masuk
                </button>
                <a href="{{ route('transaksi.index') }}" class="bg-gray-100 text-slate-600 font-bold px-6 py-2.5 rounded-lg hover:bg-gray-200 transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined">close</span>
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<!-- JavaScript: Auto-filter Area berdasarkan Jenis Kendaraan -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const kendaraanSelect = document.getElementById('kendaraan_id'); {{-- Dropdown kendaraan --}}
    const areaSelect = document.getElementById('area_id'); {{-- Dropdown area --}}
    const areaOptions = Array.from(areaSelect.querySelectorAll('option')); {{-- Simpan semua opsi area --}}

    // Fungsi: filter opsi area sesuai jenis kendaraan yang dipilih
    function filterArea() {
        const selectedKendaraan = kendaraanSelect.options[kendaraanSelect.selectedIndex];
        const jenisKendaraan = selectedKendaraan ? selectedKendaraan.getAttribute('data-jenis') : null; {{-- Ambil jenis kendaraan --}}

        const currentValue = areaSelect.value; {{-- Simpan pilihan area saat ini --}}
        areaSelect.innerHTML = ''; {{-- Kosongkan dropdown --}}
        areaSelect.appendChild(areaOptions[0]); {{-- Kembalikan opsi default "Pilih Area" --}}

        if (!jenisKendaraan) return; {{-- Kalau belum pilih kendaraan, stop --}}

        // Loop opsi area, tampilkan yang cocok
        areaOptions.slice(1).forEach(opt => {
            const jenisArea = opt.getAttribute('data-jenis'); {{-- Jenis yang cocok --}}
            const tersedia = parseInt(opt.getAttribute('data-tersedia')) || 0; {{-- Sisa kapasitas --}}

            // Tampilkan kalau: jenis cocok (atau 'semua') DAN masih tersedia
            if ((jenisArea === 'semua' || jenisArea === jenisKendaraan) && tersedia > 0) {
                areaSelect.appendChild(opt); {{-- Tambahkan opsi --}}
            }
        });

        // Kembalikan pilihan sebelumnya kalau masih ada
        if (currentValue) {
            const found = Array.from(areaSelect.options).find(o => o.value === currentValue);
            if (found) areaSelect.value = currentValue;
        }
    }

    kendaraanSelect.addEventListener('change', filterArea); {{-- Panggil saat kendaraan berubah --}}
});
</script>
@endsection