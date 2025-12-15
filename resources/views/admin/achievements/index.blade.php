@extends('layouts.app')

@section('content')
<div class="container py-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold mb-0 d-flex align-items-center gap-2">
            <span class="text-primary"><i class="bi bi-trophy"></i></span>
            Daftar Prestasi Peserta
        </h3>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.achievements.export.pdf') }}" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-filetype-pdf me-1"></i> Export PDF
            </a>
            <a href="{{ route('admin.achievements.export.excel') }}" class="btn btn-outline-success btn-sm">
                <i class="bi bi-filetype-xlsx me-1"></i> Export Excel
            </a>
        </div>
    </div>

    {{-- Toolbar --}}
    <div class="card border-0 shadow-sm rounded-4 mb-3">
        <div class="card-body">
            <div class="row g-2 align-items-center">
                <div class="col-12 col-md-3">
                    <select id="statusFilter" class="form-select form-select-sm rounded-pill">
                        <option value="">Semua Status</option>
                        <option value="pending">Pending</option>
                        <option value="verified">Verified</option>
                        <option value="rejected">Rejected</option>
                        <option value="approved">Approved</option>
                    </select>
                </div>

                <div class="col-12 col-md">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-transparent border-end-0">
                            <i class="bi bi-search"></i>
                        </span>
                        <input id="searchInput" type="text" class="form-control border-start-0"
                               placeholder="Cari nama peserta atau prestasi…">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel --}}
    <div class="card border-0 shadow-sm rounded-4">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="small text-muted sticky-top bg-white">
                    <tr>
                        <th class="py-3 ps-4">PESERTA</th>
                        <th>PRESTASI</th>
                        <th>TINGKAT & KATEGORI</th>
                        <th>TAHUN</th>
                        <th>POIN</th> {{-- ✅ Kolom baru --}}
                        <th>SERTIFIKAT</th>
                        <th>STATUS</th>
                        <th class="text-end pe-4">AKSI</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    @forelse($achievements as $a)
                        <tr class="row-item"
                            data-key="{{ \Illuminate\Support\Str::lower(($a->peserta ?? '').' '.($a->prestasi ?? '')) }}"
                            data-status="{{ strtolower($a->status ?? '') }}">

                            {{-- Peserta --}}
                            <td class="ps-4" style="min-width:200px;">
                                <div class="fw-semibold">{{ $a->peserta }}</div>
                            </td>

                            {{-- Prestasi --}}
                            <td style="min-width:220px;">
                                <div class="fw-semibold">{{ $a->prestasi }}</div>
                            </td>

                            {{-- Tingkat & Kategori --}}
                            <td>
                                <span class="badge rounded-pill px-3 py-2 fw-semibold bg-light text-dark">
                                    {{ $a->tingkat_kategori ?? '-' }}
                                </span>
                            </td>

                            {{-- Tahun --}}
                            <td>{{ $a->tahun ?? '-' }}</td> {{-- ✅ Fix tanpa Carbon --}}

                            {{-- Poin --}}
                            <td>{{ $a->poin ?? '-' }}</td> {{-- ✅ Kolom baru --}}

                            {{-- Sertifikat --}}
                            <td>
                                @if($a->sertifikat_path)
                                    <a href="{{ route('admin.achievements.download', $a->id) }}"
                                       class="btn btn-sm btn-download d-inline-flex align-items-center gap-2">
                                        <i class="bi bi-download"></i> Unduh
                                    </a>
                                @else
                                    <span class="text-muted small">Tidak ada file</span>
                                @endif
                            </td>

                            {{-- Status --}}
                            <td>
                                @php($s = strtolower($a->status ?? 'pending'))
                                <span class="badge rounded-pill px-3 py-2 fw-semibold
                                    {{ $s==='verified' ? 'bg-status-approved' : '' }}
                                    {{ $s==='approved' ? 'bg-status-approved' : '' }}
                                    {{ $s==='rejected' ? 'bg-status-rejected' : '' }}
                                    {{ $s==='pending'  ? 'bg-status-pending'  : '' }}">
                                    {{ ucfirst($s) }}
                                </span>
                            </td>

                            {{-- Aksi --}}
                            <td class="text-end pe-4">
                                <a href="{{ route('admin.achievements.show', $a->id) }}" class="link-primary me-3">Detail</a>
                                <a href="{{ route('admin.achievements.edit', $a->id) }}" class="link-secondary">Update</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="text-muted">Belum ada prestasi tercatat.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($achievements->hasPages())
            <div class="card-footer bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="small text-muted">
                        Menampilkan {{ $achievements->firstItem() }}–{{ $achievements->lastItem() }} dari {{ $achievements->total() }}
                    </div>
                    {{ $achievements->withQueryString()->links() }}
                </div>
            </div>
        @endif
    </div>
</div>

{{-- Styles --}}
<style>
.table > :not(caption) > * > * { padding: 1rem .75rem; }
.btn-download { background:#159a5a; color:#fff; border:none; }
.btn-download:hover { filter:brightness(.95); color:#fff; }

.bg-status-pending  { background:#FEF3C7; color:#92400E; }
.bg-status-approved { background:#D1FAE5; color:#065F46; }
.bg-status-rejected { background:#FEE2E2; color:#991B1B; }

.card { border-radius: .9rem; }
</style>

{{-- Filter JS --}}
<script>
const rows = [...document.querySelectorAll('#tableBody .row-item')];
const statusSel = document.getElementById('statusFilter');
const searchInp = document.getElementById('searchInput');

function applyFilter(){
  const q = (searchInp.value||'').trim().toLowerCase();
  const st = (statusSel.value||'').toLowerCase();
  rows.forEach(r=>{
    const key = r.dataset.key || '';
    const okStatus = !st || (r.dataset.status === st);
    const okSearch = !q || key.includes(q);
    r.style.display = (okStatus && okSearch) ? '' : 'none';
  });
}

statusSel.addEventListener('change', applyFilter);
searchInp.addEventListener('input', applyFilter);
</script>
@endsection
