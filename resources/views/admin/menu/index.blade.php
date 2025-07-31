<style>
#menu-table {
    width: 100% !important;
    border-collapse: collapse;
}

#menu-table th, #menu-table td {
    border: 1px solid #dee2e6 !important;
    vertical-align: middle;
    padding: 8px 12px;
    font-size: 14px;
    white-space: nowrap;
}

#menu-table td.wrap-text {
    white-space: normal;
    word-wrap: break-word;
    max-width: 250px; /* bisa disesuaikan */
}

.badge {
    padding: 6px 12px;
    font-size: 13px;
}

.table-responsive {
    overflow-x: auto;
}

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

.modal-backdrop {
        background-color: rgba(0, 0, 0, 0.2) !important; /* Lebih terang dari default */
    }
</style>

@extends('layouts.admin')

@section('content')

<!-- Main Content -->
<div class="content-wrapper">
    <section class="content">
        <div class="container mt-4">
            <h3>Daftar Menu Warung Ajus</h3>
            <div class="mb-3 d-flex justify-content-between align-items-center card-body table-responsive p-0">  
                <button class="btn btn-primary" id="btn-open-modal" data-target="#createMenuModal">Tambah Menu</button>
            </div>
            
             <!-- Filter Kategori -->
            <div class="row mb-3">
                <div class="col-md-3">
                    <select class="form-control" id="filter-kategori">
                        <option value="">Semua Kategori</option>
                        <option value="makanan">Makanan</option>
                        <option value="minuman">Minuman</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-control" id="filter-stok">
                        <option value="">Semua Stok</option>
                        <option value="tersedia">Tersedia</option>
                        <option value="habis">Habis</option>
                    </select>
                </div>
            </div>
        
            <div class="card">
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-striped" id="menu-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Menu</th>
                                <th>Add-ons</th>
                                <th>Deskripsi</th>
                                <th>Harga</th>
                                <th>Kategori</th>
                                <th>Gambar</th>
                                <th>Stok</th>
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
                                <label for="addons">Add-ons (opsional)</label>
                                <div class="row">
                                    @foreach($addons as $addon)
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="addons[]" value="{{ $addon->id }}" id="addon_{{ $addon->id }}">
                                                <label class="form-check-label" for="addon_{{ $addon->id }}">
                                                    {{ $addon->nama }} (+Rp{{ number_format($addon->harga, 0, ',', '.') }})
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
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
                                <input type="file" class="form-control" name="gambar" id="gambar" required>
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

<!-- CSS DataTables dan Bootstrap 4 theme -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- Bootstrap Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script type="text/javascript">
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// Fungsi validasi frontend
function validateForm() {
    let isValid = true;
    let errorMessages = [];

    // Validasi nama menu (wajib diisi dan hanya huruf)
    const namaMenu = $('#nama_menu').val().trim();
    if (!namaMenu) {
        errorMessages.push('Nama menu wajib diisi.');
        $('#nama_menu').addClass('is-invalid');
        isValid = false;
    } else if (!/^[a-zA-Z\s]+$/.test(namaMenu)) {
        errorMessages.push('Nama menu hanya boleh berisi huruf dan spasi.');
        $('#nama_menu').addClass('is-invalid');
        isValid = false;
    } else {
        $('#nama_menu').removeClass('is-invalid');
    }

    // Validasi harga (wajib diisi dan hanya angka)
    const harga = $('#harga').val().trim();
    if (!harga) {
        errorMessages.push('Harga wajib diisi.');
        $('#harga').addClass('is-invalid');
        isValid = false;
    } else if (!/^\d+$/.test(harga) || parseInt(harga) < 1) {
        errorMessages.push('Harga hanya boleh berisi angka dan minimal 1.');
        $('#harga').addClass('is-invalid');
        isValid = false;
    } else {
        $('#harga').removeClass('is-invalid');
    }

    // Validasi gambar (wajib untuk tambah, opsional untuk edit)
    const isEditMode = $('#create-menu-form input[name="_method"]').val() === 'PUT';
    const gambarFile = $('#gambar')[0].files[0];
    
    if (!isEditMode && !gambarFile) {
        errorMessages.push('Gambar wajib dipilih.');
        $('#gambar').addClass('is-invalid');
        isValid = false;
    } else if (gambarFile) {
        // Validasi ukuran file (maksimal 5MB)
        const maxSize = 5 * 1024 * 1024; // 5MB dalam bytes
        if (gambarFile.size > maxSize) {
            errorMessages.push('Ukuran gambar maksimal 5MB.');
            $('#gambar').addClass('is-invalid');
            isValid = false;
        } else if (!['image/jpeg', 'image/jpg', 'image/png'].includes(gambarFile.type)) {
            errorMessages.push('Format gambar harus JPG, JPEG, atau PNG.');
            $('#gambar').addClass('is-invalid');
            isValid = false;
        } else {
            $('#gambar').removeClass('is-invalid');
        }
    } else {
        $('#gambar').removeClass('is-invalid');
    }

    // Tampilkan pesan error jika ada
    if (!isValid) {
        Swal.fire({
            icon: 'error',
            title: 'Validasi Gagal',
            html: errorMessages.join('<br>'),
            timer: 3000,
            showConfirmButton: false,
        });
    }

    return isValid;
}

