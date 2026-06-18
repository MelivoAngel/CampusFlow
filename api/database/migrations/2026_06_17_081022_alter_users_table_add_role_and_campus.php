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
        Schema::table('users', function (Blueprint $table) {

            $table->foreignId('campus_id')
                ->nullable()
                ->after('id')
                ->constrained('campuses')
                ->nullOnDelete();

            $table->foreignId('created_by')
                ->nullable()
                ->after('campus_id')
                ->constrained('users')
                ->nullOnDelete();

            $table->string('role')
                ->default('field_technician')
                ->after('password');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropForeign(['campus_id']);
            $table->dropForeign(['created_by']);

            $table->dropColumn([
                'campus_id',
                'created_by',
                'role'
            ]);
        });
    }
};
