@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2>Daftar Menu</h2>
    <a href="{{ route('menu.create') }}" class="btn btn-success mb-3">+ Tambah Menu</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
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
                    @else
                        -
                    @endif
                </td>
                <td>
                    <button class="btn btn-warning btn-sm editBtn" data-id="{{ $menu->id }}">Edit</button>
                    <button class="btn btn-danger btn-sm deleteBtn" data-id="{{ $menu->id }}">Hapus</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
