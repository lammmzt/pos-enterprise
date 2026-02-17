<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\LandingPage\Home;
use App\Livewire\LandingPage\Order;
use App\Livewire\LandingPage\Auth;
use App\Livewire\LandingPage\Riwayat;

Route::get('/', Home::class)->name('Home');
Route::get('Order', Order::class)->name('Order');
Route::get('Auth', Auth::class)->name('Auth');
Route::get('Riwayat', Riwayat::class)->name('Riwayat');