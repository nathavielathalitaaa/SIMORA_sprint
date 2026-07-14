@extends('layouts.master')

@section('title', 'Tambah Sub Organ — SIMORA')

@section('content')
<div class="content-header">
    <div class="content-header-left">
        <a href="{{ route('organisasi.index') }}" class="breadcrumb-back">
            <i data-lucide="arrow-left" style="width:16px;height:16px;"></i> Kelola Organisasi
        </a>
        <h1 class="page-title" style="margin-top:.5rem;">Tambah Sub Organ Baru</h1>
        <p class="page-subtitle">Sub Organ adalah ekskul, UKM, atau organisasi non-inti yang bernaung di bawah OSIS/MPK.</p>
    </div>
</div>

<div class="form-card">
    @if($errors->any())
    <div class="alert alert-danger">
        <ul style="margin:0;padding-left:1.25rem;">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('organisasi.store') }}">
        @csrf
        <div class="form-group">
            <label class="form-label" for="nama">Nama Sub Organ <span class="required">*</span></label>
            <input type="text" id="nama" name="nama" class="form-input" value="{{ old('nama') }}"
                   placeholder="cth: ROHIS, Pramuka, Paduan Suara..." required>
        </div>
        <div class="form-group">
            <label class="form-label" for="deskripsi">Deskripsi</label>
            <textarea id="deskripsi" name="deskripsi" class="form-input" rows="3"
                      placeholder="Deskripsi singkat Sub Organ...">{{ old('deskripsi') }}</textarea>
        </div>
        <div class="form-actions">
            <a href="{{ route('organisasi.index') }}" class="btn btn-outline">Batal</a>
            <button type="submit" class="btn btn-primary">
                <i data-lucide="plus" style="width:16px;height:16px;"></i>
                Buat Sub Organ
            </button>
        </div>
    </form>
</div>

<style>
.breadcrumb-back { color:var(--text-muted,#888); font-size:.875rem; display:inline-flex; align-items:center; gap:.35rem; text-decoration:none; }
.breadcrumb-back:hover { color:var(--text-primary,#fff); }
.content-header { margin-bottom:2rem; }
.page-title { font-size:1.5rem; font-weight:700; }
.page-subtitle { color:var(--text-muted,#888); }
.form-card { background:var(--card-bg,#1e2128); border-radius:16px; border:1px solid rgba(255,255,255,.08); padding:2rem; max-width:540px; }
.form-group { margin-bottom:1.25rem; }
.form-label { font-size:.8rem; color:var(--text-muted,#888); display:block; margin-bottom:.4rem; font-weight:500; }
.form-input { width:100%; background:rgba(255,255,255,.05); border:1px solid rgba(255,255,255,.12); border-radius:8px; padding:.625rem .875rem; color:var(--text-primary,#fff); font-size:.875rem; box-sizing:border-box; }
.form-input:focus { outline:none; border-color:rgba(99,102,241,.5); }
.form-actions { display:flex; gap:.75rem; justify-content:flex-end; margin-top:1.5rem; }
.required { color:#f87171; }
.alert-danger { background:rgba(239,68,68,.1); border:1px solid rgba(239,68,68,.3); border-radius:8px; padding:.875rem 1rem; margin-bottom:1.25rem; color:#fca5a5; font-size:.875rem; }
</style>
@endsection
