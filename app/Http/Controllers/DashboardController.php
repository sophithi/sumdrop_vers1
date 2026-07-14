<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        if ($request->user()?->role === 'admin') {
            return $this->admin();
        }

        return $this->staff();
    }

    public function admin()
    {
        $today = Carbon::today();
        $startOfWeek = Carbon::now()->startOfWeek();
        $startOfMonth = Carbon::now()->startOfMonth();

        // Today's metrics
        $todayOrders = Order::whereDate('created_at', $today)->count();
        $todaySales = Order::whereDate('created_at', $today)->sum('total');
        $todayCustomers = Order::whereDate('created_at', $today)->distinct('user_id')->count();
        $todaySold = OrderItem::whereHas('order', fn($query) => $query->whereDate('created_at', $today))->sum('quantity');

        // Weekly metrics
        $weeklySales = Order::whereBetween('created_at', [$startOfWeek, now()])->sum('total');
        $weeklyOrders = Order::whereBetween('created_at', [$startOfWeek, now()])->count();

        // Monthly metrics
        $monthlySales = Order::whereBetween('created_at', [$startOfMonth, now()])->sum('total');
        $monthlyOrders = Order::whereBetween('created_at', [$startOfMonth, now()])->count();

        // Growth comparison
        $yesterdaySales = Order::whereDate('created_at', $today->copy()->subDay())->sum('total');
        $growthPercent = $yesterdaySales > 0 ? (($todaySales - $yesterdaySales) / $yesterdaySales * 100) : 0;

        // Low stock alert (if products have a stock field)
        $lowStockCount = Product::where('status', true)->whereRaw('stock < 10')->count();

        // Best sellers
        $bestSellers = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_quantity'))
            ->with('product')
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->take(5)
            ->get();

        // Payment methods breakdown
        $paymentMethods = Order::select('payment_method', DB::raw('COUNT(*) as count'), DB::raw('SUM(total) as total'))
            ->groupBy('payment_method')
            ->get();

        // Recent orders with full details
        $recentOrders = Order::with('user', 'items.product')->latest()->take(8)->get();

        // Daily sales chart data (last 7 days)
        $dailySalesData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = $today->copy()->subDays($i);
            $sales = Order::whereDate('created_at', $date)->sum('total');
            $dailySalesData[] = [
                'date' => $date->format('M d'),
                'sales' => $sales,
            ];
        }

        // Payment method pie chart data
        $paymentChartData = $paymentMethods->map(fn($method) => [
            'name' => ucfirst($method->payment_method),
            'value' => $method->count,
            'total' => $method->total,
        ])->toArray();

        return view('dashboard.admin', compact(
            'todayOrders',
            'todaySales',
            'todayCustomers',
            'todaySold',
            'weeklySales',
            'weeklyOrders',
            'monthlySales',
            'monthlyOrders',
            'growthPercent',
            'yesterdaySales',
            'lowStockCount',
            'bestSellers',
            'paymentMethods',
            'recentOrders',
            'dailySalesData',
            'paymentChartData'
        ));
    }

    public function staff()
    {
        $products = Product::where('status', true)->latest()->get();

        return view('dashboard.staff', compact('products'));
    }
}
