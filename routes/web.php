<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\WithdrawController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Admin\AdminManagementController;
use App\Http\Controllers\Admin\SiteSettingController;
use App\Http\Controllers\Admin\VideoController;
use App\Http\Controllers\Admin\AdminController;








// অ্যাডমিন রাউট গ্রুপ
Route::middleware(['auth', 'verified', AdminMiddleware::class])->prefix('admin')->group(function () {

    // মেইন ড্যাশবোর্ড
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    // ইউজার ম্যানেজমেন্ট
    Route::get('/pending-users', [AdminController::class, 'pendingUsers'])->name('admin.users.pending');
    Route::post('/users/{id}/approve', [AdminController::class, 'approveUser'])->name('admin.users.approve');
    Route::post('/users/{id}/reject', [AdminController::class, 'rejectUser'])->name('admin.users.reject');

    // ভবিষ্যতে এখানে প্যাকেজ বা সেটিংস রাউট যোগ করতে পারবেন

    Route::get('/upgrades', [AdminController::class, 'viewUpgradeRequests'])->name('admin.upgrades.index');
    Route::post('/upgrades/approve/{id}', [AdminController::class, 'approveUpgrade'])->name('admin.upgrades.approve');
    Route::post('/upgrades/reject/{id}', [AdminController::class, 'rejectUpgrade'])->name('admin.upgrades.reject');

    // Route::get('/withdraw', [AdminController::class, 'withdraw'])->name('admin.withdrawals.index');
    // Route::post('/withdrawals/{id}/status', [AdminController::class, 'updateStatus'])->name('admin.withdraw.approve');
    Route::get('/withdraw', [AdminController::class, 'withdraw'])->name('admin.withdraw');
    Route::get('/withdraw/export', [AdminController::class, 'exportWithdraw'])->name('withdraw.export');
    Route::post('/withdraw/approve/{id}', [AdminController::class, 'approve'])->name('admin.withdraw.approve');
    Route::post('/withdraw/reject/{id}', [AdminController::class, 'reject'])->name('admin.withdraw.reject');

    Route::get('/users', [AdminController::class, 'userlist'])->name('admin.users.index');
    Route::post('/users/update-inline/{id}', [AdminController::class, 'updateInline'])->name('admin.users.update');
    Route::delete('/users/delete/{id}', [AdminController::class, 'destroy'])->name('admin.users.delete');


    // অ্যাডমিনের জন্য
    Route::get('/admin/withdrawals', [WithdrawController::class, 'adminIndex'])->name('admin.withdraw.index');
    Route::post('/admin/withdraw/status/{id}/{status}', [WithdrawController::class, 'updateStatus'])->name('admin.withdraw.update');


    // Admin side
    // সেটিংস পেজ দেখানো (Edit/Create Form)
    Route::get('/settings', [SiteSettingController::class, 'index'])->name('admin.settings');

    // ডাটা সেভ বা আপডেট করার জন্য একটিই রাউট
    Route::post('/settings/update', [SiteSettingController::class, 'update'])->name('admin.settings.update');
    Route::post('/admin/approve-upgrade/{id}', [AdminController::class, 'approveUpgrade']);





    Route::get('export-users', [AdminController::class, 'exportUsers'])->name('users.export');
    Route::get('admin/withdraw/export', [AdminController::class, 'exportWithdraw'])->name('withdraw.export');
});


// নতুন অ্যাডমিন তৈরির রাউট
Route::get('/create-new-admin', [AdminManagementController::class, 'create'])->name('admin.create');
Route::post('/store-new-admin', [AdminManagementController::class, 'store'])->name('admin.store');
Route::get('/orders', [AdminManagementController::class, 'orders'])->name('admin.orders.index');
Route::put('/orders/{id}/update', [AdminManagementController::class, 'updateOrderStatus'])->name('admin.orders.update');
// লগইন করা ইউজারদের জন্য
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::post('/complete-task', [HomeController::class, 'completeTask'])->name('complete.task');

    //user profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/upgrade', [ProfileController::class, 'showUpgradePage'])->name('profile.upgrade.page');
    Route::post('/profile/upgrade', [ProfileController::class, 'upgrade'])->name('profile.upgrade');


    // Taka Withdraw জন্য
    Route::get('/withdraw', [WithdrawController::class, 'index'])->name('withdraw.index');
    Route::post('/withdraw/request', [WithdrawController::class, 'store'])->name('withdraw.store');


    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

