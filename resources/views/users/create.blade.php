@extends('layouts.admin')

@section('title', 'Create User')
@section('page-title', 'Create User')
@section('page-subtitle', 'Add a new staff account.')
@section('page-actions')
    <a href="{{ route('users.index') }}" class="btn btn-secondary">Back to users</a>
@endsection

@section('content')
    <div class="panel" style="max-width:720px;">
        @if($errors->any())
            <div class="alert-error">Please fix the errors below.</div>
        @endif

        <form method="POST" action="{{ route('users.store') }}">
            @csrf

            <div class="field">
                <label for="name">Name</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required>
                @error('name')<div class="field-error">{{ $message }}</div>@enderror
            </div>

            <div class="field">
                <label for="email">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required>
                @error('email')<div class="field-error">{{ $message }}</div>@enderror
            </div>

            <div class="field">
                <label for="role">Role</label>
                <select id="role" name="role" required>
                    <option value="admin" @selected(old('role') === 'admin')>Admin</option>
                    <option value="staff" @selected(old('role') === 'staff')>Staff</option>
                </select>
                @error('role')<div class="field-error">{{ $message }}</div>@enderror
            </div>

            <div class="field-row">
                <div class="field">
                    <label for="password">Password</label>
                    <input id="password" name="password" type="password" required>
                    @error('password')<div class="field-error">{{ $message }}</div>@enderror
                </div>
                <div class="field">
                    <label for="password_confirmation">Confirm Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn">Create User</button>
            </div>
        </form>
    </div>
@endsection
