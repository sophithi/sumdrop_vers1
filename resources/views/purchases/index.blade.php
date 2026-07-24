@extends('layouts.admin')

@section('title', 'Purchases')
@section('page-title', 'Purchases')
@section('page-subtitle', 'Review purchase history and supplier payments.')
@section('page-actions')
    <a href="{{ route('purchases.create') }}" class="btn">New Purchase</a>
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
                    <th>Supplier</th>
                    <th>Amount</th>
                    <th>Currency</th>
                    <th>Payment</th>
                    <th>User</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($purchases as $purchase)
                    <tr>
                        <td>{{ $purchase->id }}</td>
                        <td>{{ $purchase->supplier ?? 'N/A' }}</td>
                        <td>{{ $purchase->getFormattedTotal() }}</td>
                        <td>{{ strtoupper($purchase->currency) }}</td>
                        <td>{{ ucfirst($purchase->payment_method) }}</td>
                        <td>{{ $purchase->user->name ?? 'System' }}</td>
                        <td>{{ $purchase->created_at->format('Y-m-d') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="field-error" style="text-align:center;">No purchases recorded yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
