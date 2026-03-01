<?php

namespace App\Livewire\LandingPage;

use Livewire\Component;

class Home extends Component
{
    public function render()
    {
        $data['title'] = 'Home';
        $data['active'] = 'Home';
        return view('livewire.landing-page.home', $data)->layout('components.layouts.guest', $data);
    }
}