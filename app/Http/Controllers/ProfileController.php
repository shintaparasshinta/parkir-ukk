<?php

namespace App\Http\Controllers; // Menentukan namespace controller

use Illuminate\Http\Request; // Mengimpor kelas Request untuk menangani input HTTP
use Illuminate\Support\Facades\Auth; // Mengimpor facade Auth untuk data pengguna login
use Illuminate\Support\Facades\Validator; // Mengimpor facade Validator untuk validasi manual
use Illuminate\Support\Facades\DB; // Mengimpor facade DB untuk insert log langsung
use Illuminate\Support\Str; // Mengimpor Str untuk generate UUID nama file

class ProfileController extends Controller // Mendefinisikan controller profil pengguna
{
    public function index() // Method untuk menampilkan halaman profil
    {
        $user = Auth::user(); // Mengambil data pengguna yang sedang login
        return view('profile.index', compact('user')); // Kirim data user ke view profile.index
    }

    public function update(Request $request) // Method untuk memperbarui profil pengguna
    {
        $user = Auth::user(); // Mengambil data pengguna yang sedang login

        $validator = Validator::make($request->all(), [ // Membuat validator untuk input profil
            'nama' => 'required|string|max:100', // Nama wajib, string, max 100
            'username' => 'required|string|max:50|unique:users,username,' . $user->id, // Username unik kecuali milik user ini
        ]);

        if ($validator->fails()) { // Jika validasi gagal
            return redirect()->back() // Kembali ke halaman sebelumnya
                ->withErrors($validator) // Bawa pesan error
                ->withInput(); // Bawa input sebelumnya
        }

        $user->nama = $request->nama; // Set nama baru ke model user
        $user->username = $request->username; // Set username baru ke model user

        // Kalau ada hasil crop (base64)
        if ($request->filled('foto_cropped')) { // Jika user mengirim foto hasil crop (base64)
            // Hapus foto lama
            if ($user->foto && file_exists(storage_path('app/public/' . $user->foto))) { // Jika foto lama ada di storage
                unlink(storage_path('app/public/' . $user->foto)); // Hapus file foto lama
            }

            // Decode base64
            $imageData = $request->foto_cropped; // Ambil string base64 dari request
            $imageData = str_replace('data:image/png;base64,', '', $imageData); // Hilangkan prefix data URI PNG
            $imageData = str_replace(' ', '+', $imageData); // Ganti spasi dengan '+' (fix untuk base64 yang di-encode URL)
            $imageBinary = base64_decode($imageData); // Decode base64 menjadi binary image

            // Generate nama file unik
            $filename = 'foto-profil/' . Str::uuid() . '.png'; // Nama file unik dengan UUID di folder foto-profil

            // Simpan ke storage
            \Storage::disk('public')->put($filename, $imageBinary); // Simpan file ke disk public

            $user->foto = $filename; // Simpan path file ke kolom foto user

            DB::table('log_aktivitas')->insert([ // Catat log perubahan foto profil
                'user_id' => $user->id, // ID user
                'aktivitas' => 'Mengubah foto profil', // Deskripsi aktivitas
                'created_at' => now(), // Timestamp pembuatan
                'updated_at' => now(), // Timestamp update
            ]);
        }

        $user->save(); // Simpan perubahan user ke database

        DB::table('log_aktivitas')->insert([ // Catat log update profil
            'user_id' => $user->id, // ID user
            'aktivitas' => 'Mengupdate profil: ' . $request->nama, // Deskripsi aktivitas
            'created_at' => now(), // Timestamp pembuatan
            'updated_at' => now(), // Timestamp update
        ]);

        return redirect()->route('profile.index') // Redirect ke halaman profil
            ->with('success', 'Profil berhasil diperbarui!'); // Pesan sukses
    }

    public function deletePhoto() // Method untuk menghapus foto profil
    {
        $user = Auth::user(); // Mengambil data pengguna yang sedang login

        if ($user->foto && file_exists(storage_path('app/public/' . $user->foto))) { // Jika foto ada di storage
            unlink(storage_path('app/public/' . $user->foto)); // Hapus file foto dari storage
        }

        $user->foto = null; // Set kolom foto menjadi null
        $user->save(); // Simpan perubahan ke database

        DB::table('log_aktivitas')->insert([ // Catat log penghapusan foto profil
            'user_id' => $user->id, // ID user
            'aktivitas' => 'Menghapus foto profil', // Deskripsi aktivitas
            'created_at' => now(), // Timestamp pembuatan
            'updated_at' => now(), // Timestamp update
        ]);

        return redirect()->route('profile.index') // Redirect ke halaman profil
            ->with('success', 'Foto profil berhasil dihapus!'); // Pesan sukses
    }
}