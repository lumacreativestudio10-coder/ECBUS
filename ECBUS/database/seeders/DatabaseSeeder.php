<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use App\Models\BusCompany;
use App\Models\BusType;
use App\Models\Bus;
use App\Models\Location;
use App\Models\Route;
use App\Models\Schedule;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // 1. Roles
        $roles = [
            ['id' => 1, 'name' => 'Super Admin'],
            ['id' => 2, 'name' => 'Company Admin'],
            ['id' => 3, 'name' => 'Company Staff'],
            ['id' => 4, 'name' => 'Driver'],
            ['id' => 5, 'name' => 'Conductor'],
            ['id' => 6, 'name' => 'Customer'],
        ];

        foreach ($roles as $r) {
            Role::firstOrCreate(['id' => $r['id']], $r);
        }

        // 2. Super Admin
        User::firstOrCreate(['email' => 'superadmin@ecbus.com'], [
            'name' => 'Super Admin',
            'password' => Hash::make('password'),
            'role_id' => 1,
            'status' => 1
        ]);

        // 3. Bus Company
        $company = BusCompany::firstOrCreate(['email' => 'contact@royalbus.com'], [
            'company_name' => 'Royal Express',
            'contact_person' => 'Royal Owner',
            'address' => '123 Main St, Colombo',
            'mobile_number' => '0771234567',
            'commission_per_seat' => 10.00,
            'status' => 1
        ]);

        // 4. Company Users
        User::firstOrCreate(['email' => 'admin@royalbus.com'], [
            'name' => 'Royal Admin',
            'password' => Hash::make('password'),
            'role_id' => 2,
            'company_id' => $company->id,
            'status' => 1
        ]);

        User::firstOrCreate(['email' => 'staff@royalbus.com'], [
            'name' => 'Royal Staff',
            'password' => Hash::make('password'),
            'role_id' => 3,
            'company_id' => $company->id,
            'status' => 1
        ]);

        $driver = User::firstOrCreate(['email' => 'driver@royalbus.com'], [
            'name' => 'Ravi Driver',
            'password' => Hash::make('password'),
            'role_id' => 4,
            'company_id' => $company->id,
            'status' => 1
        ]);

        $conductor = User::firstOrCreate(['email' => 'conductor@royalbus.com'], [
            'name' => 'S. Kumar Conductor',
            'password' => Hash::make('password'),
            'role_id' => 5,
            'company_id' => $company->id,
            'status' => 1
        ]);

        // 5. Bus Types & Buses
        $busType = BusType::firstOrCreate(['name' => 'AC Sleeper 2x1'], [
            'description' => '40 Seats, 2x1 Layout',
            'status' => 1
        ]);

        $layoutData = [
            'rows' => 10,
            'cols' => 5,
            'map' => []
        ];
        for ($r = 0; $r < 10; $r++) {
            $row = [];
            for ($c = 0; $c < 5; $c++) {
                if ($c === 0 || $c === 4) $row[] = 'window';
                else if ($c === 2) $row[] = 'empty';
                else $row[] = 'seat';
            }
            $layoutData['map'][] = $row;
        }

        $bus = Bus::firstOrCreate(['bus_number' => 'NB-1023'], [
            'bus_company_id' => $company->id,
            'bus_type_id' => $busType->id,
            'registration_number' => 'REG1023',
            'total_seats' => 40,
            'seat_layout' => json_encode($layoutData),
            'status' => 1
        ]);

        // 6. Locations
        $colombo = Location::firstOrCreate(['name' => 'Colombo'], ['status' => 1]);
        $jaffna = Location::firstOrCreate(['name' => 'Jaffna'], ['status' => 1]);

        // 7. Route
        $route = Route::firstOrCreate(['from_location_id' => $colombo->id, 'to_location_id' => $jaffna->id], [
            'company_id' => $company->id,
            'distance' => '400',
            'estimated_duration_minutes' => 480,
            'starting_price' => 2500.00,
            'status' => 1,
            'name' => 'Colombo - Jaffna'
        ]);

        // 8. Schedule (For Today)
        $today = Carbon::today();
        
        $schedule = Schedule::firstOrCreate([
            'route_id' => $route->id,
            'bus_id' => $bus->id,
            'date' => $today->toDateString(),
        ], [
            'driver_id' => $driver->id,
            'conductor_id' => $conductor->id,
            'departure_time' => $today->copy()->setTime(8, 30)->format('H:i:s'),
            'arrival_time' => $today->copy()->setTime(16, 30)->format('H:i:s'),
            'price' => 2500.00,
            'status' => 'scheduled' // changed from published
        ]);

        // 9. Bookings
        // Booking 1: Verified and Boarded
        Booking::firstOrCreate(['booking_reference' => 'ECB100001'], [
            'schedule_id' => $schedule->id,
            'customer_name' => 'Arun Prakash',
            'email' => 'arun@example.com',
            'phone' => '0779876543',
            'passenger_count' => 2,
            'seat_numbers' => ['W1', 'S1'],
            'total_amount' => 5000.00,
            'booking_status' => 'confirmed',
            'is_verified' => true,
            'boarding_statuses' => ['W1' => 'boarded', 'S1' => 'boarded']
        ]);

        // Booking 2: Verified, one boarded, one pending
        Booking::firstOrCreate(['booking_reference' => 'ECB100002'], [
            'schedule_id' => $schedule->id,
            'customer_name' => 'Bala Kumaran',
            'email' => 'bala@example.com',
            'phone' => '0712345678',
            'passenger_count' => 2,
            'seat_numbers' => ['S2', 'W2'],
            'total_amount' => 5000.00,
            'booking_status' => 'confirmed',
            'is_verified' => true,
            'boarding_statuses' => ['S2' => 'boarded', 'W2' => 'pending']
        ]);

        // Booking 3: Not verified, pending
        Booking::firstOrCreate(['booking_reference' => 'ECB100003'], [
            'schedule_id' => $schedule->id,
            'customer_name' => 'Meena Devi',
            'email' => 'meena@example.com',
            'phone' => '0755555555',
            'passenger_count' => 1,
            'seat_numbers' => ['W3'],
            'total_amount' => 2500.00,
            'booking_status' => 'confirmed',
            'is_verified' => false,
            'boarding_statuses' => ['W3' => 'pending']
        ]);

        // Booking 4: Verified, No show
        Booking::firstOrCreate(['booking_reference' => 'ECB100004'], [
            'schedule_id' => $schedule->id,
            'customer_name' => 'Karthi',
            'email' => 'karthi@example.com',
            'phone' => '0761122334',
            'passenger_count' => 1,
            'seat_numbers' => ['S3'],
            'total_amount' => 2500.00,
            'booking_status' => 'confirmed',
            'is_verified' => true,
            'boarding_statuses' => ['S3' => 'no_show']
        ]);
    }
}
