<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->get();
        $featuredProducts = Product::with('category', 'vendor')
            ->where('status', 'active')
            ->latest()
            ->take(8)
            ->get();

        return view('home', compact('categories', 'featuredProducts'));
    }

    public function about()
    {
        $categoriesCount = Category::count();
        $productsCount = Product::where('status', 'active')->count();
        return view('about', compact('categoriesCount', 'productsCount'));
    }

    public function switchLang($lang)
    {
        if (in_array($lang, ['sw', 'en'])) {
            Session::put('locale', $lang);
        }
        return redirect()->back(fallback: route('home'));
    }
}
