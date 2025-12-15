@extends('layouts.app')

@section('content')
<style>
body {
    background: linear-gradient(135deg, #e3f2fd 0%, #f8f9ff 100%);
    font-family: 'Poppins', sans-serif;
}

.glass-card {
    backdrop-filter: blur(12px);
    background: rgba(255, 255, 255, 0.7);
    border-radius: 24px;
    padding: 2rem 2.5rem;
    box-shadow: 0 10px 35px rgba(0, 0, 0, 0.08);
    transition: 0.3s ease-in-out;
    border: 1px solid rgba(255,255,255,0.5);
}
.glass-card:hover {
    transform: translateY(-5px);
}

.title-detail {
    font-weight: 800;
    color: #0d47a1;
    font-size: 2rem;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
}

.badge-status {
    font-size: 0.85rem;
    border-radius: 30px;
    padding: 6px 14px;
    font-weight: 600;
    box-shadow: 0 3px 8px rgba(0,0,0,0.1);
}
.badge-status.pending { background: #FFECB3; color: #8D6E63; }
.badge-status.verified { background: #C8E6C9; color: #256029; }
.badge-status.rejected { background: #FFCDD2; color: #B71C1C; }

.certificate-box {
    background: rgba(255,255,255,0.6);
    border: 2px dashed #cfd8dc;
    border-radius: 18px;
    text-align: center;
    padding: 1.5rem;
    transition: 0.3s;
}
.certificate-box:hover {
    background: rgba(255,255,255,0.9);
    border-color: #64b5f6;
}

.certificate-box img {
    border-radius: 12px;
    max-height: 260px;
    object-fit: contain;
    transition: 0.3s ease-in-out;
}
.certificate-box img:hover {
    transform: scale(1.02);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

.btn-back {
    background: linear-gradient(135deg, #bbdefb, #90caf9);
    border-radius: 50px;
    color: #0d47a1;
    border: none;
    font-weight: 600;
    transition: all 0.3s ease;
    padding: 8px 20px;
}
.btn-back:hover {
    background: #1e88e5;
    color: #fff;
    transform: translateY(-2px);
}
</style>

<div class="container py-4">
    <a href="{{ route('admin.achievements.index') }}" class="btn-back mb-4 shadow-sm">
        <i class="bi bi-arrow-left-circle"></i> Kembali
    </a>

    <div class="glass-card">
        <div class="d-flex justify-content-between align-items-start flex-wrap mb-3">
            <div>
                <h2 class="title-detail mb-2">
                    🏆 {{ $achievement->prestasi }}
                </h2>
                <div class="subtitle">
                    <i class="bi bi-person-circle me-1"></i> {{ $achievement->peserta ?? 'Tidak diketahui' }}
                    &nbsp; • &nbsp;
                    <i class="bi bi-calendar3 me-1"></i> {{ $achievement->tahun ?? '-' }}
                    &nbsp; • &nbsp;
                    <span class="badge-status {{ strtolower($achievement->status) }}">
                        {{ ucfirst($achievement->status ?? 'Pending') }}
                    </span>
                </div>
            </div>
        </div>

        <hr class="my-4">

        <div>
            <h6 class="fw-bold text-secondary mb-2">Tingkat & Kategori</h6>
            <p class="text-dark" style="line-height:1.7;">
                {{ $achievement->tingkat_kategori ?? '-' }}
            </p>
        </div>

        <div class="mt-3">
            <h6 class="fw-bold text-secondary mb-2">Poin</h6>
            <p class="text-dark fs-5">
                ⭐ <strong>{{ $achievement->poin ?? '-' }}</strong>
            </p>
        </div>

        @if($achievement->sertifikat_path)
        <div class="mt-4">
            <h6 class="fw-bold text-secondary mb-3">📄 Bukti Sertifikat</h6>
            <div class="certificate-box shadow-sm">
                <a href="{{ asset('storage/' . $achievement->sertifikat_path) }}" target="_blank">
                    <img src="{{ asset('storage/' . $achievement->sertifikat_path) }}" alt="Sertifikat {{ $achievement->prestasi }}">
                </a>
                <div class="mt-2 small text-muted">
                    Klik gambar untuk melihat sertifikat ukuran penuh
                </div>
            </div>
        </div>
        @else
        <div class="mt-4 text-muted fst-italic">
            Tidak ada bukti sertifikat yang diunggah.
        </div>
        @endif
    </div>
</div>
@endsection
