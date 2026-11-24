@extends('layouts.dashboard')

@section('title', 'Tambah Kendaraan - Parkir SMKN 8 Jember')

@section('content')

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

<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-navy">Tambah Kendaraan</h1>
        <p class="text-sm text-slate-500">Masukkan data kendaraan baru</p>
    </div>

    <div class="bg-white rounded-xl border border-gray p-6 shadow-sm">
        <form method="POST" action="{{ route('kendaraan.store') }}">
            @csrf

            <div class="space-y-4">
                <!-- Plat Nomor -->
                <div>
                    <label for="plat_nomor" class="block text-sm font-semibold text-slate-700 mb-1">Plat Nomor</label>
                    <input type="text" id="plat_nomor" name="plat_nomor" value="{{ old('plat_nomor') }}" 
                           class="w-full px-4 py-2.5 border border-gray rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-yellow/50 focus:border-yellow transition-all @error('plat_nomor') border-red-500 @enderror"
                           placeholder="Contoh: B 1234 ABC" required maxlength="20">
                    @error('plat_nomor')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nama Pemilik -->
                <div>
                    <label for="pemilik" class="block text-sm font-semibold text-slate-700 mb-1">Nama Pemilik</label>
                    <input type="text" id="pemilik" name="pemilik" value="{{ old('pemilik') }}" 
                           class="w-full px-4 py-2.5 border border-gray rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-yellow/50 focus:border-yellow transition-all @error('pemilik') border-red-500 @enderror"
                           placeholder="Nama lengkap pemilik kendaraan" required maxlength="255">
                    @error('pemilik')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Jenis & Merk: 2 kolom berdampingan -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="jenis_kendaraan" class="block text-sm font-semibold text-slate-700 mb-1">Jenis Kendaraan</label>
                        <select id="jenis_kendaraan" name="jenis_kendaraan" 
                                class="w-full px-4 py-2.5 border border-gray rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-yellow/50 focus:border-yellow transition-all @error('jenis_kendaraan') border-red-500 @enderror" required>
                            <option value="">Pilih Jenis</option>
                            <option value="Motor" {{ old('jenis_kendaraan') == 'Motor' ? 'selected' : '' }}>Motor</option>
                            <option value="Mobil" {{ old('jenis_kendaraan') == 'Mobil' ? 'selected' : '' }}>Mobil</option>
                        </select>
                        @error('jenis_kendaraan')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="merk" class="block text-sm font-semibold text-slate-700 mb-1">Merk</label>
                        <input type="text" id="merk" name="merk" value="{{ old('merk') }}" 
                               class="w-full px-4 py-2.5 border border-gray rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-yellow/50 focus:border-yellow transition-all @error('merk') border-red-500 @enderror"
                               placeholder="Contoh: Honda Vario" required maxlength="255">
                        @error('merk')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Warna -->
                <div>
                    <label for="warna" class="block text-sm font-semibold text-slate-700 mb-1">Warna</label>
                    <input type="text" id="warna" name="warna" value="{{ old('warna') }}" 
                           class="w-full px-4 py-2.5 border border-gray rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-yellow/50 focus:border-yellow transition-all @error('warna') border-red-500 @enderror"
                           placeholder="Contoh: Hitam, Putih, Merah" required maxlength="100">
                    @error('warna')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Tombol Simpan & Batal -->
            <div class="flex gap-3 mt-6 pt-4 border-t border-gray">
                <button type="submit" class="bg-yellow text-navy font-bold px-6 py-2.5 rounded-lg hover:bg-yellow/90 transition-all shadow-sm flex items-center gap-2">
                    <span class="material-symbols-outlined">save</span>
                    Simpan
                </button>
                <a href="{{ route('kendaraan.index') }}" class="bg-gray-100 text-slate-600 font-bold px-6 py-2.5 rounded-lg hover:bg-gray-200 transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined">close</span>
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection