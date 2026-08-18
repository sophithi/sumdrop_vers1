@extends('layouts.admin')

@section('title', __('categories.create_title'))
@section('page-title', __('categories.create_title'))
@section('page-subtitle', __('categories.create_subtitle'))
@section('page-actions')
    <a href="{{ route('categories.index') }}" class="btn btn-secondary">{{ __('categories.back_to_categories') }}</a>
@endsection

@section('content')
    <div class="panel" style="max-width:600px;">
        <form method="POST" action="{{ route('categories.store') }}">
            @csrf

            @if($errors->any())
                <div class="alert-error">
                    {{ __('common.fix_errors') }}
                </div>
            @endif

            <div class="field">
                <label for="name">{{ __('common.name') }}</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required>
                @error('name')<div class="field-error">{{ $message }}</div>@enderror
            </div>

            <div class="field">
                <label for="slug">{{ __('categories.slug') }}</label>
                <input id="slug" name="slug" type="text" value="{{ old('slug') }}" placeholder="{{ __('categories.slug_placeholder') }}">
                @error('slug')<div class="field-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn">{{ __('categories.save_category') }}</button>
            </div>
        </form>
    </div>
@endsection
