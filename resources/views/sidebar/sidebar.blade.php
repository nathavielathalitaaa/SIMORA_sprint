{{-- ═══════════════════════════════════════════════
     SIMORA — Sidebar navigasi utama
     ═══════════════════════════════════════════════ --}}
<div class="hv-sidebar" id="hv-sidebar">

    {{-- ── top nav icons ── --}}
    <div class="hv-sidebar-nav">

        {{-- profile --}}
        <a href="{{ route('profile.show') }}"
           class="{{ request()->routeIs('profile.show') ? 'active' : '' }}"
           title="Profil Saya">
            <i data-lucide="user-circle"></i>
            <span>Profil Saya</span>
        </a>

        {{-- dashboard --}}
        <a href="{{ route('home') }}"
           class="{{ request()->routeIs('home') ? 'active' : '' }}"
           title="Dashboard">
            <i data-lucide="monitor"></i>
            <span>Dashboard</span>
        </a>

        {{-- surat ── --}}
        <a href="{{ route('surat.index') }}"
           class="{{ request()->routeIs('surat.index') || request()->routeIs('surat.create') || request()->routeIs('surat.show') ? 'active' : '' }}"
           title="Surat Saya & Approval">
            <i data-lucide="mail"></i>
            <span>Surat Saya</span>
        </a>

        {{-- Pelaksanaan & LPJ ── --}}
        <a href="{{ route('pelaksanaan.index') }}"
           class="{{ request()->routeIs('pelaksanaan.index') || request()->routeIs('pelaksanaan.disposisi') || request()->routeIs('lpj.create') ? 'active' : '' }}"
           title="Pelaksanaan & LPJ">
            <i data-lucide="play-circle"></i>
            <span>Pelaksanaan</span>
        </a>

        {{-- Verifikasi LPJ (Admin/Guru) ── --}}
        @if(auth()->user()->hasAnyRole(['admin', 'super-admin', 'guru']))
        <a href="{{ route('lpj.verifikasi.index') }}"
           class="{{ request()->routeIs('lpj.verifikasi.index') ? 'active' : '' }}"
           title="Verifikasi LPJ">
            <i data-lucide="check-square"></i>
            <span>Verifikasi LPJ</span>
        </a>
        @endif

        {{-- Database Arsip LPJ ── --}}
        <a href="{{ route('arsip.index') }}"
           class="{{ request()->routeIs('arsip.index') || request()->routeIs('lpj.show') ? 'active' : '' }}"
           title="Database Arsip LPJ">
            <i data-lucide="archive"></i>
            <span>Arsip LPJ</span>
        </a>

        {{-- inbox admin (admin only) --}}
        @if(auth()->user()->hasAnyRole(['admin', 'super-admin']))
        <a href="{{ route('surat.inbox_admin') }}"
           class="{{ request()->routeIs('surat.inbox_admin') ? 'active' : '' }}"
           title="Inbox Admin (Verifikasi)">
            <i data-lucide="inbox"></i>
            <span>Inbox Admin</span>
        </a>
        @endif

        {{-- kelola organisasi (admin only) --}}
        @if(auth()->user()->hasAnyRole(['admin', 'super-admin']))
        <a href="{{ route('organisasi.index') }}"
           class="{{ request()->routeIs('organisasi.*') || request()->routeIs('komisi.*') ? 'active' : '' }}"
           title="Kelola Organisasi">
            <i data-lucide="users"></i>
            <span>Organisasi</span>
        </a>
        @endif

        {{-- jenis surat (admin only) --}}
        @if(auth()->user()->hasAnyRole(['admin', 'super-admin']))
        <a href="{{ route('surat-type.index') }}"
           class="{{ request()->routeIs('surat-type.*') ? 'active' : '' }}"
           title="Jenis Surat">
            <i data-lucide="file-cog"></i>
            <span>Jenis Surat</span>
        </a>
        @endif

        {{-- system monitor (admin only) --}}
        @if(auth()->user()->hasAnyRole(['admin', 'super-admin']))
        <a href="{{ route('hr/system/monitor') }}"
           class="{{ request()->routeIs('hr/system/monitor') ? 'active' : '' }}"
           title="Monitor Sistem">
            <i data-lucide="activity"></i>
            <span>Sistem</span>
        </a>
        @endif

        {{-- pengaturan (admin only) --}}
        @if(auth()->user()->hasAnyRole(['admin', 'super-admin']))
        <a href="{{ route('hr.settings.document') }}"
           class="{{ request()->routeIs('hr.settings.*') ? 'active' : '' }}"
           title="Pengaturan">
            <i data-lucide="settings"></i>
            <span>Pengaturan</span>
        </a>
        @endif

    </div>

    {{-- ── logout at bottom ── --}}
    <div class="hv-sidebar-bottom">
        <a href="{{ route('logout') }}" class="hv-sidebar-logout" title="Logout">
            <i data-lucide="log-out"></i>
            <span>Logout</span>
        </a>
    </div>

</div>