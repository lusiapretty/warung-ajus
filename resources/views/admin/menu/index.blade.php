@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h4>Daftar Menu Warung Ajus</h4>
    <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#createModal">+ Tambah Menu</button>

    <table class="table table-bordered" id="menuTable">
        <thead>
            <tr>
                <th>Nama Menu</th>
                <th>Deskripsi</th>
                <th>Harga</th>
                <th>Kategori</th>
                <th>Gambar</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($menus as $menu)
            <tr id="menuRow{{ $menu->id }}">
                <td>{{ $menu->nama_menu }}</td>
                <td>{{ $menu->deskripsi }}</td>
                <td>Rp {{ number_format($menu->harga, 0, ',', '.') }}</td>
                <td>{{ ucfirst($menu->kategori) }}</td>
                <td>
                    @if($menu->gambar)
                        <img src="{{ asset('storage/' . $menu->gambar) }}" width="60">
                    @endif
                </td>
                <td>
                    <button class="btn btn-warning btn-sm editBtn" data-id="{{ $menu->id }}" data-nama="{{ $menu->nama_menu }}" data-deskripsi="{{ $menu->deskripsi }}" data-harga="{{ $menu->harga }}" data-kategori="{{ $menu->kategori }}">Edit</button>
                    <button class="btn btn-danger btn-sm deleteBtn" data-id="{{ $menu->id }}">Hapus</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal Create -->
<div class="modal fade" id="createModal" tabindex="-1">
  <div class="modal-dialog">
    <form id="createMenuForm" enctype="multipart/form-data">
      @csrf
      <div class="modal-content">
        <div class="modal-header"><h5>Tambah Menu Baru</h5></div>
        <div class="modal-body">
          <input type="hidden" name="id" id="menu_id">
          <div class="form-group">
              <label>Nama Menu</label>
              <input type="text" class="form-control" name="nama_menu" id="nama_menu" required>
          </div>
          <div class="form-group">
              <label>Deskripsi</label>
              <textarea class="form-control" name="deskripsi" id="deskripsi"></textarea>
          </div>
          <div class="form-group">
              <label>Harga</label>
              <input type="number" class="form-control" name="harga" id="harga" required>
          </div>
          <div class="form-group">
              <label>Kategori</label>
              <select name="kategori" id="kategori" class="form-control" required>
                  <option value="makanan">Makanan</option>
                  <option value="minuman">Minuman</option>
              </select>
          </div>
          <div class="form-group">
              <label>Gambar</label>
              <input type="file" class="form-control" name="gambar">
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Simpan</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection

@section('scripts')
<!-- DataTables & SweetAlert -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(document).ready(function () {
    $('#menuTable').DataTable();

    // CREATE / UPDATE
    $('#createMenuForm').submit(function (e) {
        e.preventDefault();
        let formData = new FormData(this);
        let id = $('#menu_id').val();
        let url = id ? `/menu/${id}` : `{{ route('admin.menu.store') }}`;
        let method = id ? 'POST' : 'POST';

        if (id) {
            formData.append('_method', 'PUT');
        }

        console.log([...formData.entries()]);

        $.ajax({
            url: url,
            method: method,
            data: formData,
            contentType: false,
            processData: false,
            success: function () {
                Swal.fire('Sukses', id ? 'Menu berhasil diupdate' : 'Menu berhasil ditambahkan', 'success');
                $('#createModal').modal('hide');
                location.reload();
            },
            error: function (xhr) {
                console.log(xhr.responseText);
                Swal.fire('Gagal', 'Terjadi kesalahan', + xhr.responseText, 'error');
            }
        });
    });

    // Fill data on edit
    $('.editBtn').click(function () {
        $('#menu_id').val($(this).data('id'));
        $('#nama_menu').val($(this).data('nama'));
        $('#deskripsi').val($(this).data('deskripsi'));
        $('#harga').val($(this).data('harga'));
        $('#kategori').val($(this).data('kategori'));
        $('#createModal').modal('show');
    });

    // DELETE
    $('.deleteBtn').click(function () {
        let id = $(this).data('id');
        Swal.fire({
            title: 'Yakin hapus menu ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/menu/${id}`,
                    type: 'POST',
                    data: {
                        _method: 'DELETE',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function () {
                        Swal.fire('Terhapus!', 'Menu berhasil dihapus.', 'success');
                        $('#menuRow' + id).remove();
                    },
                    error: function () {
                        Swal.fire('Gagal', 'Tidak bisa menghapus data', 'error');
                    }
                });
            }
        });
    });
});
</script>
@endsection
