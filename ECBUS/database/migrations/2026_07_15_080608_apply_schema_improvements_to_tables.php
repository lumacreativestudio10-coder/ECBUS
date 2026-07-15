<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ApplySchemaImprovementsToTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1. Locations
        Schema::table('locations', function (Blueprint $table) {
            $table->boolean('status')->default(1)->after('name');
        });

        // 2. Operators
        Schema::table('operators', function (Blueprint $table) {
            $table->dropColumn(['operating_routes', 'total_buses', 'commission_percentage']);
        });

        // 3. Buses
        // Note: doctrine/dbal doesn't fully support dropping and recreating ENUM simply via change() on some versions,
        // so we drop the boolean and add the ENUM.
        Schema::table('buses', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        Schema::table('buses', function (Blueprint $table) {
            $table->enum('status', ['active', 'maintenance', 'inactive'])->default('active')->after('description');
        });

        // 4. Routes
        Schema::table('routes', function (Blueprint $table) {
            $table->integer('estimated_duration_minutes')->nullable()->after('distance');
        });
        // We can copy data over if needed, but since it's early stage we will just drop the old one.
        Schema::table('routes', function (Blueprint $table) {
            $table->dropColumn('estimated_duration');
        });

        // 5. Schedules
        Schema::table('schedules', function (Blueprint $table) {
            $table->enum('status', ['scheduled', 'completed', 'cancelled'])->default('scheduled')->after('price');
            $table->integer('available_seats')->default(0)->after('price');
        });

        // 6. Bookings
        Schema::table('bookings', function (Blueprint $table) {
            $table->renameColumn('passenger_name', 'customer_name');
            $table->string('email')->nullable()->after('phone');
            $table->string('booking_reference')->nullable()->unique()->after('id');
            // Drop old status varchar, add new enum
            $table->dropColumn('status');
        });
        Schema::table('bookings', function (Blueprint $table) {
            $table->enum('booking_status', ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending')->after('payment_receipt_path');
        });

        // 7. Users
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['Super Admin', 'Admin'])->default('Admin')->after('password');
            $table->boolean('status')->default(1)->after('role');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Simple down to revert back
    }
}
