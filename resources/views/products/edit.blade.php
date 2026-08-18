@extends('layouts.admin')

@section('title', __('products.edit_title'))
@section('page-title', __('products.edit_title'))
@section('page-subtitle', __('products.edit_subtitle'))
@section('page-actions')
    <a href="{{ route('products.index') }}" class="btn btn-secondary">{{ __('products.back_to_products') }}</a>
@endsection

@section('content')
    <div class="panel" style="max-width:720px;">
        @if($errors->any())
            <div class="alert-error">
                {{ __('products.fix_errors_notice') }}
            </div>
        @endif

        <form method="POST" action="{{ route('products.update', $product) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            @if($product->hasImage())
                <div class="field" style="margin-bottom: 1.5rem;">
                    <label>{{ __('products.current_image') }}</label>
                    <img src="{{ $product->getImageUrl() }}" alt="{{ $product->name }}" style="width: 120px; height: 120px; object-fit: cover; border-radius: 12px; border: 1px solid #e2e8f0;">
                    <p style="color: #64748b; font-size: 0.9rem; margin-top: 0.5rem;">{{ __('products.replace_image_help') }}</p>
                </div>
            @endif

            <div class="field">
                <label for="category_id">{{ __('common.category') }}</label>
                <select id="category_id" name="category_id" required>
                    <option value="">{{ __('products.select_category') }}</option>
                    @foreach($categories as $id => $name)
                        <option value="{{ $id }}" @selected(old('category_id', $product->category_id) == $id)>{{ $name }}</option>
                    @endforeach
                </select>
                @error('category_id')<div class="field-error">{{ $message }}</div>@enderror
            </div>

            <div class="field">
                <label for="name">{{ __('common.name') }}</label>
                <input id="name" name="name" type="text" value="{{ old('name', $product->name) }}" required>
                @error('name')<div class="field-error">{{ $message }}</div>@enderror
            </div>

            <div class="field-row">
                <div class="field">
                    <label for="price_khr">{{ __('common.price') }} (KHR) <span style="color: #ef4444;">*</span></label>
                    <input id="price_khr" name="price_khr" type="number" value="{{ old('price_khr', $product->price_khr ?? $product->price) }}" step="0.01" required>
                    <small style="color: #64748b; display: block; margin-top: 0.25rem;">{{ __('products.price_khr_help') }}</small>
                    @error('price_khr')<div class="field-error">{{ $message }}</div>@enderror
                </div>
                <div class="field">
                    <label for="price_usd">{{ __('common.price') }} (USD)</label>
                    <input id="price_usd" name="price_usd" type="number" value="{{ old('price_usd', $product->price_usd ?? round(($product->price_khr ?? $product->price)/4100,2)) }}" step="0.01">
                    <small style="color: #64748b; display: block; margin-top: 0.25rem;">{{ __('products.price_usd_help') }}</small>
                    @error('price_usd')<div class="field-error">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="field">
                <label for="sku">{{ __('common.sku') }}</label>
                <input id="sku" name="sku" type="text" value="{{ old('sku', $product->sku) }}">
                @error('sku')<div class="field-error">{{ $message }}</div>@enderror
            </div>

            <div class="field-row">
                <div class="field">
                    <label for="stock">{{ __('common.stock') }}</label>
                    <input id="stock" name="stock" type="number" min="0" value="{{ old('stock', $product->stock) }}">
                    <small style="color: #64748b; display: block; margin-top: 0.25rem;">{{ __('products.stock_help') }}</small>
                    @error('stock')<div class="field-error">{{ $message }}</div>@enderror
                </div>
                <div class="field">
                    <label for="size">{{ __('common.size') }}</label>
                    <select id="size" name="size">
                        <option value="" @selected(old('size', $product->size) === '' || old('size', $product->size) === null)>{{ __('common.none') }}</option>
                        <option value="M" @selected(old('size', $product->size) === 'M')>M</option>
                        <option value="L" @selected(old('size', $product->size) === 'L')>L</option>
                    </select>
                    <small style="color: #64748b; display: block; margin-top: 0.25rem;">{{ __('products.size_help') }}</small>
                    @error('size')<div class="field-error">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="field-row">
                <div class="field">
                    <label for="unit">{{ __('products.sold_as') }}</label>
                    <select id="unit" name="unit" onchange="const isCaseLike = ['case','can','pack','box','glass'].includes(this.value); document.getElementById('pack_quantity_field').style.display = isCaseLike ? 'block' : 'none'; document.getElementById('piece_pricing_field').style.display = isCaseLike ? 'block' : 'none';">
                        <option value="piece" @selected(old('unit', $product->unit ?? 'piece') === 'piece')>{{ __('common.piece') }}</option>
                        <option value="case" @selected(old('unit', $product->unit) === 'case')>{{ __('common.case') }}</option>
                        <option value="can" @selected(old('unit', $product->unit) === 'can')>{{ __('common.can') }}</option>
                        <option value="pack" @selected(old('unit', $product->unit) === 'pack')>{{ __('common.pack') }}</option>
                        <option value="box" @selected(old('unit', $product->unit) === 'box')>{{ __('common.box') }}</option>
                        <option value="glass" @selected(old('unit', $product->unit) === 'glass')>{{ __('common.glass') }}</option>
                    </select>
                    @error('unit')<div class="field-error">{{ $message }}</div>@enderror
                </div>
                @php $isCaseLikeOld = in_array(old('unit', $product->unit), ['case', 'can', 'pack', 'box', 'glass'], true); @endphp
                <div class="field" id="pack_quantity_field" style="display: {{ $isCaseLikeOld ? 'block' : 'none' }};">
                    <label for="pack_quantity">{{ __('products.units_per_case') }}</label>
                    <input id="pack_quantity" name="pack_quantity" type="number" min="1" value="{{ old('pack_quantity', $product->pack_quantity) }}" placeholder="{{ __('products.eg_24') }}">
                    <small style="color: #64748b; display: block; margin-top: 0.25rem;">{{ __('products.pack_quantity_help') }}</small>
                    @error('pack_quantity')<div class="field-error">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="field-row" id="piece_pricing_field" style="display: {{ $isCaseLikeOld ? 'block' : 'none' }};">
                <div class="field">
                    <label for="price_khr_piece">{{ __('products.piece_price_khr') }}</label>
                    <input id="price_khr_piece" name="price_khr_piece" type="number" value="{{ old('price_khr_piece', $product->price_khr_piece) }}" step="0.01">
                    <small style="color: #64748b; display: block; margin-top: 0.25rem;">{{ __('products.piece_price_khr_help') }}</small>
                    @error('price_khr_piece')<div class="field-error">{{ $message }}</div>@enderror
                </div>
                <div class="field">
                    <label for="price_usd_piece">{{ __('products.piece_price_usd') }}</label>
                    <input id="price_usd_piece" name="price_usd_piece" type="number" value="{{ old('price_usd_piece', $product->price_usd_piece) }}" step="0.01">
                    <small style="color: #64748b; display: block; margin-top: 0.25rem;">{{ __('products.price_usd_piece_help') }}</small>
                    @error('price_usd_piece')<div class="field-error">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="field">
                <label for="image">{{ __('products.product_image') }}</label>
                <input id="image" name="image" type="file" accept="image/jpeg,image/jpg,image/png,image/webp">
                <small style="color: #64748b; display: block; margin-top: 0.25rem;">{{ __('products.image_help') }}</small>
                @error('image')<div class="field-error">{{ $message }}</div>@enderror
            </div>

            <div class="field">
                <label for="status">{{ __('common.status') }}</label>
                <select id="status" name="status">
                    <option value="1" @if(old('status', $product->status)) selected @endif>{{ __('common.active') }}</option>
                    <option value="0" @if(! old('status', $product->status)) selected @endif>{{ __('common.inactive') }}</option>
                </select>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn">{{ __('products.update_product') }}</button>
            </div>
        </form>
    </div>
@endsection
