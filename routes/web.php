<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\Vendor\VendorDashboardController;
use App\Http\Controllers\Vendor\VendorProductController;
use Illuminate\Support\Facades\Route;

// Lugha (Language switch)
Route::get('/lang/{lang}', [HomeController::class, 'switchLang'])->name('lang.switch');

// Kurasa za Umma na Duka (Public & Customer)
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/products', [ShopController::class, 'products'])->name('shop.products');
Route::get('/products/{id}', [ShopController::class, 'show'])->name('shop.product.details');

// Kikapu (Cart)
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::get('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::get('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

// Malipo na Oda (Checkout & Orders)
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/order/receipt/{id}', [OrderController::class, 'receipt'])->name('order.receipt');
Route::get('/track-order', [OrderController::class, 'track'])->name('order.track');
Route::get('/my-orders', [OrderController::class, 'myOrders'])->name('order.my_orders');
Route::post('/orders/{id}/confirm-delivery', [OrderController::class, 'confirmDelivery'])->name('order.confirm_delivery');
Route::post('/orders/{id}/dispute', [OrderController::class, 'storeDispute'])->name('order.dispute');

// Msaada na Chat (Support)
Route::get('/support', [SupportController::class, 'index'])->name('support.index');
Route::post('/support/send', [SupportController::class, 'sendMessage'])->name('support.send');

// Uthibitishaji (Auth)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashibodi ya Muuzaji (Vendor Portal)
Route::prefix('vendor')->middleware(['auth', 'role:vendor,admin'])->group(function () {
    Route::get('/dashboard', [VendorDashboardController::class, 'index'])->name('vendor.dashboard');
    Route::get('/dashboard.php', [VendorDashboardController::class, 'index']);
    Route::get('/orders', [VendorDashboardController::class, 'orders'])->name('vendor.orders');
    Route::get('/orders.php', [VendorDashboardController::class, 'orders']);
    Route::post('/orders/{id}/status', [VendorDashboardController::class, 'updateOrderStatus'])->name('vendor.order.status');
    Route::get('/products', [VendorProductController::class, 'index'])->name('vendor.products');
    Route::get('/products.php', [VendorProductController::class, 'index']);
    Route::post('/products/store', [VendorProductController::class, 'store'])->name('vendor.products.store');
    Route::post('/products/{id}/update', [VendorProductController::class, 'update'])->name('vendor.products.update');
    Route::put('/products/{id}', [VendorProductController::class, 'update'])->name('vendor.products.update_put');
    Route::delete('/products/{id}', [VendorProductController::class, 'destroy'])->name('vendor.products.destroy');
    Route::post('/subscribe', [VendorProductController::class, 'subscribe'])->name('vendor.subscribe');
    Route::get('/subscribe', [VendorProductController::class, 'subscribePage'])->name('vendor.subscribe.page');
    Route::get('/subscribe.php', [VendorProductController::class, 'subscribePage']);
    Route::get('/checkout', [VendorProductController::class, 'checkoutPage'])->name('vendor.checkout');
    Route::get('/checkout.php', [VendorProductController::class, 'checkoutPage']);
    Route::post('/process-payment', [VendorProductController::class, 'processPayment'])->name('vendor.process_payment');
    Route::post('/process_payment.php', [VendorProductController::class, 'processPayment']);
    Route::post('/withdraw', [VendorDashboardController::class, 'withdraw'])->name('vendor.withdraw');
});

// Dashibodi ya Msimamizi Mkuu (Admin Portal)
Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/dashboard.php', [AdminDashboardController::class, 'index']);
    Route::get('/products', [AdminDashboardController::class, 'products'])->name('admin.products');
    Route::get('/products.php', [AdminDashboardController::class, 'products']);
    Route::get('/orders', [AdminDashboardController::class, 'orders'])->name('admin.orders');
    Route::get('/orders.php', [AdminDashboardController::class, 'orders']);
    Route::get('/escrow_payments', [AdminDashboardController::class, 'escrowPayments'])->name('admin.escrow_payments');
    Route::get('/escrow_payments.php', [AdminDashboardController::class, 'escrowPayments']);
    Route::post('/escrow_payments', [AdminDashboardController::class, 'escrowPayments']);
    Route::post('/escrow_payments.php', [AdminDashboardController::class, 'escrowPayments']);
    Route::get('/withdrawals', [AdminDashboardController::class, 'withdrawals'])->name('admin.withdrawals');
    Route::get('/withdrawals.php', [AdminDashboardController::class, 'withdrawals']);
    Route::post('/withdrawals', [AdminDashboardController::class, 'withdrawals']);
    Route::post('/withdrawals.php', [AdminDashboardController::class, 'withdrawals']);
    Route::post('/withdrawals/{id}/approve', [AdminDashboardController::class, 'approveWithdrawal'])->name('admin.withdrawals.approve');
    Route::get('/vendors', [AdminDashboardController::class, 'vendors'])->name('admin.vendors');
    Route::get('/vendors.php', [AdminDashboardController::class, 'vendors']);
    Route::get('/disputes', [AdminDashboardController::class, 'disputes'])->name('admin.disputes');
    Route::post('/disputes/{id}/resolve', [AdminDashboardController::class, 'resolveDispute'])->name('admin.disputes.resolve');
    Route::get('/users', [AdminDashboardController::class, 'users'])->name('admin.users');
    Route::get('/users.php', [AdminDashboardController::class, 'users']);
    Route::post('/users/{id}/password', [AdminDashboardController::class, 'changePassword'])->name('admin.users.password');
    Route::post('/users/{id}/delete', [AdminDashboardController::class, 'deleteUser'])->name('admin.users.delete');
    Route::delete('/users/{id}', [AdminDashboardController::class, 'deleteUser'])->name('admin.users.destroy');
    Route::get('/reports', [AdminDashboardController::class, 'reports'])->name('admin.reports');
    Route::get('/reports.php', [AdminDashboardController::class, 'reports']);
    Route::get('/backup/export', [AdminDashboardController::class, 'exportBackup'])->name('admin.backup.export');
});

Route::get('/admin/reports.php', [AdminDashboardController::class, 'reports']);

// Chatbot Product Search API
Route::get('/get_product.php', [SupportController::class, 'getProductJson'])->name('api.get_product_legacy');
Route::get('/api/get-product', [SupportController::class, 'getProductJson'])->name('api.get_product');

// Legacy route aliases
Route::get('/track', [OrderController::class, 'track']);
Route::get('/track.php', [OrderController::class, 'track']);
Route::get('/products.php', [ShopController::class, 'products']);
Route::get('/cart.php', [CartController::class, 'index']);
Route::post('/cart.php', [CartController::class, 'add']);
Route::get('/checkout.php', [CheckoutController::class, 'index']);
Route::post('/checkout.php', [CheckoutController::class, 'process']);
Route::get('/login.php', [AuthController::class, 'showLogin']);
Route::get('/register.php', [AuthController::class, 'showRegister']);
Route::get('/my_orders.php', [OrderController::class, 'myOrders']);
Route::get('/print_receipt.php', [OrderController::class, 'receipt'])->name('order.receipt_legacy');
Route::get('/product_details.php', function(\Illuminate\Http\Request $req) { 
    return redirect()->route('shop.product.details', $req->get('id', 1)); 
});


