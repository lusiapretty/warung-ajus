@extends('layouts.admin')

@section('content')
<!-- Main Content -->
<div class="content-wrapper">
    <section class="content">
        <div class="container mt-4">
            <h3>Daftar Pesanan Pelanggan</h3>
            <div class="mb-3 d-flex justify-content-between align-items-center card-body table-responsive p-0">  
                <button class="btn btn-primary" data-toggle="modal" data-target="#createMenuModal">Tambah Menu</button>
            </div>
        
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered" id="menu-table">
                        <thead>
                            <tr>
                                <th>Order Id</th>
                                <th>Nama Pelanggan</th>
                                <th>Nama Menu</th>
                                <th>Jumlah</th>
                                <th>Catatan</th>
                                <th>Total Harga</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 1; @endphp
                            @foreach ($orders as $order)
                                <tr>
                                    <td>{{ $order->id }}</td>
                                    <td>{{ $order->nama_pelanggan }}</td>
                                    <td>{{ $order->items->count() > 0 ? implode(', ', $order->items->map(fn($i) => $i->menu->nama_menu . ' (' . $i->jumlah . ')')->toArray()) : '-' }}</td>
                                    <td>{{ $order->items->sum('jumlah') }}</td>
                                    <td>
                                        @foreach ($order->items as $item)
                                            <div>- {{ $item->menu->nama_menu ?? '-' }}: {{ $item->catatan ?? '-'}}</div>
                                        @endforeach
                                    </td>

                                    <td>Rp. {{ number_format($order->items->sum(fn($i) => ($i->harga + collect(json_decode($i->addons))->sum('price')) * $i->jumlah), 0, ',', '.') }}</td>
                                    <td>
                                        <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
