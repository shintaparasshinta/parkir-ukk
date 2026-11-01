{{--
  HALAMAN DETAIL LOG AKTIVITAS
  - Menampilkan: Waktu, Pengguna, Username, Role, Aktivitas
  - Tombol kembali ke daftar log
  - Variabel dari controller: $log
--}}
@extends('layouts.dashboard')

@section('title', 'Detail Log Aktivitas - Parkir SMKN 8 Jember')

@section('content')
<div class="max-w-2xl mx-auto"> {{-- Card di tengah, max 2xl --}}
    <!-- HEADER: judul + subjudul -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-navy">Detail Log Aktivitas</h1> {{-- Judul --}}
        <p class="text-sm text-slate-500 mt-1">Informasi lengkap aktivitas pengguna</p> {{-- Subjudul --}}
    </div>

    <div class="bg-white rounded-xl border border-gray p-6 shadow-sm">
        <div class="space-y-4"> {{-- Daftar detail, kasih jarak --}}
            <!-- Baris: Waktu -->
            <div class="flex justify-between items-center border-b border-gray pb-3">
                <span class="text-sm font-semibold text-slate-600">Waktu</span> {{-- Label --}}
                <span class="text-sm text-navy">{{ $log->created_at->format('d/m/Y H:i:s') }}</span> {{-- Nilai waktu --}}
            </div>

            <!-- Baris: Pengguna -->
            <div class="flex justify-between items-center border-b border-gray pb-3">
                <span class="text-sm font-semibold text-slate-600">Pengguna</span>
                <span class="text-sm text-navy">{{ $log->user->nama ?? 'User Dihapus' }}</span> {{-- Nama atau fallback --}}
            </div>

            <!-- Baris: Username -->
            <div class="flex justify-between items-center border-b border-gray pb-3">
                <span class="text-sm font-semibold text-slate-600">Username</span>
                <span class="text-sm text-navy">{{ $log->user->username ?? '-' }}</span> {{-- Username --}}
            </div>

            <!-- Baris: Role -->
            <div class="flex justify-between items-center border-b border-gray pb-3">
                <span class="text-sm font-semibold text-slate-600">Role</span>
                <span class="text-sm text-navy">{{ $log->user->role ?? '-' }}</span> {{-- Role user --}}
            </div>

            <!-- Baris: Aktivitas (align top, karena bisa panjang) -->
            <div class="flex justify-between items-start border-b border-gray pb-3">
                <span class="text-sm font-semibold text-slate-600">Aktivitas</span>
                <span class="text-sm text-navy text-right max-w-[60%]">{{ $log->aktivitas }}</span> {{-- Teks aktivitas --}}
            </div>
        </div>

        <!-- Tombol Kembali -->
        <div class="flex gap-3 mt-6 pt-4 border-t border-gray">
            <a href="{{ route('log_aktivitas.index') }}" class="bg-gray-100 text-slate-600 font-bold px-6 py-2.5 rounded-lg hover:bg-gray-200 transition-all flex items-center gap-2">
                <span class="material-symbols-outlined">arrow_back</span> {{-- Ikon panah --}}
                Kembali
            </a>
        </div>
    </div>
</div>
@endsection