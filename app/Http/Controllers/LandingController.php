<?php

namespace App\Http\Controllers; // Menentukan namespace controller

use Illuminate\Http\Request; // Mengimpor kelas Request (tidak dipakai saat ini)

class LandingController extends Controller // Mendefinisikan controller untuk halaman landing
{
    public function index() // Method untuk menampilkan halaman landing publik
    {
        return view('landing.index'); // Menampilkan view landing.index
    }
}