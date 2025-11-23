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
        Schema::create('trainer_signatures', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('trainer_id')->nullable();
            $table->uuid('trainer_id');
            $table->foreign('trainer_id')->references('id')->on('users')->onDelete('cascade');
            // $table->foreignId('trainee_id')->nullable();
            $table->uuid('trainee_id');
            $table->foreign('trainee_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('classe_id')->nullable();
            $table->string('status')->nullable();
            $table->string('signature')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trainer_signatures');
    }
};
