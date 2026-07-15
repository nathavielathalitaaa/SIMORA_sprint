{{-- ═══════════════════════════════════════════════
     SIMORA — Sidebar navigasi utama
     ═══════════════════════════════════════════════ --}}
<div class="hv-sidebar" id="hv-sidebar">
    {{-- ── Logo Area ── --}}
    <div class="px-[30px] pb-8 pt-2">
        <h2 class="text-white text-3xl font-bold tracking-widest">SIMORA</h2>
    </div>

    {{-- ── top nav icons ── --}}
    <div class="hv-sidebar-nav">

        {{-- profile --}}
        <a href="{{ route('profile.show') }}"
           class="{{ request()->routeIs('profile.show') ? 'active' : '' }}">
            <i data-lucide="user-circle"></i>
            <span>Profil Saya</span>
        </a>

        {{-- dashboard --}}
        <a href="{{ route('home') }}"
           class="{{ request()->routeIs('home') ? 'active' : '' }}">
            <i data-lucide="monitor"></i>
            <span>Dashboard</span>
        </a>

        {{-- surat ── --}}
        <a href="{{ route('surat.index') }}"
           class="{{ request()->routeIs('surat.index') || request()->routeIs('surat.create') || request()->routeIs('surat.show') ? 'active' : '' }}">
            <i data-lucide="mail"></i>
            <span>Surat Saya</span>
        </a>

        {{-- Pelaksanaan & LPJ ── --}}
        <a href="{{ route('pelaksanaan.index') }}"
           class="{{ request()->routeIs('pelaksanaan.index') || request()->routeIs('pelaksanaan.disposisi') || request()->routeIs('lpj.create') ? 'active' : '' }}">
            <i data-lucide="play-circle"></i>
            <span>Pelaksanaan</span>
        </a>

        {{-- Verifikasi LPJ (Admin/Guru) ── --}}
        @if(auth()->user()->hasAnyRole(['admin', 'super-admin', 'guru']))
        <a href="{{ route('lpj.verifikasi.index') }}"
           class="{{ request()->routeIs('lpj.verifikasi.index') ? 'active' : '' }}">
            <i data-lucide="check-square"></i>
            <span>Verifikasi LPJ</span>
        </a>
        @endif

        {{-- Database Arsip LPJ ── --}}
        <a href="{{ route('arsip.index') }}"
           class="{{ request()->routeIs('arsip.index') || request()->routeIs('lpj.show') ? 'active' : '' }}">
            <i data-lucide="archive"></i>
            <span>Arsip LPJ</span>
        </a>

        {{-- inbox admin (admin only) --}}
        @if(auth()->user()->hasAnyRole(['admin', 'super-admin']))
        <a href="{{ route('surat.inbox_admin') }}"
           class="{{ request()->routeIs('surat.inbox_admin') ? 'active' : '' }}">
            <i data-lucide="inbox"></i>
            <span>Inbox Admin</span>
        </a>
        @endif

        {{-- kelola organisasi (admin only) --}}
        @if(auth()->user()->hasAnyRole(['admin', 'super-admin']))
        <a href="{{ route('organisasi.index') }}"
           class="{{ request()->routeIs('organisasi.*') || request()->routeIs('komisi.*') ? 'active' : '' }}">
            <i data-lucide="users"></i>
            <span>Organisasi</span>
        </a>
        @endif

        {{-- jenis surat (admin only) --}}
        @if(auth()->user()->hasAnyRole(['admin', 'super-admin']))
        <a href="{{ route('surat-type.index') }}"
           class="{{ request()->routeIs('surat-type.*') ? 'active' : '' }}">
            <i data-lucide="file-cog"></i>
            <span>Jenis Surat</span>
        </a>
        @endif

        {{-- system monitor (admin only) --}}
        @if(auth()->user()->hasAnyRole(['admin', 'super-admin']))
        <a href="{{ route('system/monitor') }}"
           class="{{ request()->routeIs('system/monitor') ? 'active' : '' }}">
            <i data-lucide="activity"></i>
            <span>Sistem</span>
        </a>
        @endif

        {{-- pengaturan (admin only) --}}
        @if(auth()->user()->hasAnyRole(['admin', 'super-admin']))
        <a href="{{ route('users.settings.document') }}"
           class="{{ request()->routeIs('users.settings.*') ? 'active' : '' }}">
            <i data-lucide="settings"></i>
            <span>Pengaturan</span>
        </a>
        @endif

    </div>

    {{-- ── logout at bottom ── --}}
    <div class="hv-sidebar-bottom">
        <a href="{{ route('logout') }}" class="hv-sidebar-logout">
            <i data-lucide="log-out"></i>
            <span>Keluar</span>
        </a>
    </div>

</div>
