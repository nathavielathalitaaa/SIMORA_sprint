<?php

namespace Tests\Feature;

use App\Models\Organisasi;
use App\Models\Surat;
use App\Models\SuratType;
use App\Models\User;
use App\Http\Middleware\CheckOnboarding;
use App\Http\Middleware\ForceChangePassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class SuratValidationTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $osis;
    private $rohis;
    private $proposalOsisType;
    private $proposalSubOrganType;
    private $suratResmiOsisType;

    protected function setUp(): void
    {
        parent::setUp();

        // Run seeders to get roles, surat types, and organizations
        $this->seed();

        // Find standard user we can use
        $this->user = User::where('email', 'bph.osis1@smktelkom-sdj.sch.id')->first();

        // Get organizations from database
        $this->osis = Organisasi::where('tipe', 'osis')->first();
        $this->rohis = Organisasi::where('tipe', 'sub_organ')->first();

        // Get Surat Types from database
        $this->proposalOsisType = SuratType::where('kode', 'proposal_osis')->first();
        $this->proposalSubOrganType = SuratType::where('kode', 'proposal_sub_organ')->first();
        $this->suratResmiOsisType = SuratType::where('kode', 'surat_resmi_osis')->first();
    }

    /**
     * TAHAP Testing - Case 1:
     * Buat 2 surat Proposal Kegiatan dengan nama_kegiatan mirip (contoh:
     * "Lomba 17 Agustus" dan "Lomba Agustus 17"), organisasi sama, tanggal
     * berdekatan (±7 hari) → submit kedua harus DITOLAK dengan pesan duplikat.
     */
    public function test_duplicate_activity_name_and_close_dates_same_organization_is_rejected(): void
    {
        // Disable onboarding and force password middlewares to prevent redirects during test
        $this->withoutMiddleware([CheckOnboarding::class, ForceChangePassword::class]);

        $fakePdf1 = UploadedFile::fake()->create('proposal1.pdf', 100, 'application/pdf');

        // Submit first proposal
        $response1 = $this->actingAs($this->user)->post(route('surat.store'), [
            'surat_type_id' => $this->proposalOsisType->id,
            'organisasi_id' => $this->osis->id,
            'perihal'       => 'Pengajuan Proposal Lomba 17 Agustus',
            'file_pdf'      => $fakePdf1,
            'nama_kegiatan' => 'Lomba 17 Agustus',
            'tanggal_mulai' => '2026-08-15',
            'tanggal_selesai' => '2026-08-18',
            'lokasi'        => 'Aula Sekolah',
        ]);

        // First submit should succeed and redirect
        $response1->assertRedirect();
        $this->assertDatabaseHas('surats', [
            'perihal' => 'Pengajuan Proposal Lomba 17 Agustus',
        ]);

        $fakePdf2 = UploadedFile::fake()->create('proposal2.pdf', 100, 'application/pdf');

        // Submit second proposal with similar name, same organization, close date (within 7 days)
        $response2 = $this->actingAs($this->user)->post(route('surat.store'), [
            'surat_type_id' => $this->proposalOsisType->id,
            'organisasi_id' => $this->osis->id,
            'perihal'       => 'Pengajuan Proposal Lomba Agustus 17',
            'file_pdf'      => $fakePdf2,
            'nama_kegiatan' => 'Lomba Agustus 17',
            'tanggal_mulai' => '2026-08-16', // Close to 2026-08-15
            'tanggal_selesai' => '2026-08-19',
            'lokasi'        => 'Aula Sekolah',
        ]);

        // Second submit should fail validation with message in nama_kegiatan
        $response2->assertSessionHasErrors(['nama_kegiatan']);
        
        // Assert the error message contains the expected duplicate text
        $errors = session('errors')->get('nama_kegiatan');
        $this->assertStringContainsString('Terdeteksi kegiatan serupa', $errors[0]);
    }

    /**
     * TAHAP Testing - Case 2:
     * Buat 2 surat dengan lokasi sama persis, tanggal overlap, TAPI organisasi
     * BEDA → submit kedua harus DITOLAK dengan pesan konflik jadwal.
     */
    public function test_schedule_conflict_with_same_location_and_overlapping_dates_different_organization_is_rejected(): void
    {
        $this->withoutMiddleware([CheckOnboarding::class, ForceChangePassword::class]);

        $fakePdf1 = UploadedFile::fake()->create('proposal1.pdf', 100, 'application/pdf');

        // Submit first proposal by OSIS
        $response1 = $this->actingAs($this->user)->post(route('surat.store'), [
            'surat_type_id' => $this->proposalOsisType->id,
            'organisasi_id' => $this->osis->id,
            'perihal'       => 'Pengajuan Proposal Rapat OSIS',
            'file_pdf'      => $fakePdf1,
            'nama_kegiatan' => 'Rapat OSIS',
            'tanggal_mulai' => '2026-08-20',
            'tanggal_selesai' => '2026-08-22',
            'lokasi'        => 'Ruang Kelas 10',
        ]);

        $response1->assertRedirect();

        $fakePdf2 = UploadedFile::fake()->create('proposal2.pdf', 100, 'application/pdf');

        // Submit second proposal by ROHIS (different organization) at same location, overlapping dates
        $response2 = $this->actingAs($this->user)->post(route('surat.store'), [
            'surat_type_id' => $this->proposalSubOrganType->id,
            'organisasi_id' => $this->rohis->id, // ROHIS, different org
            'perihal'       => 'Pengajuan Proposal Latihan Rohis',
            'file_pdf'      => $fakePdf2,
            'nama_kegiatan' => 'Latihan Rohis',
            'tanggal_mulai' => '2026-08-21', // Overlaps with 2026-08-20 to 22
            'tanggal_selesai' => '2026-08-23',
            'lokasi'        => 'Ruang Kelas 10', // Same location
        ]);

        // Second submit should fail validation with schedule conflict on lokasi
        $response2->assertSessionHasErrors(['lokasi']);
        
        $errors = session('errors')->get('lokasi');
        $this->assertStringContainsString('sudah dipakai oleh kegiatan', $errors[0]);
    }

    /**
     * TAHAP Testing - Case 3:
     * Buat 2 surat dengan nama kegiatan/lokasi/tanggal yang jauh berbeda →
     * harus lolos normal seperti biasa.
     */
    public function test_different_proposal_details_passes_normally(): void
    {
        $this->withoutMiddleware([CheckOnboarding::class, ForceChangePassword::class]);

        $fakePdf1 = UploadedFile::fake()->create('proposal1.pdf', 100, 'application/pdf');

        // Submit first proposal (OSIS)
        $response1 = $this->actingAs($this->user)->post(route('surat.store'), [
            'surat_type_id' => $this->proposalOsisType->id,
            'organisasi_id' => $this->osis->id,
            'perihal'       => 'Pengajuan Pensi OSIS',
            'file_pdf'      => $fakePdf1,
            'nama_kegiatan' => 'Pensi OSIS',
            'tanggal_mulai' => '2026-09-01',
            'tanggal_selesai' => '2026-09-03',
            'lokasi'        => 'Lapangan Utama',
        ]);

        $response1->assertRedirect();

        $fakePdf2 = UploadedFile::fake()->create('proposal2.pdf', 100, 'application/pdf');

        // Submit second proposal (ROHIS) with totally different details
        $response2 = $this->actingAs($this->user)->post(route('surat.store'), [
            'surat_type_id' => $this->proposalSubOrganType->id,
            'organisasi_id' => $this->rohis->id,
            'perihal'       => 'Pengajuan Lomba Catur Rohis',
            'file_pdf'      => $fakePdf2,
            'nama_kegiatan' => 'Lomba Catur Rohis',
            'tanggal_mulai' => '2026-10-10',
            'tanggal_selesai' => '2026-10-12',
            'lokasi'        => 'Perpustakaan',
        ]);

        // Second submit should succeed (redirects)
        $response2->assertRedirect();
        
        $this->assertDatabaseHas('surats', [
            'perihal' => 'Pengajuan Pensi OSIS',
        ]);
        $this->assertDatabaseHas('surats', [
            'perihal' => 'Pengajuan Lomba Catur Rohis',
        ]);
    }

    /**
     * TAHAP Testing - Case 4:
     * Buat surat jenis "Surat Resmi" atau "Administrasi Organisasi" (bukan
     * Proposal Kegiatan) → pastikan TIDAK kena cek duplikat/konflik sama
     * sekali (karena requires_kegiatan_detail = false).
     */
    public function test_letters_without_kegiatan_details_bypass_duplicate_and_conflict_checks(): void
    {
        $this->withoutMiddleware([CheckOnboarding::class, ForceChangePassword::class]);

        $fakePdf1 = UploadedFile::fake()->create('surat1.pdf', 100, 'application/pdf');

        // Submit first Surat Resmi (OSIS)
        $response1 = $this->actingAs($this->user)->post(route('surat.store'), [
            'surat_type_id' => $this->suratResmiOsisType->id,
            'organisasi_id' => $this->osis->id,
            'perihal'       => 'Undangan Rapat Pleno',
            'file_pdf'      => $fakePdf1,
        ]);

        $response1->assertRedirect();

        // Follow redirect to test rendering of show page (fixes the Blade stack error)
        $showResponse = $this->get($response1->headers->get('Location'));
        $showResponse->assertStatus(200);

        $fakePdf2 = UploadedFile::fake()->create('surat2.pdf', 100, 'application/pdf');

        // Submit second Surat Resmi (OSIS) with identical/similar perihal and details
        $response2 = $this->actingAs($this->user)->post(route('surat.store'), [
            'surat_type_id' => $this->suratResmiOsisType->id,
            'organisasi_id' => $this->osis->id,
            'perihal'       => 'Undangan Rapat Pleno', // same perihal
            'file_pdf'      => $fakePdf2,
        ]);

        // Second submit should also succeed and NOT fail on duplicate/conflict validation
        $response2->assertRedirect();

        $this->assertDatabaseHas('surats', [
            'perihal' => 'Undangan Rapat Pleno',
        ]);
    }

    /**
     * TAHAP Testing - Case 5 / Bugfix:
     * Modal Approve Document (modalApprove) conditionally renders the PIN input field
     * and shows correct text based on is_signer of the waiting approval step.
     */
    public function test_approve_modal_conditionally_renders_pin_field_based_on_signer_step(): void
    {
        $this->withoutMiddleware([CheckOnboarding::class, ForceChangePassword::class]);

        $fakePdf = UploadedFile::fake()->create('proposal.pdf', 100, 'application/pdf');

        // Submit proposal
        $response = $this->actingAs($this->user)->post(route('surat.store'), [
            'surat_type_id' => $this->proposalOsisType->id,
            'organisasi_id' => $this->osis->id,
            'perihal'       => 'Proposal Kegiatan OSIS',
            'file_pdf'      => $fakePdf,
            'nama_kegiatan' => 'Pensi OSIS',
            'tanggal_mulai' => '2026-09-01',
            'tanggal_selesai' => '2026-09-03',
            'lokasi'        => 'Lapangan Utama',
        ]);

        $response->assertRedirect();
        $surat = Surat::where('perihal', 'Proposal Kegiatan OSIS')->first();

        // Initially status is pending_admin, no waiting step. Let's make it submitted and initialize approval steps.
        $surat->update(['status' => 'submitted']);
        app(\App\Services\ApprovalService::class)->initFromSuratType($surat);

        // First step: 'bph' OSIS, is_signer = true.
        $waitingStep = $surat->approvals()->where('status', 'waiting')->first();
        $this->assertTrue((bool)$waitingStep->is_signer);

        // Get the page for step 1 (is_signer = true)
        $showResponse1 = $this->actingAs($this->user)->get(route('surat.show', $surat->id));
        $showResponse1->assertStatus(200);
        $showResponse1->assertSee('Silakan masukkan 6 digit PIN Anda untuk mengonfirmasi tanda tangan digital Anda');
        $html1 = $showResponse1->getContent();
        $modalApproveHtml1 = substr($html1, strpos($html1, 'id="modalApprove"'), strpos($html1, 'id="modalReject"') - strpos($html1, 'id="modalApprove"'));
        $this->assertStringContainsString('name="pin"', $modalApproveHtml1);

        // Now update step 1 to 'approved', and set step 2 ('bph' MPK, is_signer = false) to 'waiting'.
        $surat->approvals()->where('step_order', 1)->update([
            'status' => 'approved',
            'actioned_at' => now(),
            'approver_id' => $this->user->id,
        ]);
        $surat->approvals()->where('step_order', 2)->update(['status' => 'waiting']);

        $waitingStep2 = $surat->approvals()->where('status', 'waiting')->first();
        $this->assertFalse((bool)$waitingStep2->is_signer);

        // Log in a user who can approve step 2 (e.g. BPH MPK)
        $bphMpkUser = User::where('email', 'bph.mpk1@smktelkom-sdj.sch.id')->first();

        // Get the page for step 2 (is_signer = false)
        $showResponse2 = $this->actingAs($bphMpkUser)->get(route('surat.show', $surat->id));
        $showResponse2->assertStatus(200);
        $showResponse2->assertSee('Konfirmasi persetujuan Anda untuk langkah ini.');
        $html2 = $showResponse2->getContent();
        $modalApproveHtml2 = substr($html2, strpos($html2, 'id="modalApprove"'), strpos($html2, 'id="modalReject"') - strpos($html2, 'id="modalApprove"'));
        $this->assertStringNotContainsString('name="pin"', $modalApproveHtml2);
    }

    /**
     * TAHAP Testing - Case 6 / Bugfix:
     * Index page quickApprove button click passes correct is_signer boolean flag to JS
     * so that the popup modal is properly configured.
     */
    public function test_index_page_quick_approve_button_passes_correct_is_signer_flag(): void
    {
        $this->withoutMiddleware([CheckOnboarding::class, ForceChangePassword::class]);

        $fakePdf = UploadedFile::fake()->create('proposal.pdf', 100, 'application/pdf');

        // Submit proposal
        $response = $this->actingAs($this->user)->post(route('surat.store'), [
            'surat_type_id' => $this->proposalOsisType->id,
            'organisasi_id' => $this->osis->id,
            'perihal'       => 'Proposal Kegiatan OSIS',
            'file_pdf'      => $fakePdf,
            'nama_kegiatan' => 'Pensi OSIS',
            'tanggal_mulai' => '2026-09-01',
            'tanggal_selesai' => '2026-09-03',
            'lokasi'        => 'Lapangan Utama',
        ]);

        $response->assertRedirect();
        $surat = Surat::where('perihal', 'Proposal Kegiatan OSIS')->first();

        // Initially status is pending_admin, no waiting step. Let's make it submitted and initialize approval steps.
        $surat->update(['status' => 'submitted']);
        app(\App\Services\ApprovalService::class)->initFromSuratType($surat);

        // First step: 'bph' OSIS, is_signer = true.
        $indexResponse1 = $this->actingAs($this->user)->get(route('surat.index'));
        $indexResponse1->assertStatus(200);
        // Assert that the Approve button passes true:
        $indexResponse1->assertSee("quickApprove('" . route('surat.approve', $surat->id) . "', true)", false);

        // Now update step 1 to 'approved', and set step 2 ('bph' MPK, is_signer = false) to 'waiting'.
        $surat->approvals()->where('step_order', 1)->update([
            'status' => 'approved',
            'actioned_at' => now(),
            'approver_id' => $this->user->id,
        ]);
        $surat->approvals()->where('step_order', 2)->update(['status' => 'waiting']);

        // Log in a user who can approve step 2 (e.g. BPH MPK)
        $bphMpkUser = User::where('email', 'bph.mpk1@smktelkom-sdj.sch.id')->first();

        $indexResponse2 = $this->actingAs($bphMpkUser)->get(route('surat.index'));
        $indexResponse2->assertStatus(200);
        // Assert that the Approve button passes false:
        $indexResponse2->assertSee("quickApprove('" . route('surat.approve', $surat->id) . "', false)", false);
    }

    /**
     * TAHAP Testing - Case 7 / Bugfix:
     * User signature preview route (ttd.preview.user) works and resolves correctly
     * when ttd_path is stored in the users table, even if the user has no UserProfile.
     */
    public function test_ttd_preview_resolves_correctly_when_only_user_ttd_path_exists(): void
    {
        $this->withoutMiddleware([CheckOnboarding::class, ForceChangePassword::class]);

        // Create a fake signature file on disk
        $filename = 'ttd/' . $this->user->id . '.png';
        \Illuminate\Support\Facades\Storage::disk('local')->put('private/' . $filename, 'fake image data');

        // Set the path on the User model
        $this->user->update(['ttd_path' => $filename]);

        // Make sure no profile exists or delete ttd_path/signature_path from it
        if ($this->user->profile) {
            $this->user->profile->update([
                'ttd_path' => null,
                'signature_path' => null,
            ]);
        }

        // Request preview
        $response = $this->actingAs($this->user)->get(route('ttd.preview.user', $this->user->id));
        
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'image/png');

        // Cleanup
        \Illuminate\Support\Facades\Storage::disk('local')->delete('private/' . $filename);
    }

    /**
     * TAHAP Testing - Case 8 / Feature:
     * test_surat_turunan_generation_and_signing_flow
     * Verifies that the created create.blade.php and index.blade.php views work
     * for generating and signing child letters.
     */
    public function test_surat_turunan_generation_and_signing_flow(): void
    {
        $this->withoutMiddleware([CheckOnboarding::class, ForceChangePassword::class]);

        $fakePdf = UploadedFile::fake()->create('proposal.pdf', 100, 'application/pdf');

        // Submit proposal
        $response = $this->actingAs($this->user)->post(route('surat.store'), [
            'surat_type_id' => $this->proposalOsisType->id,
            'organisasi_id' => $this->osis->id,
            'perihal'       => 'Proposal OSIS Induk',
            'file_pdf'      => $fakePdf,
            'nama_kegiatan' => 'Pensi OSIS',
            'tanggal_mulai' => '2026-09-01',
            'tanggal_selesai' => '2026-09-03',
            'lokasi'        => 'Lapangan Utama',
        ]);

        $response->assertRedirect();
        $surat = Surat::where('perihal', 'Proposal OSIS Induk')->first();

        // 1. Move status of Surat to approved_owner so turunan can be managed
        $surat->update(['status' => 'approved_owner']);

        // 2. Visit the create page
        $createResponse = $this->actingAs($this->user)->get(route('surat.turunan.create', $surat->id));
        $createResponse->assertStatus(200);
        $createResponse->assertSee('Buat Surat Turunan');
        $createResponse->assertSee('undangan');
        $createResponse->assertSee('izin_kegiatan');

        // 3. Store child letters
        $storeResponse = $this->actingAs($this->user)->post(route('surat.turunan.store', $surat->id), [
            'templates' => ['undangan', 'izin_kegiatan'],
            'signers' => [
                'undangan' => ['ketua_pelaksana', 'pembina'],
                'izin_kegiatan' => ['ketua_pelaksana'],
            ]
        ]);

        $storeResponse->assertRedirect(route('surat.turunan.index', $surat->id));

        // Assert they are created in DB
        $this->assertDatabaseHas('surat_turunans', [
            'surat_id' => $surat->id,
            'status' => 'menunggu_ttd',
        ]);

        // 4. Visit the index page
        $indexResponse = $this->actingAs($this->user)->get(route('surat.turunan.index', $surat->id));
        $indexResponse->assertStatus(200);
        $indexResponse->assertSee('Surat Undangan');
        $indexResponse->assertSee('Surat Izin Kegiatan');

        // 5. Sign the letter
        // Set PIN and signature path on Ketua OSIS so signing doesn't fail
        $ketuaOsis = User::where('email', 'ketua.osis@smktelkom-sdj.sch.id')->first();
        $this->assertNotNull($ketuaOsis);
        
        $ketuaOsis->update([
            'pin' => bcrypt('123456'),
            'ttd_path' => 'ttd/test_user.png',
        ]);
        \Illuminate\Support\Facades\Storage::disk('local')->put('private/ttd/test_user.png', 'fake ttd data');

        $suratTurunan = $surat->suratTurunans()->first();
        $signer = $suratTurunan->signers()->where('user_id', $ketuaOsis->id)->first();
        $this->assertNotNull($signer);

        $signResponse = $this->actingAs($ketuaOsis)->post(route('surat.turunan.sign', [$surat->id, $suratTurunan->id, $signer->id]), [
            'pin' => '123456',
        ]);

        $signResponse->assertRedirect(route('surat.turunan.index', $surat->id));
        
        // Assert signer state is now signed
        $this->assertEquals('signed', $signer->fresh()->status);

        // Cleanup
        \Illuminate\Support\Facades\Storage::disk('local')->delete('private/ttd/test_user.png');
    }

    /**
     * TAHAP 3 Testing: test_disposisi_akhir_flow
     */
    public function test_disposisi_akhir_flow(): void
    {
        $this->withoutMiddleware([CheckOnboarding::class, ForceChangePassword::class]);

        $fakePdf = UploadedFile::fake()->create('proposal.pdf', 100, 'application/pdf');

        // Submit proposal
        $response = $this->actingAs($this->user)->post(route('surat.store'), [
            'surat_type_id' => $this->proposalOsisType->id,
            'organisasi_id' => $this->osis->id,
            'perihal'       => 'Proposal Kegiatan Untuk Disposisi',
            'file_pdf'      => $fakePdf,
            'nama_kegiatan' => 'Pensi Akhir Tahun',
            'tanggal_mulai' => '2026-09-10',
            'tanggal_selesai' => '2026-09-12',
            'lokasi'        => 'Gedung Olahraga',
        ]);

        $response->assertRedirect();
        $surat = Surat::where('perihal', 'Proposal Kegiatan Untuk Disposisi')->first();
        $this->assertNotNull($surat);

        // Simulate final approval
        $this->user->update([
            'pin' => bcrypt('123456'),
            'ttd_path' => 'ttd/test_user.png',
        ]);
        \Illuminate\Support\Facades\Storage::disk('local')->put('private/ttd/test_user.png', 'fake ttd data');

        $approval = $surat->waitingStep();
        if ($approval) {
            $approvalResult = app(\App\Services\ApprovalService::class)->approve(
                'surat_' . $surat->jenis_surat,
                $surat->id,
                $this->user,
                'Disetujui',
                'ttd/test_user.png'
            );
        }

        // Simulating the controller final approval update
        $surat->update(['status' => 'approved_owner']);
        if ($surat->suratType && $surat->suratType->requires_kegiatan_detail) {
            $surat->update([
                'pic_user_id' => $surat->user_id,
                'status_pelaksanaan' => 'belum_mulai',
            ]);
        }

        // Verify auto-trigger values
        $this->assertEquals($surat->user_id, $surat->fresh()->pic_user_id);
        $this->assertEquals('belum_mulai', $surat->fresh()->status_pelaksanaan);

        // Log in as Admin to access disposisi-akhir
        $adminUser = User::where('email', 'admin@smktelkom-sdj.sch.id')->first();
        $this->assertNotNull($adminUser);

        // Get disposisi-akhir index page
        $disposisiResponse = $this->actingAs($adminUser)->get(route('disposisi-akhir.index'));
        $disposisiResponse->assertStatus(200);
        $disposisiResponse->assertSee('Pensi Akhir Tahun');

        // Reassign PIC to another member of the OSIS organization
        $ketuaOsis = User::where('email', 'ketua.osis@smktelkom-sdj.sch.id')->first();
        $this->assertNotNull($ketuaOsis);

        $assignResponse = $this->actingAs($adminUser)->post(route('disposisi-akhir.assign', $surat->id), [
            'pic_user_id' => $ketuaOsis->id,
        ]);

        $assignResponse->assertRedirect();
        $this->assertEquals($ketuaOsis->id, $surat->fresh()->pic_user_id);

        // Cleanup
        \Illuminate\Support\Facades\Storage::disk('local')->delete('private/ttd/test_user.png');
    }

    /**
     * TAHAP 4 Testing: test_pic_monitoring_and_selesai_flow
     */
    public function test_pic_monitoring_and_selesai_flow(): void
    {
        $this->withoutMiddleware([CheckOnboarding::class, ForceChangePassword::class]);

        $fakePdf = UploadedFile::fake()->create('proposal.pdf', 100, 'application/pdf');

        // Submit proposal
        $response = $this->actingAs($this->user)->post(route('surat.store'), [
            'surat_type_id' => $this->proposalOsisType->id,
            'organisasi_id' => $this->osis->id,
            'perihal'       => 'Proposal Kegiatan Untuk Monitoring',
            'file_pdf'      => $fakePdf,
            'nama_kegiatan' => 'Pensi Penting',
            'tanggal_mulai' => '2026-09-20',
            'tanggal_selesai' => '2026-09-22',
            'lokasi'        => 'Gedung Olahraga',
        ]);

        $response->assertRedirect();
        $surat = Surat::where('perihal', 'Proposal Kegiatan Untuk Monitoring')->first();
        $this->assertNotNull($surat);

        // Disposisi PIC to $this->user (BPH OSIS) and status approved_owner
        $surat->update([
            'status' => 'approved_owner',
            'pic_user_id' => $this->user->id,
            'status_pelaksanaan' => 'belum_mulai'
        ]);

        // 1. Visit Pelaksanaan Saya page
        $monResponse = $this->actingAs($this->user)->get(route('pelaksanaan.index'));
        $monResponse->assertStatus(200);
        $monResponse->assertSee('Pensi Penting');

        // 2. Submit progress update (e.g. 50%)
        $progResponse = $this->actingAs($this->user)->post(route('pelaksanaan.progress', $surat->id), [
            'persentase' => 50,
            'catatan' => 'Persiapan panggung dan sound system sudah berjalan setengah.'
        ]);

        $progResponse->assertRedirect();
        $this->assertDatabaseHas('progress_updates', [
            'surat_id' => $surat->id,
            'persentase' => 50,
            'user_id' => $this->user->id
        ]);
        $this->assertEquals('berjalan', $surat->fresh()->status_pelaksanaan);

        // 3. Mark as selesai
        $doneResponse = $this->actingAs($this->user)->post(route('pelaksanaan.selesai', $surat->id), [
            'catatan_penutup' => 'Acara sukses dilaksanakan, dihadiri oleh seluruh siswa.'
        ]);

        $doneResponse->assertRedirect(route('pelaksanaan.index'));
        $this->assertEquals('selesai', $surat->fresh()->status_pelaksanaan);

        // Assert LPJ draft is created
        $this->assertDatabaseHas('laporan_pertanggungjawabans', [
            'surat_id' => $surat->id,
            'status' => 'draft',
            'ringkasan_kegiatan' => 'Acara sukses dilaksanakan, dihadiri oleh seluruh siswa.'
        ]);

        // Assert notification is sent
        $this->assertDatabaseHas('notifications', [
            'user_id' => $this->user->id,
            'title' => 'LPJ Perlu Diisi'
        ]);
    }

    /**
     * TAHAP 5 Testing: test_lpj_form_submission_flow
     */
    public function test_lpj_form_submission_flow(): void
    {
        $this->withoutMiddleware([CheckOnboarding::class, ForceChangePassword::class]);

        $fakePdf = UploadedFile::fake()->create('proposal.pdf', 100, 'application/pdf');

        // Submit proposal
        $response = $this->actingAs($this->user)->post(route('surat.store'), [
            'surat_type_id' => $this->proposalOsisType->id,
            'organisasi_id' => $this->osis->id,
            'perihal'       => 'Proposal Kegiatan Untuk LPJ',
            'file_pdf'      => $fakePdf,
            'nama_kegiatan' => 'Pensi LPJ',
            'tanggal_mulai' => '2026-09-25',
            'tanggal_selesai' => '2026-09-27',
            'lokasi'        => 'Gedung Serbaguna',
        ]);

        $response->assertRedirect();
        $surat = Surat::where('perihal', 'Proposal Kegiatan Untuk LPJ')->first();
        $this->assertNotNull($surat);

        // Disposisi PIC to $this->user (BPH OSIS), status approved_owner, status_pelaksanaan selesai
        $surat->update([
            'status' => 'approved_owner',
            'pic_user_id' => $this->user->id,
            'status_pelaksanaan' => 'selesai'
        ]);

        // Auto-create draft LPJ
        $lpj = \App\Models\LaporanPertanggungjawaban::create([
            'surat_id' => $surat->id,
            'ringkasan_kegiatan' => 'Draft ringkasan awal.',
            'status' => 'draft'
        ]);

        // 1. Visit LPJ Create page
        $lpjCreateRes = $this->actingAs($this->user)->get(route('lpj.create', $surat->id));
        $lpjCreateRes->assertStatus(200);
        $lpjCreateRes->assertSee('Isi Laporan Pertanggungjawaban');

        // 2. Submit LPJ
        $fakeImg = UploadedFile::fake()->image('dokumentasi.jpg');

        $submitRes = $this->actingAs($this->user)->post(route('lpj.store', $surat->id), [
            'ringkasan_kegiatan' => 'Berikut adalah pertanggungjawaban lengkap kegiatan OSIS.',
            'realisasi_anggaran' => [
                ['item' => 'Sewa Sound System', 'jumlah' => 2000000],
                ['item' => 'Konsumsi Panitia', 'jumlah' => 750000]
            ],
            'lampirans' => [
                [
                    'file' => $fakeImg,
                    'tipe' => 'foto',
                    'keterangan' => 'Foto pembukaan acara'
                ]
            ]
        ]);

        $submitRes->assertRedirect(route('pelaksanaan.index'));
        $this->assertEquals('submitted', $lpj->fresh()->status);

        // Verify anggaran stored correctly as JSON array
        $this->assertCount(2, $lpj->fresh()->realisasi_anggaran);
        $this->assertEquals('Sewa Sound System', $lpj->fresh()->realisasi_anggaran[0]['item']);

        // Verify lampiran record is created in DB
        $this->assertDatabaseHas('lpj_lampirans', [
            'lpj_id' => $lpj->id,
            'tipe' => 'foto',
            'keterangan' => 'Foto pembukaan acara'
        ]);
    }

    /**
     * TAHAP 6 Testing: test_lpj_verification_flow
     */
    public function test_lpj_verification_flow(): void
    {
        $this->withoutExceptionHandling();
        $this->withoutMiddleware([CheckOnboarding::class, ForceChangePassword::class]);

        $fakePdf = UploadedFile::fake()->create('proposal.pdf', 100, 'application/pdf');

        // Submit proposal
        $response = $this->actingAs($this->user)->post(route('surat.store'), [
            'surat_type_id' => $this->proposalOsisType->id,
            'organisasi_id' => $this->osis->id,
            'perihal'       => 'Proposal Kegiatan Untuk Verifikasi LPJ',
            'file_pdf'      => $fakePdf,
            'nama_kegiatan' => 'Pensi Verifikasi',
            'tanggal_mulai' => '2026-10-01',
            'tanggal_selesai' => '2026-10-03',
            'lokasi'        => 'Gedung Serbaguna',
        ]);

        $response->assertRedirect();
        $surat = Surat::where('perihal', 'Proposal Kegiatan Untuk Verifikasi LPJ')->first();
        $this->assertNotNull($surat);

        // Disposisi PIC to $this->user (BPH OSIS), status approved_owner, status_pelaksanaan selesai
        $surat->update([
            'status' => 'approved_owner',
            'pic_user_id' => $this->user->id,
            'status_pelaksanaan' => 'selesai'
        ]);

        // Auto-create submitted LPJ
        $lpj = \App\Models\LaporanPertanggungjawaban::create([
            'surat_id' => $surat->id,
            'ringkasan_kegiatan' => 'Berikut adalah pertanggungjawaban lengkap kegiatan OSIS.',
            'realisasi_anggaran' => [['item' => 'Konsumsi', 'jumlah' => 500000]],
            'status' => 'submitted'
        ]);

        // Pembina OSIS
        $pembina = User::where('email', 'pembina.osis@smktelkom-sdj.sch.id')->first();
        $this->assertNotNull($pembina);

        // 1. Visit LPJ Verifikasi Index page as Pembina
        $indexVerifyRes = $this->actingAs($pembina)->get(route('lpj.verifikasi.index'));
        $indexVerifyRes->assertStatus(200);
        $indexVerifyRes->assertSee('Pensi Verifikasi');

        // 2. Reject LPJ (Revisi)
        $rejectRes = $this->actingAs($pembina)->post(route('lpj.verify', $surat->id), [
            'action' => 'reject',
            'catatan_revisi' => 'Laporan kurang detail, mohon tambahkan foto pendukung.'
        ]);

        $rejectRes->assertRedirect(route('lpj.verifikasi.index'));
        $this->assertEquals('revisi', $lpj->fresh()->status);
        $this->assertEquals('Laporan kurang detail, mohon tambahkan foto pendukung.', $lpj->fresh()->catatan_revisi);

        // 3. Resubmit LPJ as PIC
        $lpj->refresh();
        $lpj->update(['status' => 'submitted']);

        // 4. Approve LPJ as Pembina (Valid with PIN)
        $pembina->update([
            'pin' => bcrypt('123456'),
            'ttd_path' => 'ttd/pembina_user.png',
        ]);
        \Illuminate\Support\Facades\Storage::disk('local')->put('private/ttd/pembina_user.png', 'fake ttd data');

        $approveRes = $this->actingAs($pembina)->post(route('lpj.verify', $surat->id), [
            'action' => 'approve',
            'pin' => '123456'
        ]);

        $approveRes->assertRedirect(route('lpj.verifikasi.index'));
        $this->assertEquals('valid', $lpj->fresh()->status);
        $this->assertEquals($pembina->id, $lpj->fresh()->verified_by);
        $this->assertNotNull($lpj->fresh()->verified_at);
        $this->assertNotNull($lpj->fresh()->archived_at);
        $this->assertNotNull($lpj->fresh()->keywords);

        // Cleanup
        \Illuminate\Support\Facades\Storage::disk('local')->delete('private/ttd/pembina_user.png');
    }

    /**
     * TAHAP 7 Testing: test_database_arsip_search_and_filter_flow
     */
    public function test_database_arsip_search_and_filter_flow(): void
    {
        $this->withoutMiddleware([CheckOnboarding::class, ForceChangePassword::class]);

        // Create a valid LPJ for searching
        $fakePdf = UploadedFile::fake()->create('proposal.pdf', 100, 'application/pdf');

        $surat = Surat::create([
            'user_id' => $this->user->id,
            'surat_type_id' => $this->proposalOsisType->id,
            'jenis_surat' => $this->proposalOsisType->kode,
            'file_pdf' => 'surat/test.pdf',
            'organisasi_id' => $this->osis->id,
            'perihal' => 'Proposal Pensi Musik Akhir Tahun',
            'status' => 'approved_owner',
            'pic_user_id' => $this->user->id,
            'status_pelaksanaan' => 'selesai'
        ]);

        $lpj = \App\Models\LaporanPertanggungjawaban::create([
            'surat_id' => $surat->id,
            'ringkasan_kegiatan' => 'Konser musik spektakuler diselenggarakan oleh OSIS.',
            'status' => 'valid',
            'verified_by' => $this->user->id,
            'verified_at' => now(),
            'archived_at' => now(),
            'keywords' => 'Pensi Musik Konser Musik Spektakuler OSIS'
        ]);

        // 1. Visit archive page
        $archiveResponse = $this->actingAs($this->user)->get(route('arsip.index'));
        $archiveResponse->assertStatus(200);
        $archiveResponse->assertSee('Konser musik spektakuler');

        // 2. Perform search with query
        $searchRes = $this->actingAs($this->user)->get(route('arsip.index', ['q' => 'spektakuler']));
        $searchRes->assertStatus(200);
        $searchRes->assertSee('Konser musik spektakuler');

        // 3. Search with non-existent query
        $searchEmpty = $this->actingAs($this->user)->get(route('arsip.index', ['q' => 'futsal']));
        $searchEmpty->assertStatus(200);
        $searchEmpty->assertSee('Arsip Tidak Ditemukan');

        // 4. Filter by organisasi
        $filterOrg = $this->actingAs($this->user)->get(route('arsip.index', ['organisasi_id' => $this->osis->id]));
        $filterOrg->assertStatus(200);
        $filterOrg->assertSee('Konser musik spektakuler');

        // 5. Filter by organisasi that has no LPJs
        $filterEmptyOrg = $this->actingAs($this->user)->get(route('arsip.index', ['organisasi_id' => $this->rohis->id]));
        $filterEmptyOrg->assertStatus(200);
        $filterEmptyOrg->assertSee('Arsip Tidak Ditemukan');

        // 6. Filter by year
        $filterYear = $this->actingAs($this->user)->get(route('arsip.index', ['tahun' => now()->year]));
        $filterYear->assertStatus(200);
        $filterYear->assertSee('Konser musik spektakuler');
    }
}
