<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// 1. Pembina OSIS
$osis = App\Models\Organisasi::where('tipe', 'osis')->first();
$pembina = App\Models\OrganisasiMember::where('organisasi_id', $osis->id)->where('jabatan', 'pembina')->with('user')->first();

// 2. Pengawas Pusat (global Spatie role)
$pengawas = App\Models\User::role('pengawas_pusat')->first();

// 3. Kepala Sekolah (global Spatie role)
$kepsek = App\Models\User::role('kepala_sekolah')->first();

echo json_encode([
    'pembina_osis' => ['email' => $pembina->user->email ?? 'not_found', 'password' => 'Sinergi@2026'],
    'pengawas_pusat' => ['email' => $pengawas->email ?? 'not_found', 'password' => 'Sinergi@2026'],
    'kepala_sekolah' => ['email' => $kepsek->email ?? 'not_found', 'password' => 'Sinergi@2026'],
]);
