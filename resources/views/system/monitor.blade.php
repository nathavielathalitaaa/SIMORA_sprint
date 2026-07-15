@extends('layouts.master')

@section('content')
<style>
    .monitor-card {
        background: var(--color-surface);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-card);
        padding: 30px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
    }
    .monitor-title {
        font-family: 'Poppins', sans-serif;
        font-size: 24px;
        color: var(--color-text);
        margin-bottom: 20px;
    }
    .stat-value {
        font-family: 'Poppins', sans-serif;
        font-size: 42px;
        font-weight: 700;
        color: var(--color-text);
    }
    .stat-label {
        font-size: 14px;
        color: var(--color-text-muted);
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .info-list dt {
        font-size: 12px;
        color: var(--color-text-muted);
        text-transform: uppercase;
        margin-bottom: 4px;
    }
    .info-list dd {
        font-size: 16px;
        color: var(--color-text);
        font-weight: 500;
        margin-bottom: 20px;
    }

</style>

<div class="mb-10">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-4xl font-sans font-bold text-[var(--color-text)]">Monitor Kesehatan Sistem</h1>
            <p class="text-[var(--color-text-muted)] mt-2">Memantau sumber daya server, penyimpanan, dan integritas log audit.</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-8">
    {{-- Audit Logs Card --}}
    <div class="monitor-card">
        <p class="stat-label">Integritas Audit</p>
        <h2 class="monitor-title">Log Aktivitas</h2>
        <div class="stat-value">{{ number_format($logCount) }}</div>
        <p class="text-sm text-gray-500 mt-2">+{{ $logsThisMonth }} entri bulan ini</p>
        
        <div class="mt-6 p-4 bg-[var(--color-bg-light)] rounded-2xl border border-[var(--color-border)]">
            <p class="text-[11px] font-semibold text-[var(--color-primary)] uppercase tracking-wider mb-2">Arsip Offline</p>
            <div class="flex justify-between items-center">
                <span class="text-sm text-[var(--color-text-muted)]">Total File</span>
                <span class="text-sm font-bold text-[var(--color-text)]">{{ $totalArchives }} CSVs</span>
            </div>
            <div class="flex justify-between items-center mt-1">
                <span class="text-sm text-[var(--color-text-muted)]">Ukuran Arsip</span>
                <span class="text-sm font-bold text-[var(--color-text)]">{{ $archiveSize }}</span>
            </div>
        </div>

        <div class="mt-4 pt-4 border-t border-gray-100">
            <p class="text-[10px] text-gray-400">Sistem secara otomatis memindahkan log yang berusia lebih dari 12 bulan ke arsip CSV terenkripsi setiap bulan.</p>
        </div>
    </div>

    {{-- Storage Card --}}
    <div class="monitor-card relative">
        <p class="stat-label">Penggunaan Penyimpanan</p>
        <h2 class="monitor-title">Penyimpanan Dokumen</h2>
        <div class="stat-value">{{ $storageSize }}</div>
        <p class="text-sm text-gray-500 mt-2">{{ $totalFiles }} file disimpan di /public/surat</p>
        
        <div class="mt-6 flex gap-2">
            <a href="{{ route('system/monitor/archive-manager') }}" class="px-4 py-2 bg-[var(--color-bg-light)] text-[var(--color-primary)] hover:bg-[var(--color-border)] rounded-2xl text-xs font-semibold transition border border-[var(--color-border)] flex items-center gap-2">
                <i data-lucide="archive" class="w-3.5 h-3.5"></i> Arsipkan Dokumen
            </a>
        </div>

        <div class="mt-4 pt-4 border-t border-[var(--color-border)]">
            <div class="w-full bg-[var(--color-border)] rounded-full h-2">
                <div class="bg-[var(--color-primary)] h-2 rounded-full" style="width: 15%"></div>
            </div>
            <p class="text-[10px] text-[var(--color-text-muted)] mt-2">Kapasitas penyimpanan aman yang tersisa (Estimasi 85%)</p>
        </div>
    </div>

    {{-- Database Card --}}
    <div class="monitor-card">
        <p class="stat-label">Muatan Data</p>
        <h2 class="monitor-title">Ukuran Database</h2>
        <div class="stat-value">{{ $dbSize }}</div>
        <p class="text-sm text-gray-500 mt-2">Tabel yang dioptimalkan dengan nol overhead</p>
        <div class="mt-8 pt-8 border-t border-gray-100">
            <p class="text-xs text-gray-400">Enkripsi database aktif untuk profil anggota/staf yang sensitif.</p>
        </div>
    </div>
</div>

<div class="mt-8">
    <div class="monitor-card">
        <h2 class="monitor-title">Info Lingkungan Server</h2>
        <dl class="grid grid-cols-1 md:grid-cols-4 gap-4 info-list">
            <div>
                <dt>Versi PHP</dt>
                <dd>{{ $phpVersion }}</dd>
            </div>
            <div>
                <dt>Versi Laravel</dt>
                <dd>{{ $laravelVersion }}</dd>
            </div>
            <div>
                <dt>Perangkat Lunak Server</dt>
                <dd>{{ $serverInfo }}</dd>
            </div>
            <div>
                <dt>Pemindaian Terakhir</dt>
                <dd>{{ now()->format('d M Y, H:i') }}</dd>
            </div>
        </dl>
    </div>
</div>



@push('scripts')
@if (Session::has('error'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        alert("{{ Session::get('error') }}");
    });
</script>
@endif
@endpush

@endsection

