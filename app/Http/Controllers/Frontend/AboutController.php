<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Backend\Chef;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AboutController extends Controller
{
    public function index()
    {
        $chefs = Chef::orderBy('id', 'desc')
            ->limit(4)
            ->get(['name', 'position', 'photo', 'insta_link', 'linkedin_link', 'fb_link']);

        return view('frontend.about.index', [
            'chefs' => $chefs
        ]);
    }
}
