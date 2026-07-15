<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Create Admin User
        \App\Models\User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@ecbus.com',
            'password' => bcrypt('password'),
        ]);

        // Create Locations
        $colombo = \App\Models\Location::create(['name' => 'Colombo']);
        $kandy = \App\Models\Location::create(['name' => 'Kandy']);
        $jaffna = \App\Models\Location::create(['name' => 'Jaffna']);

        // Create Operators
        $ecExpress = \App\Models\Operator::create(['name' => 'EC Express', 'logo' => 'bus-front']);
        $royalTravels = \App\Models\Operator::create(['name' => 'Royal Travels', 'logo' => 'bus-front']);
        $superLine = \App\Models\Operator::create(['name' => 'Super Line', 'logo' => 'bus-front']);

        // Create Buses
        $bus1 = \App\Models\Bus::create(['operator_id' => $ecExpress->id, 'type' => 'Super Luxury A/C', 'total_seats' => 40]);
        $bus2 = \App\Models\Bus::create(['operator_id' => $royalTravels->id, 'type' => 'Luxury A/C', 'total_seats' => 45]);
        $bus3 = \App\Models\Bus::create(['operator_id' => $superLine->id, 'type' => 'Semi-Luxury Non A/C', 'total_seats' => 50]);

        // Create Schedules for Tomorrow
        $tomorrow = \Carbon\Carbon::tomorrow()->format('Y-m-d');
        
        \App\Models\Schedule::create([
            'bus_id' => $bus1->id,
            'from_location_id' => $kandy->id,
            'to_location_id' => $colombo->id,
            'date' => $tomorrow,
            'departure_time' => '20:00:00',
            'arrival_time' => '00:30:00',
            'price' => 2500.00
        ]);

        \App\Models\Schedule::create([
            'bus_id' => $bus2->id,
            'from_location_id' => $kandy->id,
            'to_location_id' => $colombo->id,
            'date' => $tomorrow,
            'departure_time' => '21:30:00',
            'arrival_time' => '01:30:00',
            'price' => 2200.00
        ]);

        \App\Models\Schedule::create([
            'bus_id' => $bus3->id,
            'from_location_id' => $kandy->id,
            'to_location_id' => $colombo->id,
            'date' => $tomorrow,
            'departure_time' => '22:00:00',
            'arrival_time' => '03:00:00',
            'price' => 1500.00
        ]);
    }
}
