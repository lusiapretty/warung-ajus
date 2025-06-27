<?php

namespace App\Http\Controllers;

// use Dotenv\Validator;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Menu;
use Illuminate\Support\Facades\Validator;

class CheckoutController extends Controller
{
    public function checkout(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'nama_pelanggan'    => 'required|string',
            'tipe_pesanan'      => 'required|in:dine_in,take_away',
            'no_meja'           => 'required_if:tipe_pesanan,dine_in',
            'pembayaran'        => 'required|string',
            'menu'              => 'required|array|min:1',
            'menu.*.menu_id'    => 'required|integer|exists:menus,id',
            'menu.*.basePrice'  => 'required|numeric',
            'menu.*.quantity'   => 'required|integer|min:1',
            'menu.*.addons'     => 'nullable|array',
            'menu.*.catatan'    => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Data tidak valid',
                'errors'  => $validator->errors(),
            ], 422);
        }

        // Simpan Order
        $order = Order::create([
            'nama_pelanggan' => $data['nama_pelanggan'],
            'no_meja'        => $data['tipe_pesanan'] === 'dine_in' ? $data['no_meja'] : null,
            'tipe_pesanan'   => $data['tipe_pesanan'],
            'pembayaran'     => $data['pembayaran'],
            'status'         => 'pending',
        ]);

        // Simpan OrderItem
        foreach ($data['menu'] as $item) {
            $menu = Menu::find($item['menu_id']);

            if (!$menu) {
                return response()->json([
                    'message' => 'Menu tidak ditemukan',
                    'errors' => ['menu_id' => 'Menu ID ' . $item['menu_id'] . ' tidak ditemukan']
                ], 422);
            }

            OrderItem::create([
                'order_id' => $order->id,
                'menu_id' => $item['menu_id'],
                'nama_menu' => $menu ? $menu->nama_menu : 'Menu Tidak Ditemukan',
                'jumlah'  => $item['quantity'],
                'harga'   => $item['basePrice'], 
                'addons'  => json_encode($item['addons'] ?? []),
                'catatan' => $item['catatan'] ?? null,

            ]);
        }

        return response()->json([
            'message' => 'Pesanan berhasil disimpan!', 
            'order_id' => $order->id], 201);
    }

    public function storeFromMidtrans(Request $request)
    {
        $data = $request->all();

        $order = Order::create([
            'user_id'        => auth()->check() ? auth()->id() : null,
            'nama_pelanggan' => $data['nama_pelanggan'],
            'tipe_pesanan'   => $data['tipe_pesanan'],
            'no_meja'        => $data['no_meja'],
            'pembayaran'     => $data['pembayaran'],
            'status'         => 'paid',
            'total'          => $data['total'],
        ]);

        foreach ($data['menu'] as $item) {
            OrderItem::create([
                'order_id'  => $order->id,
                'menu_id'   => $item['menu_id'],
                'nama_menu' => Menu::find($item['menu_id'])->nama_menu ?? 'Tidak diketahui',
                'jumlah'    => $item['quantity'],
                'harga'     => $item['basePrice'],
                'catatan'   => $item['catatan'],
                'addons'    => json_encode($item['addons'] ?? []),
            ]);

            if (!empty($item['addons'])) {
                foreach ($item['addons'] as $addon) {
                    $order->addons()->create([
                        'addon_id' => $addon['id'],
                        'order_id' => $order->id,
                        'harga' => $addon['price'],
                    ]);
                }
            }
        }

        return response()->json(['message' => 'Order berhasil disimpan']);
    }

}
