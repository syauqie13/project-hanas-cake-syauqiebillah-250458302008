<?php

namespace App\Exports;

use App\Models\Inventory;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class InventoryExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    public function collection()
    {
        return Inventory::all();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Barang',
            'Tipe (bahan_baku / produk_jadi)',
            'Satuan (gram, ml, pcs, pack, box)',
            'Stok Saat Ini',
            'Harga Per Unit (Rp)',
            'Deskripsi',
        ];
    }

    public function map($inventory): array
    {
        return [
            $inventory->id,
            $inventory->name,
            $inventory->type, // enum: bahan_baku / produk_jadi
            $inventory->unit, // enum: gram, ml, pcs, pack, box
            $inventory->stock,
            $inventory->unit_price,
            $inventory->description,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]], // Header Bold
        ];
    }
}
