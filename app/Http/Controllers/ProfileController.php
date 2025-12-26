<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\ProfileHistory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(Request $request): View
    {
        $user = $request->user();
        $history = ProfileHistory::where('user_id', $user->id)
            ->with('changedBy')
            ->latest()
            ->limit(10)
            ->get();
            
        return view('profile.show', compact('user', 'history'));
    }

    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(ProfileUpdateRequest $request)
    {
        $user = $request->user();
        $currentUser = Auth::user();
        $validated = $request->validated();
        
        // Track changes
        foreach ($validated as $field => $newValue) {
            $oldValue = $user->$field;
            if ($oldValue != $newValue) {
                ProfileHistory::create([
                    'user_id' => $user->id,
                    'field_name' => $field,
                    'old_value' => $oldValue,
                    'new_value' => $newValue,
                    'changed_by' => $currentUser->id,
                ]);
            }
        }
        
        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.show')->with('success', 'Profile updated successfully.');
    }

    public function destroy(Request $request)
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
