<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

DB::table('document_approvals')->delete();
$surat = \App\Models\Surat::find(2);
app(\App\Services\ApprovalService::class)->initFromSuratType($surat);
echo 'OK';
