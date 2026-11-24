{{--
  HALAMAN LOGIN
  - Standalone (tanpa sidebar/navbar)
  - Form POST ke route('login') untuk autentikasi
  - Tampilkan alert sukses / error validasi
  - Password: bisa dilihat (toggle mata)
--}}
<!DOCTYPE html> {{-- Standalone HTML (bukan pakai layout) --}}
<html lang="id"> {{-- Bahasa Indonesia --}}
<head>
    <meta charset="UTF-8"> {{-- Encoding UTF-8 --}}
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> {{-- Responsif --}}
    <meta name="csrf-token" content="{{ csrf_token() }}"> {{-- Meta CSRF untuk request AJAX --}}
    <title>Login - Parkir SMKN 8 Jember</title> {{-- Judul halaman --}}

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script> {{-- Tailwind via CDN (untuk halaman login standalone) --}}

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet"> {{-- Font utama --}}
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"> {{-- Font ikon --}}

    <style>
        /* Body: center vertikal + horizontal, full height */
        body {
            font-family: 'Plus Jakarta Sans', sans-serif; /* Font utama */
            display: flex; /* Flexbox untuk centering */
            justify-content: center; /* Center horizontal */
            align-items: center; /* Center vertikal */
            min-height: 100vh; /* Tinggi minimum viewport */
            background-color: #F8FAFC; /* Latar abu muda */
            color: #475569; /* Warna teks dasar */
        }

        /* Warna utama aplikasi */
        .text-navy { color: #172554; } /* Biru navy */
        .bg-navy { background-color: #172554; } /* Latar navy */
        .text-yellow { color: #F4C430; } /* Kuning */
        .bg-yellow { background-color: #F4C430; } /* Latar kuning */
        .border-gray { border-color: #E2E8F0; } /* Border abu */

        /* Card login */
        .login-card {
            background: white; /* Latar putih */
            padding: 40px; /* Padding dalam */
            border-radius: 12px; /* Sudut melengkung */
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.09); /* Bayangan lembut */
            max-width: 420px; /* Lebar maksimum */
            width: 100%; /* Lebar penuh (untuk mobile) */
        }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; /* Default ikon outlined */
        }
    </style>
</head>
<body>
    <div class="login-card"> {{-- Card login --}}
        <!-- Header Section: logo sekolah + judul -->
        <div class="flex flex-col items-center mb-6"> {{-- Header dengan layout kolom --}}
            <div class="w-24 h-24 mb-4 flex items-center justify-center"> {{-- Container logo --}}
                <img alt="School Logo" class="object-contain w-full h-full" src="{{ asset('images/smkn.png') }}"> {{-- Logo sekolah --}}
            </div>
            <h1 class="text-2xl font-bold text-navy text-center">PARKIR SEKOLAH</h1> {{-- Judul besar --}}
            <p class="text-sm text-slate-500 text-center mt-2">Masuk ke sistem manajemen parkir</p> {{-- Subjudul --}}
        </div>

        <!-- Alert Messages -->
        @if(session('success')) {{-- Jika ada session sukses --}}
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4 flex items-center gap-2"> {{-- Alert hijau --}}
                <span class="material-symbols-outlined text-green-500">check_circle</span> {{-- Ikon centang --}}
                {{ session('success') }} {{-- Pesan sukses --}}
            </div>
        @endif

        @if($errors->any()) {{-- Jika ada error validasi --}}
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4 flex items-center gap-2"> {{-- Alert merah --}}
                <span class="material-symbols-outlined text-red-500">error</span> {{-- Ikon error --}}
                {{ $errors->first() }} {{-- Tampilkan pesan error pertama --}}
            </div>
        @endif

        <!-- Login Form -->
        <form method="POST" action="{{ route('login') }}" class="space-y-4"> {{-- Form login --}}
            @csrf {{-- Token CSRF --}}

            <!-- Username Input -->
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1" for="username">Username</label> {{-- Label --}}
                <div class="relative"> {{-- Container relatif untuk ikon --}}
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">person</span> {{-- Ikon user --}}
                    <input class="w-full bg-white border border-gray rounded-lg pl-10 pr-4 py-3 text-sm text-slate-800 focus:outline-none focus:border-navy focus:ring-1 focus:ring-navy transition-all"
                           id="username"
                           name="username"
                           value="{{ old('username') }}" {{-- Restore nilai username jika gagal validasi --}}
                           placeholder="Masukkan username"
                           type="text"
                           required
                           autofocus> {{-- Fokus otomatis saat halaman load --}}
                </div>
            </div>

            <!-- Password Input + Toggle Mata -->
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1" for="password">Password</label> {{-- Label --}}
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">lock</span> {{-- Ikon gembok --}}
                    <input class="w-full bg-white border border-gray rounded-lg pl-10 pr-12 py-3 text-sm text-slate-800 focus:outline-none focus:border-navy focus:ring-1 focus:ring-navy transition-all"
                           id="password"
                           name="password"
                           placeholder="Masukkan password"
                           type="password" {{-- Default tersembunyi --}}
                           required>
                    <button type="button" {{-- Type button agar tidak submit form --}}
                            onclick="togglePassword()" {{-- Panggil JS toggle --}}
                            title="Lihat password"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-navy transition-colors focus:outline-none">
                        <span class="material-symbols-outlined" id="eyeIcon">visibility</span> {{-- Ikon mata --}}
                    </button>
                </div>
            </div>

            <!-- Action Button -->
            <button class="w-full bg-yellow text-navy font-semibold text-sm py-3 rounded-lg hover:bg-yellow/90 transition-colors mt-4 flex justify-center items-center gap-2"
                    type="submit"> {{-- Tombol submit --}}
                Masuk {{-- Label --}}
                <span class="material-symbols-outlined text-sm">arrow_forward</span> {{-- Ikon panah --}}
            </button>
        </form>
    </div>

    <!-- JavaScript: Toggle Show/Hide Password -->
    <script>
        function togglePassword() { // Fungsi untuk toggle tampil/sembunyi password
            const passwordInput = document.getElementById('password'); // Ambil elemen input password
            const eyeIcon = document.getElementById('eyeIcon'); // Ambil elemen ikon mata

            if (passwordInput.type === 'password') { // Jika saat ini tersembunyi
                passwordInput.type = 'text'; // Ubah ke teks (terlihat)
                eyeIcon.textContent = 'visibility_off'; // Ganti ikon ke mata dicoret
            } else { // Jika saat ini terlihat
                passwordInput.type = 'password'; // Ubah ke password (tersembunyi)
                eyeIcon.textContent = 'visibility'; // Ganti ikon ke mata biasa
            }
        }
    </script>
</body>
</html>