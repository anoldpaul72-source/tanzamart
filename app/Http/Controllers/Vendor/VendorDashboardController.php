<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorDashboardController extends Controller
{
    public function index()
    {
        $vendor = Auth::user();
        $totalProducts = Product::where('vendor_id', $vendor->id)->count();

        $orderItems = OrderItem::where('vendor_id', $vendor->id)->get();
        $totalSales = $orderItems->sum('subtotal');
        $totalOrders = $orderItems->pluck('order_id')->unique()->count();

        $recentOrders = Order::whereHas('items', function ($q) use ($vendor) {
            $q->where('vendor_id', $vendor->id);
        })->latest()->take(5)->get();

        $walletBalance = $vendor->balance ?? 0.00;
        $withdrawals = Withdrawal::where('vendor_id', $vendor->id)->latest()->take(5)->get();

        return view('vendor.dashboard', compact('vendor', 'totalProducts', 'totalSales', 'totalOrders', 'walletBalance', 'recentOrders', 'withdrawals'));
    }

    public function orders(Request $request)
    {
        if ($request->has('lang')) {
            $lang = in_array($request->lang, ['sw', 'en']) ? $request->lang : 'en';
            session()->put('locale', $lang);
            app()->setLocale($lang);
        }

        $vendor = Auth::user();
        $orderItems = OrderItem::where('vendor_id', $vendor->id)
            ->with(['order.user', 'product'])
            ->latest()
            ->paginate(15);

        return view('vendor.orders', compact('vendor', 'orderItems'));
    }

    public function updateOrderStatus(Request $request, $orderId)
    {
        $request->validate(['status' => 'required|string']);
        $vendor = Auth::user();

        $orderItem = OrderItem::where('vendor_id', $vendor->id)->findOrFail($orderId);
        $oldStatus = $orderItem->status;
        $newStatus = $request->status;

        $orderItem->update(['status' => $newStatus]);

        if ($orderItem->order) {
            if (strtolower($newStatus) === 'refunded' && strtolower($oldStatus) !== 'refunded') {
                $orderItem->order->update([
                    'status' => 'Refunded',
                    'payment_status' => 'refunded'
                ]);

                if ($orderItem->product_id) {
                    Product::where('id', $orderItem->product_id)->increment('stock', $orderItem->quantity);
                }
            } else {
                $orderItem->order->update(['status' => $newStatus]);
            }
        }

        $msg = app()->getLocale() === 'sw' 
            ? '✅ Hali ya oda imebadilishwa kikamilifu, kiasi kimerekebishwa na bidhaa zimerudishwa stoo!' 
            : '✅ Order status updated successfully, accounts adjusted, and items restocked!';

        return back()->with('success', $msg);
    }

    public function withdraw(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:10000',
            'phone' => 'required|string|max:30',
        ]);

        $vendor = Auth::user();

        if ($request->amount > $vendor->balance) {
            return back()->with('error', 'Salio lako halitoshi kutoa kiwango hiki.');
        }

        $vendor->decrement('balance', $request->amount);

        Withdrawal::create([
            'vendor_id' => $vendor->id,
            'amount' => $request->amount,
            'phone' => $request->phone,
            'status' => 'Pending',
        ]);

        if (empty($vendor->phone)) {
            $vendor->update(['phone' => $request->phone]);
        }

        return back()->with('success', 'Ombi lako la kutoa fedha limewasilishwa kwa msimamizi.');
    }
}
