<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\State;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function index(Request $request)
    {
        $query = City::with('state');
        if ($request->has('state_id') && $request->state_id != '') {
            $query->where('state_id', $request->state_id);
        }
        $cities = $query->paginate(10);
        $states = State::where('is_active', true)->get();
        return view('admin.cities.index', compact('cities', 'states'));
    }

    // Removed create()

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'state_id' => 'required|exists:states,id'
        ]);
        City::create($request->only('name', 'state_id') + ['is_active' => $request->has('is_active')]);
        return redirect()->route('admin.cities.index')->with('success', 'City added successfully.');
    }

    // Removed edit()

    public function update(Request $request, City $city)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'state_id' => 'required|exists:states,id'
        ]);
        $city->update($request->only('name', 'state_id') + ['is_active' => $request->has('is_active')]);
        return redirect()->route('admin.cities.index')->with('success', 'City updated successfully.');
    }

    public function destroy(City $city)
    {
        $city->delete();
        return redirect()->route('admin.cities.index')->with('success', 'City deleted successfully.');
    }
}
