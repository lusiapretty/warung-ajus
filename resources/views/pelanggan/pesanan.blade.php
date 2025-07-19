@extends('layouts.app')

@section('content')
<div class="bg-pesanan">
    <div class="container mt-4 text-light">
        <h3 class="mb-4"><i class="fas fa-box-open text-warning me-2"></i>Pesanan Saya</h3>

        @forelse($orders as $order)
        <div class="card shadow-sm mb-4 border-0">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <div>
                    <strong>ID:</strong> <span class="text-primary">{{ $order->order_id }}</span><br>
                    <small class="text-muted">{{ $order->created_at->format('d M Y, H:i') }}</small>
                </div>
                @php
                // Mapping status ke bahasa indonesia
                $statusMapping = [
                    'completed'     => 'Selesai',
                    'selesai'       => 'selesai',
                    'processing'    => 'Sedang Diproses',
                    'diproses'      => 'Sedang Diproses',
                    'pending'       => 'Menunggu',
                    'menunggu'      => 'Menunggu',
                    'belum_dibayar' => 'Belum Dibayar'
                ];
                $displayStatus = $statusMapping[$order->status] ?? ucfirst(str_replace('_', ' ', $order->status ?? 'belum_dibayar'));

                @endphp
                <span class="badge 
                    @if($order->status === 'selesai' || $order->status === 'completed') bg-success 
                    @elseif($order->status === 'diproses' || $order->status === 'processing') bg-primary 
                    @elseif($order->status === 'menunggu' || $order->status === 'pending') bg-warning text-dark
                    @else bg-secondary 
                    @endif">
                    {{ $displayStatus}}
                </span>
            </div>

            <div class="card-body">
                <div class="d-flex mb-2">
                    <div style="width: 150px;">
                        <i class="fas fa-user me-1 text-muted"></i><strong>Nama</strong>
                    </div>
                    <div>
                        <strong> : {{ $order->nama_pelanggan }}</strong>
                    </div>
                </div>
                
                <div class="d-flex mb-2">
                    <div style="width: 150px;">
                        <i class="fas fa-utensils me-1 text-muted"></i><strong>Tipe Pesanan</strong>
                    </div>
                    <div>
                        <strong> : {{ ucfirst(str_replace('_', ' ', $order->tipe_pesanan)) }}</strong>
                    </div>
                </div>
                
                @if($order->tipe_pesanan === 'dine_in')
                <div class="d-flex mb-2">
                    <div style="width: 150px;">
                        <i class="fas fa-chair me-1 text-muted"></i><strong>No Meja</strong>
                    </div>
                    <div>
                        <strong> : {{ $order->no_meja }}</strong>
                    </div>
                </div>
                @endif

                <hr>
                <p class="mb-1 fw-bold">🍽️ Rincian Pesanan:</p>
                 @php
                    $grandTotal = 0;
                    // Hitung total keseluruhan terlebih dahulu
                    foreach($order->items as $orderItem) {
                        $addons = json_decode($orderItem->addons, true) ?? [];
                        $addonTotal = collect($addons)->sum('price');
                        $totalPerItem = ($orderItem->harga + $addonTotal) * $orderItem->jumlah;
                        $grandTotal += $totalPerItem;
                    }
                @endphp
                <ul class="list-group list-group-flush">
                    @foreach($order->items as $item)
                    @php
                        $addons = json_decode($item->addons, true) ?? [];
                        $addonTotal = collect($addons)->sum('price');
                        $totalPerItem = ($item->harga + $addonTotal) * $item->jumlah;
                    @endphp
                    <li class="list-group-item px-0">
                        <div class="d-flex justify-content-between">
                            <div>
                                <strong>{{ $item->menu->nama_menu }}</strong>
                                <span class="badge bg-warning text-dark ms-2">x{{ $item->jumlah }}</span>
                                @if($item->catatan)
                                    <br><small class="text-muted">Catatan: {{ $item->catatan }}</small>
                                @endif
                                @if(count($addons) > 0)
                                    <br><small class="text-muted">Addon:</small>
                                    <ul class="mb-0 ps-3">
                                        @foreach($addons as $addon)
                                            <li>{{ $addon['name'] }} (+Rp{{ number_format($addon['price'], 0, ',', '.') }})</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                            <div class="text-end">
                                <small class="text-muted">Total:</small><br>
                                <strong>Rp {{ number_format($totalPerItem, 0, ',', '.') }}</strong>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="card-footer bg-light">
                <div class="d-flex justify-content-between align-items-center">
                    <strong class="mb-0 text-dark">Subtotal:</strong>
                    <h6 class="mb-0 text-danger fw-bold">Rp {{ number_format($grandTotal, 0, ',', '.') }}</h6>
                </div>
            </div>
        </div>
        @empty
        <div class="alert alert-info text-center">
            <i class="fas fa-info-circle me-2"></i> Belum ada pesanan yang dibuat.
        </div>
        @endforelse
    </div>
</div>
@endsection
