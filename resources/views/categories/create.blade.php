@extends('layouts.admin')

@section('title', 'Create Category')
@section('page-title', 'Create Category')
@section('page-subtitle', 'Add a new category for your menu.')
@section('page-actions')
    <a href="{{ route('categories.index') }}" class="btn btn-secondary">Back to Categories</a>
@endsection

@section('content')
    <div class="panel" style="max-width:600px;">
        <form method="POST" action="{{ route('categories.store') }}">
            @csrf

            @if($errors->any())
                <div class="alert-error">
                    Please fix the errors below.
                </div>
            @endif

            <div class="field">
                <label for="name">Name</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required>
                @error('name')<div class="field-error">{{ $message }}</div>@enderror
            </div>

            <div class="field">
                <label for="slug">Slug</label>
                <input id="slug" name="slug" type="text" value="{{ old('slug') }}" placeholder="auto-generated">
                @error('slug')<div class="field-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn">Save Category</button>
            </div>
        </form>
    </div>
@endsection
