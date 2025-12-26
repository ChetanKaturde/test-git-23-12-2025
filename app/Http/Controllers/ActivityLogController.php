<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class ActivityLogController extends Controller
{
    public function index()
    {
        // Check if user is on Free Tier
        if (auth()->user()->business->subscription_plan === 'free') {
            Log::info('Free user attempted to access Activity Log', [
                'user_id' => auth()->id(),
                'business_id' => auth()->user()->business_id,
                'ip' => request()->ip()
            ]);
            
            return view('errors.free-tier-feature', [
                'feature' => 'Activity Log',
                'description' => 'Track all system activities and user actions with detailed logs.'
            ]);
        }
        
        try {
            // Check if activity_log table exists
            if (!Schema::hasTable('activity_log')) {
                return redirect()->route('dashboard')->with('error', 'Activity log feature is not available.');
            }

            // Try to use Spatie Activity Log
            if (class_exists('\Spatie\Activitylog\Models\Activity')) {
                $activities = \Spatie\Activitylog\Models\Activity::with(['causer', 'subject'])
                    ->whereHas('causer', function($query) {
                        $query->where('business_id', auth()->user()->business_id);
                    })
                    ->latest()
                    ->paginate(50);
            } else {
                // Fallback to empty collection
                $activities = new \Illuminate\Pagination\LengthAwarePaginator(
                    collect([]), 0, 50, 1, ['path' => request()->url()]
                );
            }
        } catch (\Exception $e) {
            Log::error('Activity log error: ' . $e->getMessage());
            return redirect()->route('dashboard')->with('error', 'Unable to load activity log.');
        }

        return view('activity-log.index', compact('activities'));
    }
}