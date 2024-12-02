@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Task Details</h1>
        <ul class="list-group">
            <li class="list-group-item"><strong>Name:</strong> {{ $task->name }}</li>
            <li class="list-group-item"><strong>Phone:</strong> {{ $task->phone }}</li>
            <li class="list-group-item"><strong>City:</strong> {{ $task->city }}</li>
            <li class="list-group-item"><strong>Address:</strong> {{ $task->address }}</li>
            <li class="list-group-item"><strong>Appointment Date:</strong> {{ $task->appointment_date }}</li>
            <li class="list-group-item"><strong>Appointment Time:</strong> {{ $task->appointment_time }}</li>
            <li class="list-group-item"><strong>Assignee:</strong> {{ $task->user->name ?? 'Unassigned' }}</li>
        </ul>
    </div>
    <a href="{{ route('tasks.index') }}" class="btn btn-secondary mt-3">Back to List</a>
@endsection
