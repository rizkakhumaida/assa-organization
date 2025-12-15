@extends('layouts.app')

@section('content')
<style>
  /* ASSA Design System */
  .assa-blue { color: #1E3A8A; }
  .bg-assa-blue { background-color: #1E3A8A; }

  .gradient-bg {
    background: linear-gradient(135deg, #1E3A8A 0%, #3B82F6 100%);
  }

  .card-hover {
    transition: all 0.3s ease;
    border: 1px solid #e5e7eb;
  }

  .card-hover:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
  }

  .rounded-2xl { border-radius: 1rem; }
  .rounded-xl { border-radius: 0.75rem; }
  .shadow-lg { box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); }

  .form-card {
    background: rgba(255,255,255,0.95);
    border-radius: 1rem;
    padding: 2rem 2.5rem;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    border: 1px solid #e5e7eb;
  }

  .form-label {
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.5rem;
  }

  .form-control {
    border-radius: 0.5rem;
    border: 1px solid #d1d5db;
    padding: 0.75rem 1rem;
    transition: all 0.3s ease;
  }

  .form-control:focus {
    border-color: #1E3A8A;
    box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
  }

  .btn-assa {
    background: linear-gradient(135deg, #1E3A8A 0%, #3B82F6 100%);
    border: none;
    color: white;
    font-weight: 600;
    border-radius: 0.5rem;
    padding: 0.75rem 1.5rem;
    transition: all 0.3s ease;
  }

  .btn-assa:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(30, 58, 138, 0.4);
    color: white;
  }

  .btn-cancel {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
    border: none;
    border-radius: 0.5rem;
    padding: 0.75rem 1.5rem;
    font-weight: 600;
    transition: all 0.3s ease;
  }

  .btn-cancel:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
    color: white;
  }
</style>

<div class="container-fluid p-0">
  {{-- Header Section --}}
  <div class="row mb-4">
    <div class="col-12">
      <div class="gradient-bg text-white rounded-2xl p-4 card-hover">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
          <div class="text-center text-md-start mb-3 mb-md-0">
            <h2 class="fw-bold mb-2 fs-2">📝 Tambah Kegiatan Baru</h2>
            <p class="mb-0 fs-5" style="color: #bfdbfe;">Isi formulir berikut untuk menambahkan kegiatan baru ASSA Organization</p>
          </div>
          <a href="{{ route('admin.activities.index') }}" class="btn-cancel">
            <i class="fas fa-arrow-left me-2"></i> Kembali
          </a>
        </div>
      </div>
    </div>
  </div>

  {{-- Form Section --}}
  <div class="row">
    <div class="col-12">
      <div class="form-card">
        <div class="d-flex align-items-center mb-4">
          <div class="d-flex align-items-center justify-content-center rounded-xl me-3" style="width: 40px; height: 40px; background: linear-gradient(135deg, #1E3A8A 0%, #3B82F6 100%); color: white;">
            <i class="fas fa-plus"></i>
          </div>
          <div>
            <h4 class="fw-bold mb-0 assa-blue">Form Kegiatan Baru</h4>
            <p class="text-muted text-sm mb-0">Lengkapi semua informasi kegiatan dengan detail</p>
          </div>
        </div>

        <form action="{{ route('admin.activities.store') }}" method="POST">
          @csrf

          <div class="row">
            <div class="col-12 mb-4">
              <label for="title" class="form-label">
                <i class="fas fa-tag me-2 assa-blue"></i>Judul Kegiatan
              </label>
              <input type="text" class="form-control @error('title') is-invalid @enderror"
                     id="title" name="title" value="{{ old('title') }}" required
                     placeholder="Contoh: Seminar Teknologi AI">
              @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-12 mb-4">
              <label for="location" class="form-label">
                <i class="fas fa-map-marker-alt me-2 assa-blue"></i>Lokasi
              </label>
              <input type="text" class="form-control @error('location') is-invalid @enderror"
                     id="location" name="location" value="{{ old('location') }}"
                     placeholder="Contoh: Jakarta Convention Center">
              @error('location')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-6 mb-4">
              <label for="start_at" class="form-label">
                <i class="fas fa-play me-2 assa-blue"></i>Waktu Mulai
              </label>
              <input type="datetime-local" class="form-control @error('start_at') is-invalid @enderror"
                     id="start_at" name="start_at" value="{{ old('start_at') }}" required>
              @error('start_at')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-6 mb-4">
              <label for="end_at" class="form-label">
                <i class="fas fa-stop me-2 assa-blue"></i>Waktu Selesai
              </label>
              <input type="datetime-local" class="form-control @error('end_at') is-invalid @enderror"
                     id="end_at" name="end_at" value="{{ old('end_at') }}">
              @error('end_at')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-12 mb-4">
              <label for="description" class="form-label">
                <i class="fas fa-align-left me-2 assa-blue"></i>Deskripsi
              </label>
              <textarea class="form-control @error('description') is-invalid @enderror"
                        id="description" name="description" rows="4"
                        placeholder="Tuliskan deskripsi singkat kegiatan...">{{ old('description') }}</textarea>
              @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-12 mb-4">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" value="1" id="is_published"
                       name="is_published" {{ old('is_published') ? 'checked' : '' }}>
                <label class="form-check-label fw-medium" for="is_published">
                  <i class="fas fa-eye me-2 assa-blue"></i>Publikasikan kegiatan (peserta dapat melihat dan mendaftar)
                </label>
              </div>
            </div>
          </div>

          <div class="d-flex gap-3 pt-3 border-top">
            <button type="submit" class="btn btn-assa">
              <i class="fas fa-save me-2"></i>Simpan Kegiatan
            </button>
            <a href="{{ route('admin.activities.index') }}" class="btn btn-cancel">
              <i class="fas fa-times me-2"></i>Batal
            </a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
