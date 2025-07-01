<!DOCTYPE html>
<html>
<head>
    <title>Laporan Penjualan</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        h2 { text-align: center; }
    </style>
</head>
<body>
    <h2>Laporan Penjualan - WARUNG AJUS</h2>
    <p>
        Tanggal: {{ \Carbon\Carbon::parse($start)->format('d-m-Y') }} s/d {{ \Carbon\Carbon::parse($end)->format('d-m-Y') }}
    </p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pelanggan</th>
                <th>Tanggal</th>
                <th>Menu</th>
                <th>Total</th>
                <th>Status Pembayaran</th>
                <th>Status Pesanan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $i => $order)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $order->nama_pelanggan }}</td>
                <td>{{ $order->created_at->format('d-m-Y') }}</td>
                <td>
                    @foreach($order->items as $item)
                        {{ $item->menu->nama_menu }} (x{{ $item->jumlah }})<br>
                    @endforeach
                </td>
                <td>
                    @php $total = $order->items->sum(fn($i) => $i->harga * $i->jumlah); @endphp
                    Rp {{ number_format($total, 0, ',', '.') }}
                </td>
                <td>{{ ucfirst($order->payment_status) }}</td>
                <td>{{ ucfirst($order->status) }}</td>
            </tr>
            @endforeach
        </tbody>

         <tfoot>
            <tr>
                <td colspan="4"><strong>Total Pemasukan</strong></td>
                <td colspan="3">
                    @php
                        $grandTotal = 0;
                        foreach ($orders as $order) {
                            $grandTotal += $order->items->sum(fn($i) => $i->harga * $i->jumlah);
                        }
                    @endphp
                    <strong>Rp {{ number_format($grandTotal, 0, ',', '.') }}</strong>
                </td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
