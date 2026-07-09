<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function jobPost()
    {
        return $this->belongsTo(JobPost::class, 'job_post_id');
    }

    public function candidate()
    {
        return $this->belongsTo(User::class, 'candidate_id');
    }

    public function invoice()
    {
        return $this->hasOne(ServiceChargeInvoice::class, 'job_application_id');
    }
}
