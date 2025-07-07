<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Struk Pembayaran</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 14px;
            max-width: 300px;
            margin: auto;
        }
        h2, h4, p {
            text-align: center;
            margin: 0;
            padding: 5px;
        }
        table {
            width: 100%;
            margin-top: 10px;
            border-collapse: collapse;
        }
        th, td {
            text-align: left;
            padding: 4px;
        }
        .total {
            border-top: 1px dashed #000;
            margin-top: 10px;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <h2>WARUNG AJUS</h2>
    <h4>Struk Pembayaran</h4>
    <p>No Pesanan: {{ $order->order_id }}</p>
    <p>Tanggal: {{ $order->created_at->format('d-m-Y H:i') }}</p>
    <p>Nama Pelanggan: {{ $order->nama_pelanggan ?? '-' }}</p>
    <hr>

    <table>
        <thead>
            <tr>
                <th>Menu</th>
                <th>Qty</th>
                <th>Harga</th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp
            @foreach($order->items as $item)
                @php
                    $addonTotal = collect(json_decode($item->addons))->sum('price') ?? 0;
                    $subtotal = ($item->harga + $addonTotal) * $item->jumlah;
                    $total += $subtotal;
                @endphp
                <tr>
                    <td>{{ $item->menu->nama_menu }}</td>
                    <td>{{ $item->jumlah }}</td>
                    <td>Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        <p><strong>Total Pesanan: Rp {{ number_format($total, 0, ',', '.') }}</strong></p>
        @php
            $statusPembayaran = [
                'pending' => 'Belum Dibayar',
                'paid' => 'Sudah Dibayar',
                'failed' => 'Gagal',
                'expired' => 'Kedaluwarsa',
            ];

            $statusPesanan = [
                'pending' => 'Menunggu',
                'processing' => 'Sedang Diproses',
                'completed' => 'Selesai',
                'cancelled' => 'Dibatalkan',
            ];
        @endphp

        <p>Status Pembayaran: {{ $statusPembayaran[$order->payment_status] ?? ucfirst($order->payment_status) }}</p>
        <p>Status Pesanan: {{ $statusPesanan[$order->status] ?? ucfirst($order->status) }}</p>
    </div>

    <p>Terima kasih!</p>

    <p style="text-align: center; margin-top: 10px;">
    <button onclick="window.print()">Cetak</button>
</p>
</body>
</html>
