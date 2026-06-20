<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

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

        // Redirect if already signed
        if ($profile->is_agreement_signed) {
            return redirect()->route('candidate.dashboard')->with('info', 'You have already signed the agreement.');
        }

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

        // Create PDF
        $date = Carbon::now()->format('d M Y');
        $signatureBase64 = $request->signature; // Pass the original base64 for embedding in PDF

        $pdf = Pdf::loadView('pdf.candidate-agreement', [
            'user' => $user,
            'profile' => $profile,
            'date' => $date,
            'signature' => $signatureBase64
        ]);

        $fileName = 'agreements/agreement_' . $user->id . '_' . time() . '.pdf';
        
        // Save PDF to storage
        Storage::disk('public')->put($fileName, $pdf->output());

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

        if (!$profile->is_agreement_signed || !$profile->agreement_pdf_path) {
            return abort(404, 'Agreement not found.');
        }

        return Storage::disk('public')->download($profile->agreement_pdf_path);
    }
}
