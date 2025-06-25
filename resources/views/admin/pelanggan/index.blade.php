@extends('layouts.admin')

@section('content')
<div class="container mt-4">
   <h2 class="mb-4">Daftar Semua Pelanggan</h2>


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
