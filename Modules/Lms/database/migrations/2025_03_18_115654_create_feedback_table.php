<?php

use Modules\Lms\Enums\FeedbackEnum;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('feedback', function (Blueprint $table) {
            $table->id();
            $table->string('lang_code')->default('en');
            $table->enum('type',FeedbackEnum::getValues());
            $table->text('message')->nullable();
            $table->float('rate')->nullable();
            $table->foreignId('course_id')->nullable()->constrained('courses')->onDelete('cascade');
            $table->uuid('trainer_id')->nullable();
            $table->foreign('trainer_id')->references('id')->on('users')->onDelete('cascade');
            // $table->foreignId('trainer_id')->nullable()->constrained('users')->onDelete('cascade');
            // $table->foreignId('trainee_id')->constrained('users')->onDelete('cascade');
            $table->uuid('trainee_id')->nullable();
            $table->foreign('trainee_id')->references('id')->on('users')->onDelete('cascade');
            $table->float('trainers_rate')->nullable();
            $table->float('materials_rate')->nullable();
            $table->float('hospitality_rate')->nullable();
            $table->float('hotel_rate')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback');
    }
};
