<?php

namespace App\Livewire\LandingPage;

use Livewire\Component;

class Profile extends Component
{
    public function render()
    {
        $data['title'] = 'Profile';
        $data['active'] = 'Profile';
        return view('livewire.landing-page.profile', $data)->layout('components.layouts.app', $data);
    }
}