<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Order #{{ $order->merchant_order_id ?? $order->id }}</title>
    <style>
        /* CSS ini didesain untuk printer thermal 58mm atau 80mm */
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 10px; /* Ukuran font kecil untuk struk */
            color: #000;
            margin: 0;
            padding: 0;
            width: 280px; /* Lebar umum untuk printer 80mm (sesuaikan jika 58mm) */
        }
        .container {
            width: 100%;
            padding: 10px;
        }
        .header {
            text-align: center;
            margin-bottom: 10px;
        }
        .header h1 {
            font-size: 16px;
            margin: 0;
            font-weight: 600;
        }
        .header p {
            font-size: 10px;
            margin: 2px 0;
        }
        .info {
            margin-bottom: 10px;
            border-top: 1px dashed #000;
            padding-top: 10px;
        }
        .info table {
            width: 100%;
            font-size: 10px;
        }
        .info table td {
            padding: 1px 0;
        }
        .items {
            border-top: 1px dashed #000;
            padding-top: 5px;
        }
        .items table {
            width: 100%;
            font-size: 10px;
        }
        .items table th {
            text-align: left;
            border-bottom: 1px solid #000;
            padding-bottom: 3px;
        }
        .items table td {
            padding: 3px 0;
        }
        .items .item-name {
            font-weight: 600;
        }
        .items .item-details {
            text-align: right;
        }
        .total {
            border-top: 1px dashed #000;
            padding-top: 10px;
            margin-top: 10px;
        }
        .total table {
            width: 100%;
            font-size: 10px;
        }
        .total table td {
            padding: 1px 0;
        }
        .total table .label {
            text-align: right;
            padding-right: 10px;
        }
        .total table .value {
            text-align: right;
            font-weight: 600;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            border-top: 1px dashed #000;
            padding-top: 10px;
        }

        /* CSS KHUSUS UNTUK PRINT */
        @media print {
            body, html {
                width: 100%; /* Lebar akan disesuaikan oleh printer driver */
                margin: 0;
                padding: 0;
            }
            .container {
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Hana Cake</h1>
            <p>Jalan Kenangan No. 123, Kota Harapan</p>
            <p>Telp: (021) 555-1234</p>
        </div>

        <div class="info">
            <table>
                <tr>
                    <td>No. Struk:</td>
                    <td>{{ $order->merchant_order_id ?? $order->id }}</td>
                </tr>
                <tr>
                    <td>Tanggal:</td>
                    <td>{{ $order->tanggal->format('d/m/Y H:i') }}</td>
                </tr>
                <tr>
                    <td>Kasir:</td>
                    <td>{{ $order->cashier->name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td>Pelanggan:</td>
                    <td>{{ $order->customer->name ?? 'Guest' }}</td>
                </tr>
            </table>
        </div>

        <div class="items">
            <table>
                <thead>
                    <tr>
                        <th>Item</th>
                        <th class="item-details">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr>
                            <td>
                                <span class="item-name">{{ $item->product->name ?? 'Produk Dihapus' }}</span>
                                <br>
                                {{ $item->jumlah }} x Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}
                            </td>
                            <td class="item-details">
                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="total">
            <table>
                <tr>
                    <td class="label">Total</td>
                    <td class="value">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="label">Bayar ({{ $order->payment_method }})</td>
                    <td class="value">Rp {{ number_format($order->paid_amount, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="label">Kembalian</td>
                    <td class="value">Rp {{ number_format($order->change_amount, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>

        <div class="footer">
            <p>Terima kasih atas kunjungan Anda!</p>
            <p>Layanan Kritik & Saran: 0812-XXXX-XXXX</p>
        </div>
    </div>

    <script>
        // Skrip ini akan otomatis memanggil dialog print saat halaman struk dimuat
        // INI ADALAH PERBAIKAN DARI MASALAH LOOPING/REFRESH
        window.onload = function() {
            try {
                // Panggil dialog print
                window.print();
            } catch (e) {
                console.error('Print failed:', e);
            }
            // (Opsional) Anda bisa menambahkan logika untuk menutup tab,
            // tapi sering diblokir browser.
            // setTimeout(function () { window.close(); }, 500);
        }
    </script>
</body>
</html>
