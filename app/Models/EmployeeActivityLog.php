<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'activity_type',
        'description',
        'metadata',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'metadata' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that performed the activity
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for filtering by activity type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('activity_type', $type);
    }

    /**
     * Scope for filtering by date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Scope for recent activities
     */
    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Get formatted activity description
     */
    public function getFormattedDescriptionAttribute()
    {
        $description = $this->description;
        
        if ($this->metadata) {
            switch ($this->activity_type) {
                case 'export':
                    if (isset($this->metadata['export_type'])) {
                        $description .= " ({$this->metadata['export_type']})";
                    }
                    break;
                case 'login':
                    $description .= " from {$this->ip_address}";
                    break;
            }
        }
        
        return $description;
    }

    /**
     * Get activity icon based on type
     */
    public function getActivityIconAttribute()
    {
        switch($this->activity_type) {
            case 'export':
                return '📄';
            case 'login':
                return '🔑';
            case 'logout':
                return '🚪';
            case 'view':
                return '👁️';
            case 'download':
                return '⬇️';
            default:
                return '📝';
        }
    }

    /**
     * Get activity color based on type
     */
    public function getActivityColorAttribute()
    {
        switch($this->activity_type) {
            case 'export':
                return 'text-blue-600';
            case 'login':
                return 'text-green-600';
            case 'logout':
                return 'text-gray-600';
            case 'view':
                return 'text-purple-600';
            case 'download':
                return 'text-indigo-600';
            default:
                return 'text-gray-600';
        }
    }
}
