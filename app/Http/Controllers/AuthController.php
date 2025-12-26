<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Show the login form
     */
    public function showLoginForm()
    {
        return Auth::check()
            ? $this->redirectToDashboard()
            : view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (!$user || !$this->validateUser($user, $validated['password'], $request)) {
            return $this->failedLoginResponse($request, 'The provided credentials are incorrect.');
        }

        if (!$user->is_active) {
            return $this->failedLoginResponse($request, 'Your account has been deactivated. Please contact administrator.');
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return $request->expectsJson()
            ? response()->json([
                'success' => true,
                'message' => 'Login successful.',
                'redirect' => route('dashboard')
            ])
            : redirect()->route('dashboard');
    }

    /**
     * Validate user credentials
     */
    private function validateUser(User $user, $password, $request)
    {
        return Hash::check($password, $user->password);
    }

    /**
     * Handle failed login response
     */
    private function failedLoginResponse(Request $request, $message)
    {
        if ($request->expectsJson()) {
            return response()->json(['success' => false, 'error' => $message], 401);
        }

        throw ValidationException::withMessages(['email' => [$message]]);
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return $request->expectsJson()
            ? response()->json([
                'success' => true,
                'message' => 'Logged out successfully.',
                'redirect' => route('login')
            ])
            : redirect()->route('login')->with('success', 'You have been logged out successfully.');
    }

    /**
     * Get redirect URL based on user role
     */
    private function getRedirectUrl(User $user = null)
    {
        return route('dashboard');
    }

    /**
     * Redirect to appropriate dashboard
     */
    private function redirectToDashboard(User $user = null)
    {
        return redirect($this->getRedirectUrl($user));
    }
}
