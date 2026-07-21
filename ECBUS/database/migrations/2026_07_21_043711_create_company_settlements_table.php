<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanySettlementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_settlements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('bus_companies')->onDelete('cascade');
            $table->date('period_start');
            $table->date('period_end');
            $table->decimal('total_revenue', 15, 2)->default(0);
            $table->decimal('total_commission', 15, 2)->default(0);
            $table->decimal('net_payable', 15, 2)->default(0);
            $table->enum('status', ['pending', 'processing', 'paid', 'rejected', 'cancelled'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_settlements');
    }
}
