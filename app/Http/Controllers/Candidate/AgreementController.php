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
        try {
            $user = auth()->user();
            $profile = $user->profile;

            if (!$profile || !$profile->is_agreement_signed) {
                return redirect()->route('candidate.dashboard')->with('error', 'Agreement not signed yet.');
            }

            if (!$profile->agreement_pdf_path || !Storage::disk('public')->exists($profile->agreement_pdf_path)) {
                $signatureDataRaw = $profile->signature_data;
                if (!$signatureDataRaw) {
                    return redirect()->route('candidate.dashboard')->with('error', 'Signature data is missing. Please sign the agreement again.');
                }

                $signatureData = '';
                $type = 'png';
                if ($profile->signature_type === 'type') {
                    $signatureData = $signatureDataRaw;
                    $type = 'type';
                } elseif (Str::startsWith($signatureDataRaw, 'data:image')) {
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
                    return redirect()->route('candidate.dashboard')->with('error', 'Signature data invalid.');
                }

                $fileName = $this->generateStampedPdf($user, $profile, $signatureData, $type);
                $profile->update(['agreement_pdf_path' => $fileName]);
            }

            $fullFilePath = Storage::disk('public')->path($profile->agreement_pdf_path);
            if (!file_exists($fullFilePath)) {
                return redirect()->route('candidate.dashboard')->with('error', 'Agreement PDF file not found on server.');
            }

            return response()->download($fullFilePath, 'Candidate_Agreement_' . str_replace(' ', '_', $user->name ?? 'Candidate') . '.pdf');
        } catch (\Throwable $e) {
            \Log::error("Agreement download failed for User ID " . (auth()->id() ?? 'guest') . ": " . $e->getMessage() . "\nTrace: " . $e->getTraceAsString());
            return redirect()->route('candidate.dashboard')->with('error', 'Could not generate or download agreement PDF: ' . $e->getMessage());
        }
    }

    private function generateStampedPdf($user, $profile, $signatureData, $sigType)
    {
        $tempSignaturePath = null;
        $absoluteSigPath = null;
        
        if ($profile->signature_type !== 'type') {
            // Save signature to temporary file for FPDI
            $tempSignaturePath = 'temp/sig_' . $user->id . '_' . time() . '.jpg';
            
            // Convert any image to standard JPEG to avoid FPDF format errors (like alpha channel in PNG)
            try {
                $image = @imagecreatefromstring($signatureData);
                if ($image !== false) {
                    // Create a white background for transparent images
                    $bg = imagecreatetruecolor(imagesx($image), imagesy($image));
                    imagefill($bg, 0, 0, imagecolorallocate($bg, 255, 255, 255));
                    imagealphablending($bg, TRUE);
                    imagecopy($bg, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));
                    
                    // Save as JPEG to a temporary buffer
                    ob_start();
                    imagejpeg($bg, null, 90);
                    $signatureData = ob_get_clean();
                    
                    imagedestroy($image);
                    imagedestroy($bg);
                }
            } catch (\Exception $e) {
                \Log::error("Failed to convert signature to JPEG: " . $e->getMessage());
            }

            Storage::disk('local')->put($tempSignaturePath, $signatureData);
            $absoluteSigPath = Storage::disk('local')->path($tempSignaturePath);
        }

        // Find candidate photo (live_photo or profile_photo)
        $photoPath = null;
        if ($profile->live_photo_path && Storage::disk('public')->exists($profile->live_photo_path)) {
            $photoPath = Storage::disk('public')->path($profile->live_photo_path);
        } elseif ($profile->profile_photo_path && Storage::disk('public')->exists($profile->profile_photo_path)) {
            $photoPath = Storage::disk('public')->path($profile->profile_photo_path);
        } elseif ($profile->passport_photo_path && Storage::disk('public')->exists($profile->passport_photo_path)) {
            $photoPath = Storage::disk('public')->path($profile->passport_photo_path);
        }

        // Convert photo to JPEG if needed
        $tempPhotoPath = null;
        $absolutePhotoPath = null;
        if ($photoPath && file_exists($photoPath)) {
            try {
                $tempPhotoPath = 'temp/photo_' . $user->id . '_' . time() . '.jpg';
                $photoData = file_get_contents($photoPath);
                $image = @imagecreatefromstring($photoData);
                if ($image !== false) {
                    $bg = imagecreatetruecolor(imagesx($image), imagesy($image));
                    imagefill($bg, 0, 0, imagecolorallocate($bg, 255, 255, 255));
                    imagealphablending($bg, TRUE);
                    imagecopy($bg, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));
                    
                    ob_start();
                    imagejpeg($bg, null, 90);
                    $photoJpegData = ob_get_clean();
                    
                    imagedestroy($image);
                    imagedestroy($bg);
                    
                    Storage::disk('local')->put($tempPhotoPath, $photoJpegData);
                    $absolutePhotoPath = Storage::disk('local')->path($tempPhotoPath);
                }
            } catch (\Exception $e) {
                \Log::error("Failed to convert photo to JPEG: " . $e->getMessage());
                // Fallback to original
                $absolutePhotoPath = $photoPath;
            }
        }

        // Load the FPDI template
        $pdf = new Fpdi();
        $templatePath = Storage::disk('public')->path('template/candidate_agreement.pdf');
        
        if (!file_exists($templatePath)) {
            throw new \Exception('Agreement template PDF missing on server at: ' . $templatePath);
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
                if ($absolutePhotoPath && file_exists($absolutePhotoPath)) {
                    // Try to catch any exception with unsupported image types
                    try {
                        // X: 168, Y: 260, Width: 25, Height: 30
                        $pdf->Image($absolutePhotoPath, 168, 260, 25, 30);
                    } catch (\Exception $e) {
                        \Log::error("FPDF Image Error (Photo): " . $e->getMessage());
                    }
                }

                // Signature in the box
                if ($profile->signature_type === 'type') {
                    $pdf->SetAutoPageBreak(false);
                    $pdf->SetFont('Helvetica', 'I', 18);
                    $pdf->SetTextColor(10, 10, 100);
                    $pdf->SetXY(78, 272);
                    $pdf->Cell(60, 15, $profile->signature_data, 0, 0, 'C');
                    $pdf->SetAutoPageBreak(true);
                } else if ($absoluteSigPath && file_exists($absoluteSigPath)) {
                    try {
                        // X: 78, Y: 272, Width: 60, Height: 15
                        $pdf->Image($absoluteSigPath, 78, 272, 60, 15);
                    } catch (\Exception $e) {
                        \Log::error("FPDF Image Error (Signature): " . $e->getMessage());
                    }
                }
            }
        }
        
        $fileName = 'agreements/agreement_' . $user->id . '_' . time() . '.pdf';
        
        // Save PDF to storage
        Storage::disk('public')->put($fileName, $pdf->Output('S'));

        // Clean up temp files
        if ($tempSignaturePath) {
            Storage::disk('local')->delete($tempSignaturePath);
        }
        if ($tempPhotoPath) {
            Storage::disk('local')->delete($tempPhotoPath);
        }

        return $fileName;
    }
}
