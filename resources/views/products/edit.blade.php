@extends('layouts.admin')

@section('title', 'Edit Product')
@section('page-title', 'Edit Product')
@section('page-subtitle', 'Update product details.')
@section('page-actions')
    <a href="{{ route('products.index') }}" class="btn btn-secondary">Back to products</a>
@endsection

@section('content')
    <div class="panel" style="max-width:720px;">
        @if($errors->any())
            <div class="alert-error">
                Please fix the following errors.
            </div>
        @endif

        <form method="POST" action="{{ route('products.update', $product) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            @if($product->hasImage())
                <div class="field" style="margin-bottom: 1.5rem;">
                    <label>Current Image</label>
                    <img src="{{ $product->getImageUrl() }}" alt="{{ $product->name }}" style="width: 120px; height: 120px; object-fit: cover; border-radius: 12px; border: 1px solid #e2e8f0;">
                    <p style="color: #64748b; font-size: 0.9rem; margin-top: 0.5rem;">Upload a new image to replace it</p>
                </div>
            @endif

            <div class="field">
                <label for="category_id">Category</label>
                <select id="category_id" name="category_id" required>
                    <option value="">Select category</option>
                    @foreach($categories as $id => $name)
                        <option value="{{ $id }}" @selected(old('category_id', $product->category_id) == $id)>{{ $name }}</option>
                    @endforeach
                </select>
                @error('category_id')<div class="field-error">{{ $message }}</div>@enderror
            </div>

            <div class="field">
                <label for="name">Name</label>
                <input id="name" name="name" type="text" value="{{ old('name', $product->name) }}" required>
                @error('name')<div class="field-error">{{ $message }}</div>@enderror
            </div>

            <div class="field-row">
                <div class="field">
                    <label for="price_khr">Price (KHR) <span style="color: #ef4444;">*</span></label>
                    <input id="price_khr" name="price_khr" type="number" value="{{ old('price_khr', $product->price_khr ?? $product->price) }}" step="0.01" required>
                    <small style="color: #64748b; display: block; margin-top: 0.25rem;">Required. USD price will be auto-calculated if not provided.</small>
                    @error('price_khr')<div class="field-error">{{ $message }}</div>@enderror
                </div>
                <div class="field">
                    <label for="price_usd">Price (USD)</label>
                    <input id="price_usd" name="price_usd" type="number" value="{{ old('price_usd', $product->price_usd ?? round(($product->price_khr ?? $product->price)/4100,2)) }}" step="0.01">
                    <small style="color: #64748b; display: block; margin-top: 0.25rem;">Optional. Auto-calculated from KHR (÷4100)</small>
                    @error('price_usd')<div class="field-error">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="field">
                <label for="sku">SKU</label>
                <input id="sku" name="sku" type="text" value="{{ old('sku', $product->sku) }}">
                @error('sku')<div class="field-error">{{ $message }}</div>@enderror
            </div>

            <div class="field">
                <label for="image">Product Image</label>
                <input id="image" name="image" type="file" accept="image/jpeg,image/jpg,image/png,image/webp">
                <small style="color: #64748b; display: block; margin-top: 0.25rem;">Accepted: JPG, PNG, WebP (Max: 2MB)</small>
                @error('image')<div class="field-error">{{ $message }}</div>@enderror
            </div>

            <div class="field">
                <label for="status">Status</label>
                <select id="status" name="status">
                    <option value="1" @if(old('status', $product->status)) selected @endif>Active</option>
                    <option value="0" @if(! old('status', $product->status)) selected @endif>Inactive</option>
                </select>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn">Update Product</button>
            </div>
        </form>
    </div>
@endsection
