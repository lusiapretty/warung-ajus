@extends('layouts.admin')

@section('content')

<!-- Main Content -->
<div class="content-wrapper">
    <section class="content">
        <div class="container mt-4">
            <h4>Daftar Menu Warung Ajus</h4>
            <div class="mb-3 d-flex justify-content-between align-items-center card-body table-responsive p-0">  
                <button class="btn btn-primary" data-toggle="modal" data-target="#createMenuModal">Tambah Menu</button>
            </div>
        
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered" id="menu-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Menu</th>
                                <th>Deskripsi</th>
                                <th>Harga</th>
                                <th>Kategori</th>
                                <th>Gambar</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Create Menu Modal -->
        <div class="modal fade" id="createMenuModal" tabindex="-1" role="dialog" aria-labelledby="createMenuModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="{{ route('admin.menu.store')}}" id="create-menu-form" enctype="multipart/form-data" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="createMenuModalLabel">Tambah Menu</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="close" id="btn-close-modal">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div id="create-response-message"></div>
                            <input type="hidden" name="id" id="menu_id">
                            <div class="form-group">
                                <label for="nama-menu">Nama Menu</label>
                                <input type="text" class="form-control" name="nama_menu" id="nama_menu" required>
                            </div>
                            <div class="form-group">
                                <label for="deskripsi">Deskripsi</label>
                                <textarea class="form-control" name="deskripsi" id="deskripsi"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="harga">Harga</label>
                                <input type="number" class="form-control" name="harga" id="harga" required>
                            </div>
                            <div class="form-group">
                                <label for="kategori">Kategori</label>
                                <select name="kategori" id="kategori" class="form-control" required>
                                    <option value="makanan">Makanan</option>
                                    <option value="minuman">Minuman</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="gambar">Gambar</label>
                                <input type="file" class="form-control" name="gambar">
                            </div>
                        </div>
                        <div class="modal-footer">
                                <button type="submit" class="btn btn-primary" id="btn-simpan">Simpan</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- DataTables & SweetAlert -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.5/css/dataTables.dataTables.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script type="text/javascript">
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(document).ready(function () {
    // $.noConflict();
    $('#menu-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('admin.menu.index') }}",
            type: 'GET'
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'nama_menu', name: 'nama_menu' },
            { data: 'deskripsi', name: 'deskripsi' },
            { data: 'harga', name: 'harga' },
            { data: 'kategori', name: 'kategori' },
            { data: 'gambar', name: 'gambar'},
            { data: 'aksi', name: 'aksi', orderable: false, searchable: false }
        ]   
        }); 

    // Create Menu
    $('#create-menu-form').on('submit', function (e) {
        e.preventDefault();

         console.log('🧪 Mode:', $('#create-menu-form input[name="_method"]').val());


        var form = $(this);
        // var formElement = $('#create-menu-form')[0];
        var formData = new FormData(this);
        var actionUrl = form.attr('action');
        var method = form.find('input[name="_method"]').val() || 'POST';
        
        for (var pair of formData.entries()) {
            console.log(pair[0]+ ': ' + pair[1]);
        }


        $.ajax({
            url: actionUrl,
            method: 'POST',
            data: formData,
            dataType: "json",
            contentType: false,
            processData: false,
            success: function(response) {
                console.log("Respons dari server:", response);
                console.log("AJAX success:", response);


                if(response.success) {
                    console.log('Menjalankan modal hide...');
                    // 🔄 Cek apakah ini update atau create
                    let isUpdate = $('#create-menu-form input[name="_method"]').val() === 'PUT';

                    // Tutup modal
                    $('#createMenuModal').modal('hide');

                    // Force cleanup (just in case)
                    setTimeout(function () {
                        $('body').removeClass('modal-open');
                        $('.modal-backdrop').remove();
                    }, 300);

                    // Reset form dan reload data
                    // $('body').removeClass('modal-open');
                    // $('.modal-backdrop').remove();
                    $('#create-menu-form')[0].reset();
                    $('#create-menu-form input[name="_method"]').remove();
                    $('#menu-table').DataTable().ajax.reload();


                    // Notifikasi
                    if (isUpdate) {
                        alert("Menu berhasil diperbarui!");
                    } else {
                        alert("Menu berhasil ditambahkan!");
                    }
                } else {
                    alert("Error: " + response.message);
                }
            },

            error: function(xhr) {
                console.log("AJAX error:", xhr.status, xhr.responseText);
                
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    var errorMessages = '';
                    $.each(errors, function(key, value) {
                        errorMessages += value + '\n';
                    });
                    alert("Validasi Gagal:\n" + errorMessages);
                } else {
                    alert('Error: ' + xhr.responseText);
                }
                console.log("Error:", xhr.status, xhr.responseText);
            }

                });

                return false;
            });


    // Edit Menu (saat klik tombol edit)
    $(document).on('click', '.btn-edit', function () {
        var id = $(this).data('id');
        var editUrl = '/admin/menu/'+ id + '/edit'; // URL untuk mendapatkan data menu yang akan diedit
        var updateUrl = '/admin/menu/update/' + id; // URL untuk action update


        $.get(editUrl, function (data) {
            console.log("Data dari server untuk edit:", data);

            $('#createMenuModal').modal('show');
            $('#createMenuModalLabel').text('Edit Menu');
            $('#create-menu-form').attr('action', updateUrl); // ganti action form ke update/{id}

            // Isi field form dari data
            $('#nama_menu').val(data.nama_menu);
            $('#deskripsi').val(data.deskripsi);
            $('#harga').val(data.harga);
            $('#kategori').val(data.kategori);

            // Tambahkan _method PUT jika belum ada
            if ($('#create-menu-form input[name="_method"]').length === 0) {
                $('#create-menu-form').append('<input type="hidden" name="_method" value="PUT">');
            }
        });
    });


        
    
    // Delete Menu
    $(document).on('click', '.btn-delete', function() {
        var id = $(this).data('id');
        if (confirm('Apakah anda yakin untuk menghapus data menu ini?')) {
            $.ajax({
                url: '/admin/menu/' + id,
                method: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    _method: 'DELETE'
                },
                success: function(response) {
                    $('#menu-table').DataTable().ajax.reload();
                    alert('Menu berhasil dihapus.');
                },
                error: function(xhr) {
                    alert('Error saat menghapus menu.');
                }
            });
        }
    });

   // Reset form saat modal dibuka dari tombol Tambah Menu
    $('#createMenuModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // tombol yang memicu modal

        if (button && button.hasClass('btn-primary')) {
            // Reset form
            $(this).find('form')[0].reset();
            $('#create-menu-form').trigger('reset');
            $('#createMenuModalLabel').text('Tambah Menu');
            $('#create-menu-form').attr('action', '/admin/menu/store');
            $('#create-menu-form input[name="_method"]').remove(); // hapus input method PUT kalau ada
        }
    });
 

});

</script>
@endsection
