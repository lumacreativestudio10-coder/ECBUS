<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('status')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });

        Schema::create('bus_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        Schema::create('buses', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('bus_number');
            $table->string('registration_number');
            $table->foreignId('bus_type_id')->constrained('bus_types');
            $table->foreignId('bus_company_id')->constrained('bus_companies')->onDelete('cascade');
            $table->integer('total_seats');
            $table->longText('seat_layout');
            $table->string('image')->nullable();
            $table->longText('facilities')->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['active', 'maintenance', 'inactive'])->default('active');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('routes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('from_location_id')->constrained('locations');
            $table->foreignId('to_location_id')->constrained('locations');
            $table->decimal('distance', 8, 2);
            $table->integer('estimated_duration_minutes');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->decimal('starting_price', 10, 2);
            $table->boolean('is_popular')->default(false);
            $table->boolean('status')->default(true);
            $table->foreignId('company_id')->constrained('bus_companies')->onDelete('cascade');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('route_stops', function (Blueprint $table) {
            $table->id();
            $table->foreignId('route_id')->constrained('routes')->onDelete('cascade');
            $table->string('stop_name');
            $table->integer('stop_order');
            $table->integer('time_offset_minutes');
            $table->timestamps();
        });

        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bus_id')->constrained('buses');
            $table->foreignId('route_id')->constrained('routes');
            $table->date('date');
            $table->time('departure_time');
            $table->time('arrival_time');
            $table->decimal('price', 8, 2);
            $table->enum('status', ['scheduled', 'in_progress', 'completed', 'cancelled'])->default('scheduled');
            $table->integer('available_seats')->nullable();
            $table->foreignId('driver_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('conductor_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('publish_status')->default('published');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_reference');
            $table->foreignId('schedule_id')->constrained('schedules');
            $table->string('customer_name');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->integer('passenger_count');
            $table->longText('seat_numbers');
            $table->string('boarding_point')->nullable();
            $table->string('dropping_point')->nullable();
            $table->decimal('total_amount', 10, 2);
            $table->decimal('paid_amount', 10, 2)->default(0.00);
            $table->string('payment_receipt_path')->nullable();
            $table->enum('booking_status', ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending');
            $table->boolean('is_verified')->default(false);
            $table->longText('boarding_statuses')->nullable();
            $table->enum('booking_source', ['website', 'counter', 'staff', 'phone', 'admin'])->default('website');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('seat_locks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('schedule_id')->constrained('schedules')->onDelete('cascade');
            $table->string('seat_number');
            $table->string('session_id');
            $table->timestamp('expires_at');
            $table->timestamps();
        });

        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->string('image')->nullable();
            $table->text('description');
            $table->enum('status', ['pending', 'published', 'rejected'])->default('pending');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('popular_routes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('from_location_id')->constrained('locations');
            $table->foreignId('to_location_id')->constrained('locations');
            $table->string('image')->nullable();
            $table->decimal('starting_price', 10, 2);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('popular_routes');
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('seat_locks');
        Schema::dropIfExists('bookings');
        Schema::dropIfExists('schedules');
        Schema::dropIfExists('route_stops');
        Schema::dropIfExists('routes');
        Schema::dropIfExists('buses');
        Schema::dropIfExists('bus_types');
        Schema::dropIfExists('locations');
    }
};
