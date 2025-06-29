<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Menu;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;


class OrderController extends Controller
{
    public function create()
    {
        $menus = Menu::with('addons')->get();
        return view('orders.create', compact('menus'));    
    }

        public function index(Request $request)
    {
        $orders = Order::with('items.menu')
            ->has('items')
            ->orderBy('id')
            ->get();
        return view('admin.pesanan-pelanggan.index', compact('orders'));
    }

    public function updateStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $order = Order::findOrFail($id);
        $order->status = $request->status;
        $order->save();

        return back()->with('success', 'Status pesanan berhasil diperbarui.');
    }

    public function getDatatables(Request $request)
    {
        $statusPembayaran = $request->status_pembayaran;
        $statusPesanan = $request->status_pesanan;
        $tanggal = $request->tanggal;

        $orders = Order::with('items.menu')->has('items')->orderBy('id', 'desc');

        if ($statusPembayaran) {
            $orders->where('payment_status', $statusPembayaran);
        }

        if ($statusPesanan) {
            $orders->where('status', $statusPesanan);
        }

        if ($tanggal) {
            $orders->whereDate('created_at', $tanggal);
        }

        if ($request->ajax()) {
            return DataTables::of($orders)
                ->filter(function ($query) use ($request) {
                    if ($search = $request->input('search.value')) {
                        $query->where(function ($q) use ($search) {
                            $q->where('nama_pelanggan', 'like', "%{$search}%")
                            ->orWhereHas('items.menu', function ($menuQuery) use ($search) {
                                $menuQuery->where('nama_menu', 'like', "%{$search}%");
                            });
                        });
                    }
                })
                ->addColumn('nama_pelanggan', fn($order) => $order->nama_pelanggan ?? '-')
                ->addColumn('nama_menu', fn($order) => $order->items->count() > 0
                    ? implode(', ', $order->items->map(fn($i) => $i->menu->nama_menu . ' (' . $i->jumlah . ')')->toArray())
                    : '-')
                ->addColumn('jumlah_total', fn($order) => $order->items->sum('jumlah'))
                ->addColumn('catatan', fn($order) =>
                    $order->items->map(fn($i) =>
                        '- ' . ($i->menu->nama_menu ?? '-') . ': ' . ($i->catatan ?? '-')
                    )->implode('<br>')
                )
                ->addColumn('total_harga', fn($order) => 'Rp. ' . number_format(
                    $order->items->sum(fn($i) => ($i->harga + collect(json_decode($i->addons))->sum('price')) * $i->jumlah),
                    0, ',', '.'
                ))
                ->addColumn('status_pembayaran', function ($order) {
                    $label = [
                        'pending' => 'Belum Dibayar',
                        'paid' => 'Sudah Dibayar',
                        'failed' => 'Gagal',
                        'expired' => 'Kedaluwarsa',
                    ];
                    $color = [
                        'pending' => 'warning',
                        'paid' => 'success',
                        'failed' => 'danger',
                        'expired' => 'secondary',
                    ];
                    return '<span class="badge badge-' . ($color[$order->payment_status] ?? 'light') . '">' . ($label[$order->payment_status] ?? $order->payment_status) . '</span>';
                })
                ->addColumn('status_pesanan', function ($order) {
                    $statusLabels = [
                        'pending'    => ['label' => 'Menunggu', 'color' => 'warning'],
                        'processing' => ['label' => 'Sedang Diproses', 'color' => 'primary'],
                        'completed'  => ['label' => 'Selesai', 'color' => 'success'],
                        'cancelled'  => ['label' => 'Dibatalkan', 'color' => 'danger'],
                    ];
                    
                    $html = '<form action="' . route('admin.orders.updateStatus', $order->id) . '" method="POST">'
                        . csrf_field() . method_field('PATCH')
                        . '<select name="status" class="form-select form-select-sm text-white bg-' . ($statusLabels[$order->status]['color'] ?? 'secondary') . '" onchange="this.form.submit()">';
                        
                    foreach ($statusLabels as $value => $data) {
                        $selected = $order->status === $value ? 'selected' : '';
                        $html .= '<option class="bg-' . $data['color'] . '" value="' . $value . '" ' . $selected . '>' . $data['label'] . '</option>';

                    }
                    $html .= '</select></form>';
                    return $html;
                })
                ->addColumn('aksi', function ($order) {
                    $html = '<div class="d-flex flex-wrap gap-2">';

                    // if ($order->payment_status === 'paid') {
                    //     $html .= ' <a href="' . route('admin.orders.print', $order->id) . '" target="_blank" class="btn btn-success btn-sm">Cetak Struk</a>';
                    // }
                    
                    $html .= '<a href="' . route('admin.orders.print', $order->id) . '" target="_blank" class="btn btn-secondary btn-sm">'
                        . '<i class="fas fa-print"></i> Cetak Struk</a>';

                    $html .= '<form action="' . route('admin.orders.destroy', $order->id) . '" method="POST" class="d-inline">'
                        . csrf_field() . method_field('DELETE')
                        . '<button type="submit" class="btn btn-danger btn-sm">Hapus</button>'
                        . '</form>';
      
                    $html .= '</div>';
                                    
                    return $html;
                })
                ->rawColumns(['catatan', 'status_pembayaran', 'status_pesanan', 'aksi'])
                ->make(true);
        }

        return response()->json(['error' => 'Not Ajax'], 400);
    }

    public function print(Order $order)
    {
        $order->load('items.menu');
        return view('admin.pesanan-pelanggan.print', compact('order'));
    }

    public function exportPdf()
    {
        $orders = Order::with('items.menu')->has('items')->orderBy('created_at', 'desc')->get();

        $pdf = PDF::loadView('admin.pesanan-pelanggan.laporan', compact('orders'));
        return $pdf->download('laporan-penjualan.pdf');
    }


}
