<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\NuwusApi;

class DashboardController extends Controller
{
    public function __invoke(NuwusApi $api)
    {
        $products = collect($api->safeGet('products/'));
        $partners = collect($api->safeGet('partners/'));

        return view('admin.dashboard', [
            'productCount' => $products->count(),
            'productActive' => $products->where('is_active', true)->count(),
            'partnerCount' => $partners->count(),
            'partnerActive' => $partners->where('is_active', true)->count(),
            'latest' => $products->sortByDesc('created_at')->take(5),
        ]);
    }
}
