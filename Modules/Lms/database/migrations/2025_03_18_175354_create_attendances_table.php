<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Modules\Lms\Enums\AttendanceStatusEnum;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->string('lang_code')->default('en');
            $table->enum('status',AttendanceStatusEnum::getValues());
            $table->text('note')->nullable();
            $table->foreignId('classe_id')->constrained('classes')->onDelete('cascade');
            $table->foreignId('session_id')->nullable();
            // $table->foreignId('trainee_id')->constrained('users')->onDelete('cascade');
            $table->uuid('trainee_id');
            $table->foreign('trainee_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('signature')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
