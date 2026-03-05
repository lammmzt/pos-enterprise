<?php

namespace App\Livewire\LandingPage;

use Livewire\Component;
use App\Models\Testimoni; // Sesuaikan dengan model Ulasan/Review Anda

class Home extends Component
{
    public function render()
    {
        $data['testimonials'] = Testimoni::with(['pesanan.pelanggan', 'pesanan'])->where('status_tampil', '1')->get();
        $data['title'] = 'Home';
        $data['active'] = 'Home';
        // dd($data);
        return view('livewire.landing-page.home', $data)->layout('components.layouts.guest', $data);
    }
}