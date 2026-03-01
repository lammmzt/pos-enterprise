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
use App\Livewire\Admin\Pemasok\PemasokIndex;
use App\Livewire\Admin\Stok\PembelianIndex;
use App\Livewire\Admin\Stok\MutasiStokIndex;
use App\Livewire\Admin\Pos\PosIndex;
use App\Livewire\Admin\Pos\PesananAktifIndex;
use App\Livewire\Admin\Pos\RiwayatPesananIndex;
use App\Livewire\Admin\Laporan\LaporanKeuanganIndex;
use App\Livewire\Admin\Laporan\LaporanPenjualanIndex;


use App\Http\Controllers\AuthController; // Import Auth Controller Custom kita
use App\Http\Controllers\FrontController;
use App\Http\Controllers\Admin\PosController;
use App\Http\Controllers\Admin\LaporanController;
// use App\Http\Controllers\PosController;



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

    // pemasok
    Route::get('/pemasok', PemasokIndex::class)->name('admin.pemasok');

    // pembelian
    Route::get('/pembelian', PembelianIndex::class)->name('admin.pembelian');

    // pembelian
    Route::get('/mutasi-stok', MutasiStokIndex::class)->name('admin.mutasi-stok');

    // pos
    Route::get('/pos', PosIndex::class)->name('admin.pos');
    // Route untuk cetak struk POS
    Route::get('/pos/struk/{id}', [PosController::class, 'cetakStruk'])->name('admin.pos.struk');
    // pos pesanan
    Route::get('pesanan-aktif', PesananAktifIndex::class)->name('admin.pesanan-aktif');
    // pos hisotory
    Route::get('riwayat-pesanan', RiwayatPesananIndex::class)->name('admin.riwayat-pesanan');


    // laporan keuangan
    Route::get('laporan-keuangan', LaporanKeuanganIndex::class)->name('admin.laporan-keuangan');

    Route::get('laporan-keuangan/cetak', [LaporanController::class, 'cetakKeuangan'])->name('admin.laporan.cetak');

    // Laporan Penjualan (Livewire Dashboard)
    Route::get('/laporan-penjualan', LaporanPenjualanIndex::class)->name('admin.laporan-penjualan');
    // Route Cetak Penjualan (Controller)
    Route::get('/laporan-penjualan/cetak', [LaporanController::class, 'cetakPenjualan'])->name('admin.laporan.penjualan.cetak');
    // Pesanan Umum
    // Route::get('/pesanan', [PesananController::class, 'index'])->name('admin.pesanan.index');
    // Route::get('/pesanan/{id}', [PesananController::class, 'show'])->name('admin.pesanan.show');

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