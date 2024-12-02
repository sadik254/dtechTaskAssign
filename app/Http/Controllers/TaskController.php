<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the tasks.
     */
    public function index()
    {
        if (Auth::user()->role === 'admin') {
            // Admin can see all tasks
            $tasks = Task::with('user')->paginate(10);
        } else {
            // User can see only tasks assigned to them
            $tasks = Task::where('assignee', Auth::id())->with('user')->paginate(10);
        }

        return view('tasks.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new task.
     */
    public function create()
    {
        // Only admin can access this
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $users = User::all();

        return view('tasks.create', compact('users'));
    }

    /**
     * Store a newly created task in storage.
     */
    public function store(Request $request)
    {
        // Only admin can create tasks
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        // Validate incoming data
        $validated = $request->validate([
            'timestamp' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'city' => 'required|string|max:100',
            'address' => 'required|string',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required|string|max:255',
            'quality_report' => 'nullable|string',
            'google_maps' => 'nullable|string|max:255',
            'recording_link' => 'nullable|string|max:255',
            'prefect_pitch' => 'nullable|string|max:255',
            'assignee' => 'nullable|exists:users,id',
            'comment' => 'nullable|string',
        ]);

        // Create the task
        Task::create($validated);

        return redirect()->route('tasks.index')->with('success', 'Task created successfully');
    }

    /**
     * Display the specified task.
     */
    public function show($id)
    {
        // Find the task with the given ID and its associated user
        $task = Task::with('user')->findOrFail($id);

        // Admin can view all tasks, users can view only their tasks
        if (Auth::user()->role !== 'admin' && $task->user->id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified task.
     */
    public function edit($id)
    {
        $task = Task::findOrFail($id);
        $users = User::all();

        // Admin can edit all tasks, users can edit only their tasks
        if (Auth::user()->role !== 'admin' && $task->assignee !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('tasks.edit', compact('task', 'users'));
    }

    /**
     * Update the specified task in storage.
     */
    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        // Admin can update all tasks, users can update only their tasks
        if (Auth::user()->role !== 'admin' && $task->assignee !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        // If the user is not an admin, prevent updating the 'assignee' field
        if (Auth::user()->role !== 'admin') {
            unset($request['assignee']); // Remove assignee from the validated data
        }

        // Validate incoming data
        $validated = $request->validate([
            'timestamp' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'city' => 'required|string|max:100',
            'address' => 'required|string',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required|string|max:255',
            'quality_report' => 'nullable|string',
            'google_maps' => 'nullable|string|max:255',
            'recording_link' => 'nullable|string|max:255',
            'prefect_pitch' => 'nullable|string|max:255',
            'assignee' => 'nullable|exists:users,id',
            'comment' => 'nullable|string',
        ]);

        // Update the task
        $task->update($validated);

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully');
    }

    /**
     * Remove the specified task from storage.
     */
    public function destroy($id)
    {
        $task = Task::findOrFail($id);

        // Only admin can delete tasks
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        // Delete the task
        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully');
    }

    /**
     * Download tasks as a CSV file.
     */
//     public function downloadCsv()
// {
//     // Determine the tasks based on user role
//     $tasks = Auth::user()->role === 'admin'
//         ? Task::with('user')->get()
//         : Task::where('assignee', Auth::id())->with('user')->get();

//     // Define the CSV headers
//     $headers = [
//         'Content-Type' => 'text/csv',
//         'Content-Disposition' => 'attachment; filename="tasks.csv"',
//     ];

//     // Create a callback to generate the CSV
//     $callback = function () use ($tasks) {
//         $file = fopen('php://output', 'w');

//         // Write the column headers
//         fputcsv($file, [
//             'ID',
//             'Timestamp',
//             'Name',
//             'Phone',
//             'City',
//             'Address',
//             'Appointment Date',
//             'Appointment Time',
//             'Quality Report',
//             'Google Maps',
//             'Recording Link',
//             'Perfect Pitch',
//             'Assignee',
//             'Comment',
//             'Created At',
//             'Updated At',
//         ]);

//         // Write each task as a row
//         foreach ($tasks as $task) {
//             fputcsv($file, [
//                 $task->id,
//                 $task->timestamp,
//                 $task->name,
//                 $task->phone,
//                 $task->city,
//                 $task->address,
//                 $task->appointment_date,
//                 $task->appointment_time,
//                 $task->quality_report,
//                 $task->google_maps,
//                 $task->recording_link,
//                 $task->perfect_pitch,
//                 optional($task->user)->name,
//                 $task->comment,
//                 $task->created_at,
//                 $task->updated_at,
//             ]);
//         }

//         fclose($file);
//     };

//     // Return a streamed response
//     return response()->stream($callback, 200, $headers);
// }

// public function downloadCsv()
//     {
//         if (Auth::user()->role === 'admin') {
//             // Admin can see all tasks
//             $tasks = Task::with('user')->get();
//         } else {
//             // User can see only tasks assigned to them
//             $tasks = Task::where('assignee', Auth::id())->with('user')->get();
//         }

//         // Create a CSV writer instance
//         $csv = Writer::createFromFileObject(new SplTempFileObject());

//         // Insert the header row
//         $csv->insertOne([
//             'ID', 'Timestamp', 'Name', 'Phone', 'City', 'Address',
//             'Appointment Date', 'Appointment Time', 'Quality Report', 'Google Maps',
//             'Recording Link', 'Perfect Pitch', 'Assignee', 'Comment', 'Created At', 'Updated At'
//         ]);

//         // Insert the tasks into the CSV
//         foreach ($tasks as $task) {
//             $csv->insertOne([
//                 $task->id,
//                 $task->timestamp,
//                 $task->name,
//                 $task->phone,
//                 $task->city,
//                 $task->address,
//                 $task->appointment_date,
//                 $task->appointment_time,
//                 $task->quality_report,
//                 $task->google_maps,
//                 $task->recording_link,
//                 $task->perfect_pitch,
//                 optional($task->user)->name, // Assignee name
//                 $task->comment,
//                 $task->created_at,
//                 $task->updated_at,
//             ]);
//         }

//         // Output the CSV as a downloadable file
//         $filename = 'tasks_' . date('Ymd_His') . '.csv';
//         return response((string) $csv, 200, [
//             'Content-Type' => 'text/csv',
//             'Content-Disposition' => "attachment; filename=\"$filename\"",
//         ]);
//     }
}
