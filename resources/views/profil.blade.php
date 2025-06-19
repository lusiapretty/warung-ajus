@extends('layouts.app')

@section('title', 'Profil')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/profil.css') }}">
@endpush

@section('content')
  <div class="profile-container">
      <h2>Pengaturan Profil</h2>

      @if(session('success'))
          <div class="alert">{{ session('success') }}</div>
      @endif

      <form method="POST" action="{{ route('profil.update') }}">
          @csrf

          <div class="form-group">
              <label>Nama</label>
              <input type="text" name="nama" value="{{ old('nama', $user->nama) }}" required>
          </div>

          <div class="form-group">
              <label>Email</label>
              <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
          </div>

          <button type="submit" class="btn">Simpan</button>
      </form>
  </div>
@endsection
