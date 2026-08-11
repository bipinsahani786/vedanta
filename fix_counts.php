<?php
$profiles = \App\Models\CandidateProfile::all();
$fixed = 0;
foreach($profiles as $profile) {
    $startDate = $profile->plan_started_at ?? $profile->created_at;
    $actualCount = \App\Models\JobApplication::where('candidate_id', $profile->user_id)
        ->where('created_at', '>=', $startDate)
        ->count();
        
    if ($profile->used_applications != $actualCount) {
        echo "User {$profile->user_id}: Changing used_applications from {$profile->used_applications} to {$actualCount}\n";
        $profile->used_applications = $actualCount;
        $profile->save();
        $fixed++;
    }
}
echo "Fixed {$fixed} profiles.\n";
