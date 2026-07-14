<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$mpk = App\Models\Organisasi::where('tipe', 'mpk')->first();
$bphMpk = App\Models\OrganisasiMember::where('organisasi_id', $mpk->id)->where('jabatan', 'bph')->with('user')->first();
echo json_encode(['email' => $bphMpk->user->email ?? 'not_found', 'name' => $bphMpk->user->name ?? 'not_found', 'password' => 'Sinergi@2026']);
