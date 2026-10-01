<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\FlashSale;
use App\Models\Product;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        $banners = Banner::where('is_active', true)->orderBy('sort_order')->get();
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();

        $flashSale = FlashSale::where('is_active', true)
            ->where('starts_at', '<=', now())
            ->where('ends_at', '>=', now())
            ->with(['items.product.images', 'items.product.brand'])
            ->first();

        $topProducts = Product::where('is_active', true)
            ->with(['images', 'brand', 'category'])
            ->orderByDesc('sold_count')
            ->take(8)
            ->get();

        $latestProducts = Product::where('is_active', true)
            ->with(['images', 'brand', 'category'])
            ->latest()
            ->take(8)
            ->get();

        return Inertia::render('Home', [
            'banners' => $banners,
            'categories' => $categories,
            'flashSale' => $flashSale,
            'topProducts' => $topProducts,
            'latestProducts' => $latestProducts,
        ]);
    }
}
