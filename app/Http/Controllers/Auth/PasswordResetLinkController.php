<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
        ]);

        try {
            $status = Password::sendResetLink($validated);

            if ($status === Password::RESET_LINK_SENT) {
                return back()->with('status', __($status));
            }

            return back()->withInput($request->only('email'))
                        ->withErrors(['email' => __($status)]);
        } catch (\Exception $e) {
            \Log::error('Password reset link failed: ' . $e->getMessage());
            return back()->withInput($request->only('email'))
                        ->withErrors(['email' => 'Failed to send reset link. Please try again.']);
        }
    }
}
