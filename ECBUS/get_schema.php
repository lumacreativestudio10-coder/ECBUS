<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$tables = ['locations', 'operators', 'bus_types', 'buses', 'routes', 'schedules', 'bookings', 'reviews', 'users'];
foreach($tables as $table) {
    echo "## $table\n";
    $columns = DB::select("DESCRIBE $table");
    foreach($columns as $c) {
        echo "- **{$c->Field}** ({$c->Type})\n";
    }
    echo "\n";
}
