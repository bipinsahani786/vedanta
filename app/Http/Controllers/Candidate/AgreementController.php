<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use setasign\Fpdi\Fpdi;
use Illuminate\Support\Str;

class AgreementController extends Controller
{
    public function show()
    {
        $user = auth()->user();
        $profile = $user->profile;

        // Redirect if profile is incomplete
        if (!$profile->is_profile_complete) {
            return redirect()->route('candidate.profile.edit')->with('error', 'Please complete your profile first before signing the agreement.');
        }

        // If not signed, redirect to the wizard to ensure the live photo process is followed
        if (!$profile->is_agreement_signed) {
            return redirect()->route('candidate.wizard')->with('info', 'Please sign the agreement here.');
        }

        // If already signed, we will just show the signed state in the view.
        return view('candidate.agreement.show', compact('user', 'profile'));
    }

    public function sign(Request $request)
    {
        $request->validate([
            'signature' => 'required|string', // Base64 image
            'terms_accepted' => 'required|accepted'
        ]);

        $user = auth()->user();
        $profile = $user->profile;

        // Ensure signature is valid base64 image data
        if (preg_match('/^data:image\/(\w+);base64,/', $request->signature, $type)) {
            $signatureData = substr($request->signature, strpos($request->signature, ',') + 1);
            $type = strtolower($type[1]); // jpg, png, gif
        
            if (!in_array($type, [ 'jpg', 'jpeg', 'gif', 'png' ])) {
                return back()->with('error', 'Invalid signature image type');
            }
            $signatureData = base64_decode($signatureData);
        } else {
            return back()->with('error', 'Did not match data URI with image data');
        }

        $fileName = $this->generateStampedPdf($user, $profile, $signatureData, $type);

        // Update profile
        $profile->update([
            'is_agreement_signed' => true,
            'agreement_pdf_path' => $fileName
        ]);

        return redirect()->route('candidate.dashboard')->with('success', 'Agreement digitally signed successfully.');
    }

    public function download()
    {
        $profile = auth()->user()->profile;

        if (!$profile->is_agreement_signed) {
            return abort(404, 'Agreement not found.');
        }

        if (!$profile->agreement_pdf_path || !Storage::disk('public')->exists($profile->agreement_pdf_path)) {
            $user = auth()->user();
            
            $signatureDataRaw = $profile->signature_data;
            if (!$signatureDataRaw) {
                return abort(404, 'Signature data missing, cannot regenerate agreement.');
            }

            $signatureData = '';
            $type = 'png';
            if (Str::startsWith($signatureDataRaw, 'data:image')) {
                preg_match('/^data:image\/(\w+);base64,/', $signatureDataRaw, $matches);
                $type = strtolower($matches[1] ?? 'png');
                $signatureData = base64_decode(substr($signatureDataRaw, strpos($signatureDataRaw, ',') + 1));
            } elseif ($profile->signature_type === 'upload') {
                $path = Storage::disk('public')->path($signatureDataRaw);
                if (file_exists($path)) {
                    $type = pathinfo($path, PATHINFO_EXTENSION);
                    $signatureData = file_get_contents($path);
                }
            } else {
                $signatureData = base64_decode($signatureDataRaw);
            }

            if (!$signatureData) {
                return abort(404, 'Signature data invalid.');
            }

            $fileName = $this->generateStampedPdf($user, $profile, $signatureData, $type);
            $profile->update(['agreement_pdf_path' => $fileName]);
        }

        return Storage::disk('public')->download($profile->agreement_pdf_path, 'Candidate_Agreement_' . str_replace(' ', '_', $profile->user->name) . '.pdf');
    }

    private function generateStampedPdf($user, $profile, $signatureData, $sigType)
    {
        // Save signature to temporary file for FPDI
        $tempSignaturePath = 'temp/sig_' . $user->id . '_' . time() . '.' . $sigType;
        Storage::disk('local')->put($tempSignaturePath, $signatureData);
        $absoluteSigPath = Storage::disk('local')->path($tempSignaturePath);

        // Find candidate photo (live_photo or profile_photo)
        $photoPath = null;
        if ($profile->live_photo_path && Storage::disk('public')->exists($profile->live_photo_path)) {
            $photoPath = Storage::disk('public')->path($profile->live_photo_path);
        } elseif ($profile->profile_photo_path && Storage::disk('public')->exists($profile->profile_photo_path)) {
            $photoPath = Storage::disk('public')->path($profile->profile_photo_path);
        } elseif ($profile->passport_photo_path && Storage::disk('public')->exists($profile->passport_photo_path)) {
            $photoPath = Storage::disk('public')->path($profile->passport_photo_path);
        }

        // Load the FPDI template
        $pdf = new Fpdi();
        $templatePath = Storage::disk('public')->path('template/candidate_agreement.pdf');
        
        if (!file_exists($templatePath)) {
            abort(500, 'Agreement template not found.');
        }

        $pageCount = $pdf->setSourceFile($templatePath);

        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $templateId = $pdf->importPage($pageNo);
            $size = $pdf->getTemplateSize($templateId);
            
            $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
            $pdf->useTemplate($templateId);
            
            // On page 2, add the signature and photo
            if ($pageNo == 2) {
                // Photo on the right side
                if ($photoPath && file_exists($photoPath)) {
                    // Try to catch any exception with unsupported image types
                    try {
                        // X: 168, Y: 260, Width: 25, Height: 30
                        $pdf->Image($photoPath, 168, 260, 25, 30);
                    } catch (\Exception $e) {
                        // Ignore if image format is unsupported
                    }
                }

                // Signature in the box
                try {
                    // X: 78, Y: 272, Width: 60, Height: 15
                    $pdf->Image($absoluteSigPath, 78, 272, 60, 15);
                } catch (\Exception $e) {
                    // Ignore
                }
            }
        }
        
        $fileName = 'agreements/agreement_' . $user->id . '_' . time() . '.pdf';
        
        // Save PDF to storage
        Storage::disk('public')->put($fileName, $pdf->Output('S'));

        // Clean up temp signature
        Storage::disk('local')->delete($tempSignaturePath);

        return $fileName;
    }
}
