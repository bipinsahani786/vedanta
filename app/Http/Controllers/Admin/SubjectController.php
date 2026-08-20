<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\Category;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Subject::with('categories');

        // Search
        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%");
        }

        if ($categoryId = $request->input('category_id')) {
            $query->whereHas('categories', function($q) use ($categoryId) {
                $q->where('categories.id', $categoryId);
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
        $categories = Category::orderBy('name')->get();
        
        return view('admin.subjects.index', compact('subjects', 'categories', 'sortField', 'sortDirection'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'categories' => 'required|array|min:1',
            'categories.*' => 'exists:categories,id'
        ], [
            'name.required' => 'Subject name is required.',
            'categories.required' => 'Please select at least one category for this subject.',
            'categories.min' => 'Please select at least one category for this subject.'
        ]);

        $trimmedName = trim($request->name);

        // Check if subject with this name already exists (case-insensitive)
        $existingSubject = Subject::whereRaw('LOWER(TRIM(name)) = ?', [strtolower($trimmedName)])->first();

        if ($existingSubject) {
            // Attach any newly selected categories without detaching existing ones
            $existingSubject->categories()->syncWithoutDetaching($request->categories);
            
            if ($request->has('is_active')) {
                $existingSubject->update(['is_active' => true]);
            }

            return redirect()->route('admin.subjects.index')
                ->with('success', "Subject '{$existingSubject->name}' already exists. Assigned categories have been updated successfully.");
        }

        $subject = Subject::create([
            'name' => $trimmedName,
            'is_active' => $request->has('is_active'),
        ]);

        if ($request->has('categories')) {
            $subject->categories()->sync($request->categories);
        }

        return redirect()->route('admin.subjects.index')->with('success', "Subject '{$subject->name}' created successfully with assigned categories.");
    }

    public function update(Request $request, Subject $subject)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:subjects,name,' . $subject->id,
            'categories' => 'required|array|min:1',
            'categories.*' => 'exists:categories,id'
        ], [
            'name.required' => 'Subject name is required.',
            'categories.required' => 'Please select at least one category for this subject.',
            'categories.min' => 'Please select at least one category for this subject.'
        ]);

        $subject->update([
            'name' => trim($request->name),
            'is_active' => $request->has('is_active'),
        ]);

        if ($request->has('categories')) {
            $subject->categories()->sync($request->categories);
        } else {
            $subject->categories()->sync([]);
        }

        return redirect()->route('admin.subjects.index')->with('success', 'Subject updated successfully.');
    }

    public function destroy(Subject $subject)
    {
        $subject->categories()->detach();
        $subject->delete();
        return redirect()->route('admin.subjects.index')->with('success', 'Subject deleted successfully.');
    }
}
