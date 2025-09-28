<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prerequisite extends Model
{
    use HasFactory;

    protected $fillable = [
        'curriculum_id',
        'subject_code',
        'prerequisite_subject_code',
    ];

    public function curriculum()
    {
        return $this->belongsTo(Curriculum::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_code', 'subject_code');
    }

    public function prerequisiteSubject()
    {
        return $this->belongsTo(Subject::class, 'prerequisite_subject_code', 'subject_code');
    }
}