<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorProductController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('lang')) {
            $lang = in_array($request->lang, ['sw', 'en']) ? $request->lang : 'en';
            session()->put('locale', $lang);
            app()->setLocale($lang);
        }

        $vendor = Auth::user();
        // Restricted if vendor is not verified OR subscription is not active
        $isRestricted = (!$vendor->is_verified || !$vendor->subscription_active);

        $products = Product::where('vendor_id', $vendor->id)
            ->with('category')
            ->latest()
            ->paginate(15);

        $categories = Category::all();

        return view('vendor.products', compact('vendor', 'isRestricted', 'products', 'categories'));
    }

    public function subscribePage(Request $request)
    {
        if ($request->has('lang')) {
            $lang = in_array($request->lang, ['sw', 'en']) ? $request->lang : 'en';
            session()->put('locale', $lang);
            app()->setLocale($lang);
        }

        $vendor = Auth::user();
        return view('vendor.subscribe', compact('vendor'));
    }

    public function checkoutPage(Request $request)
    {
        if ($request->has('lang')) {
            $lang = in_array($request->lang, ['sw', 'en']) ? $request->lang : 'en';
            session()->put('locale', $lang);
            app()->setLocale($lang);
        }

        $vendor = Auth::user();
        $plan = $request->input('plan', 'Monthly Plan');
        $amount = (float)$request->input('amount', 15000);

        return view('vendor.checkout', compact('vendor', 'plan', 'amount'));
    }

    public function processPayment(Request $request)
    {
        $vendor = Auth::user();
        $data = $request->isJson() ? $request->json()->all() : $request->all();

        $txId = trim($data['txId'] ?? $data['transaction_id'] ?? '');
        $plan = trim($data['plan'] ?? 'Monthly Plan');
        $amount = (float)($data['amount'] ?? 15000);
        $method = trim($data['method'] ?? $data['payment_method'] ?? 'M-Pesa');
        $phone = trim($data['phone'] ?? $vendor->phone ?? '');
        $store = trim($data['store'] ?? $vendor->shop_name ?? '');

        if (empty($txId)) {
            return response()->json([
                'status' => 'error',
                'message' => app()->getLocale() === 'sw' ? 'Tafadhali jaza Transaction ID.' : 'Please enter Transaction ID.'
            ], 422);
        }

        // Check if transaction ID has already been used
        $exists = \App\Models\Payment::where('transaction_id', $txId)->exists();
        if ($exists) {
            return response()->json([
                'status' => 'error',
                'message' => app()->getLocale() === 'sw' 
                    ? "⚠️ Transaction ID hii ($txId) imeshatumika tayari! Tafadhali weka Transaction ID sahihi."
                    : "⚠️ This Transaction ID ($txId) has already been used! Please enter a valid Transaction ID."
            ], 422);
        }

        \App\Models\Payment::create([
            'vendor_id' => $vendor->id,
            'transaction_id' => $txId,
            'amount' => $amount,
            'plan_name' => $plan,
            'payment_method' => $method,
            'phone' => $phone,
            'status' => 'Pending',
        ]);

        if (!empty($store)) {
            $vendor->shop_name = $store;
        }
        if (!empty($phone)) {
            $vendor->phone = $phone;
        }
        $vendor->save();

        return response()->json(['status' => 'success']);
    }

    public function subscribe(Request $request)
    {
        $vendor = Auth::user();
        $plan = $request->input('plan', 'monthly');

        $durationMonths = match($plan) {
            'yearly' => 12,
            '6months' => 6,
            default => 1,
        };

        $vendor->update([
            'is_verified' => true,
            'subscription_active' => true,
            'subscription_end_date' => now()->addMonths($durationMonths),
        ]);

        $message = app()->getLocale() === 'sw' 
            ? '✅ Usajili umewezeshwa kwa mafanikio! Sasa duka lako limehakikiwa na unaweza kuongeza bidhaa.' 
            : '✅ Subscription activated successfully! Your store is now verified and you can add products.';

        return back()->with('success', $message);
    }

    public function store(Request $request)
    {
        $vendor = Auth::user();
        if (!$vendor->is_verified || !$vendor->subscription_active) {
            return back()->with('error', __('messages.vendor_unauthorized_title'));
        }

        $isSwahili = app()->getLocale() === 'sw';

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'details' => 'nullable|string',
            'images' => 'required|array|min:2',
        ], [
            'name.required' => $isSwahili ? 'Tafadhali jaza jina la bidhaa.' : 'Please enter product name.',
            'category_id.required' => $isSwahili ? 'Tafadhali chagua kategoria.' : 'Please select a category.',
            'price.required' => $isSwahili ? 'Tafadhali weka bei ya bidhaa.' : 'Please enter product price.',
            'stock.required' => $isSwahili ? 'Tafadhali weka idadi iliyopo stoo.' : 'Please enter stock quantity.',
            'images.required' => $isSwahili 
                ? 'Tafadhali chagua picha za bidhaa (angalau picha 2 au zaidi).' 
                : 'Please select product images (at least 2 images required).',
            'images.min' => $isSwahili 
                ? 'Mfumo unahitaji picha kuanzia 2 na kuendelea. Tafadhali chagua angalau picha 2.' 
                : 'The system requires 2 or more images. Please select at least 2 images.',
        ]);

        $uploadedFiles = $request->file('images', []);
        if (!is_array($uploadedFiles) || count($uploadedFiles) < 2) {
            return back()->withInput()->with('error', $isSwahili 
                ? 'Tafadhali chagua angalau picha 2 au zaidi za bidhaa hii.' 
                : 'Please select at least 2 images for this product.');
        }

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'jfif', 'svg', 'bmp', 'avif'];
        $imageNames = [];

        foreach ($uploadedFiles as $index => $file) {
            if (!$file->isValid()) {
                return back()->withInput()->with('error', $isSwahili 
                    ? "Picha namba " . ($index + 1) . " haikupakiwa vizuri. Tafadhali jaribu tena." 
                    : "Image #" . ($index + 1) . " failed to upload. Please try again.");
            }

            $ext = strtolower($file->getClientOriginalExtension());
            $guessExt = strtolower($file->guessExtension() ?? '');

            if (!in_array($ext, $allowedExtensions) && !in_array($guessExt, $allowedExtensions)) {
                return back()->withInput()->with('error', $isSwahili 
                    ? "Picha namba " . ($index + 1) . " ina muundo usioungwa mkono ($ext). Tumia JPG, PNG, WEBP, au JPEG." 
                    : "Image #" . ($index + 1) . " has an unsupported format ($ext). Please use JPG, PNG, WEBP, or JPEG.");
            }

            if ($file->getSize() > 8 * 1024 * 1024) { // 8MB per image
                return back()->withInput()->with('error', $isSwahili 
                    ? "Picha namba " . ($index + 1) . " imezidi ukubwa unaoruhusiwa (8MB)." 
                    : "Image #" . ($index + 1) . " exceeds the maximum allowed size (8MB).");
            }

            $imageName = time() . '_' . uniqid() . '.' . ($ext ?: 'jpg');
            $file->move(public_path('images'), $imageName);
            $imageNames[] = $imageName;
        }

        $imageString = implode(',', $imageNames);

        Product::create([
            'vendor_id' => $vendor->id,
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'details' => $validated['details'] ?? null,
            'image' => $imageString,
            'status' => 'active',
        ]);

        $count = count($imageNames);
        $successMsg = $isSwahili 
            ? "Hongera! Bidhaa mpya yenye picha $count imehifadhiwa kikamilifu dukani kwako!" 
            : "Success! New product with $count images has been added to your store!";

        return back()->with('success', $successMsg);
    }

    public function update(Request $request, $id)
    {
        $vendor = Auth::user();
        if (!$vendor->is_verified || !$vendor->subscription_active) {
            return back()->with('error', __('messages.vendor_unauthorized_title'));
        }

        $product = Product::where('vendor_id', $vendor->id)->findOrFail($id);
        $isSwahili = app()->getLocale() === 'sw';

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'details' => 'nullable|string',
            'images' => 'nullable|array',
        ], [
            'name.required' => $isSwahili ? 'Tafadhali jaza jina la bidhaa.' : 'Please enter product name.',
            'category_id.required' => $isSwahili ? 'Tafadhali chagua kategoria.' : 'Please select a category.',
            'price.required' => $isSwahili ? 'Tafadhali weka bei ya bidhaa.' : 'Please enter product price.',
            'stock.required' => $isSwahili ? 'Tafadhali weka idadi iliyopo stoo.' : 'Please enter stock quantity.',
        ]);

        if ($request->hasFile('images')) {
            $uploadedFiles = $request->file('images', []);
            if (count($uploadedFiles) < 2) {
                return back()->withInput()->with('error', $isSwahili 
                    ? 'Ukibadilisha picha, tafadhali chagua angalau picha 2 au zaidi za bidhaa hii.' 
                    : 'If updating images, please select at least 2 images for this product.');
            }

            $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'jfif', 'svg', 'bmp', 'avif'];
            $imageNames = [];

            foreach ($uploadedFiles as $index => $file) {
                if (!$file->isValid()) {
                    return back()->withInput()->with('error', $isSwahili 
                        ? "Picha namba " . ($index + 1) . " haikupakiwa vizuri. Tafadhali jaribu tena." 
                        : "Image #" . ($index + 1) . " failed to upload. Please try again.");
                }

                $ext = strtolower($file->getClientOriginalExtension());
                $guessExt = strtolower($file->guessExtension() ?? '');

                if (!in_array($ext, $allowedExtensions) && !in_array($guessExt, $allowedExtensions)) {
                    return back()->withInput()->with('error', $isSwahili 
                        ? "Picha namba " . ($index + 1) . " ina muundo usioungwa mkono ($ext). Tumia JPG, PNG, WEBP, au JPEG." 
                        : "Image #" . ($index + 1) . " has an unsupported format ($ext). Please use JPG, PNG, WEBP, or JPEG.");
                }

                if ($file->getSize() > 8 * 1024 * 1024) {
                    return back()->withInput()->with('error', $isSwahili 
                        ? "Picha namba " . ($index + 1) . " imezidi ukubwa unaoruhusiwa (8MB)." 
                        : "Image #" . ($index + 1) . " exceeds the maximum allowed size (8MB).");
                }

                $imageName = time() . '_' . uniqid() . '.' . ($ext ?: 'jpg');
                $file->move(public_path('images'), $imageName);
                $imageNames[] = $imageName;
            }

            $product->image = implode(',', $imageNames);
        }

        $product->category_id = $validated['category_id'];
        $product->name = $validated['name'];
        $product->price = $validated['price'];
        $product->stock = $validated['stock'];
        $product->details = $validated['details'] ?? null;
        $product->save();

        $successMsg = $isSwahili 
            ? 'Taarifa za bidhaa zimesasishwa kwa mafanikio!' 
            : 'Product details updated successfully!';

        return back()->with('success', $successMsg);
    }

    public function destroy($id)
    {
        $product = Product::where('vendor_id', Auth::id())->findOrFail($id);
        $product->delete();

        $deleteMsg = app()->getLocale() === 'sw' 
            ? 'Bidhaa imeondolewa dukani.' 
            : 'Product removed from your store.';

        return back()->with('success', $deleteMsg);
    }
}
