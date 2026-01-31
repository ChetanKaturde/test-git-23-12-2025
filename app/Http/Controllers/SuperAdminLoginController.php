<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class SuperAdminLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('superadmin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('superadmin')->attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();
            Log::info('Superadmin login successful', ['email' => $request->email]);
            return redirect()->intended(route('superadmin.dashboard'));
        }

        Log::warning('Superadmin login failed', ['email' => $request->email, 'ip' => $request->ip()]);
        throw ValidationException::withMessages([
            'email' => ['The provided credentials do not match our records.'],
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('superadmin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('superadmin.login');
    }
}
