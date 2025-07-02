@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4"><i class="fas fa-box-open text-warning me-2"></i>Pesanan Saya</h3>

    @forelse($orders as $order)
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <div>
                <strong>ID:</strong> <span class="text-primary">{{ $order->order_id }}</span><br>
                <small class="text-muted">{{ $order->created_at->format('d M Y, H:i') }}</small>
            </div>
            <span class="badge 
                @if($order->status === 'selesai') bg-success 
                @elseif($order->status === 'diproses') bg-info 
                @else bg-secondary 
                @endif">
                {{ ucfirst($order->status ?? 'belum_dibayar') }}
            </span>
        </div>

        <div class="card-body">
            <p><i class="fas fa-user me-1 text-muted"></i>Nama:  <strong>{{ $order->nama_pelanggan }}</strong></p>
            <p><i class="fas fa-utensils me-1 text-muted"></i> Tipe: <strong>{{ ucfirst(str_replace('_', ' ', $order->tipe_pesanan)) }}</strong></p>
            @if($order->tipe_pesanan === 'dine_in')
            <p><i class="fas fa-chair me-1 text-muted"></i> No Meja: <strong>{{ $order->no_meja }}</strong></p>
            @endif

            <hr>
            <p class="mb-1 fw-bold">🍽️ Rincian Pesanan:</p>
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
                            <small class="text-muted">Subtotal:</small><br>
                            <strong>Rp{{ number_format($totalPerItem, 0, ',', '.') }}</strong>
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
    @empty
    <div class="alert alert-info text-center">
        <i class="fas fa-info-circle me-2"></i> Belum ada pesanan yang dibuat.
    </div>
    @endforelse
</div>
@endsection
