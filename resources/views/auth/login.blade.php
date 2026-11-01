{{-- 
  HALAMAN LOGIN
  - Standalone (tanpa sidebar/navbar)
  - Form POST ke route('login') untuk autentikasi
  - Tampilkan alert sukses / error validasi
  - Password: bisa dilihat (toggle mata)
--}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Parkir SMKN 8 Jember</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

    <style>
        /* Body: center vertikal + horizontal, full height */
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #F8FAFC;
            color: #475569;
        }

        /* Warna utama aplikasi */
        .text-navy { color: #172554; }
        .bg-navy { background-color: #172554; }
        .text-yellow { color: #F4C430; }
        .bg-yellow { background-color: #F4C430; }
        .border-gray { border-color: #E2E8F0; }

        /* Card login */
        .login-card {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.09);
            max-width: 420px;
            width: 100%;
        }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <!-- Header Section: logo sekolah + judul -->
        <div class="flex flex-col items-center mb-6">
            <div class="w-24 h-24 mb-4 flex items-center justify-center">
                <img alt="School Logo" class="object-contain w-full h-full" src="{{ asset('images/smkn.png') }}">
            </div>
            <h1 class="text-2xl font-bold text-navy text-center">PARKIR SEKOLAH</h1>
            <p class="text-sm text-slate-500 text-center mt-2">Masuk ke sistem manajemen parkir</p>
        </div>

        <!-- Alert Messages -->
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-green-500">check_circle</span>
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-red-500">error</span>
                {{ $errors->first() }}
            </div>
        @endif

        <!-- Login Form -->
        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <!-- Username Input -->
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1" for="username">Username</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">person</span>
                    <input class="w-full bg-white border border-gray rounded-lg pl-10 pr-4 py-3 text-sm text-slate-800 focus:outline-none focus:border-navy focus:ring-1 focus:ring-navy transition-all"
                           id="username"
                           name="username"
                           value="{{ old('username') }}"
                           placeholder="Masukkan username"
                           type="text"
                           required
                           autofocus>
                </div>
            </div>

            <!-- Password Input + Toggle Mata -->
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1" for="password">Password</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">lock</span>
                    <input class="w-full bg-white border border-gray rounded-lg pl-10 pr-12 py-3 text-sm text-slate-800 focus:outline-none focus:border-navy focus:ring-1 focus:ring-navy transition-all"
                           id="password"
                           name="password"
                           placeholder="Masukkan password"
                           type="password"
                           required>
                    <button type="button"
                            onclick="togglePassword()"
                            title="Lihat password"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-navy transition-colors focus:outline-none">
                        <span class="material-symbols-outlined" id="eyeIcon">visibility</span>
                    </button>
                </div>
            </div>

            <!-- Action Button -->
            <button class="w-full bg-yellow text-navy font-semibold text-sm py-3 rounded-lg hover:bg-yellow/90 transition-colors mt-4 flex justify-center items-center gap-2"
                    type="submit">
                Masuk
                <span class="material-symbols-outlined text-sm">arrow_forward</span>
            </button>
        </form>
    </div>

    <!-- JavaScript: Toggle Show/Hide Password -->
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.textContent = 'visibility_off';
            } else {
                passwordInput.type = 'password';
                eyeIcon.textContent = 'visibility';
            }
        }
    </script>
</body>
</html>