// ----Authentication-----Start------------------------//

use App\Http\Controllers\Auth\RegisterController;

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetRequest']);
Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetRequest'])->name('password.otp.resend');

// OTP রিসেট রাউটস
Route::get('password/otp-verify', function () {
    return view('auth.otp-verify');
})->name('password.otp.verify');
Route::post('password/otp-verify', [ForgotPasswordController::class, 'verifyOtp']);

Route::post('password/reset-new', [ForgotPasswordController::class, 'updatePassword'])->name('password.reset.new');

Route::get('/password-reset-form', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset.form');

Route::get('password/reset/{token}', function ($token) {
    return view('auth.reset-form', ['token' => $token]);
})->name('password.reset');



// --- গেস্ট রাউট (যারা লগইন করেনি তাদের জন্য) ---

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/pakages', [HomeController::class, 'packages'])->name('pakages');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/privacy-policy', [HomeController::class, 'privecy'])->name('privecy');

// লগইন পেজ দেখার জন্য
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
// লগইন সাবমিট করার জন্য
Route::post('/login', [LoginController::class, 'login']);


// ----Authentication-----End------------------------//



Route::middleware(['auth', 'verified', AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('videos', VideoController::class);
});


Route::get('/go', function () {
    return redirect('https://monoarulislam.xyz'); // আগের domain
});



// Storage-link


Route::get('/storage-link-pb', function () {
    $target = storage_path('app/public');
    $link = public_path('storage');

    if (!file_exists($link)) {
        symlink($target, $link);
        return 'Storage linked successfully!';
    }
    return 'Link already exists!';
});

Route::get('/storage-link', function () {
    $targetFolder = storage_path('app/public');
    $linkFolder = $_SERVER['DOCUMENT_ROOT'] . '/storage';

    if (file_exists($linkFolder)) {
        // Optional: delete the existing symlink
        if (is_link($linkFolder)) {
            unlink($linkFolder);
        } else {
            return 'Storage folder already exists and is not a symlink!';
        }
    }

    symlink($targetFolder, $linkFolder);

    return 'Storage link created successfully!';
});


use App\Http\Controllers\ShopController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;

// গেস্ট এবং ইউজার উভয়েই প্রোডাক্ট দেখতে পারবে
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/cart', [ShopController::class, 'cartIndex'])->name('cart.index');
Route::post('/cart-add', [ShopController::class, 'addToCart'])->name('cart.add');
Route::post('/cart-remove', [ShopController::class, 'removeFromCart'])->name('cart.remove');
Route::middleware(['auth'])->group(function () {
    Route::get('/my-orders', [ShopController::class, 'myOrders'])->name('orders.my');
});
// অর্ডার করার রাউট (এটি গেস্টদের জন্যও খোলা থাকবে কারণ আমরা ভেতরে ইউজার ক্রিয়েট করছি)
Route::post('/place-order', [ShopController::class, 'placeOrder'])->name('order.place');

// অ্যাডমিন প্যানেলের জন্য (Middleware admin ব্যবহার করা উচিত)
Route::prefix('admin')->group(function () {
    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class);
});


// প্রোডাক্ট ডিটেইলস দেখার রাউট (ID সহ)
Route::get('/product/{id}', [ShopController::class, 'show'])->name('shop.show');

// রিভিউ সাবমিট করার রাউট (যদি ইউজার রিভিউ দিতে চায়)
use App\Http\Controllers\ReviewController;

// প্রোটেক্টেড রাউট (লগইন করা ইউজারদের জন্য)
Route::middleware(['auth'])->group(function () {
    Route::get('/product/{id}/review', [ReviewController::class, 'create'])->name('product.review.create');
    // রিভিউ স্টোর করার রাউট
    Route::post('/product/review/store', [ReviewController::class, 'store'])->name('product.review.store');
});
