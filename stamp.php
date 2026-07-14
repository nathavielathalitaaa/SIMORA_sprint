<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $surat = App\Models\Surat::find(2);
    if ($surat) {
        $pdfService = app(\App\Services\PdfStampService::class);
        $pdfService->stamp($surat);
        echo 'STAMPED OK';
    } else {
        echo 'Surat not found';
    }
} catch (\Exception $e) {
    echo 'ERROR: ' . $e->getMessage() . "\n" . $e->getTraceAsString();
}
