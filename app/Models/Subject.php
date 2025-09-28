<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany; // 1. Add this line

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_code',
        'subject_name',
        'subject_type',
        'subject_unit',
        'lessons',
    ];

    protected $casts = [
        'lessons' => 'array',
    ];

    public function curriculums(): BelongsToMany
    {
        return $this->belongsToMany(Curriculum::class)
            ->withPivot('year', 'semester')
            ->withTimestamps();
    }

    // 2. Add this new function to define the relationship
    /**
     * Get the prerequisites for the subject.
     */
    public function prerequisites(): HasMany
    {
        return $this->hasMany(Prerequisite::class, 'subject_code', 'subject_code');
    }
}