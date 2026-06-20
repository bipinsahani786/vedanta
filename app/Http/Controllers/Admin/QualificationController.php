<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Qualification;
use Illuminate\Http\Request;

class QualificationController extends Controller
{
    public function index(Request $request)
    {
        $query = Qualification::query();

        // Search
        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%");
        }

        // Sorting
        $sortField = $request->input('sort_by', 'created_at');
        $sortDirection = $request->input('order', 'desc');
        
        $allowedFields = ['id', 'name', 'is_active', 'created_at'];
        if (in_array($sortField, $allowedFields)) {
            $query->orderBy($sortField, $sortDirection === 'asc' ? 'asc' : 'desc');
        } else {
            $query->latest();
        }

        $qualifications = $query->paginate(10)->withQueryString();
        
        return view('admin.qualifications.index', compact('qualifications', 'sortField', 'sortDirection'));
    }

    public function create()
    {
        return view('admin.qualifications.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:qualifications,name',
        ]);

        Qualification::create([
            'name' => $request->name,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.qualifications.index')->with('success', 'Qualification created successfully.');
    }

    public function edit(Qualification $qualification)
    {
        return view('admin.qualifications.edit', compact('qualification'));
    }

    public function update(Request $request, Qualification $qualification)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:qualifications,name,' . $qualification->id,
        ]);

        $qualification->update([
            'name' => $request->name,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.qualifications.index')->with('success', 'Qualification updated successfully.');
    }

    public function destroy(Qualification $qualification)
    {
        $qualification->delete();
        return redirect()->route('admin.qualifications.index')->with('success', 'Qualification deleted successfully.');
    }
}
