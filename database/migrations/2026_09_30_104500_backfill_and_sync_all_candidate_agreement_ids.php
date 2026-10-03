<?php

use App\Models\CandidateProfile;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Automatically assigns sequential Candidate ID (vpa_id) and Agreement ID (agreement_id)
     * to all existing/old candidates who registered before these fields were added.
     */
    public function up(): void
    {
        $profiles = CandidateProfile::orderBy('created_at', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $sequences = [];

        // 1. Identify highest existing sequence per year to avoid collisions
        foreach ($profiles as $profile) {
            if (! empty($profile->vpa_id)) {
                $parts = explode('-', $profile->vpa_id);
                if (count($parts) === 3 && is_numeric($parts[2])) {
                    $year = $parts[1];
                    $num = intval($parts[2]);
                    if (! isset($sequences[$year]) || $num > $sequences[$year]) {
                        $sequences[$year] = $num;
                    }
                }
            }
        }

        // 2. Assign unique Candidate ID and Agreement ID to all profiles
        foreach ($profiles as $profile) {
            $dirty = false;
            $year = $profile->created_at ? $profile->created_at->format('Y') : date('Y');

            // Assign VPA Candidate ID if missing
            if (empty($profile->vpa_id)) {
                if (! isset($sequences[$year])) {
                    $sequences[$year] = 1;
                } else {
                    $sequences[$year]++;
                }
                $profile->vpa_id = sprintf('VPA-%s-%03d', $year, $sequences[$year]);
                $dirty = true;
            }

            // Normalize or assign Agreement ID if agreement was signed
            $isSigned = $profile->is_agreement_signed
                || ! empty($profile->agreement_pdf_path)
                || ! empty($profile->signature_data);

            if ($isSigned) {
                if (! $profile->is_agreement_signed) {
                    $profile->is_agreement_signed = true;
                    $dirty = true;
                }
                if (empty($profile->agreement_id)) {
                    $profile->agreement_id = str_replace('VPA-', 'VPA-AGR-', $profile->vpa_id);
                    $dirty = true;
                }
            }

            if ($dirty) {
                $profile->saveQuietly();
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Safe: Do not delete generated IDs on rollback
    }
};
