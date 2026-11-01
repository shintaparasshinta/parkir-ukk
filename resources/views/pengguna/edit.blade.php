{{--
  FORM EDIT PENGGUNA
  - Sama seperti create, tapi value diambil dari $pengguna (data lama)
  - Password opsional: kosongkan kalau tidak ingin ganti
  - Submit ke route('pengguna.update', $pengguna->id) dengan method PUT
--}}
@extends('layouts.dashboard')

@section('title', 'Edit Pengguna - Parkir SMKN 8 Jember')

@section('content')
<div class="max-w-2xl mx-auto">
    <!-- HEADER -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-navy">Edit Pengguna</h1> {{-- Judul --}}
        <p class="text-sm text-slate-500 mt-1">Perbarui data pengguna</p> {{-- Subjudul --}}
    </div>

    <div class="bg-white rounded-xl border border-gray p-6 shadow-sm">
        <form method="POST" action="{{ route('pengguna.update', $pengguna->id) }}"> {{-- Form update --}}
            @csrf
            @method('PUT') {{-- Spoof method PUT --}}

            <div class="space-y-4">
                <!-- Nama Lengkap -->
                <div>
                    <label for="nama" class="block text-sm font-semibold text-slate-700 mb-1">Nama Lengkap</label>
                    <input type="text" id="nama" name="nama" value="{{ old('nama', $pengguna->nama) }}" {{-- Prefill dari DB --}}
                           class="w-full px-4 py-2.5 border border-gray rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-yellow/50 focus:border-yellow transition-all @error('nama') border-red-500 @enderror"
                           placeholder="Masukkan nama lengkap" required>
                    @error('nama')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Username -->
                <div>
                    <label for="username" class="block text-sm font-semibold text-slate-700 mb-1">Username</label>
                    <input type="text" id="username" name="username" value="{{ old('username', $pengguna->username) }}" 
                           class="w-full px-4 py-2.5 border border-gray rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-yellow/50 focus:border-yellow transition-all @error('username') border-red-500 @enderror"
                           placeholder="Masukkan username" required>
                    @error('username')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password (Opsional) -->
                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-700 mb-1">Password (kosongkan jika tidak diubah)</label>
                    <input type="password" id="password" name="password" 
                           class="w-full px-4 py-2.5 border border-gray rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-yellow/50 focus:border-yellow transition-all @error('password') border-red-500 @enderror"
                           placeholder="Minimal 6 karakter"> {{-- Kosong = tidak ganti password --}}
                    @error('password')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Role -->
                <div>
                    <label for="role" class="block text-sm font-semibold text-slate-700 mb-1">Role / Hak Akses</label>
                    <select id="role" name="role" 
                            class="w-full px-4 py-2.5 border border-gray rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-yellow/50 focus:border-yellow transition-all @error('role') border-red-500 @enderror" required>
                        <option value="">Pilih Role</option>
                        <option value="admin" {{ old('role', $pengguna->role) == 'admin' ? 'selected' : '' }}>Administrator</option> {{-- Selected kalau role sekarang admin --}}
                        <option value="petugas" {{ old('role', $pengguna->role) == 'petugas' ? 'selected' : '' }}>Petugas</option>
                        <option value="owner" {{ old('role', $pengguna->role) == 'owner' ? 'selected' : '' }}>Owner</option>
                    </select>
                    @error('role')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Tombol Update & Batal -->
            <div class="flex gap-3 mt-6 pt-4 border-t border-gray">
                <button type="submit" class="bg-yellow text-navy font-bold px-6 py-2.5 rounded-lg hover:bg-yellow/90 transition-all shadow-sm flex items-center gap-2">
                    <span class="material-symbols-outlined">save</span>
                    Update
                </button>
                <a href="{{ route('pengguna.index') }}" class="bg-gray-100 text-slate-600 font-bold px-6 py-2.5 rounded-lg hover:bg-gray-200 transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined">close</span>
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection