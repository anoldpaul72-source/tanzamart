<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function products(Request $request)
    {
        $categories = Category::all();
        $query = Product::with('category', 'vendor')->where('status', 'active');

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('details', 'like', "%{$search}%");
            });
        }

        if ($request->get('sort') === 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($request->get('sort') === 'price_desc') {
            $query->orderBy('price', 'desc');
        } else {
            $query->latest();
        }

        $products = $query->paginate(12)->withQueryString();

        return view('shop.products', compact('products', 'categories'));
    }

    public function show(Request $request, $id)
    {
        if ($request->has('lang')) {
            $lang = in_array($request->lang, ['sw', 'en']) ? $request->lang : 'en';
            session()->put('locale', $lang);
            app()->setLocale($lang);
        }

        $product = Product::with('category', 'vendor')->findOrFail($id);
        $product->increment('views');

        return view('shop.product_details', compact('product'));
    }
}
