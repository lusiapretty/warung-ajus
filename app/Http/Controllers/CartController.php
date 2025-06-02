<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::with('menu')
            ->where('user_id', Auth::id())
            ->get();

        return view('cart.index', compact('cartItems'));
    }

    public function addToCart(Request $request, $menuId)
    {
        $cart = Cart::where('user_id', Auth::id())
            ->where('menu_id', $menuId)
            ->first();

        if ($cart) {
            $cart->jumlah += 1;
            $cart->save();
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'menu_id' => $menuId,
                'jumlah' => 1,
            ]);
        }

        return response()->json([
            'message' => 'Produk ditambahkan ke keranjang',
        ]);

    }

    public function count()
    {
        $cartCount = Cart::where('user_id', Auth::id())->sum('jumlah');

        return response()->json([
            'count' => $cartCount,
        ]);
    }

    public function removeFromCart($id)
    {
        Cart::where('user_id', Auth::id())
            ->where('id', $id)
            ->delete();

        return redirect()->back()->with('success', 'Menu berhasil dihapus dari keranjang');    
    }
    
}

