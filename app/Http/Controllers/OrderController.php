<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Menu;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function create()
    {
        $menus = Menu::with('addons')->get();
        return view('orders.create', compact('menus'));    
    }

    public function store(Request $request)
    {
        $order = Order::create([
            'nama_pelanggan' => $request->nama_pelanggan,
            'no_meja' => $request->no_meja,
            'status' => 'pending'
        ]);

        foreach ($request->menu as $menuData) {
            $orderItem = OrderItem::create([
                'order_id' => $order->id,
                'menu_id'  => $menuData['id'],
                'jumlah'   => $menuData['jumlah'],
                'harga'    => $menuData['harga'],
            ]);

            if (isset($menuData['addons'])) {
                $orderItem->addons()->sync($menuData['addons']);
            }
        }

        return redirect()->route('order.show', $order->id)->with('success', 'Order Placed successfully');
    }

    public function show(Order $order)
    {
        $order->load('items.menu', 'items.addons');
        return view('orders.show', compact('order'));
    }
}
