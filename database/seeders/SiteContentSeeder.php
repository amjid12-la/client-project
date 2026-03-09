<?php

namespace Database\Seeders;

use App\Models\SiteContent;
use Illuminate\Database\Seeder;

class SiteContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contents = [
            [
                'section' => 'hero',
                'title' => 'Report Management System',
                'subtitle' => 'Submit, track, and search reports with ease. Your voice matters in building a transparent community.',
                'content' => null,
                'contact_title' => null,
                'email' => null,
                'phone' => null,
                'location' => null,
            ],
            [
                'section' => 'info',
                'title' => 'Report Information',
                'subtitle' => null,
                'content' => 'Please provide accurate and complete information while submitting your report. Your contribution helps us maintain a reliable and searchable record for future reference.',
                'contact_title' => null,
                'email' => null,
                'phone' => null,
                'location' => null,
            ],
            [
                'section' => 'footer',
                'title' => 'Report System',
                'subtitle' => null,
                'content' => 'A secure and reliable platform to submit and manage reports. Your information helps build transparency and accountability.',
                'contact_title' => 'Contact',
                'email' => 'support@example.com',
                'phone' => '123456789',
                'location' => 'XYZ',
                'background_color' => '#1a1a1a',
                'text_color' => '#ffffff',
            ],
        ];

        foreach ($contents as $content) {
            SiteContent::updateOrCreate(
                ['section' => $content['section']],
                $content
            );
        }
    }
}
