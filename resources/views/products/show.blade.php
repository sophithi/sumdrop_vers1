@extends('layouts.admin')

@section('title', 'Product Details')
@section('page-title', 'Product Details')
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
                <p style="margin:0.5rem 0 0; color:#64748b;">SKU: {{ $product->sku ?? '—' }}</p>
            </div>
        </div>

        <div class="field-row" style="margin-bottom:1rem;">
            <div class="field">
                <label>Category</label>
                <p>{{ $product->category->name ?? '—' }}</p>
            </div>
            <div class="field">
                <label>Status</label>
                <p>{{ $product->status ? 'Active' : 'Inactive' }}</p>
            </div>
        </div>

        <div class="field-row" style="margin-bottom:1rem;">
            <div class="field">
                <label>Price (KHR)</label>
                <p>{{ $product->getFormattedPriceKhr() }}</p>
            </div>
            <div class="field">
                <label>Price (USD)</label>
                <p>{{ $product->getFormattedPriceUsd() }}</p>
            </div>
        </div>

        <div class="form-actions">
            @if(auth()->user()?->role === 'admin')
                <a href="{{ route('products.edit', $product) }}" class="btn">Edit product</a>
                <form method="POST" action="{{ route('products.destroy', $product) }}" onsubmit="return confirm('Delete this product?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            @endif
        </div>
    </div>
@endsection
