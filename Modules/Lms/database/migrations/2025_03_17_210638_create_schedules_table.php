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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_id')->constrained('courses')->onDelete('cascade');
            $table->date('start_date')->nullable();
            $table->unsignedInteger('city_id')->nullable();
            $table->unsignedBigInteger('trainer_id')->nullable();
            $table->boolean('online')->default(0);
            $table->string('price')->nullable();
            $table->unsignedInteger('external_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
