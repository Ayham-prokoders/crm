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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('lang_code')->default('en');
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->string('duration')->nullable();
            $table->text('days_content')->nullable();
            $table->text('related_courses')->nullable();
            // $table->unsignedBigInteger('status')->nullable();//(0,1)
            $table->unsignedBigInteger('category_id')->nullable();
            $table->boolean('online')->default(false);
            // $table->date('startDate')->nullable();
            // $table->string('city')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
