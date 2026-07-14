@extends('layouts.admin')

@section('title', 'Category Details')
@section('page-title', 'Category Details')
@section('page-subtitle', 'View category information.')
@section('page-actions')
    <a href="{{ route('categories.index') }}" class="btn btn-secondary">Back to Categories</a>
@endsection

@section('content')
    <div class="panel" style="max-width:600px;">
        <div class="field"><strong>Name</strong><p>{{ $category->name }}</p></div>
        <div class="field"><strong>Slug</strong><p>{{ $category->slug }}</p></div>
        <div class="field"><strong>Status</strong><p>{{ $category->status ? 'Active' : 'Inactive' }}</p></div>
    </div>
@endsection
