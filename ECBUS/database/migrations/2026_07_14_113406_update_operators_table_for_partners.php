<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateOperatorsTableForPartners extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('operators', function (Blueprint $table) {
            $table->string('owner_name')->nullable()->after('name');
            $table->string('phone')->nullable()->after('logo');
            $table->string('whatsapp')->nullable()->after('phone');
            $table->string('email')->nullable()->after('whatsapp');
            $table->text('address')->nullable()->after('email');
            $table->text('description')->nullable()->after('address');
            $table->boolean('status')->default(true)->after('description');
            $table->decimal('commission_percentage', 5, 2)->default(0)->after('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('operators', function (Blueprint $table) {
            $table->dropColumn([
                'owner_name', 'phone', 'whatsapp', 'email', 
                'address', 'description', 'status', 'commission_percentage'
            ]);
        });
    }
}
