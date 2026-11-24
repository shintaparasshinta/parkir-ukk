{{--
  HALAMAN MANAJEMEN HAK AKSES (RBAC)
  - 3 card per role: Admin, Petugas, Owner
  - Toggle on/off tiap fitur
  - Style card header disamakan dengan halaman lain
--}}
@extends('layouts.dashboard') {{-- Menggunakan layout dashboard (dengan sidebar & navbar) --}}

@section('title', 'Manajemen Hak Akses - Parkir SMKN 8 Jember') {{-- Judul halaman --}}

@section('content') {{-- Bagian konten utama --}}
<div class="max-w-[1440px] mx-auto"> {{-- Container utama dengan lebar maksimum & posisi tengah --}}

    {{-- Header halaman --}}
    <div class="mb-6 flex justify-between items-end"> {{-- Header dengan flexbox, margin bawah --}}
        <div>
            <h1 class="text-2xl font-bold text-navy">Manajemen Hak Akses</h1> {{-- Judul utama --}}
            <p class="text-sm text-slate-500 mt-1">Atur izin dan fitur yang tersedia untuk setiap peran pengguna.</p> {{-- Deskripsi singkat --}}
        </div>
    </div>

    {{-- Alert sukses --}}
    @if(session('success')) {{-- Jika ada session sukses --}}
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4 flex items-center gap-2"> {{-- Kotak alert hijau --}}
            <span class="material-symbols-outlined text-green-500">check_circle</span> {{-- Ikon centang --}}
            {{ session('success') }} {{-- Tampilkan pesan sukses --}}
        </div>
    @endif

    {{-- Grid 3 kolom: 1 card per role --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6"> {{-- Grid responsif: 1 kolom mobile, 3 kolom desktop --}}
        @foreach($roles as $role) {{-- Iterasi tiap role --}}
        <div class="bg-white rounded-xl border border-gray overflow-hidden flex flex-col h-full shadow-sm"> {{-- Card role --}}
            {{-- Header card: nama role + deskripsi + ikon --}}
            <div class="p-6 border-b border-gray bg-blue-50 flex justify-between items-center"> {{-- Header card dengan latar biru muda --}}
                <div>
                    <h3 class="text-lg font-bold text-navy">Role: {{ ucfirst($role->name) }}</h3> {{-- Nama role dengan huruf pertama kapital --}}
                    <p class="text-sm text-slate-500 mt-1"> {{-- Deskripsi role --}}
                        @if($role->name == 'admin') Akses penuh sistem {{-- Jika admin --}}
                        @elseif($role->name == 'petugas') Operasional harian {{-- Jika petugas --}}
                        @else Pemantauan & Laporan @endif {{-- Jika owner --}}
                    </p>
                </div>
                <span class="material-symbols-outlined text-navy text-3xl"> {{-- Ikon role --}}
                    @if($role->name == 'admin') admin_panel_settings {{-- Ikon admin --}}
                    @elseif($role->name == 'petugas') badge {{-- Ikon petugas --}}
                    @else visibility @endif {{-- Ikon owner --}}
                </span>
            </div>

            <div class="p-6 flex-1"> {{-- Body card --}}
                {{-- Form update permission --}}
                <form method="POST" action="{{ route('roles.update', $role) }}"> {{-- Form submit ke route update --}}
                    @csrf {{-- Token CSRF --}}
                    @method('PUT') {{-- Spoof method PUT --}}

                    {{-- Tabel daftar fitur + toggle switch --}}
                    <table class="w-full text-left"> {{-- Tabel daftar permission --}}
                        <thead>
                            <tr class="border-b border-gray"> {{-- Baris header --}}
                                <th class="pb-3 text-xs font-semibold text-slate-500 uppercase tracking-wider w-12">No</th> {{-- Kolom nomor --}}
                                <th class="pb-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Menu Fitur</th> {{-- Kolom nama fitur --}}
                                <th class="pb-3 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Aktif?</th> {{-- Kolom toggle --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($permissions as $index => $permission) {{-- Iterasi tiap permission --}}
                            <tr class="border-b border-gray last:border-0 hover:bg-gray-50 transition-colors"> {{-- Baris permission --}}
                                <td class="py-3 text-slate-400 text-sm">{{ $index + 1 }}</td> {{-- Nomor urut (1-based) --}}
                                <td class="py-3 font-medium text-slate-700 text-sm">{{ ucwords(str_replace('_', ' ', $permission->name)) }}</td> {{-- Nama fitur dirapikan (underscore → spasi, title case) --}}
                                <td class="py-3 text-right"> {{-- Kolom toggle --}}
                                    <!-- Toggle Switch -->
                                    <div class="relative inline-block w-12 align-middle select-none transition duration-200 ease-in"> {{-- Container toggle --}}
                                        <input type="checkbox"
                                               name="permissions[]" {{-- Nama array agar bisa multiple --}}
                                               value="{{ $permission->name }}" {{-- Value = nama permission --}}
                                               id="{{ $role->name }}_toggle_{{ $index }}" {{-- ID unik per role+permission --}}
                                               class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer" {{-- Class custom untuk styling --}}
                                               {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }} /> {{-- Centang jika role sudah punya permission --}}
                                        <label for="{{ $role->name }}_toggle_{{ $index }}" class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer"></label> {{-- Label visual toggle --}}
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{-- Tombol simpan --}}
                    <button type="submit" class="mt-6 w-full px-6 py-2.5 bg-yellow text-navy rounded-lg font-bold hover:bg-yellow/90 transition-all shadow-sm flex items-center justify-center gap-2"> {{-- Tombol submit --}}
                        <span class="material-symbols-outlined text-sm">save</span> {{-- Ikon simpan --}}
                        Simpan Perubahan {{-- Label tombol --}}
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Styling Toggle Switch -->
<style>
    .toggle-checkbox { position: absolute; opacity: 0; cursor: pointer; height: 0; width: 0; } /* Sembunyikan checkbox asli */
    .toggle-label { display: block; width: 48px; height: 26px; background-color: #e2e8f0; border-radius: 9999px; transition: all 0.3s; position: relative; } /* Track toggle */
    .toggle-label::before { content: ""; position: absolute; left: 3px; top: 3px; width: 20px; height: 20px; background-color: white; border-radius: 50%; transition: all 0.3s; box-shadow: 0 2px 4px rgba(0,0,0,0.2); } /* Knob toggle */
    .toggle-checkbox:checked + .toggle-label { background-color: #10B981; } /* Warna track saat aktif (hijau) */
    .toggle-checkbox:checked + .toggle-label::before { transform: translateX(22px); } /* Geser knob ke kanan saat aktif */
</style>
@endsection