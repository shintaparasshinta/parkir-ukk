<?php

namespace App\Http\Controllers; // Menentukan namespace controller

use App\Models\Notification; // Mengimpor model Notification untuk operasi data notifikasi
use Illuminate\Http\Request; // Mengimpor kelas Request (tidak dipakai langsung saat ini)

class NotificationController extends Controller // Mendefinisikan controller notifikasi
{
    public function index() // Method untuk menampilkan daftar notifikasi pengguna
    {
        $user = auth()->user(); // Mengambil pengguna yang sedang login

        $notifications = Notification::where('user_id', $user->id) // Filter notifikasi milik user ini
            ->latest() // Urutkan dari yang terbaru
            ->paginate(10); // Paginasi 10 per halaman

        // Filter link: kalau user tidak punya permission 'transaksi',
        // hilangkan tombol "Lihat" ke halaman transaksi
        $notifications->getCollection()->transform(function($notif) use ($user) { // Transformasi tiap notifikasi di collection
            if ($notif->link && str_contains($notif->link, '/transaksi/')) { // Jika link mengarah ke halaman transaksi
                if (!$user->hasFeature('transaksi')) { // Dan user tidak punya fitur 'transaksi'
                    $notif->link = null; // Hapus link agar tombol "Lihat" tidak muncul
                }
            }
            return $notif; // Kembalikan notifikasi (yang mungkin sudah dimodifikasi)
        });

        return view('notifications.index', compact('notifications')); // Kirim data ke view notifications.index
    }

    public function markAsRead($id) // Method untuk menandai satu notifikasi sebagai dibaca
    {
        $notif = Notification::findOrFail($id); // Cari notifikasi berdasarkan ID, error 404 jika tidak ada
        $notif->update(['is_read' => true]); // Set is_read menjadi true

        return redirect()->back()->with('success', 'Notifikasi ditandai dibaca.'); // Redirect kembali dengan pesan sukses
    }

    public function markAllAsRead() // Method untuk menandai semua notifikasi pengguna sebagai dibaca
    {
        Notification::where('user_id', auth()->user()->id) // Filter notifikasi milik user ini
            ->where('is_read', false) // Hanya yang belum dibaca
            ->update(['is_read' => true]); // Update semuanya jadi sudah dibaca

        return redirect()->back()->with('success', 'Semua notifikasi ditandai dibaca.'); // Redirect kembali dengan pesan sukses
    }

    public function getUnreadCount($userId) // Method untuk menghitung jumlah notifikasi belum dibaca (helper)
    {
        return Notification::where('user_id', $userId) // Filter notifikasi milik user tertentu
            ->where('is_read', false) // Hanya yang belum dibaca
            ->count(); // Kembalikan jumlahnya
    }

    public function getRecent() // Method untuk mengambil 5 notifikasi terbaru (via AJAX/JSON)
    {
        $notifications = Notification::where('user_id', auth()->user()->id) // Filter notifikasi milik user ini
            ->latest() // Urutkan dari yang terbaru
            ->take(5) // Ambil 5 saja
            ->get(); // Eksekusi query

        return response()->json($notifications); // Kembalikan sebagai response JSON
    }
}