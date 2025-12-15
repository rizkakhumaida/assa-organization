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
    .card-custom:hover {
        transform: translateY(-3px);
    }
    .form-label {
        font-weight: 600;
    }
    .btn-custom {
        border-radius: 10px;
        padding: 10px 20px;
        font-weight: 600;
    }
    .title-highlight {
        font-weight: 700;
        color: #0d6efd;
        border-left: 5px solid #0d6efd;
        padding-left: 10px;
        margin-bottom: 20px;
    }
</style>

<div class="container mt-4">
    <h2 class="title-highlight">✍️ Edit Prestasi</h2>

    <div class="card-custom">
        <form action="{{ route('admin.achievements.update', $achievement->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Peserta</label>
                    <input type="text" name="peserta" class="form-control" value="{{ old('peserta', $achievement->peserta) }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Prestasi</label>
                    <input type="text" name="prestasi" class="form-control" value="{{ old('prestasi', $achievement->prestasi) }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Tingkat</label>
                    <input type="text" name="tingkat" class="form-control" value="{{ old('tingkat', $achievement->tingkat) }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Kategori</label>
                    <input type="text" name="kategori" class="form-control" value="{{ old('kategori', $achievement->kategori) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Tahun</label>
                    <input type="date" name="tahun" class="form-control" value="{{ old('tahun', $achievement->tahun) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Poin</label>
                    <input type="number" name="poin" class="form-control" value="{{ old('poin', $achievement->poin) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control" required>
                        <option value="Pending" {{ $achievement->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Verified" {{ $achievement->status == 'Verified' ? 'selected' : '' }}>Verified</option>
                        <option value="Rejected" {{ $achievement->status == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>

                <div class="col-12">
                    <label class="form-label">Sertifikat (Upload baru jika ingin ganti)</label>
                    <input type="file" name="sertifikat" class="form-control">
                    @if($achievement->sertifikat)
                        <small class="text-muted">File saat ini: {{ $achievement->sertifikat }}</small>
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
