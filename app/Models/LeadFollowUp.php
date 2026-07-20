<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadFollowUp extends Model
{
    protected $guarded = [];

    public function lead()
    {
        return $this->belongsTo(ContactLead::class, 'lead_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
