<?php

namespace App\Livewire\LandingPage;

use Livewire\Component;

class Order extends Component
{
    public function render()
    {
        $data['title'] = 'Order';
        $data['active'] = 'Order';
        return view('livewire.landing-page.order', $data)->layout('components.layouts.guest', $data);
    }
}