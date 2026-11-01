{{-- 
  FORM TAMBAH AREA PARKIR
  - Input: nama_area, kapasitas, jenis_kendaraan (semua/Motor/Mobil), status
  - Submit ke route('area.store') dengan method POST
--}}
@extends('layouts.dashboard')

@section('title', 'Tambah Area Parkir - Parkir SMKN 8 Jember')

@section('content')
<div class="max-w-2xl mx-auto">
    {{-- Header halaman --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-navy">Tambah Area Parkir</h1>
        <p class="text-sm text-slate-500">Masukkan data area parkir baru</p>
    </div>

    <div class="bg-white rounded-xl border border-gray p-6 shadow-sm">
        {{-- Form submit ke route('area.store') --}}
        <form method="POST" action="{{ route('area.store') }}">
            @csrf {{-- Token CSRF --}}

            <div class="space-y-4">
                <!-- Nama Area -->
                <div>
                    <label for="nama_area" class="block text-sm font-semibold text-slate-700 mb-1">Nama Area</label>
                    <input type="text" id="nama_area" name="nama_area" value="{{ old('nama_area') }}" 
                           class="w-full px-4 py-2.5 border border-gray rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-yellow/50 focus:border-yellow transition-all @error('nama_area') border-red-500 @enderror"
                           placeholder="Contoh: Area A, Area Utama" required>
                    @error('nama_area') {{-- Tampilkan error validasi --}}
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kapasitas -->
                <div>
                    <label for="kapasitas" class="block text-sm font-semibold text-slate-700 mb-1">Kapasitas</label>
                    <input type="number" id="kapasitas" name="kapasitas" value="{{ old('kapasitas') }}" 
                           class="w-full px-4 py-2.5 border border-gray rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-yellow/50 focus:border-yellow transition-all @error('kapasitas') border-red-500 @enderror"
                           placeholder="Jumlah maksimal kendaraan" min="1" required>
                    @error('kapasitas')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Jenis Kendaraan: untuk filter area sesuai jenis kendaraan --}}
                <div>
                    <label for="jenis_kendaraan" class="block text-sm font-semibold text-slate-700 mb-1">Jenis Kendaraan</label>
                    <select id="jenis_kendaraan" name="jenis_kendaraan" 
                            class="w-full px-4 py-2.5 border border-gray rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-yellow/50 focus:border-yellow transition-all @error('jenis_kendaraan') border-red-500 @enderror" required>
                        <option value="">Pilih Jenis Kendaraan</option>
                        <option value="semua" {{ old('jenis_kendaraan') == 'semua' ? 'selected' : '' }}>Semua (Motor & Mobil)</option>
                        <option value="Motor" {{ old('jenis_kendaraan') == 'Motor' ? 'selected' : '' }}>Motor</option>
                        <option value="Mobil" {{ old('jenis_kendaraan') == 'Mobil' ? 'selected' : '' }}>Mobil</option>
                    </select>
                    @error('jenis_kendaraan')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-semibold text-slate-700 mb-1">Status</label>
                    <select id="status" name="status" 
                            class="w-full px-4 py-2.5 border border-gray rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-yellow/50 focus:border-yellow transition-all @error('status') border-red-500 @enderror" required>
                        <option value="">Pilih Status</option>
                        <option value="tersedia" {{ old('status') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                        <option value="hampir_penuh" {{ old('status') == 'hampir_penuh' ? 'selected' : '' }}>Hampir Penuh</option>
                        <option value="penuh" {{ old('status') == 'penuh' ? 'selected' : '' }}>Penuh</option>
                    </select>
                    @error('status')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Tombol Simpan & Batal --}}
            <div class="flex gap-3 mt-6 pt-4 border-t border-gray">
                <button type="submit" class="bg-yellow text-navy font-bold px-6 py-2.5 rounded-lg hover:bg-yellow/90 transition-all shadow-sm flex items-center gap-2">
                    <span class="material-symbols-outlined">save</span>
                    Simpan
                </button>
                <a href="{{ route('area.index') }}" class="bg-gray-100 text-slate-600 font-bold px-6 py-2.5 rounded-lg hover:bg-gray-200 transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined">close</span>
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection