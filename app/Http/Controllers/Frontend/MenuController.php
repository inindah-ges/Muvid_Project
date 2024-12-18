<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Backend\Product;

class MenuController extends Controller
{
    public function index()
    {
        return view('frontend.menu.index', [
            'menu_coffee' => $this->getMenu(7),
            'menu_mocktail' => $this->getMenu(8),
            'menu_food' => $this->getMenu(9),
        ]);
    }

    public function getMenu(string $id)
    {
        $menu = Product::with('category:id,name')
            ->latest()
            ->where('status', 'available')
            ->where('category_id', $id)
            ->limit(8)
            ->get(['category_id', 'name', 'price', 'image', 'description']);

        return $menu;
    }
}
