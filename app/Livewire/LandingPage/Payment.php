<?php

namespace App\Livewire\LandingPage;

use Livewire\Component;

class Payment extends Component
{
    public function render()
    {
        $data['title'] = 'Payment';
        $data['active'] = 'Payment';
        return view('livewire.landing-page.payment', $data)->layout('components.layouts.guest', $data);
    }
}