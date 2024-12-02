@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Create Task</h1>
        <form action="{{ route('tasks.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="timestamp">TimeStamp</label>
                <input type="text" name="timestamp" id="timestamp" class="form-control">
            </div>
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="text" name="phone" id="phone" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="city">City</label>
                <input type="text" name="city" id="city" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <textarea name="address" id="address" class="form-control" required></textarea>
            </div>
            <div class="form-group">
                <label for="appointment_date">Appointment Date</label>
                <input type="date" name="appointment_date" id="appointment_date" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="appointment_time">Appointment Time</label>
                <input type="text" name="appointment_time" id="appointment_time" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="quality_report">Quality Report</label>
                <input type="text" name="quality_report" id="quality_report" class="form-control">
            </div>
            <div class="form-group">
                <label for="google_maps">Google Maps</label>
                <input type="text" name="google_maps" id="google_maps" class="form-control">
            </div>
            <div class="form-group">
                <label for="recording_link">Recording Link</label>
                <input type="text" name="recording_link" id="recording_link" class="form-control">
            </div>
            <div class="form-group">
                <label for="prefect_pitch">Perfect Pitch</label>
                <input type="text" name="prefect_pitch" id="prefect_pitch" class="form-control">
            </div>
            <div class="form-group">
                <label for="assignee">Assignee</label>
                <select name="assignee" id="assignee" class="form-control">
                    <option value="">Unassigned</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="comment">Comment</label>
                <input type="text" name="comment" id="comment" class="form-control">
            </div>
            <button type="submit" class="btn btn-success">Create Task</button>
        </form>
    </div>
@endsection
