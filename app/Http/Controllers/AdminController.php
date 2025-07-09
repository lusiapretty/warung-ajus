<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalPengguna = User::count();
        $totalPesanan = Order::count();

        $now = Carbon::now();
        $startOfThisMonth = $now->copy()->startOfMonth();
        $startOfLastMonth = $now->copy()->subMonth()->startOfMonth();
        $endOfLastMonth = $now->copy()->subMonth()->endOfMonth();

        $penggunaBulanIni = User::where('created_at', '>=', $startOfThisMonth)->count();
        $penggunaBulanLalu = User::whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])->count();

        $pesananBulanIni = Order::where('created_at', '>=', $startOfThisMonth)->count();
        $pesananBulanLalu = Order::whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])->count();

        $persenPengguna = $penggunaBulanLalu > 0
            ? (($penggunaBulanIni - $penggunaBulanLalu) / $penggunaBulanLalu) * 100
            : ($pesananBulanIni > 0 ? 100 : 0);

        $persenPesanan = $pesananBulanLalu > 0
            ? (($pesananBulanIni - $pesananBulanLalu) / $pesananBulanLalu) * 100
            : ($pesananBulanIni > 0 ? 100 : 0);

        $pesananTerbaru = Order::with('user', 'items.menu')
            ->latest('created_at')
            ->take(5)
            ->get();

        $riwayatPembayaran = Order::with('user', 'items.menu')
            ->where('payment_status', 'paid')
            ->latest('created_at')
            ->take(3)
            ->get();

        return view('dashboard', compact(
            'totalPengguna',
            'totalPesanan',
            'penggunaBulanIni',
            'pesananBulanIni',
            'persenPengguna',
            'persenPesanan',
            'pesananTerbaru',
            'riwayatPembayaran'
        ));
    }

    public function show(Order $order)
    {
        $order->load('user', 'items.menu');

        return view('admin.pesanan-pelanggan.show', compact('order'));
    }
}