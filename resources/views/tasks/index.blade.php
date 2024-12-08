@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Tasks</h1>
        @if (Auth::user()->role === 'admin')
            <a href="{{ route('tasks.create') }}" class="btn btn-primary mb-3">Create Task</a>
        @endif
        <!-- Replace the backend download button -->
        <button id="download-csv" class="btn btn-primary mb-3">Download CSV</button>
        <div style="overflow-x: auto; white-space: nowrap;">
        <table class="table" id="task-table" white-space: nowrap>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>TimeStamp</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>City</th>
                    <th>Address</th>
                    <th style="white-space: nowrap;">Appointment Date</th>
                    <th style="white-space: nowrap;">Appointment Time</th>
                    <th style="white-space: nowrap;">Quality Report</th>
                    <th style="white-space: nowrap;">Google Maps</th>
                    <th style="white-space: nowrap;">Recording Link</th>
                    <th style="white-space: nowrap;">Perfect Pitch</th>
                    <th>Assignee</th>
                    <th>Comment</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tasks as $task)
                    <tr>
                        <td>{{ $task->id }}</td>
                        <td>{{ $task->timestamp }}</td>
                        <td>{{ $task->name }}</td>
                        <td>{{ $task->phone }}</td>
                        <td>{{ $task->city }}</td>
                        <td>{{ $task->address }}</td>
                        <td>{{ $task->appointment_date }}</td>
                        <td>{{ $task->appointment_time }}</td>
                        <td>{{ $task->quality_report }}</td>
                        <td>{{ $task->google_maps }}</td>
                        <td>{{ $task->recording_link }}</td>
                        <td>{{ $task->prefect_pitch }}</td>
                        <td>{{ $task->user->name ?? 'Unassigned'}}</td>
                        <td>{{ $task->comment }}</td>
                        <td class="d-flex">
                            <a href="{{ route('tasks.show', $task->id) }}" class="btn btn-info btn-sm mr-2">View</a>
                            
                            @if (Auth::user()->role === 'admin' || Auth::id() === $task->assignee)
                                <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-warning btn-sm mr-2">Edit</a>
                            @endif
                            
                            @if (Auth::user()->role === 'admin')
                                <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>
        {{ $tasks->links() }}
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const downloadButton = document.getElementById('download-csv');
        const table = document.getElementById('task-table');

        if (downloadButton && table) {
            downloadButton.addEventListener('click', function () {
                const rows = Array.from(table.querySelectorAll('tr'));
                let csvContent = [];

                rows.forEach(row => {
                    // Select all cells except the last one (Actions column)
                    const cells = Array.from(row.querySelectorAll('td, th')).slice(0, -1);
                    const rowData = cells.map(cell => {
                        // Escape double quotes and remove newlines
                        let cellText = cell.innerText.replace(/"/g, '""').replace(/\n/g, ' ').trim();
                        return `"${cellText}"`;
                    });
                    csvContent.push(rowData.join(','));
                });

                const csvString = csvContent.join('\n');
                const blob = new Blob([csvString], { type: 'text/csv;charset=utf-8;' });
                
                const link = document.createElement('a');
                link.href = URL.createObjectURL(blob);
                link.download = `tasks_${new Date().toISOString().slice(0, 10)}.csv`;
                
                // Append to body, click, and remove
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);

                // Clean up
                URL.revokeObjectURL(link.href);
            });
        } else {
            console.error('Download button or table not found');
        }
    });
</script>
@endsection
