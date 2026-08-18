@extends('layouts.admin')

@section('title', __('purchases.create_title'))
@section('page-title', __('purchases.create_title'))
@section('page-subtitle', __('purchases.create_subtitle'))
@section('page-actions')
    <a href="{{ route('purchases.index') }}" class="btn btn-secondary">{{ __('purchases.back_to_purchases') }}</a>
@endsection

@section('content')
    <div class="panel" style="max-width:720px;">
        @if($errors->any())
            <div class="alert-error">{{ __('common.fix_errors') }}</div>
        @endif

        <form method="POST" action="{{ route('purchases.store') }}">
            @csrf

            <div class="field">
                <label for="supplier">{{ __('purchases.supplier') }}</label>
                <input id="supplier" name="supplier" value="{{ old('supplier') }}" placeholder="{{ __('purchases.supplier_placeholder') }}">
                @error('supplier')<div class="field-error">{{ $message }}</div>@enderror
            </div>

            <div class="field-row">
                <div class="field">
                    <label for="purchase_date">{{ __('purchases.purchase_date') }}</label>
                    <input id="purchase_date" name="purchase_date" type="date" value="{{ old('purchase_date', now()->format('Y-m-d')) }}">
                    @error('purchase_date')<div class="field-error">{{ $message }}</div>@enderror
                </div>
                <div class="field">
                    <label for="payment_method">{{ __('purchases.payment_method') }}</label>
                    <select id="payment_method" name="payment_method">
                        <option value="cash" @selected(old('payment_method') === 'cash')>{{ __('common.payment_cash') }}</option>
                        <option value="mobile" @selected(old('payment_method') === 'mobile')>{{ __('common.payment_mobile') }}</option>
                    </select>
                    @error('payment_method')<div class="field-error">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="field">
                <label for="total">{{ __('purchases.total_amount') }}</label>
                <input id="total" name="total" type="number" step="0.01" min="0" value="{{ old('total') }}" placeholder="0.00">
                @error('total')<div class="field-error">{{ $message }}</div>@enderror
            </div>

            <div class="field">
                <label for="currency">{{ __('common.currency') }}</label>
                <select id="currency" name="currency">
                    <option value="usd" @selected(old('currency') === 'usd')>USD</option>
                    <option value="khr" @selected(old('currency') === 'khr')>KHR</option>
                </select>
                @error('currency')<div class="field-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn">{{ __('purchases.save_purchase') }}</button>
            </div>
        </form>
    </div>
@endsection
