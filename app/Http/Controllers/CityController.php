<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\State;
use App\Models\Country;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function index()
    {
        $cities = City::with('state.country')->get(); // Eager load state and country
        $countries = Country::all(); // Get all countries for the city form
        $states = State::all(); // Get all states to show when editing cities
        return view('cities.index', compact('cities', 'countries', 'states'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'country_id' => 'required|exists:countries,id',
            'state_id' => 'required|exists:states,id',
            'city_name' => 'required|string',
        ]);

        City::create($request->only('country_id', 'state_id', 'city_name'));
        return redirect()->route('cities.index');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'city_name' => 'required|string|max:255',
        ]);
    
        $city = City::findOrFail($id);
        $city->city_name = $request->input('city_name');
        $city->save();
    
        return redirect()->route('cities.index')->with('success', 'City updated successfully!');
    }

    public function destroy($id)
    {
        $city = City::findOrFail($id);
        $city->delete();

        return redirect()->back()->with('success', 'City deleted successfully!');
    }

    // To fetch states based on country
    public function getStates($countryId)
    {
        $states = State::where('country_id', $countryId)->get();
        return response()->json($states);
    }
}
