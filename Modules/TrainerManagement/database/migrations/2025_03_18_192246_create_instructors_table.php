<?php

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\TrainerManagement\Models\Instructor;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('instructors', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->uuid('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            // $table->string('portofolio_file')->nullable();
            // $table->string('availability')->nullable();
            $table->string('location')->nullable();
            $table->float('rating')->nullable();
            // $table->text('qualification')->nullable();
            // $table->text('experiences')->nullable();
            $table->text('field')->nullable();
            $table->text('work_history')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->text('professional_summary')->nullable();
            $table->json('experience')->nullable();
            $table->json('qualification')->nullable();
            $table->json('certification')->nullable();
            $table->json('course_experience_lpc')->nullable();
            $table->json('specilized_topics')->nullable();
            $table->json('languages')->nullable();
            $table->string('slug')->unique()->nullable();
            $table->json('awards')->nullable();
            $table->json('testimonials')->nullable();
            $table->json('social_media_links')->nullable();
            $table->string('portofolio_url')->nullable();
            $table->string('linkedln_url')->nullable();
            $table->json('training_modes')->nullable();
            $table->json('availability')->nullable();
            $table->json('country_availability')->nullable();
            $table->date('date_of_submission')->nullable();
            $table->json('publications')->nullable();
            $table->json('speaking_engagements')->nullable();
            $table->uuid('uuid')->nullable();
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->string('twitter')->nullable();
            $table->string('whatsapp')->nullable();
            $table->timestamps();
        });
        Instructor::whereNull('uuid')->orWhere('uuid', '')->each(function ($instructor) {
            $instructor->uuid = (string) Str::uuid();
            $instructor->save();
        });

        Schema::table('instructors', function (Blueprint $table) {
            $table->uuid('uuid')->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instructors');
    }
};
