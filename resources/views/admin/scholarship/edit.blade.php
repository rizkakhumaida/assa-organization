@extends('layouts.app')

@section('content')
<style>
    body{
        background: radial-gradient(1200px 600px at 10% 0%, #dbeafe 0%, transparent 60%),
                    radial-gradient(900px 500px at 90% 20%, #e0f2fe 0%, transparent 55%),
                    linear-gradient(135deg, #f1f5f9, #eef2ff);
        font-family: 'Poppins', sans-serif;
    }

    .page-header{
        background: linear-gradient(90deg, #1e40af, #2563eb);
        border-radius: 16px;
        padding: 18px 22px;
        color: #fff;
        box-shadow: 0 12px 30px rgba(37,99,235,.30);
    }
    .page-header h4{ margin:0; font-weight:800; letter-spacing:.2px; }
    .page-header small{ opacity:.9; font-size:.85rem; }

    .glass-card{
        background: rgba(255,255,255,.90);
        backdrop-filter: blur(16px);
        border: 1px solid rgba(255,255,255,.45);
        border-radius: 18px;
        box-shadow: 0 14px 40px rgba(2,6,23,.08);
    }

    .section-title{
        font-weight: 800;
        color:#0f172a;
        margin-bottom: .75rem;
    }

    .label{
        font-weight: 700;
        color:#1f2937;
        margin-bottom: .35rem;
    }

    .control{
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        padding: .7rem .9rem;
        background: rgba(255,255,255,.95);
    }
    .control:focus{
        outline:none;
        border-color:#93c5fd;
        box-shadow: 0 0 0 .2rem rgba(59,130,246,.15);
    }

    .doc-row{
        background:#f8fafc;
        border:1px solid #e2e8f0;
        border-radius: 14px;
        padding: 12px 14px;
    }

    .doc-pill{
        display:inline-flex;
        align-items:center;
        gap:.45rem;
        padding:.35rem .65rem;
        border-radius: 999px;
        background:#eef2ff;
        color:#3730a3;
        font-weight:700;
        font-size:.8rem;
        border:1px solid rgba(99,102,241,.25);
    }

    .btn-icon{
        width: 40px; height: 40px;
        display:inline-flex;
        align-items:center; justify-content:center;
        border-radius: 12px;
    }
</style>

@php
    // mapping field => label & type download route
    $docs = [
        'file_ktp'              => ['label' => 'KTP',                 'type' => 'ktp'],
        'file_kk'               => ['label' => 'KK',                  'type' => 'kk'],
        'file_ijazah_transkrip' => ['label' => 'Ijazah / Transkrip',   'type' => 'ijazah'],
        'file_kip_pkh'          => ['label' => 'KIP / PKH',            'type' => 'kip'],
        'file_sktm'             => ['label' => 'SKTM',                'type' => 'sktm'],
        'file_prestasi'         => ['label' => 'Sertifikat Prestasi', 'type' => 'prestasi'],
    ];

    // NORMALISASI STATUS agar "Approved"/"approved" tetap terbaca sama
    $s = strtolower(trim(old('status', $scholarship->status ?? 'pending')));

    // jaga-jaga kalau ada nilai status aneh di DB
    if (!in_array($s, ['pending','approved','rejected','on hold'], true)) {
        $s = 'pending';
    }
@endphp

<div class="container py-5">

    {{-- HEADER --}}
    <div class="page-header mb-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div class="d-flex align-items-center gap-3">
            <div class="bg-white bg-opacity-25 p-2 rounded-circle">
                <i class="bi bi-pencil-square fs-5"></i>
            </div>
            <div>
                <h4>Edit Pengajuan Beasiswa</h4>
                <small>Perbarui data pemohon, status, dan dokumen pendukung</small>
            </div>
        </div>

        <a href="{{ route('admin.scholarship.index') }}" class="btn btn-light btn-sm fw-semibold">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    {{-- ERROR ALERT --}}
    @if ($errors->any())
        <div class="alert alert-danger glass-card p-3 mb-3 border-0">
            <div class="fw-bold mb-2"><i class="bi bi-exclamation-triangle-fill me-1"></i> Validasi gagal</div>
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="glass-card p-4">

        {{-- IDENTITAS --}}
        <div class="d-flex align-items-center gap-3 mb-3">
            <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                <i class="bi bi-person-circle fs-2 text-primary"></i>
            </div>
            <div>
                <div class="fw-bold fs-5 mb-0">{{ $scholarship->full_name }}</div>
                <div class="text-muted">{{ $scholarship->email }}</div>
            </div>
        </div>

        <hr class="my-3">

        <form action="{{ route('admin.scholarship.update', $scholarship->id) }}"
              method="POST"
              enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- DATA UTAMA --}}
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="label">Nama Lengkap</div>
                    <input type="text"
                           name="full_name"
                           class="form-control control"
                           value="{{ old('full_name', $scholarship->full_name) }}"
                           required>
                </div>

                <div class="col-md-6">
                    <div class="label">Email</div>
                    <input type="email"
                           name="email"
                           class="form-control control"
                           value="{{ old('email', $scholarship->email) }}"
                           required>
                </div>

                <div class="col-md-6">
                    <div class="label">Kampus</div>
                    <input type="text"
                           name="campus"
                           class="form-control control"
                           value="{{ old('campus', $scholarship->campus) }}"
                           required>
                </div>

                <div class="col-md-6">
                    <div class="label">Status Pengajuan</div>
                    <select name="status" class="form-select control" required>
                        <option value="pending"  {{ $s === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ $s === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ $s === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="on hold"  {{ $s === 'on hold' ? 'selected' : '' }}>On Hold</option>
                    </select>
                </div>

                <div class="col-12">
                    <div class="label">Alasan Pengajuan</div>
                    <textarea name="reason"
                              rows="4"
                              class="form-control control"
                              required>{{ old('reason', $scholarship->reason) }}</textarea>
                </div>
            </div>

            <hr class="my-4">

            {{-- DOKUMEN --}}
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
                <div class="section-title mb-0">
                    <i class="bi bi-paperclip me-1"></i> Dokumen Pendukung
                </div>
                <div class="text-muted" style="font-size:.9rem;">
                    Format: PDF/JPG/PNG (maks 5MB)
                </div>
            </div>

            <div class="row g-3">
                @foreach ($docs as $field => $meta)
                    <div class="col-md-6">
                        <div class="doc-row">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="fw-bold">{{ $meta['label'] }}</div>

                                @if (!empty($scholarship->$field))
                                    <a class="doc-pill text-decoration-none"
                                       href="{{ route('admin.scholarship.download', ['id' => $scholarship->id, 'type' => $meta['type']]) }}">
                                        <i class="bi bi-download"></i> Download
                                    </a>
                                @else
                                    <span class="doc-pill" style="background:#f1f5f9;color:#64748b;border-color:#e2e8f0;">
                                        <i class="bi bi-info-circle"></i> Belum ada
                                    </span>
                                @endif
                            </div>

                            <input type="file" name="{{ $field }}" class="form-control control">
                            <div class="text-muted mt-2" style="font-size:.85rem;">
                                Upload baru jika ingin mengganti dokumen yang ada.
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <hr class="my-4">

            {{-- ACTIONS --}}
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.scholarship.show', $scholarship->id) }}"
                   class="btn btn-outline-secondary">
                    <i class="bi bi-eye"></i> Lihat Detail
                </a>

                <a href="{{ route('admin.scholarship.index') }}" class="btn btn-outline-secondary">
                    Batal
                </a>

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
