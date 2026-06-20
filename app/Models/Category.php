<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $guarded = [];

    public function jobs()
    {
        return $this->hasMany(JobPost::class, 'category_id');
    }
}
