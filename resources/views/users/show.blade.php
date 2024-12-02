<!-- resources/views/users/show.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1>User Details</h1>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Name: {{ $user->name }}</h5>
            <h6 class="card-subtitle mb-2 text-muted">Email: {{ $user->email }}</h6>
            <p class="card-text">Role: {{ ucfirst($user->role) }}</p>
        </div>
    </div>
    <a href="{{ route('users.index') }}" class="btn btn-secondary mt-3">Back to List</a>
</div>
@endsection
