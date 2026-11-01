<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $notifications = Notification::where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        // Filter link: kalau user tidak punya permission 'transaksi',
        // hilangkan tombol "Lihat" ke halaman transaksi
        $notifications->getCollection()->transform(function($notif) use ($user) {
            if ($notif->link && str_contains($notif->link, '/transaksi/')) {
                if (!$user->hasFeature('transaksi')) {
                    $notif->link = null;
                }
            }
            return $notif;
        });

        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notif = Notification::findOrFail($id);
        $notif->update(['is_read' => true]);

        return redirect()->back()->with('success', 'Notifikasi ditandai dibaca.');
    }

    public function markAllAsRead()
    {
        Notification::where('user_id', auth()->user()->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return redirect()->back()->with('success', 'Semua notifikasi ditandai dibaca.');
    }

    public function getUnreadCount($userId)
    {
        return Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->count();
    }

    public function getRecent()
    {
        $notifications = Notification::where('user_id', auth()->user()->id)
            ->latest()
            ->take(5)
            ->get();

        return response()->json($notifications);
    }
}