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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('task_code')->nullable();
            $table->foreignId('deal_id')->nullable()->constrained('deals')->onDelete('cascade');
            $table->unsignedBigInteger('assign_to')->nullable();
            $table->string('status')->nullable();
            $table->text('description')->nullable();
            $table->string('title')->nullable();
            $table->string('duration')->nullable();
            $table->boolean('hotel_booking')->nullable();
            $table->string('hotel_name')->nullable();
            $table->string('hotel_fees')->nullable();
            $table->boolean('hotel_paid')->nullable();
            $table->boolean('taxi_booking1')->nullable();
            $table->boolean('taxi_booking2')->nullable();
            $table->boolean('pre_questioner')->nullable();
            $table->boolean('is_new')->default(false);

            $table->text('requirements')->nullable();
            $table->string('sales_person')->nullable();

            $table->string('hotel_note')->nullable();
            $table->string('notes')->nullable();
            $table->boolean('evaluation_by_email')->nullable();
            $table->boolean('flight_ticket')->nullable();
            $table->boolean('hotel_paid_2')->nullable();
            $table->boolean('joining_email')->nullable();
            $table->boolean('location_sent')->nullable();
            $table->boolean('paid')->nullable();
            $table->date('payment_date')->nullable();

            $table->boolean('zoom_link')->nullable();
            $table->boolean('tutor_report_received')->nullable();
            $table->boolean('trainer_agreement')->nullable();
            $table->string('tutor_rating')->nullable();
            $table->string('course_confirm')->nullable();
            $table->string('confirm_tutor')->nullable();
            $table->string('priority')->nullable();
            $table->string('tutor_confirmation')->nullable();
            $table->string('tutor')->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
