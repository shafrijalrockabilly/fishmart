<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $totalOrders = Order::count();
        $totalRevenue = Order::whereIn('status', ['paid', 'processing', 'shipped', 'completed'])->sum('grand_total');
        $activeProductsCount = Product::where('is_active', true)->count();
        $lowStockProducts = Product::where('stock', '<=', 5)->get();

        $recentOrders = Order::with('user', 'items')->latest()->take(5)->get();
        $topSellingProducts = Product::where('is_active', true)->orderByDesc('sold_count')->take(5)->get();

        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'total_orders' => $totalOrders,
                'total_revenue' => $totalRevenue,
                'active_products' => $activeProductsCount,
                'low_stock_count' => $lowStockProducts->count(),
            ],
            'recentOrders' => $recentOrders,
            'lowStockProducts' => $lowStockProducts,
            'topSellingProducts' => $topSellingProducts,
        ]);
    }
}
