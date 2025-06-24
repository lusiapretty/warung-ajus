<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Menu;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

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


    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'nama' => 'required|string|max:255',
    //         'no_meja' => 'required_if:tipe_pesanan,dine_in',
    //         'cart' => 'required|array|min:1'
    //     ]);

    //     $order = Order::create([
    //         'nama_pelanggan' => $request->nama,
    //         'no_meja' => $request->no_meja,
    //         'status' => 'pending',
    //     ]);

    //     foreach ($request->cart as $item) {
    //         $menu = Menu::find($item['menu_id']);

    //         $orderItem = OrderItem::create([
    //             'order_id' => $order->id,
    //             'nama_menu' => $menu ? $menu->nama_menu : $item['menu'],
    //             'menu_id'  => $item['menu_id'],
    //             'jumlah'   => $item['quantity'],
    //             'harga'    => ($item['basePrice'] ?? 0) + collect($item['addons'])->sum('price'),
    //             'catatan'  => $item['note'] ?? null,
    //             'addons'   => json_encode($item['addons']),
    //         ]);
    //     }

    //     return response()->json([
    //         'message' => 'Pesanan berhasil disimpan',
    //         'order_id' => $order->id
    //     ], 201);
    // }

    public function show(Order $order)
    {
        $order->load('items.menu', 'items.addons');
        return view('orders.show', compact('order'));
    }
}
