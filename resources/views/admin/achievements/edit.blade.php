@extends('layouts.app')

@section('content')
<style>
    .card-custom {
        border-radius: 15px;
        box-shadow: 0 8px 24px rgba(0,0,0,.1);
        padding: 30px;
        background: #ffffff;
        transition: .3s ease-in-out;
    }
    .card-custom:hover { transform: translateY(-3px); }
    .form-label { font-weight: 600; }
    .btn-custom { border-radius: 10px; padding: 10px 20px; font-weight: 600; }
    .title-highlight {
        font-weight: 700;
        color: #0d6efd;
        border-left: 5px solid #0d6efd;
        padding-left: 10px;
        margin-bottom: 20px;
    }
</style>

@php
    // Normalisasi nilai lama agar tidak error validasi (kompatibel data lama)
    $currentStatus = old('status', $achievement->status);

    $mapStatus = [
        'Verified' => 'Disetujui',
        'verified' => 'Disetujui',
        'Rejected' => 'Ditolak',
        'rejected' => 'Ditolak',
    ];
    if (isset($mapStatus[$currentStatus])) {
        $currentStatus = $mapStatus[$currentStatus];
    }

    $currentTingkat = old('tingkat', $achievement->tingkat);
    // Normalisasi jika data lama pakai huruf kecil
    $mapTingkat = [
        'kabupaten' => 'Kabupaten',
        'provinsi' => 'Provinsi',
        'nasional' => 'Nasional',
        'internasional' => 'Internasional',
    ];
    if (is_string($currentTingkat) && isset($mapTingkat[strtolower($currentTingkat)])) {
        $currentTingkat = $mapTingkat[strtolower($currentTingkat)];
    }
@endphp

<div class="container mt-4">
    <h2 class="title-highlight">✍️ Edit Prestasi</h2>

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

    <div class="card-custom">
        <form action="{{ route('admin.achievements.update', $achievement->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-3">
                {{-- Identitas --}}
                <div class="col-md-6">
                    <label class="form-label">Peserta</label>
                    <input type="text" name="peserta" class="form-control"
                           value="{{ old('peserta', $achievement->peserta) }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Asal Sekolah/Universitas</label>
                    <input type="text" name="asal_sekolah" class="form-control"
                           value="{{ old('asal_sekolah', $achievement->asal_sekolah) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">NIM / NISN</label>
                    <input type="text" name="nim" class="form-control"
                           value="{{ old('nim', $achievement->nim) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control"
                           value="{{ old('email', $achievement->email) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">No. HP</label>
                    <input type="text" name="phone" class="form-control"
                           value="{{ old('phone', $achievement->phone) }}">
                </div>

                {{-- Prestasi --}}
                <div class="col-md-6">
                    <label class="form-label">Prestasi</label>
                    <input type="text" name="prestasi" class="form-control"
                           value="{{ old('prestasi', $achievement->prestasi) }}" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Tingkat</label>
                    {{-- Ganti ke select agar pasti valid sesuai controller --}}
                    <select name="tingkat" class="form-control" required>
                        @foreach(['Kabupaten','Provinsi','Nasional','Internasional'] as $t)
                            <option value="{{ $t }}" {{ $currentTingkat == $t ? 'selected' : '' }}>
                                {{ $t }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Kategori</label>
                    <input type="text" name="kategori" class="form-control"
                           value="{{ old('kategori', $achievement->kategori) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Tanggal Prestasi</label>
                    <input type="date" name="tahun" class="form-control"
                           value="{{ old('tahun', optional($achievement->tahun)->format('Y-m-d')) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Poin</label>
                    <input type="number" name="poin" class="form-control"
                           value="{{ old('poin', $achievement->poin) }}">
                    <small class="text-muted">Poin dapat dihitung otomatis dari tingkat (sesuai controller).</small>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    {{-- Status sesuai kebutuhan Anda --}}
                    <select name="status" class="form-control" required>
                        @foreach(['Pending','Disetujui','Ditolak','Ditunda'] as $st)
                            <option value="{{ $st }}" {{ $currentStatus == $st ? 'selected' : '' }}>
                                {{ $st }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" rows="4">{{ old('deskripsi', $achievement->deskripsi) }}</textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Sertifikat (Upload baru jika ingin ganti)</label>
                    <input type="file" name="sertifikat" class="form-control">
                    @if($achievement->sertifikat)
                        <small class="text-muted">File saat ini: {{ $achievement->sertifikat }}</small>
                    @endif
                </div>

                <div class="col-md-6">
                    <label class="form-label">Foto Prestasi (Upload baru jika ingin ganti)</label>
                    <input type="file" name="certificate" class="form-control">
                    @if($achievement->certificate)
                        <small class="text-muted">File saat ini: {{ $achievement->certificate }}</small>
                    @endif
                </div>
            </div>

            <div class="mt-4 d-flex justify-content-between">
                <a href="{{ route('admin.achievements.index') }}" class="btn btn-secondary btn-custom">← Kembali</a>
                <button type="submit" class="btn btn-primary btn-custom">💾 Update</button>
            </div>
        </form>
    </div>
</div>
@endsection
