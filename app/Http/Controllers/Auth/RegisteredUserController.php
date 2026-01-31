<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Business;
use App\Models\Invitation;
use App\Models\SalesRepresentative;
use App\Models\SubscriptionPlan;
use App\Models\Subscription;
use App\Models\SubscriptionFeatureUsage;
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

        $plans = SubscriptionPlan::active()->get();

        return view('auth.register', compact('invitation', 'plans'));
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
                'plan_id' => ['required', 'exists:subscription_plans,id'],
                'user_count' => ['required', 'integer', 'min:1'],
                'sales_representative_id' => ['nullable', 'exists:sales_representatives,representative_id'],
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            // Validate user count against plan limits
            $plan = SubscriptionPlan::find($request->plan_id);
            if ($request->user_count < $plan->min_users || $request->user_count > $plan->max_users) {
                return back()->withErrors(['user_count' => "User count must be between {$plan->min_users} and {$plan->max_users} for this plan."]);
            }

            // Create business with auto-generated slug
            $business = Business::create([
                'name' => $request->business_name,
                'slug' => Str::slug($request->business_name) . '-' . Str::random(6),
                'phone' => $request->business_phone,
                'address' => $request->business_address,
                'email' => $request->email,
                'is_active' => true,
                'sales_representative_id' => $request->sales_representative_id,
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

            // Create subscription
            $subscription = $this->createSubscription($business, $plan, $request->user_count, $request->sales_representative_id);

            event(new Registered($user));
            Auth::login($user);

            // Handle payment
            if ($subscription->amount > 0) {
                if (config('services.razorpay.key') && config('services.razorpay.secret')) {
                    // Redirect to payment
                    return redirect()->route('subscription.payment', $subscription->id);
                } else {
                    // Skip payment, mark as paid and redirect with fail-safe message
                    return redirect()->route('dashboard')->with('success', 'Razorpay credentials not found. Submitting registration directly.');
                }
            }

            // Free plan - redirect to dashboard
            return redirect()->route('dashboard')->with('success', 'Welcome to Monitorbizz! Your workshop is ready.');
        }
    }

    private function createSubscription(Business $business, SubscriptionPlan $plan, int $userCount, ?string $salesRepId = null)
    {
        // Create plan snapshot
        $planSnapshot = [
            'name' => $plan->name,
            'price_per_user' => $plan->price_per_user,
            'min_users' => $plan->min_users,
            'max_users' => $plan->max_users,
            'features' => []
        ];

        foreach ($plan->planFeatures as $planFeature) {
            $planSnapshot['features'][$planFeature->feature->key] = [
                'enabled' => $planFeature->enabled,
                'limit' => $planFeature->quantity_limit
            ];
        }

        // Calculate amount
        $amount = $plan->price_per_user * $userCount;

        // Create subscription
        $subscription = Subscription::create([
            'business_id' => $business->id,
            'plan_id' => $plan->id,
            'user_count' => $userCount,
            'start_date' => now(),
            'end_date' => now()->addMonth(),
            'status' => 'active',
            'plan_snapshot' => $planSnapshot,
            'sales_representative_id' => $salesRepId ? SalesRepresentative::where('representative_id', $salesRepId)->first()?->id : null,
            'amount' => $amount,
            'type' => 'new',
        ]);

        // Create feature usage records
        foreach ($plan->planFeatures as $planFeature) {
            SubscriptionFeatureUsage::create([
                'subscription_id' => $subscription->id,
                'feature_name' => $planFeature->feature->key,
                'used_count' => 0,
                'limit' => $planFeature->quantity_limit,
            ]);
        }

        return $subscription;
    }
}