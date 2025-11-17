<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    public function collection()
    {
        return Product::with('category')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Kue',
            'Kategori',
            'Harga Normal (Rp)',
            'Diskon (%)',     // <--- TAMBAHAN BARU
            'Harga Akhir',    // (Opsional: Kalkulasi otomatis biar enak dilihat)
            'Sisa Stok',
        ];
    }

    public function map($product): array
    {
        // Hitung harga setelah diskon (Hanya visual di Excel, tidak masuk DB)
        $hargaAkhir = $product->price - ($product->price * ($product->discount / 100));

        return [
            $product->id,
            $product->name,
            $product->category?->name ?? '-',
            $product->price,

            $product->discount ?? 0,  // <--- TAMBAHAN BARU (Default 0)

            $hargaAkhir, // Visual saja
            $product->stock,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
