<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CandidateProfile;

class BackfillVpaIds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vpa:backfill-ids';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backfill missing VPA IDs for existing candidates based on creation year';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $profiles = CandidateProfile::whereNull('vpa_id')
            ->orderBy('created_at', 'asc')
            ->get();

        $this->info("Found {$profiles->count()} profiles without VPA ID.");

        $sequences = [];

        foreach ($profiles as $profile) {
            $year = $profile->created_at ? $profile->created_at->format('Y') : date('Y');
            
            if (!isset($sequences[$year])) {
                $lastProfile = CandidateProfile::where('vpa_id', 'like', "VPA-{$year}-%")
                    ->orderBy('id', 'desc')
                    ->first();
                
                $nextSequence = 1;
                if ($lastProfile && $lastProfile->vpa_id) {
                    $parts = explode('-', $lastProfile->vpa_id);
                    if (count($parts) === 3) {
                        $nextSequence = intval($parts[2]) + 1;
                    }
                }
                $sequences[$year] = $nextSequence;
            } else {
                $sequences[$year]++;
            }

            $vpaId = sprintf("VPA-%s-%03d", $year, $sequences[$year]);
            $profile->vpa_id = $vpaId;
            $profile->save();

            $this->line("Assigned $vpaId to Profile ID {$profile->id}");
        }

        $this->info('Backfill complete!');
    }
}
