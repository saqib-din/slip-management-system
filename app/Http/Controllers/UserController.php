<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('role')->orderBy('name')->get()->map(fn($user) => [
            'id'         => $user->id,
            'name'       => $user->name,
            'email'      => $user->email,
            'phone'      => $user->phone,
            'role'       => $user->role,
            'role_label' => $user->getRoleLabel(),
            'role_badge' => $user->getRoleBadgeClass(),
            'status'     => $user->status,
            'is_me'      => $user->id === auth()->id(),
        ]);

        return Inertia::render('Users/Index', [
            'users' => $users,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role'     => 'required|in:admin,manager,user',
            'phone'    => 'nullable|string|max:20',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
            'status'   => 'active',
            'phone'    => $request->phone,
        ]);

        return back()->with('success', 'User created successfully.');
    }

    public function update(Request $request, User $user)
    {
        // Apni khud ki role change nahi kar sakte
        if ($user->id === auth()->id() && $request->role !== auth()->user()->role) {
            return back()->withErrors(['role' => 'You cannot change your own role.']);
        }

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'role'     => 'required|in:admin,manager,user',
            'phone'    => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = [
            'name'  => $request->name,
            'email' => $request->email,
            'role'  => $request->role,
            'phone' => $request->phone,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return back()->with('success', 'User updated successfully.');
    }

    public function toggle(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot deactivate your own account.');
        }

        $user->update([
            'status' => $user->status === 'active' ? 'inactive' : 'active',
        ]);

        return back()->with('success', 'User status updated.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return back()->with('success', 'User deleted.');
    }
}
