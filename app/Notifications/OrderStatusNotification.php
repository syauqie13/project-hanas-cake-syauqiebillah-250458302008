<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/**
 * Notifikasi perubahan status order.
 *
 * Dikirim ke pelanggan saat status order berubah, misalnya:
 * - pending → diproses (setelah pembayaran berhasil)
 * - diproses → dikirim
 * - dikirim → selesai
 * - pending → dibatalkan (pembayaran gagal/expired)
 *
 * Menggunakan channel 'database' agar bisa diambil via API.
 */
class OrderStatusNotification extends Notification
{
    use Queueable;

    protected Order $order;
    protected string $newStatus;

    public function __construct(Order $order, string $newStatus)
    {
        $this->order     = $order;
        $this->newStatus = $newStatus;
    }

    /**
     * Channel: simpan ke database agar Flutter bisa ambil via GET /api/notifications
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Data yang disimpan di tabel notifications (kolom 'data' sebagai JSON).
     */
    public function toArray(object $notifiable): array
    {
        // Mapping status ke pesan yang user-friendly
        $statusMessages = [
            'pending'    => 'Menunggu pembayaran',
            'paid'       => 'Pembayaran berhasil',
            'diproses'   => 'Pesanan sedang diproses',
            'dikirim'    => 'Pesanan sedang dalam pengiriman',
            'selesai'    => 'Pesanan telah selesai',
            'dibatalkan' => 'Pesanan dibatalkan',
        ];

        $message = $statusMessages[$this->newStatus] ?? "Status diubah ke: {$this->newStatus}";

        return [
            'type'       => 'order_status',
            'title'      => 'Update Pesanan #' . $this->order->merchant_order_id,
            'message'    => $message,
            'order_id'   => $this->order->id,
            'status'     => $this->newStatus,
        ];
    }
}
