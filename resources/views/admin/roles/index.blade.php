{{--
  HALAMAN MANAJEMEN HAK AKSES (RBAC)
  - 3 card per role: Admin, Petugas, Owner
  - Toggle on/off tiap fitur
  - Style card header disamakan dengan halaman lain
--}}
@extends('layouts.dashboard')

@section('title', 'Manajemen Hak Akses - Parkir SMKN 8 Jember')

@section('content')
<div class="max-w-[1440px] mx-auto">

    {{-- Header halaman --}}
    <div class="mb-6 flex justify-between items-end">
        <div>
            <h1 class="text-2xl font-bold text-navy">Manajemen Hak Akses</h1>
            <p class="text-sm text-slate-500 mt-1">Atur izin dan fitur yang tersedia untuk setiap peran pengguna.</p>
        </div>
    </div>

    {{-- Alert sukses --}}
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-green-500">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    {{-- Grid 3 kolom: 1 card per role --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        @foreach($roles as $role)
        <div class="bg-white rounded-xl border border-gray overflow-hidden flex flex-col h-full shadow-sm">
            {{-- Header card: nama role + deskripsi + ikon --}}
            <div class="p-6 border-b border-gray bg-blue-50 flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-bold text-navy">Role: {{ ucfirst($role->name) }}</h3>
                    <p class="text-sm text-slate-500 mt-1">
                        @if($role->name == 'admin') Akses penuh sistem
                        @elseif($role->name == 'petugas') Operasional harian
                        @else Pemantauan & Laporan @endif
                    </p>
                </div>
                <span class="material-symbols-outlined text-navy text-3xl">
                    @if($role->name == 'admin') admin_panel_settings
                    @elseif($role->name == 'petugas') badge
                    @else visibility @endif
                </span>
            </div>

            <div class="p-6 flex-1">
                {{-- Form update permission --}}
                <form method="POST" action="{{ route('roles.update', $role) }}">
                    @csrf
                    @method('PUT')

                    {{-- Tabel daftar fitur + toggle switch --}}
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b border-gray">
                                <th class="pb-3 text-xs font-semibold text-slate-500 uppercase tracking-wider w-12">No</th>
                                <th class="pb-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Menu Fitur</th>
                                <th class="pb-3 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Aktif?</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($permissions as $index => $permission)
                            <tr class="border-b border-gray last:border-0 hover:bg-gray-50 transition-colors">
                                <td class="py-3 text-slate-400 text-sm">{{ $index + 1 }}</td>
                                <td class="py-3 font-medium text-slate-700 text-sm">{{ ucwords(str_replace('_', ' ', $permission->name)) }}</td>
                                <td class="py-3 text-right">
                                    <!-- Toggle Switch -->
                                    <div class="relative inline-block w-12 align-middle select-none transition duration-200 ease-in">
                                        <input type="checkbox"
                                               name="permissions[]"
                                               value="{{ $permission->name }}"
                                               id="{{ $role->name }}_toggle_{{ $index }}"
                                               class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer"
                                               {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }} />
                                        <label for="{{ $role->name }}_toggle_{{ $index }}" class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer"></label>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{-- Tombol simpan --}}
                    <button type="submit" class="mt-6 w-full px-6 py-2.5 bg-yellow text-navy rounded-lg font-bold hover:bg-yellow/90 transition-all shadow-sm flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-sm">save</span>
                        Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Styling Toggle Switch -->
<style>
    .toggle-checkbox { position: absolute; opacity: 0; cursor: pointer; height: 0; width: 0; }
    .toggle-label { display: block; width: 48px; height: 26px; background-color: #e2e8f0; border-radius: 9999px; transition: all 0.3s; position: relative; }
    .toggle-label::before { content: ""; position: absolute; left: 3px; top: 3px; width: 20px; height: 20px; background-color: white; border-radius: 50%; transition: all 0.3s; box-shadow: 0 2px 4px rgba(0,0,0,0.2); }
    .toggle-checkbox:checked + .toggle-label { background-color: #10B981; }
    .toggle-checkbox:checked + .toggle-label::before { transform: translateX(22px); }
</style>
@endsection