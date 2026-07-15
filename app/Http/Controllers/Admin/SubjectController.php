<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Subject::with('categories');

        // Search
        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhereHas('categories', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
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

        $subjects = $query->paginate(10)->withQueryString();
        
        return view('admin.subjects.index', compact('subjects', 'sortField', 'sortDirection'));
    }

    public function create()
    {
        $categories = \App\Models\Category::where('is_active', true)->orderBy('name')->get();
        return view('admin.subjects.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
        ]);

        // Check if this subject name is already associated with this category
        $subject = Subject::where('name', $request->name)->first();
        if ($subject && $subject->categories()->where('categories.id', $request->category_id)->exists()) {
            return back()->withErrors(['name' => 'This subject is already associated with the selected category.'])->withInput();
        }

        if (!$subject) {
            $subject = Subject::create([
                'name' => $request->name,
                'is_active' => $request->has('is_active'),
            ]);
        } else {
            // If the subject exists but was deactivated, activate it if requested
            if ($request->has('is_active') && !$subject->is_active) {
                $subject->update(['is_active' => true]);
            }
        }

        $subject->categories()->syncWithoutDetaching([$request->category_id]);

        return redirect()->route('admin.subjects.index')->with('success', 'Subject created successfully.');
    }

    public function edit(Subject $subject)
    {
        $categories = \App\Models\Category::where('is_active', true)->orderBy('name')->get();
        return view('admin.subjects.edit', compact('subject', 'categories'));
    }

    public function update(Request $request, Subject $subject)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255|unique:subjects,name,' . $subject->id,
        ]);

        $subject->update([
            'name' => $request->name,
            'is_active' => $request->has('is_active'),
        ]);

        $subject->categories()->sync([$request->category_id]);

        return redirect()->route('admin.subjects.index')->with('success', 'Subject updated successfully.');
    }

    public function destroy(Subject $subject)
    {
        $subject->delete();
        return redirect()->route('admin.subjects.index')->with('success', 'Subject deleted successfully.');
    }
}
