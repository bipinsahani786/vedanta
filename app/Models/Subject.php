<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = [
        'name',
        'is_active',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_subject');
    }

    public function specializations()
    {
        return $this->hasMany(Specialization::class);
    }
}
