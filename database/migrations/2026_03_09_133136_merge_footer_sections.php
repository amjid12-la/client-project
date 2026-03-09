<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Get data from old footer sections
        $footerAbout = DB::table('site_contents')->where('section', 'footer_about')->first();
        $footerContact = DB::table('site_contents')->where('section', 'footer_contact')->first();

        // Delete old footer sections
        DB::table('site_contents')->whereIn('section', ['footer_about', 'footer_contact'])->delete();

        // Create new combined footer section if old sections existed
        if ($footerAbout || $footerContact) {
            DB::table('site_contents')->insert([
                'section' => 'footer',
                'title' => $footerAbout->title ?? 'Report System',
                'content' => $footerAbout->content ?? 'A secure and reliable platform to submit and manage reports.',
                'email' => $footerContact->email ?? 'support@example.com',
                'phone' => $footerContact->phone ?? '123456789',
                'location' => $footerContact->location ?? 'City, Country',
                'background_color' => $footerContact->background_color ?? $footerAbout->background_color ?? '#1a1a1a',
                'text_color' => $footerContact->text_color ?? '#ffffff',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Get footer data
        $footer = DB::table('site_contents')->where('section', 'footer')->first();

        // Delete combined footer
        DB::table('site_contents')->where('section', 'footer')->delete();

        // Restore old sections if footer existed
        if ($footer) {
            // Restore footer_about
            DB::table('site_contents')->insert([
                'section' => 'footer_about',
                'title' => $footer->title,
                'content' => $footer->content,
                'background_color' => $footer->background_color,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Restore footer_contact
            DB::table('site_contents')->insert([
                'section' => 'footer_contact',
                'title' => 'Contact',
                'email' => $footer->email,
                'phone' => $footer->phone,
                'location' => $footer->location,
                'background_color' => $footer->background_color,
                'text_color' => $footer->text_color,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
};
