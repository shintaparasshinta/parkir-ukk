{{--
  FORM TAMBAH TARIF (PER HARI)
--}}
@extends('layouts.dashboard') {{-- Menggunakan layout dashboard --}}

@section('title', 'Tambah Tarif Parkir - Parkir SMKN 8 Jember') {{-- Judul halaman --}}

@section('content') {{-- Bagian konten --}}
<div class="max-w-2xl mx-auto"> {{-- Container max-width 2xl, posisi tengah --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-navy">Tambah Tarif Parkir</h1> {{-- Judul --}}
        <p class="text-sm text-slate-500">Masukkan data tarif parkir baru</p> {{-- Subjudul --}}
    </div>

    <div class="bg-white rounded-xl border border-gray p-6 shadow-sm"> {{-- Card form --}}
        <form method="POST" action="{{ route('tarif.store') }}"> {{-- Form POST --}}
            @csrf {{-- Token CSRF --}}

            <div class="space-y-4"> {{-- Container input dengan spacing vertikal --}}
                <!-- Area -->
                <div>
                    <label for="area_id" class="block text-sm font-semibold text-slate-700 mb-1">Area Parkir</label> {{-- Label --}}
                    <select id="area_id" name="area_id" 
                            class="w-full px-4 py-2.5 border border-gray rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-yellow/50 focus:border-yellow transition-all @error('area_id') border-red-500 @enderror" required> {{-- Select area --}}
                        <option value="">Pilih Area</option> {{-- Default --}}
                        @foreach($areas as $area) {{-- Loop area --}}
                            <option value="{{ $area->id }}" {{ old('area_id') == $area->id ? 'selected' : '' }}> {{-- Selected kalau old cocok --}}
                                {{ $area->nama_area }} (Kapasitas: {{ $area->kapasitas }}) {{-- Info area --}}
                            </option>
                        @endforeach
                    </select>
                    @error('area_id')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p> {{-- Pesan error --}}
                    @enderror
                </div>

                <!-- Jenis Kendaraan -->
                <div>
                    <label for="jenis_kendaraan" class="block text-sm font-semibold text-slate-700 mb-1">Jenis Kendaraan</label> {{-- Label --}}
                    <select id="jenis_kendaraan" name="jenis_kendaraan" 
                            class="w-full px-4 py-2.5 border border-gray rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-yellow/50 focus:border-yellow transition-all @error('jenis_kendaraan') border-red-500 @enderror" required> {{-- Select jenis --}}
                        <option value="">Pilih Jenis</option> {{-- Default --}}
                        <option value="Motor" {{ old('jenis_kendaraan') == 'Motor' ? 'selected' : '' }}>Motor</option> {{-- Opsi motor --}}
                        <option value="Mobil" {{ old('jenis_kendaraan') == 'Mobil' ? 'selected' : '' }}>Mobil</option> {{-- Opsi mobil --}}
                    </select>
                    @error('jenis_kendaraan')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p> {{-- Pesan error --}}
                    @enderror
                </div>

                <!-- Tarif per Hari -->
                <div>
                    <label for="tarif" class="block text-sm font-semibold text-slate-700 mb-1">Tarif per Hari (Rp)</label> {{-- Label --}}
                    <input type="number" id="tarif" name="tarif" value="{{ old('tarif') }}" 
                           class="w-full px-4 py-2.5 border border-gray rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-yellow/50 focus:border-yellow transition-all @error('tarif') border-red-500 @enderror"
                           placeholder="Contoh: 2000, 5000" min="0" required> {{-- Input tarif --}}
                    @error('tarif')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p> {{-- Pesan error --}}
                    @enderror
                </div>
            </div>

            <div class="flex gap-3 mt-6 pt-4 border-t border-gray"> {{-- Container tombol --}}
                <button type="submit" class="bg-yellow text-navy font-bold px-6 py-2.5 rounded-lg hover:bg-yellow/90 transition-all shadow-sm flex items-center gap-2"> {{-- Tombol simpan --}}
                    <span class="material-symbols-outlined">save</span> {{-- Ikon simpan --}}
                    Simpan {{-- Label --}}
                </button>
                <a href="{{ route('tarif.index') }}" class="bg-gray-100 text-slate-600 font-bold px-6 py-2.5 rounded-lg hover:bg-gray-200 transition-all flex items-center gap-2"> {{-- Tombol batal --}}
                    <span class="material-symbols-outlined">close</span> {{-- Ikon batal --}}
                    Batal {{-- Label --}}
                </a>
            </div>
        </form>
    </div>
</div>
@endsection