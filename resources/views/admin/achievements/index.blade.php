@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">

  {{-- Flash message --}}
  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-4" role="alert">
      <strong>Berhasil!</strong> {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show shadow-sm rounded-4" role="alert">
      <strong>Gagal!</strong> {{ session('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  {{-- ================= HEADER ================= --}}
  <div class="page-header mb-4">
    <div class="page-header-content">
      <div class="d-flex align-items-center gap-3">
        <div class="page-icon">
          <i class="bi bi-trophy-fill"></i>
        </div>
        <div>
          <h5 class="mb-0 fw-bold text-white">Daftar Prestasi Anggota</h5>
          <div class="text-white-50 small">
            Kelola data prestasi, poin, dan sertifikat anggota
          </div>
        </div>
      </div>

      <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('admin.achievements.export.pdf') }}" class="btn btn-light btn-sm">
          <i class="bi bi-filetype-pdf me-1"></i> Export PDF
        </a>
        <a href="{{ route('admin.achievements.export.excel') }}" class="btn btn-light btn-sm">
          <i class="bi bi-filetype-xlsx me-1"></i> Export Excel
        </a>
        <a href="{{ route('admin.achievements.create') }}" class="btn btn-warning btn-sm fw-semibold">
          <i class="bi bi-plus-circle me-1"></i> Tambah Data
        </a>
      </div>
    </div>
  </div>
  {{-- ================= END HEADER ================= --}}

  {{-- ================= TOOLBAR ================= --}}
  <div class="card border-0 shadow-sm rounded-4 mb-3">
    <div class="card-body py-3">
      <div class="row g-2 align-items-center">
        <div class="col-12 col-md-3">
          <select id="statusFilter" class="form-select form-select-sm rounded-pill">
            <option value="">Semua Status</option>
            <option value="pending">Pending</option>
            <option value="verified">Verified</option>
            <option value="rejected">Rejected</option>
          </select>
        </div>

        <div class="col-12 col-md">
          <div class="input-group input-group-sm search-pill">
            <span class="input-group-text bg-transparent border-0 ps-3">
              <i class="bi bi-search"></i>
            </span>
            <input id="searchInput" type="text" class="form-control border-0 pe-3"
                   placeholder="Cari nama anggota atau prestasi...">
          </div>
        </div>

        <div class="col-12 col-md-auto text-md-end">
          <div class="small text-muted">
            @if(method_exists($achievements,'total'))
              Menampilkan {{ $achievements->count() }} dari {{ $achievements->total() }} data
            @else
              Menampilkan {{ $achievements->count() }} data
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- ================= TABLE ================= --}}
  <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="table-responsive">
      <table class="table align-middle mb-0 table-modern">
        <thead>
          <tr>
            <th class="ps-4">ANGGOTA</th>
            <th>PRESTASI</th>
            <th>TINGKAT</th>
            <th>KATEGORI</th>
            <th>TAHUN</th>
            <th class="text-center">POIN</th>
            <th>SERTIFIKAT</th>
            <th>STATUS</th>
            <th class="text-end pe-4">AKSI</th>
          </tr>
        </thead>

        <tbody id="tableBody">
          @forelse($achievements as $a)
            @php
              $status = strtolower($a->status ?? 'pending');
              $badge = match($status){
                'verified'=>'badge-status badge-verified',
                'rejected'=>'badge-status badge-rejected',
                default=>'badge-status badge-pending'
              };
              $initial = strtoupper(substr(trim($a->anggota ?? 'AG'),0,2));
            @endphp

            <tr class="row-item"
                data-status="{{ $status }}"
                data-key="{{ strtolower(($a->anggota ?? '').' '.($a->prestasi ?? '')) }}">

              <td class="ps-4">
                <div class="d-flex align-items-center gap-3">
                  <div class="avatar-circle">{{ $initial }}</div>
                  <div>
                    <div class="fw-semibold">{{ $a->anggota }}</div>
                    <div class="text-muted small">#ID {{ $a->id }}</div>
                  </div>
                </div>
              </td>

              <td class="fw-semibold">{{ $a->prestasi }}</td>
              <td><span class="chip">{{ $a->tingkat ?? '-' }}</span></td>
              <td><span class="chip">{{ $a->kategori ?? '-' }}</span></td>
              <td class="text-muted">{{ $a->tahun ? $a->tahun->format('d M Y') : '-' }}</td>
              <td class="text-center fw-bold">{{ $a->poin ?? 0 }}</td>

              <td>
                @if($a->sertifikat)
                  <a href="{{ route('admin.achievements.download',$a->id) }}"
                     class="btn btn-success btn-sm btn-soft-success">
                    <i class="bi bi-download me-1"></i> Unduh
                  </a>
                @else
                  <span class="text-muted small">Tidak ada file</span>
                @endif
              </td>

              <td><span class="{{ $badge }}">{{ ucfirst($status) }}</span></td>

              <td class="text-end pe-4">
                <div class="d-inline-flex gap-2">
                  <a href="{{ route('admin.achievements.show',$a->id) }}" class="btn btn-icon btn-view">
                    <i class="bi bi-eye"></i>
                  </a>
                  <a href="{{ route('admin.achievements.edit',$a->id) }}" class="btn btn-icon btn-edit">
                    <i class="bi bi-pencil-square"></i>
                  </a>
                  <form action="{{ route('admin.achievements.destroy',$a->id) }}" method="POST"
                        onsubmit="return confirm('Yakin ingin menghapus prestasi anggota ini?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-icon btn-delete">
                      <i class="bi bi-trash"></i>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="9" class="text-center py-5 text-muted">
                Belum ada data prestasi anggota
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

