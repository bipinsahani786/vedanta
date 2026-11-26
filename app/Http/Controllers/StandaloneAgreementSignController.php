<?php

namespace App\Http\Controllers;

use App\Mail\StandaloneAgreementMail;
use App\Models\StandaloneAgreement;
use App\Models\User;
use App\Notifications\StandaloneAgreementSigned;
use App\Services\StandaloneAgreementPdfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class StandaloneAgreementSignController extends Controller
{
    public function __construct(private readonly StandaloneAgreementPdfService $pdfService)
    {
    }

    public function show(string $token)
    {
        $agreement = StandaloneAgreement::where('token', $token)->firstOrFail();

        return view('standalone_agreement.sign', [
            'agreement' => $agreement,
            'isSigned' => $agreement->isSigned(),
            'isExpired' => $agreement->isExpired(),
            'sentDateIst' => StandaloneAgreement::formatIst($agreement->sent_at),
            'expiresDateIst' => StandaloneAgreement::formatIst($agreement->expires_at),
            'signedDateIst' => StandaloneAgreement::formatIst($agreement->signed_at),
        ]);
    }

    public function submit(Request $request, string $token)
    {
        $agreement = StandaloneAgreement::where('token', $token)->firstOrFail();

        if ($agreement->isSigned()) {
            return redirect()
                ->route('standalone-agreement.show', ['token' => $agreement->token])
                ->with('success', 'This agreement has already been digitally signed and submitted.');
        }

        if ($agreement->isExpired()) {
            return redirect()
                ->route('standalone-agreement.show', ['token' => $agreement->token])
                ->with('error', 'This agreement link has expired. Please contact Vedanta Placement Agency for a fresh link.');
        }

        $validated = $request->validate([
            'signer_name' => 'required|string|min:2|max:255',
            'recipient_phone' => 'nullable|string|max:30',
            'signature_type' => 'required|in:draw,type',
            'drawn_signature' => 'required_if:signature_type,draw|nullable|string',
            'typed_signature' => 'required_if:signature_type,type|nullable|string|min:2|max:255',
            'terms_accepted' => 'accepted',
        ], [
            'signer_name.required' => 'Please enter your full legal name.',
            'drawn_signature.required_if' => 'Please draw your signature in the signature box.',
            'typed_signature.required_if' => 'Please type your signature name.',
            'terms_accepted.accepted' => 'Please confirm that you have read and accept the agreement terms.',
        ]);

        if ($validated['signature_type'] === 'draw') {
            $drawn = (string) $validated['drawn_signature'];
            if (!str_starts_with($drawn, 'data:image/png;base64,') || strlen($drawn) < 200) {
                return back()
                    ->withInput()
                    ->withErrors(['drawn_signature' => 'Please draw a clear signature before submitting.']);
            }
            if (strlen($drawn) > 2_000_000) {
                return back()
                    ->withInput()
                    ->withErrors(['drawn_signature' => 'The signature image is too large. Please clear and draw again.']);
            }
        }

        $completedNow = DB::transaction(function () use ($agreement, $validated, $request) {
            $locked = StandaloneAgreement::whereKey($agreement->id)->lockForUpdate()->firstOrFail();
            if ($locked->isSigned()) {
                return false;
            }

            $signerName = trim($validated['signer_name']);
            $signatureData = $validated['signature_type'] === 'draw'
                ? (string) $validated['drawn_signature']
                : trim((string) $validated['typed_signature']);

            $locked->update([
                'status' => 'signed',
                'signer_name' => $signerName,
                'recipient_name' => $locked->recipient_name ?: $signerName,
                'recipient_phone' => !empty($validated['recipient_phone'])
                    ? trim((string) $validated['recipient_phone'])
                    : $locked->recipient_phone,
                'signature_type' => $validated['signature_type'],
                'signature_data' => $signatureData,
                'terms_accepted' => true,
                'signed_at' => now(),
                'signer_ip' => $request->ip(),
                'signer_user_agent' => (string) $request->userAgent(),
            ]);

            return true;
        });

        $agreement->refresh();

        if (!$completedNow) {
            return redirect()
                ->route('standalone-agreement.show', ['token' => $agreement->token])
                ->with('success', 'This agreement has already been digitally signed and submitted.');
        }

        $this->pdfService->generateAndStore($agreement);

        try {
            Mail::to($agreement->recipient_email)->send(new StandaloneAgreementMail($agreement->fresh(), 'signed_copy'));
        } catch (\Throwable $e) {
            Log::warning('Standalone signed copy email failed', [
                'agreement_id' => $agreement->id,
                'error' => $e->getMessage(),
            ]);
        }

        try {
            User::where('role', 'admin')->get()->each(function (User $admin) use ($agreement) {
                $admin->notify(new StandaloneAgreementSigned($agreement));
            });
        } catch (\Throwable $e) {
            Log::warning('Standalone agreement admin notification failed', [
                'agreement_id' => $agreement->id,
                'error' => $e->getMessage(),
            ]);
        }

        return redirect()
            ->route('standalone-agreement.show', ['token' => $agreement->token])
            ->with('success', 'Agreement signed and submitted successfully. You can now download your signed PDF copy.');
    }

    public function download(string $token)
    {
        $agreement = StandaloneAgreement::where('token', $token)->firstOrFail();

        if (!$agreement->isSigned()) {
            abort(404, 'Signed agreement copy is not available yet.');
        }

        return $this->pdfService->buildPdfResponse($agreement, 'attachment');
    }
}
