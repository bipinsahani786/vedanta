<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Specialization extends Model
{
    protected $fillable = ['subject_id', 'name', 'is_active'];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
