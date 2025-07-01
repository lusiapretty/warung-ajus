@extends('layouts.admin')

@section('content')

<style>
#menu-table {
    width: 100% !important;
    border-collapse: collapse;
}

#menu-table th, #menu-table td {
    border: 1px solid #dee2e6 !important;
    vertical-align: middle;
}

.badge {
    padding: 6px 12px;
    font-size: 13px;
}
</style>

<!-- Main Content -->
<div class="content-wrapper">
    <section class="content">
        <div class="container mt-4">
            <h3>Daftar Pesanan Pelanggan</h3>
            {{-- <div class="mb-3 d-flex justify-content-between align-items-center card-body table-responsive p-0">  
                <button class="btn btn-primary" data-toggle="modal" data-target="#createMenuModal">Tambah Pesanan</button>
            </div> --}}
        
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <select class="form-control" id="filter-status-pembayaran">
                                <option value="">Semua Pembayaran</option>
                                <option value="pending">Belum Dibayar</option>
                                <option value="paid">Sudah Dibayar</option>
                                <option value="failed">Gagal</option>
                                <option value="expired">Kedaluwarsa</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-control" id="filter-status-pesanan">
                                <option value="">Semua Pesanan</option>
                                <option value="pending">Menunggu</option>
                                <option value="processing">Diproses</option>
                                <option value="completed">Selesai</option>
                                <option value="cancelled">Dibatalkan</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="date" class="form-control" id="filter-tanggal">
                        </div>
                    </div>

                    <div class="d-flex gap-2 mb-3">
                        <a href="{{ route('admin.orders.export.pdf') }}" class="btn btn-danger">
                            <i class="fas fa-file-pdf"></i> Export PDF</a>
                        <button id="exportExcel" class="btn btn-success">
                            <i class="fas fa-file-excel"></i> Export Excel</button>
                    </div>

                    <table class="table table-bordered" id="menu-table">
                        <thead>
                            <tr>
                                <th>Order Id</th>
                                <th>Nama Pelanggan</th>
                                <th>Nama Menu</th>
                                <th>Jumlah</th>
                                <th>Catatan</th>
                                <th>Total Harga</th>
                                <th>Status Pembayaran</th>
                                <th>Status Pesanan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="pc-content" style="display:none;"></div>
<div class="footer-wrapper" style="display:none;"></div>

@endsection

@push('scripts')
<!-- jQuery harus sebelum DataTables -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<!-- DataTables core -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

<!-- Buttons -->
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>

<script>
$(document).ready(function () {
    const table = $('#menu-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route('admin.orders.datatables', true) }}',
            data: function (d) {
                d.status_pembayaran = $('#filter-status-pembayaran').val();
                d.status_pesanan = $('#filter-status-pesanan').val();
                d.tanggal = $('#filter-tanggal').val();
            }
        },
        dom: 'lfrtip',
        buttons: [
            {
                extend: 'excel',
                title: 'Daftar Pesanan',
                exportOptions: {
                    columns: ':not(:last-child)'
                }
            }
        ],
        columns: [
            { data: 'order_id', name: 'order_id' },
            { data: 'nama_pelanggan', name: 'nama_pelanggan' },
            { data: 'nama_menu', name: 'nama_menu', orderable: false, searchable: false },
            { data: 'jumlah_total', name: 'jumlah_total', orderable: false, searchable: false },
            { data: 'catatan', name: 'catatan', orderable: false, searchable: false },
            { data: 'total_harga', name: 'total_harga', orderable: false, searchable: false },
            { data: 'payment_status', name: 'payment_status', orderable: false, searchable: false },
            { data: 'status_pesanan', name: 'status_pesanan', orderable: false, searchable: false },
            { data: 'aksi', name: 'aksi', orderable: false, searchable: false },
        ]
    });

    $('#exportExcel').on('click', function () {
        table.button('.buttons-excel').trigger();
    });

    // Filter trigger
    $('#filter-status-pembayaran, #filter-status-pesanan, #filter-tanggal').on('change', function () {
        table.draw();
    });
});
</script>
@endpush

