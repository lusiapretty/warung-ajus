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

    #tabelPelanggan {
        font-size: 0.85rem; /* Ukuran font isi tabel */
    }

    #tabelPelanggan thead th {
        font-size: 0.9rem;  /* Ukuran font header kolom (opsional) */
        font-weight: bold;
    }

    #tabelPelanggan tbody td {
        font-size: 0.85rem;  /* Ukuran font isi baris */
    }
</style>
@extends('layouts.admin')

@section('content')
<div class="container mt-4">
   <h3 class="mb-4">Daftar Semua Pelanggan</h3>


    <div class="table-responsive">
        <table id="tabelPelanggan" class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Terakhir Login</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pelanggan as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->nama }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            {{ $user->last_login_at ? \Carbon\Carbon::parse($user->last_login_at)->translatedFormat('d M Y H:i') : '-' }}
                        </td>
                        <td>
                            @if ($user->last_login_at)
                                <span class="badge bg-success">Sudah Login</span>
                            @else
                                <span class="badge bg-warning text-dark">Baru Daftar</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Belum ada pelanggan yang terdaftar.</td>

                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- DataTables & jQuery CDN (Langsung include di sini) -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function () {
        $('#tabelPelanggan').DataTable({
            responsive: true,
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ entri",
                zeroRecords: "Tidak ditemukan data",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                infoEmpty: "Tidak ada data tersedia",
                paginate: {
                    first: "Awal",
                    last: "Akhir",
                    next: "Berikutnya",
                    previous: "Sebelumnya"
                },
            }
        });
    });
</script>
@endsection
