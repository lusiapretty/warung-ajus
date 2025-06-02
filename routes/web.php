<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

// Route untuk menampikan halaman login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
// Route untuk menangani proses login
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
// Route untuk menangani proses logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Public Routes (Pelanggan)
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/tentang', [PageController::class, 'tentang'])->name('tentang');
Route::get('/menu', [PageController::class, 'menu']);
Route::get('/pesan', [PageController::class, 'pesan']);
Route::get('/menu-makanan', function () {
    return view('menu-makanan');
    })->name('menu-makanan');

Route::get('/menu-minuman', function () {
    return view('menu-minuman');
})->name('menu-minuman');
// Route::get('/keranjang', function () {
//     return view('keranjang');
// })->name('keranjang');


// Admin Routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Route::get('/admin/index', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/menu', [AdminController::class, 'index'])->name('admin.menu.index'); // Daftar Menu admin
    Route::post('/menu', [AdminController::class, 'store'])->name('admin.menu.store');
    Route::put('/menu/{id}', [AdminController::class, 'update'])->name('admin.menu.update');
    Route::delete('/menu/{id}', [AdminController::class, 'destroy'])->name('admin.menu.destroy');
});


