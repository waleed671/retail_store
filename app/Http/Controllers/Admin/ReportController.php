<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $monthStart = now()->startOfMonth();
        $yearStart  = now()->startOfYear();

        // Summary stats
        $revenueMonth = (float) Order::where('created_at', '>=', $monthStart)
            ->whereNotIn('status', ['cancelled', 'returned'])
            ->sum('total');

        $revenueYear = (float) Order::where('created_at', '>=', $yearStart)
            ->whereNotIn('status', ['cancelled', 'returned'])
            ->sum('total');

        $totalOrders = Order::whereNotIn('status', ['cancelled', 'returned'])->count();

        $avgOrderValue = $totalOrders > 0
            ? round($revenueYear / $totalOrders, 2)
            : 0;

        // Top 10 products by revenue
        $topProducts = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->whereNotIn('orders.status', ['cancelled', 'returned'])
            ->select(
                'order_items.product_name',
                DB::raw('SUM(order_items.quantity) as total_qty'),
                DB::raw('SUM(order_items.subtotal) as total_revenue')
            )
            ->groupBy('order_items.product_name')
            ->orderByDesc('total_revenue')
            ->limit(10)
            ->get();

        // Last 30 days daily revenue
        $dailySales = Order::select(
                DB::raw('DATE(created_at) as sale_date'),
                DB::raw('COUNT(*) as orders_count'),
                DB::raw('SUM(total) as daily_revenue')
            )
            ->where('created_at', '>=', now()->subDays(29)->startOfDay())
            ->whereNotIn('status', ['cancelled', 'returned'])
            ->groupBy('sale_date')
            ->orderBy('sale_date')
            ->get()
            ->keyBy('sale_date');

        // Fill missing days
        $last30Days = collect();
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $last30Days->push((object) [
                'date'          => $date,
                'orders_count'  => $dailySales[$date]->orders_count  ?? 0,
                'daily_revenue' => $dailySales[$date]->daily_revenue ?? 0,
            ]);
        }

        // Payment method breakdown
        $paymentBreakdown = Order::select('payment_method', DB::raw('COUNT(*) as count'), DB::raw('SUM(total) as total'))
            ->whereNotIn('status', ['cancelled', 'returned'])
            ->groupBy('payment_method')
            ->get();

        return view('admin.reports.index', compact(
            'revenueMonth', 'revenueYear', 'totalOrders', 'avgOrderValue',
            'topProducts', 'last30Days', 'paymentBreakdown'
        ));
    }
}
