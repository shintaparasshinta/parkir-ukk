{{--
  HALAMAN NOTIFIKASI
  - Icon per tipe di sebelah judul
  - Semua emoji di database dihilangkan, diganti icon
--}}
@extends('layouts.dashboard') {{-- Menggunakan layout dashboard --}}

@section('title', 'Notifikasi - Parkir SMKN 8 Jember') {{-- Judul halaman --}}

@section('content') {{-- Bagian konten --}}
<!-- HEADER -->
<div class="mb-6">
    <h1 class="text-2xl font-bold text-navy">Notifikasi</h1> {{-- Judul --}}
    <p class="text-sm text-slate-500 mt-1">Semua notifikasi Anda</p> {{-- Subjudul --}}
</div>

<!-- ALERT sukses -->
@if(session('success')) {{-- Jika ada session sukses --}}
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4 flex items-center gap-2"> {{-- Alert hijau --}}
        <span class="material-symbols-outlined text-green-500">check_circle</span> {{-- Ikon centang --}}
        {{ session('success') }} {{-- Pesan --}}
    </div>
@endif

<!-- INFO -->
<div class="flex justify-between items-center mb-4"> {{-- Header info --}}
    <span class="text-sm text-slate-500">{{ $notifications->total() }} notifikasi</span> {{-- Total notifikasi --}}
    <a href="{{ route('notifications.mark-all-read') }}" class="text-xs text-yellow-600 hover:text-yellow-800 flex items-center gap-1 font-semibold"> {{-- Tombol tandai semua --}}
        <span class="material-symbols-outlined text-[16px]">done_all</span> {{-- Ikon done all --}}
        Tandai Semua Dibaca {{-- Label --}}
    </a>
</div>

<!-- LIST NOTIFIKASI -->
<div class="space-y-3"> {{-- Stack vertikal --}}
    @forelse($notifications as $item) {{-- Loop notifikasi --}}
    <div class="bg-white rounded-xl border border-gray p-4 shadow-sm {{ $item->is_read ? '' : 'border-l-4 border-l-yellow' }}"> {{-- Card notifikasi, highlight jika belum dibaca --}}
        <div class="flex justify-between items-start"> {{-- Layout horizontal --}}
            <div class="flex-1"> {{-- Kolom kiri: konten --}}
                {{-- Baris badge tipe + icon --}}
                <div class="flex items-center gap-2 flex-wrap"> {{-- Container badge --}}
                    <span class="inline-flex items-center gap-1 text-xs font-bold px-2 py-0.5 rounded
                        @if($item->type == 'success') bg-green-100 text-green-700
                        @elseif($item->type == 'warning') bg-yellow-100 text-yellow-700
                        @elseif($item->type == 'danger') bg-red-100 text-red-700
                        @else bg-blue-100 text-blue-700 @endif"> {{-- Badge warna sesuai tipe --}}
                        <span class="material-symbols-outlined text-[14px]"> {{-- Ikon tipe --}}
                            @if($item->type == 'success') check_circle
                            @elseif($item->type == 'warning') warning
                            @elseif($item->type == 'danger') error
                            @else info @endif
                        </span>
                        {{ strtoupper($item->type) }} {{-- Tipe uppercase --}}
                    </span>

                    @if(!$item->is_read) {{-- Jika belum dibaca --}}
                        <span class="inline-flex items-center gap-1 text-xs text-yellow-600 font-bold"> {{-- Badge "Baru" --}}
                            <span class="material-symbols-outlined text-[14px]" style="font-variation-settings: 'FILL' 1;">fiber_new</span> {{-- Ikon new --}}
                            Baru {{-- Label --}}
                        </span>
                    @endif
                </div>

                {{-- Judul + ICON di sebelahnya --}}
                <h3 class="font-bold text-navy mt-2 flex items-center gap-2"> {{-- Judul notifikasi --}}
                    {{-- Icon sesuai tipe notif --}}
                    <span class="material-symbols-outlined text-[18px]
                        @if($item->type == 'success') text-green-500
                        @elseif($item->type == 'warning') text-yellow-500
                        @elseif($item->type == 'danger') text-red-500
                        @else text-blue-500 @endif"> {{-- Ikon sesuai tipe --}}
                        @if($item->type == 'success') login
                        @elseif($item->type == 'warning') warning
                        @elseif($item->type == 'danger') error
                        @else logout @endif
                    </span>
                    {{-- Pakai preg_replace untuk jaga-jaga kalau masih ada emoji sisa --}}
                    {{ preg_replace('/[^\x00-\x7F]/u', '', $item->title) }} {{-- Judul tanpa karakter non-ASCII --}}
                </h3>

                <p class="text-sm text-slate-600 mt-1">{{ $item->message }}</p> {{-- Isi pesan --}}

                <p class="text-xs text-slate-400 mt-2 flex items-center gap-1"> {{-- Timestamp --}}
                    <span class="material-symbols-outlined text-[14px]">schedule</span> {{-- Ikon jam --}}
                    {{ $item->created_at->diffForHumans() }} {{-- Waktu relatif (misal: 5 menit lalu) --}}
                </p>
            </div>

            {{-- Kanan: tombol aksi --}}
            <div class="flex gap-3 items-center"> {{-- Container tombol --}}
                @if(!$item->is_read) {{-- Jika belum dibaca --}}
                    <a href="{{ route('notifications.mark-read', $item->id) }}" 
                       title="Tandai Dibaca"
                       class="text-xs text-yellow-600 hover:text-yellow-800 flex items-center gap-1 font-semibold whitespace-nowrap"> {{-- Tombol tandai --}}
                        <span class="material-symbols-outlined text-[16px]">done</span> {{-- Ikon done --}}
                        Tandai {{-- Label --}}
                    </a>
                @endif

                @if($item->link) {{-- Jika ada link --}}
                    <a href="{{ $item->link }}" 
                       title="Lihat Detail"
                       class="text-xs text-navy hover:text-yellow-600 flex items-center gap-1 font-semibold whitespace-nowrap"> {{-- Tombol lihat --}}
                        Lihat
                        <span class="material-symbols-outlined text-[16px]">arrow_forward</span> {{-- Ikon panah --}}
                    </a>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="bg-white rounded-xl border border-gray p-8 text-center"> {{-- Empty state --}}
        <span class="material-symbols-outlined text-5xl text-slate-300 block mb-3">notifications_off</span> {{-- Ikon besar --}}
        <p class="text-slate-500">Belum ada notifikasi.</p> {{-- Pesan --}}
    </div>
    @endforelse
</div>

<!-- PAGINATION -->
<div class="mt-4">
    {{ $notifications->links() }} {{-- Link pagination --}}
</div>
@endsection