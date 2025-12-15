@extends('layouts.app')

@section('title', 'Setor Prestasi')

@section('content')
<style>
  .category-box {
    border: 1px solid #e5e7eb;
    padding: 1.2rem;
    border-radius: 0.8rem;
    background: #f8fafc;
    transition: .3s;
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
  .form-section {
    padding: 2rem;
    background: white;
    border-radius: 1rem;
    border: 1px solid #e5e7eb;
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
    padding: 2rem;
    text-align: center;
    border-radius: 1rem;
    background: #fafafa;
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

    {{-- Flash success --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- Error validasi global --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Terjadi kesalahan.</strong> Silakan periksa kembali formulir Anda.
        </div>
    @endif

    {{-- ========================================= --}}
    {{--            HEADER SETOR PRESTASI          --}}
    {{-- ========================================= --}}
    <div class="mb-4">
        <div style="
            background: linear-gradient(135deg, #1E3A8A, #3B82F6);
            border-radius: 1rem;
            padding: 1.6rem 2rem;
            color: white;
            display: flex;
            align-items: center;
            gap: 1rem;
        ">
            <div style="
                width: 48px;
                height: 48px;
                border-radius: 0.8rem;
                background: rgba(255,255,255,0.25);
                display: flex;
                align-items: center;
                justify-content: center;
            ">
                <i class="fas fa-medal fa-lg"></i>
            </div>

            <div>
                <h3 class="mb-1 fw-bold">Setor Prestasi</h3>
                <p class="mb-0" style="opacity:.9;">
                    Ajukan prestasi Anda untuk didokumentasikan oleh ASSA Organization
                </p>
            </div>
        </div>
    </div>

    {{-- ========================================= --}}
    {{--        KATEGORI PRESTASI YANG DAPAT       --}}
    {{-- ========================================= --}}
    <div class="mb-4">
        <h4 class="fw-bold mb-3">Kategori Prestasi yang Dapat Disetor</h4>

        <div class="row g-3">

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
                    <p class="text-muted small">Seni musik, tari, fotografi, desain, film</p>
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
                    <p class="text-muted small">Kepemimpinan, volunteering, sosial, kepengurusan</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="category-box">
                    <div class="icon-circle mb-2"><i class="fas fa-lightbulb"></i></div>
                    <h6 class="fw-bold mb-1">Non-Akademik</h6>
                    <p class="text-muted small">Kewirausahaan, startup, teknologi, inovasi</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="category-box">
                    <div class="icon-circle mb-2"><i class="fas fa-layer-group"></i></div>
                    <h6 class="fw-bold mb-1">Lainnya</h6>
                    <p class="text-muted small">Prestasi lain yang tidak masuk kategori di atas</p>
                </div>
            </div>

        </div>
    </div>

    {{-- ========================================= --}}
    {{--        FORM PENYETORAN PRESTASI           --}}
    {{-- ========================================= --}}
    <h4 class="fw-bold mt-5 mb-3">Formulir Penyetoran Prestasi</h4>

    <form action="{{ route('anggota.achievements.store') }}"
          method="POST"
          enctype="multipart/form-data"
          class="form-section">

        @csrf

        <div class="row g-3">

            {{-- Nama Lengkap --}}
            <div class="col-md-6">
                <label class="input-label">Nama Lengkap *</label>
                <input
                    type="text"
                    name="fullName"
                    class="input-field @error('fullName') is-invalid @enderror"
                    value="{{ old('fullName') }}"
                    required>
                @error('fullName')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- Asal Sekolah --}}
            <div class="col-md-6">
                <label class="input-label">Asal Sekolah / Universitas *</label>
                <input
                    type="text"
                    name="school"
                    class="input-field @error('school') is-invalid @enderror"
                    value="{{ old('school') }}"
                    required>
                @error('school')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- NIM --}}
            <div class="col-md-4">
                <label class="input-label">NIM / NISN</label>
                <input
                    type="text"
                    name="nim"
                    class="input-field @error('nim') is-invalid @enderror"
                    value="{{ old('nim') }}">
                @error('nim')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- Email --}}
            <div class="col-md-4">
                <label class="input-label">Email *</label>
                <input
                    type="email"
                    name="email"
                    class="input-field @error('email') is-invalid @enderror"
                    value="{{ old('email') }}"
                    required>
                @error('email')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- Nomor HP --}}
            <div class="col-md-4">
                <label class="input-label">Nomor HP *</label>
                <input
                    type="text"
                    name="phone"
                    class="input-field @error('phone') is-invalid @enderror"
                    value="{{ old('phone') }}"
                    required>
                @error('phone')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- Nama Prestasi --}}
            <div class="col-12">
                <label class="input-label">Nama Prestasi *</label>
                <input
                    type="text"
                    name="title"
                    class="input-field @error('title') is-invalid @enderror"
                    value="{{ old('title') }}"
                    required>
                @error('title')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- Tingkat --}}
            <div class="col-md-6">
                <label class="input-label">Tingkat Prestasi *</label>
                <select
                    name="level"
                    class="input-field @error('level') is-invalid @enderror"
                    required>
                    <option value="">Pilih Tingkat Prestasi</option>
                    <option value="internasional" {{ old('level')=='internasional' ? 'selected' : '' }}>Internasional</option>
                    <option value="nasional" {{ old('level')=='nasional' ? 'selected' : '' }}>Nasional</option>
                    <option value="provinsi" {{ old('level')=='provinsi' ? 'selected' : '' }}>Provinsi</option>
                    <option value="kampus" {{ old('level')=='kampus' ? 'selected' : '' }}>Kampus</option>
                </select>
                @error('level')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- Kategori --}}
            <div class="col-md-6">
                <label class="input-label">Kategori Prestasi *</label>
                <select
                    name="category"
                    class="input-field @error('category') is-invalid @enderror"
                    required>
                    <option value="">Pilih Kategori Prestasi</option>
                    <option value="Akademik" {{ old('category')=='Akademik' ? 'selected' : '' }}>Akademik</option>
                    <option value="Seni & Kreativitas" {{ old('category')=='Seni & Kreativitas' ? 'selected' : '' }}>Seni & Kreativitas</option>
                    <option value="Olahraga" {{ old('category')=='Olahraga' ? 'selected' : '' }}>Olahraga</option>
                    <option value="Organisasi" {{ old('category')=='Organisasi' ? 'selected' : '' }}>Organisasi</option>
                    <option value="Non-Akademik" {{ old('category')=='Non-Akademik' ? 'selected' : '' }}>Non-Akademik</option>
                    <option value="Lainnya" {{ old('category')=='Lainnya' ? 'selected' : '' }}>Lainnya</option>
                </select>
                @error('category')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- Tanggal --}}
            <div class="col-md-6">
                <label class="input-label">Tahun / Tanggal Prestasi *</label>
                <input
                    type="date"
                    name="date"
                    class="input-field @error('date') is-invalid @enderror"
                    value="{{ old('date') }}"
                    required>
                @error('date')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- Deskripsi --}}
            <div class="col-md-6">
                <label class="input-label">Deskripsi Singkat Prestasi *</label>
                <textarea
                    name="description"
                    class="input-field @error('description') is-invalid @enderror"
                    rows="3"
                    required>{{ old('description') }}</textarea>
                @error('description')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- Upload Sertifikat --}}
            <div class="col-md-6">
                <label class="input-label">Upload Sertifikat / Piagam *</label>
                <div class="upload-box @error('certificate') border-danger @enderror">
                    <i class="fas fa-upload fa-2x text-muted mb-2"></i>
                    <p class="text-muted small">PDF, JPG, PNG (Max 10MB)</p>
                    <input
                        type="file"
                        name="certificate"
                        class="form-control mt-2"
                        required>
                </div>
                @error('certificate')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- Upload Foto --}}
            <div class="col-md-6">
                <label class="input-label">Upload Foto (Opsional)</label>
                <div class="upload-box @error('photo') border-danger @enderror">
                    <i class="fas fa-camera fa-2x text-muted mb-2"></i>
                    <p class="text-muted small">JPG, PNG (Max 10MB)</p>
                    <input
                        type="file"
                        name="photo"
                        class="form-control mt-2">
                </div>
                @error('photo')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

        </div>

        {{-- Submit --}}
        <button class="btn-submit mt-4">
            <i class="fas fa-paper-plane me-2"></i> Setor Prestasi
        </button>

    </form>
</div>

@endsection
