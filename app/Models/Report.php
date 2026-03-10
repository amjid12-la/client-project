<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'individual_name',
        'photo_path',
        'incident_date',
        'location',
        'narrative',
        'status',
        'reviewed_at',
        'reviewed_by',
        'admin_notes',
        'duplicate_check',
    ];

    protected $casts = [
        'incident_date' => 'date',
        'reviewed_at' => 'datetime',
    ];

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function getPhotoUrlAttribute()
    {
        if ($this->photo_path) {
            // Photo is stored in public/frontend/reports/
            return asset($this->photo_path);
        }
        return null;
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
}
