@extends('layouts.admin')

@section('title', 'Edit Category')
@section('page-title', 'Edit Category')
@section('page-subtitle', 'Update category details and status.')
@section('page-actions')
    <a href="{{ route('categories.index') }}" class="btn btn-secondary">Back to Categories</a>
@endsection

@section('content')
    <div class="panel" style="max-width:600px;">
        <form method="POST" action="{{ route('categories.update', $category) }}">
            @csrf
            @method('PUT')

            @if($errors->any())
                <div class="alert-error">
                    Please fix the errors below.
                </div>
            @endif

            <div class="field">
                <label for="name">Name</label>
                <input id="name" name="name" type="text" value="{{ old('name', $category->name) }}" required>
                @error('name')<div class="field-error">{{ $message }}</div>@enderror
            </div>

            <div class="field">
                <label for="slug">Slug</label>
                <input id="slug" name="slug" type="text" value="{{ old('slug', $category->slug) }}" placeholder="auto-generated">
                @error('slug')<div class="field-error">{{ $message }}</div>@enderror
            </div>

            <div class="field">
                <label for="status">Status</label>
                <select id="status" name="status">
                    <option value="1" @if(old('status', $category->status)) selected @endif>Active</option>
                    <option value="0" @if(! old('status', $category->status)) selected @endif>Inactive</option>
                </select>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn">Update Category</button>
            </div>
        </form>
    </div>
@endsection
