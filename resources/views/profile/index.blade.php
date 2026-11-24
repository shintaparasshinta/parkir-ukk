{{--
  HALAMAN PROFIL SAYA
  - Upload foto profil + crop pakai Cropper.js (rasio 1:1, output 400x400)
  - Edit nama & username (langsung pakai name="nama" & "username")
  - Tombol edit foto (ikon pensil), hapus foto (kalau ada)
  - Info akun (nama, username, role, status)
  - Variabel dari controller: $user
--}}
@extends('layouts.dashboard') {{-- Menggunakan layout dashboard --}}

@section('title', 'Profil Saya - Parkir SMKN 8 Jember') {{-- Judul halaman --}}

@section('content') {{-- Bagian konten --}}
<!-- Cropper.js CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css"> {{-- CSS Cropper.js --}}

<!-- HEADER -->
<div class="mb-6">
    <h1 class="text-2xl font-bold text-navy">Profil Saya</h1> {{-- Judul --}}
    <p class="text-sm text-slate-500 mt-1">Kelola informasi akun dan pengaturan profil Anda</p> {{-- Subjudul --}}
</div>

<!-- ALERT -->
@if(session('success')) {{-- Jika ada session sukses --}}
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4 flex items-center gap-2"> {{-- Alert hijau --}}
        <span class="material-symbols-outlined text-green-500">check_circle</span> {{-- Ikon centang --}}
        {{ session('success') }} {{-- Pesan sukses --}}
    </div>
@endif

@if(session('error')) {{-- Jika ada session error --}}
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4 flex items-center gap-2"> {{-- Alert merah --}}
        <span class="material-symbols-outlined text-red-500">error</span> {{-- Ikon error --}}
        {{ session('error') }} {{-- Pesan error --}}
    </div>
@endif