{{-- ================= STYLE ================= --}}
<style>
.page-header{
  background:linear-gradient(135deg,#1E3A8A,#2563EB);
  border-radius:18px;
  padding:1.25rem 1.5rem;
  box-shadow:0 12px 30px rgba(30,58,138,.25);
}
.page-header-content{
  display:flex;
  flex-direction:column;
  gap:1rem;
}
@media(min-width:992px){
  .page-header-content{
    flex-direction:row;
    justify-content:space-between;
    align-items:center;
  }
}
.page-icon{
  width:48px;height:48px;border-radius:14px;
  background:rgba(255,255,255,.15);
  display:flex;align-items:center;justify-content:center;
  color:#fff;font-size:1.4rem;
}
.search-pill{
  border:1px solid #e5e7eb;border-radius:999px;
}
.avatar-circle{
  width:40px;height:40px;border-radius:999px;
  background:#1E3A8A;color:#fff;
  display:flex;align-items:center;justify-content:center;
  font-weight:700;
}
.chip{
  padding:.35rem .7rem;
  border-radius:999px;
  background:#f3f4f6;
  font-size:.75rem;
  font-weight:600;
}
.badge-status{
  padding:.35rem .75rem;
  border-radius:999px;
  font-size:.75rem;
  font-weight:700;
}
.badge-pending{background:#FEF3C7;color:#92400E;}
.badge-verified{background:#D1FAE5;color:#065F46;}
.badge-rejected{background:#FEE2E2;color:#991B1B;}
.btn-icon{
  width:38px;height:38px;border-radius:12px;
  border:1px solid #e5e7eb;background:#fff;
}
.btn-view{color:#2563eb;}
.btn-edit{color:#f59e0b;}
.btn-delete{color:#ef4444;}
</style>

{{-- ================= SCRIPT ================= --}}
<script>
const rows=[...document.querySelectorAll('.row-item')];
statusFilter.onchange=searchInput.oninput=()=>{
  rows.forEach(r=>{
    r.style.display=
      (!statusFilter.value||r.dataset.status===statusFilter.value)
      &&(!searchInput.value||r.dataset.key.includes(searchInput.value.toLowerCase()))
      ?'':'none';
  });
};
</script>
@endsection
