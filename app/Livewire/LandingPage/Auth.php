<?php

namespace App\Livewire\LandingPage;

use Livewire\Component;

class Auth extends Component
{
    public function render()
    {
        $data['title'] = 'Auth';
        $data['active'] = 'Auth';
        return view('livewire.landing-page.auth', $data)->layout('components.layouts.app', $data);
    }
}