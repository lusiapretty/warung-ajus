@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Detail Pesanan</h4>
        </div>
        <div class="card-body">

            {{-- Informasi Pesanan --}}
            <table class="w-100 mb-4" style="border-collapse: collapse;">
                <tbody>
                    <tr>
                        <td style="width: 200px;" class="fw-bold text-start">Order ID</td>
                        <td>: {{ $order->order_id }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-start">Nama Pelanggan</td>
                        <td>: {{ $order->user->name ?? $order->nama_pelanggan }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-start">Tipe Pesanan</td>
                        <td>: 
                            <span class="badge bg-info text-white">{{ ucfirst($order->tipe_pesanan) }}</span>
                        </td>
                    </tr>
                    @if($order->tipe_pesanan === 'dine_in')
                    <tr>
                        <td class="fw-bold text-start">No Meja</td>
                        <td>: {{ $order->no_meja ?? '-' }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td class="fw-bold text-start">Status Pembayaran</td>
                        <td>: 
                            @php
                                $paymentColors = [
                                    'pending' => 'warning',
                                    'paid' => 'success',
                                    'failed' => 'danger',
                                    'expired' => 'secondary'
                                ];
                            @endphp
                            <span class="badge bg-{{ $paymentColors[$order->payment_status] ?? 'light' }}">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-start">Status Pesanan</td>
                        <td>: 
                            @php
                                $orderColors = [
                                    'pending' => 'warning',
                                    'processing' => 'primary',
                                    'completed' => 'success',
                                    'cancelled' => 'danger'
                                ];
                            @endphp
                            <span class="badge bg-{{ $orderColors[$order->status] ?? 'light' }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>

            {{-- Daftar Item --}}
            <h5 class="mt-4">Daftar Item</h5>
            <div class="table-responsive">
                <table class="table table-striped table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Menu</th>
                            <th>Jumlah</th>
                            <th>Harga</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->items as $item)
                        <tr>
                            <td>{{ $item->menu->nama_menu ?? '-' }}</td>
                            <td>{{ $item->jumlah }}</td>
                            <td>Rp{{ number_format($item->harga, 0, ',', '.') }}</td>
                            <td>Rp{{ number_format($item->harga * $item->jumlah, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Total --}}
            <p class="text-end fs-5 mt-3">
                <strong>Total:</strong> 
                Rp{{ number_format($order->items->sum(fn($i) => $i->harga * $i->jumlah), 0, ',', '.') }}
            </p>

            <a href="{{ route('dashboard') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</div>
@endsection
