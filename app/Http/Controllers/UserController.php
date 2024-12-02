<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index()
    {
        $authUser = auth()->user();

        // Admins can view all users; users can view only themselves
        $users = $authUser->role === 'admin'
            ? User::paginate(10)
            : User::where('id', $authUser->id)->paginate(10);

        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $authUser = auth()->user();

        // Only admins can create users
        if ($authUser->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        return view('users.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $authUser = auth()->user();

        // Only admins can store new users
        if ($authUser->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,user',
        ]);

        $validated['password'] = bcrypt($validated['password']);

        User::create($validated);

        return redirect()->route('users.index')->with('success', 'User created successfully');
    }

    /**
     * Display the specified user.
     */
    public function show($id)
    {
        $authUser = auth()->user();

        // Users can view only themselves; admins can view any user
        $user = $authUser->role === 'admin' 
            ? User::findOrFail($id) 
            : User::where('id', $authUser->id)->firstOrFail();

        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit($id)
    {
        $authUser = auth()->user();

        // Users can edit only themselves; admins can edit any user
        $user = $authUser->role === 'admin' 
            ? User::findOrFail($id) 
            : User::where('id', $authUser->id)->firstOrFail();

        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, $id)
    {
        $authUser = auth()->user();

        // Users can update only themselves; admins can update any user
        $user = $authUser->role === 'admin' 
            ? User::findOrFail($id) 
            : User::where('id', $authUser->id)->firstOrFail();

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'sometimes|in:admin,user',
        ]);

        // Remove 'role' from the validated data if the authenticated user is not an admin
        if ($authUser->role !== 'admin') {
            unset($validated['role']);
        }

        // Update password only if provided
        if (!empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']); // Remove password if it's empty
        }

        $user->update($validated);

        return redirect()->route('users.index')->with('success', 'User updated successfully');
    }


    /**
     * Remove the specified user from storage.
     */
    public function destroy($id)
    {
        $authUser = auth()->user();

        // Only admins can delete users
        if ($authUser->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully');
    }
}
