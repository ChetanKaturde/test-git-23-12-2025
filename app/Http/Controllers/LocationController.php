<?php

namespace App\Http\Controllers;

use App\Models\State;
use App\Models\City;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    // Return all states
    public function getStates()
    {
        return response()->json(State::orderBy('name')->get());
    }

    // Return cities for a given state
    public function getCities($state_id)
    {
        $cities = City::where('state_id', $state_id)->orderBy('name')->get();
        return response()->json($cities);
    }

    // Example: render a blade view with dropdowns
    public function index()
    {
        return view('location.index');
    }
}
