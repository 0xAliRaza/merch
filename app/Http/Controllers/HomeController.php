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
        $products = Product::latest()->get();

        return Inertia::render('Home', compact('products'));
    }

    function renderProduct(Product $product)
    {
        $product->images;
        return Inertia::render('Product', compact('product'));
    }
}
