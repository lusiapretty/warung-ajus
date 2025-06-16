<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ===== Public Routes (Pelanggan) =====
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/tentang', [PageController::class, 'tentang'])->name('tentang');
Route::get('/menu', [PageController::class, 'menu'])->name('menu.pelanggan');
Route::get('/pesan', [PageController::class, 'pesan']);
Route::get('/menu-makanan', function () {
    return view('menu-makanan');
})->name('menu-makanan');
Route::get('/menu-minuman', function () {
    return view('menu-minuman');
})->name('menu-minuman');

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

// ===== Pelanggan Route =====
Route::middleware(['auth', 'role:pelanggan'])->get('/home', function () {
    return view('home');
})->name('pelanggan.home');

// ===== Admin Routes =====
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Dashboard Route
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // Menu Routes
    Route::get('/admin/menu', [AdminController::class, 'index'])->name('admin.menu.index');
    Route::get('/menu/create', [AdminController::class, 'create'])->name('admin.menu.create');
    Route::post('/admin/menu/store', [AdminController::class, 'store'])->name('admin.menu.store');
    Route::get('/admin/menu/{id}/edit', [AdminController::class, 'edit'])->name('admin.menu.edit');
    Route::put('/admin/menu/update/{id}', [AdminController::class, 'update'])->name('admin.menu.update');
    Route::delete('/admin/menu/{id}', [AdminController::class, 'destroy'])->name('admin.menu.destroy');
    Route::get('/admin/menu/{id}', [AdminController::class, 'show']);
});
