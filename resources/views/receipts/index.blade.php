@extends('layouts.admin')

@section('title', 'Receipts')
@section('page-title', 'Order Receipts')
@section('page-subtitle', 'View and print order receipts')

@push('styles')
<style>
    .receipts-table {
        width: 100%;
        border-collapse: collapse;
        background: #ffffff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.06);
    }

    .receipts-table thead {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .receipts-table thead th {
        padding: 1rem;
        color: #e2e8f0;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #334155;
        text-align: left;
    }

    .receipts-table tbody tr {
        border-bottom: 1px solid #e2e8f0;
        transition: all 0.2s ease;
    }

    .receipts-table tbody tr:hover {
        background-color: #f0f9ff;
        box-shadow: inset 4px 0 0 #2563eb;
    }

    .receipts-table tbody tr:last-child {
        border-bottom: none;
    }

    .receipts-table tbody td {
        padding: 0.95rem 1rem;
        vertical-align: middle;
        color: #1e293b;
        font-size: 0.95rem;
    }

    .receipt-id {
        font-weight: 700;
        color: #2563eb;
        font-size: 1rem;
    }

    .receipt-customer {
        font-weight: 500;
        color: #334155;
    }

    .receipt-items-list {
        color: #64748b;
        font-size: 0.9rem;
    }

    .receipt-total {
        font-weight: 700;
        font-size: 1.05rem;
        color: #0f172a;
    }

    .receipt-payment-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.4rem 0.7rem;
        background: rgba(148, 163, 184, 0.1);
        border-radius: 6px;
        font-size: 0.9rem;
        font-weight: 500;
        color: #475569;
    }

    .receipt-currency-badge {
        font-weight: 600;
        padding: 0.35rem 0.6rem;
        background: #f1f5f9;
        border-radius: 6px;
        font-size: 0.85rem;
        color: #0f172a;
    }

    .receipt-status-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.5rem 0.9rem;
        border-radius: 8px;
        font-size: 0.83rem;
        font-weight: 700;
        letter-spacing: 0.3px;
        text-transform: uppercase;
    }

    .receipt-status-badge.completed { 
        background: linear-gradient(135deg, #dcfce7 0%, #c6f6d5 100%);
        color: #166534;
        box-shadow: 0 2px 4px rgba(22, 101, 52, 0.1);
    }
    .receipt-status-badge.pending { 
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        color: #92400e;
        box-shadow: 0 2px 4px rgba(146, 64, 14, 0.1);
    }
    .receipt-status-badge.cancelled { 
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        color: #991b1b;
        box-shadow: 0 2px 4px rgba(153, 27, 27, 0.1);
    }

    .receipt-date {
        color: #64748b;
        font-size: 0.95rem;
    }

    .print-receipt-btn {
        padding: 0.5rem 0.8rem;
        background: #2563eb;
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.85rem;
        font-weight: 600;
        transition: background 0.2s ease;
        white-space: nowrap;
    }

    .print-receipt-btn:hover {
        background: #1d4ed8;
    }

    .receipts-empty {
        text-align: center;
        padding: 3rem 2rem;
        color: #64748b;
    }
    
    .receipts-empty p {
        font-size: 1.1rem;
        margin-bottom: 1.5rem;
    }

    .receipt-filter-section {
        background: #f8fafc;
        padding: 1.5rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        border: 1px solid #e2e8f0;
    }

    .receipt-filter-section h3 {
        margin: 0 0 1rem 0;
        color: #0f172a;
        font-size: 1rem;
    }
</style>
@endpush

@section('content')
    <!-- Date Filter Section -->
    <div class="receipt-filter-section">
        <h3>Filter by Date</h3>
        <form method="GET" action="{{ route('receipts.index') }}" class="filter-form">
            <!-- Day Filter -->
            <div>
                <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 0.4rem; color: #475569;">Day</label>
                <select name="day" style="width: 100%; padding: 0.6rem; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.9rem;">
                    <option value="">All Days</option>
                    @foreach($days as $day)
                        <option value="{{ $day }}" {{ request('day') == $day ? 'selected' : '' }}>
                            {{ str_pad($day, 2, '0', STR_PAD_LEFT) }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <!-- Month Filter -->
            <div>
                <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 0.4rem; color: #475569;">Month</label>
                <select name="month" style="width: 100%; padding: 0.6rem; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.9rem;">
                    <option value="">All Months</option>
                    @foreach($months as $month)
                        <option value="{{ $month }}" {{ request('month') == $month ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::createFromFormat('m', $month)->format('F') }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <!-- Year Filter -->
            <div>
                <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 0.4rem; color: #475569;">Year</label>
                <select name="year" style="width: 100%; padding: 0.6rem; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.9rem;">
                    <option value="">All Years</option>
                    @foreach($years as $year)
                        <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <!-- Filter Button -->
            <div class="filter-actions">
                <button type="submit" style="padding: 0.6rem 1.2rem; background: #2563eb; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">Filter</button>
                <a href="{{ route('receipts.index') }}" style="padding: 0.6rem 1rem; background: #e2e8f0; color: #0f172a; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; text-decoration: none; display: flex; align-items: center;">Reset</a>
            </div>
            
            <!-- Download PDF Button -->
            <div class="filter-actions filter-actions-end">
                <button type="button" onclick="downloadReceiptsPDF()" style="padding: 0.6rem 1.2rem; background: #10b981; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: background 0.2s ease; width: 100%;" onmouseover="this.style.background='#059669'" onmouseout="this.style.background='#10b981'">Download PDF</button>
            </div>
        </form>
    </div>
    
    <!-- Results Count -->
    @if(request()->filled('day') || request()->filled('month') || request()->filled('year'))
        <div style="background: #eff6ff; padding: 0.75rem 1rem; border-radius: 8px; margin-bottom: 1.5rem; border-left: 4px solid #2563eb; color: #0c4a6e; font-weight: 500;">
            Showing {{ $receipts->count() }} receipt(s)
        </div>
    @endif

    @if($receipts->isEmpty())
        <div class="panel" style="text-align: center; padding: 3rem;">
            <div style="font-size: 3rem; margin-bottom: 1rem;">📋</div>
            <h3>No receipts available</h3>
            <p style="color: #64748b;">Orders will appear here once they are completed.</p>
        </div>
    @else
        <div class="table-responsive">
        <table class="receipts-table">
            <thead>
                <tr>
                    <th style="width: 8%;">Receipt #</th>
                    <th style="width: 12%;">Date</th>
                    <th style="width: 15%;">Customer</th>
                    <th style="width: 18%;">Items</th>
                    <th style="width: 10%;">Currency</th>
                    <th style="width: 12%;">Total</th>
                    <th style="width: 10%;">Payment</th>
                    <th style="width: 10%;">Status</th>
                    <th style="width: 7%;"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($receipts as $receipt)
                    <tr>
                        <td class="receipt-id">#{{ str_pad($receipt->id, 3, '0', STR_PAD_LEFT) }}</td>
                        <td class="receipt-date">{{ $receipt->created_at->format('M d, Y') }}</td>
                        <td class="receipt-customer">{{ $receipt->user?->name ?? 'Guest' }}</td>
                        <td class="receipt-items-list">
                            @foreach($receipt->items as $item)
                                <div>{{ $item->product?->name ?? 'Unknown' }} ({{ $item->quantity }})</div>
                            @endforeach
                        </td>
                        <td><span class="receipt-currency-badge">{{ strtoupper($receipt->currency) }}</span></td>
                        <td class="receipt-total">
                            @if($receipt->currency === 'khr')
                                ៛{{ number_format($receipt->total, 0) }}
                            @else
                                ${{ number_format($receipt->total, 2) }}
                            @endif
                        </td>
                        <td>
                            <span class="receipt-payment-badge">
                                @if($receipt->payment_method === 'cash')
                                    Cash
                                @elseif($receipt->payment_method === 'bank')
                                    Bank
                                @else
                                    Mobile
                                @endif
                            </span>
                        </td>
                        <td><span class="receipt-status-badge {{ strtolower($receipt->status) }}">{{ ucfirst($receipt->status) }}</span></td>
                        <td style="text-align: center;">
                            <a href="{{ route('orders.show', $receipt->id) }}" class="print-receipt-btn" style="display: inline-block; padding: 0.5rem 1rem; background: #2563eb; color: white; border-radius: 6px; text-decoration: none; cursor: pointer; border: none; font-weight: 500;">Print</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    @endif

    @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        function downloadReceiptsPDF() {
            const element = document.querySelector('.receipts-table');
            
            if (!element) {
                alert('No receipts to download');
                return;
            }

            const opt = {
                margin: 10,
                filename: 'receipts_' + new Date().getTime() + '.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2 },
                jsPDF: { orientation: 'landscape', unit: 'mm', format: 'a4' }
            };

            html2pdf().set(opt).from(element).save();
        }
    </script>
    @endpush
@endsection
