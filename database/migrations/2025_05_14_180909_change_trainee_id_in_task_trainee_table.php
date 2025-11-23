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
        Schema::table('task_trainee', function (Blueprint $table) {
            $table->uuid('trainee_id')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('task_trainee', function (Blueprint $table) {
            $table->unsignedBigInteger('trainee_id')->change();
        });
    }
};
