<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    function index(Request $request)
    {
        $products = Product::latest()->with(['defaultImage'])->get();

        return Inertia::render('Home', compact('products'));
    }

    function renderProduct(Product $product) {
        $product->defaultImage;
        // dd($product->toArray());
        return Inertia::render('Product', compact('product'));
    }
}
