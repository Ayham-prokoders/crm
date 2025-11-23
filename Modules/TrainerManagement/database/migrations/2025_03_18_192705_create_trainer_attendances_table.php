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
        Schema::create('trainer_attendances', function (Blueprint $table) {
            $table->id();
            $table->string('lang_code')->default('en');
            $table->string('status')->nullable();
            $table->text('note')->nullable();
            $table->string('signature')->nullable();
            $table->foreignId('classe_id')->constrained('classes')->onDelete('cascade');
            $table->foreignId('session_id')->nullable();
            // $table->foreignId('trainer_id')->constrained('users')->onDelete('cascade');
            $table->uuid('trainer_id');
            $table->foreign('trainer_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trainer_attendances');
    }
};
