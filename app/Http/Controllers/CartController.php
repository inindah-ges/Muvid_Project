<?php

namespace App\Http\Controllers;

use App\Models\Backend\Product;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        // Ambil semua item keranjang untuk pengguna yang sedang login
        $cartItems = Cart::where('user_id', Auth::id())->get();

        return view('cart.index', compact('cartItems'));
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,name',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::where('name', $request->product_id)->first();

        // Cek stok sebelum menambahkan ke cart
        if ($product->stock < $request->quantity) {
            return back()->with('error', 'Stok tidak mencukupi!');
        }

        Cart::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'product_id' => $product->id,
            ],
            [
                'quantity' => $request->quantity,
            ]
        );

        return redirect()->route('cart.index')->with('success', 'Item added to cart!');
    }

    public function remove($id)
    {
        $cartItem = Cart::findOrFail($id);
        $cartItem->delete();

        return redirect()->back()->with('success', 'Item removed from cart!');
    }
}
