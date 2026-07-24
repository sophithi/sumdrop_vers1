@extends('layouts.admin')

@section('title', __('dashboard.title'))
@section('page-title', __('dashboard.overview'))
@section('page-subtitle', __('dashboard.live_metrics'))

@push('styles')
<style>
    .currency-selector {
        display: inline-flex;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        overflow: hidden;
        background: #ffffff;
    }

    .currency-selector button {
        border: none;
        background: #ffffff;
        padding: 0.5rem 0.9rem;
        cursor: pointer;
        color: #64748b;
        font-weight: 600;
        font-size: 0.85rem;
    }

    .currency-selector button.active {
        background: #2563eb;
        color: #ffffff;
    }

    .currency-selector button:hover:not(.active) {
        background: #f8fafc;
    }

    .overview-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 1.25rem;
        margin-bottom: 1.8rem;
    }

    .metric-card {
        border-radius: 16px;
        padding: 1.5rem;
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.06);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .metric-card:hover {
        box-shadow: 0 8px 30px rgba(15, 23, 42, 0.12);
        transform: translateY(-2px);
        border-color: #cbd5e1;
    }

    .metric-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(37, 99, 235, 0.05) 0%, transparent 70%);
        border-radius: 50%;
    }

    .metric-card-content {
        position: relative;
        z-index: 1;
    }

    .metric-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: grid;
        place-items: center;
        font-size: 1.8rem;
        margin-bottom: 0.75rem;
    }

    .metric-card.primary .metric-icon { background: #eff6ff; color: #2563eb; }
    .metric-card.success .metric-icon { background: #dcfce7; color: #16a34a; }
    .metric-card.warning .metric-icon { background: #fef3c7; color: #d97706; }
    .metric-card.info .metric-icon { background: #e0e7ff; color: #6366f1; }

    .metric-label {
        display: block;
        font-size: 0.85rem;
        color: #64748b;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.5rem;
    }

    .metric-value {
        display: block;
        font-size: 2rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0.5rem;
    }

    .metric-desc {
        font-size: 0.85rem;
        color: #64748b;
        line-height: 1.4;
    }

    .metric-growth {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.8rem;
        font-weight: 600;
        margin-top: 0.75rem;
    }

    .metric-growth.positive { color: #16a34a; }
    .metric-growth.negative { color: #dc2626; }

    .stats-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 1.5rem;
        margin-bottom: 1.8rem;
    }

    .panel-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.25rem;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .panel-title {
        margin: 0;
        font-size: 1.15rem;
        font-weight: 700;
        color: #0f172a;
    }

    .panel-actions {
        display: flex;
        gap: 0.5rem;
        align-items: center;
    }

    .time-badge {
        font-size: 0.75rem;
        color: #94a3b8;
        font-weight: 500;
    }

    .table-list th,
    .table-list td {
        padding: 1rem 0;
        border-bottom: 1px solid #e2e8f0;
    }

    .table-list th {
        font-weight: 600;
        color: #475569;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .best-seller {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 0;
        border-bottom: 1px solid #e2e8f0;
        transition: background 0.2s ease;
    }

    .best-seller:hover {
        background: #f8fafc;
        padding: 1rem;
        margin: 0 -1rem;
        border-radius: 8px;
    }

    .best-seller:last-child { border-bottom: none; }

    .best-seller-info strong {
        display: block;
        font-size: 0.95rem;
        color: #0f172a;
        margin-bottom: 0.25rem;
    }

    .best-seller-info span {
        font-size: 0.85rem;
        color: #64748b;
    }

    .best-seller-price {
        font-weight: 700;
        font-size: 1rem;
        color: #2563eb;
    }

    .alert-box {
        padding: 1rem;
        border-radius: 12px;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .alert-warning {
        background: #fef3c7;
        border-left: 4px solid #d97706;
        color: #92400e;
    }

    .empty-state {
        padding: 2rem;
        text-align: center;
        color: #94a3b8;
    }

    .empty-state-icon {
        font-size: 3rem;
        margin-bottom: 0.75rem;
        opacity: 0.5;
    }

    .chart-container {
        position: relative;
        height: 300px;
        margin-bottom: 1rem;
    }

    .dashboard-hero {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        border-radius: 20px;
        padding: 2.5rem;
        margin-bottom: 2.5rem;
        color: white;
        box-shadow: 0 8px 30px rgba(37, 99, 235, 0.15);
    }

    .dashboard-hero-inner {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 2rem;
        flex-wrap: wrap;
    }

    .dashboard-hero h1 {
        margin: 0 0 0.5rem 0;
        font-size: 2.5rem;
        font-weight: 700;
    }

    .dashboard-hero-status {
        text-align: right;
    }

    .quick-actions-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 2.5rem;
    }

    .dashboard-section-header {
        margin-bottom: 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .dashboard-section-header h2 {
        margin: 0;
        font-size: 1.4rem;
        font-weight: 700;
    }

    @media (max-width: 768px) {
        .overview-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .dashboard-hero {
            padding: 1.75rem;
        }

        .dashboard-hero-inner {
            flex-direction: column;
            gap: 1.25rem;
        }

        .dashboard-hero h1 {
            font-size: 1.85rem;
        }

        .dashboard-section-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .metric-value {
            font-size: 1.65rem;
        }
    }

    @media (max-width: 480px) {
        .overview-grid {
            grid-template-columns: 1fr;
        }

        .dashboard-hero {
            padding: 1.25rem;
            margin-bottom: 1.5rem;
        }

        .dashboard-hero h1 {
            font-size: 1.5rem;
        }

        .quick-actions-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
    @php
        $displayCurrency = request('currency', 'khr');
        $currencySymbol = $displayCurrency === 'khr' ? '៛' : '$';
        // $hour = now()->hour;
        // $greeting = $hour < 12 ? __('dashboard.good_morning') : ($hour < 18 ? __('dashboard.good_afternoon') : __('dashboard.good_evening'));
    @endphp

    <!-- Welcome Hero Section -->
    <div class="dashboard-hero">
        <div class="dashboard-hero-inner">
            <div>
               
                <h1>{{ auth()->user()?->name ?? 'Welcome' }}</h1>
                <p style="margin: 0; font-size: 1.1rem; opacity: 0.95;">{{ now()->format('l, F j, Y') }}</p>
            </div>
            <div class="dashboard-hero-status">
                <p style="margin: 0 0 0.5rem 0; font-size: 0.9rem; opacity: 0.9;">System Status</p>
                <div style="display: inline-flex; align-items: center; gap: 0.5rem; background: rgba(255,255,255,0.2); padding: 0.5rem 1rem; border-radius: 8px;">
                    <span style="width: 8px; height: 8px; background: #10b981; border-radius: 50%; display: inline-block;"></span>
                    <span style="font-weight: 600;">Operational</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions Section -->
    <div class="quick-actions-grid">
        <a href="{{ route('products.index') }}" style="background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%); border: 1px solid #bfdbfe; border-radius: 12px; padding: 1.5rem; text-decoration: none; color: #0369a1; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 8px 20px rgba(3, 105, 161, 0.15)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
            <div style="font-weight: 700; font-size: 1.1rem; margin-bottom: 0.25rem;">Manage Menu</div>
            <div style="font-size: 0.9rem; opacity: 0.7;">View & edit products</div>
        </a>
        <a href="{{ route('orders.index') }}" style="background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%); border: 1px solid #86efac; border-radius: 12px; padding: 1.5rem; text-decoration: none; color: #166534; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 8px 20px rgba(22, 101, 52, 0.15)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
            <div style="font-weight: 700; font-size: 1.1rem; margin-bottom: 0.25rem;">View Orders</div>
            <div style="font-size: 0.9rem; opacity: 0.7;">Check order status</div>
        </a>
        <a href="{{ route('receipts.index') }}" style="background: linear-gradient(135deg, #fef3c7 0%, #fed7aa 100%); border: 1px solid #fcd34d; border-radius: 12px; padding: 1.5rem; text-decoration: none; color: #92400e; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 8px 20px rgba(146, 64, 14, 0.15)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
            <div style="font-weight: 700; font-size: 1.1rem; margin-bottom: 0.25rem;">Recent Receipts</div>
            <div style="font-size: 0.9rem; opacity: 0.7;">Print receipts</div>
        </a>
        <a href="{{ route('reports.index') }}" style="background: linear-gradient(135deg, #e0e7ff 0%, #dbeafe 100%); border: 1px solid #c7d2fe; border-radius: 12px; padding: 1.5rem; text-decoration: none; color: #4f46e5; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 8px 20px rgba(79, 70, 229, 0.15)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
            <div style="font-weight: 700; font-size: 1.1rem; margin-bottom: 0.25rem;">View Reports</div>
            <div style="font-size: 0.9rem; opacity: 0.7;">Analytics & insights</div>
        </a>
    </div>

    <!-- Currency Selector -->
    <!-- <div class="dashboard-section-header">
        <h2>Today's Performance</h2>
        <div class="currency-selector">
            <button type="button" id="curr-khr" class="active" onclick="window.location.href='{{ route('dashboard') }}?currency=khr'">៛ KHR (Base)</button>
            <button type="button" id="curr-usd" onclick="window.location.href='{{ route('dashboard') }}?currency=usd'">$ USD</button>
        </div>
    </div> -->

    <!-- Key Metrics -->
    <div class="overview-grid">
        <div class="metric-card primary">
            <div class="metric-card-content">
                <span class="metric-label">{{ __('dashboard.today_sales') }}</span>
                <span class="metric-value">
                    @if($displayCurrency === 'khr')
                        ៛{{ number_format($todaySales ?? 0, 0) }}
                    @else
                        ${{ number_format($todaySales ?? 0, 2) }}
                    @endif
                </span>
                <span class="metric-desc">{{ __('dashboard.revenue_since_midnight') }}</span>
                @if($yesterdaySales > 0)
                    <div class="metric-growth {{ $growthPercent >= 0 ? 'positive' : 'negative' }}">
                        <span>{{ $growthPercent >= 0 ? '↑' : '↓' }} {{ abs($growthPercent) }}%</span> {{ __('dashboard.vs_yesterday') }}
                    </div>
                @endif
            </div>
        </div>

        <div class="metric-card success">
            <div class="metric-card-content">
                <span class="metric-label">{{ __('dashboard.orders_completed') }}</span>
                <span class="metric-value">{{ $todayOrders ?? 0 }}</span>
                <span class="metric-desc">{{ __('dashboard.transactions_processed') }}</span>
            </div>
        </div>

        <div class="metric-card warning">
            <div class="metric-card-content">
                <span class="metric-label">{{ __('dashboard.customers') }}</span>
                <span class="metric-value">{{ $todayCustomers ?? 0 }}</span>
                <span class="metric-desc">{{ __('dashboard.unique_customers') }}</span>
            </div>
        </div>

        <div class="metric-card info">
            <div class="metric-card-content">
                <span class="metric-label">{{ __('dashboard.items_sold') }}</span>
                <span class="metric-value">{{ $todaySold ?? 0 }}</span>
                <span class="metric-desc">{{ __('dashboard.product_quantity_sold') }}</span>
            </div>
        </div>
    </div>

    <!-- Additional Period Metrics -->
    <h2 style="font-size: 1.4rem; font-weight: 700; margin-bottom: 1.5rem; margin-top: 2rem;">Weekly & Monthly Overview</h2>
    <div class="overview-grid" style="margin-bottom: 2rem;">
        <div class="metric-card" style="background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%); border-color: #cffafe;">
            <div class="metric-card-content">
                <span class="metric-label">{{ __('dashboard.weekly_sales') }}</span>
                <span class="metric-value" style="color: #0369a1;">
                    @if($displayCurrency === 'khr')
                        ៛{{ number_format($weeklySales ?? 0, 0) }}
                    @else
                        ${{ number_format($weeklySales ?? 0, 2) }}
                    @endif
                </span>
                <span class="metric-desc">{{ $weeklyOrders ?? 0 }} {{ __('dashboard.orders_this_week') }}</span>
            </div>
        </div>

        <div class="metric-card" style="background: linear-gradient(135deg, #fef3c7 0%, #fed7aa 100%); border-color: #fbbf24;">
            <div class="metric-card-content">
                <span class="metric-label">{{ __('dashboard.monthly_sales') }}</span>
                <span class="metric-value" style="color: #92400e;">
                    @if($displayCurrency === 'khr')
                        ៛{{ number_format($monthlySales ?? 0, 0) }}
                    @else
                        ${{ number_format($monthlySales ?? 0, 2) }}
                    @endif
                </span>
                <span class="metric-desc">{{ $monthlyOrders ?? 0 }} {{ __('dashboard.orders_this_month') }}</span>
            </div>
        </div>

        <div class="metric-card" style="background: linear-gradient(135deg, #f5f3ff 0%, #ede9fe 100%); border-color: #ddd6fe;">
            <div class="metric-card-content">
                <span class="metric-label">{{ __('dashboard.low_stock_items') }}</span>
                <span class="metric-value" style="color: #7c3aed;">{{ $lowStockCount ?? 0 }}</span>
                <span class="metric-desc">{{ __('dashboard.items_need_attention') }}</span>
            </div>
        </div>
    </div>



@endsection
