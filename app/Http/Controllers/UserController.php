<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('role')->orderBy('name')->get();
        return view('pages.admin.users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role'     => 'required|in:admin,manager,user',
            'phone'    => 'nullable|string|max:20',
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => $validated['role'],
            'status'   => 'active',
            'phone'    => $validated['phone'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User created successfully.',
            'user'    => [
                'id'         => $user->id,
                'name'       => $user->name,
                'email'      => $user->email,
                'phone'      => $user->phone,
                'role'       => $user->role,
                'role_label' => $user->getRoleLabel(),
                'role_badge' => $user->getRoleBadgeClass(),
                'status'     => $user->status,
                'status_badge' => $user->getStatusBadgeClass(),
                'created_at' => $user->created_at->format('d M Y'),
                'is_me'      => false,
            ],
        ]);
    }

    public function editData(User $user)
    {
        return response()->json([
            'id'     => $user->id,
            'name'   => $user->name,
            'email'  => $user->email,
            'role'   => $user->role,
            'phone'  => $user->phone,
            'status' => $user->status,
        ]);
    }

    public function update(Request $request, User $user)
    {
        // 
        if ($user->id === auth()->id() && $request->role !== auth()->user()->role) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot change your own role.',
            ], 403);
        }

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'role'     => 'required|in:admin,manager,user',
            'phone'    => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = [
            'name'  => $validated['name'],
            'email' => $validated['email'],
            'role'  => $validated['role'],
            'phone' => $validated['phone'] ?? null,
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);
        $user->refresh();

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully.',
            'user'    => [
                'id'           => $user->id,
                'name'         => $user->name,
                'email'        => $user->email,
                'phone'        => $user->phone,
                'role'         => $user->role,
                'role_label'   => $user->getRoleLabel(),
                'role_badge'   => $user->getRoleBadgeClass(),
                'status'       => $user->status,
                'status_badge' => $user->getStatusBadgeClass(),
                'created_at'   => $user->created_at->format('d M Y'),
            ],
        ]);
    }

    public function toggle(User $user)
    {
        if ($user->id === auth()->id()) {
            return response()->json(['success' => false, 'message' => 'You cannot deactivate your own account.'], 403);
        }

        $user->update([
            'status' => $user->status === 'active' ? 'inactive' : 'active',
        ]);

        $user->refresh();

        return response()->json([
            'success'      => true,
            'message'      => 'User status updated.',
            'status'       => $user->status,
            'status_badge' => $user->getStatusBadgeClass(),
        ]);
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return response()->json(['success' => false, 'message' => 'You cannot delete your own account.'], 403);
        }

        $user->delete();

        return response()->json(['success' => true, 'message' => 'User deleted.']);
    }
}