<!-- GRID -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-6"> {{-- Grid 12 kolom untuk layout asimetris --}}
    <!-- LEFT: Profile Card -->
    <div class="lg:col-span-4"> {{-- Card profil (4 kolom) --}}
        <div class="bg-white border border-gray rounded-xl p-6 shadow-sm"> {{-- Container card --}}
            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="flex flex-col items-center" id="profileForm"> {{-- Form update profil --}}
                @csrf {{-- Token CSRF --}}
                @method('PUT') {{-- Spoof method PUT --}}

                <!-- Input hidden untuk kirim hasil crop -->
                <input type="hidden" name="foto_cropped" id="fotoCropped"> {{-- Hidden input untuk base64 foto --}}
                <input type="file" id="fotoInput" accept="image/*" class="hidden"> {{-- Input file (disembunyikan) --}}

                <!-- AVATAR + ICON EDIT -->
                <div class="relative mb-4 group"> {{-- Container avatar --}}
                    @if($user->foto) {{-- Jika user punya foto --}}
                        <img src="{{ asset('storage/' . $user->foto) }}" alt="Foto Profil" class="w-32 h-32 rounded-full object-cover border-4 border-white shadow-md"> {{-- Tampilkan foto --}}
                    @else
                        <div class="w-32 h-32 rounded-full bg-navy text-white flex items-center justify-center text-5xl font-bold border-4 border-white shadow-sm"> {{-- Avatar inisial --}}
                            {{ strtoupper(substr($user->nama, 0, 2)) }} {{-- 2 huruf pertama nama --}}
                        </div>
                    @endif

                    <button type="button" onclick="document.getElementById('fotoInput').click()" title="Ganti Foto Profil"
                        class="absolute bottom-1 right-1 w-9 h-9 bg-navy text-white rounded-full flex items-center justify-center shadow-lg hover:bg-yellow hover:text-navy transition-all border-2 border-white"> {{-- Tombol edit foto --}}
                        <span class="material-symbols-outlined text-[18px]">edit</span> {{-- Ikon edit --}}
                    </button>
                </div>

                @if($user->foto) {{-- Jika ada foto --}}
                <a href="{{ route('profile.delete-photo') }}" onclick="return confirm('Yakin ingin menghapus foto profil?')"
                   class="text-xs text-red-500 hover:text-red-700 font-semibold mb-3 flex items-center gap-1"> {{-- Tombol hapus foto --}}
                    <span class="material-symbols-outlined text-[14px]">delete</span> {{-- Ikon hapus --}}
                    Hapus Foto {{-- Label --}}
                </a>
                @endif

                <h3 class="text-xl font-bold text-navy mb-1">{{ $user->nama }}</h3> {{-- Nama user --}}
                <p class="text-sm text-slate-500 mb-3 capitalize">{{ $user->role }}</p> {{-- Role user --}}
                <div class="bg-green-50 text-green-600 px-3 py-1 rounded-full text-xs font-bold flex items-center gap-1 mb-6"> {{-- Badge status --}}
                    <span class="w-2 h-2 rounded-full bg-green-500"></span> {{-- Dot hijau --}}
                    Aktif {{-- Label --}}
                </div>

                <!-- FORM INPUT: langsung pakai name="nama" & name="username" -->
                <div class="w-full text-left border-t border-gray pt-6"> {{-- Container input --}}
                    <div class="mb-4">
                        <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Nama Lengkap</label> {{-- Label --}}
                        <input type="text" name="nama" value="{{ old('nama', $user->nama) }}"
                            class="w-full bg-gray-50 border border-gray rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-navy focus:ring-1 focus:ring-navy transition-all"> {{-- Input nama --}}
                        @error('nama')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p> {{-- Pesan error --}}
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Username</label> {{-- Label --}}
                        <input type="text" name="username" value="{{ old('username', $user->username) }}"
                            class="w-full bg-gray-50 border border-gray rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-navy focus:ring-1 focus:ring-navy transition-all"> {{-- Input username --}}
                        @error('username')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p> {{-- Pesan error --}}
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Role</label> {{-- Label --}}
                        <input type="text" value="{{ ucfirst($user->role) }}"
                            class="w-full bg-gray-100 border border-gray rounded-lg px-3 py-2 text-sm text-slate-400 cursor-not-allowed" disabled> {{-- Input disabled (readonly) --}}
                    </div>
                </div>

                <button type="submit" class="w-full mt-2 bg-yellow text-navy font-bold py-2 px-4 rounded-lg hover:bg-yellow/90 transition-all shadow-sm flex items-center justify-center gap-2"> {{-- Tombol simpan --}}
                    <span class="material-symbols-outlined text-sm">save</span> {{-- Ikon simpan --}}
                    Simpan Perubahan {{-- Label --}}
                </button>
            </form>
        </div>
    </div>

    <!-- RIGHT: Info Akun -->
    <div class="lg:col-span-8"> {{-- Card info akun (8 kolom) --}}
        <div class="bg-white border border-gray rounded-xl shadow-sm overflow-hidden"> {{-- Container card --}}
            <div class="px-6 py-4 border-b border-gray bg-gray-50"> {{-- Header card --}}
                <h3 class="text-lg font-bold text-navy flex items-center gap-2"> {{-- Judul --}}
                    <span class="material-symbols-outlined text-navy">info</span> {{-- Ikon info --}}
                    Informasi Akun {{-- Label --}}
                </h3>
            </div>
            <div class="p-6"> {{-- Body card --}}
                <div class="space-y-4"> {{-- Stack vertikal --}}
                    <div class="flex justify-between items-center border-b border-gray pb-3"> {{-- Baris nama --}}
                        <span class="text-sm font-semibold text-slate-600">Nama Lengkap</span> {{-- Label --}}
                        <span class="text-sm text-navy">{{ $user->nama }}</span> {{-- Nilai --}}
                    </div>
                    <div class="flex justify-between items-center border-b border-gray pb-3"> {{-- Baris username --}}
                        <span class="text-sm font-semibold text-slate-600">Username</span> {{-- Label --}}
                        <span class="text-sm text-navy">{{ $user->username }}</span> {{-- Nilai --}}
                    </div>
                    <div class="flex justify-between items-center border-b border-gray pb-3"> {{-- Baris role --}}
                        <span class="text-sm font-semibold text-slate-600">Role</span> {{-- Label --}}
                        <span class="text-sm font-bold text-navy capitalize">{{ $user->role }}</span> {{-- Nilai --}}
                    </div>
                    <div class="flex justify-between items-center"> {{-- Baris status --}}
                        <span class="text-sm font-semibold text-slate-600">Status Akun</span> {{-- Label --}}
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200"> {{-- Badge status --}}
                            Aktif {{-- Label --}}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL CROP -->
