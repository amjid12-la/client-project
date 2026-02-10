<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'section',
        'title',
        'subtitle',
        'content',
        'image',
        'email',
        'phone',
        'location',
    ];

    // Helper method to get content by section
    public static function getSection($section)
    {
        return self::where('section', $section)->first();
    }
}
