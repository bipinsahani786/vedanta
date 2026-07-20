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

        // Follow-up Date Filter
        if ($followUpDate = $request->input('follow_up_date')) {
            $query->whereHas('followUps', function ($q) use ($followUpDate) {
                $q->whereDate('follow_up_date', $followUpDate);
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

    public function show($id)
    {
        $lead = ContactLead::with(['followUps.admin'])->findOrFail($id);
        return view('admin.leads.show', compact('lead'));
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

    public function storeFollowUp(Request $request, $id)
    {
        $request->validate([
            'notes' => 'required|string',
            'follow_up_date' => 'nullable|date',
            'status' => 'nullable|in:new,contacted,closed'
        ]);

        $lead = ContactLead::findOrFail($id);

        \App\Models\LeadFollowUp::create([
            'lead_id' => $lead->id,
            'notes' => $request->notes,
            'follow_up_date' => $request->follow_up_date,
            'created_by' => auth()->id(),
        ]);

        if ($request->filled('status') && $request->status !== $lead->status) {
            $lead->status = $request->status;
            $lead->save();
        }

        return back()->with('success', 'Follow-up added successfully.');
    }
}
