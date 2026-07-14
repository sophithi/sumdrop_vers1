@extends('layouts.admin')

@section('title', 'Edit User')
@section('page-title', 'Edit User')
@section('page-subtitle', 'Update user details and role.')
@section('page-actions')
    <a href="{{ route('users.index') }}" class="btn btn-secondary">Back to users</a>
@endsection

@section('content')
    <div class="panel" style="max-width:720px;">
        @if($errors->any())
            <div class="alert-error">Please fix the errors below.</div>
        @endif

        <form method="POST" action="{{ route('users.update', $user) }}">
            @csrf
            @method('PUT')

            <div class="field">
                <label for="name">Name</label>
                <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required>
                @error('name')<div class="field-error">{{ $message }}</div>@enderror
            </div>

            <div class="field">
                <label for="email">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required>
                @error('email')<div class="field-error">{{ $message }}</div>@enderror
            </div>

            <div class="field">
                <label for="role">Role</label>
                <select id="role" name="role" required>
                    <option value="admin" @selected(old('role', $user->role) === 'admin')>Admin</option>
                    <option value="staff" @selected(old('role', $user->role) === 'staff')>Staff</option>
                </select>
                @error('role')<div class="field-error">{{ $message }}</div>@enderror
            </div>

            <div class="field-row">
                <div class="field">
                    <label for="password">New Password</label>
                    <input id="password" name="password" type="password">
                    @error('password')<div class="field-error">{{ $message }}</div>@enderror
                </div>
                <div class="field">
                    <label for="password_confirmation">Confirm Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password">
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn">Save Changes</button>
            </div>
        </form>
    </div>
@endsection
