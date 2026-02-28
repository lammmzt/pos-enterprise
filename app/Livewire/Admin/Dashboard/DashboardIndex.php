<?php

namespace App\Livewire\Admin\Dashboard;

use Livewire\Component;

class DashboardIndex extends Component
{
    public function render()
    {
        $data['title'] = 'Dashboard';
        $data['desc_page'] = 'Ringkasan penjualan, inventaris, dan performa toko Anda hari ini.';
        return view('livewire.admin.dashboard.dashboard-index',$data)->layout('components.layouts.app', $data);
    }
}