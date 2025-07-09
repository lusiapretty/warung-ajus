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

#menu-table th,
#menu-table td {
    padding: 8px 12px;
    font-size: 14px;
    white-space: nowrap;
}


.badge {
    padding: 6px 12px;
    font-size: 13px;
}

.table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.dataTables_length {
    margin-bottom: 10px;
}

.badge i.fas.fa-circle {
    font-size: 0.5rem;
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
                        <div class="col-md-3">
                            <input type="text" class="form-control" id="search-input" placeholder="Cari...">
                        </div>
                    </div>

                    <div class="d-flex gap-2 mb-3">
                        <button class="btn btn-danger" data-toggle="modal" data-target="#exportPdfModal">
                            <i class="fas fa-file-pdf"></i> Export PDF
                        </button>
                        <button class="btn btn-success" data-toggle="modal" data-target="#exportExcelModal">
                            <i class="fas fa-file-excel"></i> Export Excel
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="menu-table">
                            <thead>
                                <tr>
                                    <th>Order Id</th>
                                    <th>Tanggal Order</th>
                                    <th>Nama Pelanggan</th>
                                    <th>Nama Menu</th>
                                    <th>Jumlah</th>
                                    <th>Catatan</th>
                                    <th>Total Harga</th>
                                    <th>Tipe Pesanan</th>
                                    <th>No Meja</th>
                                    <th>Status Pembayaran</th>
                                    <th>Status Pesanan</th>
                                    <th>Aksi</th>
                                    <th style="display:none">Status Pesanan (Export)</th> 
                                </tr>
                            </thead>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="pc-content" style="display:none;"></div>
<div class="footer-wrapper" style="display:none;"></div>

<!-- Modal Export PDF -->
<div class="modal fade" id="exportPdfModal" tabindex="-1" role="dialog" aria-labelledby="pdfModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="pdfExportForm" method="GET" action="{{ route('admin.orders.export.pdf') }}">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Export PDF - Pilih Tanggal</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body row">
                <div class="col-md-6">
                    <label>Tanggal Mulai</label>
                    <input type="date" class="form-control" name="start_date" required>
                </div>
                <div class="col-md-6">
                    <label>Tanggal Akhir</label>
                    <input type="date" class="form-control" name="end_date" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="submitExportPdf">Export PDF</button>
            </div>
        </div>
    </form>
  </div>
</div>

<!-- Modal Export Excel -->
<div class="modal fade" id="exportExcelModal" tabindex="-1" role="dialog" aria-labelledby="excelModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="excelExportForm" method="GET" action="{{ route('admin.orders.export.excel') }}">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Export Excel - Pilih Tanggal</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body row">
                <div class="col-md-6">
                    <label>Tanggal Mulai</label>
                    <input type="date" class="form-control" name="start_date" required>
                </div>
                <div class="col-md-6">
                    <label>Tanggal Akhir</label>
                    <input type="date" class="form-control" name="end_date" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="submitExportExcel">Export Excel</button>
            </div>
        </div>
    </form>
  </div>
</div>

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

<!-- SweetAlert2 -->
<link  href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

{{-- 
@php
    $ajaxUrl = route('admin.orders.datatables');
    if (request()->isSecure()) {
        $ajaxUrl = preg_replace("/^http:/", "https:", $ajaxUrl);
    }
@endphp --}}

<script>
/**
 *  type  : 'success' | 'error' | 'info' | 'warning' | 'question'
 *  title : teks yang ingin ditampilkan
 */
function showToast(type, title){
    Swal.fire({
        // toast: true,
        // position: 'top',      // pojok kanan‑atas; ubah ke 'top' jika mau di tengah atas
        icon: type,
        title: title,
        showConfirmButton: false,
        timer: 1400,              // 1,8 detik
        timerProgressBar: true,
        width: '400px',
        position: 'center', 
    });
}
</script>


<script>
$(document).on('submit', '.no-meja-form', function(e) {
    e.preventDefault();

    const form = $(this);
    const orderId = form.data('order-id');
    const actionUrl = form.attr('action');
    const formData = form.serialize();

    $.ajax({
        url: actionUrl,
        type: 'POST',
        data: formData,
        success: function () {
            showToast('success', 'Nomor meja berhasil diperbarui');
            $('#menu-table').DataTable().ajax.reload(null, false);  // refresh baris tanpa reload
        },
        error: function (xhr) {
            const msg = xhr.responseJSON?.message || 'Gagal memperbarui nomor meja';
            showToast('error', msg);
        }
    });
});

$(document).on('submit', '.status-form', function(e) {
    e.preventDefault();
    const form = $(this);
    const actionUrl = form.attr('action');
    const formData = form.serialize();

    $.ajax({
        url: actionUrl,
        type: 'PATCH',
        data: formData,
        success: function () {
            showToast('success', 'Status pesanan diperbarui');
            $('#menu-table').DataTable().ajax.reload(null, false);
        },
        error: function (xhr) {
            const msg = xhr.responseJSON?.message || 'Gagal mengubah status pesanan';
            showToast('error', msg);
        }
    });
});

</script>


<script>
$(document).ready(function () {
    const table = $('#menu-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route('admin.orders.datatables') }}',
            data: function (d) {
                d.status_pembayaran = $('#filter-status-pembayaran').val();
                d.status_pesanan = $('#filter-status-pesanan').val();
                d.tanggal = $('#filter-tanggal').val();
                d.search = { value: $('#search-input').val() }; 
            }
        },
        dom: 'lrtip',
        buttons: [
            {
                extend: 'excel',
                title: 'Daftar Pesanan',
                exportOptions: {
                    columns: [0,1,2,3,4,5,6,7,8,9,12]
                }
            }
        ],
        columns: [
            { data: 'order_id', name: 'order_id' },
            { data: 'tanggal_order', name: 'tanggal_order' },
            { data: 'nama_pelanggan', name: 'nama_pelanggan', orderable: false, },
            { data: 'nama_menu', name: 'nama_menu', orderable: false, searchable: false },
            { data: 'jumlah_total', name: 'jumlah_total', orderable: false, searchable: false },
            { data: 'catatan', name: 'catatan', orderable: false, searchable: false },
            { data: 'total_harga', name: 'total_harga', orderable: false, searchable: false },
            { data: 'tipe_pesanan', name: 'tipe_pesanan', orderable: false, searchable: false },
            { data: 'no_meja', name: 'no_meja', orderable: false, searchable: false },
            { data: 'payment_status', name: 'payment_status', orderable: false, searchable: false},
            { data: 'status_pesanan', name: 'status_pesanan', orderable: false, searchable: false, exportable: false},
            { data: 'aksi', name: 'aksi', orderable: false, searchable: false },
            { data: 'status_pesanan_export', name: 'status_pesanan_export', visible: false},
        ]
    });

    $('#exportExcel').on('click', function () {
        table.button('.buttons-excel').trigger();
    });

    // Filter trigger
    $('#filter-status-pembayaran, #filter-status-pesanan, #filter-tanggal, #search-input').on('change keyup', function () {
        table.draw();
    });
});

