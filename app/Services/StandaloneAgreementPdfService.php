<?php

namespace App\Services;

use App\Models\StandaloneAgreement;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class StandaloneAgreementPdfService
{
    public function buildPdfResponse(StandaloneAgreement $agreement, string $disposition = 'inline'): Response
    {
        $relativePath = $this->ensurePdfExists($agreement);
        $fileName = "Vedanta_Signed_Agreement_{$agreement->agreement_code}.pdf";
        $safeDisposition = $disposition === 'attachment' ? 'attachment' : 'inline';

        return response(Storage::disk('public')->get($relativePath), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => $safeDisposition . '; filename="' . $fileName . '"',
            'Cache-Control' => 'private, max-age=0, must-revalidate',
        ]);
    }

    public function ensurePdfExists(StandaloneAgreement $agreement): string
    {
        if (!empty($agreement->pdf_path) && Storage::disk('public')->exists($agreement->pdf_path)) {
            return $agreement->pdf_path;
        }

        return $this->generateAndStore($agreement);
    }

    public function generateAndStore(StandaloneAgreement $agreement): string
    {
        $logoBase64 = $this->imageToBase64(public_path('images/logo.png'));
        $adminSignBase64 = $this->imageToBase64(public_path('images/aditya_rajveer_signature.png'));
        $signatureSrc = $this->normalizeSignatureDataUri($agreement);

        $signerName = $agreement->signer_name ?: ($agreement->recipient_name ?: $agreement->recipient_email);
        $sentDateTime = StandaloneAgreement::formatIst($agreement->sent_at) ?: 'N/A';
        $signedDateTime = StandaloneAgreement::formatIst($agreement->signed_at) ?: StandaloneAgreement::formatIst(now());

        $pdf = Pdf::loadView('pdf.standalone-agreement', [
            'agreement' => $agreement,
            'signerName' => $signerName,
            'sentDateTime' => $sentDateTime,
            'signedDateTime' => $signedDateTime,
            'logoBase64' => $logoBase64,
            'adminSignBase64' => $adminSignBase64,
            'signatureSrc' => $signatureSrc,
        ])
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'DejaVu Sans',
            ]);

        $relativePath = "standalone_agreements/{$agreement->agreement_code}_{$agreement->id}.pdf";
        Storage::disk('public')->put($relativePath, $pdf->output());

        $agreement->pdf_path = $relativePath;
        $agreement->save();

        return $relativePath;
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

        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION) ?: 'png');
        if ($extension === 'jpg') {
            $extension = 'jpeg';
        }

        return 'data:image/' . $extension . ';base64,' . base64_encode($data);
    }

    private function normalizeSignatureDataUri(StandaloneAgreement $agreement): ?string
    {
        if ($agreement->signature_type !== 'draw' || empty($agreement->signature_data)) {
            return null;
        }

        $raw = trim((string) $agreement->signature_data);
        if (str_starts_with($raw, 'data:image/')) {
            return $raw;
        }

        return 'data:image/png;base64,' . $raw;
    }
}
