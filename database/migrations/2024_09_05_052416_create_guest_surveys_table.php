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
        Schema::create('guest_surveys', function (Blueprint $table) {
            $table->id();
            $table->string('lang_code')->default('en');
            $table->uuid('designed_form_id');
            $table->foreign('designed_form_id')->references('id')->on('designed_forms')->onDelete('cascade');
            $table->string('email');
            $table->json('info')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guest_surveys');
    }
};
