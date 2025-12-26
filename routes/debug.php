<?php

use Illuminate\Support\Facades\Route;

// Debug login route
Route::post('/debug-login', function(\Illuminate\Http\Request $request) {
    \Log::info('Debug login attempt', [
        'email' => $request->email,
        'password_length' => strlen($request->password ?? ''),
        'has_password' => !empty($request->password),
        'request_data' => $request->all()
    ]);
    
    $credentials = $request->only('email', 'password');
    
    if (\Auth::attempt($credentials)) {
        \Log::info('Debug login SUCCESS', ['user' => \Auth::user()->email]);
        return response()->json(['success' => true, 'user' => \Auth::user()->email]);
    } else {
        \Log::error('Debug login FAILED', ['credentials' => $credentials]);
        return response()->json(['success' => false, 'message' => 'Invalid credentials']);
    }
});

// Test route to check if you're logged in
Route::get('/debug-auth', function() {
    return response()->json([
        'authenticated' => \Auth::check(),
        'user' => \Auth::user() ? \Auth::user()->email : null,
        'session_id' => session()->getId()
    ]);
});