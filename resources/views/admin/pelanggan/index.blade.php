@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2>Daftar Pelanggan yang Pernah Login</h2>

    <table id="tabelPelanggan" class="table table-bordered table-striped">
        <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Terakhir Login</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pelanggan as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->nama }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->last_login_at }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">Belum ada pelanggan yang login.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#tabelPelanggan').DataTable();
    });
</script>
@endpush
