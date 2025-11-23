<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\QuestionEnum;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('lang_code')->default('en');
            $table->enum('type',QuestionEnum::getValues());
            $table->text('question')->nullable();
            $table->json('options')->nullable();
            $table->boolean('is_required')->default(false);
            $table->uuid('designed_form_id');
            $table->foreign('designed_form_id')->references('id')->on('designed_forms')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
