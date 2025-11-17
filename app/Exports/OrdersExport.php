<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class OrdersExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $search;
    protected $filterStatus;

    /**
     * Terima filter (search & status) dari Livewire Component
     */
    public function __construct($search, $filterStatus)
    {
        $this->search = $search;
        $this->filterStatus = $filterStatus;
    }

    /**
     * Tentukan nama kolom (header) di file Excel
     */
    public function headings(): array
    {
        return [
            'Order ID',
            'Tanggal',
            'Nama Pelanggan',
            'Email Pelanggan',
            'No. Telepon',
            'Alamat',
            'Kota',
            'Kode Pos',
            'Metode Pengiriman',
            'Ongkos Kirim (Rp)',
            'Status Pembayaran',
            'Status Pesanan',
            'Total (Rp)',
        ];
    }

    /**
     * Ambil data dari database
     * Query ini HARUS SAMA PERSIS dengan query di OrderManagement.php
     */
    public function query()
    {
        $query = Order::query()
            ->where('order_type', 'online')
            ->with('user'); // Load relasi user

        // Terapkan filter Search (sama seperti di OrderManagement)
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('merchant_order_id', 'like', '%' . $this->search . '%')
                    ->orWhere('shipping_name', 'like', '%' . $this->search . '%')
                    ->orWhereHas('user', function ($userQuery) {
                        $userQuery->where('name', 'like', '%' . $this->search . '%');
                    });
            });
        }

        // Terapkan filter Status (sama seperti di OrderManagement)
        $query->when($this->filterStatus, function ($q) {
            if ($this->filterStatus == 'pending') {
                $q->where('payment_status', 'pending')
                    ->where('created_at', '>', now()->subHour());
            } else {
                $q->where('status', $this->filterStatus);
            }
        }, function ($q) {
            $q->where(function ($subQ) {
                $subQ->where('payment_status', '!=', 'pending')
                    ->orWhere(function ($pendingQ) {
                        $pendingQ->where('payment_status', 'pending')
                            ->where('created_at', '>', now()->subHour());
                    });
            });
        });

        return $query->latest('tanggal');
    }

    /**
     * Petakan/Format setiap baris data
     */
    public function map($order): array
    {
        return [
            $order->merchant_order_id,
            $order->tanggal->format('Y-m-d H:i:s'),
            $order->user?->name ?? 'Guest',
            $order->shipping_email ?? $order->user?->email ?? 'noemail@example.com',
            $order->shipping_phone ?? $order->user?->phone ?? '-',
            $order->shipping_address ?? '-',
            $order->shipping_city ?? '-',
            $order->shipping_postal_code ?? '-',
            $order->shipping_zone_name ?? '-',
            $order->shipping_price ?? 0,
            $order->payment_status ?? '-',
            $order->status ?? '-',
            $order->total ?? 0,
        ];
    }

}
