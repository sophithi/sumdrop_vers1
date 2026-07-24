<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Purchase;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $monthStart = Carbon::now()->startOfMonth();
        
        // Get filter parameters
        $filterDay = request('day') ? intval(request('day')) : null;
        $filterMonth = request('month') ? intval(request('month')) : null;
        
        // Build date filter query
        $filterDate = null;
        $filterPeriodLabel = 'All Time';
        
        if ($filterDay && $filterMonth) {
            // Both day and month specified
            $year = Carbon::now()->year;
            $filterDate = Carbon::createFromDate($year, $filterMonth, $filterDay)->startOfDay();
            $filterPeriodLabel = $filterDate->format('F d, Y');
        } elseif ($filterMonth) {
            // Only month specified
            $year = Carbon::now()->year;
            $filterDate = Carbon::createFromDate($year, $filterMonth, 1)->startOfMonth();
            $filterPeriodLabel = $filterDate->format('F Y');
        }

        $orders = Order::with('user')->latest()->take(20)->get();

        // Base summary data
        $baseQuery = Order::query();
        if ($filterDate && $filterDay && $filterMonth) {
            $baseQuery = $baseQuery->whereDate('created_at', $filterDate);
        } elseif ($filterDate && $filterMonth) {
            $baseQuery = $baseQuery->whereBetween('created_at', [$filterDate, $filterDate->copy()->endOfMonth()]);
        }

        $summary = [
            'orders_count' => (clone $baseQuery)->count(),
            'today_orders' => Order::whereDate('created_at', $today)->count(),
            'month_orders' => Order::whereBetween('created_at', [$monthStart, Carbon::now()])->count(),
            'total_sales_usd' => (clone $baseQuery)->where('currency', 'usd')->sum('total'),
            'total_sales_khr' => (clone $baseQuery)->where('currency', 'khr')->sum('total'),
            'today_sales_usd' => Order::where('currency', 'usd')->whereDate('created_at', $today)->sum('total'),
            'today_sales_khr' => Order::where('currency', 'khr')->whereDate('created_at', $today)->sum('total'),
            'month_sales_usd' => Order::where('currency', 'usd')->whereBetween('created_at', [$monthStart, Carbon::now()])->sum('total'),
            'month_sales_khr' => Order::where('currency', 'khr')->whereBetween('created_at', [$monthStart, Carbon::now()])->sum('total'),
            'cash_count' => (clone $baseQuery)->where('payment_method', 'cash')->count(),
            'mobile_count' => (clone $baseQuery)->where('payment_method', 'mobile')->count(),
            'cash_total_usd' => (clone $baseQuery)->where('payment_method', 'cash')->where('currency', 'usd')->sum('total'),
            'cash_total_khr' => (clone $baseQuery)->where('payment_method', 'cash')->where('currency', 'khr')->sum('total'),
            'mobile_total_usd' => (clone $baseQuery)->where('payment_method', 'mobile')->where('currency', 'usd')->sum('total'),
            'mobile_total_khr' => (clone $baseQuery)->where('payment_method', 'mobile')->where('currency', 'khr')->sum('total'),
            'purchase_count' => \App\Models\Purchase::count(),
            'purchase_total_usd' => \App\Models\Purchase::where('currency', 'usd')->sum('total'),
            'purchase_total_khr' => \App\Models\Purchase::where('currency', 'khr')->sum('total'),
        ];

        // Get daily sales data for chart
        $dailySalesQuery = Order::select(DB::raw('DATE(created_at) as date'), 'currency', DB::raw('SUM(total) as total'))
            ->groupBy(DB::raw('DATE(created_at)'), 'currency');
        
        if ($filterDate && $filterMonth) {
            $dailySalesQuery = $dailySalesQuery->whereBetween('created_at', [$filterDate, $filterDate->copy()->endOfMonth()]);
        }
        
        $dailySales = $dailySalesQuery->orderBy('date')->get();
        
        // Get payment method breakdown
        $paymentBreakdownQuery = Order::select('payment_method', DB::raw('SUM(total) as total'), 'currency', DB::raw('COUNT(*) as count'));
        
        if ($filterDate && $filterDay && $filterMonth) {
            $paymentBreakdownQuery = $paymentBreakdownQuery->whereDate('created_at', $filterDate);
        } elseif ($filterDate && $filterMonth) {
            $paymentBreakdownQuery = $paymentBreakdownQuery->whereBetween('created_at', [$filterDate, $filterDate->copy()->endOfMonth()]);
        }
        
        $paymentBreakdown = $paymentBreakdownQuery->groupBy('payment_method', 'currency')->get();

        $todayProductSales = OrderItem::select('product_id', 'orders.currency', DB::raw('SUM(quantity) as quantity_sold'), DB::raw('SUM(quantity * order_items.price) as sales_total'))
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->whereDate('orders.created_at', $today)
            ->groupBy('product_id', 'orders.currency')
            ->with('product')
            ->orderByDesc('quantity_sold')
            ->take(10)
            ->get();

        $monthProductSales = OrderItem::select('product_id', 'orders.currency', DB::raw('SUM(quantity) as quantity_sold'), DB::raw('SUM(quantity * order_items.price) as sales_total'))
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->whereBetween('orders.created_at', [$monthStart, Carbon::now()])
            ->groupBy('product_id', 'orders.currency')
            ->with('product')
            ->orderByDesc('quantity_sold')
            ->take(10)
            ->get();

        // Get available days and months for filters
        $availableDays = Order::selectRaw('DISTINCT DAY(created_at) as day')
            ->orderBy('day')
            ->pluck('day')
            ->toArray();
        
        $availableMonths = Order::selectRaw('DISTINCT MONTH(created_at) as month')
            ->orderBy('month')
            ->pluck('month')
            ->toArray();

        return view('reports.index', compact('orders', 'summary', 'todayProductSales', 'monthProductSales', 'dailySales', 'paymentBreakdown', 'filterDay', 'filterMonth', 'filterPeriodLabel', 'availableDays', 'availableMonths'));
    }
}
