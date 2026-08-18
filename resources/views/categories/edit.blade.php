@extends('layouts.admin')

@section('title', __('categories.edit_title'))
@section('page-title', __('categories.edit_title'))
@section('page-subtitle', __('categories.edit_subtitle'))
@section('page-actions')
    <a href="{{ route('categories.index') }}" class="btn btn-secondary">{{ __('categories.back_to_categories') }}</a>
@endsection

@section('content')
    <div class="panel" style="max-width:600px;">
        <form method="POST" action="{{ route('categories.update', $category) }}">
            @csrf
            @method('PUT')

            @if($errors->any())
                <div class="alert-error">
                    {{ __('common.fix_errors') }}
                </div>
            @endif

            <div class="field">
                <label for="name">{{ __('common.name') }}</label>
                <input id="name" name="name" type="text" value="{{ old('name', $category->name) }}" required>
                @error('name')<div class="field-error">{{ $message }}</div>@enderror
            </div>

            <div class="field">
                <label for="slug">{{ __('categories.slug') }}</label>
                <input id="slug" name="slug" type="text" value="{{ old('slug', $category->slug) }}" placeholder="{{ __('categories.slug_placeholder') }}">
                @error('slug')<div class="field-error">{{ $message }}</div>@enderror
            </div>

            <div class="field">
                <label for="status">{{ __('common.status') }}</label>
                <select id="status" name="status">
                    <option value="1" @if(old('status', $category->status)) selected @endif>{{ __('common.active') }}</option>
                    <option value="0" @if(! old('status', $category->status)) selected @endif>{{ __('common.inactive') }}</option>
                </select>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn">{{ __('categories.update_category') }}</button>
            </div>
        </form>
    </div>
@endsection
