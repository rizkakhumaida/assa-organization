@extends('layouts.app')

@section('content')
<style>
body{
  background:linear-gradient(135deg,#e3f2fd 0%,#f8f9ff 100%);
  font-family:'Poppins',sans-serif;
}

/* ===== Back Button Wrapper ===== */
.back-wrapper{
  margin-bottom: 1.75rem; /* JEDA dengan card */
}
.btn-back{
  display:inline-flex;
  align-items:center;
  gap:.4rem;
  padding:.55rem 1.1rem;
  border-radius:999px;
  background:linear-gradient(135deg,#bbdefb,#90caf9);
  color:#0d47a1;
  font-weight:600;
  border:none;
  transition:.25s;
}
.btn-back:hover{
  background:#1e88e5;
  color:#fff;
  transform:translateY(-2px);
  box-shadow:0 6px 16px rgba(30,136,229,.35);
}

/* ===== Glass Card ===== */
.glass-card{
  background:rgba(255,255,255,.75);
  backdrop-filter:blur(14px);
  border-radius:26px;
  padding:2.3rem 2.5rem;
  border:1px solid rgba(255,255,255,.55);
  box-shadow:0 14px 40px rgba(0,0,0,.08);
}

/* ===== Title ===== */
.title-detail{
  font-size:2rem;
  font-weight:800;
  color:#0d47a1;
}

/* ===== Badge Status ===== */
.badge-status{
  font-size:.75rem;
  padding:.4rem .9rem;
  border-radius:999px;
  font-weight:700;
}
.badge-status.pending{background:#FFECB3;color:#8D6E63;}
.badge-status.verified{background:#C8E6C9;color:#256029;}
.badge-status.rejected{background:#FFCDD2;color:#B71C1C;}

/* ===== Info Box ===== */
.info-box{
  background:#fff;
  border-radius:16px;
  padding:1rem 1.25rem;
  box-shadow:0 6px 18px rgba(0,0,0,.06);
  height:100%;
}
.info-label{
  font-size:.75rem;
  font-weight:700;
  color:#64748b;
  text-transform:uppercase;
}
.info-value{
  font-weight:600;
  margin-top:.15rem;
}

/* ===== Certificate ===== */
.certificate-box{
  border:2px dashed #cfd8dc;
  border-radius:18px;
  padding:1.5rem;
  text-align:center;
  background:rgba(255,255,255,.6);
  transition:.25s;
}
.certificate-box:hover{
  border-color:#64b5f6;
  background:#fff;
}
.certificate-box img{
  max-height:260px;
  border-radius:14px;
  transition:.3s;
}
.certificate-box img:hover{
  transform:scale(1.03);
  box-shadow:0 12px 28px rgba(0,0,0,.15);
}
</style>

<div class="container py-4">

  {{-- ===== BACK BUTTON (ADA JEDA) ===== --}}
  <div class="back-wrapper">
    <a href="{{ route('admin.achievements.index') }}" class="btn-back shadow-sm">
      <i class="bi bi-arrow-left-circle"></i> Kembali
    </a>
  </div>

  {{-- ===== MAIN CARD ===== --}}
  <div class="glass-card">

    {{-- Header --}}
    <div class="mb-4">
      <h2 class="title-detail mb-1">🏆 {{ $achievement->prestasi }}</h2>
      <div class="text-muted d-flex flex-wrap gap-2 align-items-center">
        <span><i class="bi bi-person-circle me-1"></i>{{ $achievement->anggota ?? '-' }}</span>
        <span>•</span>
        <span><i class="bi bi-calendar3 me-1"></i>{{ $achievement->tahun?->format('d M Y') }}</span>
        <span class="badge-status {{ strtolower($achievement->status ?? 'pending') }}">
          {{ ucfirst($achievement->status ?? 'Pending') }}
        </span>
      </div>
    </div>

    {{-- Info Grid --}}
    <div class="row g-3 mb-4">
      <div class="col-md-6 col-lg-4">
        <div class="info-box">
          <div class="info-label">Tingkat</div>
          <div class="info-value">{{ $achievement->tingkat ?? '-' }}</div>
        </div>
      </div>

      <div class="col-md-6 col-lg-4">
        <div class="info-box">
          <div class="info-label">Kategori</div>
          <div class="info-value">{{ $achievement->kategori ?? '-' }}</div>
        </div>
      </div>

      <div class="col-md-6 col-lg-4">
        <div class="info-box">
          <div class="info-label">Poin</div>
          <div class="info-value fs-5">⭐ {{ $achievement->poin ?? 0 }}</div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="info-box">
          <div class="info-label">Asal Sekolah / Universitas</div>
          <div class="info-value">{{ $achievement->asal_sekolah ?? '-' }}</div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="info-box">
          <div class="info-label">Kontak</div>
          <div class="info-value">{{ $achievement->email }} • {{ $achievement->phone }}</div>
        </div>
      </div>

      <div class="col-12">
        <div class="info-box">
          <div class="info-label">Deskripsi</div>
          <div class="info-value" style="line-height:1.7">
            {{ $achievement->deskripsi ?? '-' }}
          </div>
        </div>
      </div>
    </div>

    {{-- Sertifikat --}}
    <div class="mb-4">
      <h6 class="fw-bold text-secondary mb-3">📄 Bukti Sertifikat</h6>
      @if($achievement->sertifikat)
        <div class="certificate-box">
          <a href="{{ route('admin.achievements.download',$achievement->id) }}" class="btn btn-primary">
            <i class="bi bi-download me-1"></i> Unduh Sertifikat
          </a>
        </div>
      @else
        <div class="text-muted fst-italic">Tidak ada sertifikat.</div>
      @endif
    </div>

    {{-- Foto --}}
    <div>
      <h6 class="fw-bold text-secondary mb-3">🖼️ Foto Prestasi</h6>
      @if($achievement->certificate)
        <div class="certificate-box">
          <img src="{{ asset('storage/'.$achievement->certificate) }}" alt="Foto Prestasi">
        </div>
      @else
        <div class="text-muted fst-italic">Tidak ada foto prestasi.</div>
      @endif
    </div>

  </div>
</div>
@endsection