$(document).ready(function () {
    $('#submitExportPdf').on('click', function () {
        const form = $('#pdfExportForm');
        const start = form.find('input[name="start_date"]').val();
        const end = form.find('input[name="end_date"]').val();

        if (!start || !end) {
            alert('Harap isi tanggal mulai dan akhir.');
            return;
        }

        $('#exportPdfModal').modal('hide'); // TUTUP MODAL DULU
        setTimeout(() => {
            window.location.href = `{{ route('admin.orders.export.pdf') }}?start_date=${start}&end_date=${end}`;
        }, 500); // Beri delay 500ms agar modal benar-benar tertutup
    });

    $('#submitExportExcel').on('click', function () {
        const form = $('#excelExportForm');
        const start = form.find('input[name="start_date"]').val();
        const end = form.find('input[name="end_date"]').val();

        if (!start || !end) {
            alert('Harap isi tanggal mulai dan akhir.');
            return;
        }

        $('#exportExcelModal').modal('hide');
        setTimeout(() => {
            window.location.href = `{{ route('admin.orders.export.excel') }}?start_date=${start}&end_date=${end}`;
        }, 500);
    });
});

</script>

<script>
    function updateSelectColor(selectElement) {
        const statusColors = {
            'pending': 'text-warning',
            'processing': 'text-primary',
            'completed': 'text-success',
            'cancelled': 'text-danger'
        };

        // Hapus semua class text-* sebelumnya
        selectElement.classList.remove('text-warning', 'text-primary', 'text-success', 'text-danger');

        // Tambahkan class baru sesuai value yang dipilih
        const selectedValue = selectElement.value;
        const newClass = statusColors[selectedValue] || 'text-secondary';
        selectElement.classList.add(newClass);
    }

    // Jalankan saat halaman dimuat untuk inisialisasi
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.status-select').forEach(select => {
            updateSelectColor(select);
        });
    });
</script>

<div class="pc-content" style="display:none;"></div>
<div class="footer-wrapper" style="display:none;"></div>

<script>
@if(session('success'))
    showToast('success', @json(session('success')));
@endif

@if(session('error'))
    showToast('error', @json(session('error')));
@endif
</script>

@endpush

