<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('meter_readings', function (Blueprint $table) {

            $table->boolean('was_corrected')->default(false)->after('is_approved');
        });
    }

    public function down(): void
    {
        Schema::table('meter_readings', function (Blueprint $table) {

            $table->dropColumn('was_corrected');
        });
    }
};