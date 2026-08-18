@extends('layouts.admin')

@section('title', __('menu.purchases'))
@section('page-title', __('menu.purchases'))
@section('page-subtitle', __('purchases.page_subtitle'))
@section('page-actions')
    <a href="{{ route('purchases.create') }}" class="btn">{{ __('purchases.new_purchase') }}</a>
@endsection

@section('content')
    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <div class="panel" style="margin-bottom:1rem;">
        <div class="table-responsive">
        <table class="table-list" style="width:100%;">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __('purchases.supplier') }}</th>
                    <th>{{ __('purchases.amount') }}</th>
                    <th>{{ __('common.currency') }}</th>
                    <th>{{ __('common.payment') }}</th>
                    <th>{{ __('purchases.user') }}</th>
                    <th>{{ __('common.date') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($purchases as $purchase)
                    <tr>
                        <td>{{ $purchase->id }}</td>
                        <td>{{ $purchase->supplier ?? __('purchases.na') }}</td>
                        <td>{{ $purchase->getFormattedTotal() }}</td>
                        <td>{{ strtoupper($purchase->currency) }}</td>
                        <td>{{ $purchase->payment_method === 'mobile' ? __('common.payment_mobile') : __('common.payment_cash') }}</td>
                        <td>{{ $purchase->user->name ?? __('purchases.system') }}</td>
                        <td>{{ $purchase->created_at->format('Y-m-d') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="field-error" style="text-align:center;">{{ __('purchases.no_purchases_yet') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
