<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\StandaloneAgreementMail;
use App\Models\StandaloneAgreement;
use App\Services\StandaloneAgreementPdfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;

class StandaloneAgreementController extends Controller
{
    public function __construct(private readonly StandaloneAgreementPdfService $pdfService)
    {
    }

    public function index(Request $request)
    {
        if (!Schema::hasTable('standalone_agreements')) {
            return view('admin.standalone_agreements.index', [
                'tableReady' => false,
                'agreements' => collect(),
                'counts' => ['total' => 0, 'pending' => 0, 'resent' => 0, 'signed' => 0, 'expired' => 0],
                'activeStatus' => 'all',
                'search' => '',
            ]);
        }

        $activeStatus = strtolower((string) $request->query('status', 'all'));
        $search = trim((string) $request->query('q', ''));

        $counts = [
            'total' => StandaloneAgreement::count(),
            'pending' => StandaloneAgreement::filterByStatus('pending')->count(),
            'resent' => StandaloneAgreement::filterByStatus('resent')->count(),
            'signed' => StandaloneAgreement::filterByStatus('signed')->count(),
            'expired' => StandaloneAgreement::filterByStatus('expired')->count(),
        ];

        $query = StandaloneAgreement::with('sender')->latest('last_sent_at')->latest('id');

        if (in_array($activeStatus, ['pending', 'resent', 'signed', 'expired'], true)) {
            $query->filterByStatus($activeStatus);
        } else {
            $activeStatus = 'all';
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('recipient_email', 'like', "%{$search}%")
                    ->orWhere('recipient_name', 'like', "%{$search}%")
                    ->orWhere('signer_name', 'like', "%{$search}%")
                    ->orWhere('agreement_code', 'like', "%{$search}%");
            });
        }

        $agreements = $query->paginate(20)->withQueryString();

        return view('admin.standalone_agreements.index', [
            'tableReady' => true,
            'agreements' => $agreements,
            'counts' => $counts,
            'activeStatus' => $activeStatus,
            'search' => $search,
        ]);
    }

    public function store(Request $request)
    {
        if (!Schema::hasTable('standalone_agreements')) {
            return back()->with('error', 'Database table for standalone agreements is missing. Please run migrations first.');
        }

        $validated = $request->validate([
            'recipient_email' => 'required|email:rfc|max:255',
            'recipient_name' => 'nullable|string|max:255',
            'recipient_phone' => 'nullable|string|max:30',
            'subject' => 'nullable|string|max:255',
            'admin_note' => 'nullable|string|max:1500',
            'expiry_days' => 'nullable|integer|min:1|max:90',
        ]);

        $expiryDays = (int) ($validated['expiry_days'] ?? 7);
        $now = now();

        $agreement = StandaloneAgreement::create([
            'agreement_code' => StandaloneAgreement::generateAgreementCode(),
            'token' => StandaloneAgreement::generateUniqueToken(),
            'recipient_email' => strtolower(trim($validated['recipient_email'])),
            'recipient_name' => !empty($validated['recipient_name']) ? trim($validated['recipient_name']) : null,
            'recipient_phone' => !empty($validated['recipient_phone']) ? trim($validated['recipient_phone']) : null,
            'subject' => !empty($validated['subject']) ? trim($validated['subject']) : null,
            'admin_note' => !empty($validated['admin_note']) ? trim($validated['admin_note']) : null,
            'status' => 'pending',
            'sent_by' => Auth::id(),
            'sent_at' => $now,
            'last_sent_at' => $now,
            'resent_count' => 0,
            'expires_at' => $now->copy()->addDays($expiryDays),
        ]);

        try {
            Mail::to($agreement->recipient_email)->send(new StandaloneAgreementMail($agreement, 'initial'));
        } catch (\Throwable $e) {
            Log::error('Standalone agreement email failed', [
                'agreement_id' => $agreement->id,
                'recipient_email' => $agreement->recipient_email,
                'error' => $e->getMessage(),
            ]);

            return redirect()
                ->route('admin.standalone-agreements.index', ['highlight' => $agreement->id])
                ->with('warning', "Agreement {$agreement->agreement_code} was created, but email delivery failed ({$e->getMessage()}). You can copy the signing link below or click Resend.");
        }

        return redirect()
            ->route('admin.standalone-agreements.index', ['highlight' => $agreement->id])
            ->with('success', "Agreement link ({$agreement->agreement_code}) sent to {$agreement->recipient_email}.");
    }

    public function resend(Request $request, StandaloneAgreement $agreement)
    {
        if ($agreement->isSigned()) {
            return back()->with('error', 'This agreement has already been signed and cannot be resent.');
        }

        $validated = $request->validate([
            'expiry_days' => 'nullable|integer|min:1|max:90',
        ]);

        $expiryDays = (int) ($validated['expiry_days'] ?? 7);
        $now = now();

        $agreement->update([
            'token' => StandaloneAgreement::generateUniqueToken(),
            'status' => 'pending',
            'resent_count' => ((int) $agreement->resent_count) + 1,
            'resent_at' => $now,
            'last_sent_at' => $now,
            'expires_at' => $now->copy()->addDays($expiryDays),
        ]);

        try {
            Mail::to($agreement->recipient_email)->send(new StandaloneAgreementMail($agreement->fresh(), 'resend'));
        } catch (\Throwable $e) {
            Log::error('Standalone agreement resend email failed', [
                'agreement_id' => $agreement->id,
                'recipient_email' => $agreement->recipient_email,
                'error' => $e->getMessage(),
            ]);

            return redirect()
                ->route('admin.standalone-agreements.index', ['highlight' => $agreement->id])
                ->with('warning', "Agreement {$agreement->agreement_code} status updated to Resent, but email delivery failed ({$e->getMessage()}). Copy the new link or retry.");
        }

        return redirect()
            ->route('admin.standalone-agreements.index', ['highlight' => $agreement->id])
            ->with('success', "Fresh signing link for {$agreement->agreement_code} resent to {$agreement->recipient_email}.");
    }

    public function expire(StandaloneAgreement $agreement)
    {
        if ($agreement->isSigned()) {
            return back()->with('error', 'Signed agreements cannot be expired.');
        }

        $agreement->update([
            'status' => 'expired',
            'expires_at' => now()->subMinute(),
        ]);

        return redirect()
            ->route('admin.standalone-agreements.index', ['highlight' => $agreement->id])
            ->with('success', "Agreement {$agreement->agreement_code} has been marked as Expired.");
    }

    public function pdf(Request $request, StandaloneAgreement $agreement)
    {
        if (!$agreement->isSigned()) {
            return back()->with('error', 'A completed PDF is only available after the recipient signs the agreement.');
        }

        $disposition = $request->boolean('download') ? 'attachment' : 'inline';

        return $this->pdfService->buildPdfResponse($agreement, $disposition);
    }
}
