@extends('layouts.master')

@section('content')
<style>
  *, *::before, *::after { box-sizing: border-box; }

  /* GREETING */
  .hv-welcome {
    font-family: 'Poppins', sans-serif;
    font-size: 36px;
    font-weight: 400;
    color: var(--color-text);
    margin: 0 0 32px 0;
    letter-spacing: -0.5px;
  }

  /* GRID */
  .hv-row1 {
    display: grid;
    grid-template-columns: 260px 1fr 1fr 320px;
    gap: 24px;
    margin-bottom: 24px;
  }

  @media (max-width: 1200px) {
    .hv-row1 {
      grid-template-columns: 1fr;
      gap: 16px;
    }
  }

  /* FOTO */
  .hv-photo-card {
    border-radius: var(--radius-card);
    overflow: hidden;
    position: relative;
    min-height: 220px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
    border: none;
  }
  .hv-photo-card img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
  .hv-photo-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 24px;
    background: linear-gradient(to top, rgba(17,17,17,0.9), rgba(17,17,17,0.4));
  }
  .hv-photo-name {
    font-family: 'Poppins', sans-serif;
    font-size: 16px;
    font-weight: 600;
    color: #fff;
    margin: 0 0 4px 0;
  }
  .hv-photo-role {
    font-size: 12px;
    color: rgba(255,255,255,0.8);
    margin: 0;
  }

  /* CARD GLOBAL */
  .hv-actions-card,
  .hv-full-card {
    background: var(--color-surface);
    border-radius: var(--radius-card);
    padding: 24px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.03);
    border: none;
  }

  /* STAT */
  .hv-stat {
    background: var(--color-bg-light);
    border-radius: var(--radius-card);
    padding: 24px;
    border: none;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    min-height: 200px;
  }
  .hv-stat.dark {
    background: var(--color-primary);
    color: white;
  }
  .hv-stat.dark .hv-stat-label,
  .hv-stat.dark .hv-stat-num {
    color: white;
  }
  .hv-stat-label {
    font-size: 13px;
    color: var(--color-text-muted);
  }
  .hv-stat-num {
    font-family: 'Poppins', sans-serif;
    font-size: 56px;
    font-weight: 600;
    color: var(--color-text);
    margin: 16px 0;
    line-height: 1.2;
  }
  .hv-stat-bottom {
    display: flex;
    align-items: flex-end;
    justify-content: flex-start;
    margin-top: auto;
    gap: 12px;
  }
  .hv-stat-icon {
    width: 32px;
    height: 32px;
    color: var(--color-text-muted);
    flex-shrink: 0;
  }
  .hv-stat.dark .hv-stat-icon {
    color: rgba(255,255,255,0.7);
  }

  /* DARK CARD */
  .hv-recent {
    background: var(--color-surface);
    border-radius: var(--radius-card);
    padding: 24px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.03);
    border: none;
    display: flex;
    flex-direction: column;
  }
  .hv-recent-list {
    flex: 1;
    max-height: 340px;
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: rgba(255,255,255,0.2) transparent;
    padding-right: 4px;
  }
  .hv-recent-list::-webkit-scrollbar {
    width: 4px;
  }
  .hv-recent-list::-webkit-scrollbar-thumb {
    background: rgba(255,255,255,0.2);
    border-radius: 999px;
  }
  .hv-recent-list::-webkit-scrollbar-track {
    background: transparent;
  }
  .hv-recent-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
  }
  .hv-recent-title {
    font-family: 'Poppins', sans-serif;
    font-size: 20px;
    font-weight: 600;
    color: var(--color-text);
    margin: 0;
  }
  .hv-recent-viewall {
    font-size: 12px;
    color: var(--color-primary);
    text-decoration: none;
    transition: color 0.2s;
  }
  .hv-recent-viewall:hover {
    color: var(--color-primary-dark);
  }
  .hv-recent-sub {
    font-size: 11px;
    color: var(--color-text-muted);
    margin-bottom: 16px;
  }
  .hv-recent-item {
    display: flex;
    gap: 12px;
    margin-bottom: 14px;
  }
  .hv-recent-ava {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: var(--color-bg-light);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--color-text);
    font-size: 14px;
    font-weight: bold;
  }
  .hv-recent-name {
    font-size: 12px;
    color: var(--color-text);
  }
  .hv-recent-desc {
    font-size: 11px;
    color: var(--color-text-muted);
  }

  /* ACTION */
  .hv-actions-title {
    font-family: 'Poppins', sans-serif;
    font-size: 18px;
    margin-bottom: 16px;
    color: var(--color-text);
  }
  .hv-actions-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
  }

  /* BUTTON */
  .hv-btn-primary {
    width: 100%;
    padding: 12px;
    background: var(--color-primary);
    color: #fff !important;
    border-radius: var(--radius-pill);
    font-size: 14px;
    font-weight: 600;
    transition: 0.2s;
    text-align: center;
    display: inline-block;
  }
  .hv-btn-primary:hover {
    background: var(--color-primary-dark);
  }
  .hv-btn-outline {
    width: 100%;
    padding: 11px;
    border: 1px solid var(--color-border);
    border-radius: var(--radius-pill);
    text-align: center;
    display: inline-block;
    transition: 0.2s;
    color: var(--color-text-muted);
  }
  .hv-btn-outline:hover {
    background: var(--color-bg-light);
  }

  .hv-empty {
    text-align: center;
    color: var(--color-text-muted);
    font-size: 12px;
    padding: 24px 0;
  }

  .hv-recent-empty {
    text-align: center;
    color: var(--color-text-muted);
    font-size: 12px;
    padding: 24px 0;
  }

  .hv-list-link {
    text-decoration: none;
    color: var(--color-text);
    font-weight: 500;
    transition: color 0.2s;
  }

  .hv-list-link:hover {
    color: var(--color-primary);
  }
