<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('meter_readings', function (Blueprint $table) {

            $table->foreignId('approved_by')->nullable()->after('technician_id')->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->after('approved_by')->constrained('users')->nullOnDelete();
            $table->boolean('is_approved')->default(false)->after('anomaly_reason');
        });
    }

    public function down(): void
    {
        Schema::table('meter_readings', function (Blueprint $table) {

            $table->dropForeign(['approved_by']);
            $table->dropForeign(['updated_by']);
            $table->dropColumn([
                'approved_by',
                'updated_by',
                'is_approved'
            ]);
        });
    }
};