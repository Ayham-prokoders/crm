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
        Schema::create('external_courses', function (Blueprint $table) {
            $table->id();
            $table->string('lang_code')->default('en');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->boolean('online')->default(false);
            $table->string('name');
            $table->string('city');
            $table->date('date');
            $table->string('price')->nullable();
            $table->text('objective')->nullable();
            $table->string('duration')->nullable();
            $table->string('code')->nullable();
            $table->string('project_source')->nullable();
            $table->unsignedInteger('external_id')->nullable();
             $table->string('base_code')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('external_courses');
    }
};
