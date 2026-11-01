{{-- 
  FORM EDIT AREA PARKIR
  - Sama seperti create, tapi value input diambil dari $area (data lama)
  - Submit ke route('area.update', $area->id) dengan method PUT
--}}
@extends('layouts.dashboard')

@section('title', 'Edit Area Parkir - Parkir SMKN 8 Jember')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-navy">Edit Area Parkir</h1>
        <p class="text-sm text-slate-500">Perbarui data area parkir</p>
    </div>

    <div class="bg-white rounded-xl border border-gray p-6 shadow-sm">
        {{-- Form submit ke route update dengan method PUT --}}
        <form method="POST" action="{{ route('area.update', $area->id) }}">
            @csrf
            @method('PUT') {{-- Spoof method: HTML cuma support POST/GET, PUT diakali dengan _method --}}

            <div class="space-y-4">
                <!-- Nama Area -->
                <div>
                    <label for="nama_area" class="block text-sm font-semibold text-slate-700 mb-1">Nama Area</label>
                    {{-- old() buat restore input kalau validasi gagal, kalau tidak ada pakai $area->nama_area --}}
                    <input type="text" id="nama_area" name="nama_area" value="{{ old('nama_area', $area->nama_area) }}" 
                           class="w-full px-4 py-2.5 border border-gray rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-yellow/50 focus:border-yellow transition-all @error('nama_area') border-red-500 @enderror"
                           placeholder="Contoh: Area A, Area Utama" required>
                    @error('nama_area')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kapasitas -->
                <div>
                    <label for="kapasitas" class="block text-sm font-semibold text-slate-700 mb-1">Kapasitas</label>
                    <input type="number" id="kapasitas" name="kapasitas" value="{{ old('kapasitas', $area->kapasitas) }}" 
                           class="w-full px-4 py-2.5 border border-gray rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-yellow/50 focus:border-yellow transition-all @error('kapasitas') border-red-500 @enderror"
                           placeholder="Jumlah maksimal kendaraan" min="1" required>
                    @error('kapasitas')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Jenis Kendaraan -->
                <div>
                    <label for="jenis_kendaraan" class="block text-sm font-semibold text-slate-700 mb-1">Jenis Kendaraan</label>
                    <select id="jenis_kendaraan" name="jenis_kendaraan" 
                            class="w-full px-4 py-2.5 border border-gray rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-yellow/50 focus:border-yellow transition-all @error('jenis_kendaraan') border-red-500 @enderror" required>
                        <option value="">Pilih Jenis Kendaraan</option>
                        {{-- Cek `old()` dulu, kalau tidak ada pakai nilai dari database --}}
                        <option value="semua" {{ old('jenis_kendaraan', $area->jenis_kendaraan) == 'semua' ? 'selected' : '' }}>Semua (Motor & Mobil)</option>
                        <option value="Motor" {{ old('jenis_kendaraan', $area->jenis_kendaraan) == 'Motor' ? 'selected' : '' }}>Motor</option>
                        <option value="Mobil" {{ old('jenis_kendaraan', $area->jenis_kendaraan) == 'Mobil' ? 'selected' : '' }}>Mobil</option>
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
                        <option value="tersedia" {{ old('status', $area->status) == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                        <option value="hampir_penuh" {{ old('status', $area->status) == 'hampir_penuh' ? 'selected' : '' }}>Hampir Penuh</option>
                        <option value="penuh" {{ old('status', $area->status) == 'penuh' ? 'selected' : '' }}>Penuh</option>
                    </select>
                    @error('status')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Tombol Update & Batal --}}
            <div class="flex gap-3 mt-6 pt-4 border-t border-gray">
                <button type="submit" class="bg-yellow text-navy font-bold px-6 py-2.5 rounded-lg hover:bg-yellow/90 transition-all shadow-sm flex items-center gap-2">
                    <span class="material-symbols-outlined">save</span>
                    Update
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