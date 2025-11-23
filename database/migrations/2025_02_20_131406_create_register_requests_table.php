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
        Schema::create('register_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('external_id')->unique();
            $table->string('course_name')->nullable();
            $table->string('course_date')->nullable();
            $table->string('course_city')->nullable();
            $table->string('salutation')->nullable();
            $table->string('nationality')->nullable();
            $table->string('company')->nullable();
            $table->string('email')->nullable();
            $table->string('full_name')->nullable();
            $table->string('city')->nullable();
            $table->string('type')->nullable();
            $table->string('mobile')->nullable();
            $table->string('payment_mode')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('bill_to')->nullable();
            $table->json('participantName')->nullable();
            $table->json('participantEmail')->nullable();
            $table->json('participantPhone')->nullable();
            $table->json('participantPosition')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('register_requests');
    }
};
