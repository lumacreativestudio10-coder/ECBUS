<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSeatLayoutToBusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('buses', function (Blueprint $table) {
            $table->json('seat_layout')->nullable()->after('total_seats');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('buses', function (Blueprint $table) {
            $table->dropColumn('seat_layout');
        });
    }
}
