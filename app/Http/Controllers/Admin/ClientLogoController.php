<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClientLogo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ClientLogoController extends Controller
{
    public function index(Request $request)
    {
        $query = ClientLogo::query();

        // Search
        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%");
        }

        // Status Filter
        if ($request->has('status')) {
            $status = $request->input('status');
            if ($status === 'active') {
                $query->where('is_active', true);
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            }
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

        $clients = $query->paginate(10)->withQueryString();
        
        return view('admin.clients.index', compact('clients', 'sortField', 'sortDirection'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'required|image|max:2048'
        ]);

        $logoPath = $request->file('logo')->store('clients', 'public');

        ClientLogo::create([
            'name' => $request->name,
            'logo_path' => $logoPath,
            'is_active' => $request->has('is_active')
        ]);

        return back()->with('success', 'Client Logo uploaded successfully.');
    }

    public function update(Request $request, ClientLogo $clientLogo)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|max:2048'
        ]);

        $data = [
            'name' => $request->name,
            'is_active' => $request->has('is_active')
        ];

        if ($request->hasFile('logo')) {
            if ($clientLogo->logo_path) {
                Storage::disk('public')->delete($clientLogo->logo_path);
            }
            $data['logo_path'] = $request->file('logo')->store('clients', 'public');
        }

        $clientLogo->update($data);

        return back()->with('success', 'Client Logo updated successfully.');
    }

    public function destroy(ClientLogo $clientLogo)
    {
        if ($clientLogo->logo_path) {
            Storage::disk('public')->delete($clientLogo->logo_path);
        }
        $clientLogo->delete();
        
        return back()->with('success', 'Client Logo deleted successfully.');
    }
}
