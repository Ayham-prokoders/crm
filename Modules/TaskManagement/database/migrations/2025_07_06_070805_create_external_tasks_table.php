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
        Schema::create('external_tasks', function (Blueprint $table) {
            $table->id();
            $table->string('task_code')->nullable();
            $table->string('status')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->text('description')->nullable();
            $table->string('title')->nullable();

            $table->string('hotel_booking')->nullable();
            $table->string('hotel_name')->nullable();
            $table->decimal('hotel_fees', 10, 2)->nullable();
            $table->string('hotel_paid')->nullable();

            $table->string('taxi_booking1')->nullable();
            $table->string('taxi_booking2')->nullable();

            $table->text('pre_questioner')->nullable();
            $table->text('requirements')->nullable();
            $table->json('requirement_mentions')->nullable();

            $table->text('hotel_note')->nullable();
            $table->text('notes')->nullable();

            $table->string('evaluation_by_email')->nullable();
            $table->string('flight_ticket')->nullable();
            $table->string('hotel_paid_2')->nullable();

            $table->string('sales_person')->nullable();
            $table->string('joining_email')->nullable();
            $table->string('location_sent')->nullable();
            $table->string('paid')->nullable();

            $table->timestamp('payment_date')->nullable();

            $table->string('zoom_link')->nullable();
            $table->string('tutor_report_received')->nullable();
            $table->string('trainer_agreement')->nullable();

            $table->float('tutor_rating')->nullable();
            $table->string('course_confirm')->nullable();
            $table->string('confirm_tutor')->nullable();
            $table->string('priority')->nullable();
            $table->string('tutor_confirmation')->nullable();
            $table->string('tutor')->nullable();

            $table->boolean('is_new')->default(false);
             $table->unsignedBigInteger('contact')->nullable();
            $table->json('trainees')->nullable();

            $table->string('company_name')->nullable();
            $table->string('course_name')->nullable();
            $table->string('city')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->date('course_date')->nullable();
            $table->string('course_duration')->nullable();
            $table->date('date')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('external_tasks');
    }
};
