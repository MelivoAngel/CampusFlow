<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table(
            'schedules',
            function (
                Blueprint $table
            ) {

                $table->dropColumn(
                    'building_id'
                );

                $table->foreignId(

                    'facility_id'

                )->after(

                    'created_by'

                )->constrained(

                    'facilities'

                )->cascadeOnDelete();
            }
        );
    }

    public function down(): void
    {
        Schema::table(
            'schedules',
            function (
                Blueprint $table
            ) {

                $table->dropColumn(
                    'facility_id'
                );

                $table->foreignId(

                    'building_id'

                )->constrained(

                    'buildings'

                )->cascadeOnDelete();
            }
        );
    }
};