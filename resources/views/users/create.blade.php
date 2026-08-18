@extends('layouts.admin')

@section('title', __('users.create_title'))
@section('page-title', __('users.create_title'))
@section('page-subtitle', __('users.create_subtitle'))
@section('page-actions')
    <a href="{{ route('users.index') }}" class="btn btn-secondary">{{ __('users.back_to_users') }}</a>
@endsection

@section('content')
    <div class="panel" style="max-width:720px;">
        @if($errors->any())
            <div class="alert-error">{{ __('common.fix_errors') }}</div>
        @endif

        <form method="POST" action="{{ route('users.store') }}">
            @csrf

            <div class="field">
                <label for="name">{{ __('common.name') }}</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required>
                @error('name')<div class="field-error">{{ $message }}</div>@enderror
            </div>

            <div class="field">
                <label for="email">{{ __('common.email') }}</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required>
                @error('email')<div class="field-error">{{ $message }}</div>@enderror
            </div>

            <div class="field">
                <label for="role">{{ __('menu.role') }}</label>
                <select id="role" name="role" required>
                    <option value="admin" @selected(old('role') === 'admin')>{{ __('menu.role_admin') }}</option>
                    <option value="staff" @selected(old('role') === 'staff')>{{ __('menu.role_staff') }}</option>
                </select>
                @error('role')<div class="field-error">{{ $message }}</div>@enderror
            </div>

            <div class="field-row">
                <div class="field">
                    <label for="password">{{ __('users.password') }}</label>
                    <input id="password" name="password" type="password" required>
                    @error('password')<div class="field-error">{{ $message }}</div>@enderror
                </div>
                <div class="field">
                    <label for="password_confirmation">{{ __('users.confirm_password') }}</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn">{{ __('users.create_user_btn') }}</button>
            </div>
        </form>
    </div>
@endsection
