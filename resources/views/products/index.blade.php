@extends('layouts.admin')

@section('title', 'Products')
@section('page-title', 'Products')
@section('page-subtitle', 'Manage menu items, pricing and stock.')
@section('page-actions')
    <a href="{{ route('products.create') }}" class="btn">+ New product</a>
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
            <table class="table-list" style="width:100%;">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Category</th>
                        <th>Price (KHR)</th>
                        <th>Price (USD)</th>
                        <th>Status</th>
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
                                        <small>{{ $product->sku }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $product->category->name ?? '—' }}</td>
                            <td>{{ $product->getFormattedPriceKhr() }}</td>
                            <td>{{ $product->getFormattedPriceUsd() }}</td>
                            <td>
                                <span class="badge {{ $product->status ? 'badge-active' : 'badge-inactive' }}">
                                    {{ $product->status ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div style="display:flex; gap:0.5rem; flex-wrap:wrap; justify-content:flex-end;">
                                    <a href="{{ route('products.show', $product) }}" class="btn btn-secondary btn-sm">View</a>
                                    <a href="{{ route('products.edit', $product) }}" class="btn btn-secondary btn-sm">Edit</a>
                                    <form method="POST" action="{{ route('products.destroy', $product) }}" onsubmit="return confirm('Delete this product?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty-state">
                <p>No products yet.</p>
                <a href="{{ route('products.create') }}" class="btn">+ Add your first product</a>
            </div>
        @endif
    </div>
@endsection
