<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSchedulesTableForRoutes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropForeign(['from_location_id']);
            $table->dropForeign(['to_location_id']);
            $table->dropColumn(['from_location_id', 'to_location_id']);

            $table->foreignId('route_id')->after('bus_id')->constrained('routes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropForeign(['route_id']);
            $table->dropColumn('route_id');

            $table->foreignId('from_location_id')->constrained('locations')->onDelete('cascade');
            $table->foreignId('to_location_id')->constrained('locations')->onDelete('cascade');
        });
    }
}
