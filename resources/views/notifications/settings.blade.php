@extends('layouts.app')
@section('title', 'Notification Settings')

@section('content')
<div class="container">
    <h3>Notification Preferences</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('notifications.settings.update') }}">
        @csrf
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="email" value="1" {{ $preferences['email'] ? 'checked' : '' }}>
            <label class="form-check-label">Email Notifications</label>
        </div>

        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="sms" value="1" {{ $preferences['sms'] ? 'checked' : '' }}>
            <label class="form-check-label">SMS Notifications</label>
        </div>

        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="dashboard" value="1" {{ $preferences['dashboard'] ? 'checked' : '' }}>
            <label class="form-check-label">Dashboard Notifications</label>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Save Preferences</button>
    </form>
</div>
@endsection
