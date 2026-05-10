<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('employees.index');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        // 1. Validate incoming request (Using Username, not Email)
        $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // 2. Fetch the user by username
        $user = User::where('username', '=', $request->username, 'and')->first();

        if (!$user) {
            return back()->withErrors([
                'username' => 'Invalid username or password.',
            ])->onlyInput('username');
        }

        // 3. Check if the account is active
        if (!$user->is_active) {
            return back()->withErrors([
                'username' => 'Account is inactive. Contact administrator.',
            ])->onlyInput('username');
        }

        // 4. Replicate C# Hash Logic: Convert.ToBase64String(SHA256.ComputeHash(Encoding.UTF8.GetBytes(password + salt)))
        $combinedString = $request->password . $user->salt;
        $hashedBytes = hash('sha256', $combinedString, true); // 'true' returns raw binary data
        $inputHash = base64_encode($hashedBytes);

        // 5. Compare hashes
        if ($inputHash !== $user->password_hash) {
            return back()->withErrors([
                'username' => 'Invalid username or password.',
            ])->onlyInput('username');
        }

        // 6. Log the user in and regenerate session
        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->intended(route('employees.index'))->with('success', 'Login successful');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'You have been logged out successfully.');
    }
}