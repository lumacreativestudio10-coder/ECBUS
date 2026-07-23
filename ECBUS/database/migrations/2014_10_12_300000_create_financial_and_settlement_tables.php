<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('commission_rules', function (Blueprint $table) {
            $table->id();
            $table->string('booking_source');
            $table->enum('type', ['fixed', 'percentage']);
            $table->decimal('value', 8, 2);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('booking_commissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            $table->foreignId('company_id')->constrained('bus_companies')->onDelete('cascade');
            $table->foreignId('rule_id')->constrained('commission_rules')->onDelete('cascade');
            $table->decimal('commission_amount', 10, 2);
            $table->timestamps();
        });

        Schema::create('company_settlements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('bus_companies')->onDelete('cascade');
            $table->date('period_start');
            $table->date('period_end');
            $table->decimal('total_revenue', 15, 2);
            $table->decimal('total_commission', 15, 2);
            $table->decimal('net_payable', 15, 2);
            $table->enum('status', ['pending', 'processing', 'partially_paid', 'paid', 'rejected', 'cancelled'])->default('pending');
            $table->timestamps();
        });

        Schema::create('settlement_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('settlement_id')->constrained('company_settlements')->onDelete('cascade');
            $table->decimal('amount', 15, 2);
            $table->date('payment_date');
            $table->string('receipt_path')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('reference_number')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('settlement_payments');
        Schema::dropIfExists('company_settlements');
        Schema::dropIfExists('booking_commissions');
        Schema::dropIfExists('commission_rules');
    }
};
