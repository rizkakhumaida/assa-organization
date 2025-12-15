@extends('layouts.app')

@section('title', 'Ajukan Prestasi')

@section('content')
<style>
    .category-box {
        border: 1px solid #e5e7eb;
        padding: 1.2rem;
        border-radius: 0.8rem;
        background: #f8fafc;
        transition: .3s;
        height: 100%;
    }
    .category-box:hover {
        background: #eef2ff;
        transform: translateY(-3px);
    }
    .icon-circle {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: #e0e7ff;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #1d4ed8;
    }
    .input-label {
        font-weight: 600;
        margin-bottom: 6px;
    }
    .input-field {
        border: 1px solid #d1d5db;
        padding: .75rem 1rem;
        border-radius: .6rem;
        width: 100%;
    }
    .upload-box {
        border: 2px dashed #d1d5db;
        padding: 1.8rem;
        text-align: center;
        border-radius: 1rem;
        background: #fafafa;
        cursor: pointer;
    }
    .btn-submit {
        background: linear-gradient(135deg,#1E3A8A,#3B82F6);
        padding: .9rem;
        width: 100%;
        border-radius: .7rem;
        color: white;
        font-weight: bold;
        border: none;
    }
</style>

<div class="container py-4">

    {{-- ============================= --}}
    {{--       HEADER HALAMAN         --}}
    {{-- ============================= --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0 d-flex align-items-center gap-2">
            <span class="material-icons-outlined">emoji_events</span>
            Ajukan Prestasi
        </h3>

        <a href="{{ route('anggota.achievements.index') }}" class="btn btn-outline-secondary">
            Kembali
        </a>
    </div>

    {{-- ============================= --}}
    {{--     KATEGORI PRESTASI         --}}
    {{-- ============================= --}}
    <h5 class="fw-bold mb-3">Kategori Prestasi</h5>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="category-box">
                <div class="icon-circle mb-2"><i class="fas fa-book"></i></div>
                <h6 class="fw-bold mb-1">Prestasi Akademik</h6>
                <p class="text-muted small">Olimpiade, kompetisi sains, penelitian, karya tulis ilmiah</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="category-box">
                <div class="icon-circle mb-2"><i class="fas fa-paint-brush"></i></div>
                <h6 class="fw-bold mb-1">Seni & Kreativitas</h6>
                <p class="text-muted small">Desain, musik, tari, fotografi, film, dan lainnya</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="category-box">
                <div class="icon-circle mb-2"><i class="fas fa-running"></i></div>
                <h6 class="fw-bold mb-1">Olahraga</h6>
                <p class="text-muted small">Kompetisi olahraga individu maupun beregu</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="category-box">
                <div class="icon-circle mb-2"><i class="fas fa-users"></i></div>
                <h6 class="fw-bold mb-1">Organisasi</h6>
                <p class="text-muted small">Kepemimpinan, volunteer, sosial, dan kepengurusan</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="category-box">
                <div class="icon-circle mb-2"><i class="fas fa-lightbulb"></i></div>
                <h6 class="fw-bold mb-1">Non-Akademik</h6>
                <p class="text-muted small">Kewirausahaan, inovasi, startup, dan teknologi</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="category-box">
                <div class="icon-circle mb-2"><i class="fas fa-layer-group"></i></div>
                <h6 class="fw-bold mb-1">Lainnya</h6>
                <p class="text-muted small">Prestasi yang tidak termasuk kategori di atas</p>
            </div>
        </div>
    </div>

    {{-- ============================= --}}
    {{--       FORM AJUKAN PRESTASI    --}}
    {{-- ============================= --}}
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">

            <form action="{{ route('anggota.achievements.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row g-3">

                    {{-- Judul --}}
                    <div class="col-md-6">
                        <label class="input-label">Judul Prestasi *</label>
                        <input type="text" name="judul" class="input-field" placeholder="Contoh: Juara 1 Lomba Debat Nasional" required>
                    </div>

                    {{-- Tingkat --}}
                    <div class="col-md-6">
                        <label class="input-label">Tingkat Prestasi *</label>
                        <select name="tingkat" class="input-field" required>
                            <option value="">Pilih Tingkat Prestasi</option>
                            <option value="lokal">Lokal</option>
                            <option value="kabupaten">Kabupaten</option>
                            <option value="provinsi">Provinsi</option>
                            <option value="nasional">Nasional</option>
                            <option value="internasional">Internasional</option>
                        </select>
                    </div>

                    {{-- Deskripsi --}}
                    <div class="col-12">
                        <label class="input-label">Deskripsi Prestasi *</label>
                        <textarea name="deskripsi" class="input-field" rows="4" placeholder="Jelaskan prestasi yang diajukan..." required></textarea>
                    </div>

                    {{-- Tanggal --}}
                    <div class="col-md-6">
                        <label class="input-label">Tanggal Perolehan *</label>
                        <input type="date" name="tanggal" class="input-field" required>
                    </div>

                    {{-- Upload Sertifikat --}}
                    <div class="col-md-6">
                        <label class="input-label">Upload Sertifikat / Bukti *</label>

                        <label class="upload-box">
                            <i class="fas fa-upload fa-2x text-muted mb-2"></i>
                            <p class="text-muted small">PDF, JPG, PNG (maks. 2 MB)</p>
                            <input type="file" name="bukti" class="d-none" accept=".pdf,.jpg,.jpeg,.png" required>
                        </label>
                    </div>
                </div>

                {{-- Button --}}
                <button type="submit" class="btn-submit mt-4">
                    <i class="fas fa-paper-plane me-2"></i> Ajukan Prestasi
                </button>

            </form>

        </div>
    </div>

</div>
@endsection
