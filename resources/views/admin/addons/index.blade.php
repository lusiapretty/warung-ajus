@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h4>Daftar Add-ons</h4>
    <button class="btn btn-success mb-3" data-toggle="modal" data-target="#addonModal">Tambah Add-on</button>

    <table class="table table-bordered" id="addon-table">
        <thead>
            <tr>
                <th>No</th>
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
    let table = $('#addon-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.addons.index') }}",
        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'nama' },
            { data: 'harga' },
            { data: 'aksi', orderable: false, searchable: false }
        ]
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
            $('#addonModal').modal('hide');
            // setTimeout(() => {
            //     $('#addonModal').modal('hide');
            // }, 200);

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
            title: 'Yakin ingin hapus?',
            text: "Data yang dihapus tidak bisa dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!'
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
