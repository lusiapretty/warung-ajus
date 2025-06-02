<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function checkout(Request $request)
    {
        $data = $request->validate([
            'nama'    => 'required|string',
            'no_meja' => 'required|integer',
            'cart'    => 'required|array',
            'cart.*.menu_id' => 'required|string',
            'cart.*.jumlah'  => 'required|integer|min:1',
            'cart.*.addons'  => 'nullable|array',
            'cart.*.catatan'    => 'nullable|string'
        ]);

        $order = \App\Models\Order::create([
            'nama_pelanggan' => $data['nama'],
            'no_meja'        => $data['no_meja'],
        ]);

        foreach ($data['cart'] as $item) {
            $order->items()->create([
                'menu_id' => $item['menu_id'],
                'jumlah'  => $item['jumlah'],
                'addons'  => json_encode($item['addons'] ?? []),
                'catatan' => $item['catatan'] ?? null,

            ]);
        }

        return response()->json(['message' => 'Pesanan berhasil disimpan!']);
    }
}
