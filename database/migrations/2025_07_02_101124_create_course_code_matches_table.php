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
        Schema::create('course_code_matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('source_course_id')->nullable()->constrained('courses')->nullOnDelete();
            $table->foreignId('target_course_id')->constrained('courses')->onDelete('cascade');
            $table->string('applied_code');
            $table->enum('status', ['active', 'not_active'])->default('not_active');
            $table->string('target_project'); 
            $table->string('note'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_code_matches');
    }
};
