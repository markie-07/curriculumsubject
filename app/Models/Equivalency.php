<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Equivalency extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'source_subject_name',
        'equivalent_subject_id',
    ];

    /**
     * Get the subject that this equivalency points to.
     */
    public function equivalentSubject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'equivalent_subject_id');
    }
}