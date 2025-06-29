<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;
use App\Models\Menu;
use App\Models\Order;

class PaymentController extends Controller
{
    public function createSnapToken(Request $request)
    {
        // Konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        // Ambil data dari request
        $payload = $request->all();

        $orderId = 'ORDER-' . uniqid();
        $items = [];

        // Loop item cart
        foreach ($payload['menu'] as $item) {
            
             $menu = Menu::find($item['menu_id']);
             $menuName = $menu ? $menu->nama_menu : 'Menu tidak ditemukan';

            $items[] = [
                'id'       => $item['menu_id'],
                'price'    => $item['basePrice'],
                'quantity' => $item['quantity'],
                'name'     => $menuName
            ];

            if (!empty($item['addons'])) {
                foreach ($item['addons'] as $addon) {
                    $items[] = [
                        'id'       => 'addon-' . ($addon['id'] ?? uniqid()),
                        'price'    => $addon['price'],
                        'quantity' => 1,
                        'name'     => 'Addon ' . $addon['name']
                    ];
                }
            }
        }

        $totalHarga = array_reduce($items, function ($total, $item) {
            return $total + ($item['price'] * $item['quantity']);
        }, 0);
        
        $params = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => $totalHarga
            ],
            'customer_details' => [
                'first_name' => $payload['nama_pelanggan'],
                'email'      => auth()->check() ? auth()->user()->email : 'guest@warungajus.com',
            ],
            'item_details' => $items
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            return response()->json(['snap_token' => $snapToken]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function handleNotification(Request $request)
    {
        $orderId = $request->order_id;
        $status = $request->transaction_status;

        $order = Order::where('id', $orderId)->first();

        if (!$order) return;

        switch ($status) {
            case 'settlement':
            case 'capture':
                $order->payment_status = 'paid';
                break;
            case 'expire':
                $order->payment_status = 'expired';
                break;
            case 'cancel':
            case 'deny':
                $order->payment_status = 'failed';
                break;
            default:
                $order->payment_status = 'pending';
        }

        $order->save();
    }

}
