<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPartiallyPaidToCompanySettlementsStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement("ALTER TABLE company_settlements MODIFY COLUMN status ENUM('pending', 'processing', 'partially_paid', 'paid', 'rejected', 'cancelled') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement("ALTER TABLE company_settlements MODIFY COLUMN status ENUM('pending', 'processing', 'paid', 'rejected', 'cancelled') DEFAULT 'pending'");
    }
}
