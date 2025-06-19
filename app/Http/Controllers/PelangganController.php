<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;

class PelangganController extends Controller
{
    public function index()
    {
        // Ambil semua user dengan role pelanggan dan sudah pernah login
        $pelanggan = User::where('role', 'pelanggan')
                        ->whereNotNull('last_login_at')
                        ->get();

        return view('admin.pelanggan.index', compact('pelanggan'));
    }
}
