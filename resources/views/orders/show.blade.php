@extends('layouts.admin')

@section('title', __('menu.receipt'))
@section('page-title', __('menu.receipt'))
@section('page-subtitle', __('orders.order_hash') . $order->id)

@section('page-actions')
    <!-- <a href="{{ route('dashboard') }}" class="btn btn-secondary">Back to POS</a> -->
@endsection

@push('styles')
<style>
    .receipt-wrapper {
        max-width: 105mm;
        margin: 0 auto;
        padding: 0.5rem;
    }
    .receipt {
        background: white;
        padding: 0.5rem 0.4rem;
        font-family: 'Courier New', 'Consolas', 'Leelawadee UI', monospace;
        font-size: 0.73rem;
        line-height: 1.2;
        text-align: center;
    }
    .receipt-store {
        font-weight: bold;
        font-size: 0.88rem;
        margin-bottom: 0.1rem;
        letter-spacing: 0.05em;
    }
    .receipt-address {
        font-size: 0.68rem;
        margin-bottom: 0.05rem;
        line-height: 1.2;
    }
    .receipt-phone {
        font-size: 0.68rem;
        margin-bottom: 0.15rem;
    }
    .divider {
        border: none;
        border-top: 1px dashed #333;
        margin: 0.12rem 0;
        padding: 0;
    }
    .receipt-order-info {
        margin-bottom: 0.1rem;
        font-size: 0.68rem;
        line-height: 1.2;
        text-align: left;
        padding: 0 0.1rem;
    }
    .receipt-items {
        text-align: left;
        margin: 0.1rem 0;
        padding: 0.08rem 0.1rem;
    }
    .receipt-item-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.08rem;
        font-size: 0.73rem;
        line-height: 1.1;
    }
    .receipt-item-name {
        flex: 1;
        text-align: left;
        word-break: break-word;
        padding-right: 0.1rem;
    }
    .receipt-item-price {
        width: 45px;
        text-align: right;
        font-weight: bold;
        font-size: 0.73rem;
        flex-shrink: 0;
    }
    .receipt-totals {
        margin: 0.12rem 0;
        text-align: right;
        padding: 0 0.1rem;
    }
    .receipt-total-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.08rem;
        font-size: 0.73rem;
        line-height: 1.1;
    }
    .receipt-total-final {
        display: flex;
        justify-content: space-between;
        font-weight: bold;
        font-size: 0.8rem;
        margin-top: 0.08rem;
        line-height: 1.1;
        padding-top: 0.05rem;
    }
    .receipt-payment {
        font-size: 0.68rem;
        margin-bottom: 0.08rem;
        text-align: left;
        padding: 0 0.1rem;
    }
    .receipt-thank-you {
        font-size: 0.73rem;
        font-weight: normal;
        margin: 0.2rem 0;
        color: #555;
    }
    .receipt-actions {
        display: flex;
        justify-content: center;
        gap: 0.3rem;
        flex-wrap: wrap;
        margin-top: 0.3rem;
    }
    .btn { 
        font-size: 0.75rem;
        padding: 0.2rem 0.5rem;
    }
    @media print {
        * {
            margin: 0;
            padding: 0;
        }
        body { 
            background: white;
            margin: 0;
            padding: 0;
        }
        html {
            width: 105mm;
            height: 240mm;
            margin: 0;
            padding: 0;
        }
        .receipt-wrapper { 
            max-width: 105mm;
            width: 105mm;
            height: 240mm;
            margin: 0;
            padding: 0;
        }
        .receipt { 
            padding: 0.2rem;
            box-shadow: none;
            border: none;
            background: white;
            margin: 0;
        }
        .receipt-actions { 
            display: none;
        }
        main, .sidebar, nav, .navbar, header, footer {
            display: none !important;
        }
    }
</style>
@endpush

@section('content')
    @php
        $paymentLabel = match($order->payment_method) {
            'cash' => __('common.payment_cash'),
            'bank' => __('common.payment_bank'),
            'mobile' => __('common.payment_mobile'),
            default => ucfirst($order->payment_method),
        };
    @endphp
    <div class="receipt-wrapper">
        <div class="receipt">
            <div class="receipt-store">SUMDROP COFFEE</div>
            <div class="receipt-address">123 Main Street<br>Phnom Penh, Cambodia</div>
            <div class="receipt-phone">(855) 123-4567</div>
            <div class="divider"></div>
            <div class="receipt-order-info">{{ __('orders.receipt_number_label') }} {{ $order->receipt_number }} {{ __('orders.barista_label') }} {{ $order->user->name ?? __('orders.staff_fallback') }}<br>{{ __('orders.date_label') }} {{ $order->created_at->format('m/d/Y') }}  {{ __('orders.time_label') }} {{ $order->created_at->format('h:i:s A') }}</div>
            <div class="divider"></div>
            <div class="receipt-items">
@foreach($order->items as $item)
<div class="receipt-item-row"><div class="receipt-item-name">{{ $item->product->name }}{{ $item->saleUnitLabel() ? ' (' . $item->saleUnitLabel() . ')' : '' }} (x{{ $item->quantity }})</div><div class="receipt-item-price">{{ $item->getFormattedTotal() }}</div></div>
@endforeach
</div>
            <div class="divider"></div>
            <div class="receipt-totals">
<div class="receipt-total-row"><span>{{ __('orders.receipt_subtotal') }}</span><span>{{ $order->getFormattedSubtotal() }}</span></div>
<div class="receipt-total-row"><span>{{ __('orders.receipt_tax') }}</span><span>-</span></div>
<div class="receipt-total-final"><span>{{ __('orders.receipt_total') }}</span><span>{{ $order->getFormattedTotal() }}</span></div>
</div>
            <div class="divider"></div>
            <div class="receipt-payment">{{ __('orders.receipt_payment') }} {{ strtoupper($paymentLabel) }}</div>
            <div class="divider"></div>
            <div class="receipt-thank-you">{{ __('orders.thank_you') }}</div>
            <div class="receipt-actions"><button type="button" class="btn btn-secondary" onclick="window.print()">🖨️ {{ __('common.print') }}</button><a href="{{ route('dashboard') }}" class="btn btn-success">✓ {{ __('common.done') }}</a></div>
        </div>
    </div>
@endsection
