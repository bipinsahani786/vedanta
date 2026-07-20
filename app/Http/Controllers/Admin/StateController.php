<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\State;
use Illuminate\Http\Request;

class StateController extends Controller
{
    public function index()
    {
        $states = State::paginate(10);
        return view('admin.states.index', compact('states'));
    }

    // Removed create()

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255|unique:states']);
        State::create($request->only('name') + ['is_active' => $request->has('is_active')]);
        return redirect()->route('admin.states.index')->with('success', 'State added successfully.');
    }

    // Removed edit()

    public function update(Request $request, State $state)
    {
        $request->validate(['name' => 'required|string|max:255|unique:states,name,' . $state->id]);
        $state->update($request->only('name') + ['is_active' => $request->has('is_active')]);
        return redirect()->route('admin.states.index')->with('success', 'State updated successfully.');
    }

    public function destroy(State $state)
    {
        $state->delete();
        return redirect()->route('admin.states.index')->with('success', 'State deleted successfully.');
    }
}
