<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('site_contents', function (Blueprint $table) {
            $table->string('hero_text_color', 7)->nullable()->after('text_color');
            $table->string('button_bg_color', 7)->nullable()->after('hero_text_color');
            $table->string('button_text_color', 7)->nullable()->after('button_bg_color');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('site_contents', function (Blueprint $table) {
            $table->dropColumn(['hero_text_color', 'button_bg_color', 'button_text_color']);
        });
    }
};
