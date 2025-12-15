@extends('layouts.app')

@section('content')
<style>
    /* 🌈 Style Modern Glassmorphism */
    body {
        background: linear-gradient(135deg, #4e73df 0%, #1cc88a 100%);
        min-height: 100vh;
        padding-top: 40px;
    }

    .card-glass {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        border-radius: 20px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.2);
        padding: 2.5rem;
        transition: all 0.3s ease;
    }

    .card-glass:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.25);
    }

    .gradient-header {
        background: linear-gradient(90deg, #fff, #d1ffe4);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    label {
        color: #f8f9fa;
        font-weight: 600;
    }

    .form-control, .form-select {
        background-color: rgba(255, 255, 255, 0.9);
        border-radius: 10px;
        border: none;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
        outline: none;
        box-shadow: 0 0 0 0.25rem rgba(78, 115, 223, 0.35);
        background-color: white;
    }

    .btn-gradient {
        background: linear-gradient(90deg, #1cc88a, #4e73df);
        color: white;
        border: none;
        padding: 0.75rem 2rem;
        font-weight: 600;
        border-radius: 10px;
        transition: all 0.3s ease;
    }

    .btn-gradient:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
        background: linear-gradient(90deg, #4e73df, #1cc88a);
    }

    .text-muted {
        color: rgba(255,255,255,0.85) !important;
    }

    .fade-in {
        animation: fadeIn 0.8s ease-in-out;
    }

    @keyframes fadeIn {
        from {opacity: 0; transform: translateY(15px);}
        to {opacity: 1; transform: translateY(0);}
    }
</style>

<div class="container fade-in">
    <div class="card-glass animate__animated animate__fadeIn">
        <h2 class="gradient-header fw-bold mb-3">
            <i class="bi bi-trophy-fill me-2"></i>Tambah Prestasi Peserta
        </h2>
        <p class="text-muted mb-4">Isi data prestasi peserta secara lengkap untuk menambah catatan prestasi baru.</p>

        <form method="POST" action="{{ route('admin.achievements.store') }}" enctype="multipart/form-data" class="row g-4">
            @csrf

            {{-- Peserta --}}
            <div class="col-md-6">
                <label>Nama Peserta</label>
                <input type="text" name="peserta" class="form-control" placeholder="Contoh: Andi Setiawan" required>
            </div>

            {{-- Prestasi --}}
            <div class="col-md-6">
                <label>Nama Prestasi</label>
                <input type="text" name="prestasi" class="form-control" placeholder="Contoh: Juara 1 Lomba Cerdas Cermat" required>
            </div>

            {{-- Tingkat & Kategori --}}
            <div class="col-md-6">
                <label>Tingkat & Kategori</label>
                <select name="tingkat_kategori" class="form-select" required>
                    <option value="">-- Pilih Tingkat & Kategori --</option>
                    <option value="Kabupaten - Akademik">Kabupaten - Akademik</option>
                    <option value="Kabupaten - Non Akademik">Kabupaten - Non Akademik</option>
                    <option value="Nasional - Akademik">Nasional - Akademik</option>
                    <option value="Nasional - Non Akademik">Nasional - Non Akademik</option>
                    <option value="Internasional - Akademik">Internasional - Akademik</option>
                    <option value="Internasional - Non Akademik">Internasional - Non Akademik</option>
                </select>
            </div>

            {{-- Tahun --}}
            <div class="col-md-3">
                <label>Tahun</label>
                <input type="number" name="tahun" class="form-control" placeholder="Contoh: 2025" required>
            </div>

            {{-- Poin (Baru Ditambahkan) --}}
            <div class="col-md-3">
                <label>Poin</label>
                <input type="number" name="poin" class="form-control" placeholder="Contoh: 50" required>
            </div>

            {{-- Status --}}
            <div class="col-md-6">
                <label>Status</label>
                <select name="status" class="form-select" required>
                    <option value="pending">Pending</option>
                    <option value="verified">Verified</option>
                    <option value="rejected">Rejected</option>
                </select>
            </div>

            {{-- Sertifikat --}}
            <div class="col-md-6">
                <label>Bukti Sertifikat</label>
                <input type="file" name="sertifikat" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                <small class="text-muted">Format: JPG, PNG, PDF (maks 2MB)</small>
            </div>

            {{-- Tombol Simpan --}}
            <div class="col-12 text-end mt-4">
                <button type="submit" class="btn-gradient">
                    <i class="bi bi-save me-2"></i> Simpan Prestasi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
