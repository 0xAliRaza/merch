<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CartsController extends Controller
{

    public function index(Request $request)
    {
        $carts = $request->user()->carts()->with(['product'])->get(['quantity', 'product_id', 'selected']);
        $total = $carts->filter(function ($cart) {
            return $cart->selected;
        })->sum(function ($cart) {
            return $cart->quantity * $cart->product->discounted_price;
        });
        return Inertia::render('Cart', ['carts' => $carts, 'total' => round($total, 2)]);
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
        $cart->selected = true;
        $cart->save();
        return redirect()->back();
    }

    public function editCartItem(Request $request)
    {
        $request->validate([
            'product' => 'required|exists:carts,product_id',
            'selected' => 'boolean|required'
        ]);

        $cart = Cart::where(['product_id' => $request->input('product'), 'user_id' => $request->user()->id])->firstOrFail();
        $cart->selected = $request->input('selected');
        $cart->save();
        return redirect()->back();
    }
}
