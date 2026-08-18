@extends('layouts.admin')

@section('title', __('menu.categories'))
@section('page-title', __('menu.categories'))
@section('page-subtitle', __('categories.page_subtitle'))
@section('page-actions')
    <a href="{{ route('categories.create') }}" class="btn">{{ __('categories.add_category') }}</a>
@endsection

@section('content')
    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <div class="panel">
        <div class="table-responsive">
        <table class="table-list" style="width:100%;">
            <thead>
                <tr>
                    <th>{{ __('common.name') }}</th>
                    <th>{{ __('categories.slug') }}</th>
                    <th>{{ __('common.status') }}</th>
                    <th>{{ __('common.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $category)
                    <tr>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->slug }}</td>
                        <td>{{ $category->status ? __('common.active') : __('common.inactive') }}</td>
                        <td>
                            <div style="display:flex; gap:0.5rem; flex-wrap:wrap;">
                                <a href="{{ route('categories.edit', $category) }}" class="btn btn-secondary btn-sm">{{ __('common.edit') }}</a>
                                <form action="{{ route('categories.destroy', $category) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('{{ __('categories.confirm_delete') }}')">{{ __('common.delete') }}</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
