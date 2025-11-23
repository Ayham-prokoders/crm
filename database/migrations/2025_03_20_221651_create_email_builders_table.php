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
        Schema::create('email_builders', function (Blueprint $table) {
            $table->id();
            $table->string('template_name')->nullable();
            $table->string('subject')->nullable();
            $table->string('category')->nullable();
            $table->longText('body')->nullable();
            $table->longText('html_body')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_builders');
    }
};
