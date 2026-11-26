<?php

namespace App\Services;

use App\Models\EmployerProfile;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class EmployerAgreementService
{
    public function buildTemplatePdfResponse(User $employer, EmployerProfile $profile): Response
    {
        $logoBase64 = $this->imageToBase64(public_path('images/logo.png'));
        $adminSignBase64 = $this->imageToBase64(public_path('images/aditya_rajveer_signature.png'));

        $schoolName = $profile->school_name ?: $employer->name;
        $contactPerson = $profile->contact_person ?: $employer->name;
        $generatedDateIst = now('Asia/Kolkata')->format('d M Y, h:i A') . ' IST';
        $employerCode = 'VPA-EMP-' . str_pad((string) $employer->id, 4, '0', STR_PAD_LEFT);

        $pdf = Pdf::loadView('pdf.employer-agreement', [
            'employer' => $employer,
            'profile' => $profile,
            'schoolName' => $schoolName,
            'contactPerson' => $contactPerson,
            'generatedDateIst' => $generatedDateIst,
            'employerCode' => $employerCode,
            'logoBase64' => $logoBase64,
            'adminSignBase64' => $adminSignBase64,
        ])
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'DejaVu Sans',
            ]);

        $fileName = "Vedanta_School_Agreement_{$employerCode}.pdf";

        return response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            'Cache-Control' => 'private, max-age=0, must-revalidate',
        ]);
    }

    public function streamUploadedAgreement(EmployerProfile $profile, string $disposition = 'inline'): Response
    {
        $relativePath = (string) $profile->agreement_path;
        $ext = strtolower(pathinfo($relativePath, PATHINFO_EXTENSION) ?: 'pdf');
        $mime = match ($ext) {
            'pdf' => 'application/pdf',
            'png' => 'image/png',
            'jpg', 'jpeg' => 'image/jpeg',
            default => 'application/octet-stream',
        };

        $safeSchool = preg_replace('/[^A-Za-z0-9_\-]/', '_', (string) ($profile->school_name ?: 'School_' . $profile->user_id));
        $fileName = trim($safeSchool, '_') . '_Signed_Agreement.' . $ext;
        $safeDisposition = $disposition === 'attachment' ? 'attachment' : 'inline';

        return response(Storage::disk('public')->get($relativePath), 200, [
            'Content-Type' => $mime,
            'Content-Disposition' => $safeDisposition . '; filename="' . $fileName . '"',
            'Cache-Control' => 'private, max-age=0, must-revalidate',
        ]);
    }

    private function imageToBase64(string $path): ?string
    {
        if (!file_exists($path)) {
            return null;
        }

        $data = file_get_contents($path);
        if ($data === false) {
            return null;
        }

        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION) ?: 'png');
        if ($ext === 'jpg') {
            $ext = 'jpeg';
        }

        return 'data:image/' . $ext . ';base64,' . base64_encode($data);
    }
}