// Update script yang sudah ada
$(document).ready(function () {
    // Validasi real-time untuk nama menu
    $('#nama_menu').on('input', function() {
        const value = $(this).val();
        if (value && !/^[a-zA-Z\s]+$/.test(value)) {
            $(this).addClass('is-invalid');
            if (!$('#nama_menu_error').length) {
                $(this).after('<div id="nama_menu_error" class="invalid-feedback">Nama menu hanya boleh berisi huruf dan spasi.</div>');
            }
        } else {
            $(this).removeClass('is-invalid');
            $('#nama_menu_error').remove();
        }
    });

    // Validasi real-time untuk harga
    $('#harga').on('input', function() {
        const value = $(this).val();
        if (value && (!/^\d+$/.test(value) || parseInt(value) < 1)) {
            $(this).addClass('is-invalid');
            if (!$('#harga_error').length) {
                $(this).after('<div id="harga_error" class="invalid-feedback">Harga hanya boleh berisi angka dan minimal 1.</div>');
            }
        } else {
            $(this).removeClass('is-invalid');
            $('#harga_error').remove();
        }
    });

    // Validasi real-time untuk gambar
    $('#gambar').on('change', function() {
        const file = this.files[0];
        const maxSize = 5 * 1024 * 1024; // 5MB
        
        if (file) {
            if (file.size > maxSize) {
                $(this).addClass('is-invalid');
                if (!$('#gambar_error').length) {
                    $(this).after('<div id="gambar_error" class="invalid-feedback">Ukuran gambar maksimal 5MB.</div>');
                }
            } else if (!['image/jpeg', 'image/jpg', 'image/png'].includes(file.type)) {
                $(this).addClass('is-invalid');
                if (!$('#gambar_error').length) {
                    $(this).after('<div id="gambar_error" class="invalid-feedback">Format gambar harus JPG, JPEG, atau PNG.</div>');
                }
            } else {
                $(this).removeClass('is-invalid');
                $('#gambar_error').remove();
                
                // Tampilkan preview gambar
                const reader = new FileReader();
                reader.onload = function(e) {
                    if (!$('#image_preview').length) {
                        $('#gambar').after('<div id="image_preview" class="mt-2"><img src="" style="max-width: 200px; max-height: 200px;" class="img-thumbnail"></div>');
                    }
                    $('#image_preview img').attr('src', e.target.result);
                };
                reader.readAsDataURL(file);
            }
        } else {
            $(this).removeClass('is-invalid');
            $('#gambar_error').remove();
            $('#image_preview').remove();
        }
    });
    $('#btn-open-modal').on('click', function () {
        // Reset form & modal title
        $('#createMenuModalLabel').text('Tambah Menu');
        $('#create-menu-form')[0].reset();
        $('#create-menu-form input[name="_method"]').remove();
        $('#create-menu-form').attr('action', '/admin/menu/store');

        // Reset validation states
        $('.is_invalid').removeClass('is-invalid');
        $('.invalid_feedback').remove();
        $('#image_preview').remove(); 

        // Tampilkan modal
        $('#createMenuModal').modal('show');
    });

    $('#createMenuModal .close, #createMenuModal .btn-secondary').on('click', function () {
        $('#createMenuModal').modal('hide');
    });

    // $.noConflict();
    var table = $('#menu-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        order: [],
        dom: '<"top d-flex justify-content-between align-items-center mb-3"l f>' +
             'rt' +
             '<"bottom d-flex justify-content-between align-items-center mt-2"i p>',
        ajax: {
            url: "{{ route('admin.menu.index') }}",
            type: 'GET',
            data: function(d) {
                d.kategori = $('#filter-kategori').val();
                d.stok = $('#filter-stok').val(); 
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'nama_menu', name: 'nama_menu' },
            { data: 'addons', name: 'addons', orderable: false, searchable: false, className: 'wrap-text'},
            { data: 'deskripsi', name: 'deskripsi', orderable: false, className: 'wrap-text' },
            { data: 'harga', name: 'harga', orderable: false },
            { data: 'kategori', name: 'kategori', orderable: false },
            { data: 'gambar', name: 'gambar', orderable: false},
            { data: 'stok', name: 'stok', orderable: false },
            { data: 'aksi', name: 'aksi', orderable: false, searchable: false }
        ],
        columnDefs: [
            {
                targets: 0,
                orderable: false,
                className: 'no-sort'
            }
        ]  
        }); 

        // Trigger filter
        $('#filter-kategori, #filter-stok').on('change', function () {
            table.draw();
        });

    // Create Menu
    $('#create-menu-form').on('submit', function (e) {
        e.preventDefault();

        // Jalankan validasi frontend
        if (!validateForm()) {
            return false; // Jika validasi gagal, hentikan proses
        }

        var form = $(this);
        var formData = new FormData(this);
        formData.append('_token', $('meta[name="csrf-token"]').attr('content')); 

         if ($('#create-menu-form input[name="_method"]').val() === 'PUT') {
            formData.append('_method', 'PUT');
        }
        var actionUrl = form.attr('action');
        var method = 'POST';

        // var gambarFile = $('#gambar')[0].files[0];
        // for (var pair of formData.entries()) {
        //     console.log(pair[0]+ ': ' + pair[1]);
        // }

        // Disable submit button untuk mencegah double submit
        $('#btn-simpan').prop('disabled', true).text('Menyimpan...');

        // if (!gambarFile) {
        //     alert('Gambar belum dipilih!');
        //     return;
        // }

        $.ajax({
            url: actionUrl,
            method: method,
            data: formData,
            dataType: "json",
            contentType: false,
            processData: false,
            success: function(response) {
                console.log("Respons dari server:", response);
                // console.log("AJAX success:", response);

                if(response.success) {
                    let isUpdate = $('#create-menu-form input[name="_method"]').val() === 'PUT';

                    // Tutup modal
                    $('#createMenuModal').modal('hide');

                    // Reset form
                    $('#create-menu-form')[0].reset();
                    $('#create-menu-form input[name="_method"]').remove();
                    $('.is-invalid').removeClass('is-invalid');
                    $('.invalid-feedback').remove();
                    $('#image_preview').remove();
                    
                    // Reload table
                    $('#menu-table').DataTable().ajax.reload();

                    // Notifikasi
                    Swal.fire({
                        icon: 'success',
                        title: isUpdate ? 'Menu Diperbarui' : 'Menu Ditambahkan',
                        text: isUpdate ? 'Data menu berhasil diperbarui.' : 'Data menu berhasil ditambahkan.',
                        timer: 1500,
                        showConfirmButton: false,
                        // toast: true,
                        // position: 'top-end'
                    });
                }
                else {
                   Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Terjadi kesalahan saat menyimpan data.',
                    });
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
                    Swal.fire({
                        icon: 'error',
                        title: 'Validasi Gagal',
                        html: errorMessages.replace(/\n/g, '<br>'),
                        timer: 2000,
                        showConfirmButton: false,
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Terjadi kesalahan pada server.',
                    });
                }
            },
                complete: function() {
                // Re-enable submit button
                $('#btn-simpan').prop('disabled', false).text('Simpan');
                }

        });

                return false;
            });


    $(document).on('click', '.toggle-status', function () {
    var id = $(this).data('id');

        $.ajax({
            url: '/admin/menu/toggle-status/' + id,
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                $('#menu-table').DataTable().ajax.reload();
                Swal.fire({
                    icon: 'success',
                    title: response.message,
                    timer: 1200,
                    showConfirmButton: false
                });
            },
            error: function (xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Tidak bisa mengubah status menu.',
                });
            }
        });
    });


    // Edit Menu (saat klik tombol edit)
    $(document).on('click', '.btn-edit', function () {
        var id = $(this).data('id');
        var editUrl = '/admin/menu/'+ id + '/edit'; // URL untuk mendapatkan data menu yang akan diedit
        var updateUrl = '/admin/menu/update/' + id; // URL untuk action update


        $.get(editUrl, function (data) {
            console.log("Data dari server untuk edit:", data);

            $('#createMenuModal').modal('show');

            // Reset validation states
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();
            $('#image_preview').remove();

            // Isi field form dari data
            $('#nama_menu').val(data.nama_menu);
            // $('addons[]').prop('checked', false); // Uncheck semua checkbox addons
            $('#deskripsi').val(data.deskripsi);
            $('#harga').val(data.harga);
            $('#kategori').val(data.kategori);

            // Centang checkbox addon yang dipilih
            $('input[name="addons[]"]').each(function() {
                const id = parseInt($(this).val());
                $(this).prop('checked', data.addons.includes(id));
            });

            $('#createMenuModalLabel').text('Edit Menu');
            $('#create-menu-form').attr('action', updateUrl); // ganti action form ke update/{id}


            // Tambahkan _method PUT jika belum ada
            if ($('#create-menu-form input[name="_method"]').length === 0) {
                $('#create-menu-form').append('<input type="hidden" name="_method" value="PUT">');
            }
        });
    });
    
    
    // Delete Menu
    $(document).on('click', '.btn-delete', function() {
        var id = $(this).data('id');
        Swal.fire({
            title: 'Yakin ingin menghapus menu ini?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal',
            background: '#fff',
            backdrop: `rgba(0, 0, 0, 0.2)`
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/admin/menu/' + id,
                    method: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        _method: 'DELETE'
                    },
                    success: function(response) {
                        $('#menu-table').DataTable().ajax.reload();
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Menu berhasil dihapus.',
                            timer: 1500,
                            showConfirmButton: false,
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Terjadi kesalahan saat menghapus menu.',
                            timer: 1800,
                            showConfirmButton: false,
                            // toast: true,
                            // position: 'top-end'
                        });
                    }
                });
            }
        });
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
            $('#create-menu-form input[name="_method"]').remove();

            // Reset validation
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();
            $('#image_preview').remove();

            // Gambar wajib untuk tambah menu
            $('#gambar').attr('required', 'required');
        }
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

{{-- Tambahkan sebelum @endsection --}}
<div class="pc-content" style="display:none;"></div>
<div class="footer-wrapper" style="display:none;"></div>

@endsection
