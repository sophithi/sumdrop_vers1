@extends('layouts.admin')
@php use App\Helpers\CurrencyHelper; @endphp

@section('title', __('reports.title'))
@section('page-title', __('reports.title'))
@section('page-subtitle', __('reports.page_subtitle'))
@section('page-actions')
    <!-- <a href="{{ route('dashboard') }}" class="btn btn-secondary">Back to POS</a> -->
@endsection

@push('styles')
<style>
    .filter-section {
        background: #f8fafc;
        padding: 1.5rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        border: 1px solid #e2e8f0;
    }

    .filter-section h3 {
        margin: 0 0 1rem 0;
        color: #0f172a;
        font-size: 1rem;
    }

    .filter-controls {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 1rem;
        align-items: end;
    }

    .filter-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        align-items: center;
    }

    .report-chart-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
        align-items: start;
    }

    @media (max-width: 768px) {
        .report-chart-grid {
            grid-template-columns: 1fr;
        }

        .chart-container {
            height: 240px;
        }
    }

    .filter-controls label {
        display: block;
        font-size: 0.85rem;
        font-weight: 600;
        margin-bottom: 0.4rem;
        color: #475569;
    }

    .filter-controls select {
        width: 100%;
        padding: 0.6rem;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        font-size: 0.9rem;
    }

    .chart-container {
        position: relative;
        height: 300px;
        margin-bottom: 2rem;
    }

    .stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 1rem;
        margin-bottom: 1rem;
    }
    .stat {
        padding: 1rem;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        box-shadow: 0 12px 24px rgba(15, 23, 42, 0.05);
    }
    .stat .label { display: block; color: #64748b; margin-bottom: 0.5rem; font-size: 0.85rem; }
    .stat .value { font-size: 1.4rem; font-weight: 700; }
    .report-card { margin-bottom: 1rem; }
</style>
@endpush

@section('content')
    <!-- Date Filter -->
    <div class="filter-section">
        <h3>{{ __('reports.filter_by_date') }}</h3>
        <form method="GET" action="{{ route('reports.index') }}" class="filter-controls">
            <div>
                <label>{{ __('reports.day') }}</label>
                <select name="day">
                    <option value="">{{ __('reports.all_days') }}</option>
                    @foreach($availableDays as $day)
                        <option value="{{ $day }}" {{ request('day') == $day ? 'selected' : '' }}>
                            {{ str_pad($day, 2, '0', STR_PAD_LEFT) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label>{{ __('reports.month') }}</label>
                <select name="month">
                    <option value="">{{ __('reports.all_months') }}</option>
                    @foreach($availableMonths as $month)
                        <option value="{{ $month }}" {{ request('month') == $month ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::createFromFormat('m', $month)->format('F') }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-actions">
                <button type="submit" style="padding: 0.6rem 1.2rem; background: #2563eb; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">{{ __('common.filter') }}</button>
                <a href="{{ route('reports.index') }}" style="padding: 0.6rem 1rem; background: #e2e8f0; color: #0f172a; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; text-decoration: none; display: inline-block;">{{ __('reports.reset') }}</a>
            </div>
        </form>
        @if(request()->filled('day') || request()->filled('month'))
            <div style="background: #eff6ff; padding: 0.75rem 1rem; border-radius: 8px; margin-top: 1rem; border-left: 4px solid #2563eb; color: #0c4a6e; font-weight: 500;">
                {{ __('reports.showing_data_for') }} <strong>{{ $filterPeriodLabel }}</strong>
            </div>
        @endif
    </div>

    <!-- Summary Stats -->
    <section class="stats">
        <div class="stat"><div class="label">{{ __('reports.total_orders') }}</div><div class="value">{{ $summary['orders_count'] }}</div></div>
        <div class="stat"><div class="label">{{ __('reports.today_orders') }}</div><div class="value">{{ $summary['today_orders'] }}</div></div>
        <div class="stat"><div class="label">{{ __('reports.month_orders') }}</div><div class="value">{{ $summary['month_orders'] }}</div></div>
        <div class="stat"><div class="label">{{ __('menu.purchases') }}</div><div class="value">{{ $summary['purchase_count'] }}</div></div>
    </section>

    <!-- Sales Overview -->
    <section class="report-card panel">
        <h3>{{ __('reports.sales_overview') }}</h3>
        <div class="stats" style="margin-top:1rem;">
            <div class="stat"><div class="label">{{ __('reports.total_usd') }}</div><div class="value">{{ CurrencyHelper::format($summary['total_sales_usd'], 'usd') }}</div></div>
            <div class="stat"><div class="label">{{ __('reports.total_khr') }}</div><div class="value">{{ CurrencyHelper::format($summary['total_sales_khr'], 'khr') }}</div></div>
            <div class="stat"><div class="label">{{ __('reports.today_usd') }}</div><div class="value">{{ CurrencyHelper::format($summary['today_sales_usd'], 'usd') }}</div></div>
            <div class="stat"><div class="label">{{ __('reports.today_khr') }}</div><div class="value">{{ CurrencyHelper::format($summary['today_sales_khr'], 'khr') }}</div></div>
        </div>
    </section>

    <!-- Daily Sales Chart -->
    @if($dailySales->count() > 0)
    <section class="panel">
        <h3>{{ __('reports.daily_sales_trend') }}</h3>
        <div class="chart-container">
            <canvas id="dailySalesChart"></canvas>
        </div>
    </section>
    @endif

    <!-- Payment Method Chart -->
    @if($paymentBreakdown->count() > 0)
    <section class="panel">
        <h3>{{ __('reports.payment_method_breakdown') }}</h3>
        <div class="report-chart-grid">
            <div class="chart-container">
                <canvas id="paymentMethodChart"></canvas>
            </div>
            <div>
                <table class="table-list" style="width:100%;">
                    <thead>
                        <tr><th>{{ __('reports.method') }}</th><th>{{ __('reports.count') }}</th><th>{{ __('reports.total_usd') }}</th><th>{{ __('reports.total_khr') }}</th></tr>
                    </thead>
                    <tbody>
                        @foreach(['cash' => __('common.payment_cash'), 'mobile' => __('common.payment_mobile')] as $key => $label)
                            <tr>
                                <td>{{ $label }}</td>
                                <td>{{ $summary["{$key}_count"] }}</td>
                                <td>{{ CurrencyHelper::format($summary["{$key}_total_usd"], 'usd') }}</td>
                                <td>{{ CurrencyHelper::format($summary["{$key}_total_khr"], 'khr') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    @endif

    <section class="panel">
        <h3>{{ __('reports.product_sales_today') }}</h3>
        <table class="table-list" style="width:100%; margin-top:1rem;">
            <thead><tr><th>{{ __('common.product') }}</th><th>{{ __('reports.qty_sold') }}</th><th>{{ __('reports.revenue') }}</th></tr></thead>
            <tbody>
                @forelse($todayProductSales as $item)
                    <tr>
                        <td>{{ $item->product->name ?? __('reports.unknown_product') }}</td>
                        <td>{{ $item->quantity_sold }}</td>
                        <td>{{ CurrencyHelper::format($item->sales_total, $item->currency) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="field-error" style="text-align:center;">{{ __('reports.no_product_sales_today') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </section>

    <section class="panel">
        <h3>{{ __('reports.product_sales_this_month') }}</h3>
        <table class="table-list" style="width:100%; margin-top:1rem;">
            <thead><tr><th>{{ __('common.product') }}</th><th>{{ __('reports.qty_sold') }}</th><th>{{ __('reports.revenue') }}</th></tr></thead>
            <tbody>
                @forelse($monthProductSales as $item)
                    <tr>
                        <td>{{ $item->product->name ?? __('reports.unknown_product') }}</td>
                        <td>{{ $item->quantity_sold }}</td>
                        <td>{{ CurrencyHelper::format($item->sales_total, $item->currency) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="field-error" style="text-align:center;">{{ __('reports.no_product_sales_month') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </section>


    @php
        $reportsText = [
            'khr' => __('reports.khr_label'),
            'usd' => __('reports.usd_label'),
            'cash' => __('common.payment_cash'),
            'mobile' => __('common.payment_mobile'),
            'bank' => __('common.payment_bank'),
        ];
    @endphp

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <script>
        const reportsText = @json($reportsText);

        // Prepare data for Daily Sales Chart
        const dailySalesData = @json($dailySales);

        if (dailySalesData.length > 0) {
            const dates = [];
            const khrValues = [];
            const usdValues = [];

            dailySalesData.forEach(item => {
                const dateStr = item.date;
                if (!dates.includes(dateStr)) {
                    dates.push(dateStr);
                    khrValues.push(0);
                    usdValues.push(0);
                }
                const index = dates.indexOf(dateStr);
                if (item.currency === 'khr') {
                    khrValues[index] = item.total;
                } else {
                    usdValues[index] = item.total;
                }
            });

            const dailySalesCtx = document.getElementById('dailySalesChart');
            if (dailySalesCtx) {
                new Chart(dailySalesCtx, {
                    type: 'line',
                    data: {
                        labels: dates.map(d => new Date(d).toLocaleDateString('en-US', { month: 'short', day: 'numeric' })),
                        datasets: [
                            {
                                label: reportsText.khr,
                                data: khrValues,
                                borderColor: '#2563eb',
                                backgroundColor: 'rgba(37, 99, 235, 0.1)',
                                tension: 0.4,
                                fill: true
                            },
                            {
                                label: reportsText.usd,
                                data: usdValues,
                                borderColor: '#10b981',
                                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                                tension: 0.4,
                                fill: true
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top',
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }
        }

        // Payment Method Chart
        const paymentData = @json($paymentBreakdown);

        if (paymentData.length > 0) {
            const methods = [];
            const counts = [];
            const colors = ['#2563eb', '#10b981'];

            paymentData.forEach((item, idx) => {
                if (methods.indexOf(item.payment_method) === -1) {
                    methods.push(item.payment_method);
                    counts.push(0);
                }
                const index = methods.indexOf(item.payment_method);
                counts[index] += item.count;
            });

            const paymentCtx = document.getElementById('paymentMethodChart');
            if (paymentCtx) {
                new Chart(paymentCtx, {
                    type: 'doughnut',
                    data: {
                        labels: methods.map(m => reportsText[m] || (m.charAt(0).toUpperCase() + m.slice(1))),
                        datasets: [{
                            data: counts,
                            backgroundColor: colors.slice(0, methods.length),
                            borderWidth: 2,
                            borderColor: '#ffffff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                            }
                        }
                    }
                });
            }
        }
    </script>
    @endpush
@endsection
