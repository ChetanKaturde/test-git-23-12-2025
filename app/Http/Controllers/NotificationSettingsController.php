<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationSettingsController extends Controller
{
    public function edit()
    {
        $preferences = Auth::user()->getNotificationPreferences();
        return view('notifications.settings', compact('preferences'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'email' => 'boolean',
            'sms' => 'boolean',
            'dashboard' => 'boolean',
        ]);

        Auth::user()->updateNotificationPreferences($validated);
        return redirect()->back()->with('success', 'Preferences updated.');
    }
}
