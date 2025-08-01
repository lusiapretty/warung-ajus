<!DOCTYPE html>
<html>
<head>
    <title>Laporan Penjualan</title>
    <style>
        body { 
            font-family: DejaVu Sans, sans-serif; 
            font-size: 12px; 
            margin: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 15px;
        }
        
        .period-info {
            background-color: #f5f5f5;
            padding: 10px;
            margin-bottom: 20px;
            border-left: 4px solid #007bff;
        }
        
        .summary-cards {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            gap: 15px;
        }
        
        .summary-card {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 15px;
            text-align: center;
            flex: 1;
            border-radius: 5px;
        }
        
        .summary-card h4 {
            margin: 0 0 10px 0;
            color: #495057;
        }
        
        .summary-card .value {
            font-size: 18px;
            font-weight: bold;
            color: #007bff;
        }
        
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px; 
        }
        
        th, td { 
            border: 1px solid #000; 
            padding: 8px; 
            text-align: left; 
        }
        
        th {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }
        
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        
        /* .status-paid { color: #28a745; font-weight: bold; }
        .status-pending { color: #ffc107; font-weight: bold; }
        .status-failed { color: #dc3545; font-weight: bold; } */
        
        .menu-analysis, .daily-summary {
            margin-top: 30px;
        }
        
        .section-title {
            background-color: #343a40;
            color: white;
            padding: 10px;
            margin: 20px 0 10px 0;
            font-weight: bold;
        }
        
        tfoot tr {
            background-color: #e9ecef;
            font-weight: bold;
        }
        
        .footer-info {
            margin-top: 30px;
            font-size: 10px;
            text-align: center;
            color: #6c757d;
        }
        
        @media print {
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>LAPORAN PENJUALAN</h1>
        <h2>WARUNG AJUS</h2>
        <p>Jln. Goa Gong No. 3, Bukit Jimbaran, Kampus UNUD | Telp: 0821-4408-3032</p>
    </div>

    <!-- Period Info -->
    <div class="period-info">
        <strong>Periode Laporan:</strong> 
        {{ \Carbon\Carbon::parse($start)->format('d F Y') }} s/d {{ \Carbon\Carbon::parse($end)->format('d F Y') }}
        <br>
        <strong>Tanggal Cetak:</strong> {{ \Carbon\Carbon::now()->format('d F Y, H:i') }} WIB
    </div>

    <!-- Summary Cards -->
    <div class="summary-cards">
        <div class="summary-card">
            <h4>Total Pesanan</h4>
            <div class="value">{{ count($orders) }}</div>
        </div>
        <div class="summary-card">
            <h4>Pesanan Berhasil</h4>
            <div class="value">{{ collect($orders)->where('payment_status', 'paid')->count() }}</div>
        </div>
        <div class="summary-card">
            <h4>Total Pendapatan</h4>
            <div class="value">
                @php
                    $totalPendapatan = 0;
                    foreach ($orders as $order) {
                        if ($order->payment_status === 'paid') {
                            $totalPendapatan += $order->items->sum(fn($i) => $i->harga * $i->jumlah);
                        }
                    }
                @endphp
                Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
            </div>
        </div>
        <div class="summary-card">
            <h4>Rata-rata per Pesanan</h4>
            <div class="value">
                @php
                    $paidOrders = collect($orders)->where('payment_status', 'paid');
                    $avgOrder = $paidOrders->count() > 0 ? $totalPendapatan / $paidOrders->count() : 0;
                @endphp
                Rp {{ number_format($avgOrder, 0, ',', '.') }}
            </div>
        </div>
    </div>

    <!-- Detail Transaksi -->
    <div class="section-title">DETAIL TRANSAKSI</div>
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="12%">No. Pesanan</th>
                <th width="12%">Tanggal & Waktu</th>
                <th width="12%">Nama Pelanggan</th>
                <th width="25%">Menu & Qty</th>
                <th width="12%">Total</th>
                <th width="8%">Pembayaran</th>
                <th width="8%">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $i => $order)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td>{{ $order->order_id }}</td>
                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ $order->nama_pelanggan ?? '-' }}</td>
                <td>
                    @foreach($order->items as $item)
                        • {{ $item->menu->nama_menu }} ({{ $item->jumlah }}x)<br>
                    @endforeach
                </td>
                <td>
                    @php $total = $order->items->sum(fn($i) => $i->harga * $i->jumlah); @endphp
                    Rp {{ number_format($total, 0, ',', '.') }}
                </td>
                <td class="text-center">
                    <span class="status-{{ $order->payment_status }}">
                        {{ ucfirst($order->payment_status) }}
                    </span>
                </td>
                <td class="text-center">{{ ucfirst($order->status) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5"><strong>TOTAL KESELURUHAN</strong></td>
                <td>
                    @php
                        $grandTotal = 0;
                        foreach ($orders as $order) {
                            $grandTotal += $order->items->sum(fn($i) => $i->harga * $i->jumlah);
                        }
                    @endphp
                    <strong>Rp {{ number_format($grandTotal, 0, ',', '.') }}</strong>
                </td>
                <td colspan="2" class="text-center">
                    <strong>{{ collect($orders)->where('payment_status', 'paid')->count() }} Berhasil</strong>
                </td>
            </tr>
        </tfoot>
    </table>

    <!-- Analisa Menu Terlaris -->
    <div class="menu-analysis">
        <div class="section-title">ANALISA MENU TERLARIS</div>
        <table>
            <thead>
                <tr>
                    <th>Ranking</th>
                    <th>Nama Menu</th>
                    <th>Jumlah Terjual</th>
                    <th>Total Pendapatan</th>
                    <th>Persentase</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $menuStats = [];
                    foreach ($orders as $order) {
                        if ($order->payment_status === 'paid') {
                            foreach ($order->items as $item) {
                                $menuName = $item->menu->nama_menu;
                                if (!isset($menuStats[$menuName])) {
                                    $menuStats[$menuName] = ['qty' => 0, 'revenue' => 0];
                                }
                                $menuStats[$menuName]['qty'] += $item->jumlah;
                                $menuStats[$menuName]['revenue'] += $item->harga * $item->jumlah;
                            }
                        }
                    }
                    
                    // Sort by quantity sold
                    arsort($menuStats);
                    $totalMenuRevenue = array_sum(array_column($menuStats, 'revenue'));
                @endphp
                
                @foreach(array_slice($menuStats, 0, 10, true) as $index => $menuName)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $index }}</td>
                    <td class="text-center">{{ $menuName['qty'] }}</td>
                    <td class="text-right">Rp {{ number_format($menuName['revenue'], 0, ',', '.') }}</td>
                    <td class="text-center">
                        {{ $totalMenuRevenue > 0 ? number_format(($menuName['revenue'] / $totalMenuRevenue) * 100, 1) : 0 }}%
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Ringkasan Harian -->
    <div class="daily-summary">
        <div class="section-title">RINGKASAN PENJUALAN HARIAN</div>
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Jumlah Pesanan</th>
                    <th>Pesanan Berhasil</th>
                    <th>Total Pendapatan</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $dailyStats = [];
                    foreach ($orders as $order) {
                        $date = $order->created_at->format('Y-m-d');
                        if (!isset($dailyStats[$date])) {
                            $dailyStats[$date] = ['total_orders' => 0, 'paid_orders' => 0, 'revenue' => 0];
                        }
                        $dailyStats[$date]['total_orders']++;
                        if ($order->payment_status === 'paid') {
                            $dailyStats[$date]['paid_orders']++;
                            $dailyStats[$date]['revenue'] += $order->items->sum(fn($i) => $i->harga * $i->jumlah);
                        }
                    }
                    ksort($dailyStats);
                @endphp
                
                @foreach($dailyStats as $date => $stats)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($date)->format('d F Y') }}</td>
                    <td class="text-center">{{ $stats['total_orders'] }}</td>
                    <td class="text-center">{{ $stats['paid_orders'] }}</td>
                    <td class="text-right">Rp {{ number_format($stats['revenue'], 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    
    <!-- Footer Info -->
    <div class="footer-info">
        <hr>
        <p>Laporan ini digenerate secara otomatis oleh sistem Warung Ajus</p>
        <p>Untuk pertanyaan terkait laporan ini, hubungi admin sistem</p>
    </div>

    <!-- Print Button -->
    {{-- <div class="no-print" style="text-align: center; margin-top: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; background-color: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer;">
            Cetak Laporan
        </button>
    </div> --}}
</body>
</html>