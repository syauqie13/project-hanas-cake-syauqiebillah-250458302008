<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon; // <-- Import Carbon

class PosExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $search;
    protected $filterStatus;
    protected $filterTanggal; // <-- Tambahkan filter tanggal

    /**
     * Terima semua filter dari PosManagement.php
     */
    public function __construct($search, $filterStatus, $filterTanggal)
    {
        $this->search = $search;
        $this->filterStatus = $filterStatus;
        $this->filterTanggal = $filterTanggal;
    }

    /**
     * Tentukan nama kolom (header) untuk POS
     */
    public function headings(): array
    {
        return [
            'Order ID',
            'Tanggal',
            'Nama Pelanggan',
            'Kasir',
            'Metode Pembayaran',
            'Status Pembayaran',
            'Status Pesanan',
            'Total (Rp)',
            'Dibayar (Rp)',
            'Kembalian (Rp)',
        ];
    }

    /**
     * Ambil data dari database
     * Query ini SAMA PERSIS dengan query di PosManagement.php
     */
    public function query()
    {
        $query = Order::query()
            ->where('order_type', 'pos') // <-- Filter utama: HANYA POS
            ->with(['customer', 'cashier']); // Eager load

        // 1. Terapkan Filter Tanggal
        switch ($this->filterTanggal) {
            case 'today':
                $query->whereDate('tanggal', Carbon::today());
                break;
            case 'week':
                $query->whereBetween('tanggal', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereMonth('tanggal', Carbon::now()->month)->whereYear('tanggal', Carbon::now()->year);
                break;
        }

        // 2. Terapkan Filter Status
        if ($this->filterStatus !== 'all') {
            $query->where('status', $this->filterStatus);
        }

        // 3. Terapkan Filter Pencarian
        if (strlen($this->search) > 2) {
            $query->where(function ($q) {
                $q->where('id', 'like', '%' . $this->search . '%')
                    ->orWhere('merchant_order_id', 'like', '%' . $this->search . '%')
                    ->orWhereHas('customer', function ($subQ) {
                        $subQ->where('name', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('cashier', function ($subQ) {
                        $subQ->where('name', 'like', '%' . $this->search . '%');
                    });
            });
        }

        return $query->latest('tanggal');
    }

    /**
     * Petakan/Format setiap baris data
     */
    public function map($order): array
    {
        return [
            $order->id,
            $order->tanggal->format('Y-m-d H:i:s'),
            $order->customer?->name ?? 'Guest',
            $order->cashier?->name ?? 'N/A',
            $order->payment_method,
            $order->payment_status,
            $order->status,
            $order->total,
            $order->paid_amount,
            $order->change_amount,
        ];
    }
}
