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
                            ['name' => 'Product List', 'route' => 'admin.dashboard'],
                            ['name' => 'Add Product', 'route' => 'admin.dashboard'],
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
        ];
    }

    public function render(): View|Closure|string
    {
        return view('components.admin.layouts.sidebar');
    }
}