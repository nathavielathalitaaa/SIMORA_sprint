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
.breadcrumb-back { color:var(--color-text-muted); font-size:.875rem; display:inline-flex; align-items:center; gap:.35rem; text-decoration:none; font-weight:500; }
.breadcrumb-back:hover { color:var(--color-text); }
.content-header { margin-bottom:2rem; }
.page-title { font-family:'Poppins',sans-serif; font-size:1.5rem; font-weight:700; color:var(--color-text); margin:.5rem 0 0; }
.page-subtitle { color:var(--color-text-muted); font-size:13px; margin:0; }
.form-card { background:var(--color-surface); border-radius:var(--radius-card); border:none; box-shadow:0 4px 20px rgba(0,0,0,0.03); padding:2rem; max-width:540px; }
.form-group { margin-bottom:1.25rem; }
.form-label { font-size:.8rem; color:var(--color-text-muted); display:block; margin-bottom:.4rem; font-weight:500; }
.form-input { width:100%; background:var(--color-bg-light); border:1px solid var(--color-border); border-radius:var(--radius-input); padding:.625rem .875rem; color:var(--color-text); font-size:.875rem; box-sizing:border-box; font-family:'Poppins',sans-serif; }
.form-input:focus { outline:none; border-color:var(--color-primary); box-shadow:0 0 0 3px rgba(230,33,41,0.12); }
.form-actions { display:flex; gap:.75rem; justify-content:flex-end; margin-top:1.5rem; }
.required { color:var(--color-primary); }
.alert-danger { background:rgba(230,33,41,0.08); border:1px solid rgba(230,33,41,0.2); border-radius:var(--radius-card); padding:.875rem 1rem; margin-bottom:1.25rem; color:var(--color-primary); font-size:.875rem; }
</style>
@endsection
