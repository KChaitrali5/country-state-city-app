<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\State;
use Illuminate\Http\Request;

class StateController extends Controller
{
    public function index()
    {
        $states = State::with('country')->get(); // Eager load country
        $countries = Country::all(); // Get all countries for the add state form
        return view('states.index', compact('states', 'countries'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'country_id' => 'required|exists:countries,id',
            'state_name' => 'required|string',
        ]);

        State::create($request->only('country_id', 'state_name'));
        return redirect()->route('states.index')->with('success', 'State added successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'state_name' => 'required|string',
        ]);

        $state = State::findOrFail($id);
        $state->update(['state_name' => $request->state_name]);

        return redirect()->route('states.index')->with('success', 'State updated successfully.');
    }

    public function destroy($id)
    {
        $state = State::findOrFail($id);
        $state->delete(); 
        return redirect()->back()->with('success', 'State and its cities deleted successfully.');
    }
}
