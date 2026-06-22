<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meter_anomalies', function (Blueprint $table) {

            $table->id();
            $table->foreignId('meter_id')->constrained('meters')->cascadeOnDelete();
            $table->foreignId('meter_reading_id')->constrained('meter_readings')->cascadeOnDelete();
            $table->foreignId('reported_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('resolved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('type');
            $table->string('severity');
            $table->text('message');
            $table->boolean('is_resolved')->default(false);
            $table->text('resolution_note')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meter_anomalies');
    }
};