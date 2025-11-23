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
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->string('lang_code')->default('en');
            $table->boolean('show_in_website')->default(false);
            $table->unsignedInteger('external_id')->nullable();
            $table->string('course_custom_name')->nullable();
            $table->string('course_type');
            $table->unsignedInteger('course_id')->nullable();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('ID_certificate')->unique();
            $table->string('image');
            $table->string('pdf');
            $table->unsignedInteger('user_id');
            $table->string('origin');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
