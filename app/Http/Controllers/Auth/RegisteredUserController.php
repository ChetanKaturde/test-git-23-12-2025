<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Business;
use App\Models\Invitation;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Str;

class RegisteredUserController extends Controller
{
    public function create(Request $request): View
    {
        $invitation = null;
        
        // Check if there's a valid invitation token
        if ($request->has('token')) {
            $invitation = Invitation::where('token', $request->token)
                ->where('expires_at', '>', now())
                ->first();
        }
        
        return view('auth.register', compact('invitation'));
    }

    public function store(Request $request): RedirectResponse
    {
        $invitation = null;
        
        // Check if registering via invitation
        if ($request->has('token')) {
            $invitation = Invitation::where('token', $request->token)
                ->where('expires_at', '>', now())
                ->first();
                
            if (!$invitation) {
                return back()->withErrors(['token' => 'Invalid or expired invitation.']);
            }
        }
        
        if ($invitation) {
            // Invitation-based registration
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);
            
            // Ensure email matches invitation
            if ($request->email !== $invitation->email) {
                return back()->withErrors(['email' => 'Email must match the invitation.']);
            }
            
            // Create team member user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $invitation->role,
                'is_active' => true,
                'business_id' => $invitation->business_id,
                'email_verified_at' => now(),
            ]);
            
            // Delete the invitation
            $invitation->delete();
            
            event(new Registered($user));
            Auth::login($user);
            
            return redirect()->route('dashboard')->with('success', 'Welcome to the team!');
        } else {
            // Regular business owner registration
            $request->validate([
                'business_name' => ['required', 'string', 'max:255'],
                'business_phone' => ['required', 'string', 'regex:/^[6-9][0-9]{9}$/', 'size:10'],
                'business_address' => ['required', 'string', 'max:500'],
                'subscription_tier' => ['required', 'in:billing_sales,full_erp'],
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            // Create business with auto-generated slug and subscription tier
            $business = Business::create([
                'name' => $request->business_name,
                'slug' => Str::slug($request->business_name) . '-' . Str::random(6),
                'phone' => $request->business_phone,
                'address' => $request->business_address,
                'email' => $request->email,
                'is_active' => true,
                'subscription_plan' => 'free',
                'subscription_tier' => $request->subscription_tier,
            ]);

            // Create owner user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'admin',
                'is_active' => true,
                'business_id' => $business->id,
                'email_verified_at' => now(),
            ]);

            event(new Registered($user));
            Auth::login($user);

            // Redirect to dashboard (no subdomain)
            return redirect()->route('dashboard')->with('success', 'Welcome to Monitorbizz! Your workshop is ready.');
        }
    }
}