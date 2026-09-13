<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('lang')) {
            $lang = in_array($request->lang, ['sw', 'en']) ? $request->lang : 'en';
            session()->put('locale', $lang);
            app()->setLocale($lang);
        }

        if ($request->has('remove')) {
            $removeKey = $request->get('remove');
            $cart = session()->get('cart', []);
            unset($cart[$removeKey]);
            session()->put('cart', $cart);
            return redirect()->route('cart.index');
        }

        $cart = session()->get('cart', []);
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view('shop.cart', compact('cart', 'total'));
    }

    public function add(Request $request)
    {
        $productId = $request->input('product_id');
        $quantity = (int) $request->input('quantity', 1);
        if ($quantity < 1) $quantity = 1;

        $product = Product::findOrFail($productId);
        $selectedImage = $request->input('selected_image', $product->image_url);
        
        $cart = session()->get('cart', []);
        $cartKey = $productId . '_' . md5($selectedImage);

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $quantity;
        } else {
            $cart[$cartKey] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => (float) $product->price,
                'image' => $selectedImage,
                'quantity' => $quantity,
                'vendor_id' => $product->vendor_id,
            ];
        }

        session()->put('cart', $cart);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Bidhaa imeongezwa kwenye kikapu!',
                'cartCount' => count($cart),
            ]);
        }

        return redirect()->route('cart.index');
    }

    public function update(Request $request)
    {
        $cartKey = $request->input('cart_key');
        $quantity = (int) $request->input('quantity', 1);

        $cart = session()->get('cart', []);
        if (isset($cart[$cartKey])) {
            if ($quantity <= 0) {
                unset($cart[$cartKey]);
            } else {
                $cart[$cartKey]['quantity'] = $quantity;
            }
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index');
    }

    public function remove($key)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$key])) {
            unset($cart[$key]);
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index');
    }

    public function clear()
    {
        session()->forget('cart');
        return redirect()->route('cart.index');
    }
}
