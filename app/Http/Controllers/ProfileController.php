<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('profile.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:users,username,' . $user->id,
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user->nama = $request->nama;
        $user->username = $request->username;

        // Kalau ada hasil crop (base64)
        if ($request->filled('foto_cropped')) {
            // Hapus foto lama
            if ($user->foto && file_exists(storage_path('app/public/' . $user->foto))) {
                unlink(storage_path('app/public/' . $user->foto));
            }

            // Decode base64
            $imageData = $request->foto_cropped;
            $imageData = str_replace('data:image/png;base64,', '', $imageData);
            $imageData = str_replace(' ', '+', $imageData);
            $imageBinary = base64_decode($imageData);

            // Generate nama file unik
            $filename = 'foto-profil/' . Str::uuid() . '.png';

            // Simpan ke storage
            \Storage::disk('public')->put($filename, $imageBinary);

            $user->foto = $filename;

            DB::table('log_aktivitas')->insert([
                'user_id' => $user->id,
                'aktivitas' => 'Mengubah foto profil',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $user->save();

        DB::table('log_aktivitas')->insert([
            'user_id' => $user->id,
            'aktivitas' => 'Mengupdate profil: ' . $request->nama,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('profile.index')
            ->with('success', 'Profil berhasil diperbarui!');
    }

    public function deletePhoto()
    {
        $user = Auth::user();

        if ($user->foto && file_exists(storage_path('app/public/' . $user->foto))) {
            unlink(storage_path('app/public/' . $user->foto));
        }

        $user->foto = null;
        $user->save();

        DB::table('log_aktivitas')->insert([
            'user_id' => $user->id,
            'aktivitas' => 'Menghapus foto profil',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('profile.index')
            ->with('success', 'Foto profil berhasil dihapus!');
    }
}