{{--
  FORM EDIT KENDARAAN
  - Sama seperti create, tapi value input diambil dari $kendaraan (data lama)
  - Submit ke route('kendaraan.update', $kendaraan->id) dengan method PUT
  - Ringkasan error ditampilkan di atas form untuk memudahkan debugging
--}}
@extends('layouts.dashboard') {{-- Menggunakan layout dashboard --}}

@section('title', 'Edit Kendaraan - Parkir SMKN 8 Jember') {{-- Judul halaman --}}

@section('content') {{-- Bagian konten --}}

{{-- RINGKASAN ERROR: tampil di atas form jika ada validasi gagal --}}
@if($errors->any())
    <div class="max-w-2xl mx-auto mb-4 bg-red-100 border-2 border-red-500 text-red-800 p-4 rounded-lg">
        <strong class="text-lg flex items-center gap-2">
            <span class="material-symbols-outlined">error</span>
            Validasi Gagal — Periksa input berikut:
        </strong>
        <ul class="list-disc ml-8 mt-2 text-sm">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="max-w-2xl mx-auto"> {{-- Container max-width 2xl, posisi tengah --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-navy">Edit Kendaraan</h1> {{-- Judul --}}
        <p class="text-sm text-slate-500">Perbarui data kendaraan</p> {{-- Subjudul --}}
    </div>

    <div class="bg-white rounded-xl border border-gray p-6 shadow-sm"> {{-- Card form --}}
        {{-- Form update, method di-spoof jadi PUT --}}
        <form method="POST" action="{{ route('kendaraan.update', $kendaraan->id) }}"> {{-- Form POST ke update --}}
            @csrf {{-- Token CSRF --}}
            @method('PUT') {{-- Spoof method PUT --}}

            <div class="space-y-4"> {{-- Container input dengan spacing vertikal --}}
                <!-- Plat Nomor -->
                <div>
                    <label for="plat_nomor" class="block text-sm font-semibold text-slate-700 mb-1">Plat Nomor</label> {{-- Label --}}
                    {{-- old() untuk restore, kalau tidak ada pakai $kendaraan->plat_nomor --}}
                    <input type="text" id="plat_nomor" name="plat_nomor" value="{{ old('plat_nomor', $kendaraan->plat_nomor) }}" {{-- Value default dari $kendaraan --}}
                           class="w-full px-4 py-2.5 border border-gray rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-yellow/50 focus:border-yellow transition-all @error('plat_nomor') border-red-500 @enderror"
                           placeholder="Contoh: B 1234 ABC" required maxlength="20">
                    @error('plat_nomor')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p> {{-- Pesan error --}}
                    @enderror
                </div>

                <!-- Pemilik -->
                <div>
                    <label for="pemilik" class="block text-sm font-semibold text-slate-700 mb-1">Nama Pemilik</label> {{-- Label --}}
                    <input type="text" id="pemilik" name="pemilik" value="{{ old('pemilik', $kendaraan->pemilik) }}" {{-- Value default dari $kendaraan --}}
                           class="w-full px-4 py-2.5 border border-gray rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-yellow/50 focus:border-yellow transition-all @error('pemilik') border-red-500 @enderror"
                           placeholder="Nama lengkap pemilik kendaraan" required maxlength="255">
                    @error('pemilik')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p> {{-- Pesan error --}}
                    @enderror
                </div>

                <!-- Jenis & Merk -->
                <div class="grid grid-cols-2 gap-4"> {{-- Grid 2 kolom --}}
                    <div>
                        <label for="jenis_kendaraan" class="block text-sm font-semibold text-slate-700 mb-1">Jenis Kendaraan</label> {{-- Label --}}
                        <select id="jenis_kendaraan" name="jenis_kendaraan" {{-- Select jenis --}}
                                class="w-full px-4 py-2.5 border border-gray rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-yellow/50 focus:border-yellow transition-all @error('jenis_kendaraan') border-red-500 @enderror" required>
                            <option value="">Pilih Jenis</option> {{-- Placeholder --}}
                            <option value="Motor" {{ old('jenis_kendaraan', $kendaraan->jenis_kendaraan) == 'Motor' ? 'selected' : '' }}>Motor</option> {{-- Terpilih jika cocok --}}
                            <option value="Mobil" {{ old('jenis_kendaraan', $kendaraan->jenis_kendaraan) == 'Mobil' ? 'selected' : '' }}>Mobil</option> {{-- Terpilih jika cocok --}}
                        </select>
                        @error('jenis_kendaraan')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p> {{-- Pesan error --}}
                        @enderror
                    </div>

                    <div>
                        <label for="merk" class="block text-sm font-semibold text-slate-700 mb-1">Merk</label> {{-- Label --}}
                        <input type="text" id="merk" name="merk" value="{{ old('merk', $kendaraan->merk) }}" {{-- Value default dari $kendaraan --}}
                               class="w-full px-4 py-2.5 border border-gray rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-yellow/50 focus:border-yellow transition-all @error('merk') border-red-500 @enderror"
                               placeholder="Contoh: Honda Vario" required maxlength="255">
                        @error('merk')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p> {{-- Pesan error --}}
                        @enderror
                    </div>
                </div>

                <!-- Warna -->
                <div>
                    <label for="warna" class="block text-sm font-semibold text-slate-700 mb-1">Warna</label> {{-- Label --}}
                    <input type="text" id="warna" name="warna" value="{{ old('warna', $kendaraan->warna) }}" {{-- Value default dari $kendaraan --}}
                           class="w-full px-4 py-2.5 border border-gray rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-yellow/50 focus:border-yellow transition-all @error('warna') border-red-500 @enderror"
                           placeholder="Contoh: Hitam, Putih, Merah" required maxlength="100">
                    @error('warna')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p> {{-- Pesan error --}}
                    @enderror
                </div>
            </div>

            {{-- Tombol Update & Batal --}}
            <div class="flex gap-3 mt-6 pt-4 border-t border-gray"> {{-- Container tombol --}}
                <button type="submit" class="bg-yellow text-navy font-bold px-6 py-2.5 rounded-lg hover:bg-yellow/90 transition-all shadow-sm flex items-center gap-2"> {{-- Tombol update --}}
                    <span class="material-symbols-outlined">save</span> {{-- Ikon simpan --}}
                    Update {{-- Label --}}
                </button>
                <a href="{{ route('kendaraan.index') }}" class="bg-gray-100 text-slate-600 font-bold px-6 py-2.5 rounded-lg hover:bg-gray-200 transition-all flex items-center gap-2"> {{-- Tombol batal --}}
                    <span class="material-symbols-outlined">close</span> {{-- Ikon batal --}}
                    Batal {{-- Label --}}
                </a>
            </div>
        </form>
    </div>
</div>
@endsection