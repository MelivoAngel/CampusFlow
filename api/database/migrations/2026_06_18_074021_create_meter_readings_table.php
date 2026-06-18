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
        Schema::create('meter_readings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meter_id')->constrained('meters')->cascadeOnDelete();
            $table->foreignId('technician_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('previous_reading',12,2);
            $table->decimal('current_reading',12,2);
            $table->decimal('consumption',12,2)->nullable();
            $table->string('photo_path');
            $table->boolean('has_anomaly')->default(false);
            $table->text('anomaly_reason')->nullable();
            $table->date('recorded_date')->index();
            $table->unique(['meter_id','recorded_date']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meter_readings');
    }
};
