<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSoftDeletesToTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('operators', function (Blueprint $table) { $table->softDeletes(); });
        Schema::table('buses', function (Blueprint $table) { $table->softDeletes(); });
        Schema::table('routes', function (Blueprint $table) { $table->softDeletes(); });
        Schema::table('schedules', function (Blueprint $table) { $table->softDeletes(); });
        Schema::table('bookings', function (Blueprint $table) { $table->softDeletes(); });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('operators', function (Blueprint $table) { $table->dropSoftDeletes(); });
        Schema::table('buses', function (Blueprint $table) { $table->dropSoftDeletes(); });
        Schema::table('routes', function (Blueprint $table) { $table->dropSoftDeletes(); });
        Schema::table('schedules', function (Blueprint $table) { $table->dropSoftDeletes(); });
        Schema::table('bookings', function (Blueprint $table) { $table->dropSoftDeletes(); });
    }
}
