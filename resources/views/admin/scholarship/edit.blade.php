@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h3 class="fw-bold text-primary mb-4">✏️ Edit Pengajuan Beasiswa</h3>

    <form action="{{ route('admin.scholarships.update', $scholarship->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Nama Pendaftar --}}
        <div class="mb-3">
            <label for="nama_pendaftar" class="form-label">Nama Pendaftar</label>
            <input type="text" name="nama_pendaftar" class="form-control" value="{{ $scholarship->nama_pendaftar }}" required>
        </div>

        {{-- Campus --}}
        <div class="mb-3">
            <label for="campus" class="form-label">Campus</label>
            <input type="text" name="campus" class="form-control" value="{{ $scholarship->campus }}" required>
        </div>

        {{-- Program Studi --}}
        <div class="mb-3">
            <label for="program_studi" class="form-label">Program Studi</label>
            <textarea name="program_studi" class="form-control" rows="4" required>{{ $scholarship->program_studi }}</textarea>
        </div>

        {{-- Dokumen --}}
        <div class="mb-3">
            <label class="form-label">Dokumen (PDF / Gambar)</label>
            @if($scholarship->dokumen_pendukung)
                <p>Dokumen saat ini: <a href="{{ Storage::url($scholarship->dokumen_pendukung) }}" target="_blank">Lihat</a></p>
            @endif
            <input type="file" name="dokumen_pendukung" class="form-control">
        </div>

        {{-- Status --}}
        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-control">
                <option value="pending" {{ $scholarship->status == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ $scholarship->status == 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ $scholarship->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                <option value="on hold" {{ $scholarship->status == 'on hold' ? 'selected' : '' }}>On Hold</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save me-1"></i> Simpan Perubahan
        </button>
        <a href="{{ route('admin.scholarships.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
