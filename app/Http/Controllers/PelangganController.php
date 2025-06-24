<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;

class PelangganController extends Controller
{
 public function index()
{
    // Ambil semua user dengan role 'pelanggan', baik yang sudah login maupun belum
    $pelanggan = User::where('role', 'pelanggan')->get();

    return view('admin.pelanggan.index', compact('pelanggan'));
}
}

