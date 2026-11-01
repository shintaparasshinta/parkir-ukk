{{--
  HALAMAN PROFIL SAYA
  - Upload foto profil + crop pakai Cropper.js (rasio 1:1, output 400x400)
  - Edit nama & username (langsung pakai name="nama" & "username")
  - Tombol edit foto (ikon pensil), hapus foto (kalau ada)
  - Info akun (nama, username, role, status)
  - Variabel dari controller: $user
--}}
@extends('layouts.dashboard')

@section('title', 'Profil Saya - Parkir SMKN 8 Jember')

@section('content')
<!-- Cropper.js CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css">

<!-- HEADER -->
<div class="mb-6">
    <h1 class="text-2xl font-bold text-navy">Profil Saya</h1>
    <p class="text-sm text-slate-500 mt-1">Kelola informasi akun dan pengaturan profil Anda</p>
</div>

<!-- ALERT -->
@if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4 flex items-center gap-2">
        <span class="material-symbols-outlined text-green-500">check_circle</span>
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4 flex items-center gap-2">
        <span class="material-symbols-outlined text-red-500">error</span>
        {{ session('error') }}
    </div>
@endif

<!-- GRID -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
    <!-- LEFT: Profile Card -->
    <div class="lg:col-span-4">
        <div class="bg-white border border-gray rounded-xl p-6 shadow-sm">
            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="flex flex-col items-center" id="profileForm">
                @csrf
                @method('PUT')

                <!-- Input hidden untuk kirim hasil crop -->
                <input type="hidden" name="foto_cropped" id="fotoCropped">
                <input type="file" id="fotoInput" accept="image/*" class="hidden">

                <!-- AVATAR + ICON EDIT -->
                <div class="relative mb-4 group">
                    @if($user->foto)
                        <img src="{{ asset('storage/' . $user->foto) }}" alt="Foto Profil" class="w-32 h-32 rounded-full object-cover border-4 border-white shadow-md">
                    @else
                        <div class="w-32 h-32 rounded-full bg-navy text-white flex items-center justify-center text-5xl font-bold border-4 border-white shadow-sm">
                            {{ strtoupper(substr($user->nama, 0, 2)) }}
                        </div>
                    @endif

                    <button type="button" onclick="document.getElementById('fotoInput').click()" title="Ganti Foto Profil"
                        class="absolute bottom-1 right-1 w-9 h-9 bg-navy text-white rounded-full flex items-center justify-center shadow-lg hover:bg-yellow hover:text-navy transition-all border-2 border-white">
                        <span class="material-symbols-outlined text-[18px]">edit</span>
                    </button>
                </div>

                @if($user->foto)
                <a href="{{ route('profile.delete-photo') }}" onclick="return confirm('Yakin ingin menghapus foto profil?')"
                   class="text-xs text-red-500 hover:text-red-700 font-semibold mb-3 flex items-center gap-1">
                    <span class="material-symbols-outlined text-[14px]">delete</span>
                    Hapus Foto
                </a>
                @endif

                <h3 class="text-xl font-bold text-navy mb-1">{{ $user->nama }}</h3>
                <p class="text-sm text-slate-500 mb-3 capitalize">{{ $user->role }}</p>
                <div class="bg-green-50 text-green-600 px-3 py-1 rounded-full text-xs font-bold flex items-center gap-1 mb-6">
                    <span class="w-2 h-2 rounded-full bg-green-500"></span>
                    Aktif
                </div>

                <!-- FORM INPUT: langsung pakai name="nama" & name="username" -->
                <div class="w-full text-left border-t border-gray pt-6">
                    <div class="mb-4">
                        <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Nama Lengkap</label>
                        <input type="text" name="nama" value="{{ old('nama', $user->nama) }}"
                            class="w-full bg-gray-50 border border-gray rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-navy focus:ring-1 focus:ring-navy transition-all">
                        @error('nama')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Username</label>
                        <input type="text" name="username" value="{{ old('username', $user->username) }}"
                            class="w-full bg-gray-50 border border-gray rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-navy focus:ring-1 focus:ring-navy transition-all">
                        @error('username')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Role</label>
                        <input type="text" value="{{ ucfirst($user->role) }}"
                            class="w-full bg-gray-100 border border-gray rounded-lg px-3 py-2 text-sm text-slate-400 cursor-not-allowed" disabled>
                    </div>
                </div>

                <button type="submit" class="w-full mt-2 bg-yellow text-navy font-bold py-2 px-4 rounded-lg hover:bg-yellow/90 transition-all shadow-sm flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-sm">save</span>
                    Simpan Perubahan
                </button>
            </form>
        </div>
    </div>

    <!-- RIGHT: Info Akun -->
    <div class="lg:col-span-8">
        <div class="bg-white border border-gray rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray bg-gray-50">
                <h3 class="text-lg font-bold text-navy flex items-center gap-2">
                    <span class="material-symbols-outlined text-navy">info</span>
                    Informasi Akun
                </h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex justify-between items-center border-b border-gray pb-3">
                        <span class="text-sm font-semibold text-slate-600">Nama Lengkap</span>
                        <span class="text-sm text-navy">{{ $user->nama }}</span>
                    </div>
                    <div class="flex justify-between items-center border-b border-gray pb-3">
                        <span class="text-sm font-semibold text-slate-600">Username</span>
                        <span class="text-sm text-navy">{{ $user->username }}</span>
                    </div>
                    <div class="flex justify-between items-center border-b border-gray pb-3">
                        <span class="text-sm font-semibold text-slate-600">Role</span>
                        <span class="text-sm font-bold text-navy capitalize">{{ $user->role }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-semibold text-slate-600">Status Akun</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                            Aktif
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL CROP -->
<div id="cropModal" class="fixed inset-0 bg-black bg-opacity-75 z-50 hidden items-center justify-center">
    <div class="bg-white rounded-xl p-6 max-w-lg w-full mx-4">
        <h3 class="text-lg font-bold text-navy mb-4">Atur Foto Profil</h3>
        <div class="w-full h-80 bg-gray-100 rounded-lg overflow-hidden mb-4">
            <img id="imageToCrop" class="max-w-full">
        </div>
        <div class="flex justify-end gap-3">
            <button type="button" onclick="closeCropModal()"
                    class="bg-gray-100 text-slate-600 font-bold px-6 py-2 rounded-lg hover:bg-gray-200 transition-all">
                Batal
            </button>
            <button type="button" onclick="saveCroppedImage()"
                    class="bg-yellow text-navy font-bold px-6 py-2 rounded-lg hover:bg-yellow/90 transition-all flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">check</span>
                Simpan
            </button>
        </div>
    </div>
</div>

<!-- Cropper.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
<script>
    let cropper = null;

    document.getElementById('fotoInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function(event) {
            const img = document.getElementById('imageToCrop');
            img.src = event.target.result;

            document.getElementById('cropModal').classList.remove('hidden');
            document.getElementById('cropModal').classList.add('flex');

            if (cropper) cropper.destroy();
            cropper = new Cropper(img, {
                aspectRatio: 1,
                viewMode: 1,
                dragMode: 'move',
                autoCropArea: 1,
                background: false,
            });
        };
        reader.readAsDataURL(file);
    });

    function closeCropModal() {
        document.getElementById('cropModal').classList.add('hidden');
        document.getElementById('cropModal').classList.remove('flex');
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
        document.getElementById('fotoInput').value = '';
    }

    function saveCroppedImage() {
        if (!cropper) return;

        const canvas = cropper.getCroppedCanvas({ width: 400, height: 400 });
        const croppedData = canvas.toDataURL('image/png');
        document.getElementById('fotoCropped').value = croppedData;

        closeCropModal();
        document.getElementById('profileForm').submit();
    }
</script>
@endsection