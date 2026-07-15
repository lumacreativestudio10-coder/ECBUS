<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateBusesTableForBusTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('buses', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->string('name')->nullable()->after('operator_id');
            $table->string('bus_number')->nullable()->after('name');
            $table->string('registration_number')->nullable()->after('bus_number');
            $table->foreignId('bus_type_id')->nullable()->after('registration_number')->constrained('bus_types')->onDelete('set null');
            $table->string('image')->nullable();
            $table->json('facilities')->nullable();
            $table->text('description')->nullable();
            $table->boolean('status')->default(true);
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
            $table->dropForeign(['bus_type_id']);
            $table->dropColumn([
                'name', 'bus_number', 'registration_number', 'bus_type_id', 
                'image', 'facilities', 'description', 'status'
            ]);
            $table->string('type')->after('operator_id');
        });
    }
}
