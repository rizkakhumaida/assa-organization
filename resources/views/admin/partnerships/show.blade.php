@extends('layouts.app')

@section('content')
<div class="container py-5">

    {{-- Tombol Kembali --}}
    <div class="mb-4">
        <a href="{{ route('admin.partnerships.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="bi bi-arrow-left-circle me-1"></i> Kembali ke Daftar
        </a>
    </div>

    {{-- Card Proposal --}}
    <div class="card border-0 rounded-4 glass-card shadow-lg p-4 p-md-5 animate__animated animate__fadeIn">
        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
            <div>
                <h3 class="fw-bold mb-1 text-gradient">
                    <i class="bi bi-handshake text-primary me-2"></i> {{ $proposal->organization_name }}
                </h3>
                <p class="text-muted mb-0">
                    <i class="bi bi-envelope-at me-1"></i> {{ $proposal->contact_email }}
                    @if($proposal->contact_phone)
                        • <i class="bi bi-telephone me-1"></i> {{ $proposal->contact_phone }}
                    @endif
                </p>
            </div>

            {{-- Status Badge --}}
            <span class="badge px-4 py-2 fs-6 text-white rounded-pill shadow-sm
                @class([
                    'bg-secondary' => $proposal->status === 'submitted',
                    'bg-info'      => $proposal->status === 'review',
                    'bg-warning text-dark' => $proposal->status === 'pending',
                    'bg-success'   => $proposal->status === 'approved',
                    'bg-danger'    => $proposal->status === 'rejected',
                    'bg-dark'      => $proposal->status === 'onhold',
                ])">
                <i class="bi bi-circle-fill me-1"></i> {{ ucfirst($proposal->status) }}
            </span>
        </div>

        <hr class="my-3 opacity-25">

        {{-- Jenis Kerjasama --}}
        <div class="mb-4">
            <h5 class="fw-semibold mb-2">
                <i class="bi bi-diagram-3 me-2 text-primary"></i>Jenis Kerjasama
            </h5>
            <p class="fs-6 text-dark ps-1">
                {{ $proposal->cooperation_type ?? '— Tidak Diketahui —' }}
            </p>
        </div>

        {{-- Ringkasan Proposal --}}
        <div class="mb-4">
            <h5 class="fw-semibold mb-2"><i class="bi bi-file-text me-2 text-primary"></i>Ringkasan Proposal</h5>
            <p class="text-dark lh-lg">{{ $proposal->proposal_summary }}</p>
        </div>

        {{-- Dokumen --}}
        @if($proposal->document_path)
        <div class="mt-4">
            <h6 class="fw-semibold mb-2"><i class="bi bi-file-earmark-pdf me-2 text-danger"></i>Dokumen Proposal</h6>
            <a href="{{ asset('storage/'.$proposal->document_path) }}" target="_blank"
               class="btn btn-gradient px-4 py-2 rounded-pill">
                <i class="bi bi-download me-1"></i> Unduh Dokumen
            </a>
        </div>
        @endif

        {{-- Footer --}}
        <div class="mt-5 text-muted small">
            <i class="bi bi-clock-history me-1"></i> Diperbarui {{ $proposal->updated_at->diffForHumans() }}
        </div>
    </div>
</div>

{{-- Custom CSS --}}
<style>
.glass-card {
    background: rgba(255, 255, 255, 0.88);
    backdrop-filter: blur(14px);
    border: 1px solid rgba(220, 220, 220, 0.3);
    transition: all 0.3s ease-in-out;
}
.glass-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 24px rgba(0,0,0,0.08);
}
.text-gradient {
    background: linear-gradient(90deg, #4e54c8, #8f94fb);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}
.btn-gradient {
    background: linear-gradient(90deg, #007bff, #6610f2);
    color: #fff !important;
    font-weight: 600;
    border: none;
    transition: all 0.3s ease-in-out;
}
.btn-gradient:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 18px rgba(0, 114, 255, 0.3);
}
.animate__fadeIn { animation-duration: 0.6s; }
p { font-size: 15px; color: #333; }
</style>
@endsection
