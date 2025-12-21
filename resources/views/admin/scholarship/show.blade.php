@extends('layouts.app')

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #eef2ff, #e0e7ff);
        font-family: 'Poppins', sans-serif;
    }

    .page-header {
        background: linear-gradient(90deg, #1e40af, #2563eb);
        border-radius: 14px;
        padding: 18px 22px;
        color: #ffffff;
        box-shadow: 0 10px 25px rgba(37,99,235,.35);
    }

    .glass-card {
        background: rgba(255, 255, 255, 0.88);
        backdrop-filter: blur(18px);
        border-radius: 22px;
        border: 1px solid rgba(255,255,255,.4);
        box-shadow: 0 15px 40px rgba(0,0,0,.12);
        padding: 2.2rem;
    }

    .section-title {
        font-weight: 700;
        color: #4338ca;
        margin-bottom: .75rem;
    }

    .info-label {
        font-size: .85rem;
        font-weight: 600;
        color: #6366f1;
    }

    .info-value {
        font-weight: 500;
        color: #1f2937;
    }

    .doc-card {
        background: #f8fafc;
        border-radius: 14px;
        padding: .75rem 1rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: .75rem;
        border: 1px solid #e5e7eb;
        transition: .25s;
    }

    .doc-card:hover {
        background: #eef2ff;
        transform: translateY(-2px);
    }

    .doc-icon {
        font-size: 1.4rem;
        color: #4f46e5;
    }

    .badge {
        padding: .45em .9em;
        border-radius: 12px;
        font-size: .85rem;
    }
</style>

<div class="container py-5">

    {{-- HEADER --}}
    <div class="page-header mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0 fw-bold">Detail Pengajuan Beasiswa</h4>
            <small>ASSA Organization</small>
        </div>
        <a href="{{ route('admin.scholarship.index') }}" class="btn btn-light btn-sm fw-semibold">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="glass-card">

        {{-- IDENTITAS --}}
        <div class="d-flex align-items-center gap-3 mb-4">
            <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                <i class="bi bi-person-circle fs-2 text-primary"></i>
            </div>
            <div>
                <h4 class="fw-bold mb-0">{{ $scholarship->full_name }}</h4>
                <small class="text-muted">{{ $scholarship->email }}</small>
            </div>
        </div>

        <hr>

        {{-- INFO --}}
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="info-label">Kampus</div>
                <div class="info-value">{{ $scholarship->campus ?? '-' }}</div>
            </div>

            <div class="col-md-4">
                <div class="info-label">Tanggal Pengajuan</div>
                <div class="info-value">
                    {{ optional($scholarship->created_at)->format('d M Y, H:i') }}
                </div>
            </div>

            <div class="col-md-4">
                <div class="info-label">Status</div>
                <span class="badge
                    @if(strtolower($scholarship->status) === 'approved') bg-success
                    @elseif(strtolower($scholarship->status) === 'rejected') bg-danger
                    @elseif(strtolower($scholarship->status) === 'on hold') bg-warning text-dark
                    @else bg-secondary @endif">
                    {{ strtoupper($scholarship->status) }}
                </span>
            </div>
        </div>

        {{-- ALASAN --}}
        <div class="mb-4">
            <p class="section-title">Alasan Pengajuan</p>
            <p class="text-muted lh-lg">{{ $scholarship->reason }}</p>
        </div>

        {{-- DOKUMEN --}}
        <div>
            <p class="section-title">Dokumen Pendukung</p>

            @php
                $docs = [
                    'KTP' => ['path' => $scholarship->file_ktp, 'type' => 'ktp'],
                    'KK' => ['path' => $scholarship->file_kk, 'type' => 'kk'],
                    'Ijazah / Transkrip' => ['path' => $scholarship->file_ijazah_transkrip, 'type' => 'ijazah'],
                    'KIP / PKH' => ['path' => $scholarship->file_kip_pkh, 'type' => 'kip'],
                    'SKTM' => ['path' => $scholarship->file_sktm, 'type' => 'sktm'],
                    'Prestasi' => ['path' => $scholarship->file_prestasi, 'type' => 'prestasi'],
                ];
            @endphp

            <div class="row g-3">
                @php $hasDoc = false; @endphp

                @foreach ($docs as $label => $doc)
                    @if ($doc['path'])
                        @php $hasDoc = true; @endphp
                        <div class="col-md-4 col-lg-3">
                            <div class="doc-card">

                                {{-- PREVIEW --}}
                                <a href="{{ asset('storage/'.$doc['path']) }}"
                                   target="_blank"
                                   class="text-decoration-none d-flex align-items-center gap-2">
                                    <i class="bi bi-file-earmark-pdf doc-icon"></i>
                                    <div>
                                        <div class="fw-semibold text-dark">{{ $label }}</div>
                                        <small class="text-muted">Buka dokumen</small>
                                    </div>
                                </a>

                                {{-- FORCE DOWNLOAD --}}
                                <a href="{{ route('admin.scholarship.download', [
                                        'id' => $scholarship->id,
                                        'type' => $doc['type']
                                    ]) }}"
                                   class="btn btn-sm btn-outline-primary rounded-circle"
                                   title="Download {{ $label }}">
                                    <i class="bi bi-download"></i>
                                </a>

                            </div>
                        </div>
                    @endif
                @endforeach

                @if (!$hasDoc)
                    <p class="text-muted fst-italic">Tidak ada dokumen diunggah.</p>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection
