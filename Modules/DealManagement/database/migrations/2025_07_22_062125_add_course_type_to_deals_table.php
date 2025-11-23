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
         if (!Schema::hasColumn('deals', 'course_type')) {
            Schema::table('deals', function (Blueprint $table) {
                $table->string('course_type')->default('official')->after('course_id');
            });
        }

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('deals', function (Blueprint $table) {
            Schema::dropIfExists('course_type');
        });
    }
};
