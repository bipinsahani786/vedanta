<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CrmFollowUp;
use App\Models\JobApplication;
use App\Models\ServiceChargeInvoice;
use App\Models\User;
use Illuminate\Http\Request;

class CrmController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'candidate')
            ->with(['profile', 'applications.jobPost', 'applications' => function($q) {
                $q->where('status', 'hired');
            }]);

        // Search
        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sortField = $request->input('sort_by', 'created_at');
        $sortDirection = $request->input('order', 'desc');
        
        $allowedFields = ['id', 'name', 'email', 'created_at'];
        if (in_array($sortField, $allowedFields)) {
            $query->orderBy($sortField, $sortDirection === 'asc' ? 'asc' : 'desc');
        } else {
            $query->latest();
        }

        $candidates = $query->paginate(15)->withQueryString();

        return view('admin.crm.index', compact('candidates', 'sortField', 'sortDirection'));
    }

    public function show($id)
    {
        $candidate = User::where('role', 'candidate')
            ->with(['profile', 'applications.jobPost'])
            ->findOrFail($id);

        $followUps = CrmFollowUp::where('candidate_id', $id)->with('admin')->orderBy('created_at', 'desc')->get();
        $invoices = ServiceChargeInvoice::where('candidate_id', $id)->with('jobApplication.jobPost')->orderBy('created_at', 'desc')->get();

        return view('admin.crm.show', compact('candidate', 'followUps', 'invoices'));
    }

    public function storeFollowUp(Request $request, $id)
    {
        $request->validate([
            'notes' => 'required|string',
            'follow_up_date' => 'nullable|date',
            'status' => 'required|in:open,closed'
        ]);

        CrmFollowUp::create([
            'candidate_id' => $id,
            'notes' => $request->notes,
            'follow_up_date' => $request->follow_up_date,
            'status' => $request->status,
            'created_by' => auth()->id()
        ]);

        return back()->with('success', 'Follow-up added successfully.');
    }

    public function storeInvoice(Request $request, $id)
    {
        $request->validate([
            'job_application_id' => 'required|exists:job_applications,id',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date'
        ]);

        ServiceChargeInvoice::create([
            'candidate_id' => $id,
            'job_application_id' => $request->job_application_id,
            'amount' => $request->amount,
            'due_date' => $request->due_date,
            'status' => 'pending'
        ]);

        return back()->with('success', 'Invoice created successfully.');
    }

    public function updateInvoiceStatus(Request $request, $invoiceId)
    {
        $invoice = ServiceChargeInvoice::findOrFail($invoiceId);
        
        $request->validate([
            'status' => 'required|in:pending,paid,overdue'
        ]);

        $invoice->status = $request->status;
        if ($request->status === 'paid') {
            $invoice->payment_date = now();
        }
        $invoice->save();

        return back()->with('success', 'Invoice status updated.');
    }
}
