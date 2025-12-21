@extends('layouts.app')

@section('title', 'Detail Prestasi')

@section('content')

<style>
    .header-gradient {
        background: linear-gradient(135deg, #1E3A8A, #3B82F6);
        color: white;
        border-radius: 1rem;
        padding: 2rem;
    }
    .info-label {
        font-size: .85rem;
        color: #6b7280;
        margin-bottom: 2px;
        font-weight: 600;
    }
    .info-value {
        font-weight: 700;
        color: #111827;
        font-size: 1rem;
    }
    .badge-custom {
        padding: .4rem .8rem;
        border-radius: .5rem;
        font-size: .85rem;
        font-weight: 600;
        display: inline-block;
    }
    .badge-level {
        background: #d1fae5;
        color: #065f46;
    }
    .badge-category {
        background: #dbeafe;
        color: #1e40af;
    }
    .proof-box {
        background: #f9fafb;
        padding: 1.5rem;
        border-radius: 1rem;
        border: 1px solid #e5e7eb;
    }
    .btn-assa {
        background: linear-gradient(135deg,#1E3A8A,#3B82F6);
        border-radius: .6rem;
        padding: .75rem 1.5rem;
        border: none;
        color: white;
        font-weight: 600;
        transition: .3s;
        text-decoration: none;
    }
    .btn-assa:hover {
        transform: translateY(-2px);
        color: white;
    }
</style>

<div class="container py-4">

    {{-- HEADER --}}
    <div class="header-gradient mb-4">
        <h2 class="fw-bold mb-1">
            <i class="fas fa-medal me-2"></i> Detail Prestasi
        </h2>
        <p class="mb-0 opacity-75">
            Penyetoran Prestasi — ASSA Organization
        </p>
    </div>

    {{-- ALERT --}}
    @if(session('success'))
        <div class="alert alert-success border-start border-3 border-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- DETAIL --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-4">
            <div class="row g-4">

                {{-- INFORMASI PERSONAL --}}
                <div class="col-md-6">
                    <h5 class="fw-bold mb-3">Informasi Personal</h5>

                    <div class="mb-3">
                        <p class="info-label">Nama Lengkap</p>
                        <p class="info-value">{{ $data->peserta }}</p>
                    </div>

                    <div class="mb-3">
                        <p class="info-label">Asal Sekolah / Universitas</p>
                        <p class="info-value">{{ $data->asal_sekolah }}</p>
                    </div>

                    @if($data->nim)
                        <div class="mb-3">
                            <p class="info-label">NIM / NISN</p>
                            <p class="info-value">{{ $data->nim }}</p>
                        </div>
                    @endif

                    <div class="mb-3">
                        <p class="info-label">Email</p>
                        <p class="info-value">{{ $data->email }}</p>
                    </div>

                    <div class="mb-3">
                        <p class="info-label">Nomor Telepon</p>
                        <p class="info-value">{{ $data->phone }}</p>
                    </div>
                </div>

                {{-- DETAIL PRESTASI --}}
                <div class="col-md-6">
                    <h5 class="fw-bold mb-3">Detail Prestasi</h5>

                    <div class="mb-3">
                        <p class="info-label">Judul Prestasi</p>
                        <p class="info-value">{{ $data->prestasi }}</p>
                    </div>

                    <div class="mb-3">
                        <p class="info-label">Kategori</p>
                        <span class="badge-custom badge-category">
                            {{ $data->kategori }}
                        </span>
                    </div>

                    <div class="mb-3">
                        <p class="info-label">Tingkat</p>
                        <span class="badge-custom badge-level">
                            {{ $data->tingkat }}
                        </span>
                    </div>

                    <div class="mb-3">
                        <p class="info-label">Tanggal Pengajuan</p>
                        <p class="info-value">
                            {{ \Carbon\Carbon::parse($data->created_at)->format('d M Y, H:i') }}
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- BUKTI PRESTASI --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-4 text-center">

            <h5 class="fw-bold mb-3">Bukti Prestasi</h5>

            @php
                $ext = pathinfo($data->sertifikat, PATHINFO_EXTENSION);
            @endphp

            <div class="proof-box">

                {{-- GAMBAR --}}
                @if(in_array(strtolower($ext), ['jpg','jpeg','png']))
                    <img src="{{ route('anggota.achievements.preview', $data->id) }}"
                         class="img-fluid rounded shadow-sm"
                         style="max-height: 380px; object-fit: contain;"
                         alt="Bukti Prestasi">

                {{-- PDF --}}
                @else
                    <a href="{{ route('anggota.achievements.preview', $data->id) }}"
                       target="_blank"
                       class="btn btn-assa">
                        <i class="fas fa-file-pdf me-2"></i> Lihat File PDF
                    </a>
                @endif

            </div>

        </div>
    </div>

    {{-- AKSI --}}
    <div class="d-flex justify-content-between mt-4">

        <a href="{{ route('anggota.achievements.index') }}"
           class="btn btn-secondary px-4">
            ← Kembali
        </a>

        <a href="{{ route('anggota.achievements.download', $data->id) }}"
           class="btn btn-success px-4">
            <i class="fas fa-download me-2"></i> Download Bukti
        </a>

    </div>

</div>

@endsection
