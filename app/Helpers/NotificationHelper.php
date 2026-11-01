<?php

namespace App\Helpers;

use App\Models\Notification;

class NotificationHelper
{
    public static function send($userId, $title, $message, $type = 'info', $link = null)
    {
        return Notification::create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'link' => $link,
        ]);
    }

    public static function sendToAll($title, $message, $type = 'info', $link = null)
    {
        $users = \App\Models\User::all();
        foreach ($users as $user) {
            self::send($user->id, $title, $message, $type, $link);
        }
    }

    public static function getUnreadCount($userId)
    {
        return Notification::where('user_id', $userId)->unread()->count();
    }

    public static function getRecent($userId, $limit = 10)
    {
        return Notification::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
}