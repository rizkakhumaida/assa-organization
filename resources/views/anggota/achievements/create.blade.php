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
.page-header{
  background:linear-gradient(135deg,#1e3a8a,#3b82f6);
  color:#fff;
  border-radius:1rem;
  padding:1.5rem 2rem;
}
.category-card{
  border:1px solid #e5e7eb;
  border-radius:1rem;
  padding:1.2rem;
  background:#f8fafc;
  transition:.3s;
  height:100%;
}
.category-card:hover{
  background:#eef2ff;
  transform:translateY(-3px);
}
.icon-circle{
  width:46px;height:46px;
  border-radius:50%;
  background:#e0e7ff;
  display:flex;
  align-items:center;
  justify-content:center;
  color:#1d4ed8;
}
.form-control,.form-select{
  border-radius:.65rem;
  padding:.65rem .9rem;
}
.upload-box{
  border:2px dashed #c7d2fe;
  border-radius:1rem;
  padding:1.25rem;
  text-align:center;
  cursor:pointer;
  background:#f8fafc;
  user-select:none;
}
.upload-box:hover{ background:#eef2ff; }
.btn-submit{
  background:linear-gradient(135deg,#1e3a8a,#3b82f6);
  border:none;
  border-radius:.8rem;
  padding:.9rem;
  font-weight:600;
  color:#fff;
}
.point-info{
  background:#ecfeff;
  color:#0369a1;
  padding:.6rem 1rem;
  border-radius:.6rem;
  display:inline-flex;
  align-items:center;
  gap:.5rem;
  font-weight:600;
}
.file-name{
  margin-top:.5rem;
  font-size:.85rem;
  color:#0f172a;
}
</style>

<div class="container py-4">

  {{-- HEADER --}}
  <div class="page-header mb-4">
    <h5 class="fw-bold mb-1">Setor Prestasi</h5>
    <small class="opacity-75">
      Ajukan prestasi Anda untuk didokumentasikan oleh ASSA Organization
    </small>
  </div>

  {{-- FORM --}}
  <div class="card border-0 shadow-sm">
    <div class="card-body p-4">

      <h6 class="fw-bold mb-3">Formulir Penyetoran Prestasi</h6>

      {{-- ✅ TAMPILKAN ERROR VALIDASI --}}
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

      <form action="{{ route('anggota.achievements.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row g-3">

          {{-- IDENTITAS --}}
          <div class="col-md-6">
            <label class="form-label">Nama Lengkap *</label>
            <input type="text" name="fullName" class="form-control"
                   value="{{ auth()->user()->name }}" readonly>
          </div>

          <div class="col-md-6">
            <label class="form-label">Asal Sekolah / Universitas *</label>
            <input type="text" name="school" class="form-control" required value="{{ old('school') }}">
          </div>

          <div class="col-md-4">
            <label class="form-label">NIM / NISN</label>
            <input type="text" name="nim" class="form-control" maxlength="50" value="{{ old('nim') }}">
          </div>

          <div class="col-md-4">
            <label class="form-label">Email *</label>
            <input type="email" name="email" class="form-control"
                   value="{{ auth()->user()->email }}" readonly>
          </div>

          <div class="col-md-4">
            <label class="form-label">Nomor HP *</label>
            <input type="text" name="phone" class="form-control" required value="{{ old('phone') }}">
          </div>

          {{-- PRESTASI --}}
          <div class="col-12">
            <label class="form-label">Nama Prestasi *</label>
            <input type="text" name="title" class="form-control" required value="{{ old('title') }}">
          </div>

          <div class="col-md-6">
            <label class="form-label">Tingkat Prestasi *</label>
            <select name="tingkat" id="tingkatSelect" class="form-select" required>
              <option value="">Pilih Tingkat Prestasi</option>
              @foreach ($tingkatPoint as $t => $p)
                <option value="{{ $t }}" {{ old('tingkat') === $t ? 'selected' : '' }}>
                  {{ ucwords($t) }}
                </option>
              @endforeach
            </select>
          </div>

          <div class="col-md-6">
            <label class="form-label">Kategori Prestasi *</label>
            <select name="kategori" class="form-select" required>
              <option value="">Pilih Kategori</option>
              @php $oldKategori = old('kategori'); @endphp
              <option value="Akademik" {{ $oldKategori === 'Akademik' ? 'selected' : '' }}>Akademik</option>
              <option value="Seni & Kreativitas" {{ $oldKategori === 'Seni & Kreativitas' ? 'selected' : '' }}>Seni & Kreativitas</option>
              <option value="Olahraga" {{ $oldKategori === 'Olahraga' ? 'selected' : '' }}>Olahraga</option>
              <option value="Organisasi" {{ $oldKategori === 'Organisasi' ? 'selected' : '' }}>Organisasi</option>
              <option value="Non-Akademik" {{ $oldKategori === 'Non-Akademik' ? 'selected' : '' }}>Non-Akademik</option>
              <option value="Lainnya" {{ $oldKategori === 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
            </select>
          </div>

          <div class="col-md-6">
            <label class="form-label">Tanggal Prestasi *</label>
            <input type="date" name="date" class="form-control" required value="{{ old('date') }}">
          </div>

          <div class="col-md-6">
            <label class="form-label">Poin (Otomatis)</label><br>
            <span id="pointBadge" class="point-info d-none">
              <i class="fas fa-coins"></i>
              <span id="pointText">0</span> poin
            </span>
            <small class="text-muted d-block mt-2">
              Poin dihitung otomatis dari tingkat prestasi
            </small>
          </div>

          <div class="col-12">
            <label class="form-label">Deskripsi Prestasi *</label>
            <textarea name="description" rows="3" class="form-control" required>{{ old('description') }}</textarea>
          </div>

          {{-- FILE (✅ dibuat pasti terklik & tampil nama file) --}}
          <div class="col-md-6">
            <label class="form-label">Upload Sertifikat / Piagam *</label>

            <div class="upload-box w-100" onclick="document.getElementById('certificateFile').click()">
              <strong>Klik untuk upload</strong>
              <small class="d-block text-muted">PDF / JPG / PNG (maks 10 MB)</small>
              <div class="file-name" id="certificateName">Belum ada file dipilih</div>
            </div>

            <input
              type="file"
              id="certificateFile"
              name="certificate"
              class="d-none"
              required
              accept=".pdf,.jpg,.jpeg,.png"
            >
          </div>

          <div class="col-md-6">
            <label class="form-label">Upload Foto Prestasi (Opsional)</label>

            <div class="upload-box w-100" onclick="document.getElementById('photoFile').click()">
              <strong>Klik untuk upload</strong>
              <small class="d-block text-muted">JPG / PNG (maks 10 MB)</small>
              <div class="file-name" id="photoName">Belum ada file dipilih</div>
            </div>

            <input
              type="file"
              id="photoFile"
              name="photo"
              class="d-none"
              accept=".jpg,.jpeg,.png"
            >
          </div>

        </div>

        <button class="btn btn-submit w-100 mt-4" type="submit">
          Setor Prestasi
        </button>

      </form>

    </div>
  </div>

</div>

<script>
const pointMap = @json($tingkatPoint);
const tingkat = document.getElementById('tingkatSelect');
const badge = document.getElementById('pointBadge');
const text = document.getElementById('pointText');

tingkat.addEventListener('change', () => {
  const p = pointMap[tingkat.value] ?? 0;
  text.innerText = p;
  badge.classList.toggle('d-none', p === 0);
});

// ✅ tampilkan nama file yang dipilih agar tidak "terasa upload" padahal belum
const certInput = document.getElementById('certificateFile');
const certName  = document.getElementById('certificateName');

certInput.addEventListener('change', () => {
  certName.innerText = certInput.files?.[0]?.name ?? 'Belum ada file dipilih';
});

const photoInput = document.getElementById('photoFile');
const photoName  = document.getElementById('photoName');

photoInput.addEventListener('change', () => {
  photoName.innerText = photoInput.files?.[0]?.name ?? 'Belum ada file dipilih';
});

// ✅ jika halaman kembali karena error, tampilkan poin sesuai old('tingkat')
(() => {
  const selected = tingkat.value;
  const p = pointMap[selected] ?? 0;
  text.innerText = p;
  badge.classList.toggle('d-none', p === 0);
})();
</script>
@endsection
