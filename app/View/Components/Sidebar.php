<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Sidebar extends Component
{
    public array $menuGroups;

    public function __construct()
    {
        $this->menuGroups = [
            [
                'title' => 'Main Menu',
                'items' => [
                    [
                        'name' => 'Dashboard', 
                        'icon' => 'smart-home', 
                        'activePattern' => 'admin.dashboard',
                        'route' => 'admin.dashboard' // Single menu tanpa sub-item
                    ],
                    
                ]
            ],
            [
                'title' => 'Application',
                'items' => [
                    [
                        'name' => 'Point of Sale', 
                        'icon' => 'device-laptop', 
                        'activePattern' => 'pages.apps.pos',
                        'route' => 'admin.dashboard' // Single menu tanpa sub-item
                    ],
                    [
                        'name' => 'E-Commerce', 
                        'icon' => 'shopping-cart', 
                        'activePattern' => 'ecommerce.*',
                        'subItems' => [
                            ['name' => 'Kategori', 'route' => 'admin.kategori'],
                            ['name' => 'Produk', 'route' => 'admin.produk'],
                            ['name' => 'My Cart', 'route' => 'admin.dashboard'],
                        ]
                    ],
                ]
            ],
             [
                'title' => 'User management',
                'items' => [
                    [
                        'name' => 'Users', 
                        'icon' => 'users', 
                        'activePattern' => 'admin.user',
                        'route' => 'admin.user' // Single menu tanpa sub-item
                    ],
                    
                ]
            ],
             [
                'title' => 'Pengaturan',
                'items' => [
                    [
                        'name' => 'Pengaturan', 
                        'icon' => 'settings', 
                        'activePattern' => 'admin.pengaturan',
                        'route' => 'admin.pengaturan' // Single menu tanpa sub-item
                    ],
                    
                ]
            ],
        ];
    }

    public function render(): View|Closure|string
    {
        return view('components.admin.layouts.sidebar');
    }
}