@extends('layouts.app')

@section('content')
<style>
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
    .card-glass:hover { transform: translateY(-4px); box-shadow: 0 10px 40px rgba(0,0,0,0.25); }
    .gradient-header {
        background: linear-gradient(90deg, #fff, #d1ffe4);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    label { color: #f8f9fa; font-weight: 600; }
    .form-control, .form-select {
        background-color: rgba(255, 255, 255, 0.95);
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
        color: white; border: none;
        padding: 0.75rem 2rem;
        font-weight: 600; border-radius: 10px;
        transition: all 0.3s ease;
    }
    .btn-gradient:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.25);
        background: linear-gradient(90deg, #4e73df, #1cc88a);
        color: #fff;
    }
    .text-muted { color: rgba(255,255,255,0.85) !important; }
</style>

<div class="container">
    <div class="card-glass">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            <div>
                <h2 class="gradient-header fw-bold mb-1">
                    <i class="bi bi-trophy-fill me-2"></i>Tambah Prestasi Peserta
                </h2>
                <p class="text-muted mb-0">Isi data prestasi peserta sesuai kolom database.</p>
            </div>
            <a href="{{ route('admin.achievements.index') }}" class="btn btn-light" style="border-radius:10px;">
                <i class="bi bi-arrow-left-circle me-1"></i> Kembali
            </a>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">
                <div class="fw-semibold mb-1">Ada kesalahan input:</div>
                <ul class="mb-0">
                    @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.achievements.store') }}" enctype="multipart/form-data" class="row g-4">
            @csrf

            {{-- Identitas Peserta --}}
            <div class="col-12">
                <div class="fw-semibold text-white mb-2">Identitas Peserta</div>
            </div>

            <div class="col-md-6">
                <label>Nama Peserta</label>
                <input type="text" name="peserta" class="form-control"
                       value="{{ old('peserta') }}" placeholder="Contoh: Andi Setiawan" required>
            </div>

            <div class="col-md-6">
                <label>Asal Sekolah/Universitas</label>
                <input type="text" name="asal_sekolah" class="form-control"
                       value="{{ old('asal_sekolah') }}" placeholder="Contoh: Universitas X">
            </div>

            <div class="col-md-4">
                <label>NIM / NISN</label>
                <input type="text" name="nim" class="form-control"
                       value="{{ old('nim') }}" placeholder="Contoh: 2200012345">
            </div>

            <div class="col-md-4">
                <label>Email</label>
                <input type="email" name="email" class="form-control"
                       value="{{ old('email') }}" placeholder="Contoh: andi@mail.com">
            </div>

            <div class="col-md-4">
                <label>No. HP</label>
                <input type="text" name="phone" class="form-control"
                       value="{{ old('phone') }}" placeholder="Contoh: 08xxxxxxxxxx">
            </div>

            {{-- Detail Prestasi --}}
            <div class="col-12">
                <div class="fw-semibold text-white mb-2">Detail Prestasi</div>
            </div>

            <div class="col-md-6">
                <label>Nama Prestasi</label>
                <input type="text" name="prestasi" class="form-control"
                       value="{{ old('prestasi') }}" placeholder="Contoh: Juara 1 Lomba Cerdas Cermat" required>
            </div>

            <div class="col-md-3">
                <label>Tingkat</label>
                <select name="tingkat" class="form-select" required>
                    <option value="">-- Pilih Tingkat --</option>
                    @foreach(['Kabupaten','Provinsi','Nasional','Internasional'] as $t)
                        <option value="{{ $t }}" {{ old('tingkat')===$t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label>Kategori</label>
                <select name="kategori" class="form-select" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach(['Akademik','Non Akademik','Seni','Olahraga','Lainnya'] as $k)
                        <option value="{{ $k }}" {{ old('kategori')===$k ? 'selected' : '' }}>{{ $k }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label>Tanggal Prestasi</label>
                <input type="date" name="tahun" class="form-control"
                       value="{{ old('tahun') }}" required>
                <small class="text-muted">Field DB: <b>tahun</b> bertipe <b>date</b>.</small>
            </div>

            <div class="col-md-4">
                <label>Poin</label>
                <input type="number" name="poin" class="form-control"
                       value="{{ old('poin') }}" placeholder="Contoh: 50">
            </div>

            <div class="col-md-4">
                <label>Status</label>
                <select name="status" class="form-select" required>
                    @foreach(['Pending','Verified','Rejected'] as $st)
                        <option value="{{ $st }}" {{ old('status','Pending')===$st ? 'selected' : '' }}>
                            {{ $st }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-12">
                <label>Deskripsi</label>
                <textarea name="deskripsi" rows="4" class="form-control"
                          placeholder="Tuliskan deskripsi singkat prestasi...">{{ old('deskripsi') }}</textarea>
            </div>

            {{-- Upload --}}
            <div class="col-md-6">
                <label>Bukti Sertifikat (pdf/jpg/png)</label>
                <input type="file" name="sertifikat" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                <small class="text-muted">Field DB: <b>sertifikat</b> (path file).</small>
            </div>

            <div class="col-md-6">
                <label>Foto Prestasi (opsional) (jpg/png/webp)</label>
                <input type="file" name="certificate" class="form-control" accept=".jpg,.jpeg,.png,.webp">
                <small class="text-muted">Field DB: <b>certificate</b> (path foto).</small>
            </div>

            <div class="col-12 d-flex justify-content-end gap-2 mt-2">
                <a href="{{ route('admin.achievements.index') }}" class="btn btn-outline-light" style="border-radius:10px;">
                    Batal
                </a>
                <button type="submit" class="btn-gradient">
                    <i class="bi bi-save me-2"></i> Simpan Prestasi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
