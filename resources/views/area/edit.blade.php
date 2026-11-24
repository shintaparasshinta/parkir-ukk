{{--
  FORM EDIT AREA PARKIR
  - Sama seperti create, tapi value input diambil dari $area (data lama)
  - Submit ke route('area.update', $area->id) dengan method PUT
--}}
@extends('layouts.dashboard') {{-- Menggunakan layout dashboard --}}

@section('title', 'Edit Area Parkir - Parkir SMKN 8 Jember') {{-- Judul halaman --}}

@section('content') {{-- Bagian konten --}}
<div class="max-w-2xl mx-auto"> {{-- Container dengan lebar maksimum 2xl, posisi tengah --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-navy">Edit Area Parkir</h1> {{-- Judul --}}
        <p class="text-sm text-slate-500">Perbarui data area parkir</p> {{-- Subjudul --}}
    </div>

    <div class="bg-white rounded-xl border border-gray p-6 shadow-sm"> {{-- Card form --}}
        {{-- Form submit ke route update dengan method PUT --}}
        <form method="POST" action="{{ route('area.update', $area->id) }}"> {{-- Form POST ke update --}}
            @csrf {{-- Token CSRF --}}
            @method('PUT') {{-- Spoof method: HTML cuma support POST/GET, PUT diakali dengan _method --}}

            <div class="space-y-4"> {{-- Container input dengan spacing vertikal --}}
                <!-- Nama Area -->
                <div>
                    <label for="nama_area" class="block text-sm font-semibold text-slate-700 mb-1">Nama Area</label> {{-- Label input --}}
                    {{-- old() buat restore input kalau validasi gagal, kalau tidak ada pakai $area->nama_area --}}
                    <input type="text" id="nama_area" name="nama_area" value="{{ old('nama_area', $area->nama_area) }}" {{-- Value default dari $area --}}
                           class="w-full px-4 py-2.5 border border-gray rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-yellow/50 focus:border-yellow transition-all @error('nama_area') border-red-500 @enderror"
                           placeholder="Contoh: Area A, Area Utama" required> {{-- Placeholder & wajib --}}
                    @error('nama_area')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p> {{-- Pesan error --}}
                    @enderror
                </div>

                <!-- Kapasitas -->
                <div>
                    <label for="kapasitas" class="block text-sm font-semibold text-slate-700 mb-1">Kapasitas</label> {{-- Label input --}}
                    <input type="number" id="kapasitas" name="kapasitas" value="{{ old('kapasitas', $area->kapasitas) }}" {{-- Value default dari $area --}}
                           class="w-full px-4 py-2.5 border border-gray rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-yellow/50 focus:border-yellow transition-all @error('kapasitas') border-red-500 @enderror"
                           placeholder="Jumlah maksimal kendaraan" min="1" required>
                    @error('kapasitas')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p> {{-- Pesan error --}}
                    @enderror
                </div>

                <!-- Jenis Kendaraan -->
                <div>
                    <label for="jenis_kendaraan" class="block text-sm font-semibold text-slate-700 mb-1">Jenis Kendaraan</label> {{-- Label input --}}
                    <select id="jenis_kendaraan" name="jenis_kendaraan" {{-- Select jenis kendaraan --}}
                            class="w-full px-4 py-2.5 border border-gray rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-yellow/50 focus:border-yellow transition-all @error('jenis_kendaraan') border-red-500 @enderror" required>
                        <option value="">Pilih Jenis Kendaraan</option> {{-- Placeholder --}}
                        {{-- Cek `old()` dulu, kalau tidak ada pakai nilai dari database --}}
                        <option value="semua" {{ old('jenis_kendaraan', $area->jenis_kendaraan) == 'semua' ? 'selected' : '' }}>Semua (Motor & Mobil)</option> {{-- Opsi semua, terpilih jika cocok --}}
                        <option value="Motor" {{ old('jenis_kendaraan', $area->jenis_kendaraan) == 'Motor' ? 'selected' : '' }}>Motor</option> {{-- Opsi Motor --}}
                        <option value="Mobil" {{ old('jenis_kendaraan', $area->jenis_kendaraan) == 'Mobil' ? 'selected' : '' }}>Mobil</option> {{-- Opsi Mobil --}}
                    </select>
                    @error('jenis_kendaraan')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p> {{-- Pesan error --}}
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-semibold text-slate-700 mb-1">Status</label> {{-- Label input --}}
                    <select id="status" name="status" {{-- Select status --}}
                            class="w-full px-4 py-2.5 border border-gray rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-yellow/50 focus:border-yellow transition-all @error('status') border-red-500 @enderror" required>
                        <option value="">Pilih Status</option> {{-- Placeholder --}}
                        <option value="tersedia" {{ old('status', $area->status) == 'tersedia' ? 'selected' : '' }}>Tersedia</option> {{-- Opsi tersedia --}}
                        <option value="hampir_penuh" {{ old('status', $area->status) == 'hampir_penuh' ? 'selected' : '' }}>Hampir Penuh</option> {{-- Opsi hampir penuh --}}
                        <option value="penuh" {{ old('status', $area->status) == 'penuh' ? 'selected' : '' }}>Penuh</option> {{-- Opsi penuh --}}
                    </select>
                    @error('status')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p> {{-- Pesan error --}}
                    @enderror
                </div>
            </div>

            {{-- Tombol Update & Batal --}}
            <div class="flex gap-3 mt-6 pt-4 border-t border-gray"> {{-- Container tombol --}}
                <button type="submit" class="bg-yellow text-navy font-bold px-6 py-2.5 rounded-lg hover:bg-yellow/90 transition-all shadow-sm flex items-center gap-2"> {{-- Tombol update --}}
                    <span class="material-symbols-outlined">save</span> {{-- Ikon simpan --}}
                    Update {{-- Label tombol --}}
                </button>
                <a href="{{ route('area.index') }}" class="bg-gray-100 text-slate-600 font-bold px-6 py-2.5 rounded-lg hover:bg-gray-200 transition-all flex items-center gap-2"> {{-- Tombol batal --}}
                    <span class="material-symbols-outlined">close</span> {{-- Ikon batal --}}
                    Batal {{-- Label tombol --}}
                </a>
            </div>
        </form>
    </div>
</div>
@endsection