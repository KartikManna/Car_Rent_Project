<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Updating vehicle image paths in database...\n";

DB::statement("UPDATE vehicle_images SET path = REPLACE(path, 'vehicles/', 'vehicle/')");
DB::statement("UPDATE vehicles SET image_path = REPLACE(image_path, 'vehicles/', 'vehicle/')");

echo "Done.\n";
