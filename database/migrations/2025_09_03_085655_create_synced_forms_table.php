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
        Schema::create('synced_forms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('remote_id');
            $table->string('type');
            $table->string('original_type');
            $table->timestamp('form_created_at');
            $table->json('data');
            $table->string('site_key');
            $table->timestamp('synced_at');
            $table->timestamps();
            
            $table->unique(['remote_id', 'site_key']);
            $table->index(['type', 'site_key']);
            $table->index(['form_created_at', 'site_key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('synced_forms');
    }
};
