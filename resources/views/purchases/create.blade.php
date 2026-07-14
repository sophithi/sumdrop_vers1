@extends('layouts.admin')

@section('title', 'Record Purchase')
@section('page-title', 'Record Purchase')
@section('page-subtitle', 'Log a supplier purchase quickly.')
@section('page-actions')
    <a href="{{ route('purchases.index') }}" class="btn btn-secondary">Back to Purchases</a>
@endsection

@section('content')
    <div class="panel" style="max-width:720px;">
        @if($errors->any())
            <div class="alert-error">Please fix the errors below.</div>
        @endif

        <form method="POST" action="{{ route('purchases.store') }}">
            @csrf

            <div class="field">
                <label for="supplier">Supplier</label>
                <input id="supplier" name="supplier" value="{{ old('supplier') }}" placeholder="Supplier name">
                @error('supplier')<div class="field-error">{{ $message }}</div>@enderror
            </div>

            <div class="field-row">
                <div class="field">
                    <label for="purchase_date">Purchase Date</label>
                    <input id="purchase_date" name="purchase_date" type="date" value="{{ old('purchase_date', now()->format('Y-m-d')) }}">
                    @error('purchase_date')<div class="field-error">{{ $message }}</div>@enderror
                </div>
                <div class="field">
                    <label for="payment_method">Payment Method</label>
                    <select id="payment_method" name="payment_method">
                        <option value="cash" @selected(old('payment_method') === 'cash')>Cash</option>
                        <option value="mobile" @selected(old('payment_method') === 'mobile')>Mobile</option>
                    </select>
                    @error('payment_method')<div class="field-error">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="field">
                <label for="total">Total Amount</label>
                <input id="total" name="total" type="number" step="0.01" min="0" value="{{ old('total') }}" placeholder="0.00">
                @error('total')<div class="field-error">{{ $message }}</div>@enderror
            </div>

            <div class="field">
                <label for="currency">Currency</label>
                <select id="currency" name="currency">
                    <option value="usd" @selected(old('currency') === 'usd')>USD</option>
                    <option value="khr" @selected(old('currency') === 'khr')>KHR</option>
                </select>
                @error('currency')<div class="field-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn">Save Purchase</button>
            </div>
        </form>
    </div>
@endsection
