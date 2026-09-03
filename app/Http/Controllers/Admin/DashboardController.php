<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today      = now()->startOfDay();
        $monthStart = now()->startOfMonth();

        $revenueMonth = (float) Order::where('created_at', '>=', $monthStart)
            ->whereNotIn('status', ['cancelled', 'returned'])
            ->sum('total');

        $expensesMonth = (float) Expense::whereYear('expense_date', now()->year)
            ->whereMonth('expense_date', now()->month)
            ->sum('amount');

        $netProfit = $revenueMonth - $expensesMonth;

        $stats = [
            'orders_today'    => Order::where('created_at', '>=', $today)->count(),
            'revenue_month'   => $revenueMonth,
            'pending_orders'  => Order::where('status', 'pending')->count(),
            'low_stock'       => Product::where('stock', '<=', 5)->where('is_active', true)->count(),
            'total_products'  => Product::count(),
            'total_customers' => User::where('role', 'customer')->count(),
            'total_vendors'   => Vendor::where('is_active', true)->count(),
            'expenses_month'  => $expensesMonth,
            'net_profit'      => $netProfit,
        ];

        $recentOrders = Order::with('user')->latest()->take(8)->get();

        $topProducts = DB::table('order_items')
            ->select('product_name', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(subtotal) as total_revenue'))
            ->groupBy('product_name')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        $lowStockProducts = Product::where('stock', '<=', 5)
            ->where('is_active', true)
            ->orderBy('stock')
            ->take(8)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'topProducts', 'lowStockProducts'));
    }
}
