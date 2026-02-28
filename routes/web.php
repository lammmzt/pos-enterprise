<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\LandingPage\Home;
use App\Livewire\LandingPage\Order;
use App\Livewire\LandingPage\Auth_landing;
use App\Livewire\LandingPage\Riwayat;
use App\Livewire\LandingPage\Payment;
use App\Livewire\LandingPage\Profile;
use App\Livewire\Admin\Auth\Login;
use App\Livewire\Admin\Dashboard\DashboardIndex;
use App\Livewire\Admin\User\UserIndex;
use App\Livewire\Admin\Pengaturan\PengaturanIndex;
use App\Livewire\Admin\Kategori\KategoriIndex;
use App\Livewire\Admin\Produk\ProdukIndex;

use App\Http\Controllers\AuthController; // Import Auth Controller Custom kita
use App\Http\Controllers\FrontController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\ProdukController;
use App\Http\Controllers\Admin\PemasokController;
use App\Http\Controllers\Admin\PembelianController;
use App\Http\Controllers\Admin\MutasiStokController;
use App\Http\Controllers\Admin\PesananController;
use App\Http\Controllers\Admin\LaporanController;



/*
|--------------------------------------------------------------------------
| AREA PUBLIK (E-Commerce)
|--------------------------------------------------------------------------
*/
Route::get('/', Home::class)->name('Home');
Route::get('Order', Order::class)->name('Order');
Route::get('Auth_landing', Auth_landing::class)->name('Auth_landing');
Route::get('Riwayat', Riwayat::class)->name('Riwayat');
Route::get('Payment', Payment::class)->name('Payment');
Route::get('Profile', Profile::class)->name('Profile');
// Route::get('/dashboard', [FrontController::class, 'index'])->name('dashboard');
// Route::get('/', [FrontController::class, 'index'])->name('home');
// Route::get('/produk', [FrontController::class, 'produk'])->name('produk.index');
// Route::get('/produk/{slug}', [FrontController::class, 'detailProduk'])->name('produk.detail');
/*
|--------------------------------------------------------------------------
| AREA AUTENTIKASI (Custom murni, tanpa Laravel UI)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('login.process');
});

Route::get('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| AREA PELANGGAN (Wajib Login)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:pelanggan'])->group(function () {
    Route::get('/profil', [FrontController::class, 'profil'])->name('pelanggan.profil');
    Route::get('/pesanan-saya', [FrontController::class, 'pesanan'])->name('pelanggan.pesanan');
});

/*
|--------------------------------------------------------------------------
| AREA BACKEND (Admin, Owner, Kasir)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->middleware(['auth', 'role:admin,owner,kasir'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', DashboardIndex::class)->name('admin.dashboard');

    // user
    Route::get('/user', UserIndex::class)->name('admin.user');

    // pengaturan
    Route::get('/pengaturan', PengaturanIndex::class)->name('admin.pengaturan');

    // kategori
    Route::get('/kategori', KategoriIndex::class)->name('admin.kategori');

    // produk
    Route::get('/produk', ProdukIndex::class)->name('admin.produk');

    // POS Kasir
    Route::get('/pos', [PosController::class, 'index'])->name('pos.index');

    // Pesanan Umum
    Route::get('/pesanan', [PesananController::class, 'index'])->name('admin.pesanan.index');
    Route::get('/pesanan/{id}', [PesananController::class, 'show'])->name('admin.pesanan.show');

    // Area Khusus Admin & Owner
    // Route::middleware(['role:admin,owner'])->group(function () {
    //     Route::resource('kategori', KategoriController::class);
    //     Route::resource('produk', ProdukController::class);
    //     Route::resource('pemasok', PemasokController::class);
    //     Route::resource('pembelian', PembelianController::class);
    //     Route::resource('mutasi-stok', MutasiStokController::class);
    // });

    // Area Khusus Owner
    // Route::middleware(['role:owner'])->group(function () {
    //     Route::get('/laporan/keuangan', [LaporanController::class, 'keuangan'])->name('laporan.keuangan');
    //     Route::get('/laporan/stok', [LaporanController::class, 'stok'])->name('laporan.stok');
    // });
});