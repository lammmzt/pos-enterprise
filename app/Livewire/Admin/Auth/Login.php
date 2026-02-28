<?php

namespace App\Livewire\Admin\Auth;

use Livewire\Component;

class Login extends Component
{
    public function render()
    {
        $data['title'] = 'Auth';
        $data['active'] = 'Auth';
        return view('livewire.admin.auth.login', $data)->layout('components.layouts.auth', $data);
    }
}