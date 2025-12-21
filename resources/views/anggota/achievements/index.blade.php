@extends('layouts.app')

@section('title', 'Setor Prestasi')

@php
$tingkatPoint = [
  'internasional'     => 50,
  'nasional'          => 40,
  'provinsi'          => 30,
  'kabupaten/kota'    => 20,
  'institusi/kampus'  => 10,
];
@endphp

@section('content')
<style>
  .category-box { border: 1px solid #e5e7eb; padding: 1.2rem; border-radius: 0.8rem; background: #f8fafc; transition: .3s; }
  .category-box:hover { background: #eef2ff; transform: translateY(-3px); }
  .icon-circle { width: 48px; height: 48px; border-radius: 50%; background: #e0e7ff; display: flex; align-items: center; justify-content: center; color: #1d4ed8; }
  .form-section { padding: 2rem; background: white; border-radius: 1rem; border: 1px solid #e5e7eb; }
  .input-label { font-weight: 600; margin-bottom: 6px; }
  .input-field { border: 1px solid #d1d5db; padding: .75rem 1rem; border-radius: .6rem; width: 100%; }
  .upload-box { border: 2px dashed #d1d5db; padding: 1.5rem; text-align: center; border-radius: 1rem; background: #fafafa; }
  .btn-submit { background: linear-gradient(135deg,#1E3A8A,#3B82F6); padding: .9rem; width: 100%; border-radius: .7rem; color: white; font-weight: bold; border: none; }
  .point-info{ background:#ecfeff; color:#0369a1; padding:.6rem 1rem; border-radius:.6rem; display:inline-flex; align-items:center; gap:.5rem; font-weight:600; }
</style>

<div class="container py-4">

  {{-- ✅ TAMPILKAN ERROR VALIDASI DETAIL --}}
  @if ($errors->any())
    <div class="alert alert-danger">
      <strong>Terjadi kesalahan:</strong>
      <ul class="mb-0">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  {{-- HEADER --}}
  <div class="mb-4">
    <div style="background: linear-gradient(135deg, #1E3A8A, #3B82F6); border-radius: 1rem; padding: 1.6rem 2rem; color: white; display: flex; align-items: center; gap: 1rem;">
      <div style="width: 48px; height: 48px; border-radius: 0.8rem; background: rgba(255,255,255,0.25); display: flex; align-items: center; justify-content: center;">
        <i class="fas fa-medal fa-lg"></i>
      </div>
      <div>
        <h3 class="mb-1 fw-bold">Setor Prestasi</h3>
        <p class="mb-0" style="opacity:.9;">Ajukan prestasi Anda untuk didokumentasikan oleh ASSA Organization</p>
      </div>
    </div>
  </div>

  {{-- KATEGORI PRESTASI --}}
  <div class="mb-4">
    <h4 class="fw-bold mb-3">Kategori Prestasi yang Dapat Disetor</h4>
    <div class="row g-3">
      <div class="col-md-4"><div class="category-box"><div class="icon-circle mb-2"><i class="fas fa-book"></i></div><h6 class="fw-bold mb-1">Prestasi Akademik</h6><p class="text-muted small">Olimpiade, kompetisi sains, penelitian, karya tulis ilmiah</p></div></div>
      <div class="col-md-4"><div class="category-box"><div class="icon-circle mb-2"><i class="fas fa-paint-brush"></i></div><h6 class="fw-bold mb-1">Seni & Kreativitas</h6><p class="text-muted small">Seni musik, tari, fotografi, desain, film</p></div></div>
      <div class="col-md-4"><div class="category-box"><div class="icon-circle mb-2"><i class="fas fa-running"></i></div><h6 class="fw-bold mb-1">Olahraga</h6><p class="text-muted small">Kompetisi olahraga individu maupun beregu</p></div></div>
      <div class="col-md-4"><div class="category-box"><div class="icon-circle mb-2"><i class="fas fa-users"></i></div><h6 class="fw-bold mb-1">Organisasi</h6><p class="text-muted small">Kepemimpinan, volunteering, sosial, kepengurusan</p></div></div>
      <div class="col-md-4"><div class="category-box"><div class="icon-circle mb-2"><i class="fas fa-lightbulb"></i></div><h6 class="fw-bold mb-1">Non-Akademik</h6><p class="text-muted small">Kewirausahaan, startup, teknologi, inovasi</p></div></div>
      <div class="col-md-4"><div class="category-box"><div class="icon-circle mb-2"><i class="fas fa-layer-group"></i></div><h6 class="fw-bold mb-1">Lainnya</h6><p class="text-muted small">Prestasi lain yang tidak masuk kategori di atas</p></div></div>
    </div>
  </div>

  {{-- FORM --}}
  <h4 class="fw-bold mt-5 mb-3">Formulir Penyetoran Prestasi</h4>

  <form action="{{ route('anggota.achievements.store') }}" method="POST" enctype="multipart/form-data" class="form-section">
    @csrf

    <div class="row g-3">

      {{-- ✅ NAMA: MANUAL (TIDAK AUTO-ISI) --}}
      <div class="col-md-6">
        <label class="input-label">Nama Lengkap *</label>
        <input type="text" name="fullName" class="input-field @error('fullName') is-invalid @enderror"
               value="{{ old('fullName', '') }}" placeholder="Masukkan nama lengkap" required>
        @error('fullName')<small class="text-danger">{{ $message }}</small>@enderror
      </div>

      <div class="col-md-6">
        <label class="input-label">Asal Sekolah / Universitas *</label>
        <input type="text" name="school" class="input-field @error('school') is-invalid @enderror"
               value="{{ old('school', '') }}" required>
        @error('school')<small class="text-danger">{{ $message }}</small>@enderror
      </div>

      <div class="col-md-4">
        <label class="input-label">NIM / NISN</label>
        <input type="text" name="nim" class="input-field @error('nim') is-invalid @enderror"
               value="{{ old('nim', '') }}">
        @error('nim')<small class="text-danger">{{ $message }}</small>@enderror
      </div>

      {{-- ✅ EMAIL: MANUAL (TIDAK AUTO-ISI) --}}
      <div class="col-md-4">
        <label class="input-label">Email *</label>
        <input type="email" name="email" class="input-field @error('email') is-invalid @enderror"
               value="{{ old('email', '') }}" placeholder="email@example.com" required>
        @error('email')<small class="text-danger">{{ $message }}</small>@enderror
      </div>

      <div class="col-md-4">
        <label class="input-label">Nomor HP *</label>
        <input type="text" name="phone" class="input-field @error('phone') is-invalid @enderror"
               value="{{ old('phone', '') }}" required>
        @error('phone')<small class="text-danger">{{ $message }}</small>@enderror
      </div>

      <div class="col-12">
        <label class="input-label">Nama Prestasi *</label>
        <input type="text" name="title" class="input-field @error('title') is-invalid @enderror"
               value="{{ old('title', '') }}" required>
        @error('title')<small class="text-danger">{{ $message }}</small>@enderror
      </div>

      <div class="col-md-6">
        <label class="input-label">Tingkat Prestasi *</label>
        <select name="tingkat" id="tingkatSelect" class="input-field @error('tingkat') is-invalid @enderror" required>
          <option value="">Pilih Tingkat Prestasi</option>
          @foreach($tingkatPoint as $k=>$v)
            <option value="{{ $k }}" {{ old('tingkat')===$k ? 'selected' : '' }}>{{ ucwords($k) }}</option>
          @endforeach
        </select>
        @error('tingkat')<small class="text-danger">{{ $message }}</small>@enderror
      </div>

      <div class="col-md-6">
        <label class="input-label">Kategori Prestasi *</label>
        <select name="kategori" class="input-field @error('kategori') is-invalid @enderror" required>
          <option value="">Pilih Kategori Prestasi</option>
          @php $ok = old('kategori'); @endphp
          <option value="Akademik" {{ $ok==='Akademik'?'selected':'' }}>Akademik</option>
          <option value="Seni & Kreativitas" {{ $ok==='Seni & Kreativitas'?'selected':'' }}>Seni & Kreativitas</option>
          <option value="Olahraga" {{ $ok==='Olahraga'?'selected':'' }}>Olahraga</option>
          <option value="Organisasi" {{ $ok==='Organisasi'?'selected':'' }}>Organisasi</option>
          <option value="Non-Akademik" {{ $ok==='Non-Akademik'?'selected':'' }}>Non-Akademik</option>
          <option value="Lainnya" {{ $ok==='Lainnya'?'selected':'' }}>Lainnya</option>
        </select>
        @error('kategori')<small class="text-danger">{{ $message }}</small>@enderror
      </div>

      <div class="col-md-6">
        <label class="input-label">Tahun / Tanggal Prestasi *</label>
        <input type="date" name="date" class="input-field @error('date') is-invalid @enderror"
               value="{{ old('date', '') }}" required>
        @error('date')<small class="text-danger">{{ $message }}</small>@enderror
      </div>

      <div class="col-md-6">
        <label class="input-label">Poin (Otomatis)</label><br>
        <span id="pointBadge" class="point-info d-none">
          <i class="fas fa-coins"></i>
          <span id="pointText">0</span> poin
        </span>
        <small class="text-muted d-block mt-2">Poin dihitung otomatis dari tingkat prestasi saat disimpan.</small>
      </div>

      <div class="col-12">
        <label class="input-label">Deskripsi Singkat Prestasi *</label>
        <textarea name="description" class="input-field @error('description') is-invalid @enderror" rows="3" required>{{ old('description','') }}</textarea>
        @error('description')<small class="text-danger">{{ $message }}</small>@enderror
      </div>

      <div class="col-md-6">
        <label class="input-label">Upload Sertifikat / Piagam *</label>
        <div class="upload-box @error('certificate') border-danger @enderror">
          <p class="text-muted small mb-2">PDF, JPG, PNG (Max 10MB)</p>
          <input type="file" name="certificate" class="form-control" accept=".pdf,.jpg,.jpeg,.png" required>
        </div>
        @error('certificate')<small class="text-danger">{{ $message }}</small>@enderror
      </div>

      <div class="col-md-6">
        <label class="input-label">Upload Foto (Opsional)</label>
        <div class="upload-box @error('photo') border-danger @enderror">
          <p class="text-muted small mb-2">JPG, PNG (Max 10MB)</p>
          <input type="file" name="photo" class="form-control" accept=".jpg,.jpeg,.png">
        </div>
        @error('photo')<small class="text-danger">{{ $message }}</small>@enderror
      </div>

    </div>

    <button class="btn-submit mt-4" type="submit">
      <i class="fas fa-paper-plane me-2"></i> Setor Prestasi
    </button>

  </form>
</div>

<script>
const pointMap = @json($tingkatPoint);
const tingkat = document.getElementById('tingkatSelect');
const badge = document.getElementById('pointBadge');
const text  = document.getElementById('pointText');

function updatePoint(){
  const p = pointMap[tingkat.value] ?? 0;
  text.innerText = p;
  badge.classList.toggle('d-none', p === 0);
}
tingkat.addEventListener('change', updatePoint);
updatePoint();
</script>
@endsection
