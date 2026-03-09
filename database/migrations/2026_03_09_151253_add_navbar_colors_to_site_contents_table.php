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
            $table->string('navbar_bg_color', 7)->nullable()->after('button_text_color');
            $table->string('navbar_text_color', 7)->nullable()->after('navbar_bg_color');
            $table->string('navbar_button_bg_color', 7)->nullable()->after('navbar_text_color');
            $table->string('navbar_button_text_color', 7)->nullable()->after('navbar_button_bg_color');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('site_contents', function (Blueprint $table) {
            $table->dropColumn(['navbar_bg_color', 'navbar_text_color', 'navbar_button_bg_color', 'navbar_button_text_color']);
        });
    }
};
