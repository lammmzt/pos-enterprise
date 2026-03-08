<?php

namespace App\Livewire\Admin\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Login extends Component
{
    public function mount()
    {
        // jika ada auth
        if (Auth::check()) {
            return redirect()->route('admin/dashboard');
        }
    }
    public function render()
    {
        $data['title'] = 'Auth';
        $data['active'] = 'Auth';
        
        return view('livewire.admin.auth.login', $data)->layout('components.layouts.auth', $data);
    }
}