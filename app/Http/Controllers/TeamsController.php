<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeamsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $teams = Team::where('business_id', Auth::user()->business_id)
                    ->orderBy('team_name')
                    ->get();

        return view('teams.index', compact('teams'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('teams.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'team_name' => 'required|string|max:255|unique:teams,team_name,NULL,id,business_id,' . Auth::user()->business_id,
        ]);

        Team::create([
            'team_name' => $request->team_name,
            'business_id' => Auth::user()->business_id,
        ]);

        return redirect()->route('teams.index')->with('success', 'Team created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Team $team)
    {
        // Ensure team belongs to current business
        if ($team->business_id !== Auth::user()->business_id) {
            abort(403);
        }

        $team->load('users');

        return view('teams.show', compact('team'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Team $team)
    {
        // Ensure team belongs to current business
        if ($team->business_id !== Auth::user()->business_id) {
            abort(403);
        }

        return view('teams.edit', compact('team'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Team $team)
    {
        // Ensure team belongs to current business
        if ($team->business_id !== Auth::user()->business_id) {
            abort(403);
        }

        $request->validate([
            'team_name' => 'required|string|max:255|unique:teams,team_name,' . $team->id . ',id,business_id,' . Auth::user()->business_id,
        ]);

        $team->update([
            'team_name' => $request->team_name,
        ]);

        return redirect()->route('teams.index')->with('success', 'Team updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Team $team)
    {
        // Ensure team belongs to current business
        if ($team->business_id !== Auth::user()->business_id) {
            abort(403);
        }

        // Check if team has users
        if ($team->users()->count() > 0) {
            return back()->withErrors(['error' => 'Cannot delete team that has members. Please remove all members first.']);
        }

        $team->delete();

        return redirect()->route('teams.index')->with('success', 'Team deleted successfully.');
    }
}
