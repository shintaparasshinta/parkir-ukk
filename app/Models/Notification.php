<?php

namespace App\Models; // Menentukan namespace model

use Illuminate\Database\Eloquent\Factories\HasFactory; // Mengimpor trait HasFactory
use Illuminate\Database\Eloquent\Model; // Mengimpor kelas dasar Model Eloquent

class Notification extends Model // Mendefinisikan model Notification
{
    use HasFactory; // Mengaktifkan trait HasFactory

    protected $fillable = [ // Mendaftar kolom yang boleh diisi massal
        'user_id', // ID penerima notifikasi
        'title', // Judul notifikasi
        'message', // Isi pesan notifikasi
        'type', // Tipe notifikasi (success/info/warning)
        'link', // URL tujuan saat diklik
        'is_read', // Status sudah dibaca
    ];

    public function user() // Relasi ke model User
    {
        return $this->belongsTo(User::class); // Notifikasi dimiliki oleh satu User (default FK user_id)
    }

    public function markAsRead() // Helper untuk menandai notifikasi ini sebagai sudah dibaca
    {
        $this->update(['is_read' => true]); // Update kolom is_read menjadi true
    }

    public function scopeUnread($query) // Local scope untuk filter notifikasi belum dibaca
    {
        return $query->where('is_read', false); // Tambahkan kondisi is_read = false
    }

    /**
     * Helper untuk mengirim notifikasi HANYA ke Owner
     */
    public static function kirimKeOwner($title, $message, $type = 'info', $link = null) // Static helper broadcast notifikasi ke semua owner
    {
        $owners = \App\Models\User::where('role', 'owner')->get(); // Ambil semua user ber-role owner
        
        foreach ($owners as $owner) { // Iterasi tiap owner
            self::create([ // Buat record notifikasi untuk owner tersebut
                'user_id' => $owner->id, // ID owner penerima
                'title'   => $title, // Judul notifikasi
                'message' => $message, // Isi pesan
                'type'    => $type, // Tipe notifikasi (default: info)
                'link'    => $link, // Link tujuan (default: null)
                'is_read' => false, // Status awal: belum dibaca
            ]);
        }
    }
}