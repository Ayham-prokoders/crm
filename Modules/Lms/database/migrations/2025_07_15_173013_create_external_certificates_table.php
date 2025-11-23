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
        Schema::create('external_certificates', function (Blueprint $table) {
            $table->id();
            $table->string('lang_code')->default('en');
            $table->string('project_source')->nullable();
            $table->string('type');
            $table->unsignedBigInteger('category');
            $table->string('city')->nullable();
            $table->string('course')->nullable();
            $table->date('schedule')->nullable();
            $table->string('class')->nullable();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->boolean('show_in_website')->default(false);
            $table->string('image')->nullable();
            $table->string('course_type');
            $table->string('ID_certificate')->unique();
            $table->string('pdf')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('external_certificates');
    }
};
