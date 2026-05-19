<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

/**
 * NotificationController
 *
 * Mengelola notifikasi untuk pelanggan via aplikasi mobile.
 * Notifikasi yang ditampilkan hanya terkait perubahan status order.
 *
 * Menggunakan fitur bawaan Laravel Notification (database channel)
 * yang menyimpan notifikasi di tabel 'notifications'.
 */
class NotificationController extends Controller
{
    use ApiResponseTrait;

    /**
     * GET /api/notifications
     *
     * Menampilkan daftar notifikasi milik user yang login.
     * Mendukung parameter ?unread_only=true untuk filter notifikasi belum dibaca.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        // Jika client mengirim ?unread_only=true, hanya tampilkan yang belum dibaca
        $query = $user->notifications();

        if ($request->boolean('unread_only')) {
            $query = $user->unreadNotifications();
        }

        $notifications = $query->orderByDesc('created_at')
            ->paginate(20)
            ->through(function ($notification) {
                return [
                    'id'         => $notification->id,
                    'type'       => $notification->data['type'] ?? 'order_status',
                    'title'      => $notification->data['title'] ?? 'Notifikasi',
                    'message'    => $notification->data['message'] ?? '',
                    'order_id'   => $notification->data['order_id'] ?? null,
                    'read_at'    => $notification->read_at,
                    'created_at' => $notification->created_at,
                ];
            });

        return $this->successResponse($notifications, 'Daftar notifikasi berhasil diambil');
    }

    /**
     * POST /api/notifications/{id}/read
     *
     * Menandai satu notifikasi sebagai sudah dibaca.
     * Hanya bisa menandai notifikasi milik sendiri.
     */
    public function markAsRead(Request $request, string $id): JsonResponse
    {
        $notification = $request->user()
            ->notifications()
            ->where('id', $id)
            ->first();

        if (!$notification) {
            return $this->notFoundResponse('Notifikasi tidak ditemukan');
        }

        $notification->markAsRead();

        return $this->successResponse(null, 'Notifikasi ditandai sebagai dibaca');
    }
}
