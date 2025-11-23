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
        Schema::table('board_tasks', function (Blueprint $table) {
            $table->string('status')->nullable()->change();
            $table->string('related_to')->nullable()->change();
            $table->string('priority')->nullable()->change();
            $table->string('label')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('board_tasks', function (Blueprint $table) {
            $table->string('status')->nullable(false)->change();
            $table->string('related_to')->nullable(false)->change();
            $table->string('priority')->nullable(false)->change();
            $table->string('label')->nullable(false)->change();
        });
    }
};
