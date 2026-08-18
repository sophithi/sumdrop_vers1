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

            main,
            .sidebar,
            nav,
            .navbar,
            header,
            footer {
                display: none !important;
            }
        }

        /* On-screen only — the thermal-print rules above are untouched and still
           control what actually comes out of the receipt printer. */
        @media screen {
            .receipt-wrapper {
                max-width: 420px;
                padding: 2.5rem 1rem;
            }

            .receipt {
                border-radius: 20px;
                border: 1px solid var(--border, #e2e8f0);
                box-shadow: var(--shadow, 0 18px 40px rgba(15, 23, 42, 0.08));
                padding: 2rem 1.75rem;
                font-size: 0.95rem;
                line-height: 1.5;
            }

            .receipt-store {
                font-size: 1.4rem;
                margin-bottom: 0.35rem;
            }

            .receipt-address,
            .receipt-phone {
                font-size: 0.85rem;
                color: #64748b;
                margin-bottom: 0.15rem;
            }

            .divider {
                border-top: 1px dashed #cbd5e1;
                margin: 0.9rem 0;
            }

            .receipt-order-info {
                font-size: 0.85rem;
                margin-bottom: 0.4rem;
                line-height: 1.6;
            }

            .receipt-items {
                margin: 0.6rem 0;
            }

            .receipt-item-row {
                font-size: 0.92rem;
                margin-bottom: 0.55rem;
                line-height: 1.4;
            }

            .receipt-item-name {
                padding-right: 0.6rem;
            }

            .receipt-item-price {
                width: auto;
                min-width: 60px;
                font-size: 0.92rem;
            }

            .receipt-totals {
                margin: 0.7rem 0;
            }

            .receipt-total-row {
                font-size: 0.9rem;
                margin-bottom: 0.4rem;
                color: #64748b;
            }

            .receipt-total-final {
                font-size: 1.2rem;
                margin-top: 0.5rem;
                padding-top: 0.6rem;
                border-top: 1px solid var(--border, #e2e8f0);
                color: var(--accent, #2563eb);
            }

            .receipt-payment {
                font-size: 0.85rem;
                margin-bottom: 0.4rem;
            }

            .receipt-thank-you {
                font-size: 0.95rem;
                margin: 1rem 0 0.5rem;
            }

            .receipt-actions {
                gap: 0.6rem;
                margin-top: 1.25rem;
            }

            .receipt-actions .btn {
                font-size: 0.9rem;
                padding: 0.65rem 1.3rem;
                border-radius: 10px;
            }
        }
    </style>
@endpush

@section('content')
    @php
        $paymentLabel = match ($order->payment_method) {
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
            <div class="receipt-order-info">{{ __('orders.receipt_number_label') }} {{ $order->receipt_number }}
                {{ __('orders.barista_label') }}
                {{ $order->user->name ?? __('orders.staff_fallback') }}<br>{{ __('orders.date_label') }}
                {{ $order->created_at->format('m/d/Y') }} {{ __('orders.time_label') }}
                {{ $order->created_at->format('h:i:s A') }}</div>
            <div class="divider"></div>
            <div class="receipt-items">
                @foreach($order->items as $item)
                    <div class="receipt-item-row">
                        <div class="receipt-item-name">
                            {{ $item->product->name }}{{ $item->saleUnitLabel() ? ' (' . $item->saleUnitLabel() . ')' : '' }}
                            (x{{ $item->quantity }})</div>
                        <div class="receipt-item-price">{{ $item->getFormattedTotal() }}</div>
                    </div>
                @endforeach
            </div>
            <div class="divider"></div>
            <div class="receipt-totals">
                <div class="receipt-total-row">
                    <span>{{ __('orders.receipt_subtotal') }}</span><span>{{ $order->getFormattedSubtotal() }}</span></div>
                <div class="receipt-total-row"><span>{{ __('orders.receipt_tax') }}</span><span>-</span></div>
                <div class="receipt-total-final">
                    <span>{{ __('orders.receipt_total') }}</span><span>{{ $order->getFormattedTotal() }}</span></div>
            </div>
            <div class="divider"></div>
            <div class="receipt-payment">{{ __('orders.receipt_payment') }} {{ strtoupper($paymentLabel) }}</div>
            <div class="divider"></div>
            <div class="receipt-thank-you">{{ __('orders.thank_you') }}</div>
           
        </div>
         <div class="receipt-actions"><button type="button" class="btn btn-secondary" onclick="window.print()">🖨️
                    {{ __('common.print') }}</button><a href="{{ route('dashboard') }}" class="btn btn-success">✓
                    {{ __('common.done') }}</a></div>
    </div>
@endsection