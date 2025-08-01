<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Menu;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OrderExport;


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

    public function pesananSaya()
    {
        $orders = Order::where('user_id', auth()->id())
                    ->with('items.menu')
                    ->latest()
                    ->get();

        return view('pelanggan.pesanan', compact('orders'));
    }

    public function updatePaymentStatus(Request $request, Order $order)
    {
        $request->validate([
            'payment_status' => 'required|in:pending,paid',
        ]);

        // Hanya bisa mengubah status pembayaran jika tipe pembayaran adalah cash
        if ($order->pembayaran !== 'cash') {
            return back()->with('error', 'Status pembayaran hanya bisa diubah untuk metode pembayaran cash.');
        }

        $order->payment_status = $request->payment_status;
        $order->save();

        return back()->with('success', 'Status pembayaran berhasil diperbarui.');
    }

    public function updateStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,processing,ready,completed,cancelled',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $order = Order::findOrFail($id);

        // Jika status diubah menjadi 'cancelled', kosongkan nomor meja
        if ($request->status === 'cancelled') {
            $order->no_meja = null;
        }
        
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
                ->addColumn('order_id', fn($order) => $order->order_id)
                ->addColumn('tanggal_order', fn($order) => $order->created_at->format('d-m-Y H:i:s'))
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
                ->addColumn('tipe_pesanan', fn($order) => $order->tipe_pesanan)
                ->addColumn('no_meja', function ($order) {
                    // Untuk dine in, tampilkan nomor meja
                    if ($order->tipe_pesanan === 'dine_in') {
                        if ($order->no_meja) {
                            $tableDisplay = '<div class="text-center"><strong>Meja ' . $order->no_meja . '</strong>';
                            
                            // Tambahkan indikator jika meja sedang terpakai
                            if (
                                $order->payment_status === 'paid' &&
                                in_array($order->status, ['pending', 'processing', 'ready'])
                            ) {
                                $tableDisplay .= '<br><p class="text-danger mb-0" style="font-size: 12px;"><i class="fas fa-circle" style="font-size: 9px;"></i> Terpakai</p>';
                            }
                            
                            $tableDisplay .= '</div>';
                            return $tableDisplay;
                        } else {
                            return '<div class="text-center"><span class="text-muted">-</span></div>';
                        }
                    } else {
                        // Untuk takeaway atau delivery
                        return '<div class="text-center"><span class="text-muted">-</span></div>';
                    }
                })
                ->addColumn('payment_status', function ($order) {
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

                    if ($order->pembayaran === 'cash' && $order->payment_status !== 'paid') {
                        return '
                            <div class="text-center">
                                <form method="POST" action="' . route('admin.orders.updatePaymentStatus', $order->id) . '">
                                    ' . csrf_field() . method_field('PATCH') . '
                                    <select name="payment_status" class="form-select form-select-sm fw-bold mb-1" onChange="this.form.submit()">
                                        <option value="pending" ' . ($order->payment_status === 'pending' ? 'selected' : '') . '>Belum Dibayar</option>
                                        <option value="paid" ' . ($order->payment_status === 'paid' ? 'selected' : '') . '>Sudah Dibayar</option>
                                    </select>
                                </form>
                            </div>
                        ';
                    }
                return '<div class="text-center">
                    <span class="badge bg-' . ($color[$order->payment_status] ?? 'dark') . ' rounded-pill px-3 py-1 fw-semibold">
                        ' . ($label[$order->payment_status] ?? ucfirst($order->payment_status)) . '
                    </span>
                </div>';
                })
                ->addColumn('status_pesanan', function ($order) {
                    $statusLabels = [
                        'pending'    => ['label' => 'Menunggu', 'color' => 'warning'],
                        'processing' => ['label' => 'Sedang Diproses', 'color' => 'primary'],
                        'ready'      => ['label' => 'Pesanan Siap', 'color' => 'info'],
                        'completed'  => ['label' => 'Selesai', 'color' => 'success'],
                        'cancelled'  => ['label' => 'Dibatalkan', 'color' => 'danger'],
                    ];

                    $labelText = $statusLabels[$order->status]['label'] ?? $order->status;
                    $labelColor = $statusLabels[$order->status]['color'] ?? 'secondary';

                    $html = '<div class="mb-1"><span class="fw-bold text-' . $labelColor . '">' . $labelText . '</span></div>';
                    
                    $html = '<form action="' . route('admin.orders.updateStatus', $order->id) . '" method="POST" class="status-form">'
                        . csrf_field() . method_field('PATCH')
                        . '<select name="status" class="form-select form-select-sm fw-semibold border-' . $labelColor . ' text-' . $labelColor . '" style="max-width: 150px;" data-initial="' . $order->status . '" onchange="updateSelectColor(this); this.form.submit();">';
                        
                    foreach ($statusLabels as $value => $data) {
                        $selected = $order->status === $value ? 'selected' : '';
                        $html .= '<option class="text-' . $data['color'] . '" value="' . $value . '" ' . $selected . '>' . $data['label'] . '</option>';

                    }
                    $html .= '</select></form>';
                    return $html;
                })
                ->addColumn('status_pesanan_export', function ($order) {
                    $labels = [
                        'pending'    => 'Menunggu',
                        'processing' => 'Sedang Diproses',
                        'ready'      => 'Pesanan Siap',
                        'completed'  => 'Selesai',
                        'cancelled'  => 'Dibatalkan',
                    ];
                    return $labels[$order->status] ?? $order->status;
                })

                ->addColumn('aksi', function ($order) {
                    $html = '<div class="d-flex flex-wrap gap-2">';

                    if ($order->payment_status === 'paid') {                   
                        $html .= '<a href="' . route('admin.orders.print', $order->id) . '" target="_blank" class="btn btn-success btn-sm">'
                            . '<i class="fas fa-print"></i> Cetak Struk</a>';

                    // $html .= '<form action="' . route('admin.orders.destroy', $order->id) . '" method="POST" class="d-inline">'
                    //     . csrf_field() . method_field('DELETE')
                    //     . '<button type="submit" class="btn btn-danger btn-sm">Hapus</button>'
                    //     . '</form>';
                    }
                    $html .= '</div>';
                                    
                    return $html;
                })
                ->rawColumns(['catatan', 'status_pembayaran', 'status_pesanan', 'aksi', 'payment_status', 'no_meja'])
                ->make(true);
        }

        return response()->json(['error' => 'Not Ajax'], 400);
    }

    public function getMejaTerpakai()
    {
        $mejaTerpakai = Order::where('tipe_pesanan', 'dine_in')
            ->whereIn('status', ['pending', 'processing', 'ready']) // hanya pesanan aktif
            ->whereNotNull('no_meja') // yang sudah diberi nomor meja
            ->pluck('no_meja')
            ->map(fn($meja) => (int)$meja)
            ->unique()
            ->values();

        return response()->json(['meja_terpakai' => $mejaTerpakai]);
    }

    public function updateStatusMeja($id)
    {
        $order = Order::findOrFail($id);

        // Misalnya ada kolom 'status_meja' di tabel orders
        $order->status_meja = 'tersedia';
        $order->save();

        return response()->json(['message' => 'Status meja berhasil diubah menjadi tersedia']);
    }

    public function updateNoMeja(Request $request, Order $order)
    {
        $request->validate([
            'no_meja' => 'nullable|integer|min:1'
        ]);

        if ($request->no_meja) {
            $mejaTerpakai = Order::where('no_meja', $request->no_meja)
                ->where('id', '!=', $order->id)
                ->where('tipe_pesanan', 'dine_in')
                ->where('payment_status', 'paid')
                ->whereIn('status', ['pending', 'processing', 'ready', 'completed'])
                ->whereNotNull('no_meja') 
                ->exists();

            if ($mejaTerpakai) {
                return back()->with('error', 'Nomor meja sedang digunakan oleh pesanan aktif lain.');
            }
        }

        $order->no_meja = $request->no_meja;
        $order->save();

        return back()->with('success', 'Nomor meja berhasil diperbarui.');
    }

    public function print(Order $order)
    {
        $order->load('items.menu');
        return view('admin.pesanan-pelanggan.print', compact('order'));
    }

    public function exportPdf(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $start = Carbon::parse($request->start_date)->startOfDay();
        $end   = Carbon::parse($request->end_date)->endOfDay();

        $orders = Order::with(['items.menu'])
            ->whereBetween('created_at', [$start, $end])
            ->where('payment_status', 'paid')
            ->where('status', 'completed')
            ->get();

        $pdf = Pdf::loadView('admin.pesanan-pelanggan.laporan-pdf', compact('orders', 'start', 'end'));

        return $pdf->download('laporan-penjualan-' . now()->format('Ymd') . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $start = Carbon::parse($request->start_date)->startOfDay();
        $end = Carbon::parse($request->end_date)->endOfDay();

         $orders = Order::with('items.menu')
            ->whereBetween('created_at', [$start, $end])
            ->where('payment_status', 'paid')
            ->where('status', 'completed')
            ->get();

        return Excel::download(new OrderExport($orders, $start, $end), 'laporan-penjualan-' . now()->format('Ymd') . '.xlsx');
    }
}
