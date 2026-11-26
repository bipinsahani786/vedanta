<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployerProfile extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'agreement_uploaded_at' => 'datetime',
            'agreement_reviewed_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'agreement_reviewed_by');
    }
}
