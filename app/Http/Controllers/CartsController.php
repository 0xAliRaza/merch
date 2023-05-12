<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CartsController extends Controller
{

    public function index(Request $request)
    {
        $carts = $request->user()->carts()->with(['product'])->get(['quantity', 'product_id']);
        return Inertia::render('Cart', compact('carts'));
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'product' => 'required|exists:products,id',
            'quantity' => 'integer|required|min:1|max:3'
        ]);
        // TODO: Limit the allowable quantity of purchase to 3
        $cart = Cart::firstOrNew(['product_id' => $request->input('product'), 'user_id' => $request->user()->id]);
        $cart->quantity = (int) $cart->quantity + $request->input('quantity');
        $cart->save();
        return redirect()->back();
    }
}
