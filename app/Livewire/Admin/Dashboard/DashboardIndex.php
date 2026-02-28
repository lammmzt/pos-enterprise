<?php

namespace App\Livewire\Admin\Dashboard;

use Livewire\Component;

class DashboardIndex extends Component
{
    public function render()
    {
        $data['title'] = 'Dashboard';
        $data['active'] = 'Dashboard';
        return view('livewire.admin.dashboard.dashboard-index')->layout('components.layouts.app', $data);
    }
}