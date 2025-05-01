<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function index()
    {
        $countries = Country::all();
        return view('countries.index', compact('countries'));
    }

    public function store(Request $request)
    {
        $request->validate(['country_name' => 'required|string']);
        Country::create($request->only('country_name'));
        return redirect()->route('countries.index')->with('success', 'Country added successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate(['country_name' => 'required|string']);
        $country = Country::findOrFail($id);
        $country->update($request->only('country_name'));
        return redirect()->route('countries.index')->with('success', 'Country updated successfully.');
    }

    public function destroy($id)
    {
        $country = Country::findOrFail($id);

        // Delete all cities of all states
        foreach ($country->states as $state) {
            $state->cities()->delete();
        }

        // Delete all states
        $country->states()->delete();

        // Delete the country
        $country->delete();

        return redirect()->route('countries.index')->with('success', 'Country and related states and cities deleted.');
    }
}
