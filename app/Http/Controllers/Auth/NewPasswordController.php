<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request): View
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        try {
            $status = Password::reset(
                $validated,
                $this->resetPasswordCallback($request)
            );

            return $this->handlePasswordResetResponse($status, $request);
        } catch (\Exception $e) {
            \Log::error('Password reset failed: ' . $e->getMessage());
            return back()->withInput($request->only('email'))
                        ->withErrors(['email' => 'Password reset failed. Please try again.']);
        }
    }

    /**
     * Get the password reset callback.
     */
    private function resetPasswordCallback(Request $request): \Closure
    {
        return function ($user) use ($request) {
            $user->forceFill([
                'password' => Hash::make($request->password),
                'remember_token' => Str::random(60),
            ])->save();

            event(new PasswordReset($user));
        };
    }

    /**
     * Handle the password reset response.
     */
    private function handlePasswordResetResponse(string $status, Request $request): RedirectResponse
    {
        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('status', __($status));
        }
        
        return back()->withInput($request->only('email'))
                    ->withErrors(['email' => __($status)]);
    }
}
