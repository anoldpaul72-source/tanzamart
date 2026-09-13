<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function products(Request $request)
    {
        $categories = Category::withCount('products')->get();
        $query = Product::with('category', 'vendor')->where('status', 'active');
        $activeCategory = null;

        if ($request->filled('category')) {
            $catParam = $request->category;
            if (is_numeric($catParam)) {
                $activeCategory = Category::find($catParam);
                if ($activeCategory) {
                    $query->where('category_id', $activeCategory->id);
                }
            } else {
                $activeCategory = Category::where('slug', $catParam)->first();
                if ($activeCategory) {
                    $query->where('category_id', $activeCategory->id);
                }
            }
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
        } elseif ($request->get('sort') === 'popular') {
            $query->orderBy('views', 'desc');
        } else {
            $query->latest();
        }

        $products = $query->paginate(12)->withQueryString();

        return view('shop.products', compact('products', 'categories', 'activeCategory'));
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
