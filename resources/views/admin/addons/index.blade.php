<style>
    .dataTables_length select {
        padding-right: 24px;
        background-position: right center;
        background-repeat: no-repeat;
        background-size: 16px 16px;
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        background-image: url("data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns='http://www.w3.org/2000/svg'%20width='16'%20height='16'%20fill='gray'%20class='bi%20bi-caret-down-fill'%20viewBox='0%200%2016%2016'%3E%3Cpath%20d='M7.247%2011.14%202.451%205.658c-.566-.64-.106-1.658.753-1.658h9.592c.86%200%201.32%201.018.753%201.658L8.753%2011.14a1%201%200%200%201-1.506%200z'/%3E%3C/svg%3E");
    }

    #addon-table {
        font-size: 0.85rem;
        border-collapse: collapse;
        width: 100%;
    }

    #addon-table, 
    #addon-table th, 
    #addon-table td {
        border: 1px solid #dee2e6 !important;
    }

    #addon-table thead th {
        font-size: 0.9rem;
        font-weight: bold;
    }

    #addon-table tbody td {
        font-size: 0.85rem;
    }

    #addon-table th, 
    #addon-table td {
        vertical-align: middle;
        padding: 8px 12px;
    }
    #addon-table th:first-child,
    #addon-table td:first-child {
        width: 20px;
    }

    th.col-no, td.col-no {
        width: 20px !important;
        text-align: center;
    }
    th.no-sort::before,
    th.no-sort::after {
        display: none !important;
    }

    .modal-backdrop {
        background-color: rgba(0, 0, 0, 0.2) !important; /* Lebih terang dari default */
    }

</style>

@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h4>Daftar Add-ons</h4>
    <button class="btn btn-primary mb-3" id="btn-open-addon-modal" data-target="#addonModal">Tambah Add-on</button>

    <table class="table table-bordered table-striped" id="addon-table">
        <thead>
            <tr>
                <th class="col-no no-sort">No</th>
                <th>Nama Add-on</th>
                <th>Harga</th>
                <th>Aksi</th>
            </tr>
        </thead>
    </table>
</div>

<!-- Modal Add/Edit -->
<div class="modal fade" id="addonModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <form id="addonForm">
            @csrf
            <input type="hidden" name="_method" id="_method" value="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Add-on</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="addon_id">
                    <div class="form-group">
                        <label>Nama Add-on</label>
                        <input type="text" name="nama" id="nama" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Harga</label>
                        <input type="number" name="harga" id="harga" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Script -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function () {

    // Buka modal tambah secara manual
    $('#btn-open-addon-modal').on('click', function () {
        $('#addonForm')[0].reset();
        $('#addon_id').val('');
        $('#_method').val('POST');
        $('.modal-title').text('Tambah Add-on');
        $('#addonForm button[type="submit"]').text('Simpan');
        $('#addonModal').modal('show');
    });

    let table = $('#addon-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.addons.index') }}",
        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false},
            { data: 'nama' },
            { data: 'harga',orderable: false },
            { data: 'aksi', orderable: false, searchable: false }
        ]
    });

    // Memastikan tombol x dan tombol tutup tetap berfungsi
    $('#addonModal .close, #addonModal .btn-secondary').on('click', function () {
        $('#addonModal').modal('hide');
    });


    $('#addonModal').on('hidden.bs.modal', function () {
        $('#addonForm')[0].reset();
        $('#_method').val('POST');
        $('#addon_id').val('');
        $('.modal-title').text('Tambah Add-on');
        $('#addonForm button[type="submit"]').text('Simpan');
    });

    $('#addonForm').on('submit', function (e) {
        e.preventDefault();
        const id = $('#addon_id').val();
        const method = $('#_method').val();
        const isEdit = method === 'PUT';
        const url = isEdit 
            ? `/admin/addons/update/${id}` 
            : `{{ route('admin.addons.store') }}`;

        const data = {
            nama: $('#nama').val(),
            harga: $('#harga').val(),
            _method: method,
            _token: $('input[name="_token"]').val()
        };

        $.post(url, data, function (res) {
            console.log('Callback success:', res);
            if ($('#addonModal').hasClass('show')) {
                $('#addonModal').modal('hide');
            }

            table.ajax.reload();

            Swal.fire({
                icon: 'success',
                title: isEdit ? 'Data berhasil diperbarui!' : 'Data berhasil disimpan!',
                showConfirmButton: false,
                timer: 1500
            });
        }).fail(function (xhr) {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: xhr.responseText || 'Terjadi kesalahan.'
            });
        });
    });

    $(document).on('click', '.btn-edit', function () {
        const id = $(this).data('id');
        $.get(`/admin/addons/${id}/edit`, function (data) {
            $('#addonModal').modal('show');
            $('#nama').val(data.nama);
            $('#harga').val(data.harga);
            $('#addon_id').val(data.id);
            $('#_method').val('PUT');
            $('.modal-title').text('Edit Add-on');
            $('#addonForm button[type="submit"]').text('Perbarui');
        });
    });

    $(document).on('click', '.btn-delete', function () {
        const id = $(this).data('id');
        Swal.fire({
            title: 'Yakin ingin menghapus add on ini?',
            text: "Data yang dihapus tidak bisa dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            background: '#fff',
            backdrop: `rgba(0, 0, 0, 0.2)`
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/admin/addons/${id}`,
                    method: 'POST',
                    data: {
                        _token: $('input[name="_token"]').val(),
                        _method: 'DELETE'
                    },
                    success: function () {
                        table.ajax.reload();
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil dihapus!',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    },
                    error: function () {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal menghapus!',
                        });
                    }
                });
            }
        });
    });
});
</script>

<script>
    // Mencegah error classList pada elemen null
    window.addEventListener('DOMContentLoaded', () => {
        try {
            var el = document.getElementById('box-container');
            if (el && el.classList) {
                el.classList.add('safe');
            }
        } catch(e) {
            console.warn("Safe fallback: box-container tidak ditemukan");
        }
    });
</script>

<div class="pc-content" style="display:none;"></div>
<div class="footer-wrapper" style="display:none;"></div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
