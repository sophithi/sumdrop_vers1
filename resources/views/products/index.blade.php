@extends('layouts.admin')

@section('title', __('menu.menu'))
@section('page-title', __('menu.menu'))
@section('page-subtitle', __('products.index_subtitle'))
@section('page-actions')
    <a href="{{ route('products.create') }}" class="btn">+ {{ __('products.new_product') }}</a>
@endsection

@push('styles')
<style>
    .product-thumb { width: 38px; height: 38px; border-radius: 10px; background: #eff6ff; display: inline-block; object-fit: cover; }
    .product-name-cell { display: flex; align-items: center; gap: 0.8rem; }
    .badge-active { background: #dcfce7; color: #166534; }
    .badge-inactive { background: #f8fafc; color: #475569; }
</style>
@endpush

@section('content')
    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <div class="panel">
        @if($products->count())
            <div class="table-responsive">
            <table class="table-list" style="width:100%;">
                <thead>
                    <tr>
                        <th>{{ __('common.product') }}</th>
                        <th>{{ __('common.category') }}</th>
                        <th>{{ __('common.price') }} (KHR)</th>
                        <th>{{ __('common.price') }} (USD)</th>
                        <th>{{ __('common.stock') }}</th>
                        <th>{{ __('common.status') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                        <tr>
                            <td>
                                <div class="product-name-cell">
                                    @if($product->hasImage())
                                        <img src="{{ $product->getImageUrl() }}" alt="{{ $product->name }}" class="product-thumb">
                                    @else
                                        <span class="product-thumb"></span>
                                    @endif
                                    <div>
                                        <strong>{{ $product->name }}</strong>
                                        @if($product->size)
                                            <span class="badge badge-size">{{ $product->size }}</span>
                                        @endif
                                        @if($product->unit !== 'piece')
                                            <span class="badge badge-unit">{{ $product->getUnitLabel() }}</span>
                                        @endif
                                        <br><small>{{ $product->sku }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $product->category->name ?? '—' }}</td>
                            <td>
                                {{ $product->getFormattedPriceKhr() }}
                                @if($product->sellsByPiece())
                                    <br><small style="color:#64748b;">{{ __('products.piece_price_prefix') }} ៛{{ number_format($product->price_khr_piece) }}</small>
                                @endif
                            </td>
                            <td>
                                {{ $product->getFormattedPriceUsd() }}
                                @if($product->sellsByPiece())
                                    <br><small style="color:#64748b;">{{ __('products.piece_price_prefix') }} ${{ number_format($product->price_usd_piece, 2) }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $product->isLowStock() ? 'badge-low-stock' : 'badge-active' }}">
                                    {{ $product->stockDisplay() }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $product->status ? 'badge-active' : 'badge-inactive' }}">
                                    {{ $product->status ? __('common.active') : __('common.inactive') }}
                                </span>
                            </td>
                            <td>
                                <div style="display:flex; gap:0.5rem; flex-wrap:wrap; justify-content:flex-end;">
                                    <a href="{{ route('products.show', $product) }}" class="btn btn-secondary btn-sm">{{ __('common.view') }}</a>
                                    <a href="{{ route('products.edit', $product) }}" class="btn btn-secondary btn-sm">{{ __('common.edit') }}</a>
                                    <form method="POST" action="{{ route('products.destroy', $product) }}" onsubmit="return confirm('{{ __('common.confirm_delete') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">{{ __('common.delete') }}</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        @else
            <div class="empty-state">
                <p>{{ __('common.no_products_yet') }}</p>
                <a href="{{ route('products.create') }}" class="btn">+ {{ __('products.add_first_product') }}</a>
            </div>
        @endif
    </div>
@endsection
