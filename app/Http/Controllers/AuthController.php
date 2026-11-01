<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'username' => 'Username atau password salah.',
            ])->onlyInput('username');
        }

        Auth::login($user);

        // Catat log PAKAI DB FACADE
        DB::table('log_aktivitas')->insert([
            'user_id' => $user->id,
            'aktivitas' => 'Login ke sistem',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('dashboard')->with('success', 'Selamat datang, ' . $user->nama . '!');
    }

    public function logout(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            // Catat log PAKAI DB FACADE
            DB::table('log_aktivitas')->insert([
                'user_id' => $user->id,
                'aktivitas' => 'Logout dari sistem',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Anda berhasil logout.');
    }
}