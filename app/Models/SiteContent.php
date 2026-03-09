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
        'contact_title',
        'image',
        'background_color',
        'text_color',
        'hero_text_color',
        'button_bg_color',
        'button_text_color',
        'navbar_bg_color',
        'navbar_text_color',
        'navbar_button_bg_color',
        'navbar_button_text_color',
        'info_text_color',
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
