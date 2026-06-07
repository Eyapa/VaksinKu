<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $service = app(App\Services\SatuSehatService::class);
    echo "Fetching organizations...\n";
    $res = $service->searchOrganization('Klinik Kudus', 5);
    
    if (empty($res)) {
        echo "No data returned.\n";
    }

    foreach ($res as $org) {
        $norm = $service->normalizeOrganization($org);
        echo 'NAMA: ' . $norm['nama'] . PHP_EOL;
        echo 'LAT/LONG: ' . ($norm['latitude'] ?? 'null') . ', ' . ($norm['longitude'] ?? 'null') . PHP_EOL;
        echo str_repeat('-', 20) . PHP_EOL;
    }

} catch (\Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
