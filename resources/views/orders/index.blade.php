@extends('layouts.admin')

@section('title', 'Orders')
@section('page-title', 'Orders')
@section('page-subtitle', 'Review recent and completed sales.')

@section('page-actions')
    <!-- <a href="{{ route('dashboard') }}" class="btn btn-secondary">Back to POS</a> -->
@endsection

@push('styles')
<style>
    .table-list {
        border-collapse: separate;
        border-spacing: 0;
        font-size: 0.9rem;
    }
    
    .table-list thead {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        position: sticky;
        top: 0;
    }
    
    .table-list thead th {
        padding: 0.8rem 0.5rem;
        color: #e2e8f0;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #334155;
        text-align: left;
    }
    
    .table-list tbody tr {
        border-bottom: 1px solid #e2e8f0;
        transition: all 0.2s ease;
    }
    
    .table-list tbody tr:hover {
        background-color: #f0f9ff;
        box-shadow: inset 4px 0 0 #2563eb;
    }
    
    .table-list tbody tr:last-child {
        border-bottom: none;
    }
    
    .table-list tbody td {
        padding: 0.8rem 0.5rem;
        vertical-align: middle;
        color: #1e293b;
        font-size: 0.9rem;
    }
    
    .table-list .order-id {
        font-weight: 700;
        color: #2563eb;
        font-size: 0.95rem;
    }
    
    .table-list .order-staff {
        font-weight: 500;
        color: #334155;
        font-size: 0.9rem;
    }
    
    .table-list .order-payment {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.3rem 0.5rem;
        background: rgba(148, 163, 184, 0.1);
        border-radius: 4px;
        font-size: 0.8rem;
        font-weight: 500;
        color: #475569;
        white-space: nowrap;
    }
    
    .table-list .order-currency {
        font-weight: 600;
        padding: 0.25rem 0.5rem;
        background: #f1f5f9;
        border-radius: 4px;
        font-size: 0.75rem;
        color: #0f172a;
        white-space: nowrap;
    }
    
    .table-list .order-total {
        font-weight: 700;
        font-size: 1rem;
        color: #0f172a;
    }

    .order-status {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.35rem 0.6rem;
        border-radius: 6px;
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.3px;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .order-status.completed { 
        background: linear-gradient(135deg, #dcfce7 0%, #c6f6d5 100%);
        color: #166534;
        box-shadow: 0 2px 4px rgba(22, 101, 52, 0.1);
    }
    .order-status.pending { 
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        color: #92400e;
        box-shadow: 0 2px 4px rgba(146, 64, 14, 0.1);
    }
    .order-status.cancelled { 
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        color: #991b1b;
        box-shadow: 0 2px 4px rgba(153, 27, 27, 0.1);
    }
    .order-status.processing { 
        background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%);
        color: #0c4a6e;
        box-shadow: 0 2px 4px rgba(12, 74, 110, 0.1);
    }

    .orders-empty {
        text-align: center;
        padding: 3rem 2rem;
        color: #64748b;
    }
    
    .orders-empty p {
        font-size: 1.1rem;
        margin-bottom: 1.5rem;
    }
</style>
@endpush

@section('content')
    <section class="panel">
        @if($orders->count())
            <div class="table-responsive">
            <table class="table-list" style="width:100%;">
                <thead>
                    <tr>
                        <th style="width: 6%;">#</th>
                        <th style="width: 14%;">Staff</th>
                        <th style="width: 12%;">Payment</th>
                        <th style="width: 8%;">Currency</th>
                        <th style="width: 12%;">Subtotal</th>
                        <th style="width: 12%;">Total</th>
                        <th style="width: 12%;">Status</th>
                        <th style="width: 12%;">Date</th>
                        <th style="width: 12%;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td class="order-id"><a href="{{ route('orders.show', $order) }}" style="color: inherit; text-decoration: none; cursor: pointer; display: inline-flex; align-items: center; gap: 0.5rem;">#{{ str_pad($order->id, 3, '0', STR_PAD_LEFT) }}</a></td>
                            <td class="order-staff">{{ $order->user->name ?? 'N/A' }}</td>
                            <td><span class="order-payment">
                                @if($order->payment_method === 'cash')
                                    Cash
                                @elseif($order->payment_method === 'mobile')
                                    Mobile
                                @else
                                    Bank
                                @endif
                            </span></td>
                            <td><span class="order-currency">{{ strtoupper($order->currency) }}</span></td>
                            <td style="color: #64748b; font-size: 0.95rem;">{{ $order->getFormattedSubtotal() }}</td>
                            <td class="order-total">{{ $order->getFormattedTotal() }}</td>
                            <td><span class="order-status {{ strtolower($order->status) }}">{{ ucfirst($order->status) }}</span></td>
                            <td style="color: #64748b; font-size: 0.95rem;">{{ $order->created_at->format('Y-m-d') }}</td>
                            <td style="text-align: center;">
                                <div style="display: inline-flex; gap: 0.5rem;">
                                    <a href="{{ route('orders.show', $order) }}" style="padding: 0.4rem 0.7rem; background: #2563eb; color: white; border-radius: 4px; text-decoration: none; font-size: 0.85rem; font-weight: 500;" title="View">View</a>
                                    <button onclick="if(confirm('Are you sure you want to delete this order?')) { document.getElementById('delete-form-{{ $order->id }}').submit(); }" style="padding: 0.4rem 0.7rem; background: #dc2626; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 0.85rem; font-weight: 500;" title="Delete">Delete</button>
                                    <form id="delete-form-{{ $order->id }}" action="{{ route('orders.destroy', $order) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="orders-empty">
                <p class="text-muted">No orders have been recorded yet.</p>
                <!-- <a href="{{ route('dashboard') }}" class="btn">Back to POS</a> -->
            </div>
        @endif
    </section>
@endsection
