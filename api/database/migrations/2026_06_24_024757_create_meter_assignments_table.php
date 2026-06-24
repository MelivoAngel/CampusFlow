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
        Schema::create('meter_assignments', function (Blueprint $table) {

        $table->id();
        $table->foreignId('meter_id')->constrained()->cascadeOnDelete();
        $table->foreignId('technician_id')->constrained('users')->cascadeOnDelete();
        $table->foreignId('assigned_by')->constrained('users')->cascadeOnDelete();
        $table->unique(['meter_id']);
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meter_assignments');
    }
};
