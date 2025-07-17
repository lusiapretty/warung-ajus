<?php

namespace App\Http\Controllers;

// use Dotenv\Validator;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Menu;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CheckoutController extends Controller
{
    public function checkout(Request $request)
    {
        // ✅ Cek apakah user sudah login
        if (!auth()->check()) {
            return response()->json([
                'message' => 'Silakan login terlebih dahulu untuk melakukan pemesanan.'
            ], 401);
        }

        // ✅ Cek apakah user adalah pelanggan
        if (auth()->user()->role !== 'pelanggan') {
            return response()->json([
                'message' => 'Hanya pengguna yang sudah login yang dapat melakukan pemesanan.'
            ], 403);
        }

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

        $orderId = 'ORDER-' . strtoupper(Str::random(6));

        // Simpan Order
        $order = Order::create([
            'order_id'       => $orderId,
            'nama_pelanggan' => $data['nama_pelanggan'],
            'no_meja'        => $data['tipe_pesanan'] === 'dine_in' ? $data['no_meja'] : null,
            'tipe_pesanan'   => $data['tipe_pesanan'],
            'pembayaran'     => $data['pembayaran'] ?? 'midtrans',
            'status'         => 'pending',
            'user_id'        => auth()->id(), 
            'status_meja'   => 'terpakai', 
        ]);

        $totalHarga = 0;

        // Simpan OrderItem
        foreach ($data['menu'] as $item) {
            $menu = Menu::find($item['menu_id']);

            $hargaMenu = $item['basePrice'];
            $jumlah = $item['quantity'];
            $addonTotal = collect($item['addons'] ?? [])->sum('price');
            $totalItem = ($hargaMenu + $addonTotal) * $jumlah;

            $totalHarga += $totalItem;

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
                'jumlah'  => $jumlah,
                'harga'   => $hargaMenu, 
                'addons'  => json_encode($item['addons'] ?? []),
                'catatan' => $item['catatan'] ?? null,

            ]);
        }

        $order->update(['total' => $totalHarga]);

        return response()->json([
            'message' => 'Pesanan berhasil disimpan!', 
            'order_id' => $orderId], 201);
    }

    public function storeFromMidtrans(Request $request)
    {
        try {
            $data = $request->all();

            Log::info('DATA MASUK DARI MIDTRANS:', $data);

            $order = Order::create([
                'order_id'        => $data['order_id'] ?? 'ORDER-' . Str::uuid(),
                'user_id'         => auth()->check() ? auth()->id() : null,
                'nama_pelanggan'  => $data['nama_pelanggan'],
                'tipe_pesanan'    => $data['tipe_pesanan'],
                'no_meja'         => $data['tipe_pesanan'] === 'dine_in' ? $data['no_meja'] : null,
                'pembayaran'      => 'midtrans',
                'status'          => 'processing', 
                'payment_status'  => 'paid',
                'total'           => $data['total'],
                'status_meja'     => 'terpakai',
            ]);

            foreach ($data['menu'] as $item) {
                $menu = Menu::find($item['menu_id']);

                OrderItem::create([
                    'order_id'  => $order->id,
                    'menu_id'   => $item['menu_id'],
                    'nama_menu' => $menu ? $menu->nama_menu : 'Tidak diketahui',
                    'jumlah'    => $item['quantity'],
                    'harga'     => $item['basePrice'],
                    'catatan'   => $item['note'] ?? null,
                    'addons'    => json_encode($item['addons'] ?? []),
                ]);

            }

            return response()->json(['message' => 'Order berhasil disimpan']);
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan order dari Midtrans: ' . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan saat menyimpan order.'], 500);
        }
    }

}
