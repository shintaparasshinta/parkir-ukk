<?php

namespace App\Http\Controllers; // Menentukan namespace controller

use Illuminate\Http\Request; // Mengimpor kelas Request untuk menangani input HTTP
use Illuminate\Support\Facades\Auth; // Mengimpor facade Auth untuk autentikasi
use Illuminate\Support\Facades\Hash; // Mengimpor facade Hash untuk verifikasi password
use App\Models\User; // Mengimpor model User untuk query data pengguna
use Illuminate\Support\Facades\DB; // Mengimpor facade DB untuk query langsung ke database

class AuthController extends Controller // Mendefinisikan controller autentikasi
{
    public function showLoginForm() // Method untuk menampilkan form login
    {
        return view('auth.login'); // Menampilkan view form login
    }

    public function login(Request $request) // Method untuk memproses login
    {
        $request->validate([ // Validasi input login
            'username' => 'required|string', // Username wajib dan berupa string
            'password' => 'required|string', // Password wajib dan berupa string
        ]);

        $user = User::where('username', $request->username)->first(); // Mencari pengguna berdasarkan username

        if (!$user || !Hash::check($request->password, $user->password)) { // Jika user tidak ditemukan atau password salah
            return back()->withErrors([ // Kembali ke halaman login dengan error
                'username' => 'Username atau password salah.', // Pesan error
            ])->onlyInput('username'); // Mempertahankan input username saja
        }

        Auth::login($user); // Melakukan login pengguna ke sistem

        // Catat log PAKAI DB FACADE
        DB::table('log_aktivitas')->insert([ // Menyisipkan log aktivitas login langsung via query builder
            'user_id' => $user->id, // ID pengguna yang login
            'aktivitas' => 'Login ke sistem', // Deskripsi aktivitas
            'created_at' => now(), // Timestamp pembuatan
            'updated_at' => now(), // Timestamp update
        ]);

        return redirect()->route('dashboard')->with('success', 'Selamat datang, ' . $user->nama . '!'); // Redirect ke dashboard dengan pesan sukses
    }

    public function logout(Request $request) // Method untuk memproses logout
    {
        $user = Auth::user(); // Mengambil data pengguna yang sedang login

        if ($user) { // Jika ada pengguna yang login
            // Catat log PAKAI DB FACADE
            DB::table('log_aktivitas')->insert([ // Menyisipkan log aktivitas logout
                'user_id' => $user->id, // ID pengguna yang logout
                'aktivitas' => 'Logout dari sistem', // Deskripsi aktivitas
                'created_at' => now(), // Timestamp pembuatan
                'updated_at' => now(), // Timestamp update
            ]);
        }

        Auth::logout(); // Melakukan logout pengguna
        $request->session()->invalidate(); // Menghapus seluruh data session
        $request->session()->regenerateToken(); // Membuat ulang token CSRF untuk keamanan

        return redirect('/login')->with('success', 'Anda berhasil logout.'); // Redirect ke halaman login dengan pesan sukses
    }
}