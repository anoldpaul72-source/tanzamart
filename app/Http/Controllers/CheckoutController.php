<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('lang')) {
            $lang = in_array($request->lang, ['sw', 'en']) ? $request->lang : 'en';
            session()->put('locale', $lang);
            app()->setLocale($lang);
        }

        // Check if there is a recently completed order in session
        if (session()->has('order_completed_data')) {
            $data = session()->get('order_completed_data');
            return view('shop.checkout', [
                'success' => true,
                'order_id' => $data['order_id'],
                'wa_url' => $data['wa_url'],
                'sms_url' => $data['sms_url'],
                'cart' => [],
                'total_amount' => 0,
                'error' => null,
            ]);
        }

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index');
        }

        $total_amount = 0;
        foreach ($cart as $item) {
            $total_amount += $item['price'] * $item['quantity'];
        }

        $user = Auth::user();

        return view('shop.checkout', [
            'success' => false,
            'cart' => $cart,
            'total_amount' => $total_amount,
            'user' => $user,
            'error' => null,
        ]);
    }

    public function process(Request $request)
    {
        if ($request->has('lang')) {
            $lang = in_array($request->lang, ['sw', 'en']) ? $request->lang : 'en';
            session()->put('locale', $lang);
            app()->setLocale($lang);
        }

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index');
        }

        $total_amount = 0;
        foreach ($cart as $item) {
            $total_amount += $item['price'] * $item['quantity'];
        }

        $name = trim($request->input('name', ''));
        $phone = trim($request->input('phone', ''));
        $address = trim($request->input('address', ''));
        $city = trim($request->input('city', ''));
        $transaction_id = trim($request->input('transaction_id', ''));

        if (empty($name) || empty($phone) || empty($address) || empty($city) || empty($transaction_id)) {
            return view('shop.checkout', [
                'success' => false,
                'cart' => $cart,
                'total_amount' => $total_amount,
                'user' => Auth::user(),
                'error' => __('messages.cust_chk_err_empty') ?? 'Please fill in all required fields!',
            ]);
        }

        // 1. Kagua kama Transaction ID imeshatumika
        if (Order::where('transaction_id', $transaction_id)->exists()) {
            return view('shop.checkout', [
                'success' => false,
                'cart' => $cart,
                'total_amount' => $total_amount,
                'user' => Auth::user(),
                'error' => __('messages.cust_chk_err_txn_used') ?? 'This Transaction ID has already been used in another order! Please enter a valid one.',
            ]);
        }

        // 2. Kagua stoku ya kila bidhaa
        foreach ($cart as $cart_key => $item) {
            $product = Product::find($item['id'] ?? null);
            if ($product && $product->stock < $item['quantity']) {
                $errStock = app()->getLocale() === 'sw'
                    ? sprintf('Bidhaa "%s" haina stoku ya kutosha. Zimebaki %d tu!', $product->name, $product->stock)
                    : sprintf('Product "%s" does not have enough stock. Only %d left!', $product->name, $product->stock);

                return view('shop.checkout', [
                    'success' => false,
                    'cart' => $cart,
                    'total_amount' => $total_amount,
                    'user' => Auth::user(),
                    'error' => $errStock,
                ]);
            }
        }

        // 3. Hifadhi Oda
        $orderNumber = 'TZM-' . strtoupper(Str::random(6)) . '-' . date('Ymd');
        $order = Order::create([
            'user_id' => Auth::id(),
            'order_number' => $orderNumber,
            'total' => $total_amount,
            'name' => $name,
            'phone' => $phone,
            'address' => $address,
            'city' => $city,
            'transaction_id' => $transaction_id,
            'payment_method' => 'Mobile / Bank',
            'payment_status' => 'held',
            'status' => 'Pending',
        ]);

        foreach ($cart as $cart_key => $item) {
            $product = Product::find($item['id'] ?? null);
            $vendorId = $item['vendor_id'] ?? ($product ? $product->vendor_id : null);

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['id'] ?? null,
                'vendor_id' => $vendorId,
                'name' => $item['name'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'subtotal' => $item['price'] * $item['quantity'],
            ]);

            if ($product) {
                $product->decrement('stock', $item['quantity']);
                $product->increment('sales', $item['quantity']);
            }
        }

        // 4. Tengeneza ujumbe wa WhatsApp na SMS
        $admin_phone = '255621530804';
        $formatted_total = number_format($total_amount, 2);

        if (app()->getLocale() === 'sw') {
            $message = "Habari Admin, nimeweka oda mpya TanzaMart:\n\n📦 *Oda No:* #{$order->id}\n👤 *Jina:* {$name}\n📞 *Simu:* {$phone}\n📍 *Mahali:* {$city} ({$address})\n💰 *Jumla:* TSh {$formatted_total}\n💳 *Transaction ID:* {$transaction_id}\n\nTafadhali hakiki malipo yangu.";
        } else {
            $message = "Hello Admin, I have placed a new order on TanzaMart:\n\n📦 *Order No:* #{$order->id}\n👤 *Name:* {$name}\n📞 *Phone:* {$phone}\n📍 *Location:* {$city} ({$address})\n💰 *Total:* TSh {$formatted_total}\n💳 *Transaction ID:* {$transaction_id}\n\nPlease verify my payment.";
        }

        $wa_url = "https://wa.me/{$admin_phone}?text=" . urlencode($message);
        $sms_url = "sms:+{$admin_phone}?body=" . urlencode($message);

        // Futa kikapu
        session()->forget('cart');

        $completedData = [
            'order_id' => $order->id,
            'wa_url' => $wa_url,
            'sms_url' => $sms_url,
        ];
        session()->flash('order_completed_data', $completedData);

        return view('shop.checkout', [
            'success' => true,
            'order_id' => $order->id,
            'wa_url' => $wa_url,
            'sms_url' => $sms_url,
            'cart' => [],
            'total_amount' => $total_amount,
            'error' => null,
        ]);
    }
}
