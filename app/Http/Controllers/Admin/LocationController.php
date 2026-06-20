<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index(Request $request)
    {
        $query = Location::query();

        // Search
        if ($search = $request->input('search')) {
            $query->where('city', 'like', "%{$search}%")
                  ->orWhere('state', 'like', "%{$search}%");
        }

        // Sorting
        $sortField = $request->input('sort_by', 'created_at');
        $sortDirection = $request->input('order', 'desc');
        
        $allowedFields = ['id', 'city', 'state', 'is_active', 'created_at'];
        if (in_array($sortField, $allowedFields)) {
            $query->orderBy($sortField, $sortDirection === 'asc' ? 'asc' : 'desc');
        } else {
            $query->latest();
        }

        $locations = $query->paginate(10)->withQueryString();
        
        return view('admin.locations.index', compact('locations', 'sortField', 'sortDirection'));
    }

    public function create()
    {
        return view('admin.locations.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
        ]);

        Location::create([
            'city' => $request->city,
            'state' => $request->state,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.locations.index')->with('success', 'Location created successfully.');
    }

    public function edit(Location $location)
    {
        return view('admin.locations.edit', compact('location'));
    }

    public function update(Request $request, Location $location)
    {
        $request->validate([
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
        ]);

        $location->update([
            'city' => $request->city,
            'state' => $request->state,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.locations.index')->with('success', 'Location updated successfully.');
    }

    public function destroy(Location $location)
    {
        $location->delete();
        return redirect()->route('admin.locations.index')->with('success', 'Location deleted successfully.');
    }
}
