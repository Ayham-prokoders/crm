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
        Schema::create('survey_builder_recipients', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('survey_builder_id');
            $table->uuid('user_id');
            $table->timestamp('answered_at')->nullable();

            $table->string('hmac_token')->unique();
            $table->foreign('survey_builder_id')->references('id')->on('survey_builders')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survey_builder_recipient');
    }
};
