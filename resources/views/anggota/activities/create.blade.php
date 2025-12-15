@extends('layouts.app')

@section('title', 'Daftar Kegiatan')

@section('content')
@php
    use Carbon\Carbon;
@endphp

<style>
    .form-wrapper {
        background: #ffffff;
        border-radius: 1.5rem;
        padding: 2rem;
        border: 1px solid #e5e7eb;
        box-shadow: 0 4px 18px rgba(0,0,0,0.06);
        max-width: 780px;
        margin: 0 auto;
    }

    .info-box {
        background: #f3f6fa;
        padding: 1.25rem;
        border-radius: 1rem;
        border-left: 4px solid #1E3A8A;
        margin-bottom: 1.5rem;
    }

    .form-label {
        font-weight: 600;
        color: #1e3a8a;
    }

    .form-control {
        border: 1px solid #d1d5db;
        padding: 0.8rem 1rem;
        border-radius: 0.75rem;
        transition: .2s;
    }
    .form-control:focus {
        border-color: #1E3A8A;
        box-shadow: 0 0 0 4px rgba(30, 58, 138, 0.15);
    }

    .btn-submit {
        background: linear-gradient(135deg, #10b981, #34d399);
        color: white;
        font-weight: 600;
        padding: 0.9rem 1.7rem;
        border-radius: 0.75rem;
        border: none;
        transition: .3s;
    }
    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 22px rgba(16,185,129,0.4);
    }

    .btn-back {
        background: white;
        border: 1px solid #d1d5db;
        padding: 0.85rem 1.7rem;
        border-radius: 0.75rem;
        font-weight: 600;
        color: #6b7280;
    }
    .btn-back:hover {
        background: #f3f4f6;
        color: #1E3A8A;
    }
</style>

<div class="container py-4">

    <h2 class="fw-bold assa-blue mb-4 text-center">
        Form Pendaftaran Kegiatan
    </h2>

    <div class="form-wrapper">

        <p class="text-muted mb-3">Isi formulir berikut untuk mendaftar kegiatan:</p>

        <div class="info-box mb-4">
            <p><strong>Nama Kegiatan:</strong> {{ $activity->title }}</p>
            <p><strong>Tanggal:</strong>
                {{ $activity->start_at ? Carbon::parse($activity->start_at)->translatedFormat('d F Y') : 'TBA' }}
            </p>
            <p><strong>Lokasi:</strong> {{ $activity->location ?? 'TBA' }}</p>
        </div>

        {{-- FORM PENDAFTARAN --}}
        <form action="{{ route('anggota.activities.register', $activity->id) }}" method="POST">
            @csrf

            {{-- Nama --}}
            <div class="mb-3">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" class="form-control shadow-sm"
                    value="{{ auth()->user()->name }}" readonly>
            </div>

            {{-- Email --}}
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control shadow-sm"
                    value="{{ auth()->user()->email }}" readonly>
            </div>

            {{-- Catatan --}}
            <div class="mb-4">
                <label class="form-label">Catatan Tambahan (Opsional)</label>
                <textarea name="notes" class="form-control shadow-sm" rows="4"
                    placeholder="Tulis catatan untuk panitia jika diperlukan"></textarea>
            </div>

            {{-- Actions --}}
            <div class="d-flex justify-content-between align-items-center">

                <a href="{{ route('anggota.activities.show', $activity->id) }}" class="btn-back">
                    <i class="fas fa-arrow-left me-2"></i> Kembali
                </a>

                <button type="submit" class="btn-submit">
                    <i class="fas fa-check me-2"></i> Daftar Sekarang
                </button>

            </div>
        </form>

    </div>
</div>

@endsection
