<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CandidateRating extends Model
{
    protected $guarded = [];

    public function candidate()
    {
        return $this->belongsTo(User::class, 'candidate_id');
    }
    
    public function rater()
    {
        return $this->belongsTo(User::class, 'rated_by');
    }
}
