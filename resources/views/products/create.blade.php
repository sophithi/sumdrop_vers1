@extends('layouts.admin')

@section('title', 'Create Product')
@section('page-title', 'Create Product')
@section('page-subtitle', 'Add a new item to the menu.')
@section('page-actions')
    <a href="{{ route('products.index') }}" class="btn btn-secondary">Back to products</a>
@endsection

@section('content')
    <div class="panel" style="max-width:720px;">
        @if($errors->any())
    <div class="alert-error">
        <strong>Please fix the following errors:</strong>
        <ul style="margin: 0.5rem 0 0 1.25rem;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

        <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="field">
                <label for="category_id">Category</label>
                <select id="category_id" name="category_id" required>
                    <option value="">Select category</option>
                    @foreach($categories as $id => $name)
                        <option value="{{ $id }}" @selected(old('category_id') == $id)>{{ $name }}</option>
                    @endforeach
                </select>
                @error('category_id')<div class="field-error">{{ $message }}</div>@enderror
            </div>

            <div class="field">
                <label for="name">Name</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required>
                @error('name')<div class="field-error">{{ $message }}</div>@enderror
            </div>

            <div class="field-row">
                <div class="field">
                    <label for="price_khr">Price (KHR) <span style="color: #ef4444;">*</span></label>
                    <input id="price_khr" name="price_khr" type="number" value="{{ old('price_khr') }}" step="0.01" required>
                    <small style="color: #64748b; display: block; margin-top: 0.25rem;">Required. USD price will be auto-calculated if not provided.</small>
                    @error('price_khr')<div class="field-error">{{ $message }}</div>@enderror
                </div>
                <div class="field">
                    <label for="price_usd">Price (USD)</label>
                    <input id="price_usd" name="price_usd" type="number" value="{{ old('price_usd') }}" step="0.01">
                    <small style="color: #64748b; display: block; margin-top: 0.25rem;">Optional. Auto-calculated from KHR (÷4100)</small>
                    @error('price_usd')<div class="field-error">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="field">
                <label for="sku">SKU</label>
                <input id="sku" name="sku" type="text" value="{{ old('sku') }}">
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
                    <option value="1" @if(old('status', true)) selected @endif>Active</option>
                    <option value="0" @if(! old('status', true)) selected @endif>Inactive</option>
                </select>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn">Save Product</button>
            </div>
        </form>
    </div>
@endsection
