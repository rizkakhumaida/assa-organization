@extends('layouts.app')

@section('content')
<style>
    /* 🌈 Tampilan Modern + Glassmorphism */
    body {
        background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%);
    }

    .detail-card {
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(16px);
        border-radius: 20px;
        border: 1px solid rgba(255, 255, 255, 0.4);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        padding: 2rem;
    }

    .detail-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
    }

    .gradient-title {
        background: linear-gradient(90deg, #4f46e5, #7c3aed);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .info-label {
        font-weight: 600;
        color: #4f46e5;
    }

    .info-value {
        color: #374151;
    }

    .btn-modern {
        border-radius: 10px;
        transition: all 0.2s ease-in-out;
    }

    .btn-modern:hover {
        transform: scale(1.05);
    }

    .badge {
        padding: 0.5em 0.8em;
        font-size: 0.9rem;
        border-radius: 8px;
        text-transform: capitalize;
    }

    .fade-in {
        animation: fadeIn 0.7s ease-in-out both;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<div class="container py-5 fade-in">
    {{-- 🌟 Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h3 class="fw-bold gradient-title">
            <i class="bi bi-award me-2"></i> Detail Pengajuan Beasiswa
        </h3>
        <a href="{{ route('admin.scholarship.index') }}" class="btn btn-outline-primary btn-modern">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
        </a>
    </div>

    {{-- 💎 Kartu Detail --}}
    <div class="detail-card">
        <div class="d-flex align-items-center mb-4">
            <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3">
                <i class="bi bi-person-circle text-primary fs-2"></i>
            </div>
            <div>
                <h4 class="fw-bold text-dark mb-0">{{ $scholarship->nama_pendaftar }}</h4>
                <small class="text-muted">Pendaftar Beasiswa</small>
            </div>
        </div>

        <hr class="mb-4">

        <div class="row g-4">
            <div class="col-md-6">
                <p class="info-label">🏫 Kampus</p>
                <p class="info-value">{{ $scholarship->campus ?? '-' }}</p>
            </div>

            <div class="col-md-6">
                <p class="info-label">🎓 Program Studi</p>
                <p class="info-value">{{ $scholarship->program_studi ?? '-' }}</p>
            </div>

            <div class="col-md-6">
                <p class="info-label">📅 Tanggal Pengajuan</p>
                <p class="info-value">
                    {{ \Carbon\Carbon::parse($scholarship->tanggal_pengajuan)->format('d M Y') }}
                </p>
            </div>

            <div class="col-md-6">
                <p class="info-label">📋 Status</p>
                <span class="badge
                    @if($scholarship->status == 'approved') bg-success
                    @elseif($scholarship->status == 'rejected') bg-danger
                    @elseif($scholarship->status == 'on hold') bg-warning text-dark
                    @else bg-secondary @endif">
                    {{ ucfirst($scholarship->status) }}
                </span>
            </div>

            {{-- Dokumen Pendukung --}}
            <div class="col-12 mt-3">
                <p class="info-label">📎 Dokumen Pendukung</p>
                @if($scholarship->dokumen_pendukung)
                    <a href="{{ asset('storage/' . $scholarship->dokumen_pendukung) }}"
                       target="_blank"
                       class="btn btn-outline-primary btn-modern">
                        <i class="bi bi-file-earmark-pdf"></i> Lihat Dokumen
                    </a>
                @else
                    <p class="text-muted fst-italic">Tidak ada dokumen yang diunggah.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
