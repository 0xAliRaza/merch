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
        $cart = Cart::firstOrNew(['product_id' => $request->input('product'), 'user_id' => $request->user()->id]);
        $newQuantity = (int) $cart->quantity + $request->input('quantity');
        if ($newQuantity > 3) {
            return back()->withErrors([
                'quantity' => "We're sorry, but you can only add 3 of this item to your cart at this time."
            ]);
        }
        $cart->quantity = (int) $cart->quantity + $request->input('quantity');
        $cart->selected = true;
        $cart->save();
        return redirect()->back();
    }

    public function editCartItem(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:carts,product_id',
            'selected' => 'required_without:quantity|boolean',
            'quantity' => 'required_without:selected|integer|min:1|max:3',

        ]);


        $cart = Cart::where(['product_id' => $request->input('product_id'), 'user_id' => $request->user()->id])->firstOrFail();
        if ($request->has('selected')) {
            $cart->selected = $request->input('selected');
        }
        if ($request->has('quantity')) {
            $cart->quantity = $request->input('quantity');
        }
        $cart->save();
        return redirect()->back();
    }
}
