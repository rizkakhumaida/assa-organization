@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h3 class="fw-bold text-gradient d-flex align-items-center gap-2 mb-0">
            <i class="bi bi-pencil-square"></i>
            Edit Pengajuan Kerjasama
        </h3>
        <a href="{{ route('admin.partnerships.index') }}" class="btn btn-outline-secondary rounded-pill px-3">
            <i class="bi bi-arrow-left-circle me-1"></i> Kembali
        </a>
    </div>

    <div class="card shadow-lg border-0 rounded-4 glass-card p-4 p-md-5">
        <form method="POST" action="{{ route('admin.partnerships.update', $proposal->id) }}">
            @csrf
            @method('PUT')

            {{-- Nama Instansi --}}
            <div class="mb-4">
                <label class="form-label fw-semibold text-secondary">
                    <i class="bi bi-building me-2 text-primary"></i> Nama Instansi
                </label>
                <input type="text" class="form-control form-control-lg rounded-3 bg-light border-0 shadow-sm"
                    value="{{ $proposal->organization_name }}" readonly>
            </div>

            {{-- Jenis Kerjasama (PAKAI cooperation_type SESUAI INDEX) --}}
            <div class="mb-4">
                <label class="form-label fw-semibold text-secondary">
                    <i class="bi bi-diagram-3 me-2 text-primary"></i> Jenis Kerjasama
                </label>

                @php
                    $jenisList = ['Beasiswa','Sponsorship','Kegiatan','Seminar','Event','Penelitian','Magang','Lainnya'];
                    $selectedJenis = old('cooperation_type', $proposal->cooperation_type);
                @endphp

                <select name="cooperation_type" class="form-select form-select-lg rounded-3 shadow-sm" required>
                    @foreach ($jenisList as $j)
                        <option value="{{ $j }}" {{ $selectedJenis === $j ? 'selected' : '' }}>
                            {{ $j }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Status --}}
            <div class="mb-4">
                <label class="form-label fw-semibold text-secondary">
                    <i class="bi bi-flag me-2 text-primary"></i> Status
                </label>
                <select name="status" class="form-select form-select-lg rounded-3 shadow-sm">
                    @foreach(['submitted','review','pending','approved','rejected','onhold'] as $s)
                        <option value="{{ $s }}" {{ $proposal->status == $s ? 'selected' : '' }}>
                            {{ ucfirst($s) }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Catatan --}}
            <div class="mb-4">
                <label class="form-label fw-semibold text-secondary">
                    <i class="bi bi-journal-text me-2 text-primary"></i> Catatan
                </label>
                <textarea name="notes" class="form-control form-control-lg rounded-3 shadow-sm" rows="4">{{ old('notes', $proposal->notes) }}</textarea>
            </div>

            {{-- Tombol Aksi --}}
            <div class="d-flex justify-content-end gap-3 mt-4">
                <button type="submit" class="btn btn-primary btn-lg rounded-pill px-4 shadow-sm hover-glow">
                    <i class="bi bi-save me-2"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Styling Tambahan --}}
<style>
    body {
        background: linear-gradient(135deg, #eef2f3 0%, #d9e4f5 100%);
    }
    .text-gradient {
        background: linear-gradient(90deg, #0d6efd, #6610f2);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .glass-card {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(12px);
        transition: all 0.3s ease-in-out;
    }
    .glass-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    }
    .hover-glow {
        transition: 0.3s ease;
    }
    .hover-glow:hover {
        box-shadow: 0 0 12px rgba(13, 110, 253, 0.5);
    }
</style>
@endsection
