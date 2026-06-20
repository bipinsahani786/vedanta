<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactLead;
use Illuminate\Http\Request;

class ContactLeadController extends Controller
{
    public function index(Request $request)
    {
        $query = ContactLead::query();

        // Search
        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sortField = $request->input('sort_by', 'created_at');
        $sortDirection = $request->input('order', 'desc');
        
        $allowedFields = ['id', 'name', 'email', 'status', 'created_at'];
        if (in_array($sortField, $allowedFields)) {
            $query->orderBy($sortField, $sortDirection === 'asc' ? 'asc' : 'desc');
        } else {
            $query->latest();
        }

        $leads = $query->paginate(15)->withQueryString();
        
        return view('admin.leads.index', compact('leads', 'sortField', 'sortDirection'));
    }

    public function updateStatus(Request $request, $id)
    {
        $lead = ContactLead::findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:new,contacted,closed'
        ]);

        $lead->status = $request->status;
        $lead->save();

        return back()->with('success', 'Lead status updated successfully.');
    }
}
