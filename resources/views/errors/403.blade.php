<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>403 - Akses Ditolak</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-red-50 to-orange-50 p-4">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl p-8 text-center">
        <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-red-100 flex items-center justify-center">
            <span class="material-symbols-outlined text-red-500 text-5xl">lock</span>
        </div>
        <h1 class="text-3xl font-bold text-slate-800 mb-2">403</h1>
        <h2 class="text-xl font-semibold text-slate-700 mb-4">Akses Ditolak</h2>
        <p class="text-sm text-slate-500 mb-6">
            Anda tidak memiliki izin untuk mengakses halaman ini. Silakan hubungi administrator.
        </p>
        <a href="{{ url()->previous() }}" 
           class="inline-flex items-center gap-2 bg-yellow-400 text-slate-800 font-bold px-6 py-2.5 rounded-lg hover:bg-yellow-300 transition-all">
            <span class="material-symbols-outlined">arrow_back</span>
            Kembali
        </a>
    </div>
</body>
</html>