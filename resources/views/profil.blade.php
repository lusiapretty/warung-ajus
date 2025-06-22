@extends('layouts.app')

@section('title', 'Profil')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/profil.css') }}">
@endpush

@section('content')
<div class="profile-wrapper">
    <div class="profile-card">
        <h2 class="profile-title">Pengaturan Profil</h2>

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('profil.update') }}">
            @csrf

            <div class="form-group">
                <label for="nama">Nama</label>
                <input type="text" name="nama" id="nama" value="{{ old('nama', $user->nama) }}" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
