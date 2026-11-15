<?php

namespace App\Http\Controllers;

use App\Services\NuwusApi;

class HomeController extends Controller
{
    public function __invoke(NuwusApi $api)
    {
        // Pengunjung publik hanya melihat data aktif
        $public = new NuwusApi(token: '');
        $products = $public->safeGet('products/');
        $partners = $public->safeGet('partners/');

        $categories = collect($products)->pluck('category')->filter()->unique()->values();

        return view('home', [
            'company' => config('nuwus.company'),
            'products' => $products,
            'partners' => $partners,
            'categories' => $categories,
        ]);
    }
}
