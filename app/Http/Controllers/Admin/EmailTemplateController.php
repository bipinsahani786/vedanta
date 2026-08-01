<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;

class EmailTemplateController extends Controller
{
    public function index()
    {
        $templates = EmailTemplate::latest()->paginate(20);
        return view('admin.email_templates.index', compact('templates'));
    }

    public function create()
    {
        return view('admin.email_templates.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        EmailTemplate::create($request->all());

        return redirect()->route('admin.email-templates.index')->with('success', 'Email template created successfully.');
    }

    public function edit(EmailTemplate $emailTemplate)
    {
        return view('admin.email_templates.edit', compact('emailTemplate'));
    }

    public function update(Request $request, EmailTemplate $emailTemplate)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        $emailTemplate->update($request->all());

        return redirect()->route('admin.email-templates.index')->with('success', 'Email template updated successfully.');
    }

    public function destroy(EmailTemplate $emailTemplate)
    {
        $emailTemplate->delete();
        return redirect()->route('admin.email-templates.index')->with('success', 'Email template deleted successfully.');
    }

    // For AJAX fetching when selecting a template
    public function getTemplate($id)
    {
        $template = EmailTemplate::findOrFail($id);
        return response()->json($template);
    }
}
