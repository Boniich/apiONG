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
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('logo', 70);
            $table->string('short_description', 50);
            $table->string('long_description', 200);
            $table->string('welcome_text', 45);
            $table->string('address', 45);
            $table->string('phone', 45);
            $table->string('cell_phone', 45);
            $table->string('facebook_url', 50);
            $table->string('linkedin_url', 50);
            $table->string('instagram_url', 50);
            $table->string('twitter_url', 50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};
