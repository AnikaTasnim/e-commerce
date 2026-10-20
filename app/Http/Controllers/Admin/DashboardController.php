<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $metrics = [
            'products' => Product::count(),
            'active_products' => Product::where('status', 'active')->count(),
            'orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'revenue' => (float) Order::whereNotIn('status', ['cancelled'])->sum('total'),
            'customers' => Order::distinct('phone')->count('phone'),
        ];
        $recentOrders = Order::withCount('items')->latest()->limit(8)->get();

        return view('admin.dashboard', compact('metrics', 'recentOrders'));
    }
}