</style>

<div>

<div class="mb-8">
    <h1 class="text-3xl font-sans font-bold text-[#111111]">{{ $userDisplayName ?? 'Selamat datang kembali' }}, {{ auth()->user()->name }}</h1>
    <p class="text-[13px] font-light text-[#6B7280] mt-1">Sistem Surat Organisasi SIMORA SMK Telkom Sidoarjo</p>
</div>

{{-- Statistik Pelaksanaan & LPJ Kegiatan --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    {{-- Kegiatan Berjalan --}}
    <div class="bg-white rounded-[28px] p-6 border border-gray-100 shadow-[0_10px_40px_rgba(0,0,0,0.06)] flex items-center justify-between">
        <div>
            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Kegiatan Berjalan</span>
            <span class="text-3xl font-sans font-bold text-[#111111] mt-2 block">{{ $kegiatanBerjalanCount }}</span>
            <a href="{{ route('pelaksanaan.index') }}" class="text-[11px] text-[#E62129] font-semibold hover:underline mt-2 inline-block">Monitor Pelaksanaan</a>
        </div>
        <div class="w-12 h-12 bg-[#F5F5F7] rounded-[28px] flex items-center justify-center text-[#E62129]">
            <i data-lucide="play-circle" class="w-6 h-6"></i>
        </div>
    </div>

    {{-- LPJ Menunggu Verifikasi --}}
    <div class="bg-white rounded-[28px] p-6 border border-gray-100 shadow-[0_10px_40px_rgba(0,0,0,0.06)] flex items-center justify-between">
        <div>
            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">LPJ Menunggu Verifikasi</span>
            <span class="text-3xl font-sans font-bold text-[#111111] mt-2 block">{{ $lpjPendingCount }}</span>
            @if(auth()->user()->hasAnyRole(['admin', 'super-admin', 'guru']))
                <a href="{{ route('lpj.verifikasi.index') }}" class="text-[11px] text-[#E62129] font-semibold hover:underline mt-2 inline-block">Verifikasi Sekarang</a>
            @else
                <span class="text-[11px] text-gray-400 mt-2 inline-block">Menunggu Review Pembina</span>
            @endif
        </div>
        <div class="w-12 h-12 bg-[#F5F5F7] rounded-[28px] flex items-center justify-center text-[#E62129]">
            <i data-lucide="clock" class="w-6 h-6"></i>
        </div>
    </div>

    {{-- LPJ Revisi --}}
    <div class="bg-white rounded-[28px] p-6 border border-gray-100 shadow-[0_10px_40px_rgba(0,0,0,0.06)] flex items-center justify-between">
        <div>
            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">LPJ Perlu Revisi</span>
            <span class="text-3xl font-sans font-bold text-[#111111] mt-2 block">{{ $lpjRevisiCount }}</span>
            <a href="{{ route('pelaksanaan.index') }}" class="text-[11px] text-[#E62129] font-semibold hover:underline mt-2 inline-block">Lihat Pelaksanaan Saya</a>
        </div>
        <div class="w-12 h-12 bg-[#F5F5F7] rounded-[28px] flex items-center justify-center text-[#E62129]">
            <i data-lucide="alert-triangle" class="w-6 h-6"></i>
        </div>
    </div>
</div>

{{-- ══════ admin & super-admin ══════ --}}
@if(auth()->user()->hasAnyRole(['admin', 'super-admin']))

    <div class="hv-row1">

        {{-- foto profil --}}
        <div class="hv-photo-card">
            @if(auth()->user()->avatar)
                <img src="{{ URL::to('assets/images/user/'.auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}">
            @else
                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=FFF1F2&color=111111&size=200" alt="{{ auth()->user()->name }}">
            @endif
            <div class="hv-photo-overlay">
                <p class="hv-photo-name">{{ auth()->user()->name }}</p>
                <p class="hv-photo-role">{{ $userRoleName }}</p>
            </div>
        </div>

        {{-- total pengurus --}}
        <div class="hv-stat">
            <p class="hv-stat-label">Total Pengurus Aktif</p>
            <p class="hv-stat-num">{{ $totalPengurus ?? 0 }}</p>
            <div class="hv-stat-bottom">
                <svg class="hv-stat-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
        </div>

        {{-- pending approvals --}}
        <div class="hv-stat">
            <p class="hv-stat-label">Surat Menunggu Persetujuan (Global)</p>
            <p class="hv-stat-num">{{ $suratMenungguCount ?? 0 }}</p>
            <div class="hv-stat-bottom">
                <svg class="hv-stat-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <a href="{{ route('surat.index') }}" class="hv-list-link" style="font-size:12px;text-decoration:underline;text-underline-offset:2px;color:#111111;">Lihat Surat</a>
            </div>
        </div>

        {{-- recent activity --}}
        <div class="hv-recent">
            <div class="hv-recent-header">
                <p class="hv-recent-title">Aktivitas Terbaru</p>
                <a href="{{ route('activity.log') }}" class="hv-recent-viewall">Lihat semua</a>
            </div>
            <p class="hv-recent-sub">Log aktivitas sistem</p>
            <div class="hv-recent-list">
                @foreach($recentActivities as $log)
                <div class="hv-recent-item">
                    <div class="hv-recent-ava">
                        {{ strtoupper(substr($log->user?->name ?? 'S', 0, 1)) }}
                    </div>
                    <div>
                        <p class="hv-recent-name">{{ $log->user?->name ?? 'System' }}</p>
                        <p class="hv-recent-desc">
                            {{ $log->description }}<br>
                            <span style="opacity:.55;">{{ $log->created_at->diffForHumans() }}</span>
                        </p>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="hv-recent-empty">Belum ada aktivitas</div>
            @endif
        </div>

    </div>{{-- /row1 --}}

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-6">
        <div class="hv-actions-card">
            <p class="hv-actions-title">Aksi Cepat Admin</p>
            <div class="hv-actions-list">
                <a href="{{ route('organisasi.index') }}" class="hv-btn-primary">Kelola Organisasi</a>
                <a href="{{ route('surat-type.index') }}" class="hv-btn-outline">Kelola Jenis Surat</a>
            </div>
        </div>
    </div>

{{-- ══════ pengawas_pusat & kepala_sekolah ══════ --}}
@elseif(auth()->user()->hasAnyRole(['pengawas_pusat', 'kepala_sekolah']))

    <div class="hv-row1">

        {{-- foto profil --}}
        <div class="hv-photo-card">
            @if(auth()->user()->avatar)
                <img src="{{ URL::to('assets/images/user/'.auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}">
            @else
                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=FFF1F2&color=111111&size=200" alt="{{ auth()->user()->name }}">
            @endif
            <div class="hv-photo-overlay">
                <p class="hv-photo-name">{{ auth()->user()->name }}</p>
                <p class="hv-photo-role">{{ $userRoleName }}</p>
            </div>
        </div>

        {{-- surat menunggu --}}
        <div class="hv-stat dark">
            <p class="hv-stat-label">Perlu Persetujuan Anda</p>
            <p class="hv-stat-num">{{ $suratMenungguCount ?? 0 }}</p>
            <div class="hv-stat-bottom">
                <svg class="hv-stat-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <a href="{{ route('surat.index') }}" class="hv-list-link" style="font-size:12px;text-decoration:underline;text-underline-offset:2px;color:rgba(255,255,255,0.8);">Buka daftar surat</a>
            </div>
        </div>
        
        <div class="hv-stat" style="opacity: 0;"></div>

        {{-- recent activity --}}
        <div class="hv-recent">
            <div class="hv-recent-header">
                <p class="hv-recent-title">Daftar Menunggu</p>
                <a href="{{ route('surat.index') }}" class="hv-recent-viewall">Lihat semua</a>
            </div>
            <p class="hv-recent-sub">Surat yang menunggu persetujuan Anda</p>
            @if(isset($suratMenungguList) && $suratMenungguList->count())
            <div class="hv-recent-list">
                @foreach($suratMenungguList->take(4) as $rs)
                <div class="hv-recent-item">
                    <div class="hv-recent-ava">
                        {{ strtoupper(substr($rs->user?->name ?? 'U', 0, 1)) }}
                    </div>
                    <div>
                        <p class="hv-recent-name">{{ $rs->user?->name ?? '-' }} ({{ $rs->organisasi->nama ?? '' }})</p>
                        <p class="hv-recent-desc">
                            {{ ucfirst(str_replace('_',' ',$rs->jenis_surat)) }}<br>
                            <span style="opacity:.55;">{{ $rs->created_at->diffForHumans() }}</span>
                        </p>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="hv-recent-empty">Tidak ada surat menunggu</div>
            @endif
        </div>

    </div>{{-- /row1 --}}


{{-- ══════ guru & anggota (Pengurus/Pembuat Surat) ══════ --}}
@else

    <div class="hv-row1">

        {{-- foto profil --}}
        <div class="hv-photo-card">
            @if(auth()->user()->avatar)
                <img src="{{ URL::to('assets/images/user/'.auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}">
            @else
                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=FFF1F2&color=111111&size=200" alt="{{ auth()->user()->name }}">
            @endif
            <div class="hv-photo-overlay">
                <p class="hv-photo-name">{{ auth()->user()->name }}</p>
                <p class="hv-photo-role">{{ auth()->user()->profile?->jabatan_struktural ?? $userRoleName }}</p>
            </div>
        </div>

        {{-- Surat diajukan --}}
        <div class="hv-stat">
            <p class="hv-stat-label">Surat Saya Diajukan</p>
            <p class="hv-stat-num">{{ $suratStaffDiajukan ?? 0 }}</p>
            <div class="hv-stat-bottom">
                <svg class="hv-stat-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
            </div>
        </div>

        {{-- Surat disetujui --}}
        <div class="hv-stat">
            <p class="hv-stat-label">Surat Saya Disetujui</p>
            <p class="hv-stat-num text-green-700">{{ $suratStaffSelesai ?? 0 }}</p>
            <div class="hv-stat-bottom">
                <svg class="hv-stat-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
        </div>

        {{-- Butuh approval dari user (bila anggota BPH dll) --}}
        <div class="hv-recent">
            <div class="hv-recent-header">
                <p class="hv-recent-title">Perlu Persetujuan Anda</p>
                <a href="{{ route('surat.index') }}" class="hv-recent-viewall">Lihat semua</a>
            </div>
            <p class="hv-recent-sub">Di organisasi tempat Anda bergabung</p>
            @if(isset($suratMenungguList) && $suratMenungguList->count())
            <div class="hv-recent-list">
                @foreach($suratMenungguList->take(4) as $rs)
                <div class="hv-recent-item">
                    <div class="hv-recent-ava">
                        {{ strtoupper(substr($rs->user?->name ?? 'U', 0, 1)) }}
                    </div>
                    <div>
                        <p class="hv-recent-name">{{ $rs->user?->name ?? '-' }} ({{ $rs->organisasi->nama ?? '' }})</p>
                        <p class="hv-recent-desc">
                            {{ ucfirst(str_replace('_',' ',$rs->jenis_surat)) }}<br>
                            <span style="opacity:.55;">{{ $rs->created_at->diffForHumans() }}</span>
                        </p>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="hv-recent-empty">Tidak ada surat untuk disetujui</div>
            @endif
        </div>

    </div>{{-- /row1 --}}

    {{-- Info Organisasi & Aksi Cepat --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-6">
        
        <div class="hv-full-card">
            <p class="font-sans text-lg font-bold mb-4">Organisasi Saya</p>
            @if(isset($myOrganisasi) && $myOrganisasi->count())
                <div class="space-y-3">
                @foreach($myOrganisasi as $orgMember)
                    <div class="flex items-center justify-between p-3 rounded-2xl border border-gray-100 bg-gray-50/50">
                        <div>
                            <p class="font-medium text-sm text-gray-800">{{ $orgMember->organisasi->nama ?? '-' }}</p>
                            <p class="text-xs text-gray-500">{{ $orgMember->organisasi->tipe_label ?? '-' }}</p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-[var(--color-primary)]/20 text-[var(--color-text)]">
                            {{ $orgMember->jabatan_label }}
                        </span>
                    </div>
                @endforeach
                </div>
            @else
                <div class="text-center text-sm text-gray-400 py-6">Anda belum tergabung dalam organisasi manapun.</div>
            @endif
        </div>

        <div class="hv-actions-card">
            <p class="hv-actions-title">Aksi Cepat</p>
            <div class="hv-actions-list">
                @can('create', App\Models\Surat::class)
                <a href="{{ route('surat.create') }}" class="hv-btn-primary">Ajukan Surat Baru</a>
                @endcan
                <a href="{{ route('surat.index') }}" class="hv-btn-outline">Lihat Semua Surat</a>
                <a href="{{ route('profile.show') }}" class="hv-btn-outline">Perbarui Profil</a>
            </div>
        </div>
    </div>

@endif

</div>
@endsection


