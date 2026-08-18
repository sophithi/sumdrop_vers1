@extends('layouts.admin')

@section('title', __('products.details_title'))
@section('page-title', __('products.details_title'))
@section('page-subtitle', $product->name)
@section('page-actions')
    <!-- <a href="{{ route('products.index') }}" class="btn btn-secondary">Back to products</a> -->
@endsection

@section('content')
    <div class="panel" style="max-width:720px;">
        <div style="display:flex; gap:1rem; flex-wrap:wrap; align-items:center; margin-bottom:1.25rem;">
            @if($product->hasImage())
                <img src="{{ $product->getImageUrl() }}" alt="{{ $product->name }}" style="width:88px; height:88px; object-fit:cover; border-radius:18px; background:#eff6ff;">
            @else
                <span style="width:88px; height:88px; display:block; border-radius:18px; background:#eff6ff;"></span>
            @endif
            <div>
                <h3 style="margin:0; font-size:1.25rem;">{{ $product->name }}</h3>
                <p style="margin:0.5rem 0 0; color:#64748b;">{{ __('common.sku') }}: {{ $product->sku ?? '—' }}</p>
            </div>
        </div>

        <div class="field-row" style="margin-bottom:1rem;">
            <div class="field">
                <label>{{ __('common.category') }}</label>
                <p>{{ $product->category->name ?? '—' }}</p>
            </div>
            <div class="field">
                <label>{{ __('common.status') }}</label>
                <p>{{ $product->status ? __('common.active') : __('common.inactive') }}</p>
            </div>
        </div>

        <div class="field-row" style="margin-bottom:1rem;">
            <div class="field">
                <label>{{ __('common.price') }} (KHR)</label>
                <p>{{ $product->getFormattedPriceKhr() }}</p>
            </div>
            <div class="field">
                <label>{{ __('common.price') }} (USD)</label>
                <p>{{ $product->getFormattedPriceUsd() }}</p>
            </div>
        </div>

        <div class="field-row" style="margin-bottom:1rem;">
            <div class="field">
                <label>{{ __('common.stock') }}</label>
                <p>{{ $product->stockDisplay() }}</p>
            </div>
            <div class="field">
                <label>{{ __('common.size') }}</label>
                <p>{{ $product->size ?? '—' }}</p>
            </div>
        </div>

        <div class="field-row" style="margin-bottom:1rem;">
            <div class="field">
                <label>{{ __('products.sold_as') }}</label>
                <p>{{ $product->getUnitLabel() }}</p>
            </div>
            @if($product->sellsByPiece())
                <div class="field">
                    <label>{{ __('products.piece_price_label') }}</label>
                    <p>៛{{ number_format($product->price_khr_piece) }} / ${{ number_format($product->price_usd_piece, 2) }}</p>
                </div>
            @endif
        </div>

        <div class="form-actions">
            @if(auth()->user()?->role === 'admin')
                <a href="{{ route('products.edit', $product) }}" class="btn">{{ __('products.edit_product') }}</a>
                <form method="POST" action="{{ route('products.destroy', $product) }}" onsubmit="return confirm('{{ __('common.confirm_delete') }}');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">{{ __('common.delete') }}</button>
                </form>
            @endif
        </div>
    </div>
@endsection
