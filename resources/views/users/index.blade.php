@extends('layouts.admin')

@section('title', __('menu.users'))
@section('page-title', __('users.page_title'))
@section('page-subtitle', __('users.page_subtitle'))
@section('page-actions')
    <a href="{{ route('users.create') }}" class="btn">{{ __('users.new_user') }}</a>
@endsection

@push('styles')
<style>
    .product-name-cell { display: flex; align-items: center; gap: 0.75rem; }
    .badge-active { background: #dcfce7; color: #166534; }
    .badge-inactive { background: #f8fafc; color: #475569; }
</style>
@endpush

@section('content')
    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <div class="panel">
        @if($users->count())
            <div class="table-responsive">
            <table class="table-list" style="width:100%;">
                <thead>
                    <tr>
                        <th>{{ __('users.user_name') }}</th>
                        <th>{{ __('common.email') }}</th>
                        <th>{{ __('menu.role') }}</th>
                        <th>{{ __('common.status') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $u)
                        <tr>
                            <td>{{ $u->name }}</td>
                            <td>{{ $u->email }}</td>
                            <td>{{ $u->role === 'admin' ? __('menu.role_admin') : __('menu.role_staff') }}</td>
                            <td>
                                <span class="badge {{ $u->status ? 'badge-active' : 'badge-inactive' }}">{{ $u->status ? __('common.active') : __('common.inactive') }}</span>
                            </td>
                            <td>
                                <div style="display:flex; gap:0.5rem; flex-wrap:wrap; justify-content:flex-end;">
                                    <a href="{{ route('users.edit', $u) }}" class="btn btn-secondary btn-sm">{{ __('common.edit') }}</a>
                                    <form action="{{ route('users.destroy', $u) }}" method="POST" onsubmit="return confirm('{{ __('users.confirm_delete') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">{{ __('common.delete') }}</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty-state">
                <p>{{ __('users.no_users_yet') }}</p>
                <a href="{{ route('users.create') }}" class="btn">{{ __('users.add_first_user') }}</a>
            </div>
        @endif
    </div>
@endsection
