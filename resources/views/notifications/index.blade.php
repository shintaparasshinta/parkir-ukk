{{--
  HALAMAN NOTIFIKASI
  - Icon per tipe di sebelah judul
  - Semua emoji di database dihilangkan, diganti icon
--}}
@extends('layouts.dashboard')

@section('title', 'Notifikasi - Parkir SMKN 8 Jember')

@section('content')
<!-- HEADER -->
<div class="mb-6">
    <h1 class="text-2xl font-bold text-navy">Notifikasi</h1>
    <p class="text-sm text-slate-500 mt-1">Semua notifikasi Anda</p>
</div>

<!-- ALERT sukses -->
@if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4 flex items-center gap-2">
        <span class="material-symbols-outlined text-green-500">check_circle</span>
        {{ session('success') }}
    </div>
@endif

<!-- INFO -->
<div class="flex justify-between items-center mb-4">
    <span class="text-sm text-slate-500">{{ $notifications->total() }} notifikasi</span>
    <a href="{{ route('notifications.mark-all-read') }}" class="text-xs text-yellow-600 hover:text-yellow-800 flex items-center gap-1 font-semibold">
        <span class="material-symbols-outlined text-[16px]">done_all</span>
        Tandai Semua Dibaca
    </a>
</div>

<!-- LIST NOTIFIKASI -->
<div class="space-y-3">
    @forelse($notifications as $item)
    <div class="bg-white rounded-xl border border-gray p-4 shadow-sm {{ $item->is_read ? '' : 'border-l-4 border-l-yellow' }}">
        <div class="flex justify-between items-start">
            <div class="flex-1">
                {{-- Baris badge tipe + icon --}}
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="inline-flex items-center gap-1 text-xs font-bold px-2 py-0.5 rounded
                        @if($item->type == 'success') bg-green-100 text-green-700
                        @elseif($item->type == 'warning') bg-yellow-100 text-yellow-700
                        @elseif($item->type == 'danger') bg-red-100 text-red-700
                        @else bg-blue-100 text-blue-700 @endif">
                        <span class="material-symbols-outlined text-[14px]">
                            @if($item->type == 'success') check_circle
                            @elseif($item->type == 'warning') warning
                            @elseif($item->type == 'danger') error
                            @else info @endif
                        </span>
                        {{ strtoupper($item->type) }}
                    </span>

                    @if(!$item->is_read)
                        <span class="inline-flex items-center gap-1 text-xs text-yellow-600 font-bold">
                            <span class="material-symbols-outlined text-[14px]" style="font-variation-settings: 'FILL' 1;">fiber_new</span>
                            Baru
                        </span>
                    @endif
                </div>

                {{-- Judul + ICON di sebelahnya --}}
                <h3 class="font-bold text-navy mt-2 flex items-center gap-2">
                    {{-- Icon sesuai tipe notif --}}
                    <span class="material-symbols-outlined text-[18px]
                        @if($item->type == 'success') text-green-500
                        @elseif($item->type == 'warning') text-yellow-500
                        @elseif($item->type == 'danger') text-red-500
                        @else text-blue-500 @endif">
                        @if($item->type == 'success') login
                        @elseif($item->type == 'warning') warning
                        @elseif($item->type == 'danger') error
                        @else logout @endif
                    </span>
                    {{-- Pakai preg_replace untuk jaga-jaga kalau masih ada emoji sisa --}}
                    {{ preg_replace('/[^\x00-\x7F]/u', '', $item->title) }}
                </h3>

                <p class="text-sm text-slate-600 mt-1">{{ $item->message }}</p>

                <p class="text-xs text-slate-400 mt-2 flex items-center gap-1">
                    <span class="material-symbols-outlined text-[14px]">schedule</span>
                    {{ $item->created_at->diffForHumans() }}
                </p>
            </div>

            {{-- Kanan: tombol aksi --}}
            <div class="flex gap-3 items-center">
                @if(!$item->is_read)
                    <a href="{{ route('notifications.mark-read', $item->id) }}" 
                       title="Tandai Dibaca"
                       class="text-xs text-yellow-600 hover:text-yellow-800 flex items-center gap-1 font-semibold whitespace-nowrap">
                        <span class="material-symbols-outlined text-[16px]">done</span>
                        Tandai
                    </a>
                @endif

                @if($item->link)
                    <a href="{{ $item->link }}" 
                       title="Lihat Detail"
                       class="text-xs text-navy hover:text-yellow-600 flex items-center gap-1 font-semibold whitespace-nowrap">
                        Lihat
                        <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                    </a>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="bg-white rounded-xl border border-gray p-8 text-center">
        <span class="material-symbols-outlined text-5xl text-slate-300 block mb-3">notifications_off</span>
        <p class="text-slate-500">Belum ada notifikasi.</p>
    </div>
    @endforelse
</div>

<!-- PAGINATION -->
<div class="mt-4">
    {{ $notifications->links() }}
</div>
@endsection