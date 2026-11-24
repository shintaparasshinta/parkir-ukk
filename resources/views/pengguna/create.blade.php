{{--
  FORM TAMBAH PENGGUNA
  - Input: nama, username, password, role (admin/petugas/owner)
  - Submit ke route('pengguna.store') dengan method POST
--}}
@extends('layouts.dashboard') {{-- Menggunakan layout dashboard --}}

@section('title', 'Tambah Pengguna - Parkir SMKN 8 Jember') {{-- Judul halaman --}}

@section('content') {{-- Bagian konten --}}
<div class="max-w-2xl mx-auto"> {{-- Container max-width 2xl, posisi tengah --}}
    <!-- HEADER -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-navy">Tambah Pengguna</h1> {{-- Judul --}}
        <p class="text-sm text-slate-500 mt-1">Buat akun pengguna baru untuk sistem parkir</p> {{-- Subjudul --}}
    </div>

    <div class="bg-white rounded-xl border border-gray p-6 shadow-sm"> {{-- Card form --}}
        <form method="POST" action="{{ route('pengguna.store') }}"> {{-- Form POST --}}
            @csrf {{-- Token CSRF --}}

            <div class="space-y-4"> {{-- Container input dengan spacing vertikal --}}
                <!-- Nama Lengkap -->
                <div>
                    <label for="nama" class="block text-sm font-semibold text-slate-700 mb-1">Nama Lengkap</label> {{-- Label --}}
                    <input type="text" id="nama" name="nama" value="{{ old('nama') }}" {{-- Restore input --}}
                           class="w-full px-4 py-2.5 border border-gray rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-yellow/50 focus:border-yellow transition-all @error('nama') border-red-500 @enderror"
                           placeholder="Masukkan nama lengkap" required> {{-- Placeholder & wajib --}}
                    @error('nama') {{-- Error validasi --}}
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p> {{-- Pesan error --}}
                    @enderror
                </div>

                <!-- Username -->
                <div>
                    <label for="username" class="block text-sm font-semibold text-slate-700 mb-1">Username</label> {{-- Label --}}
                    <input type="text" id="username" name="username" value="{{ old('username') }}" {{-- Restore input --}}
                           class="w-full px-4 py-2.5 border border-gray rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-yellow/50 focus:border-yellow transition-all @error('username') border-red-500 @enderror"
                           placeholder="Masukkan username" required>
                    @error('username')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p> {{-- Pesan error --}}
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-700 mb-1">Password</label> {{-- Label --}}
                    <input type="password" id="password" name="password" 
                           class="w-full px-4 py-2.5 border border-gray rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-yellow/50 focus:border-yellow transition-all @error('password') border-red-500 @enderror"
                           placeholder="Minimal 6 karakter" required> {{-- Min 6 karakter --}}
                    @error('password')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p> {{-- Pesan error --}}
                    @enderror
                </div>

                <!-- Role -->
                <div>
                    <label for="role" class="block text-sm font-semibold text-slate-700 mb-1">Role / Hak Akses</label> {{-- Label --}}
                    <select id="role" name="role" 
                            class="w-full px-4 py-2.5 border border-gray rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-yellow/50 focus:border-yellow transition-all @error('role') border-red-500 @enderror" required>
                        <option value="">Pilih Role</option> {{-- Default --}}
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrator</option> {{-- Admin --}}
                        <option value="petugas" {{ old('role') == 'petugas' ? 'selected' : '' }}>Petugas</option> {{-- Petugas --}}
                        <option value="owner" {{ old('role') == 'owner' ? 'selected' : '' }}>Owner</option> {{-- Owner --}}
                    </select>
                    @error('role')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p> {{-- Pesan error --}}
                    @enderror
                </div>
            </div>

            <!-- Tombol Simpan & Batal -->
            <div class="flex gap-3 mt-6 pt-4 border-t border-gray"> {{-- Container tombol --}}
                <button type="submit" class="bg-yellow text-navy font-bold px-6 py-2.5 rounded-lg hover:bg-yellow/90 transition-all shadow-sm flex items-center gap-2"> {{-- Tombol simpan --}}
                    <span class="material-symbols-outlined">save</span> {{-- Ikon simpan --}}
                    Simpan {{-- Label --}}
                </button>
                <a href="{{ route('pengguna.index') }}" class="bg-gray-100 text-slate-600 font-bold px-6 py-2.5 rounded-lg hover:bg-gray-200 transition-all flex items-center gap-2"> {{-- Tombol batal --}}
                    <span class="material-symbols-outlined">close</span> {{-- Ikon batal --}}
                    Batal {{-- Label --}}
                </a>
            </div>
        </form>
    </div>
</div>
@endsection