<?php

namespace App\Http\Controllers;

use App\Models\DocumentApproval;
use App\Models\Organisasi;
use App\Models\OrganisasiMember;
use App\Models\Surat;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Dashboard utama SIMORA.
     * Menampilkan data berbeda berdasarkan role user.
     */
    public function index()
    {
        $user = auth()->user();

        $kegiatanBerjalanCount = Surat::where('status_pelaksanaan', 'berjalan')->count();
        $lpjPendingCount = \App\Models\LaporanPertanggungjawaban::where('status', 'submitted')->count();
        $lpjRevisiCount = \App\Models\LaporanPertanggungjawaban::where('status', 'revisi')->count();

        $data = [
            'userRoleName'    => match(true) {
                $user->hasRole('admin')          => 'Admin',
                $user->hasRole('super-admin')    => 'Super Admin',
                $user->hasRole('kepala_sekolah') => 'Kepala Sekolah',
                $user->hasRole('pengawas_pusat') => 'Pengawas Pusat',
                $user->hasRole('guru')           => 'Guru',
                $user->hasRole('anggota')        => 'Pengurus',
                default                          => 'Pengguna',
            },
            'userDisplayName' => 'Selamat datang kembali',
            'kegiatanBerjalanCount' => $kegiatanBerjalanCount,
            'lpjPendingCount'       => $lpjPendingCount,
            'lpjRevisiCount'        => $lpjRevisiCount,
        ];

        // ── Admin / Super Admin: statistik sistem ───────────────────────
        if ($user->hasAnyRole(['admin', 'super-admin'])) {
            $data = array_merge($data, [
                'totalPengurus'       => User::where('status', 'aktif')->count(),
                'totalOrganisasi'     => Organisasi::where('is_active', true)->count(),
                'suratMenungguCount'  => DocumentApproval::where('status', 'waiting')
                                            ->where('document_type', 'LIKE', 'surat_%')
                                            ->count(),
                'suratSelesaiHariIni' => Surat::where('status', 'approved_owner')
                                            ->whereDate('updated_at', now()->format('Y-m-d'))
                                            ->count(),
                'recentActivities'    => \App\Models\ActivityLog::with('user')
                                            ->orderBy('created_at', 'desc')
                                            ->take(5)
                                            ->get(),
            ]);
        }

        // ── Pengawas Pusat / Kepala Sekolah: surat yang menunggu approval global ──
        elseif ($user->hasAnyRole(['pengawas_pusat', 'kepala_sekolah'])) {
            $roleName = $user->hasRole('pengawas_pusat') ? 'pengawas_pusat' : 'kepala_sekolah';
            $data = array_merge($data, [
                'suratMenungguCount' => DocumentApproval::where('status', 'waiting')
                                            ->where('target_mode', 'global')
                                            ->where('jabatan', $roleName)
                                            ->count(),
                'suratMenungguList'  => Surat::whereHas('approvals', function($q) use ($roleName) {
                                                $q->where('target_mode', 'global')
                                                  ->where('jabatan', $roleName)
                                                  ->where('status', 'waiting');
                                            })
                                            ->with(['user', 'organisasi'])
                                            ->orderBy('created_at', 'desc')
                                            ->take(5)
                                            ->get(),
            ]);
        }

        // ── Guru / Anggota: monitoring berdasarkan OrganisasiMember ──────
        else {
            $organisasiIds = OrganisasiMember::where('user_id', $user->id)
                ->pluck('organisasi_id')
                ->toArray();

            // Surat yang user bisa approve (step waiting yang sesuai)
            $suratMenunggu = collect();
            if (!empty($organisasiIds)) {
                $suratMenunggu = Surat::whereIn('organisasi_id', $organisasiIds)
                    ->whereHas('approvals', fn($q) => $q->where('status', 'waiting'))
                    ->with(['user', 'organisasi', 'suratType'])
                    ->orderBy('created_at', 'desc')
                    ->take(5)
                    ->get();
            }

            $suratStaff = Surat::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();

            $data = array_merge($data, [
                'suratMenungguList'      => $suratMenunggu,
                'suratMenungguCount'     => $suratMenunggu->count(),
                'suratStaff'             => $suratStaff->take(10),
                'suratStaffDiajukan'     => $suratStaff->where('status', 'submitted')->count(),
                'suratStaffSelesai'      => $suratStaff->where('status', 'approved_owner')->count(),
                'suratStaffRevisiCount'  => $suratStaff->where('status', 'revised')->count(),
                'myOrganisasi'           => OrganisasiMember::where('user_id', $user->id)->with('organisasi')->get(),
            ]);
        }

        return view('dashboard.home', $data);
    }

    /**
     * Halaman full activity log dengan filter & paginasi.
     * Hanya Admin yang bisa mengakses.
     */
    public function activityLog(Request $request)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'super-admin'])) {
            abort(403);
        }

        $query = \App\Models\ActivityLog::with('user')->orderBy('created_at', 'desc');

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('action')) {
            $query->where('action', 'like', '%' . $request->action . '%');
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        if ($request->filled('search')) {
            $query->where('description', 'like', '%' . $request->search . '%');
        }

        $logs      = $query->paginate(20)->withQueryString();
        $users     = \App\Models\User::orderBy('name')->get(['id', 'name']);
        $actions   = \App\Models\ActivityLog::select('action')->distinct()->pluck('action');
        $totalLogs = \App\Models\ActivityLog::count();

        return view('dashboard.activity-log', compact('logs', 'users', 'actions', 'totalLogs'));
    }
}
