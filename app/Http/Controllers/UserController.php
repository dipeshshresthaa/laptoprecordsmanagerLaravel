<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('employee')->get();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $eligibleEmployees = Employee::doesntHave('userAccount')->get();
        return view('users.form', compact('eligibleEmployees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'username' => 'required|string|unique:users,username',
            'password' => 'required|string|min:4',
        ]);

        // C# SHA256 + Salt Cryptography Implementation
        $salt = base64_encode(random_bytes(16));
        $combinedString = $request->password . $salt;
        $hashedBytes = hash('sha256', $combinedString, true);
        $passwordHash = base64_encode($hashedBytes);

        User::create([
            'id' => Str::uuid()->toString(),
            'employee_id' => $request->employee_id,
            'username' => $request->username,
            'password_hash' => $passwordHash,
            'salt' => $salt,
            'is_admin' => $request->boolean('is_admin'),
            'is_active' => true,
        ]);

        return redirect()->route('users.index')->with('success', 'User account created successfully.');
    }

    public function edit(User $user)
    {
        return view('users.form', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'username' => 'required|string|unique:users,username,' . $user->id,
            'password' => 'nullable|string|min:4',
        ]);

        // CRITICAL SECURITY RULE: Prevent activating an account if the employee is inactive
        $wantsToBeActive = $request->boolean('is_active');

        if ($wantsToBeActive && !$user->employee->is_active) {
            return back()->withInput()->withErrors([
                'is_active' => 'Action denied: You cannot activate a user account linked to an offboarded employee.'
            ]);
        }

        $user->username = $request->username;
        $user->is_admin = $request->boolean('is_admin');
        $user->is_active = $wantsToBeActive;

        if ($request->filled('password')) {
            $salt = base64_encode(random_bytes(16));
            $combinedString = $request->password . $salt;
            $hashedBytes = hash('sha256', $combinedString, true);

            $user->salt = $salt;
            $user->password_hash = base64_encode($hashedBytes);

            // Force them to change the new password you just gave them
            $user->requires_password_change = true;
        }

        $user->save();

        return redirect()->route('users.index')->with('success', 'User account updated successfully.');
    }
}
