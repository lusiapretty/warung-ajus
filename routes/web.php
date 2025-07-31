<?php

use App\Http\Controllers\AddonController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\CheckoutController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Controllers\PelangganController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/


// ===== Auth Routes =====
// Login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Register
Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Forgot Password
Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->middleware('guest')->name('password.request');

Route::post('/forgot-password', function (Request $request) {
    $request->validate(['email' => 'required|email']);

    $status = Password::sendResetLink($request->only('email'));

    return $status === Password::RESET_LINK_SENT
        ? back()->with(['status' => __($status)])
        : back()->withErrors(['email' => __($status)]);
})->middleware('guest')->name('password.email');

// Reset Password
Route::get('/reset-password/{token}', function (string $token) {
    return view('auth.reset-password', ['token' => $token]);
})->middleware('guest')->name('password.reset');

Route::post('/reset-password', function (Request $request) {
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:6|confirmed',
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->forceFill([
                'password' => Hash::make($password),
            ])->save();
        }
    );

    return $status === Password::PASSWORD_RESET
        ? redirect()->route('login')->with('status', __($status))
        : back()->withErrors(['email' => [__($status)]]);
})->middleware('guest')->name('password.update');

// ===== Public Routes (Pelanggan) =====
Route::middleware(['auth', 'role:pelanggan'])->get('/home', function () {
    return view('home');
})->name('pelanggan.home');
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/tentang', [PageController::class, 'tentang'])->name('tentang');
Route::get('/menu', [PageController::class, 'menu'])->name('menu.pelanggan');
Route::get('/pesan', [PageController::class, 'pesan']);
Route::get('/menu-makanan', [MenuController::class, 'indexMakanan'])->name('menu.makanan');
Route::get('/menu-minuman', [MenuController::class, 'indexMinuman'])->name('menu.minuman');
Route::get('/meja-terpakai', [OrderController::class, 'getMejaTerpakai']);
Route::post('/checkout', [CheckoutController::class, 'checkout'])->name('checkout')->middleware('auth');
Route::post('/simpan-order', [CheckoutController::class, 'storeFromMidtrans'])->name('order.storeFormMidtrans');
Route::post('/midtrans/token', [PaymentController::class, 'createSnapToken'])->name('midtrans.token');

Route::middleware(['auth'])->group(function () {
    Route::get('/profil', [ProfileController::class, 'edit'])->name('profil.edit');
    Route::post('/profil', [ProfileController::class, 'update'])->name('profil.update');
    Route::get('/pesanan-saya', [OrderController::class, 'pesananSaya'])->name('pesanan.saya');
});

// ===== Admin Routes =====
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Menu Routes
    Route::get('/admin/menu', [MenuController::class, 'index'])->name('admin.menu.index');
    Route::get('/menu/create', [MenuController::class, 'create'])->name('admin.menu.create');
    Route::post('/admin/menu/store', [MenuController::class, 'store'])->name('admin.menu.store');
    Route::get('/admin/menu/{id}/edit', [MenuController::class, 'edit'])->name('admin.menu.edit');
    Route::post('/admin/menu/toggle-status/{id}', [MenuController::class, 'toggleStatus'])->name('admin.menu.toggleStatus');
    Route::put('/admin/menu/update/{id}', [MenuController::class, 'update'])->name('admin.menu.update');
    Route::delete('/admin/menu/{id}', [MenuController::class, 'destroy'])->name('admin.menu.destroy');
    Route::get('/admin/menu/{id}', [MenuController::class, 'show']);

    // Addon Routes
    Route::get('/admin/addons', [AddonController::class, 'index'])->name('admin.addons.index');  
    Route::post('/admin/addons/store', [AddonController::class, 'store'])->name('admin.addons.store');
    Route::get('/admin/addons/{id}/edit', [AddonController::class, 'edit'])->name('admin.addons.edit');
    Route::put('/admin/addons/update/{id}', [AddonController::class, 'update'])->name('admin.addons.update');
    Route::delete('/admin/addons/{id}', [AddonController::class, 'destroy'])->name('admin.addons.destroy');

    //Pelanggan Routes
    Route::get('/admin/pelanggan', [PelangganController::class, 'index'])->name('admin.pelanggan.index');

    // Pesanan Pelanggan Routes
    Route::get('/admin/orders', [OrderController::class, 'index'])->name('admin.orders.index');
    Route::put('/admin/orders/{id}/update', [OrderController::class, 'update'])->name('admin.orders.update');
    Route::delete('/admin/orders/{id}', [OrderController::class, 'destroy'])->name('admin.orders.destroy');
    // Route::get('/admin/orders/{id}/print', [OrderController::class, 'print'])->name('admin.orders.print');
    Route::get('/admin/orders/datatables', [OrderController::class, 'getDatatables'])->name('admin.orders.datatables');
    Route::get('/admin/orders/{order}', [AdminController::class, 'show'])->name('admin.orders.show');
    // Route::patch('/admin/orders/{order}/update-status-meja', [OrderController::class, 'updateStatusMeja'])->name('admin.orders.updateStatusMeja');
    Route::patch('/admin/orders/{order}/update-no-meja', [OrderController::class, 'updateNoMeja'])->name('admin.orders.updateNoMeja');
    // Route::patch('/admin/orders/{order}/toggle-meja-status', [OrderController::class, 'toggleMejaStatus'])->name('admin.orders.toggleMejaStatus');
    // Route::patch('/admin/orders/{order}/release-table', [OrderController::class, 'releaseTable'])->name('admin.orders.releaseTable');


    Route::patch('/admin/orders/{order}/update-payment-status', [OrderController::class, 'updatePaymentStatus'])->name('admin.orders.updatePaymentStatus');
    Route::patch('/admin/orders/{id}/status', [OrderController::class, 'updateStatus'])->name('admin.orders.updateStatus');

    Route::get('/admin/orders/print/{order}', [OrderController::class, 'print'])->name('admin.orders.print');
    Route::get('/admin/orders/export/pdf', [OrderController::class, 'exportPdf'])->name('admin.orders.export.pdf');
    Route::get('/admin/orders/export/excel', [OrderController::class, 'exportExcel'])->name('admin.orders.export.excel');

});

