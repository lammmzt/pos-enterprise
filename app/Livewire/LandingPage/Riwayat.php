<?php

namespace App\Livewire\LandingPage;

use Livewire\Component;

class Riwayat extends Component
{
    public function render()
    {
        $data['title'] = 'Riwayat';
        $data['active'] = 'Riwayat';
        return view('livewire.landing-page.riwayat', $data)->layout('components.layouts.guest', $data);
    }
}