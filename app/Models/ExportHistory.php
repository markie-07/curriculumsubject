<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExportHistory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'curriculum_id',
        'user_id',
        'exported_by_name',
        'exported_by_email',
        'file_name',
        'format',
    ];

    /**
     * Get the curriculum that owns the export history.
     */
    public function curriculum()
    {
        return $this->belongsTo(Curriculum::class);
    }

    /**
     * Get the user who exported the curriculum.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}