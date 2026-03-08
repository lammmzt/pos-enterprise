<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\LandingPage\Home;
use App\Livewire\LandingPage\Order;
use App\Livewire\LandingPage\AuthLanding;
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
use App\Livewire\Admin\Testimoni\TestimoniIndex;
use App\Livewire\Admin\Pos\PesananAktifIndex;
use App\Livewire\Admin\Pos\RiwayatPesananIndex;
use App\Livewire\Admin\Laporan\LaporanKeuanganIndex;
use App\Livewire\Admin\Laporan\LaporanPenjualanIndex;


use App\Http\Controllers\AuthController; // Import Auth Controller Custom kita
use App\Http\Controllers\FrontController;
use App\Http\Controllers\Admin\PosController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Api\MidtransWebhookController;
// use App\Http\Controllers\PosController;

use App\Models\User;
use Illuminate\Support\Facades\Auth;


/*
|--------------------------------------------------------------------------
| AUTO LOGIN
|--------------------------------------------------------------------------
*/
Route::get('/auto-login/{token}', function($token) {
    // Decode username dari token base64
    $username = base64_decode($token);
    
    // Cari user antrean_
    $user = User::where('username', $username)->first();
    
    if($user && str_starts_with(strtolower($user->username), 'antrean_')) {
        Auth::login($user);
        // $request->session()->regenerate();
        session()->regenerate();
        return redirect()->route('Order')->with('success', 'Berhasil login ke sistem antrean.');
    }
    
    return redirect()->route('Auth')->with('error', 'Token QR Tidak Valid.');
})->name('auto.login');


/*
|--------------------------------------------------------------------------
| AREA PUBLIK (E-Commerce)
|--------------------------------------------------------------------------
*/
Route::get('/', Home::class)->name('Home');
Route::get('Order', Order::class)->name('Order');
Route::get('/Auth', AuthLanding::class)->name('Auth');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
Route::get('/logoutUser', [AuthController::class, 'logoutUser'])->name('logoutUser')->middleware('auth');
/*
|--------------------------------------------------------------------------
| AREA AUTENTIKASI (Custom murni, tanpa Laravel UI)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('login.process');
});



/*
|--------------------------------------------------------------------------
| AREA PELANGGAN (Wajib Login)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:pelanggan'])->group(function () {
    Route::get('/profil', [FrontController::class, 'profil'])->name('pelanggan.profil');
    Route::get('/pesanan-saya', [FrontController::class, 'pesanan'])->name('pelanggan.pesanan');
    Route::post('/midtrans/webhook', [MidtransWebhookController::class, 'handler']);
    Route::get('/payment/{id}', Payment::class)->name('Payment');
    Route::get('Riwayat', Riwayat::class)->name('Riwayat');
    Route::get('Profile', Profile::class)->name('Profile');
});

/*
|--------------------------------------------------------------------------
| AREA BACKEND (Admin, Owner, Kasir)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->middleware(['auth'])->group(function () {
    // =================================================================
    // 1. AKSES UNTUK SEMUA ROLE (Owner, Admin, Kasir)
    // =================================================================
    Route::middleware(['role:owner,admin,kasir'])->group(function () {
        Route::get('/dashboard', DashboardIndex::class)->name('admin.dashboard');
    });
    // =================================================================
    // 2. AKSES UNTUK OWNER & ADMIN SAJA (Kasir DILARANG masuk)
    // =================================================================
    Route::middleware(['role:owner,admin'])->group(function () {
        // Master Data
        Route::get('/kategori', KategoriIndex::class)->name('admin.kategori');
        Route::get('/produk', ProdukIndex::class)->name('admin.produk');
        Route::get('/pemasok', PemasokIndex::class)->name('admin.pemasok');
        // Inventaris & Pembelian
        Route::get('/pembelian', PembelianIndex::class)->name('admin.pembelian');
        Route::get('/mutasi-stok', MutasiStokIndex::class)->name('admin.mutasi-stok');
        // data pelanggan
        Route::get('/user', UserIndex::class)->name('admin.user');
        // Lain-lain
        Route::get('/testimoni', TestimoniIndex::class)->name('admin.testimoni');
    });
    // =================================================================
    // 3. AKSES KHUSUS OWNER SAJA (Super Admin)
    // =================================================================
    Route::middleware(['role:owner'])->group(function () {
        Route::get('/pengaturan', PengaturanIndex::class)->name('admin.pengaturan');
        // Laporan Pendapatan & Keuangan
        Route::get('/laporan-keuangan', LaporanKeuanganIndex::class)->name('admin.laporan-keuangan');
        Route::get('/laporan-keuangan/cetak', [LaporanController::class, 'cetakKeuangan'])->name('admin.laporan.cetak');
        
        Route::get('/laporan-penjualan', LaporanPenjualanIndex::class)->name('admin.laporan-penjualan');
        Route::get('/laporan-penjualan/cetak', [LaporanController::class, 'cetakPenjualan'])->name('admin.laporan.penjualan.cetak');
    });
    // =================================================================
    // 4. AKSES KHUSUS OWNER & KASIR
    // =================================================================
    Route::middleware(['role:kasir,owner'])->group(function () {
        // Operasional POS & Pesanan
        Route::get('/pos', PosIndex::class)->name('admin.pos');
        Route::get('/pesanan-aktif', PesananAktifIndex::class)->name('admin.pesanan-aktif');
        Route::get('/pos/struk/{id}', [PosController::class, 'cetakStruk'])->name('admin.pos.struk');
        Route::get('/riwayat-pesanan', RiwayatPesananIndex::class)->name('admin.riwayat-pesanan');
    });

});