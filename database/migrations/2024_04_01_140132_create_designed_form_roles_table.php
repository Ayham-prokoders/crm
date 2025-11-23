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
        Schema::create('designed_form_roles', function (Blueprint $table) {
            $table->id(); 
            $table->unsignedBigInteger('role_id');
            $table->uuid('designed_form_id');
            $table->foreign('designed_form_id')->references('id')->on('designed_forms')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('designed_form_roles');
    }
};
