<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'curriculum_id',
        'subject_id',
        'academic_year',
        'subject_name',
        'subject_code',
        'year',
        'semester',
        'action',
    ];

    public function curriculum()
    {
        return $this->belongsTo(Curriculum::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
