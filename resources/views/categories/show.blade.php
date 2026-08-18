@extends('layouts.admin')

@section('title', __('categories.show_title'))
@section('page-title', __('categories.show_title'))
@section('page-subtitle', __('categories.show_subtitle'))
@section('page-actions')
    <a href="{{ route('categories.index') }}" class="btn btn-secondary">{{ __('categories.back_to_categories') }}</a>
@endsection

@section('content')
    <div class="panel" style="max-width:600px;">
        <div class="field"><strong>{{ __('common.name') }}</strong><p>{{ $category->name }}</p></div>
        <div class="field"><strong>{{ __('categories.slug') }}</strong><p>{{ $category->slug }}</p></div>
        <div class="field"><strong>{{ __('common.status') }}</strong><p>{{ $category->status ? __('common.active') : __('common.inactive') }}</p></div>
    </div>
@endsection