<div id="cropModal" class="fixed inset-0 bg-black bg-opacity-75 z-50 hidden items-center justify-center"> {{-- Modal crop --}}
    <div class="bg-white rounded-xl p-6 max-w-lg w-full mx-4"> {{-- Card modal --}}
        <h3 class="text-lg font-bold text-navy mb-4">Atur Foto Profil</h3> {{-- Judul --}}
        <div class="w-full h-80 bg-gray-100 rounded-lg overflow-hidden mb-4"> {{-- Container gambar --}}
            <img id="imageToCrop" class="max-w-full"> {{-- Gambar yang akan di-crop --}}
        </div>
        <div class="flex justify-end gap-3"> {{-- Container tombol --}}
            <button type="button" onclick="closeCropModal()"
                    class="bg-gray-100 text-slate-600 font-bold px-6 py-2 rounded-lg hover:bg-gray-200 transition-all"> {{-- Tombol batal --}}
                Batal {{-- Label --}}
            </button>
            <button type="button" onclick="saveCroppedImage()"
                    class="bg-yellow text-navy font-bold px-6 py-2 rounded-lg hover:bg-yellow/90 transition-all flex items-center gap-2"> {{-- Tombol simpan --}}
                <span class="material-symbols-outlined text-sm">check</span> {{-- Ikon check --}}
                Simpan {{-- Label --}}
            </button>
        </div>
    </div>
</div>

<!-- Cropper.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script> {{-- Library Cropper.js --}}
<script>
    let cropper = null; {{-- Instance cropper (null = belum diinisialisasi) --}}

    document.getElementById('fotoInput').addEventListener('change', function(e) { {{-- Saat user pilih file --}}
        const file = e.target.files[0]; {{-- Ambil file pertama --}}
        if (!file) return; {{-- Jika tidak ada file, keluar --}}

        const reader = new FileReader(); {{-- FileReader untuk baca file --}}
        reader.onload = function(event) { {{-- Setelah file terbaca --}}
            const img = document.getElementById('imageToCrop'); {{-- Ambil elemen img --}}
            img.src = event.target.result; {{-- Set src ke data URL --}}

            document.getElementById('cropModal').classList.remove('hidden'); {{-- Tampilkan modal --}}
            document.getElementById('cropModal').classList.add('flex');

            if (cropper) cropper.destroy(); {{-- Destroy cropper lama jika ada --}}
            cropper = new Cropper(img, { {{-- Inisialisasi Cropper.js --}}
                aspectRatio: 1, {{-- Rasio 1:1 (persegi) --}}
                viewMode: 1, {{-- Batasi crop di dalam gambar --}}
                dragMode: 'move', {{-- Mode drag: pindahkan gambar --}}
                autoCropArea: 1, {{-- Area crop otomatis penuh --}}
                background: false, {{-- Sembunyikan background grid --}}
            });
        };
        reader.readAsDataURL(file); {{-- Baca file sebagai data URL --}}
    });

    function closeCropModal() { {{-- Fungsi tutup modal crop --}}
        document.getElementById('cropModal').classList.add('hidden'); {{-- Sembunyikan modal --}}
        document.getElementById('cropModal').classList.remove('flex');
        if (cropper) { {{-- Jika ada cropper --}}
            cropper.destroy(); {{-- Hancurkan --}}
            cropper = null; {{-- Reset --}}
        }
        document.getElementById('fotoInput').value = ''; {{-- Reset input file --}}
    }

    function saveCroppedImage() { {{-- Fungsi simpan hasil crop --}}
        if (!cropper) return; {{-- Keluar jika tidak ada cropper --}}

        const canvas = cropper.getCroppedCanvas({ width: 400, height: 400 }); {{-- Ambil canvas 400x400 --}}
        const croppedData = canvas.toDataURL('image/png'); {{-- Konversi ke base64 PNG --}}
        document.getElementById('fotoCropped').value = croppedData; {{-- Set nilai hidden input --}}

        closeCropModal(); {{-- Tutup modal --}}
        document.getElementById('profileForm').submit(); {{-- Submit form --}}
    }
</script>
@endsection