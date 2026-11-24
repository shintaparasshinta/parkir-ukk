<?php

namespace App\Helpers; // Menentukan namespace helper

use App\Models\Notification; // Mengimpor model Notification untuk operasi data notifikasi

class NotificationHelper // Mendefinisikan helper statis untuk notifikasi
{
    public static function send($userId, $title, $message, $type = 'info', $link = null) // Method untuk kirim notifikasi ke satu user
    {
        return Notification::create([ // Buat record notifikasi baru
            'user_id' => $userId, // Penerima notifikasi
            'title' => $title, // Judul notifikasi
            'message' => $message, // Isi pesan
            'type' => $type, // Tipe notifikasi (default: info)
            'link' => $link, // Link tujuan (default: null)
        ]);
    }

    public static function sendToAll($title, $message, $type = 'info', $link = null) // Method untuk broadcast notifikasi ke semua user
    {
        $users = \App\Models\User::all(); // Ambil semua user
        foreach ($users as $user) { // Iterasi tiap user
            self::send($user->id, $title, $message, $type, $link); // Kirim notifikasi ke user tersebut
        }
    }

    public static function getUnreadCount($userId) // Method untuk hitung jumlah notifikasi belum dibaca
    {
        return Notification::where('user_id', $userId)->unread()->count(); // Filter user + scope unread, lalu hitung
    }

    public static function getRecent($userId, $limit = 10) // Method untuk ambil notifikasi terbaru
    {
        return Notification::where('user_id', $userId) // Filter berdasarkan user
            ->orderBy('created_at', 'desc') // Urutkan terbaru
            ->limit($limit) // Batasi jumlah (default 10)
            ->get(); // Eksekusi query
    }
}