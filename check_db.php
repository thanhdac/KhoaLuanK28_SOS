<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$tables = \Illuminate\Support\Facades\DB::select('SHOW TABLES'); 
foreach($tables as $table) { 
    $t = array_values((array)$table)[0]; 
    $columns = \Illuminate\Support\Facades\Schema::getColumnListing($t); 
    foreach($columns as $col) { 
        if(strpos(strtolower($col), 'hash') !== false || strpos(strtolower($col), 'active') !== false) {
            echo $t . '.' . $col . PHP_EOL; 
        }
    } 
}
