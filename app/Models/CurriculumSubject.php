<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class CurriculumSubject extends Pivot
{
    use HasFactory;

    protected $table = 'curriculum_subject';
}