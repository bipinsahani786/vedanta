<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class AgreementController extends Controller
{
    public function show()
    {
        $user = auth()->user();
        $profile = $user->profile;

        if (!$profile || !$profile->is_profile_complete) {
            return redirect()->route('candidate.profile.edit')->with('error', 'Please complete your profile first before signing the agreement.');
        }

        if (!$profile->is_agreement_signed) {
            return redirect()->route('candidate.wizard')->with('info', 'Please complete your live camera photo and agreement signature.');
        }

        return view('candidate.agreement.show', compact('user', 'profile'));
    }

    public function preview(Request $request)
    {
        $user = auth()->user();
        $profile = $user ? $user->profile : null;

        if (!$profile) {
            return redirect()->route('candidate.dashboard')->with('error', 'Candidate profile not found.');
        }

        return self::renderAgreementStream($user, $profile);
    }

    public function sign(Request $request)
    {
        $request->validate([
            'signature' => 'required|string',
            'terms_accepted' => 'required|accepted'
        ]);

        $user = auth()->user();
        $profile = $user->profile;

        $sigData = $request->signature;
        $sigType = 'draw';

        // Update profile
        $profile->update([
            'is_agreement_signed' => true,
            'signature_type' => $sigType,
            'signature_data' => $sigData,
            'signature_date_time' => now(),
            'signature_ip_address' => $request->ip(),
            'signature_device_info' => $request->header('User-Agent'),
        ]);

        $fileName = self::generateDomPdfAgreement($user, $profile);
        $profile->update(['agreement_pdf_path' => $fileName]);

        // Send signed agreement email
        try {
            \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\RegistrationSuccessMail($user));
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("Failed to send RegistrationSuccessMail on agreement sign: " . $e->getMessage());
        }

        return redirect()->route('candidate.dashboard')->with('success', 'Agreement digitally signed successfully.');
    }

    public function download(Request $request)
    {
        try {
            $user = auth()->user();
            $profile = $user ? $user->profile : null;

            if (!$profile || (!$profile->is_agreement_signed && !$profile->signature_data)) {
                return redirect()->route('candidate.dashboard')->with('error', 'Agreement not signed yet.');
            }

            // Always force fresh regeneration so changes in template/details are immediately reflected
            $fileName = self::ensureAgreementPdfExists($profile, true);

            if (!$fileName || !Storage::disk('public')->exists($fileName)) {
                return redirect()->route('candidate.dashboard')->with('error', 'Agreement PDF file could not be generated.');
            }

            $fullFilePath = Storage::disk('public')->path($fileName);
            $cleanName = preg_replace('/[^A-Za-z0-9_]/', '_', $user->name ?? 'Candidate');
            return response()->download($fullFilePath, 'Candidate_Agreement_' . $cleanName . '.pdf');
        } catch (\Throwable $e) {
            \Log::error("Agreement download failed: " . $e->getMessage());
            return redirect()->route('candidate.dashboard')->with('error', 'Could not generate or download agreement PDF: ' . $e->getMessage());
        }
    }

    public static function ensureAgreementPdfExists($profile, $forceRegenerate = false)
    {
        if (!$profile) {
            return null;
        }
        $user = $profile->user;
        if (!$user) {
            return null;
        }

        if (!$forceRegenerate && $profile->agreement_pdf_path && Storage::disk('public')->exists($profile->agreement_pdf_path)) {
            return $profile->agreement_pdf_path;
        }

        $fileName = self::generateDomPdfAgreement($user, $profile);
        $profile->update([
            'is_agreement_signed' => true,
            'agreement_pdf_path' => $fileName
        ]);

        return $fileName;
    }

    public static function renderAgreementStream($user, $profile)
    {
        $sigInfo = self::getSignatureDataForPdf($user, $profile);

        $date = $profile->signature_date_time 
            ? Carbon::parse($profile->signature_date_time)->format('d M Y') 
            : Carbon::now()->format('d M Y');

        $pdf = Pdf::loadView('pdf.candidate-agreement', [
            'user' => $user,
            'profile' => $profile,
            'date' => $date,
            'signature' => $sigInfo['signature'],
            'signature_type' => $sigInfo['type'],
        ]);

        $pdf->setPaper('a4', 'portrait');
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'defaultFont' => 'DejaVu Sans',
        ]);

        $cleanName = preg_replace('/[^A-Za-z0-9_]/', '_', $user->name ?? 'Candidate');
        return $pdf->stream('Candidate_Agreement_' . $cleanName . '.pdf');
    }

    public static function generateDomPdfAgreement($user, $profile)
    {
        $sigInfo = self::getSignatureDataForPdf($user, $profile);

        $date = $profile->signature_date_time 
            ? Carbon::parse($profile->signature_date_time)->format('d M Y') 
            : Carbon::now()->format('d M Y');

        $pdf = Pdf::loadView('pdf.candidate-agreement', [
            'user' => $user,
            'profile' => $profile,
            'date' => $date,
            'signature' => $sigInfo['signature'],
            'signature_type' => $sigInfo['type'],
        ]);

        $pdf->setPaper('a4', 'portrait');
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'defaultFont' => 'DejaVu Sans',
        ]);

        $fileName = 'agreements/agreement_' . $user->id . '_' . time() . '.pdf';
        Storage::disk('public')->put($fileName, $pdf->output());

        return $fileName;
    }

    public static function generateStampedPdf($user, $profile, $signatureData = null, $sigType = 'draw')
    {
        return self::generateDomPdfAgreement($user, $profile);
    }

    public static function getSignatureDataForPdf($user, $profile)
    {
        $signatureDataRaw = $profile->signature_data;
        $signatureType = $profile->signature_type ?: 'type';

        if (!$signatureDataRaw) {
            return [
                'signature' => $user->name ?? 'Candidate',
                'type' => 'type',
            ];
        }

        if ($signatureType === 'type') {
            return [
                'signature' => $signatureDataRaw,
                'type' => 'type',
            ];
        }

        if (Str::startsWith($signatureDataRaw, 'data:image')) {
            return [
                'signature' => $signatureDataRaw,
                'type' => 'draw',
            ];
        }

        if ($signatureType === 'upload' || Storage::disk('public')->exists($signatureDataRaw)) {
            $path = Storage::disk('public')->path($signatureDataRaw);
            if (file_exists($path)) {
                $ext = pathinfo($path, PATHINFO_EXTENSION);
                return [
                    'signature' => 'data:image/' . $ext . ';base64,' . base64_encode(file_get_contents($path)),
                    'type' => 'upload',
                ];
            }
        }

        // Try raw base64 string
        $decoded = @base64_decode($signatureDataRaw, true);
        if ($decoded !== false && strlen($decoded) > 50) {
            return [
                'signature' => 'data:image/png;base64,' . base64_encode($decoded),
                'type' => 'draw',
            ];
        }

        return [
            'signature' => $signatureDataRaw ?: ($user->name ?? 'Candidate'),
            'type' => 'type',
        ];
    }
}
