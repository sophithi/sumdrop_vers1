@extends('layouts.admin')

@section('title', 'Users')
@section('page-title', 'Manage users')
@section('page-subtitle', 'Add, edit, or remove staff accounts.')
@section('page-actions')
    <a href="{{ route('users.create') }}" class="btn">+ New user</a>
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
                        <th>User Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $u)
                        <tr>
                            <td>{{ $u->name }}</td>
                            <td>{{ $u->email }}</td>
                            <td>{{ ucfirst($u->role) }}</td>
                            <td>
                                <span class="badge {{ $u->status ? 'badge-active' : 'badge-inactive' }}">{{ $u->status ? 'Active' : 'Inactive' }}</span>
                            </td>
                            <td>
                                <div style="display:flex; gap:0.5rem; flex-wrap:wrap; justify-content:flex-end;">
                                    <a href="{{ route('users.edit', $u) }}" class="btn btn-secondary btn-sm">Edit</a>
                                    <form action="{{ route('users.destroy', $u) }}" method="POST" onsubmit="return confirm('Delete this user?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty-state">
                <p>No users yet.</p>
                <a href="{{ route('users.create') }}" class="btn">+ Add your first user</a>
            </div>
        @endif
    </div>
@endsection
